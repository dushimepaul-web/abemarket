<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModePayement extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ModePayement_model');
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
        $upload_path = FCPATH . 'uploads/mode_payement/';
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
     * Upload du logo
     */
    private function upload_logo() {
        if (!isset($_FILES['logo_url']) || $_FILES['logo_url']['error'] != UPLOAD_ERR_OK) {
            return null;
        }
        
        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['logo_url']['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowed)) {
            return null;
        }
        
        $ext = pathinfo($_FILES['logo_url']['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . date('Ymd_His') . '_' . uniqid() . '.' . strtolower($ext);
        $upload_path = FCPATH . 'uploads/mode_payement/';
        
        if (move_uploaded_file($_FILES['logo_url']['tmp_name'], $upload_path . $filename)) {
            return 'uploads/mode_payement/' . $filename;
        }
        
        return null;
    }

    /**
     * Supprimer un fichier
     */
    private function delete_file($file_path) {
        if (!empty($file_path) && file_exists(FCPATH . $file_path)) {
            unlink(FCPATH . $file_path);
            return true;
        }
        return false;
    }

    /**
     * Liste des modes de paiement
     */
    public function index() {
        $data['title'] = 'Gestion des modes de paiement';
        
        $filters = [
            'est_actif' => $this->input->get('est_actif'),
            'type' => $this->input->get('type'),
            'search' => $this->input->get('search')
        ];
        
        $config['base_url'] = base_url('mode-payement/index');
        $config['total_rows'] = $this->ModePayement_model->count_all($filters);
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
        
        $data['modes'] = $this->ModePayement_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->ModePayement_model->get_stats();
        $data['filters'] = $filters;
        
        $this->load->view('mode_payement_list', $data);
    }

    /**
     * Ajouter un mode de paiement
     */
    public function add() {
        $data['title'] = 'Ajouter un mode de paiement';
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('code', 'Code', 'required|trim|is_unique[mode_payement.code]');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('type', 'Type', 'required');
            
            if ($this->form_validation->run() == true) {
                $logo_url = $this->upload_logo();
                
                $insert_data = [
                    'code' => strtoupper(trim($this->input->post('code'))),
                    'description' => $this->input->post('description'),
                    'type' => $this->input->post('type'),
                    'logo_url' => $logo_url,
                    'frais_fixe' => $this->input->post('frais_fixe') ?: 0,
                    'frais_pourcentage' => $this->input->post('frais_pourcentage') ?: 0,
                    'instructions' => $this->input->post('instructions'),
                    'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                    'ordre_affichage' => $this->input->post('ordre_affichage') ?: 0,
                    'date_creation' => date('Y-m-d H:i:s')
                ];
                
                $id = $this->ModePayement_model->add($insert_data);
                
                if ($id) {
                    $this->session->set_flashdata('success', 'Mode de paiement ajouté avec succès');
                    redirect('mode-payement');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
                }
            }
        }
        
        $this->load->view('mode_payement_add_edit', $data);
    }

    /**
     * Modifier un mode de paiement
     */
    public function edit($id) {
        $data['mode'] = $this->ModePayement_model->get_by_id($id);
        
        if (!$data['mode']) {
            show_404();
        }
        
        $data['title'] = 'Modifier le mode de paiement - ' . $data['mode']->code;
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('code', 'Code', 'required|trim');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('type', 'Type', 'required');
            
            if ($this->form_validation->run() == true) {
                $update_data = [
                    'code' => strtoupper(trim($this->input->post('code'))),
                    'description' => $this->input->post('description'),
                    'type' => $this->input->post('type'),
                    'frais_fixe' => $this->input->post('frais_fixe') ?: 0,
                    'frais_pourcentage' => $this->input->post('frais_pourcentage') ?: 0,
                    'instructions' => $this->input->post('instructions'),
                    'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                    'ordre_affichage' => $this->input->post('ordre_affichage') ?: 0
                ];
                
                // Upload nouveau logo
                $new_logo = $this->upload_logo();
                if ($new_logo) {
                    $this->delete_file($data['mode']->logo_url);
                    $update_data['logo_url'] = $new_logo;
                }
                
                $updated = $this->ModePayement_model->update($id, $update_data);
                
                if ($updated) {
                    $this->session->set_flashdata('success', 'Mode de paiement modifié avec succès');
                    redirect('mode-payement');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de la modification');
                }
            }
        }
        
        $this->load->view('mode_payement_add_edit', $data);
    }

    /**
     * Supprimer un mode de paiement
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $mode = $this->ModePayement_model->get_by_id($id);
        
        if (!$mode) {
            echo json_encode(['success' => false, 'message' => 'Mode de paiement non trouvé']);
            return;
        }
        
        $this->delete_file($mode->logo_url);
        
        $deleted = $this->ModePayement_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Mode de paiement supprimé avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    /**
     * Activer/Désactiver un mode de paiement
     */
    public function toggle_status($id) {
        $this->output->set_content_type('application/json');
        
        $mode = $this->ModePayement_model->get_by_id($id);
        
        if (!$mode) {
            echo json_encode(['success' => false, 'message' => 'Mode de paiement non trouvé']);
            return;
        }
        
        $new_status = $mode->est_actif == 1 ? 0 : 1;
        $updated = $this->ModePayement_model->update($id, ['est_actif' => $new_status]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut modifié avec succès' : 'Erreur'
        ]);
    }

    /**
     * Détail d'un mode de paiement
     */
    public function detail($id) {
        $data['mode'] = $this->ModePayement_model->get_by_id($id);
        
        if (!$data['mode']) {
            show_404();
        }
        
        $data['title'] = 'Détails du mode de paiement - ' . $data['mode']->code;
        
        $this->load->view('mode_payement_detail', $data);
    }
}
?>