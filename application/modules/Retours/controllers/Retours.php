<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retours extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Retours_model');
        $this->load->model('Commande_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Créer le dossier d'upload
        $upload_path = FCPATH . 'uploads/retours/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
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

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    /**
     * Liste des demandes de retour (Admin)
     */
    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des retours et remboursements';
        
        $filters = [
            'statut' => $this->input->get('statut'),
            'motif' => $this->input->get('motif'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $config['base_url'] = base_url('retours/index');
        $config['total_rows'] = $this->Retours_model->count_all($filters);
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<ul class="pagination justify-content-end mb-0">';
        $config['full_tag_close'] = '</ul>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="javascript:void(0);">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['reuse_query_string'] = true;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['retours'] = $this->Retours_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->Retours_model->get_stats();
        $data['filters'] = $filters;
        
        $this->load->view('retours_list', $data);
    }

    /**
     * Mes demandes de retour (Client)
     */
    public function mes_demandes() {
        $user_id = $this->session->userdata('id_utilisateur');
        
        $data['title'] = 'Mes demandes de retour';
        $data['retours'] = $this->Retours_model->get_by_utilisateur($user_id);
        $data['stats'] = $this->Retours_model->get_stats_by_utilisateur($user_id);
        
        $this->load->view('retours_client', $data);
    }

    /**
     * Détail d'une demande de retour
     */
    public function detail($id) {
        $data['retour'] = $this->Retours_model->get_by_id($id);
        
        if (!$data['retour']) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        $is_admin = $this->is_admin();
        
        if (!$is_admin && $data['retour']->id_utilisateur != $user_id) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['commande'] = $this->Commande_model->get_by_id($data['retour']->id_commande);
        $data['article'] = $this->Retours_model->get_article_by_id($data['retour']->id_article);
        $data['is_admin'] = $is_admin;
        $data['title'] = 'Détails de la demande de retour #' . $id;
        
        $this->load->view('retours_detail', $data);
    }

    /**
     * Demander un retour (Client)
     */
    public function demander() {
        $this->output->set_content_type('application/json');
        
        $id_commande = $this->input->post('id_commande');
        $id_article = $this->input->post('id_article');
        $type = $this->input->post('type');
        $motif = $this->input->post('motif');
        $description = $this->input->post('description');
        $montant_demande = $this->input->post('montant_demande');
        
        if (empty($id_commande) || empty($motif) || empty($montant_demande)) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
            return;
        }
        
        // Upload des photos
        $photos_urls = [];
        if (!empty($_FILES['photos']['name'][0])) {
            for ($i = 0; $i < count($_FILES['photos']['name']); $i++) {
                if ($_FILES['photos']['error'][$i] == UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION);
                    $filename = 'retour_' . date('Ymd_His') . '_' . uniqid() . '.' . strtolower($ext);
                    $upload_path = FCPATH . 'uploads/retours/';
                    
                    if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], $upload_path . $filename)) {
                        $photos_urls[] = 'uploads/retours/' . $filename;
                    }
                }
            }
        }
        
        $data = [
            'id_commande' => $id_commande,
            'id_article' => $id_article ?: null,
            'id_utilisateur' => $this->session->userdata('id_utilisateur'),
            'type' => $type ?: 'retour_produit',
            'motif' => $motif,
            'description' => $description,
            'photos_urls' => !empty($photos_urls) ? json_encode($photos_urls) : null,
            'montant_demande' => $montant_demande,
            'statut' => 'demande',
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $id_retour = $this->Retours_model->demander($data);
        
        if ($id_retour) {
            echo json_encode(['success' => true, 'message' => 'Demande de retour envoyée avec succès', 'id' => $id_retour]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'envoi de la demande']);
        }
    }

    /**
     * Traiter une demande de retour (Admin)
     */
    public function traiter($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $statut = $this->input->post('statut');
        $montant_approuve = $this->input->post('montant_approuve');
        $motif_refus = $this->input->post('motif_refus');
        $methode_remboursement = $this->input->post('methode_remboursement');
        
        if (empty($statut)) {
            echo json_encode(['success' => false, 'message' => 'Statut manquant']);
            return;
        }
        
        $update_data = [
            'statut' => $statut,
            'date_traitement' => date('Y-m-d H:i:s'),
            'traite_par' => $this->session->userdata('id_utilisateur')
        ];
        
        if ($statut == 'approuve' && $montant_approuve) {
            $update_data['montant_approuve'] = $montant_approuve;
            $update_data['methode_remboursement'] = $methode_remboursement;
        }
        
        if ($statut == 'refuse' && $motif_refus) {
            $update_data['motif_refus'] = $motif_refus;
        }
        
        if ($statut == 'rembourse') {
            $update_data['date_remboursement'] = date('Y-m-d H:i:s');
        }
        
        $updated = $this->Retours_model->update($id, $update_data);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Demande traitée avec succès' : 'Erreur'
        ]);
    }

    /**
     * Supprimer une demande (Admin)
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $retour = $this->Retours_model->get_by_id($id);
        
        if ($retour && !empty($retour->photos_urls)) {
            $photos = json_decode($retour->photos_urls, true);
            if (is_array($photos)) {
                foreach ($photos as $photo) {
                    if (file_exists(FCPATH . $photo)) {
                        unlink(FCPATH . $photo);
                    }
                }
            }
        }
        
        $deleted = $this->Retours_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Demande supprimée' : 'Erreur'
        ]);
    }

    /**
     * Exporter les demandes (Admin)
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'statut' => $this->input->get('statut'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $retours = $this->Retours_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=retours_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Commande', 'Client', 'Type', 'Motif', 'Montant demandé', 'Montant approuvé', 'Statut', 'Date demande', 'Date traitement']);
        
        foreach ($retours as $r) {
            fputcsv($output, [
                $r->id_retour,
                $r->numero_commande,
                $r->client_nom,
                $r->type,
                $r->motif,
                number_format($r->montant_demande, 0, ',', ' ') . ' FBu',
                $r->montant_approuve ? number_format($r->montant_approuve, 0, ',', ' ') . ' FBu' : '-',
                $r->statut,
                date('d/m/Y', strtotime($r->date_creation)),
                $r->date_traitement ? date('d/m/Y', strtotime($r->date_traitement)) : '-'
            ]);
        }
        
        fclose($output);
    }
}
?>