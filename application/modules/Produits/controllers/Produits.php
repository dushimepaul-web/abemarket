<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produits extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
        
        // Charger le modèle
        $this->load->model('Produit_model');
        
        // Créer le dossier d'upload s'il n'existe pas
        $upload_path = FCPATH . 'uploads/produits/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }
    }

    // Liste des produits
    public function index()
    {
        // Récupérer tous les produits via le modèle
        $data['produits'] = $this->Produit_model->get_all_produits();
        
        // Statistiques
        $stats = $this->Produit_model->get_produits_stats();
        $data['total_produits'] = $stats['total'];
        $data['produits_actifs'] = $stats['actifs'];
        $data['produits_inactifs'] = $stats['inactifs'];
        $data['produits_stock_bas'] = $stats['stock_bas'];
        $data['rupture_stock'] = $stats['rupture'];
        
        // Récupérer les catégories pour le filtre
        $data['categories'] = $this->Model->read('categories', ['est_actif' => 1], 'nom_categorie', 'ASC');

        // Récupérer les images pour chaque produit
        foreach($data['produits'] as &$prod){
            $prod['image_url'] = $this->Produit_model->get_main_image($prod['id_produit']);
        }
        
        $this->load->view('produits_list', $data);
    }

    // Ajouter un produit
    public function add()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $nom_produit = trim($this->input->post('nom_produit'));
            $id_categorie = $this->input->post('id_categorie');
            $id_vendeur = $this->input->post('id_vendeur');
            $prix_base = $this->input->post('prix_base');
            $quantite_actuelle = $this->input->post('quantite_actuelle');
            
            // Validation
            if (empty($nom_produit) || empty($prix_base)) {
                $this->session->set_flashdata('error', 'Le nom du produit et le prix sont requis.');
                redirect(base_url('Produits/add'));
                return;
            }
            
            // Générer SKU, code produit et slug
            $sku = $this->generateSku($nom_produit);
            $code_produit = $this->generateCodeProduit();
            $slug = $this->Produit_model->generate_unique_slug($nom_produit);
            
            $date_debut_promo = $this->input->post('date_debut_promo');
            $date_fin_promo = $this->input->post('date_fin_promo');
            
            $seuil_stock_bas = $this->input->post('seuil_stock_bas') ?: 5;
            if ($quantite_actuelle <= 0) {
                $statut_stock = 'rupture_stock';
            } elseif ($quantite_actuelle <= $seuil_stock_bas) {
                $statut_stock = 'stock_bas';
            } else {
                $statut_stock = 'en_stock';
            }
            
            $data = [
                'id_vendeur' => !empty($id_vendeur) ? $id_vendeur : null,
                'id_categorie' => !empty($id_categorie) ? $id_categorie : null,
                'sku' => $sku,
                'code_produit' => $code_produit,
                'nom_produit' => $nom_produit,
                'slug_produit' => $slug,
                'description_courte' => $this->input->post('description_courte'),
                'description' => $this->input->post('description'),
                'marque' => $this->input->post('marque'),
                'prix_base' => $prix_base,
                'prix_promo' => $this->input->post('prix_promo') ?: null,
                'date_debut_promo' => !empty($date_debut_promo) ? date('Y-m-d H:i:s', strtotime($date_debut_promo)) : null,
                'date_fin_promo' => !empty($date_fin_promo) ? date('Y-m-d H:i:s', strtotime($date_fin_promo)) : null,
                'quantite_actuelle' => $quantite_actuelle ?: 0,
                'seuil_stock_bas' => $seuil_stock_bas,
                'statut_stock' => $statut_stock,
                'poids_kg' => $this->input->post('poids_kg') ?: null,
                'type_produit' => $this->input->post('type_produit') ?: 'simple',
                'statut' => $this->input->post('statut') ?: 'brouillon',
                'date_publication' => $this->input->post('statut') == 'actif' ? date('Y-m-d H:i:s') : null,
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            $produit_id = $this->Produit_model->add_produit($data);
            
            if ($produit_id) {
                // Gestion des images
                if (!empty($_FILES['images']['name'][0])) {
                    $this->upload_images($produit_id, $_FILES['images']);
                }
                
                $this->session->set_flashdata('success', 'Produit créé avec succès.');
                redirect(base_url('Produits'));
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la création du produit.');
                redirect(base_url('Produits/add'));
            }
        }
        
        // Récupérer les catégories et vendeurs pour les selects
        $data['categories'] = $this->Model->read('categories', ['est_actif' => 1], 'nom_categorie', 'ASC');
        $data['vendeurs'] = $this->db->select('v.id_vendeur, v.nom_boutique, u.prenom, u.nom')
            ->from('vendeurs v')
            ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
            ->where('v.est_approuve', 1)
            ->where('v.statut', 'actif')
            ->get()
            ->result_array();
        
        $this->load->view('produits_add_edit', $data);
    }






// Afficher la page de gestion des variantes d'un produit
public function variantes($slug)
{
    // Récupérer le produit par son slug
    $produit = $this->Produit_model->get_produit_by_slug($slug);
    
    if (!$produit) {
        $this->session->set_flashdata('error', 'Produit non trouvé.');
        redirect(base_url('Produits'));
    }
    
    $produit_id = $produit['id_produit'];
    
    // Récupérer les variantes du produit
    $variantes = $this->db->select('*')
                          ->from('variantes_produit')
                          ->where('id_produit', $produit_id)
                          ->order_by('date_creation', 'ASC')
                          ->get()
                          ->result();
    
    // Décoder les attributs JSON pour chaque variante
    foreach ($variantes as $v) {
        $v->attributs = json_decode($v->attributs_variante, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            $v->attributs = [];
        }
    }
    
    // Préparer les données pour la vue
    $data['produit'] = (object)$produit;  // Convertir en objet
    $data['variantes'] = $variantes;
    $data['title'] = 'Gestion des variantes - ' . $produit['nom_produit'];
    
    // Charger la vue
    $this->load->view('variantes_list', $data);
}



    // Modifier un produit par SLUG
    public function edit($slug)
    {
        $data['produit'] = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$data['produit']) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('Produits'));
        }
        
        $produit_id = $data['produit']['id_produit'];
        
        // Récupérer les images du produit
        $data['images'] = $this->Produit_model->get_images_by_produit_id($produit_id);
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->process_edit($slug, $produit_id);
        }
        
        $data['categories'] = $this->Model->read('categories', ['est_actif' => 1], 'nom_categorie', 'ASC');
        $data['vendeurs'] = $this->db->select('v.id_vendeur, v.nom_boutique, u.prenom, u.nom')
            ->from('vendeurs v')
            ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
            ->where('v.est_approuve', 1)
            ->where('v.statut', 'actif')
            ->get()
            ->result_array();
        
        $this->load->view('produits_add_edit', $data);
    }



// Afficher la page de gestion des images d'un produit
public function images($slug)
{
    // Récupérer le produit par son slug
    $produit = $this->Produit_model->get_produit_by_slug($slug);
    
    if (!$produit) {
        $this->session->set_flashdata('error', 'Produit non trouvé.');
        redirect(base_url('Produits'));
    }
    
    $produit_id = $produit['id_produit'];
    
    // Récupérer les images du produit
    $images = $this->Produit_model->get_images_by_produit_id($produit_id);
    
    // Convertir le produit en objet pour la vue
    $data['produit'] = (object)$produit;
    
    // Convertir les images en objets
    $data['images'] = [];
    foreach ($images as $img) {
        $data['images'][] = (object)$img;
    }
    
    // Charger la vue
    $this->load->view('image_list', $data);
}

    // Traitement de la modification
    private function process_edit($slug, $produit_id)
    {
        $produit_existant = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit_existant) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('Produits'));
            return;
        }
        
        $nom_produit = trim($this->input->post('nom_produit'));
        $id_categorie = $this->input->post('id_categorie');
        $id_vendeur = $this->input->post('id_vendeur');
        $prix_base = $this->input->post('prix_base');
        $quantite_actuelle = $this->input->post('quantite_actuelle');
        
        if (empty($nom_produit) || empty($prix_base)) {
            $this->session->set_flashdata('error', 'Le nom du produit et le prix sont requis.');
            redirect(base_url('Produits/edit/' . $slug));
            return;
        }
        
        // Mettre à jour le slug si le nom change
        $new_slug = $produit_existant['slug_produit'];
        if ($nom_produit != $produit_existant['nom_produit']) {
            $new_slug = $this->Produit_model->generate_unique_slug($nom_produit, $produit_id);
        }
        
        $date_debut_promo = $this->input->post('date_debut_promo');
        $date_fin_promo = $this->input->post('date_fin_promo');
        
        $seuil_stock_bas = $this->input->post('seuil_stock_bas') ?: 5;
        if ($quantite_actuelle <= 0) {
            $statut_stock = 'rupture_stock';
        } elseif ($quantite_actuelle <= $seuil_stock_bas) {
            $statut_stock = 'stock_bas';
        } else {
            $statut_stock = 'en_stock';
        }
        
        $update_data = [
            'id_vendeur' => !empty($id_vendeur) ? $id_vendeur : null,
            'id_categorie' => !empty($id_categorie) ? $id_categorie : null,
            'nom_produit' => $nom_produit,
            'slug_produit' => $new_slug,
            'description_courte' => $this->input->post('description_courte'),
            'description' => $this->input->post('description'),
            'marque' => $this->input->post('marque'),
            'prix_base' => $prix_base,
            'prix_promo' => $this->input->post('prix_promo') ?: null,
            'date_debut_promo' => !empty($date_debut_promo) ? date('Y-m-d H:i:s', strtotime($date_debut_promo)) : null,
            'date_fin_promo' => !empty($date_fin_promo) ? date('Y-m-d H:i:s', strtotime($date_fin_promo)) : null,
            'quantite_actuelle' => $quantite_actuelle ?: 0,
            'seuil_stock_bas' => $seuil_stock_bas,
            'statut_stock' => $statut_stock,
            'poids_kg' => $this->input->post('poids_kg') ?: null,
            'type_produit' => $this->input->post('type_produit'),
            'statut' => $this->input->post('statut'),
            'date_publication' => $this->input->post('statut') == 'actif' && $produit_existant['statut'] != 'actif' ? date('Y-m-d H:i:s') : $produit_existant['date_publication'],
            'est_actif' => $this->input->post('est_actif') ? 1 : 0,
            'date_modification' => date('Y-m-d H:i:s')
        ];
        
        $this->Produit_model->update_produit_by_id($produit_id, $update_data);
        
        // Gestion des nouvelles images
        if (!empty($_FILES['images']['name'][0])) {
            $this->upload_images($produit_id, $_FILES['images']);
        }
        
        $this->session->set_flashdata('success', 'Produit modifié avec succès.');
        redirect(base_url('Produits'));
    }

    // Supprimer un produit par SLUG
    public function delete($slug)
    {
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('Produits'));
        }
        
        $produit_id = $produit['id_produit'];
        
        // Supprimer les images physiques
        $images = $this->Produit_model->get_images_by_produit_id($produit_id);
        foreach ($images as $img) {
            if (!empty($img['url_image']) && file_exists(FCPATH . ltrim($img['url_image'], '/'))) {
                unlink(FCPATH . ltrim($img['url_image'], '/'));
            }
            if (!empty($img['url_miniature']) && file_exists(FCPATH . ltrim($img['url_miniature'], '/'))) {
                unlink(FCPATH . ltrim($img['url_miniature'], '/'));
            }
            $this->Produit_model->delete_image($img['id_image']);
        }
        
        // Supprimer les variantes
        $this->db->delete('variantes_produit', ['id_produit' => $produit_id]);
        
        // Supprimer le produit (soft delete)
        $this->Produit_model->delete_produit_by_id($produit_id);
        
        $this->session->set_flashdata('success', 'Produit supprimé avec succès.');
        redirect(base_url('Produits'));
    }

    // Voir les détails d'un produit par SLUG
    public function view($slug)
    {
        $data['produit'] = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$data['produit']) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('Produits'));
        }
        
        $produit_id = $data['produit']['id_produit'];
        
        // Récupérer les images
        $data['images'] = $this->Produit_model->get_images_by_produit_id($produit_id);
        
        // Incrémenter le nombre de vues
        $this->Produit_model->increment_vues_by_slug($slug);
        
        $this->load->view('produits_view', $data);
    }

    // Activer/Désactiver un produit par SLUG
    public function toggle_status($slug)
    {
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('Produits'));
        }
        
        $new_status = $produit['est_actif'] == 1 ? 0 : 1;
        $this->Produit_model->toggle_status_by_slug($slug, $new_status);
        
        $this->session->set_flashdata('success', 'Statut du produit modifié avec succès.');
        redirect(base_url('Produits'));
    }

   




    // Upload multiple d'images
    private function upload_images($produit_id, $files)
    {
        $ref_folder = FCPATH . 'uploads/produits/';
        
        if (!is_dir($ref_folder)) {
            mkdir($ref_folder, 0777, TRUE);
        }
        
        // Récupérer les images existantes
        $existing_images = $this->Produit_model->get_images_by_produit_id($produit_id);
        $ordre_actuel = count($existing_images);
        $is_first_upload = ($ordre_actuel == 0);
        
        $uploaded = 0;
        
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] == 0) {
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $filename = date("YmdHis") . '_' . uniqid() . '.' . strtolower($ext);
                
                if (move_uploaded_file($files['tmp_name'][$i], $ref_folder . $filename)) {
                    
                    // Créer la miniature
                    $thumb_name = 'thumb_' . $filename;
                    $this->create_thumbnail($ref_folder . $filename, $ref_folder . $thumb_name);
                    
                    // Déterminer si c'est l'image principale
                    $est_principale = 0;
                    if ($is_first_upload && $i == 0 && $ordre_actuel == 0) {
                        $est_principale = 1;
                    }
                    
                    $produit = $this->Produit_model->get_produit_by_id($produit_id);
                    
                    $insert_data = [
                        'id_produit' => $produit_id,
                        'url_image' => 'uploads/produits/' . $filename,
                        'url_miniature' => 'uploads/produits/' . $thumb_name,
                        'texte_alt' => $produit['nom_produit'] ?? 'Image produit',
                        'est_principale' => $est_principale,
                        'ordre_affichage' => $ordre_actuel + $uploaded + 1,
                        'date_creation' => date('Y-m-d H:i:s')
                    ];
                    
                    if ($this->Produit_model->add_image($insert_data)) {
                        $uploaded++;
                    }
                }
            }
        }
        
        return $uploaded;
    }

    // Créer une miniature
    private function create_thumbnail($source_path, $dest_path, $width = 150, $height = 150)
    {
        if (!file_exists($source_path)) {
            return false;
        }
        
        $image_info = getimagesize($source_path);
        if (!$image_info) {
            return false;
        }
        
        switch ($image_info['mime']) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
                $source = imagecreatefrompng($source_path);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($source_path);
                break;
            default:
                return false;
        }
        
        $src_width = imagesx($source);
        $src_height = imagesy($source);
        
        $ratio = max($width / $src_width, $height / $src_height);
        $new_width = $src_width * $ratio;
        $new_height = $src_height * $ratio;
        
        $thumbnail = imagecreatetruecolor($width, $height);
        
        if ($image_info['mime'] == 'image/png') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $width, $height, $transparent);
        }
        
        $x_offset = ($src_width - $new_width / $ratio) / 2;
        $y_offset = ($src_height - $new_height / $ratio) / 2;
        
        imagecopyresampled($thumbnail, $source, 
            0, 0, 
            $x_offset, $y_offset, 
            $width, $height, 
            $new_width / $ratio, $new_height / $ratio);
        
        switch ($image_info['mime']) {
            case 'image/jpeg':
                imagejpeg($thumbnail, $dest_path, 85);
                break;
            case 'image/png':
                imagepng($thumbnail, $dest_path, 8);
                break;
            case 'image/gif':
                imagegif($thumbnail, $dest_path);
                break;
        }
        
        imagedestroy($source);
        imagedestroy($thumbnail);
        
        return true;
    }

    // Supprimer une image (AJAX)
    public function delete_image($id_image)
    {
        $this->output->set_content_type('application/json');
        
        $image = $this->db->select('*')->from('images_produit')->where('id_image', $id_image)->get()->row_array();
        
        if ($image) {
            // Supprimer les fichiers physiques
            $full_path = FCPATH . ltrim($image['url_image'], '/');
            if (file_exists($full_path)) {
                unlink($full_path);
            }
            
            if (!empty($image['url_miniature'])) {
                $thumb_path = FCPATH . ltrim($image['url_miniature'], '/');
                if (file_exists($thumb_path)) {
                    unlink($thumb_path);
                }
            }
            
            // Supprimer de la base de données
            $this->Produit_model->delete_image($id_image);
            
            $id_produit = $image['id_produit'];
            
            // Vérifier s'il reste des images et définir une nouvelle image principale si nécessaire
            $remaining_images = $this->Produit_model->get_images_by_produit_id($id_produit);
            $has_main = false;
            
            foreach($remaining_images as $img){
                if($img['est_principale'] == 1){
                    $has_main = true;
                    break;
                }
            }
            
            if(!$has_main && !empty($remaining_images)){
                $this->Produit_model->set_main_image($id_produit, $remaining_images[0]['id_image']);
            }
            
            // Récupérer le slug pour redirection
            $produit = $this->Produit_model->get_produit_by_id($id_produit);
            $slug = $produit ? $produit['slug_produit'] : '';
            
            echo json_encode(['success' => true, 'redirect_slug' => $slug]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    // Définir une image comme principale (AJAX)
    public function set_main_image($id_image)
    {
        $this->output->set_content_type('application/json');
        
        $image = $this->db->select('id_produit')->from('images_produit')->where('id_image', $id_image)->get()->row_array();
        
        if ($image) {
            $result = $this->Produit_model->set_main_image($image['id_produit'], $id_image);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    // Générer SKU
    private function generateSku($nom)
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nom), 0, 3));
        if (empty($prefix)) $prefix = 'PRD';
        return $prefix . '-' . date('Ymd') . '-' . rand(100, 999);
    }

    // Générer code produit
    private function generateCodeProduit()
    {
        return 'PROD-' . date('Ymd') . '-' . rand(1000, 9999);
    }
}
?>