<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PointsRelais extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('PointsRelais_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Vérifier que l'utilisateur est admin
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        // Créer le dossier d'upload
        $upload_path = FCPATH . 'uploads/points_relais/';
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

    /**
     * Liste des points relais
     */
    public function index() {
        $data['title'] = 'Gestion des points relais';
        
        $filters = [
            'type' => $this->input->get('type'),
            'est_actif' => $this->input->get('est_actif'),
            'id_commune' => $this->input->get('id_commune'),
            'search' => $this->input->get('search')
        ];
        
        $config['base_url'] = base_url('points-relais/index');
        $config['total_rows'] = $this->PointsRelais_model->count_all($filters);
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
        
        $data['points'] = $this->PointsRelais_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->PointsRelais_model->get_stats();
        $data['filters'] = $filters;
        $data['communes'] = $this->db->get('communes')->result();
        
        $this->load->view('points_relais_list', $data);
    }

    /**
     * Ajouter un point relais
     */
    public function add() {
        $data['title'] = 'Ajouter un point relais';
        $data['communes'] = $this->db->get('communes')->result();
        $data['quartiers'] = $this->db->get('quartiers')->result();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('nom', 'Nom', 'required');
            $this->form_validation->set_rules('adresse', 'Adresse', 'required');
            $this->form_validation->set_rules('latitude', 'Latitude', 'required|numeric');
            $this->form_validation->set_rules('longitude', 'Longitude', 'required|numeric');
            
            if ($this->form_validation->run() == true) {
                // Décoder les horaires JSON
                $horaires = $this->input->post('horaires');
                if ($horaires && is_array($horaires)) {
                    $horaires = json_encode($horaires);
                }
                
                $insert_data = [
                    'nom' => $this->input->post('nom'),
                    'type' => $this->input->post('type'),
                    'adresse' => $this->input->post('adresse'),
                    'id_commune' => $this->input->post('id_commune') ?: null,
                    'id_quartier' => $this->input->post('id_quartier') ?: null,
                    'telephone' => $this->input->post('telephone'),
                    'latitude' => $this->input->post('latitude'),
                    'longitude' => $this->input->post('longitude'),
                    'horaires' => $horaires,
                    'capacite_max' => $this->input->post('capacite_max') ?: 50,
                    'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                    'date_creation' => date('Y-m-d H:i:s')
                ];
                
                $id = $this->PointsRelais_model->add($insert_data);
                
                if ($id) {
                    $this->session->set_flashdata('success', 'Point relais ajouté avec succès');
                    redirect('points-relais');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
                }
            }
        }
        
        $this->load->view('points_relais_add_edit', $data);
    }

    /**
     * Modifier un point relais
     */
    public function edit($id) {
        $data['point'] = $this->PointsRelais_model->get_by_id($id);
        
        if (!$data['point']) {
            show_404();
        }
        
        // Décoder les horaires
        if ($data['point']->horaires) {
            $data['point']->horaires_array = json_decode($data['point']->horaires, true);
        }
        
        $data['title'] = 'Modifier le point relais - ' . $data['point']->nom;
        $data['communes'] = $this->db->get('communes')->result();
        $data['quartiers'] = $this->db->get('quartiers')->result();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('nom', 'Nom', 'required');
            $this->form_validation->set_rules('adresse', 'Adresse', 'required');
            $this->form_validation->set_rules('latitude', 'Latitude', 'required|numeric');
            $this->form_validation->set_rules('longitude', 'Longitude', 'required|numeric');
            
            if ($this->form_validation->run() == true) {
                $horaires = $this->input->post('horaires');
                if ($horaires && is_array($horaires)) {
                    $horaires = json_encode($horaires);
                }
                
                $update_data = [
                    'nom' => $this->input->post('nom'),
                    'type' => $this->input->post('type'),
                    'adresse' => $this->input->post('adresse'),
                    'id_commune' => $this->input->post('id_commune') ?: null,
                    'id_quartier' => $this->input->post('id_quartier') ?: null,
                    'telephone' => $this->input->post('telephone'),
                    'latitude' => $this->input->post('latitude'),
                    'longitude' => $this->input->post('longitude'),
                    'horaires' => $horaires,
                    'capacite_max' => $this->input->post('capacite_max') ?: 50,
                    'est_actif' => $this->input->post('est_actif') ? 1 : 0
                ];
                
                $updated = $this->PointsRelais_model->update($id, $update_data);
                
                if ($updated) {
                    $this->session->set_flashdata('success', 'Point relais modifié avec succès');
                    redirect('points-relais');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de la modification');
                }
            }
        }
        
        $this->load->view('points_relais_add_edit', $data);
    }

    /**
     * Supprimer un point relais
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $point = $this->PointsRelais_model->get_by_id($id);
        
        if (!$point) {
            echo json_encode(['success' => false, 'message' => 'Point relais non trouvé']);
            return;
        }
        
        $deleted = $this->PointsRelais_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Point relais supprimé avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    /**
     * Activer/Désactiver un point relais
     */
    public function toggle_status($id) {
        $this->output->set_content_type('application/json');
        
        $point = $this->PointsRelais_model->get_by_id($id);
        
        if (!$point) {
            echo json_encode(['success' => false, 'message' => 'Point relais non trouvé']);
            return;
        }
        
        $new_status = $point->est_actif == 1 ? 0 : 1;
        $updated = $this->PointsRelais_model->update($id, ['est_actif' => $new_status]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut modifié avec succès' : 'Erreur'
        ]);
    }

    /**
     * Détail d'un point relais
     */
    public function detail($id) {
        $data['point'] = $this->PointsRelais_model->get_by_id($id);
        
        if (!$data['point']) {
            show_404();
        }
        
        // Décoder les horaires
        if ($data['point']->horaires) {
            $data['point']->horaires_array = json_decode($data['point']->horaires, true);
        }
        
        // Récupérer commune et quartier
        if ($data['point']->id_commune) {
            $data['commune'] = $this->db->where('id_commune', $data['point']->id_commune)->get('communes')->row();
        }
        if ($data['point']->id_quartier) {
            $data['quartier'] = $this->db->where('id_quartier', $data['point']->id_quartier)->get('quartiers')->row();
        }
        
        $data['title'] = 'Détails du point relais - ' . $data['point']->nom;
        
        $this->load->view('points_relais_detail', $data);
    }

    /**
     * Exporter les points relais
     */
    public function exporter() {
        $points = $this->PointsRelais_model->get_all();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=points_relais_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Type', 'Adresse', 'Commune', 'Téléphone', 'Latitude', 'Longitude', 'Capacité', 'Statut', 'Date création']);
        
        foreach ($points as $p) {
            fputcsv($output, [
                $p->id_point,
                $p->nom,
                $p->type,
                $p->adresse,
                $p->commune_name ?? '-',
                $p->telephone ?? '-',
                $p->latitude,
                $p->longitude,
                $p->capacite_max,
                $p->est_actif ? 'Actif' : 'Inactif',
                date('d/m/Y', strtotime($p->date_creation))
            ]);
        }
        
        fclose($output);
    }

    /**
     * Récupérer les quartiers par commune (AJAX)
     */
    public function get_quartiers() {
        $this->output->set_content_type('application/json');
        
        $id_commune = $this->input->post('id_commune');
        
        if (!$id_commune) {
            echo json_encode([]);
            return;
        }
        
        $quartiers = $this->db->where('id_commune', $id_commune)->get('quartiers')->result();
        
        echo json_encode($quartiers);
    }
}
?>