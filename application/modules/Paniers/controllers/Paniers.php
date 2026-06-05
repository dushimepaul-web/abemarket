<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paniers extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Paniers_model');
        $this->load->model('Produit_model');
        $this->load->model('VarianteProduit_model');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Vérifier que l'utilisateur est admin
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
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

    /**
     * Liste des paniers (admin)
     */
    public function index() {
        $data['title'] = 'Gestion des paniers';
        
        $filters = [
            'search' => $this->input->get('search'),
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $config['base_url'] = base_url('paniers/index');
        $config['total_rows'] = $this->Paniers_model->count_all($filters);
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
        
        $data['paniers'] = $this->Paniers_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->Paniers_model->get_stats($filters);
        $data['filters'] = $filters;
        
        $this->load->view('paniers_list', $data);
    }

    /**
     * Détail d'un panier par utilisateur
     */
    public function detail($id_utilisateur) {
        $data['utilisateur'] = $this->db->where('id_utilisateur', $id_utilisateur)->get('utilisateurs')->row();
        
        if (!$data['utilisateur']) {
            show_404();
        }
        
        $data['articles'] = $this->Paniers_model->get_by_utilisateur($id_utilisateur);
        $data['total'] = $this->Paniers_model->get_total_by_utilisateur($id_utilisateur);
        $data['title'] = 'Détails du panier - ' . $data['utilisateur']->prenom . ' ' . $data['utilisateur']->nom;
        
        $this->load->view('paniers_detail', $data);
    }

    /**
     * Supprimer un article du panier
     */
    public function delete_article($id_panier) {
        $this->output->set_content_type('application/json');
        
        $article = $this->Paniers_model->get_article_by_id($id_panier);
        
        if (!$article) {
            echo json_encode(['success' => false, 'message' => 'Article non trouvé']);
            return;
        }
        
        $deleted = $this->Paniers_model->delete_article($id_panier);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Article supprimé du panier' : 'Erreur'
        ]);
    }


    public function update_quantite() {
    $this->output->set_content_type('application/json');
    
    $id_panier = $this->input->post('id_panier');
    $quantite = $this->input->post('quantite');
    
    if (empty($id_panier) || $quantite <= 0) {
        echo json_encode(['success' => false, 'message' => 'Données invalides']);
        return;
    }
    
    $updated = $this->Paniers_model->update_quantite($id_panier, $quantite);
    
    echo json_encode([
        'success' => $updated,
        'message' => $updated ? 'Quantité mise à jour' : 'Erreur'
    ]);
}

    /**
     * Vider le panier d'un utilisateur
     */
    public function vider($id_utilisateur) {
        $this->output->set_content_type('application/json');
        
        $deleted = $this->Paniers_model->vider_panier($id_utilisateur);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Panier vidé avec succès' : 'Erreur'
        ]);
    }

    /**
     * Exporter les paniers
     */
    public function exporter() {
        $filters = [
            'date_debut' => $this->input->get('date_debut'),
            'date_fin' => $this->input->get('date_fin')
        ];
        
        $paniers = $this->Paniers_model->get_all_paniers_export($filters);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=paniers_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID Panier', 'Client', 'Email', 'Produit', 'Variante', 'Quantité', 'Prix unitaire', 'Total', 'Date ajout']);
        
        foreach ($paniers as $p) {
            fputcsv($output, [
                $p->id_panier,
                $p->client_nom,
                $p->email,
                $p->nom_produit,
                $p->variante_sku ?? 'Standard',
                $p->quantite,
                number_format($p->prix, 0, ',', ' ') . ' FBu',
                number_format($p->quantite * $p->prix, 0, ',', ' ') . ' FBu',
                date('d/m/Y H:i', strtotime($p->date_ajout))
            ]);
        }
        
        fclose($output);
    }
}
?>