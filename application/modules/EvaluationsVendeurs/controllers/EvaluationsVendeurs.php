<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EvaluationsVendeurs extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('EvaluationsVendeurs_model');
        $this->load->model('Vendeurs_model');
        $this->load->model('Commande_model');
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

    private function is_vendeur() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where('up.id_profil', 4);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_user_id() {
        return $this->session->userdata('id_utilisateur');
    }

    private function get_vendeur_id_by_user() {
        $user_id = $this->get_user_id();
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    /**
     * Liste des évaluations (Admin)
     */
    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des évaluations vendeurs';
        
        $filters = [
            'id_vendeur' => $this->input->get('id_vendeur'),
            'est_approuve' => $this->input->get('est_approuve'),
            'note_min' => $this->input->get('note_min'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $config['base_url'] = base_url('evaluations-vendeurs/index');
        $config['total_rows'] = $this->EvaluationsVendeurs_model->count_all($filters);
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
        
        $data['evaluations'] = $this->EvaluationsVendeurs_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->EvaluationsVendeurs_model->get_stats($filters);
        $data['vendeurs'] = $this->db->select('id_vendeur, nom_boutique')->get('vendeurs')->result();
        $data['filters'] = $filters;
        
        $this->load->view('evaluations_vendeurs_list', $data);
    }

    /**
     * Évaluations du vendeur connecté
     */
    public function mes_evaluations() {
        if (!$this->is_vendeur()) {
            show_error('Accès réservé aux vendeurs', 403);
        }
        
        $id_vendeur = $this->get_vendeur_id_by_user();
        
        if (!$id_vendeur) {
            show_error('Vous n\'êtes pas associé à une boutique', 403);
        }
        
        $data['title'] = 'Évaluations de ma boutique';
        
        $filters = [
            'id_vendeur' => $id_vendeur,
            'est_approuve' => $this->input->get('est_approuve'),
            'note_min' => $this->input->get('note_min'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $config['base_url'] = base_url('evaluations-vendeurs/mes-evaluations');
        $config['total_rows'] = $this->EvaluationsVendeurs_model->count_all($filters);
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
        
        $data['evaluations'] = $this->EvaluationsVendeurs_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['vendeur'] = $this->Vendeurs_model->get_by_id($id_vendeur);
        $data['stats'] = $this->EvaluationsVendeurs_model->get_stats(['id_vendeur' => $id_vendeur]);
        $data['filters'] = $filters;
        
        $this->load->view('evaluations_vendeurs_mes_evaluations', $data);
    }

    /**
     * Ajouter une évaluation (Client)
     */
    public function ajouter() {
        $this->output->set_content_type('application/json');
        
        $id_commande = $this->input->post('id_commande');
        $id_vendeur = $this->input->post('id_vendeur');
        $note_globale = $this->input->post('note_globale');
        $note_communication = $this->input->post('note_communication');
        $note_livraison = $this->input->post('note_livraison');
        $commentaire = $this->input->post('commentaire');
        $user_id = $this->get_user_id();
        
        // Validation
        if (!$id_commande || !$id_vendeur || !$note_globale) {
            echo json_encode(['success' => false, 'message' => 'Données incomplètes']);
            return;
        }
        
        if ($note_globale < 1 || $note_globale > 5) {
            echo json_encode(['success' => false, 'message' => 'Note invalide (1-5)']);
            return;
        }
        
        // Vérifier que la commande appartient à l'utilisateur
        $commande = $this->Commande_model->get_by_id($id_commande);
        if (!$commande || $commande->id_utilisateur != $user_id) {
            echo json_encode(['success' => false, 'message' => 'Commande non trouvée']);
            return;
        }
        
        // Vérifier que la commande est livrée
        if ($commande->statut_commande != 'livre') {
            echo json_encode(['success' => false, 'message' => 'Vous ne pouvez évaluer qu\'une commande livrée']);
            return;
        }
        
        // Vérifier si déjà évalué
        if ($this->EvaluationsVendeurs_model->a_deja_evalue($id_vendeur, $id_commande, $user_id)) {
            echo json_encode(['success' => false, 'message' => 'Vous avez déjà évalué ce vendeur pour cette commande']);
            return;
        }
        
        $data = [
            'id_vendeur' => $id_vendeur,
            'id_utilisateur' => $user_id,
            'id_commande' => $id_commande,
            'note_globale' => $note_globale,
            'note_communication' => $note_communication,
            'note_livraison' => $note_livraison,
            'commentaire' => $commentaire,
            'est_approuve' => 0, // En attente d'approbation si admin, sinon 1
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        // Si l'utilisateur est admin, approuver automatiquement
        if ($this->is_admin()) {
            $data['est_approuve'] = 1;
        }
        
        $inserted = $this->EvaluationsVendeurs_model->ajouter($data);
        
        if ($inserted) {
            // Mettre à jour la note moyenne du vendeur
            $this->update_vendeur_rating($id_vendeur);
            
            echo json_encode(['success' => true, 'message' => 'Merci pour votre évaluation !']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement']);
        }
    }

    /**
     * Approuver une évaluation (Admin)
     */
    public function approuver($id_evaluation) {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $this->EvaluationsVendeurs_model->approuver($id_evaluation);
        
        // Mettre à jour la note
        $evaluation = $this->EvaluationsVendeurs_model->get_by_id($id_evaluation);
        if ($evaluation) {
            $this->update_vendeur_rating($evaluation->id_vendeur);
        }
        
        $this->session->set_flashdata('success', 'Évaluation approuvée');
        redirect('evaluations-vendeurs');
    }

    /**
     * Rejeter une évaluation (Admin)
     */
    public function rejeter($id_evaluation) {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $this->EvaluationsVendeurs_model->supprimer($id_evaluation);
        $this->session->set_flashdata('success', 'Évaluation rejetée');
        redirect('evaluations-vendeurs');
    }

    /**
     * Supprimer une évaluation (Admin)
     */
    public function supprimer($id_evaluation) {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $evaluation = $this->EvaluationsVendeurs_model->get_by_id($id_evaluation);
        
        if ($evaluation) {
            $this->EvaluationsVendeurs_model->supprimer($id_evaluation);
            $this->update_vendeur_rating($evaluation->id_vendeur);
        }
        
        $this->session->set_flashdata('success', 'Évaluation supprimée');
        redirect('evaluations-vendeurs');
    }

    /**
     * Détail d'une évaluation
     */
    public function detail($id_evaluation) {
        $data['evaluation'] = $this->EvaluationsVendeurs_model->get_by_id_with_details($id_evaluation);
        
        if (!$data['evaluation']) {
            show_404();
        }
        
        // Vérifier les droits (admin ou vendeur concerné)
        $id_vendeur_user = $this->get_vendeur_id_by_user();
        if (!$this->is_admin() && (!$id_vendeur_user || $id_vendeur_user != $data['evaluation']->id_vendeur)) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['title'] = 'Détail de l\'évaluation #' . $id_evaluation;
        
        $this->load->view('evaluations_vendeurs_detail', $data);
    }

    /**
     * Mettre à jour la note moyenne d'un vendeur
     */
    private function update_vendeur_rating($id_vendeur) {
        $stats = $this->EvaluationsVendeurs_model->get_vendeur_stats($id_vendeur);
        
        $this->db->where('id_vendeur', $id_vendeur);
        $this->db->update('vendeurs', [
            'note_moyenne' => $stats->note_moyenne,
            'nombre_avis' => $stats->total_evaluations
        ]);
    }

    /**
     * Exporter les évaluations
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $filters = [
            'id_vendeur' => $this->input->get('id_vendeur'),
            'est_approuve' => $this->input->get('est_approuve'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $evaluations = $this->EvaluationsVendeurs_model->get_all(null, null, $filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=evaluations_vendeurs_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Vendeur', 'Client', 'Commande', 'Note Globale', 'Note Communication', 'Note Livraison', 'Commentaire', 'Statut', 'Date']);
        
        foreach ($evaluations as $e) {
            fputcsv($output, [
                $e->id_evaluation,
                $e->vendeur_nom,
                $e->client_nom,
                $e->commande_numero,
                $e->note_globale . '/5',
                $e->note_communication ? $e->note_communication . '/5' : '-',
                $e->note_livraison ? $e->note_livraison . '/5' : '-',
                $e->commentaire,
                $e->est_approuve ? 'Approuvé' : 'En attente',
                date('d/m/Y H:i:s', strtotime($e->date_creation))
            ]);
        }
        
        fclose($output);
    }
}
?>