<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Categories_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->helper('file');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Vérifier que l'utilisateur est admin
        if (!$this->is_admin()) {
            show_error('Accès réservé aux administrateurs', 403);
        }
        
        // Créer le dossier d'upload des catégories
        $upload_path = FCPATH . 'uploads/categories/';
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

  // Générer un slug unique (version corrigée)
private function generate_slug($nom, $id_exclude = null) {
    // Supprimer les accents
    $search = explode(",", "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ó,Ò,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");
    $replace = explode(",", "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");
    
    $nom = str_replace($search, $replace, $nom);
    
    // Convertir en minuscules et remplacer les espaces par des tirets
    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]/', '-', $nom), '-'));
    
    // Supprimer les tirets multiples
    $slug = preg_replace('/-+/', '-', $slug);
    
    // Vérifier l'unicité
    $this->db->where('slug_categorie', $slug);
    if ($id_exclude) {
        $this->db->where('id_categorie !=', $id_exclude);
    }
    $count = $this->db->count_all_results('categories');
    
    if ($count > 0) {
        $slug = $slug . '-' . ($count + 1);
    }
    
    return $slug;
}



// Calculer automatiquement le niveau en fonction du parent
private function calculer_niveau_automatique($id_parent) {
    // Si pas de parent, c'est une catégorie principale (niveau 0)
    if (empty($id_parent)) {
        return 0;
    }
    
    // Récupérer le parent
    $parent = $this->Categories_model->get_categorie_by_id($id_parent);
    
    // Si le parent n'existe pas, niveau 0
    if (!$parent) {
        return 0;
    }
    
    // Le niveau de l'enfant = niveau du parent + 1
    return $parent->niveau + 1;
}




    // Upload d'image
    private function upload_image($file_input_name = 'image') {
        if (!isset($_FILES[$file_input_name]) || $_FILES[$file_input_name]['error'] != UPLOAD_ERR_OK) {
            return null;
        }
        
        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES[$file_input_name]['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime, $allowed)) {
            return null;
        }
        
        $ext = pathinfo($_FILES[$file_input_name]['name'], PATHINFO_EXTENSION);
        $filename = 'categorie_' . date('Ymd_His') . '_' . uniqid() . '.' . strtolower($ext);
        $upload_path = FCPATH . 'uploads/categories/';
        
        if (move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $upload_path . $filename)) {
            return 'uploads/categories/' . $filename;
        }
        
        return null;
    }

    // Supprimer une image
    private function delete_image($url_image) {
        if (!empty($url_image) && file_exists(FCPATH . $url_image)) {
            unlink(FCPATH . $url_image);
            return true;
        }
        return false;
    }

    // Liste des catégories (avec slug)
    public function index() {
        $data['title'] = 'Gestion des catégories';
        
        $config['base_url'] = base_url('categories/index');
        $config['total_rows'] = $this->Categories_model->count_all();
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
        
        $data['categories'] = $this->Categories_model->get_all_categories($config['per_page'], $page);
        $data['categories_parent'] = $this->Categories_model->get_parent_categories();
        
        $this->load->view('categories_list', $data);
    }

   
public function add() {
    $data['title'] = 'Ajouter une catégorie';
    // CORRECTION : Utiliser get_categories_hierarchique() pour avoir TOUTES les catégories
    $data['categories_parent'] = $this->Categories_model->get_categories_hierarchique();
    
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $this->form_validation->set_rules('nom_categorie', 'Nom de la catégorie', 'required|trim');
        
        if ($this->form_validation->run() == true) {
            $id_parent = $this->input->post('id_parent') ?: null;
            
            // Calcul automatique du niveau
            $niveau = $this->calculer_niveau_automatique($id_parent);
            
            // Vérifier la limite de niveau (max 3)
            if ($niveau > 3) {
                $this->session->set_flashdata('error', 'Niveau maximum (3) atteint. Impossible de créer une sous-catégorie plus profonde.');
                redirect('categories/add');
                return;
            }
            
            $slug = $this->generate_slug($this->input->post('nom_categorie'));
            $image_url = $this->upload_image('image');
            
            $insert_data = [
                'id_parent' => $id_parent,
                'nom_categorie' => $this->input->post('nom_categorie'),
                'slug_categorie' => $slug,
                'niveau' => $niveau,
                'icone' => $this->input->post('icone'),
                'url_image' => $image_url,
                'description' => $this->input->post('description'),
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'ordre_affichage' => $this->input->post('ordre_affichage') ?: 0,
                'taux_commission_specifique' => $this->input->post('taux_commission_specifique') ?: null,
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            $id = $this->Categories_model->add_categorie($insert_data);
            
            if ($id) {
                $this->session->set_flashdata('success', 'Catégorie ajoutée avec succès');
                redirect('categories');
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de l\'ajout');
            }
        }
    }
    
    $this->load->view('categories_add_edit', $data);
}

public function edit($slug) {
    $data['categorie'] = $this->Categories_model->get_categorie_by_slug($slug);
    
    if (!$data['categorie']) {
        show_404();
    }
    
    $data['title'] = 'Modifier la catégorie - ' . $data['categorie']->nom_categorie;
    // CORRECTION : Utiliser get_categories_hierarchique() en excluant la catégorie actuelle
    $data['categories_parent'] = $this->Categories_model->get_categories_hierarchique($data['categorie']->id_categorie);
    
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
        $this->form_validation->set_rules('nom_categorie', 'Nom de la catégorie', 'required|trim');
        
        if ($this->form_validation->run() == true) {
            $id_parent = $this->input->post('id_parent') ?: null;
            
            // Vérifier qu'on ne crée pas une boucle infinie
            if ($id_parent == $data['categorie']->id_categorie) {
                $this->session->set_flashdata('error', 'Une catégorie ne peut pas être son propre parent');
                redirect('categories/edit/' . $slug);
                return;
            }
            
            // Vérifier qu'on ne crée pas une boucle avec un enfant qui deviendrait parent
            if ($this->est_un_enfant($id_parent, $data['categorie']->id_categorie)) {
                $this->session->set_flashdata('error', 'Impossible de définir une sous-catégorie comme parente');
                redirect('categories/edit/' . $slug);
                return;
            }
            
            // Calcul automatique du niveau
            $niveau = $this->calculer_niveau_automatique($id_parent);
            
            // Vérifier la limite de niveau (max 3)
            if ($niveau > 3) {
                $this->session->set_flashdata('error', 'Niveau maximum (3) atteint. Impossible de modifier.');
                redirect('categories/edit/' . $slug);
                return;
            }
            
            $slug_new = $this->generate_slug($this->input->post('nom_categorie'), $data['categorie']->id_categorie);
            $image_url = $data['categorie']->url_image;
            
            // Upload nouvelle image
            $new_image = $this->upload_image('image');
            if ($new_image) {
                $this->delete_image($image_url);
                $image_url = $new_image;
            }
            
            $update_data = [
                'id_parent' => $id_parent,
                'nom_categorie' => $this->input->post('nom_categorie'),
                'slug_categorie' => $slug_new,
                'niveau' => $niveau,
                'icone' => $this->input->post('icone'),
                'url_image' => $image_url,
                'description' => $this->input->post('description'),
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'ordre_affichage' => $this->input->post('ordre_affichage') ?: 0,
                'taux_commission_specifique' => $this->input->post('taux_commission_specifique') ?: null
            ];
            
            $updated = $this->Categories_model->update_categorie($data['categorie']->id_categorie, $update_data);
            
            if ($updated) {
                // Mettre à jour les niveaux des sous-catégories
                $this->mettre_a_jour_niveaux_sous_categories($data['categorie']->id_categorie, $niveau + 1);
                
                $this->session->set_flashdata('success', 'Catégorie modifiée avec succès');
                redirect('categories');
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la modification');
            }
        }
    }
    
    $this->load->view('categories_add_edit', $data);
}

// Vérifier si une catégorie est un enfant d'une autre (pour éviter les boucles)
private function est_un_enfant($id_parent, $id_categorie) {
    if (empty($id_parent)) return false;
    
    $enfant = $this->Categories_model->get_categorie_by_id($id_parent);
    if (!$enfant) return false;
    
    if ($enfant->id_parent == $id_categorie) {
        return true;
    }
    
    return $this->est_un_enfant($enfant->id_parent, $id_categorie);
}




// Mettre à jour les niveaux de toutes les sous-catégories
private function mettre_a_jour_niveaux_sous_categories($id_parent, $niveau_de_base) {
    // Récupérer toutes les sous-catégories directes
    $sous_categories = $this->db->where('id_parent', $id_parent)->get('categories')->result();
    
    foreach ($sous_categories as $sous_cat) {
        // Mettre à jour le niveau
        $this->db->where('id_categorie', $sous_cat->id_categorie);
        $this->db->update('categories', ['niveau' => $niveau_de_base]);
        
        // Appel récursif pour les sous-sous-catégories
        $this->mettre_a_jour_niveaux_sous_categories($sous_cat->id_categorie, $niveau_de_base + 1);
    }
}



    // Supprimer une catégorie (avec slug)
    public function delete($slug) {
        $this->output->set_content_type('application/json');
        
        $categorie = $this->Categories_model->get_categorie_by_slug($slug);
        
        if (!$categorie) {
            echo json_encode(['success' => false, 'message' => 'Catégorie non trouvée']);
            return;
        }
        
        // Vérifier si la catégorie a des sous-catégories
        $has_children = $this->Categories_model->has_children($categorie->id_categorie);
        if ($has_children) {
            echo json_encode(['success' => false, 'message' => 'Cette catégorie contient des sous-catégories. Supprimez-les d\'abord.']);
            return;
        }
        
        // Vérifier si la catégorie a des produits
        $this->db->where('id_categorie', $categorie->id_categorie);
        $has_products = $this->db->count_all_results('produits') > 0;
        
        if ($has_products) {
            echo json_encode(['success' => false, 'message' => 'Cette catégorie contient des produits. Supprimez-les ou déplacez-les d\'abord.']);
            return;
        }
        
        // Supprimer l'image
        $this->delete_image($categorie->url_image);
        
        $deleted = $this->Categories_model->delete_categorie($categorie->id_categorie);
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Catégorie supprimée avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    // Activer/Désactiver une catégorie (avec slug)
    public function toggle_status($slug) {
        $this->output->set_content_type('application/json');
        
        $categorie = $this->Categories_model->get_categorie_by_slug($slug);
        
        if (!$categorie) {
            echo json_encode(['success' => false, 'message' => 'Catégorie non trouvée']);
            return;
        }
        
        $new_status = $categorie->est_actif == 1 ? 0 : 1;
        $updated = $this->Categories_model->update_categorie($categorie->id_categorie, ['est_actif' => $new_status]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Statut modifié avec succès' : 'Erreur'
        ]);
    }
}
?>