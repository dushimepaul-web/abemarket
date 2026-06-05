<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Banners_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->helper('file');
        
        // Vérifier que l'utilisateur est admin
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        // Créer le dossier d'upload des bannières
        $upload_path = FCPATH . 'uploads/banners/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
        
        // Désactiver automatiquement les bannières expirées à chaque chargement (optionnel)
        $this->Banners_model->disable_expired_banners();
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        if (!$user_id) return false;
        
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2, 3]); // super_admin, admin, moderateur
        return $this->db->get()->num_rows() > 0;
    }

    /**
     * Upload d'image avec taille maximale
     */
    private function upload_image($file_input_name = 'image') {
        if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] != UPLOAD_ERR_OK) {
            return null;
        }
        
        // Vérifier la taille du fichier (max 5MB)
        if ($_FILES[$file_input_name]['size'] > 5 * 1024 * 1024) {
            $this->session->set_flashdata('error', 'L\'image ne doit pas dépasser 5MB');
            return null;
        }
        
        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES[$file_input_name]['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowed)) {
            $this->session->set_flashdata('error', 'Format d\'image non supporté. Utilisez JPG, PNG, GIF ou WEBP');
            return null;
        }
        
        $ext = pathinfo($_FILES[$file_input_name]['name'], PATHINFO_EXTENSION);
        $filename = 'banner_' . date('Ymd_His') . '_' . uniqid() . '.' . strtolower($ext);
        $upload_path = FCPATH . 'uploads/banners/';
        
        if (move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $upload_path . $filename)) {
            return 'uploads/banners/' . $filename;
        }
        
        return null;
    }

    /**
     * Supprimer une image
     */
    private function delete_image($url_image) {
        if (!empty($url_image) && file_exists(FCPATH . $url_image)) {
            unlink(FCPATH . $url_image);
            return true;
        }
        return false;
    }

    /**
     * Liste des bannières
     */
    public function index() {
        $data['title'] = 'Gestion des bannières';
        
        // Configuration de la pagination
        $config['base_url'] = base_url('banners/index');
        $config['total_rows'] = $this->Banners_model->count_all();
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
        
        // Statistiques supplémentaires
        $data['total_banners'] = $config['total_rows'];
        $data['total_active'] = $this->Banners_model->count_active();
        $data['total_inactive'] = $this->Banners_model->count_all(0);
        $data['expiring_banners'] = $this->Banners_model->get_expiring_banners(7);
        
        $data['banners'] = $this->Banners_model->get_all_banners($config['per_page'], $page);
        
        $this->load->view('banners_list', $data);
    }

    /**
     * Ajouter une bannière
     */
    public function add() {
        $data['title'] = 'Ajouter une bannière';
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('title', 'Titre', 'required|trim');
            $this->form_validation->set_rules('position', 'Position', 'required');
            
            if ($this->form_validation->run() == true) {
                $image_url = $this->upload_image('image');
                
                if (!$image_url) {
                    redirect('banners/add');
                    return;
                }
                
                // Nettoyer les dates
                $date_debut = $this->input->post('date_debut');
                $date_fin = $this->input->post('date_fin');
                
                $insert_data = [
                    'title' => $this->input->post('title'),
                    'subtitle' => $this->input->post('subtitle'),
                    'image' => $image_url,
                    'link' => $this->input->post('link'),
                    'position' => $this->input->post('position'),
                    'ordre_affichage' => (int)$this->input->post('ordre_affichage') ?: 0,
                    'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                    'date_debut' => !empty($date_debut) ? date('Y-m-d H:i:s', strtotime($date_debut)) : null,
                    'date_fin' => !empty($date_fin) ? date('Y-m-d H:i:s', strtotime($date_fin)) : null,
                    'date_creation' => date('Y-m-d H:i:s')
                ];
                
                $id = $this->Banners_model->add_banner($insert_data);
                
                if ($id) {
                    $this->session->set_flashdata('success', 'Bannière ajoutée avec succès');
                    redirect('banners');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
                }
            }
        }
        
        $this->load->view('banners_add_edit', $data);
    }

    /**
     * Modifier une bannière
     */
    public function edit($id) {
        $data['banner'] = $this->Banners_model->get_banner_by_id($id);
        
        if (!$data['banner']) {
            show_404();
        }
        
        $data['title'] = 'Modifier la bannière - ' . $data['banner']->title;
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('title', 'Titre', 'required|trim');
            $this->form_validation->set_rules('position', 'Position', 'required');
            
            if ($this->form_validation->run() == true) {
                $image_url = $data['banner']->image;
                
                $new_image = $this->upload_image('image');
                if ($new_image) {
                    $this->delete_image($image_url);
                    $image_url = $new_image;
                }
                
                $date_debut = $this->input->post('date_debut');
                $date_fin = $this->input->post('date_fin');
                
                $update_data = [
                    'title' => $this->input->post('title'),
                    'subtitle' => $this->input->post('subtitle'),
                    'image' => $image_url,
                    'link' => $this->input->post('link'),
                    'position' => $this->input->post('position'),
                    'ordre_affichage' => (int)$this->input->post('ordre_affichage') ?: 0,
                    'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                    'date_debut' => !empty($date_debut) ? date('Y-m-d H:i:s', strtotime($date_debut)) : null,
                    'date_fin' => !empty($date_fin) ? date('Y-m-d H:i:s', strtotime($date_fin)) : null
                ];
                
                $updated = $this->Banners_model->update_banner($id, $update_data);
                
                if ($updated) {
                    $this->session->set_flashdata('success', 'Bannière modifiée avec succès');
                    redirect('banners');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de la modification');
                }
            }
        }
        
        $this->load->view('banners_add_edit', $data);
    }

    /**
     * Supprimer une bannière
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $banner = $this->Banners_model->get_banner_by_id($id);
        
        if (!$banner) {
            echo json_encode(['success' => false, 'message' => 'Bannière non trouvée']);
            return;
        }
        
        $this->delete_image($banner->image);
        $deleted = $this->Banners_model->delete_banner($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Bannière supprimée avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    /**
     * Activer/Désactiver une bannière
     */
    public function toggle_status($id) {
        $this->output->set_content_type('application/json');
        
        $banner = $this->Banners_model->get_banner_by_id($id);
        
        if (!$banner) {
            echo json_encode(['success' => false, 'message' => 'Bannière non trouvée']);
            return;
        }
        
        $new_status = $banner->est_actif == 1 ? 0 : 1;
        $updated = $this->Banners_model->toggle_status($id, $new_status);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut modifié avec succès' : 'Erreur'
        ]);
    }

    /**
     * NOUVEAU: Voir les bannières par position
     */
    public function by_position($position = null) {
        if (!$position) {
            redirect('banners');
        }
        
        $data['title'] = 'Bannières - Position: ' . ucfirst(str_replace('_', ' ', $position));
        $data['banners'] = $this->Banners_model->get_banners_by_position($position);
        $data['position'] = $position;
        $data['total_banners'] = count($data['banners']);
        
        $this->load->view('banners_by_position', $data);
    }

    /**
     * NOUVEAU: Supprimer toutes les bannières expirées
     */
    public function delete_expired() {
        $this->output->set_content_type('application/json');
        
        $deleted = $this->Banners_model->delete_expired_banners();
        
        echo json_encode([
            'success' => $deleted !== false,
            'message' => $deleted ? 'Bannières expirées supprimées avec succès' : 'Aucune bannière expirée à supprimer'
        ]);
    }

    /**
     * NOUVEAU: Désactiver les bannières expirées
     */
    public function disable_expired() {
        $this->output->set_content_type('application/json');
        
        $disabled = $this->Banners_model->disable_expired_banners();
        
        echo json_encode([
            'success' => $disabled !== false,
            'message' => $disabled ? 'Bannières expirées désactivées avec succès' : 'Aucune bannière expirée à désactiver'
        ]);
    }

    /**
     * NOUVEAU: Statistiques des bannières
     */
    public function stats() {
        $data['title'] = 'Statistiques des bannières';
        
        $data['total_banners'] = $this->Banners_model->count_all();
        $data['total_active'] = $this->Banners_model->count_active();
        $data['total_inactive'] = $this->Banners_model->count_all(0);
        
        // Compter par position
        $positions = ['home_main', 'home_bottom', 'home_top_right', 'category', 'product'];
        foreach ($positions as $position) {
            $data['count_by_position'][$position] = $this->Banners_model->count_by_position($position);
        }
        
        $data['expiring_banners'] = $this->Banners_model->get_expiring_banners(7);
        
        $this->load->view('banners_stats', $data);
    }

    /**
     * NOUVEAU: Dupliquer une bannière
     */
    public function duplicate($id) {
        $banner = $this->Banners_model->get_banner_by_id($id);
        
        if (!$banner) {
            show_404();
        }
        
        // Créer une copie
        $copy_data = [
            'title' => $banner->title . ' (Copie)',
            'subtitle' => $banner->subtitle,
            'image' => $banner->image, // Note: l'image n'est pas dupliquée physiquement
            'link' => $banner->link,
            'position' => $banner->position,
            'ordre_affichage' => $banner->ordre_affichage + 1,
            'est_actif' => 0, // La copie est inactive par défaut
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $new_id = $this->Banners_model->add_banner($copy_data);
        
        if ($new_id) {
            $this->session->set_flashdata('success', 'Bannière dupliquée avec succès');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la duplication');
        }
        
        redirect('banners');
    }
}
?>