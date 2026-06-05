<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transporteurs extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transporteurs_model');
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
        $upload_path = FCPATH . 'uploads/transporteurs/';
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
     * Liste des transporteurs
     */
    public function index() {
        $data['title'] = 'Gestion des transporteurs';
        
        $filters = [
            'statut' => $this->input->get('statut'),
            'type_vehicule' => $this->input->get('type_vehicule'),
            'est_disponible' => $this->input->get('est_disponible'),
            'search' => $this->input->get('search')
        ];
        
        $config['base_url'] = base_url('transporteurs/index');
        $config['total_rows'] = $this->Transporteurs_model->count_all($filters);
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
        
        $data['transporteurs'] = $this->Transporteurs_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->Transporteurs_model->get_stats();
        $data['filters'] = $filters;
        
        $this->load->view('transporteurs_list', $data);
    }

    /**
     * Ajouter un transporteur
     */
    public function add() {
        $data['title'] = 'Ajouter un transporteur';
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('nom', 'Nom', 'required');
            $this->form_validation->set_rules('telephone', 'Téléphone', 'required');
            $this->form_validation->set_rules('type_vehicule', 'Type de véhicule', 'required');
            
            if ($this->form_validation->run() == true) {
                $photo_url = $this->upload_photo();
                
                $insert_data = [
                    'id_utilisateur' => $this->input->post('id_utilisateur') ?: null,
                    'type' => $this->input->post('type'),
                    'nom' => $this->input->post('nom'),
                    'telephone' => $this->input->post('telephone'),
                    'whatsapp' => $this->input->post('whatsapp'),
                    'photo_url' => $photo_url,
                    'type_vehicule' => $this->input->post('type_vehicule'),
                    'plaque' => $this->input->post('plaque'),
                    'latitude_actuelle' => $this->input->post('latitude') ?: null,
                    'longitude_actuelle' => $this->input->post('longitude') ?: null,
                    'est_disponible' => $this->input->post('est_disponible') ? 1 : 0,
                    'statut' => $this->input->post('statut'),
                    'date_creation' => date('Y-m-d H:i:s')
                ];
                
                $id = $this->Transporteurs_model->add($insert_data);
                
                if ($id) {
                    $this->session->set_flashdata('success', 'Transporteur ajouté avec succès');
                    redirect('transporteurs');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
                }
            }
        }
        
        $data['utilisateurs'] = $this->db->get('utilisateurs')->result();
        $this->load->view('transporteurs_add_edit', $data);
    }

    /**
     * Modifier un transporteur
     */
    public function edit($id) {
        $data['transporteur'] = $this->Transporteurs_model->get_by_id($id);
        
        if (!$data['transporteur']) {
            show_404();
        }
        
        $data['title'] = 'Modifier le transporteur - ' . $data['transporteur']->nom;
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('nom', 'Nom', 'required');
            $this->form_validation->set_rules('telephone', 'Téléphone', 'required');
            $this->form_validation->set_rules('type_vehicule', 'Type de véhicule', 'required');
            
            if ($this->form_validation->run() == true) {
                $photo_url = $data['transporteur']->photo_url;
                $new_photo = $this->upload_photo();
                
                if ($new_photo) {
                    // Supprimer l'ancienne photo
                    if (!empty($photo_url) && file_exists(FCPATH . $photo_url)) {
                        unlink(FCPATH . $photo_url);
                    }
                    $photo_url = $new_photo;
                }
                
                $update_data = [
                    'id_utilisateur' => $this->input->post('id_utilisateur') ?: null,
                    'type' => $this->input->post('type'),
                    'nom' => $this->input->post('nom'),
                    'telephone' => $this->input->post('telephone'),
                    'whatsapp' => $this->input->post('whatsapp'),
                    'photo_url' => $photo_url,
                    'type_vehicule' => $this->input->post('type_vehicule'),
                    'plaque' => $this->input->post('plaque'),
                    'latitude_actuelle' => $this->input->post('latitude') ?: null,
                    'longitude_actuelle' => $this->input->post('longitude') ?: null,
                    'est_disponible' => $this->input->post('est_disponible') ? 1 : 0,
                    'statut' => $this->input->post('statut')
                ];
                
                $updated = $this->Transporteurs_model->update($id, $update_data);
                
                if ($updated) {
                    $this->session->set_flashdata('success', 'Transporteur modifié avec succès');
                    redirect('transporteurs');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de la modification');
                }
            }
        }
        
        $data['utilisateurs'] = $this->db->get('utilisateurs')->result();
        $this->load->view('transporteurs_add_edit', $data);
    }

    /**
     * Upload de photo
     */
    private function upload_photo() {
        if (!isset($_FILES['photo_url']) || $_FILES['photo_url']['error'] != UPLOAD_ERR_OK) {
            return null;
        }
        
        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['photo_url']['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowed)) {
            return null;
        }
        
        $ext = pathinfo($_FILES['photo_url']['name'], PATHINFO_EXTENSION);
        $filename = 'transporteur_' . date('Ymd_His') . '_' . uniqid() . '.' . strtolower($ext);
        $upload_path = FCPATH . 'uploads/transporteurs/';
        
        if (move_uploaded_file($_FILES['photo_url']['tmp_name'], $upload_path . $filename)) {
            return 'uploads/transporteurs/' . $filename;
        }
        
        return null;
    }

    /**
     * Supprimer un transporteur
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $transporteur = $this->Transporteurs_model->get_by_id($id);
        
        if (!$transporteur) {
            echo json_encode(['success' => false, 'message' => 'Transporteur non trouvé']);
            return;
        }
        
        // Supprimer la photo
        if (!empty($transporteur->photo_url) && file_exists(FCPATH . $transporteur->photo_url)) {
            unlink(FCPATH . $transporteur->photo_url);
        }
        
        $deleted = $this->Transporteurs_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Transporteur supprimé avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    /**
     * Changer le statut (actif/inactif/suspendu)
     */
    public function change_statut($id) {
        $this->output->set_content_type('application/json');
        
        $statut = $this->input->post('statut');
        
        $updated = $this->Transporteurs_model->update($id, ['statut' => $statut]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut modifié avec succès' : 'Erreur'
        ]);
    }

    /**
     * Changer la disponibilité
     */
    public function toggle_disponible($id) {
        $this->output->set_content_type('application/json');
        
        $transporteur = $this->Transporteurs_model->get_by_id($id);
        
        if (!$transporteur) {
            echo json_encode(['success' => false, 'message' => 'Transporteur non trouvé']);
            return;
        }
        
        $new_status = $transporteur->est_disponible == 1 ? 0 : 1;
        $updated = $this->Transporteurs_model->update($id, ['est_disponible' => $new_status]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Disponibilité modifiée' : 'Erreur'
        ]);
    }

    /**
     * Mettre à jour la position GPS
     */
    public function update_position($id) {
        $this->output->set_content_type('application/json');
        
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        
        if (empty($latitude) || empty($longitude)) {
            echo json_encode(['success' => false, 'message' => 'Position invalide']);
            return;
        }
        
        $updated = $this->Transporteurs_model->update($id, [
            'latitude_actuelle' => $latitude,
            'longitude_actuelle' => $longitude,
            'derniere_position' => date('Y-m-d H:i:s')
        ]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Position mise à jour' : 'Erreur'
        ]);
    }

    /**
     * Détail d'un transporteur
     */
    public function detail($id) {
        $data['transporteur'] = $this->Transporteurs_model->get_by_id($id);
        
        if (!$data['transporteur']) {
            show_404();
        }
        
        $data['title'] = 'Détails du transporteur - ' . $data['transporteur']->nom;
        
        $this->load->view('transporteurs_detail', $data);
    }

    /**
     * Exporter les transporteurs
     */
    public function exporter() {
        $transporteurs = $this->Transporteurs_model->get_all();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=transporteurs_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nom', 'Téléphone', 'WhatsApp', 'Type', 'Véhicule', 'Plaque', 'Disponible', 'Statut', 'Livraisons', 'Note', 'Date création']);
        
        foreach ($transporteurs as $t) {
            fputcsv($output, [
                $t->id_transporteur,
                $t->nom,
                $t->telephone,
                $t->whatsapp ?? '-',
                $t->type,
                $t->type_vehicule,
                $t->plaque ?? '-',
                $t->est_disponible ? 'Oui' : 'Non',
                $t->statut,
                $t->livraisons_totales,
                $t->note_moyenne,
                date('d/m/Y', strtotime($t->date_creation))
            ]);
        }
        
        fclose($output);
    }
}
?>