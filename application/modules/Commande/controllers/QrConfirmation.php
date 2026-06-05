<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QrConfirmation extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('QrConfirmation_model');
        $this->load->model('Commande_model');
        $this->load->library('form_validation');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2, 3]);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_transporteur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $transporteur = $this->db->where('id_utilisateur', $user_id)->get('transporteurs')->row();
        return $transporteur ? $transporteur->id_transporteur : null;
    }

    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des QR codes de livraison';
        
        $this->db->select('q.*, c.numero_commande, c.montant_total, u.prenom, u.nom')
                 ->from('qr_confirmations q')
                 ->join('commandes c', 'q.id_commande = c.id_commande')
                 ->join('utilisateurs u', 'c.id_utilisateur = u.id_utilisateur')
                 ->order_by('q.date_creation', 'DESC')
                 ->limit(100);
        
        $data['qr_codes'] = $this->db->get()->result();
        $data['stats'] = $this->QrConfirmation_model->get_stats();
        
        $this->load->view('qr_list', $data);
    }

    public function generate($id_commande) {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $commande = $this->Commande_model->get_by_id($id_commande);
        
        if (!$commande) {
            show_404();
        }
        
        $qr_data = $this->QrConfirmation_model->generer_token($id_commande);
        
        if ($qr_data) {
            $this->session->set_flashdata('success', 'QR code généré avec succès');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la génération du QR code');
        }
        
        redirect('Commande/detail/' . $id_commande);
    }

    public function view($id_commande) {
        $is_admin = $this->is_admin();
        $is_livreur = !$is_admin && $this->get_transporteur_id() ? true : false;
        
        if (!$is_admin && !$is_livreur) {
            show_error('Accès non autorisé', 403);
        }
        
        $commande = $this->Commande_model->get_by_id($id_commande);
        
        if (!$commande) {
            show_404();
        }
        
        $data['commande'] = $commande;
        $data['qr'] = $this->QrConfirmation_model->get_qr_by_commande($id_commande);
        
        if (!$data['qr']) {
            $this->QrConfirmation_model->generer_token($id_commande);
            $data['qr'] = $this->QrConfirmation_model->get_qr_by_commande($id_commande);
        }
        
        $data['title'] = 'QR Code - Commande #' . $commande->numero_commande;
        $data['is_admin'] = $is_admin;
        $data['is_livreur'] = $is_livreur;
        
        $this->load->view('qr_view', $data);
    }

    public function verify($token) {
        $verification = $this->QrConfirmation_model->verifier_token($token);
        
        if (!$verification['success']) {
            $data['error'] = $verification['message'];
            $this->load->view('qr_error', $data);
            return;
        }
        
        $data['qr'] = $verification['qr'];
        $data['commande'] = $verification['commande'];
        $data['title'] = 'Confirmation de livraison';
        
        $this->load->view('qr_verify', $data);
    }

    public function confirm() {
        $this->output->set_content_type('application/json');
        
        $token = $this->input->post('token');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $id_transporteur = $this->input->post('id_transporteur');
        
        $photo_url = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $upload_path = FCPATH . 'uploads/livraisons/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = 'livraison_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path . $filename)) {
                $photo_url = 'uploads/livraisons/' . $filename;
            }
        }
        
        $result = $this->QrConfirmation_model->confirmer_livraison($token, $latitude, $longitude, $photo_url, $id_transporteur);
        
        echo json_encode($result);
    }

    public function regenerate($id_commande) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $qr_data = $this->QrConfirmation_model->generer_token($id_commande);
        
        if ($qr_data) {
            echo json_encode(['success' => true, 'message' => 'QR code régénéré avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la régénération']);
        }
    }

    public function merci() {
        $data['title'] = 'Merci - Confirmation de livraison';
        $this->load->view('qr_thanks', $data);
    }
}
?>