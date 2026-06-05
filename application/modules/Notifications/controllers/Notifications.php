<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Notifications_model');
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

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    /**
     * Liste des notifications (Admin)
     */
    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des notifications';
        
        $filters = [
            'categorie' => $this->input->get('categorie'),
            'est_lue' => $this->input->get('est_lue'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin'),
            'search' => $this->input->get('search')
        ];
        
        $config['base_url'] = base_url('notifications/index');
        $config['total_rows'] = $this->Notifications_model->count_all($filters);
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
        
        $data['notifications'] = $this->Notifications_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->Notifications_model->get_stats($filters);
        $data['filters'] = $filters;
        
        $this->load->view('notifications_list', $data);
    }

    /**
     * Mes notifications (utilisateur connecté)
     */
    public function mes_notifications() {
        $user_id = $this->session->userdata('id_utilisateur');
        
        $data['title'] = 'Mes notifications';
        $data['notifications'] = $this->Notifications_model->get_by_utilisateur($user_id);
        $data['stats'] = $this->Notifications_model->get_stats_by_utilisateur($user_id);
        
        $this->load->view('notifications_user', $data);
    }

    /**
     * Détail d'une notification
     */
    public function detail($id) {
        $data['notification'] = $this->Notifications_model->get_by_id($id);
        
        if (!$data['notification']) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        $is_admin = $this->is_admin();
        
        if (!$is_admin && $data['notification']->id_utilisateur != $user_id) {
            show_error('Accès non autorisé', 403);
        }
        
        // Marquer comme lue
        if (!$data['notification']->est_lue) {
            $this->Notifications_model->marquer_lue($id);
            $data['notification']->est_lue = 1;
        }
        
        $data['title'] = 'Détails de la notification';
        $data['is_admin'] = $is_admin;
        
        $this->load->view('notifications_detail', $data);
    }

    /**
     * Créer une notification (Admin)
     */
    /**
 * Créer une notification (Admin)
 */
public function create() {
    if (!$this->is_admin()) {
        show_error('Accès non autorisé', 403);
    }
    
    // Charger la bibliothèque form_validation
    $this->load->library('form_validation');
    
    $data['title'] = 'Créer une notification';
    $data['utilisateurs'] = $this->db->get('utilisateurs')->result();
    
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $this->form_validation->set_rules('titre', 'Titre', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');
        $this->form_validation->set_rules('categorie', 'Catégorie', 'required');
        
        if ($this->form_validation->run() == true) {
            $id_utilisateur = $this->input->post('id_utilisateur');
            $type_canal = $this->input->post('type_canal');
            $categorie = $this->input->post('categorie');
            $titre = $this->input->post('titre');
            $message = $this->input->post('message');
            
            if ($id_utilisateur == 'all') {
                // Envoyer à tous les utilisateurs
                $users = $this->db->get('utilisateurs')->result();
                $count = 0;
                foreach ($users as $user) {
                    $this->Notifications_model->creer($user->id_utilisateur, $type_canal, $categorie, $titre, $message);
                    $count++;
                }
                $this->session->set_flashdata('success', 'Notification envoyée à ' . $count . ' utilisateurs');
            } else {
                $this->Notifications_model->creer($id_utilisateur, $type_canal, $categorie, $titre, $message);
                $this->session->set_flashdata('success', 'Notification créée avec succès');
            }
            
            redirect('notifications');
        }
    }
    
    $this->load->view('notifications_create', $data);
}

    /**
     * Marquer comme lue (AJAX)
     */
    public function marquer_lue($id) {
        $this->output->set_content_type('application/json');
        
        $notification = $this->Notifications_model->get_by_id($id);
        
        if (!$notification) {
            echo json_encode(['success' => false, 'message' => 'Notification non trouvée']);
            return;
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        if (!$this->is_admin() && $notification->id_utilisateur != $user_id) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $updated = $this->Notifications_model->marquer_lue($id);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Notification marquée comme lue' : 'Erreur'
        ]);
    }

    /**
     * Marquer toutes comme lues (AJAX)
     */
    public function marquer_toutes_lues() {
        $this->output->set_content_type('application/json');
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        $updated = $this->Notifications_model->marquer_toutes_lues($user_id);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Toutes les notifications marquées comme lues' : 'Erreur'
        ]);
    }

    /**
     * Supprimer une notification (Admin)
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $deleted = $this->Notifications_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Notification supprimée' : 'Erreur'
        ]);
    }

    /**
     * Supprimer toutes les notifications d'un utilisateur
     */
    public function supprimer_toutes() {
        $this->output->set_content_type('application/json');
        
        $user_id = $this->session->userdata('id_utilisateur');
        $is_admin = $this->is_admin();
        
        if (!$is_admin) {
            $deleted = $this->Notifications_model->supprimer_toutes_utilisateur($user_id);
        } else {
            $id_utilisateur = $this->input->post('id_utilisateur');
            if ($id_utilisateur) {
                $deleted = $this->Notifications_model->supprimer_toutes_utilisateur($id_utilisateur);
            } else {
                $deleted = $this->Notifications_model->supprimer_toutes();
            }
        }
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Notifications supprimées' : 'Erreur'
        ]);
    }

    /**
     * Compter les notifications non lues (API)
     */
    public function count_non_lues() {
        $this->output->set_content_type('application/json');
        
        $user_id = $this->session->userdata('id_utilisateur');
        $count = $this->Notifications_model->count_non_lues($user_id);
        
        echo json_encode(['count' => $count]);
    }

    /**
     * Exporter les notifications (Admin)
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'categorie' => $this->input->get('categorie'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $notifications = $this->Notifications_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=notifications_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Utilisateur', 'Email', 'Canal', 'Catégorie', 'Titre', 'Message', 'Lue', 'Date création']);
        
        foreach ($notifications as $n) {
            fputcsv($output, [
                $n->id_notification,
                $n->utilisateur_nom,
                $n->email,
                $n->type_canal,
                $n->categorie,
                $n->titre,
                $n->message,
                $n->est_lue ? 'Oui' : 'Non',
                date('d/m/Y H:i', strtotime($n->date_creation))
            ]);
        }
        
        fclose($output);
    }
}
?>