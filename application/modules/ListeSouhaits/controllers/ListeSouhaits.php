<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListeSouhaits extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ListeSouhaits_model');
        $this->load->model('Produits_model');
        $this->load->library('pagination');
        
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

    private function get_user_id() {
        return $this->session->userdata('id_utilisateur');
    }

    /**
     * Liste des souhaits d'un utilisateur
     */
    public function index() {
        $user_id = $this->get_user_id();
        
        $data['title'] = 'Ma liste de souhaits';
        $data['souhaits'] = $this->ListeSouhaits_model->get_by_user($user_id);
        $data['total_souhaits'] = $this->ListeSouhaits_model->count_by_user($user_id);
        
        $this->load->view('liste_souhaits_list', $data);
    }

    /**
     * Liste admin de tous les souhaits
     */
    public function admin_list() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des listes de souhaits';
        
        $filters = [
            'id_utilisateur' => $this->input->get('id_utilisateur'),
            'id_produit' => $this->input->get('id_produit'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $config['base_url'] = base_url('liste-souhaits/admin-list');
        $config['total_rows'] = $this->ListeSouhaits_model->count_all($filters);
        $config['per_page'] = 30;
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
        
        $data['souhaits'] = $this->ListeSouhaits_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->ListeSouhaits_model->get_stats($filters);
        $data['utilisateurs'] = $this->db->select('id_utilisateur, prenom, nom, email')->get('utilisateurs')->result();
        $data['filters'] = $filters;
        
        $this->load->view('liste_souhaits_admin_list', $data);
    }

    /**
     * Ajouter un produit à la liste de souhaits
     */
    public function ajouter() {
        $this->output->set_content_type('application/json');
        
        $id_produit = $this->input->post('id_produit');
        $user_id = $this->get_user_id();
        
        if (!$id_produit) {
            echo json_encode(['success' => false, 'message' => 'Produit non spécifié']);
            return;
        }
        
        // Vérifier si le produit existe
        $produit = $this->Produits_model->get_by_id($id_produit);
        if (!$produit) {
            echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
            return;
        }
        
        // Vérifier si déjà dans la liste
        if ($this->ListeSouhaits_model->existe($user_id, $id_produit)) {
            echo json_encode(['success' => false, 'message' => 'Produit déjà dans votre liste de souhaits']);
            return;
        }
        
        $data = [
            'id_utilisateur' => $user_id,
            'id_produit' => $id_produit,
            'date_ajout' => date('Y-m-d H:i:s')
        ];
        
        $inserted = $this->ListeSouhaits_model->ajouter($data);
        
        if ($inserted) {
            echo json_encode(['success' => true, 'message' => 'Produit ajouté à votre liste de souhaits']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
        }
    }

    /**
     * Supprimer un produit de la liste de souhaits
     */
    public function supprimer($id_souhait) {
        $user_id = $this->get_user_id();
        
        // Vérifier que le souhait appartient à l'utilisateur
        $souhait = $this->ListeSouhaits_model->get_by_id($id_souhait);
        if (!$souhait || $souhait->id_utilisateur != $user_id) {
            if (!$this->is_admin()) {
                show_error('Accès non autorisé', 403);
            }
        }
        
        $this->ListeSouhaits_model->supprimer($id_souhait);
        $this->session->set_flashdata('success', 'Produit retiré de votre liste de souhaits');
        redirect('liste-souhaits');
    }

    /**
     * Supprimer un produit (API)
     */
    public function supprimer_api() {
        $this->output->set_content_type('application/json');
        
        $id_souhait = $this->input->post('id_souhait');
        $id_produit = $this->input->post('id_produit');
        $user_id = $this->get_user_id();
        
        if ($id_souhait) {
            $souhait = $this->ListeSouhaits_model->get_by_id($id_souhait);
            if ($souhait && $souhait->id_utilisateur == $user_id) {
                $this->ListeSouhaits_model->supprimer($id_souhait);
                echo json_encode(['success' => true, 'message' => 'Produit retiré']);
                return;
            }
        }
        
        if ($id_produit) {
            $this->ListeSouhaits_model->supprimer_by_produit_user($id_produit, $user_id);
            echo json_encode(['success' => true, 'message' => 'Produit retiré']);
            return;
        }
        
        echo json_encode(['success' => false, 'message' => 'Erreur']);
    }

    /**
     * Vider la liste de souhaits
     */
    public function vider() {
        $user_id = $this->get_user_id();
        $this->ListeSouhaits_model->vider($user_id);
        $this->session->set_flashdata('success', 'Liste de souhaits vidée');
        redirect('liste-souhaits');
    }

    /**
     * Détail d'un souhait (Admin)
     */
    public function detail($id_souhait) {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['souhait'] = $this->ListeSouhaits_model->get_by_id_with_details($id_souhait);
        
        if (!$data['souhait']) {
            show_404();
        }
        
        $data['title'] = 'Détail du souhait #' . $id_souhait;
        
        $this->load->view('liste_souhaits_detail', $data);
    }

    /**
     * Exporter les données
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'id_utilisateur' => $this->input->get('id_utilisateur'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $souhaits = $this->ListeSouhaits_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=liste_souhaits_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Utilisateur', 'Email', 'Produit', 'Date ajout']);
        
        foreach ($souhaits as $s) {
            fputcsv($output, [
                $s->id_souhait,
                $s->utilisateur_nom,
                $s->utilisateur_email,
                $s->produit_nom,
                date('d/m/Y H:i:s', strtotime($s->date_ajout))
            ]);
        }
        
        fclose($output);
    }
}
?>