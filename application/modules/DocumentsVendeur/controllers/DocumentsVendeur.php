<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DocumentsVendeur extends MY_Controller {

    
    public function __construct() {
        parent::__construct();
        $this->load->model('DocumentsVendeur_model');
        $this->load->model('Vendeur_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Créer le dossier d'upload
        $upload_path = FCPATH . 'uploads/documents_vendeurs/';
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

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    /**
     * Upload d'un document
     */
    private function upload_document() {
        if (!isset($_FILES['fichier_document']) || $_FILES['fichier_document']['error'] != UPLOAD_ERR_OK) {
            return null;
        }
        
        $allowed = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['fichier_document']['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowed)) {
            return null;
        }
        
        $ext = pathinfo($_FILES['fichier_document']['name'], PATHINFO_EXTENSION);
        $filename = 'document_' . date('Ymd_His') . '_' . uniqid() . '.' . strtolower($ext);
        $upload_path = FCPATH . 'uploads/documents_vendeurs/';
        
        if (move_uploaded_file($_FILES['fichier_document']['tmp_name'], $upload_path . $filename)) {
            return 'uploads/documents_vendeurs/' . $filename;
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
 * Détail d'un document (Admin)
 */
public function detail($id) {
    if (!$this->is_admin()) {
        show_error('Accès réservé aux administrateurs', 403);
    }
    
    $data['document'] = $this->DocumentsVendeur_model->get_by_id($id);
    
    if (!$data['document']) {
        show_404();
    }
    
    $data['title'] = 'Détails du document - ' . $data['document']->vendeur_nom;
    
    $this->load->view('documents_vendeur_detail', $data);
}

    /**
     * Liste des documents (Admin)
     */
    public function index() {
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        $data['title'] = 'Gestion des documents vendeurs';
        
        $filters = [
            'statut_verification' => $this->input->get('statut'),
            'id_vendeur' => $this->input->get('id_vendeur'),
            'type_document' => $this->input->get('type_document')
        ];
        
        $config['base_url'] = base_url('documents-vendeur/index');
        $config['total_rows'] = $this->DocumentsVendeur_model->count_all($filters);
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
        
        $data['documents'] = $this->DocumentsVendeur_model->get_all(
            $config['per_page'],
            $page,
            $filters
        );
        
        $data['stats'] = $this->DocumentsVendeur_model->get_stats();
        $data['vendeurs'] = $this->db->get('vendeurs')->result();
        $data['filters'] = $filters;
        
        $this->load->view('documents_vendeur_list', $data);
    }

    /**
     * Mes documents (Vendeur)
     */
    public function mes_documents() {
    $id_vendeur = $this->get_vendeur_id();
    
    if (!$id_vendeur) {
        show_error('Accès réservé aux vendeurs', 403);
    }
    
    $data['title'] = 'Mes documents';
    $data['documents'] = $this->DocumentsVendeur_model->get_by_vendeur($id_vendeur);
    
    // Récupérer directement le vendeur sans modèle
    $data['vendeur'] = $this->db->where('id_vendeur', $id_vendeur)->get('vendeurs')->row();
    
    $data['types_document'] = $this->DocumentsVendeur_model->get_types_document();
    
    $this->load->view('documents_vendeur_mes', $data);
}

    /**
     * Ajouter un document (Vendeur)
     */
    public function add() {
        $id_vendeur = $this->get_vendeur_id();
        
        if (!$id_vendeur) {
            show_error('Accès réservé aux vendeurs', 403);
        }
        
        $data['title'] = 'Ajouter un document';
        $data['types_document'] = $this->DocumentsVendeur_model->get_types_document();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('type_document', 'Type de document', 'required');
            $this->form_validation->set_rules('numero_document', 'Numéro de document', 'required');
            
            if ($this->form_validation->run() == true) {
                $fichier_url = $this->upload_document();
                
                if (!$fichier_url) {
                    $this->session->set_flashdata('error', 'Erreur lors de l\'upload du fichier (PDF, JPG, PNG uniquement)');
                    redirect('documents-vendeur/add');
                    return;
                }
                
                $insert_data = [
                    'id_vendeur' => $id_vendeur,
                    'type_document' => $this->input->post('type_document'),
                    'numero_document' => $this->input->post('numero_document'),
                    'fichier_document' => $fichier_url,
                    'date_expiration' => $this->input->post('date_expiration') ?: null,
                    'statut_verification' => 'en_attente',
                    'date_upload' => date('Y-m-d H:i:s')
                ];
                
                $id = $this->DocumentsVendeur_model->add($insert_data);
                
                if ($id) {
                    $this->session->set_flashdata('success', 'Document ajouté avec succès, en attente de vérification');
                    redirect('documents-vendeur/mes-documents');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
                }
            }
        }
        
        $this->load->view('documents_vendeur_add', $data);
    }

    /**
     * Modifier un document (Admin ou Vendeur)
     */
    public function edit($id) {
        $data['document'] = $this->DocumentsVendeur_model->get_by_id($id);
        
        if (!$data['document']) {
            show_404();
        }
        
        $id_vendeur = $this->get_vendeur_id();
        
        if (!$this->is_admin() && $data['document']->id_vendeur != $id_vendeur) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['title'] = 'Modifier le document';
        $data['types_document'] = $this->DocumentsVendeur_model->get_types_document();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $update_data = [
                'type_document' => $this->input->post('type_document'),
                'numero_document' => $this->input->post('numero_document'),
                'date_expiration' => $this->input->post('date_expiration') ?: null
            ];
            
            // Upload nouveau fichier
            $new_file = $this->upload_document();
            if ($new_file) {
                $this->delete_file($data['document']->fichier_document);
                $update_data['fichier_document'] = $new_file;
            }
            
            $updated = $this->DocumentsVendeur_model->update($id, $update_data);
            
            if ($updated) {
                $this->session->set_flashdata('success', 'Document modifié avec succès');
                if ($this->is_admin()) {
                    redirect('documents-vendeur');
                } else {
                    redirect('documents-vendeur/mes-documents');
                }
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la modification');
            }
        }
        
        $this->load->view('documents_vendeur_edit', $data);
    }

    /**
     * Vérifier un document (Admin)
     */
    public function verifier($id) {
        $this->output->set_content_type('application/json');
        
        if (!$this->is_admin()) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $statut = $this->input->post('statut');
        $motif_refus = $this->input->post('motif_refus');
        
        $update_data = [
            'statut_verification' => $statut,
            'date_verification' => date('Y-m-d H:i:s'),
            'verifie_par' => $this->session->userdata('id_utilisateur')
        ];
        
        if ($statut == 'refuse') {
            $update_data['motif_refus'] = $motif_refus;
        }
        
        $updated = $this->DocumentsVendeur_model->update($id, $update_data);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Document vérifié avec succès' : 'Erreur'
        ]);
    }

    /**
     * Supprimer un document
     */
    public function delete($id) {
        $this->output->set_content_type('application/json');
        
        $document = $this->DocumentsVendeur_model->get_by_id($id);
        
        if (!$document) {
            echo json_encode(['success' => false, 'message' => 'Document non trouvé']);
            return;
        }
        
        $id_vendeur = $this->get_vendeur_id();
        
        if (!$this->is_admin() && $document->id_vendeur != $id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $this->delete_file($document->fichier_document);
        
        $deleted = $this->DocumentsVendeur_model->delete($id);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Document supprimé avec succès' : 'Erreur'
        ]);
    }

    /**
     * Télécharger un document
     */
    public function download($id) {
        $document = $this->DocumentsVendeur_model->get_by_id($id);
        
        if (!$document) {
            show_404();
        }
        
        $id_vendeur = $this->get_vendeur_id();
        
        if (!$this->is_admin() && $document->id_vendeur != $id_vendeur) {
            show_error('Accès non autorisé', 403);
        }
        
        $file_path = FCPATH . $document->fichier_document;
        
        if (!file_exists($file_path)) {
            show_404();
        }
        
        $this->load->helper('download');
        force_download(basename($document->fichier_document), file_get_contents($file_path));
    }
}
?>