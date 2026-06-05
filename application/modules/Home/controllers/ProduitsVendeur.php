<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProduitsVendeur extends MY_Controller
{
    private $vendeur_id;
    
    public function __construct()
    {
        parent::__construct();
        
        // Vérifier si l'utilisateur est connecté
        if ($this->session->userdata('logged_in') !== TRUE) {
            redirect('Admin');
        }
        
        // Vérifier si l'utilisateur est un vendeur
        if (!$this->is_vendeur()) {
            $this->session->set_flashdata('error', 'Vous n\'avez pas les droits d\'accès à cette section.');
            redirect('Dashboard');
        }
        
        // Récupérer l'ID du vendeur connecté
        $this->vendeur_id = $this->get_vendeur_id();
        
        // Charger le modèle
        $this->load->model('Produit_model');
        
        // Créer le dossier d'upload s'il n'existe pas (spécifique au vendeur)
        $upload_path = FCPATH . 'uploads/produits/' . $this->vendeur_id . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }
    }
    
    /**
     * Vérifie si l'utilisateur connecté est un vendeur
     */
    private function is_vendeur()
    {
        $user_id = $this->session->userdata('user_id');
        $vendeur = $this->db->select('*')
            ->from('vendeurs')
            ->where('id_utilisateur', $user_id)
            ->where('est_approuve', 1)
            ->where('statut', 'actif')
            ->get()
            ->row_array();
        
        return !empty($vendeur);
    }
    
    /**
     * Récupère l'ID du vendeur connecté
     */
    private function get_vendeur_id()
    {
        $user_id = $this->session->userdata('user_id');
        $vendeur = $this->db->select('id_vendeur')
            ->from('vendeurs')
            ->where('id_utilisateur', $user_id)
            ->where('est_approuve', 1)
            ->where('statut', 'actif')
            ->get()
            ->row_array();
        
        return $vendeur ? $vendeur['id_vendeur'] : null;
    }
    
    /**
     * Vérifie si un produit appartient au vendeur connecté
     * @param int $produit_id
     * @return bool
     */
    private function produit_appartient_vendeur($produit_id)
    {
        $produit = $this->db->select('id_vendeur')
            ->from('produits')
            ->where('id_produit', $produit_id)
            ->where('id_vendeur', $this->vendeur_id)
            ->get()
            ->row_array();
        
        return !empty($produit);
    }
    
    /**
     * Vérifie si un produit par slug appartient au vendeur
     * @param string $slug
     * @return bool
     */
    private function produit_slug_appartient_vendeur($slug)
    {
        $produit = $this->db->select('id_produit')
            ->from('produits')
            ->where('slug_produit', $slug)
            ->where('id_vendeur', $this->vendeur_id)
            ->get()
            ->row_array();
        
        return !empty($produit);
    }

    /**
     * Liste des produits du vendeur
     */
    public function index()
    {
        // Récupérer uniquement les produits du vendeur connecté
        $data['produits'] = $this->Produit_model->get_produits_by_vendeur($this->vendeur_id);
        
        // Statistiques spécifiques au vendeur
        $stats = $this->Produit_model->get_produits_stats_by_vendeur($this->vendeur_id);
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
        
        // Informations du vendeur
        $data['vendeur_info'] = $this->get_vendeur_info();
        
        $this->load->view('vendeur/produits_list', $data);
    }
    
    /**
     * Récupère les informations du vendeur
     */
    private function get_vendeur_info()
    {
        return $this->db->select('v.*, u.prenom, u.nom, u.email')
            ->from('vendeurs v')
            ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
            ->where('v.id_vendeur', $this->vendeur_id)
            ->get()
            ->row_array();
    }

    /**
     * Ajouter un produit
     */
    public function add()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->process_add();
            return;
        }
        
        // Récupérer les catégories pour les selects
        $data['categories'] = $this->Model->read('categories', ['est_actif' => 1], 'nom_categorie', 'ASC');
        $data['vendeur_info'] = $this->get_vendeur_info();
        $data['is_edit'] = false;
        
        $this->load->view('vendeur/produits_add_edit', $data);
    }
    
    /**
     * Traitement de l'ajout d'un produit
     */
    private function process_add()
    {
        $nom_produit = trim($this->input->post('nom_produit'));
        $id_categorie = $this->input->post('id_categorie');
        $prix_base = $this->input->post('prix_base');
        $quantite_actuelle = $this->input->post('quantite_actuelle');
        
        // Validation
        if (empty($nom_produit) || empty($prix_base)) {
            $this->session->set_flashdata('error', 'Le nom du produit et le prix sont requis.');
            redirect(base_url('ProduitsVendeur/add'));
            return;
        }
        
        // Limite de produits par vendeur (optionnel)
        $max_produits = 50;
        $total_produits = $this->Produit_model->count_produits_by_vendeur($this->vendeur_id);
        
        if ($total_produits >= $max_produits) {
            $this->session->set_flashdata('error', 'Vous avez atteint la limite maximale de ' . $max_produits . ' produits.');
            redirect(base_url('ProduitsVendeur/add'));
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
            'id_vendeur' => $this->vendeur_id,
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
            'statut' => 'en_attente',
            'date_publication' => null,
            'est_actif' => 0,
            'est_validation_admin' => 0,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $produit_id = $this->Produit_model->add_produit($data);
        
        if ($produit_id) {
            // Gestion des images
            if (!empty($_FILES['images']['name'][0])) {
                $this->upload_images($produit_id, $_FILES['images']);
            }
            
            $this->session->set_flashdata('success', 'Produit créé avec succès. En attente de validation par l\'administrateur.');
            redirect(base_url('ProduitsVendeur'));
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la création du produit.');
            redirect(base_url('ProduitsVendeur/add'));
        }
    }

    /**
     * Gestion des variantes d'un produit
     */
    public function variantes($slug)
    {
        // Vérifier si le produit appartient au vendeur
        if (!$this->produit_slug_appartient_vendeur($slug)) {
            $this->session->set_flashdata('error', 'Accès non autorisé à ce produit.');
            redirect(base_url('ProduitsVendeur'));
        }
        
        // Récupérer le produit par son slug
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('ProduitsVendeur'));
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
        $data['produit'] = (object)$produit;
        $data['variantes'] = $variantes;
        $data['title'] = 'Gestion des variantes - ' . $produit['nom_produit'];
        $data['vendeur_info'] = $this->get_vendeur_info();
        
        // Charger la vue
        $this->load->view('vendeur/variantes_list', $data);
    }

    /**
     * Modifier un produit
     */
    public function edit($slug)
    {
        // Vérifier si le produit appartient au vendeur
        if (!$this->produit_slug_appartient_vendeur($slug)) {
            $this->session->set_flashdata('error', 'Accès non autorisé à ce produit.');
            redirect(base_url('ProduitsVendeur'));
        }
        
        $data['produit'] = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$data['produit']) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('ProduitsVendeur'));
        }
        
        $produit_id = $data['produit']['id_produit'];
        
        // Récupérer les images du produit
        $data['images'] = $this->Produit_model->get_images_by_produit_id($produit_id);
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->process_edit($slug, $produit_id);
            return;
        }
        
        $data['categories'] = $this->Model->read('categories', ['est_actif' => 1], 'nom_categorie', 'ASC');
        $data['vendeur_info'] = $this->get_vendeur_info();
        $data['is_edit'] = true;
        
        $this->load->view('vendeur/produits_add_edit', $data);
    }

    /**
     * Gestion des images d'un produit
     */
    public function images($slug)
    {
        // Vérifier si le produit appartient au vendeur
        if (!$this->produit_slug_appartient_vendeur($slug)) {
            $this->session->set_flashdata('error', 'Accès non autorisé à ce produit.');
            redirect(base_url('ProduitsVendeur'));
        }
        
        // Récupérer le produit par son slug
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('ProduitsVendeur'));
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
        
        $data['vendeur_info'] = $this->get_vendeur_info();
        
        // Charger la vue
        $this->load->view('vendeur/image_list', $data);
    }
    
    /**
     * Traitement de la modification
     */
    private function process_edit($slug, $produit_id)
    {
        $produit_existant = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit_existant) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('ProduitsVendeur'));
            return;
        }
        
        $nom_produit = trim($this->input->post('nom_produit'));
        $id_categorie = $this->input->post('id_categorie');
        $prix_base = $this->input->post('prix_base');
        $quantite_actuelle = $this->input->post('quantite_actuelle');
        
        if (empty($nom_produit) || empty($prix_base)) {
            $this->session->set_flashdata('error', 'Le nom du produit et le prix sont requis.');
            redirect(base_url('ProduitsVendeur/edit/' . $slug));
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
            'date_modification' => date('Y-m-d H:i:s')
        ];
        
        $this->Produit_model->update_produit_by_id($produit_id, $update_data);
        
        // Gestion des nouvelles images
        if (!empty($_FILES['images']['name'][0])) {
            $this->upload_images($produit_id, $_FILES['images']);
        }
        
        $this->session->set_flashdata('success', 'Produit modifié avec succès.');
        redirect(base_url('ProduitsVendeur'));
    }

    /**
     * Supprimer un produit
     */
    public function delete($slug)
    {
        // Vérifier si le produit appartient au vendeur
        if (!$this->produit_slug_appartient_vendeur($slug)) {
            $this->session->set_flashdata('error', 'Accès non autorisé à ce produit.');
            redirect(base_url('ProduitsVendeur'));
        }
        
        $produit = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$produit) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('ProduitsVendeur'));
        }
        
        $produit_id = $produit['id_produit'];
        
        // Supprimer les images physiques
        $images = $this->Produit_model->get_images_by_produit_id($produit_id);
        $upload_folder = FCPATH . 'uploads/produits' . $this->vendeur_id . '/';
        
        foreach ($images as $img) {
            if (!empty($img['url_image'])) {
                $image_path = str_replace('uploads/produits/', $upload_folder, $img['url_image']);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            if (!empty($img['url_miniature'])) {
                $thumb_path = str_replace('uploads/produits/', $upload_folder, $img['url_miniature']);
                if (file_exists($thumb_path)) {
                    unlink($thumb_path);
                }
            }
            $this->Produit_model->delete_image($img['id_image']);
        }
        
        // Supprimer les variantes
        $this->db->delete('variantes_produit', ['id_produit' => $produit_id]);
        
        // Supprimer le produit (soft delete)
        $this->Produit_model->delete_produit_by_id($produit_id);
        
        $this->session->set_flashdata('success', 'Produit supprimé avec succès.');
        redirect(base_url('ProduitsVendeur'));
    }

    /**
     * Voir les détails d'un produit
     */
    public function view($slug)
    {
        // Vérifier si le produit appartient au vendeur
        if (!$this->produit_slug_appartient_vendeur($slug)) {
            $this->session->set_flashdata('error', 'Accès non autorisé à ce produit.');
            redirect(base_url('ProduitsVendeur'));
        }
        
        $data['produit'] = $this->Produit_model->get_produit_by_slug($slug);
        
        if (!$data['produit']) {
            $this->session->set_flashdata('error', 'Produit non trouvé.');
            redirect(base_url('ProduitsVendeur'));
        }
        
        $produit_id = $data['produit']['id_produit'];
        
        // Récupérer les images
        $data['images'] = $this->Produit_model->get_images_by_produit_id($produit_id);
        
        $data['vendeur_info'] = $this->get_vendeur_info();
        
        $this->load->view('vendeur/produits_view', $data);
    }

    /**
     * Upload multiple d'images
     */
    private function upload_images($produit_id, $files)
    {
        $ref_folder = FCPATH . 'uploads/produits' . $this->vendeur_id . '/';
        
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
                        'url_image' => 'uploads/produits' . $this->vendeur_id . '/' . $filename,
                        'url_miniature' => 'uploads/produits' . $this->vendeur_id . '/' . $thumb_name,
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

    /**
     * Créer une miniature
     */
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

    /**
     * Supprimer une image (AJAX)
     */
    public function delete_image($id_image)
    {
        // Forcer le type de contenu JSON
        $this->output->set_content_type('application/json');
        
        // Vérifier si c'est une requête AJAX
        if (!$this->input->is_ajax_request()) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Requête non autorisée']));
            return;
        }
        
        $image = $this->db->select('*')
            ->from('images_produit')
            ->where('id_image', $id_image)
            ->get()
            ->row_array();
        
        if (!$image) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Image non trouvée']));
            return;
        }
        
        // Vérifier si l'image appartient à un produit du vendeur
        $produit = $this->db->select('id_vendeur, slug_produit')
            ->from('produits')
            ->where('id_produit', $image['id_produit'])
            ->get()
            ->row_array();
        
        if (!$produit || $produit['id_vendeur'] != $this->vendeur_id) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Accès non autorisé']));
            return;
        }
        
        // Supprimer les fichiers physiques
        $upload_folder = FCPATH . 'uploads/produits' . $this->vendeur_id . '/';
        $image_path = $upload_folder . basename($image['url_image']);
        
        if (file_exists($image_path)) {
            @unlink($image_path);
        }
        
        if (!empty($image['url_miniature'])) {
            $thumb_path = $upload_folder . basename($image['url_miniature']);
            if (file_exists($thumb_path)) {
                @unlink($thumb_path);
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
        
        // Retourner la réponse JSON
        $this->output->set_output(json_encode([
            'success' => true, 
            'redirect_slug' => $produit['slug_produit'],
            'message' => 'Image supprimée avec succès'
        ]));
    }

    /**
     * Définir une image comme principale (AJAX)
     */
    public function set_main_image($id_image)
    {
        // Forcer le type de contenu JSON
        $this->output->set_content_type('application/json');
        
        // Vérifier si c'est une requête AJAX
        if (!$this->input->is_ajax_request()) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Requête non autorisée']));
            return;
        }
        
        $image = $this->db->select('id_produit')
            ->from('images_produit')
            ->where('id_image', $id_image)
            ->get()
            ->row_array();
        
        if (!$image) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Image non trouvée']));
            return;
        }
        
        // Vérifier si l'image appartient à un produit du vendeur
        $produit = $this->db->select('id_vendeur')
            ->from('produits')
            ->where('id_produit', $image['id_produit'])
            ->get()
            ->row_array();
        
        if (!$produit || $produit['id_vendeur'] != $this->vendeur_id) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Accès non autorisé']));
            return;
        }
        
        $result = $this->Produit_model->set_main_image($image['id_produit'], $id_image);
        
        $this->output->set_output(json_encode([
            'success' => $result,
            'message' => $result ? 'Image principale mise à jour' : 'Erreur lors de la mise à jour'
        ]));
    }
    
    /**
     * Réordonner les images (AJAX)
     */
    public function reorder_images()
    {
        $this->output->set_content_type('application/json');
        
        if (!$this->input->is_ajax_request()) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Requête non autorisée']));
            return;
        }
        
        $orders = $this->input->post('orders');
        
        if (empty($orders) || !is_array($orders)) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Données invalides']));
            return;
        }
        
        $success = true;
        
        foreach ($orders as $order) {
            $id_image = $order['id_image'];
            $nouvel_ordre = $order['ordre'];
            
            // Vérifier que l'image appartient au vendeur
            $image = $this->db->select('i.id_image, p.id_vendeur')
                ->from('images_produit i')
                ->join('produits p', 'p.id_produit = i.id_produit')
                ->where('i.id_image', $id_image)
                ->where('p.id_vendeur', $this->vendeur_id)
                ->get()
                ->row_array();
            
            if ($image) {
                $this->db->where('id_image', $id_image)
                         ->update('images_produit', ['ordre_affichage' => $nouvel_ordre]);
            } else {
                $success = false;
            }
        }
        
        $this->output->set_output(json_encode([
            'success' => $success,
            'message' => $success ? 'Ordre des images mis à jour' : 'Erreur lors de la mise à jour'
        ]));
    }
    
    /**
     * Upload d'image en AJAX
     */
    public function ajax_upload_image($produit_id)
    {
        $this->output->set_content_type('application/json');
        
        if (!$this->input->is_ajax_request()) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Requête non autorisée']));
            return;
        }
        
        // Vérifier que le produit appartient au vendeur
        if (!$this->produit_appartient_vendeur($produit_id)) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Accès non autorisé']));
            return;
        }
        
        if (empty($_FILES['file'])) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Aucun fichier uploadé']));
            return;
        }
        
        $ref_folder = FCPATH . 'uploads/produits' . $this->vendeur_id . '/';
        
        if (!is_dir($ref_folder)) {
            mkdir($ref_folder, 0777, TRUE);
        }
        
        $file = $_FILES['file'];
        
        if ($file['error'] != 0) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Erreur lors de l\'upload']));
            return;
        }
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($ext, $allowed)) {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Format de fichier non autorisé']));
            return;
        }
        
        $filename = date("YmdHis") . '_' . uniqid() . '.' . $ext;
        
        if (move_uploaded_file($file['tmp_name'], $ref_folder . $filename)) {
            // Créer la miniature
            $thumb_name = 'thumb_' . $filename;
            $this->create_thumbnail($ref_folder . $filename, $ref_folder . $thumb_name);
            
            // Récupérer les images existantes
            $existing_images = $this->Produit_model->get_images_by_produit_id($produit_id);
            $est_principale = empty($existing_images) ? 1 : 0;
            $ordre_affichage = count($existing_images) + 1;
            
            $produit = $this->Produit_model->get_produit_by_id($produit_id);
            
            $insert_data = [
                'id_produit' => $produit_id,
                'url_image' => 'uploads/produits' . $this->vendeur_id . '/' . $filename,
                'url_miniature' => 'uploads/produits' . $this->vendeur_id . '/' . $thumb_name,
                'texte_alt' => $produit['nom_produit'] ?? 'Image produit',
                'est_principale' => $est_principale,
                'ordre_affichage' => $ordre_affichage,
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            $new_id = $this->Produit_model->add_image($insert_data);
            
            if ($new_id) {
                $this->output->set_output(json_encode([
                    'success' => true,
                    'image_id' => $new_id,
                    'url' => base_url($insert_data['url_image']),
                    'thumb_url' => base_url($insert_data['url_miniature']),
                    'message' => 'Image uploadée avec succès'
                ]));
            } else {
                $this->output->set_output(json_encode(['success' => false, 'error' => 'Erreur lors de l\'enregistrement']));
            }
        } else {
            $this->output->set_output(json_encode(['success' => false, 'error' => 'Erreur lors du déplacement du fichier']));
        }
    }

    /**
     * Générer SKU
     */
    private function generateSku($nom)
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nom), 0, 3));
        if (empty($prefix)) $prefix = 'PRD';
        return $prefix . '-' . date('Ymd') . '-' . rand(100, 999);
    }

    /**
     * Générer code produit
     */
    private function generateCodeProduit()
    {
        return 'PROD-' . date('Ymd') . '-' . rand(1000, 9999);
    }
}
?>