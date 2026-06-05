<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ordertracking_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Récupère une commande par son numéro
     */
    public function getCommandeByNumero($numero_commande) {
        $this->db->select('c.*, mp.description as mode_paiement_desc, mp.code as mode_paiement_code, 
                          mp.type as mode_paiement_type, u.prenom, u.nom, u.email, u.telephone,
                          t.nom as transporteur_nom, t.telephone as transporteur_telephone,
                          t.whatsapp as transporteur_whatsapp, t.type_vehicule, t.note_moyenne');
        $this->db->from('commandes c');
        $this->db->join('mode_payement mp', 'c.id_mode_payement = mp.id_mode_payement', 'left');
        $this->db->join('utilisateurs u', 'c.id_utilisateur = u.id_utilisateur', 'left');
        $this->db->join('transporteurs t', 'c.id_transporteur = t.id_transporteur', 'left');
        $this->db->where('c.numero_commande', $numero_commande);
        
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * Récupère les articles d'une commande
     */
    public function getArticlesCommande($id_commande) {
        $this->db->select('ac.*, i.url_image as image_url, 
                          p.nom_produit, p.marque, p.prix_base,
                          v.attributs_variante');
        $this->db->from('articles_commande ac');
        $this->db->join('produits p', 'ac.id_produit = p.id_produit', 'left');
        $this->db->join('variantes_produit v', 'ac.id_variante = v.id_variante', 'left');
        $this->db->join('images_produit i', 'ac.id_produit = i.id_produit AND i.est_principale = 1', 'left');
        $this->db->where('ac.id_commande', $id_commande);
        $this->db->group_by('ac.id_article');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère l'historique des statuts de la commande (timeline)
     */
    public function getHistoriqueStatuts($id_commande) {
        $this->db->select('h.*, u.prenom, u.nom');
        $this->db->from('historique_statut_commande h');
        $this->db->join('utilisateurs u', 'h.modifie_par = u.id_utilisateur', 'left');
        $this->db->where('h.id_commande', $id_commande);
        $this->db->order_by('h.date_creation', 'DESC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère l'adresse de livraison
     */
    public function getAdresse($id_adresse) {
        $this->db->select('a.*, p.province_name, c.commune_name');
        $this->db->from('adresses a');
        $this->db->join('provinces p', 'a.id_province = p.id_province', 'left');
        $this->db->join('communes c', 'a.id_commune = c.id_commune', 'left');
        $this->db->where('a.id_adresse', $id_adresse);
        
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * Récupère le QR code de la commande
     */
    public function getQrCode($id_commande) {
        $this->db->from('qr_confirmations');
        $this->db->where('id_commande', $id_commande);
        $this->db->order_by('id_qr', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * Récupère les positions GPS du livreur
     */
    public function getPositionsGPS($id_commande) {
        $this->db->select('latitude, longitude, timestamp_gps, vitesse_kmh');
        $this->db->from('suivi_gps');
        $this->db->where('id_commande', $id_commande);
        $this->db->order_by('timestamp_gps', 'DESC');
        $this->db->limit(50);
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Met à jour le statut d'une commande
     */
    public function updateOrderStatus($id_commande, $statut, $commentaire, $modifie_par) {
        $this->db->trans_start();
        
        // Mettre à jour la commande
        $this->db->where('id_commande', $id_commande);
        $this->db->update('commandes', ['statut_commande' => $statut]);
        
        // Ajouter dans l'historique
        $historique_data = [
            'id_commande' => $id_commande,
            'statut' => $statut,
            'commentaire' => $commentaire,
            'modifie_par' => $modifie_par
        ];
        $this->db->insert('historique_statut_commande', $historique_data);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'message' => 'Erreur lors de la mise à jour'];
        }
        
        return ['success' => true, 'message' => 'Statut mis à jour'];
    }
    
    /**
     * Confirme la livraison par QR code
     */
    public function confirmDeliveryByQR($token, $modifie_par) {
        $token_hash = hash('sha256', $token);
        
        $this->db->trans_start();
        
        // Vérifier le QR code
        $this->db->select('q.*, c.id_commande');
        $this->db->from('qr_confirmations q');
        $this->db->join('commandes c', 'q.id_commande = c.id_commande');
        $this->db->where('q.token_hash', $token_hash);
        $this->db->where('q.est_utilise', 0);
        $this->db->where('q.date_expiration >', date('Y-m-d H:i:s'));
        
        $query = $this->db->get();
        $qr = $query->row_array();
        
        if (!$qr) {
            return ['success' => false, 'message' => 'QR code invalide ou expiré'];
        }
        
        // Marquer le QR comme utilisé
        $this->db->where('id_qr', $qr['id_qr']);
        $this->db->update('qr_confirmations', [
            'est_utilise' => 1,
            'date_utilisation' => date('Y-m-d H:i:s')
        ]);
        
        // Mettre à jour la commande
        $this->db->where('id_commande', $qr['id_commande']);
        $this->db->update('commandes', [
            'statut_commande' => 'livre',
            'date_livraison_reelle' => date('Y-m-d H:i:s'),
            'reception_confirmee' => 1
        ]);
        
        // Ajouter dans l'historique
        $this->db->insert('historique_statut_commande', [
            'id_commande' => $qr['id_commande'],
            'statut' => 'livre',
            'commentaire' => 'Livraison confirmée par QR code',
            'modifie_par' => $modifie_par
        ]);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return ['success' => false, 'message' => 'Erreur lors de la confirmation'];
        }
        
        return ['success' => true, 'message' => 'Livraison confirmée avec succès'];
    }
    
    /**
     * Génère le PDF de facture (simulation)
     */
    public function generateInvoice($id_commande) {
        // Logique de génération PDF
        return true;
    }
}