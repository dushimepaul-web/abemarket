<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LitigeCommande extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('LitigeCommande_model');
        $this->load->model('Commande_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2]);
        return $this->db->get()->num_rows() > 0;
    }
    
    /**
     * Liste des litiges
     */
    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des litiges';
        
        // Filtres
        $filters = [
            'statut' => $this->input->get('statut'),
            'type_plaignant' => $this->input->get('type_plaignant'),
            'raison' => $this->input->get('raison'),
            'search' => $this->input->get('search')
        ];
        
        // Configuration de la pagination
        $config['base_url'] = base_url('LitigeCommande/index');
        $config['total_rows'] = count($this->LitigeCommande_model->get_all_litiges(null, null, $filters));
        $config['per_page'] = 15;
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
        
        $data['litiges'] = $this->LitigeCommande_model->get_all_litiges(
            $config['per_page'],
            $this->uri->segment(3),
            $filters
        );
        
        $data['stats'] = $this->LitigeCommande_model->count_litiges_by_statut();
        $data['raisons_stats'] = $this->LitigeCommande_model->count_litiges_by_raison();
        $data['statuts'] = $this->LitigeCommande_model->get_statuts_options();
        $data['raisons'] = $this->LitigeCommande_model->get_raisons_options();
        $data['filters'] = $filters;
        
        // Récupérer les médiateurs possibles
        $this->db->select('u.id_utilisateur, u.prenom, u.nom')
                 ->from('utilisateur_profils up')
                 ->join('utilisateurs u', 'up.id_utilisateur = u.id_utilisateur')
                 ->where('up.id_profil', 8); // profil support
        $data['mediateurs'] = $this->db->get()->result();
        
        $this->load->view('litige_list', $data);
    }
    
    /**
     * Détails d'un litige
     */
    public function detail($id) {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['litige'] = $this->LitigeCommande_model->get_litige_by_id($id);
        
        if (!$data['litige']) {
            show_404();
        }
        
        $data['title'] = 'Détails du litige #' . $data['litige']->id_litige;
        $data['statuts'] = $this->LitigeCommande_model->get_statuts_options();
        $data['raisons'] = $this->LitigeCommande_model->get_raisons_options();
        
        // Récupérer les articles concernés par la commande
        $this->load->model('ArticleCommande_model');
        $data['articles'] = $this->ArticleCommande_model->get_articles_by_commande($data['litige']->id_commande);
        
        // Récupérer les médiateurs possibles
        $this->db->select('u.id_utilisateur, u.prenom, u.nom')
                 ->from('utilisateur_profils up')
                 ->join('utilisateurs u', 'up.id_utilisateur = u.id_utilisateur')
                 ->where('up.id_profil', 8);
        $data['mediateurs'] = $this->db->get()->result();
        
        $this->load->view('litige/detail', $data);
    }
    
    /**
     * Modifier un litige
     */
    public function edit($id) {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['litige'] = $this->LitigeCommande_model->get_litige_by_id($id);
        
        if (!$data['litige']) {
            show_404();
        }
        
        $data['title'] = 'Modifier le litige #' . $data['litige']->id_litige;
        $data['statuts'] = $this->LitigeCommande_model->get_statuts_options();
        $data['raisons'] = $this->LitigeCommande_model->get_raisons_options();
        
        $this->load->view('litige/edit', $data);
    }
    
    /**
     * Mettre à jour un litige
     */
    public function update($id) {
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $litige = $this->LitigeCommande_model->get_litige_by_id($id);
        
        if (!$litige) {
            echo json_encode(['success' => false, 'message' => 'Litige non trouvé']);
            return;
        }
        
        $data = [
            'decision' => $this->input->post('decision'),
            'montant_rembourse' => $this->input->post('montant_rembourse')
        ];
        
        // Supprimer les champs vides
        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }
        
        $updated = $this->LitigeCommande_model->update_litige($id, $data);
        
        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Litige mis à jour avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Aucune modification effectuée']);
        }
    }
    
    /**
     * Changer le statut d'un litige
     */
    public function change_statut() {
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $id_litige = $this->input->post('id_litige');
        $statut = $this->input->post('statut');
        $decision = $this->input->post('decision');
        $montant_rembourse = $this->input->post('montant_rembourse');
        
        $updated = $this->LitigeCommande_model->update_statut($id_litige, $statut, $decision, $montant_rembourse);
        
        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }
    
    /**
     * Assigner un médiateur
     */
    public function assigner_mediateur() {
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $id_litige = $this->input->post('id_litige');
        $id_mediateur = $this->input->post('id_mediateur');
        
        $updated = $this->LitigeCommande_model->assigner_mediateur($id_litige, $id_mediateur);
        
        if ($updated) {
            echo json_encode(['success' => true, 'message' => 'Médiateur assigné avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'assignation']);
        }
    }
    
    /**
     * Créer un nouveau litige (depuis une commande)
     */
    public function creer() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['title'] = 'Nouveau litige';
        $data['commandes'] = $this->Commande_model->get_all_commandes();
        $data['raisons'] = $this->LitigeCommande_model->get_raisons_options();
        
        $this->load->view('litige/create', $data);
    }
    
    /**
     * Enregistrer un nouveau litige
     */
    public function save() {
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $this->form_validation->set_rules('id_commande', 'Commande', 'required');
        $this->form_validation->set_rules('raison', 'Raison', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        
        if ($this->form_validation->run() == false) {
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }
        
        $commande = $this->Commande_model->get_commande_by_id($this->input->post('id_commande'));
        
        if (!$commande) {
            echo json_encode(['success' => false, 'message' => 'Commande non trouvée']);
            return;
        }
        
        $data = [
            'id_commande' => $this->input->post('id_commande'),
            'id_plaignant' => $this->input->post('id_plaignant') ?: $commande->id_utilisateur,
            'type_plaignant' => $this->input->post('type_plaignant'),
            'id_defendeur' => $this->input->post('id_defendeur'),
            'raison' => $this->input->post('raison'),
            'description' => $this->input->post('description'),
            'statut' => 'ouvert',
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $id_litige = $this->LitigeCommande_model->creer_litige($data);
        
        if ($id_litige) {
            echo json_encode(['success' => true, 'message' => 'Litige créé avec succès', 'id' => $id_litige]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
        }
    }
    
    /**
     * Exporter les litiges en CSV
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'statut' => $this->input->get('statut'),
            'type_plaignant' => $this->input->get('type_plaignant'),
            'raison' => $this->input->get('raison')
        ];
        
        $data = $this->LitigeCommande_model->exporter_litiges($filters);
        
        $this->load->helper('download');
        
        $csv = '';
        foreach ($data as $row) {
            $csv .= '"' . implode('","', array_map('addslashes', $row)) . '"' . "\n";
        }
        
        force_download('litiges_' . date('Y-m-d') . '.csv', $csv);
    }

    /**
 * Supprimer un litige
 */
public function delete($id) {
    if (!$this->is_admin()) {
        echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
        return;
    }
    
    $litige = $this->LitigeCommande_model->get_litige_by_id($id);
    
    if (!$litige) {
        echo json_encode(['success' => false, 'message' => 'Litige non trouvé']);
        return;
    }
    
    $deleted = $this->db->where('id_litige', $id)->delete('litiges_commandes');
    
    if ($deleted) {
        echo json_encode(['success' => true, 'message' => 'Litige supprimé avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
}
}
?>