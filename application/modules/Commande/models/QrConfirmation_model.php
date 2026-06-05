<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QrConfirmation_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('string');
    }

    public function generer_token($id_commande) {
        // Vérifier si un QR existe déjà
        $existing = $this->db->where('id_commande', $id_commande)->get('qr_confirmations')->row();
        
        if ($existing) {
            // Supprimer l'ancien fichier
            if (!empty($existing->qr_image_url) && file_exists(FCPATH . $existing->qr_image_url)) {
                unlink(FCPATH . $existing->qr_image_url);
            }
            $this->db->where('id_commande', $id_commande)->delete('qr_confirmations');
        }
        
        // Générer un nouveau token
        $token = random_string('alnum', 32);
        $token_hash = hash('sha256', $token);
        
        // Créer le dossier
        $qr_folder = FCPATH . 'uploads/qr_codes/';
        if (!is_dir($qr_folder)) {
            mkdir($qr_folder, 0777, true);
        }
        
        // Générer l'image QR
        $filename = 'qr_' . $id_commande . '_' . date('YmdHis') . '.png';
        $filepath = $qr_folder . $filename;
        $url = base_url('qr/verify/' . $token);
        
        $qr_content = @file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($url));
        if (!$qr_content) {
            $qr_content = @file_get_contents("https://quickchart.io/qr?text=" . urlencode($url) . "&size=250");
        }
        
        $qr_image_url = null;
        if ($qr_content) {
            file_put_contents($filepath, $qr_content);
            $qr_image_url = 'uploads/qr_codes/' . $filename;
        }
        
        $data = [
            'id_commande' => $id_commande,
            'token' => $token,
            'token_hash' => $token_hash,
            'qr_image_url' => $qr_image_url,
            'date_expiration' => date('Y-m-d H:i:s', strtotime('+7 days')),
            'est_utilise' => 0,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('qr_confirmations', $data);
        $this->db->where('id_commande', $id_commande)->update('commandes', ['qr_token' => $token]);
        
        return $data;
    }

    public function get_qr_by_commande($id_commande) {
        $qr = $this->db->where('id_commande', $id_commande)
                       ->order_by('id_qr', 'DESC')
                       ->get('qr_confirmations')
                       ->row();
        
        if ($qr && !empty($qr->qr_image_url) && !file_exists(FCPATH . $qr->qr_image_url)) {
            $qr->qr_image_url = null;
        }
        
        return $qr;
    }

    public function verifier_token($token) {
        $token_hash = hash('sha256', $token);
        $qr = $this->db->where('token_hash', $token_hash)
                       ->where('est_utilise', 0)
                       ->where('date_expiration >', date('Y-m-d H:i:s'))
                       ->get('qr_confirmations')
                       ->row();
        
        if (!$qr) {
            return ['success' => false, 'message' => 'QR code invalide ou expiré'];
        }
        
        $commande = $this->db->select('c.*, u.prenom, u.nom, u.telephone')
                             ->from('commandes c')
                             ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                             ->where('c.id_commande', $qr->id_commande)
                             ->get()
                             ->row();
        
        return ['success' => true, 'qr' => $qr, 'commande' => $commande];
    }

    public function confirmer_livraison($token, $latitude, $longitude, $photo_url = null, $id_transporteur = null) {
        $token_hash = hash('sha256', $token);
        $qr = $this->db->where('token_hash', $token_hash)
                       ->where('est_utilise', 0)
                       ->where('date_expiration >', date('Y-m-d H:i:s'))
                       ->get('qr_confirmations')
                       ->row();
        
        if (!$qr) {
            return ['success' => false, 'message' => 'QR code invalide ou expiré'];
        }
        
        $this->db->where('id_qr', $qr->id_qr)->update('qr_confirmations', [
            'est_utilise' => 1,
            'date_utilisation' => date('Y-m-d H:i:s'),
            'latitude_scan' => $latitude,
            'longitude_scan' => $longitude,
            'id_transporteur' => $id_transporteur,
            'photo_livraison_url' => $photo_url
        ]);
        
        $this->db->where('id_commande', $qr->id_commande)->update('commandes', [
            'statut_commande' => 'livre',
            'date_livraison_reelle' => date('Y-m-d H:i:s'),
            'reception_confirmee' => 1,
            'date_confirmation_reception' => date('Y-m-d H:i:s')
        ]);
        
        $this->db->insert('historique_statut_commande', [
            'id_commande' => $qr->id_commande,
            'statut' => 'livre',
            'commentaire' => 'Livraison confirmée par QR code',
            'modifie_par' => $id_transporteur,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'date_creation' => date('Y-m-d H:i:s')
        ]);
        
        return ['success' => true, 'message' => 'Livraison confirmée avec succès'];
    }

    public function get_stats() {
        return $this->db->select('
            COUNT(*) as total_qr,
            SUM(CASE WHEN est_utilise = 1 THEN 1 ELSE 0 END) as utilises,
            SUM(CASE WHEN est_utilise = 0 AND date_expiration > NOW() THEN 1 ELSE 0 END) as actifs,
            SUM(CASE WHEN est_utilise = 0 AND date_expiration <= NOW() THEN 1 ELSE 0 END) as expires
        ')->get('qr_confirmations')->row();
    }
}
?>