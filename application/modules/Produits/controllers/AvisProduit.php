<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AvisProduit extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AvisProduit_model');
        $this->load->model('Produit_model');
        $this->load->library('form_validation');
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

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    public function index() {
        // Vérifier les droits (admin ou vendeur)
        if (!$this->is_admin() && !$this->is_vendeur()) {
            show_error('Accès réservé aux administrateurs et vendeurs', 403);
        }
        
        $data['title'] = 'Gestion des avis produits';
        
        $filters = [
            'est_approuve' => $this->input->get('statut'),
            'note' => $this->input->get('note'),
            'search' => $this->input->get('search')
        ];
        
        // Pour les vendeurs, filtrer par leurs produits
        if ($this->is_vendeur() && !$this->is_admin()) {
            $filters['id_vendeur'] = $this->get_vendeur_id();
        }
        
        // Configuration de la pagination
        $config['base_url'] = base_url('avis-produits');
        $config['total_rows'] = $this->AvisProduit_model->count_all_avis($filters);
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
        
        $data['avis'] = $this->AvisProduit_model->get_all_avis(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->AvisProduit_model->get_stats($filters);
        $data['repartition'] = $this->AvisProduit_model->get_repartition_notes($filters);
        $data['filters'] = $filters;
        
        $this->load->view('avis_list', $data);
    }

    public function detail($id) {
        // Vérifier les droits
        if (!$this->is_admin() && !$this->is_vendeur()) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['avis'] = $this->AvisProduit_model->get_avis_by_id($id);
        
        if (!$data['avis']) {
            show_404();
        }
        
        // Pour les vendeurs, vérifier que l'avis concerne un de leurs produits
        if ($this->is_vendeur() && !$this->is_admin()) {
            $id_vendeur = $this->get_vendeur_id();
            $produit = $this->Produit_model->get_produit_by_id($data['avis']->id_produit);
            if ($produit['id_vendeur'] != $id_vendeur) {
                show_error('Accès non autorisé', 403);
            }
        }
        
        // Passer les droits à la vue
        $data['is_admin'] = $this->is_admin();
        $data['is_vendeur'] = $this->is_vendeur();
        $data['title'] = 'Détails de l\'avis - ' . $data['avis']->nom_produit;
        
        $this->load->view('avis_detail', $data);
    }

    public function approuver($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès réservé aux administrateurs']);
            return;
        }
        
        $this->db->where('id_avis', $id);
        $updated = $this->db->update('avis_produits', ['est_approuve' => 1]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Avis approuvé avec succès' : 'Erreur lors de l\'approbation'
        ]);
    }

    public function rejeter($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès réservé aux administrateurs']);
            return;
        }
        
        $this->db->where('id_avis', $id);
        $updated = $this->db->update('avis_produits', ['est_approuve' => -1]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Avis rejeté avec succès' : 'Erreur lors du rejet'
        ]);
    }

    public function desapprouver($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès réservé aux administrateurs']);
            return;
        }
        
        $this->db->where('id_avis', $id);
        $updated = $this->db->update('avis_produits', ['est_approuve' => 0]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Avis désapprouvé, remis en attente' : 'Erreur'
        ]);
    }

    public function enattente($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès réservé aux administrateurs']);
            return;
        }
        
        $this->db->where('id_avis', $id);
        $updated = $this->db->update('avis_produits', ['est_approuve' => 0]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Avis remis en attente' : 'Erreur'
        ]);
    }

    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès réservé aux administrateurs']);
            return;
        }
        
        $this->db->where('id_avis', $id);
        $deleted = $this->db->delete('avis_produits');
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Avis supprimé avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    public function repondre() {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin() && !$this->is_vendeur()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $id_avis = $this->input->post('id_avis');
        $reponse = trim($this->input->post('reponse'));
        
        if (empty($id_avis)) {
            echo json_encode(['success' => false, 'message' => 'ID avis manquant']);
            return;
        }
        
        if (empty($reponse)) {
            echo json_encode(['success' => false, 'message' => 'La réponse ne peut pas être vide']);
            return;
        }
        
        // Vérifier que l'avis existe
        $avis = $this->AvisProduit_model->get_avis_by_id($id_avis);
        if (!$avis) {
            echo json_encode(['success' => false, 'message' => 'Avis non trouvé']);
            return;
        }
        
        // Pour les vendeurs, vérifier que l'avis concerne un de leurs produits
        if ($this->is_vendeur() && !$this->is_admin()) {
            $id_vendeur = $this->get_vendeur_id();
            $produit = $this->Produit_model->get_produit_by_id($avis->id_produit);
            if ($produit['id_vendeur'] != $id_vendeur) {
                echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
                return;
            }
        }
        
        // Mettre à jour la réponse
        $data = [
            'reponse_vendeur' => $reponse,
            'date_reponse_vendeur' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id_avis', $id_avis);
        $updated = $this->db->update('avis_produits', $data);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Réponse ajoutée avec succès' : 'Erreur lors de l\'enregistrement'
        ]);
    }
}
?>