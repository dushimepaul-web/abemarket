<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ZoneLivraison extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ZoneLivraison_model');
        $this->load->model('Location_model');
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
     * Liste des zones de livraison
     */
    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des zones de livraison';
        
        // Pagination
        $config['base_url'] = base_url('ZoneLivraison/index');
        $config['total_rows'] = $this->ZoneLivraison_model->count_zones_livraison();
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
        
        $this->pagination->initialize($config);
        
        $data['zones'] = $this->ZoneLivraison_model->get_all_zones_livraison(
            $config['per_page'],
            $this->uri->segment(3)
        );
        
        $data['stats'] = $this->ZoneLivraison_model->get_stats();
        
        $this->load->view('zone_livraison_list', $data);
    }

    /**
     * Ajouter/Modifier une zone de livraison
     */
    public function add_edit($id = null) {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = $id ? 'Modifier la zone de livraison' : 'Ajouter une zone de livraison';
        $data['provinces'] = $this->Location_model->get_all_provinces();
        $data['communes'] = [];
        $data['quartiers'] = [];
        
        if ($id) {
            $data['zone'] = $this->ZoneLivraison_model->get_zone_livraison_by_id($id);
            if (!$data['zone']) {
                show_404();
            }
            
            // Charger les communes de la province sélectionnée
            if ($data['zone']->id_province) {
                $data['communes'] = $this->Location_model->get_communes_by_province($data['zone']->id_province);
            }
            
            // Charger tous les quartiers des communes sélectionnées
            if (!empty($data['zone']->communes_ids)) {
                foreach ($data['zone']->communes_ids as $commune_id) {
                    $quartiers = $this->Location_model->get_quartiers_by_commune($commune_id);
                    $data['quartiers'] = array_merge($data['quartiers'], $quartiers);
                }
            }
        }
        
        $this->load->view('zone_livraison_add_edit', $data);
    }

    /**
     * Sauvegarder une zone de livraison
     */
    public function save() {
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $this->form_validation->set_rules('nom_zone', 'Nom de la zone', 'required|max_length[100]');
        $this->form_validation->set_rules('cout_base', 'Coût de base', 'required|numeric|greater_than_equal_to[0]');
        
        if ($this->form_validation->run() == false) {
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }
        
        $data = [
            'nom_zone' => $this->input->post('nom_zone'),
            'id_province' => $this->input->post('id_province') ?: null,
            'communes_ids' => $this->input->post('communes_ids') ?: [],
            'quartiers_ids' => $this->input->post('quartiers_ids') ?: [],
            'cout_base' => $this->input->post('cout_base'),
            'seuil_livraison_gratuite' => $this->input->post('seuil_livraison_gratuite') ?: null,
            'cout_par_kg' => $this->input->post('cout_par_kg') ?: null,
            'delai_min_jours' => $this->input->post('delai_min_jours') ?: null,
            'delai_max_jours' => $this->input->post('delai_max_jours') ?: null,
            'est_actif' => $this->input->post('est_actif') ? 1 : 0
        ];
        
        $id = $this->input->post('id_zone_liv');
        
        if ($id) {
            $result = $this->ZoneLivraison_model->modifier_zone_livraison($id, $data);
            $message = $result ? 'Zone modifiée avec succès' : 'Erreur lors de la modification';
        } else {
            $id = $this->ZoneLivraison_model->ajouter_zone_livraison($data);
            $result = $id ? true : false;
            $message = $result ? 'Zone ajoutée avec succès' : 'Erreur lors de l\'ajout';
        }
        
        echo json_encode(['success' => $result, 'message' => $message, 'id' => $id]);
    }

    /**
     * Détails d'une zone de livraison
     */
    public function detail($id) {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['zone'] = $this->ZoneLivraison_model->get_zone_livraison_by_id($id);
        
        if (!$data['zone']) {
            show_404();
        }
        
        $data['title'] = 'Détails de la zone - ' . $data['zone']->nom_zone;
        
        $this->load->view('zone_livraison_detail', $data);
    }

    /**
     * Supprimer une zone de livraison
     */
    public function delete($id) {
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $deleted = $this->ZoneLivraison_model->supprimer_zone_livraison($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Zone supprimée avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    /**
     * Changer le statut d'une zone
     */
    public function toggle_statut($id) {
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $zone = $this->ZoneLivraison_model->get_zone_livraison_by_id($id);
        
        if (!$zone) {
            echo json_encode(['success' => false, 'message' => 'Zone non trouvée']);
            return;
        }
        
        $nouveau_statut = $zone->est_actif ? 0 : 1;
        $updated = $this->ZoneLivraison_model->modifier_zone_livraison($id, ['est_actif' => $nouveau_statut]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut modifié avec succès' : 'Erreur lors de la modification',
            'nouveau_statut' => $nouveau_statut
        ]);
    }

    /**
     * Exporter les zones en CSV
     */
    public function exporter() {
        if (!$this->is_admin()) {
            show_error('Accès non autorisé', 403);
        }
        
        $zones = $this->ZoneLivraison_model->get_all_zones_livraison();
        
        $this->load->helper('download');
        
        $data = [];
        $headers = ['ID', 'Nom zone', 'Province', 'Communes', 'Coût base', 'Seuil gratuité', 'Coût/kg', 'Délai min', 'Délai max', 'Statut'];
        $data[] = $headers;
        
        foreach ($zones as $z) {
            $row = [
                $z->id_zone_liv,
                $z->nom_zone,
                $z->province_name ?? 'Toutes',
                $z->ids_communes ?? '-',
                number_format($z->cout_base, 2),
                $z->seuil_livraison_gratuite ? number_format($z->seuil_livraison_gratuite, 2) : '-',
                $z->cout_par_kg ? number_format($z->cout_par_kg, 2) : '-',
                $z->delai_min_jours ?? '-',
                $z->delai_max_jours ?? '-',
                $z->est_actif ? 'Actif' : 'Inactif'
            ];
            $data[] = $row;
        }
        
        $csv = '';
        foreach ($data as $row) {
            $csv .= '"' . implode('","', array_map('addslashes', $row)) . '"' . "\n";
        }
        
        force_download('zones_livraison_' . date('Y-m-d') . '.csv', $csv);
    }

    /**
     * Récupérer les communes par province (AJAX)
     */
    public function get_communes() {
        $id_province = $this->input->post('id_province');
        
        if ($id_province) {
            $communes = $this->Location_model->get_communes_by_province($id_province);
            echo json_encode($communes);
        } else {
            echo json_encode([]);
        }
    }

    /**
     * Récupérer les quartiers par commune (AJAX)
     */
    public function get_quartiers() {
        $id_commune = $this->input->post('id_commune');
        
        if ($id_commune) {
            $quartiers = $this->Location_model->get_quartiers_by_commune($id_commune);
            echo json_encode($quartiers);
        } else {
            echo json_encode([]);
        }
    }
}
?>