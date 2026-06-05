<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Location_model');
        $this->load->library('form_validation');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // ==================== PROVINCES ====================
    
    public function provinces() {
        $data['title'] = 'Gestion des Provinces';
        $data['provinces'] = $this->Location_model->get_all_provinces();
        $this->load->view('province_list', $data);
    }
    
    public function province_add_edit($id = null) {
        $data['title'] = $id ? 'Modifier la province' : 'Ajouter une province';
        if ($id) {
            $data['province'] = $this->Location_model->get_province_by_id($id);
            if (!$data['province']) show_404();
        }
        $this->load->view('province_add_edit', $data);
    }
    
    public function province_detail($id) {
        $data['title'] = 'Détails de la province';
        $data['province'] = $this->Location_model->get_province_by_id($id);
        if (!$data['province']) show_404();
        $this->load->view('province_detail', $data);
    }
    
    public function ajouter($type) {
        if ($type == 'province') {
            $this->form_validation->set_rules('province_name', 'Nom', 'required|is_unique[provinces.province_name]');
            if ($this->form_validation->run()) {
                $data = ['province_name' => $this->input->post('province_name'), 'latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude'), 'est_actif' => $this->input->post('est_actif') ? 1 : 0];
                $id = $this->Location_model->ajouter_province($data);
                $response = $id ? ['success' => true, 'message' => 'Province ajoutée avec succès'] : ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
            } else {
                $response = ['success' => false, 'message' => validation_errors()];
            }
        }
        echo json_encode($response);
    }
    
    public function modifier($type, $id) {
        if ($type == 'province') {
            $data = ['province_name' => $this->input->post('province_name'), 'latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude'), 'est_actif' => $this->input->post('est_actif') ? 1 : 0];
            $updated = $this->Location_model->modifier_province($id, $data);
            $response = $updated ? ['success' => true, 'message' => 'Province modifiée avec succès'] : ['success' => false, 'message' => 'Erreur lors de la modification'];
        }
        echo json_encode($response);
    }

    // ==================== COMMUNES ====================
    
    public function communes($province_id = null) {
        $data['title'] = 'Gestion des Communes';
        if ($province_id) {
            $data['province'] = $this->Location_model->get_province_by_id($province_id);
            $data['communes'] = $this->Location_model->get_communes_by_province($province_id);
        } else {
            $data['communes'] = $this->Location_model->get_all_communes();
        }
        $this->load->view('commune_list', $data);
    }
    
    public function commune_add_edit($id = null) {
        $data['title'] = $id ? 'Modifier la commune' : 'Ajouter une commune';
        $data['provinces'] = $this->Location_model->get_all_provinces();
        if ($id) {
            $data['commune'] = $this->Location_model->get_commune_by_id($id);
            if (!$data['commune']) show_404();
        }
        $this->load->view('commune_add_edit', $data);
    }
    
    public function commune_detail($id) {
        $data['title'] = 'Détails de la commune';
        $data['commune'] = $this->Location_model->get_commune_by_id($id);
        if (!$data['commune']) show_404();
        $this->load->view('commune_detail', $data);
    }
    
    public function ajouter_commune() {
        $this->form_validation->set_rules('id_province', 'Province', 'required');
        $this->form_validation->set_rules('commune_name', 'Nom', 'required');
        if ($this->form_validation->run()) {
            $data = ['id_province' => $this->input->post('id_province'), 'commune_name' => $this->input->post('commune_name'), 'latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude'), 'est_actif' => $this->input->post('est_actif') ? 1 : 0];
            $id = $this->Location_model->ajouter_commune($data);
            $response = $id ? ['success' => true, 'message' => 'Commune ajoutée avec succès'] : ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
        } else {
            $response = ['success' => false, 'message' => validation_errors()];
        }
        echo json_encode($response);
    }
    
    public function modifier_commune($id) {
        $data = ['id_province' => $this->input->post('id_province'), 'commune_name' => $this->input->post('commune_name'), 'latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude'), 'est_actif' => $this->input->post('est_actif') ? 1 : 0];
        $updated = $this->Location_model->modifier_commune($id, $data);
        echo json_encode($updated ? ['success' => true, 'message' => 'Commune modifiée avec succès'] : ['success' => false, 'message' => 'Erreur lors de la modification']);
    }

    // ==================== QUARTIERS ====================
    
    public function quartiers($commune_id = null) {
        $data['title'] = 'Gestion des Quartiers';
        if ($commune_id) {
            $data['commune'] = $this->Location_model->get_commune_by_id($commune_id);
            $data['quartiers'] = $this->Location_model->get_quartiers_by_commune($commune_id);
        } else {
            $data['quartiers'] = $this->Location_model->get_all_quartiers();
        }
        $this->load->view('quartier_list', $data);
    }
    
    public function quartier_add_edit($id = null) {
        $data['title'] = $id ? 'Modifier le quartier' : 'Ajouter un quartier';
        $data['provinces'] = $this->Location_model->get_all_provinces();
        if ($id) {
            $data['quartier'] = $this->Location_model->get_quartier_by_id($id);
            if (!$data['quartier']) show_404();
            $data['selected_province'] = $data['quartier']->id_province;
            $data['communes'] = $this->Location_model->get_communes_by_province($data['selected_province']);
        }
        $this->load->view('quartier_add_edit', $data);
    }
    
    public function quartier_detail($id) {
        $data['title'] = 'Détails du quartier';
        $data['quartier'] = $this->Location_model->get_quartier_by_id($id);
        if (!$data['quartier']) show_404();
        $this->load->view('quartier_detail', $data);
    }
    
    public function ajouter_quartier() {
        $this->form_validation->set_rules('id_commune', 'Commune', 'required');
        $this->form_validation->set_rules('quartier_name', 'Nom', 'required');
        if ($this->form_validation->run()) {
            $data = ['id_commune' => $this->input->post('id_commune'), 'quartier_name' => $this->input->post('quartier_name'), 'zone' => $this->input->post('zone'), 'latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude'), 'est_actif' => $this->input->post('est_actif') ? 1 : 0];
            $id = $this->Location_model->ajouter_quartier($data);
            $response = $id ? ['success' => true, 'message' => 'Quartier ajouté avec succès'] : ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
        } else {
            $response = ['success' => false, 'message' => validation_errors()];
        }
        echo json_encode($response);
    }
    
    public function modifier_quartier($id) {
        $data = ['id_commune' => $this->input->post('id_commune'), 'quartier_name' => $this->input->post('quartier_name'), 'zone' => $this->input->post('zone'), 'latitude' => $this->input->post('latitude'), 'longitude' => $this->input->post('longitude'), 'est_actif' => $this->input->post('est_actif') ? 1 : 0];
        $updated = $this->Location_model->modifier_quartier($id, $data);
        echo json_encode($updated ? ['success' => true, 'message' => 'Quartier modifié avec succès'] : ['success' => false, 'message' => 'Erreur lors de la modification']);
    }

    // ==================== ZONES ====================

public function zones($commune_id = null) {
    $data['title'] = 'Gestion des Zones';
    if ($commune_id) {
        $data['commune'] = $this->Location_model->get_commune_by_id($commune_id);
        $data['zones'] = $this->Location_model->get_zones_by_commune($commune_id);
    } else {
        $data['zones'] = $this->Location_model->get_all_zones();
    }
    $this->load->view('zone_list', $data);
}

public function zone_add_edit($id = null) {
    $data['title'] = $id ? 'Modifier la zone' : 'Ajouter une zone';
    
    // Récupérer toutes les communes pour le select
    $data['communes'] = $this->Location_model->get_all_communes();
    
    if ($id) {
        $data['zone'] = $this->Location_model->get_zone_by_id($id);
        if (!$data['zone']) show_404();
    }
    
    $this->load->view('zone_add_edit', $data);
}

public function zone_detail($id) {
    $data['title'] = 'Détails de la zone';
    $data['zone'] = $this->Location_model->get_zone_by_id($id);
    if (!$data['zone']) show_404();
    $this->load->view('zone_detail', $data);
}

public function ajouter_zone() {
    $this->form_validation->set_rules('id_commune', 'Commune', 'required');
    $this->form_validation->set_rules('zone_name', 'Nom de la zone', 'required');
    
    if ($this->form_validation->run()) {
        $data = [
            'id_commune' => $this->input->post('id_commune'),
            'zone_name' => $this->input->post('zone_name'),
            'latitude' => $this->input->post('latitude') ?: null,
            'longitude' => $this->input->post('longitude') ?: null,
            'est_actif' => $this->input->post('est_actif') ? 1 : 0
        ];
        $id = $this->Location_model->ajouter_zone($data);
        $response = $id ? ['success' => true, 'message' => 'Zone ajoutée avec succès', 'id' => $id] : ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
    } else {
        $response = ['success' => false, 'message' => validation_errors()];
    }
    echo json_encode($response);
}

public function modifier_zone($id) {
    $data = [
        'id_commune' => $this->input->post('id_commune'),
        'zone_name' => $this->input->post('zone_name'),
        'latitude' => $this->input->post('latitude') ?: null,
        'longitude' => $this->input->post('longitude') ?: null,
        'est_actif' => $this->input->post('est_actif') ? 1 : 0
    ];
    $updated = $this->Location_model->modifier_zone($id, $data);
    echo json_encode($updated ? ['success' => true, 'message' => 'Zone modifiée avec succès'] : ['success' => false, 'message' => 'Erreur lors de la modification']);
}
    // ==================== COLLINES ====================
    
    /**
     * Liste des collines
     */
    public function collines($zone_id = null) {
        $data['title'] = 'Gestion des Collines';
        if ($zone_id) {
            $data['zone'] = $this->Location_model->get_zone_by_id($zone_id);
            $data['collines'] = $this->Location_model->get_collines_by_zone($zone_id);
        } else {
            $data['collines'] = $this->Location_model->get_all_collines();
        }
        $this->load->view('colline_list', $data);
    }
    
    /**
     * Page Ajouter/Modifier une colline
     */
    public function colline_add_edit($id = null) {
        $data['title'] = $id ? 'Modifier la colline' : 'Ajouter une colline';
        
        // Récupérer toutes les zones pour le select
        $data['zones'] = $this->Location_model->get_all_zones();
        
        if ($id) {
            $data['colline'] = $this->Location_model->get_colline_by_id($id);
            if (!$data['colline']) show_404();
            $data['selected_zone'] = $data['colline']->id_zone;
        }
        
        $this->load->view('colline_add_edit', $data);
    }
    
    /**
     * Détails d'une colline
     */
    public function colline_detail($id) {
        $data['title'] = 'Détails de la colline';
        $data['colline'] = $this->Location_model->get_colline_by_id($id);
        if (!$data['colline']) show_404();
        $this->load->view('colline_detail', $data);
    }
    
    /**
     * Ajouter une colline (AJAX)
     */
    public function ajouter_colline() {
        $this->form_validation->set_rules('id_zone', 'Zone', 'required');
        $this->form_validation->set_rules('colline_name', 'Nom de la colline', 'required');
        
        if ($this->form_validation->run()) {
            $data = [
                'id_zone' => $this->input->post('id_zone'),
                'colline_name' => $this->input->post('colline_name'),
                'latitude' => $this->input->post('latitude') ?: null,
                'longitude' => $this->input->post('longitude') ?: null,
                'est_actif' => $this->input->post('est_actif') ? 1 : 0
            ];
            $id = $this->Location_model->ajouter_colline($data);
            $response = $id ? ['success' => true, 'message' => 'Colline ajoutée avec succès', 'id' => $id] : ['success' => false, 'message' => 'Erreur lors de l\'ajout'];
        } else {
            $response = ['success' => false, 'message' => validation_errors()];
        }
        echo json_encode($response);
    }
    
    /**
     * Modifier une colline (AJAX)
     */
    public function modifier_colline($id) {
        $data = [
            'id_zone' => $this->input->post('id_zone'),
            'colline_name' => $this->input->post('colline_name'),
            'latitude' => $this->input->post('latitude') ?: null,
            'longitude' => $this->input->post('longitude') ?: null,
            'est_actif' => $this->input->post('est_actif') ? 1 : 0
        ];
        $updated = $this->Location_model->modifier_colline($id, $data);
        echo json_encode($updated ? ['success' => true, 'message' => 'Colline modifiée avec succès'] : ['success' => false, 'message' => 'Erreur lors de la modification']);
    }

    // ==================== SUPPRESSIONS ====================
    
    public function supprimer($type, $id) {
        $result = false;
        if ($type == 'province') $result = $this->Location_model->supprimer_province($id);
        elseif ($type == 'commune') $result = $this->Location_model->supprimer_commune($id);
        elseif ($type == 'quartier') $result = $this->Location_model->supprimer_quartier($id);
        elseif ($type == 'zone') $result = $this->Location_model->supprimer_zone($id);
        elseif ($type == 'colline') $result = $this->Location_model->supprimer_colline($id);
        echo json_encode($result ? ['success' => true, 'message' => ucfirst($type) . ' supprimé avec succès'] : ['success' => false, 'message' => 'Erreur lors de la suppression']);
    }

    // ==================== AJAX ====================
    
    public function get_communes_by_province($id_province) {
        echo json_encode($this->Location_model->get_communes_by_province($id_province));
    }
    
    public function get_quartiers_by_commune($id_commune) {
        echo json_encode($this->Location_model->get_quartiers_by_commune($id_commune));
    }
    
    public function get_zones_by_quartier($id_quartier) {
        echo json_encode($this->Location_model->get_zones_by_quartier($id_quartier));
    }
    
    public function get_collines_by_zone($id_zone) {
        echo json_encode($this->Location_model->get_collines_by_zone($id_zone));
    }
}
?>