<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageProduit extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Produit_model');
        $this->load->library('form_validation');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Créer le dossier d'upload s'il n'existe pas
        $upload_path = FCPATH . 'uploads/produits/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }
    }

    private function is_admin() {
        $user_id = $this->session->userdata('id_utilisateur');
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id)
                 ->where_in('up.id_profil', [1, 2]);
        return $this->db->get()->num_rows() > 0;
    }

    private function get_vendeur_id() {
        $user_id = $this->session->userdata('id_utilisateur');
        $vendeur = $this->db->where('id_utilisateur', $user_id)->get('vendeurs')->row();
        return $vendeur ? $vendeur->id_vendeur : null;
    }

    public function index($id_produit) {
        $data['produit'] = $this->Produit_model->get_produit_by_id($id_produit);
        
        if (!$data['produit']) {
            show_404();
        }
        
        // Vérifier les droits
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $data['produit']->id_vendeur != $id_vendeur) {
            show_error('Accès non autorisé', 403);
        }
        
        $data['title'] = 'Gestion des images - ' . $data['produit']->nom_produit;
        
        // Récupérer les images
        $this->db->where('id_produit', $id_produit);
        $this->db->order_by('ordre_affichage', 'ASC');
        $data['images'] = $this->db->get('images_produit')->result();
        
        $this->load->view('image_list', $data);
    }











public function upload($id_produit) {
    // Désactiver l'affichage des erreurs pour ne pas polluer le JSON
    error_reporting(0);
    
    $produit = $this->Produit_model->get_produit_by_id($id_produit);
    
    if (!$produit) {
        echo json_encode(['success' => false, 'message' => 'Produit non trouvé']);
        return;
    }
    
    // Vérifier les droits
    $id_vendeur = $this->get_vendeur_id();
    if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
        echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
        return;
    }
    
    // Vérifier si un fichier a été uploadé
    if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Aucun fichier valide']);
        return;
    }
    
    // Créer le dossier d'upload
    $upload_dir = FCPATH . 'uploads/produits/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Générer un nom unique
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = date('Ymd_His') . '_' . uniqid() . '.' . strtolower($ext);
    $target_path = $upload_dir . $filename;
    
    // Déplacer le fichier
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors du déplacement']);
        return;
    }
    
    // Créer la miniature
    $thumb_name = 'thumb_' . $filename;
    $thumb_path = $upload_dir . $thumb_name;
    $this->create_thumbnail($target_path, $thumb_path);
    
    // Récupérer les données
    $texte_alt = $this->input->post('texte_alt');
    if (empty($texte_alt)) {
        $texte_alt = $produit->nom_produit;
    }
    
    // Déterminer l'ordre
    $this->db->select_max('ordre_affichage');
    $this->db->where('id_produit', $id_produit);
    $max = $this->db->get('images_produit')->row();
    $ordre_affichage = ($max->ordre_affichage ?? 0) + 1;
    
    $est_principale = $this->input->post('est_principale') ? 1 : 0;
    
    // Vérifier si c'est la première image
    $this->db->where('id_produit', $id_produit);
    $count = $this->db->count_all_results('images_produit');
    
    if ($count == 0) {
        $est_principale = 1;
    }
    
    // Si cette image est principale, retirer le statut des autres
    if ($est_principale == 1 && $count > 0) {
        $this->db->where('id_produit', $id_produit);
        $this->db->update('images_produit', ['est_principale' => 0]);
    }
    
    // Insérer dans la base
    $data = [
        'id_produit' => $id_produit,
        'url_image' => 'uploads/produits/' . $filename,
        'url_miniature' => 'uploads/produits/' . $thumb_name,
        'texte_alt' => $texte_alt,
        'est_principale' => $est_principale,
        'ordre_affichage' => $ordre_affichage,
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    $inserted = $this->db->insert('images_produit', $data);
    
    if ($inserted) {
        // Nettoyer le buffer de sortie avant d'envoyer le JSON
        if (ob_get_length()) ob_clean();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true, 
            'message' => 'Image uploadée avec succès',
            'id' => $this->db->insert_id()
        ]);
    } else {
        // Supprimer les fichiers si erreur
        if (file_exists($target_path)) unlink($target_path);
        if (file_exists($thumb_path)) unlink($thumb_path);
        
        echo json_encode(['success' => false, 'message' => 'Erreur base de données']);
    }
}












    private function create_thumbnail($source_path, $dest_path, $width = 150, $height = 150) {
        if (!file_exists($source_path)) {
            return false;
        }
        
        $image_info = getimagesize($source_path);
        if (!$image_info) {
            return false;
        }
        
        $source = null;
        switch ($image_info['mime']) {
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
                $source = imagecreatefrompng($source_path);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($source_path);
                break;
            case 'image/webp':
                $source = imagecreatefromwebp($source_path);
                break;
            default:
                return false;
        }
        
        if (!$source) {
            return false;
        }
        
        $src_width = imagesx($source);
        $src_height = imagesy($source);
        
        // Calculer les proportions
        if ($src_width > $src_height) {
            $new_width = $width;
            $new_height = intval($src_height * $width / $src_width);
        } else {
            $new_height = $height;
            $new_width = intval($src_width * $height / $src_height);
        }
        
        $thumbnail = imagecreatetruecolor($new_width, $new_height);
        
        // Gérer la transparence pour PNG et WEBP
        if ($image_info['mime'] == 'image/png' || $image_info['mime'] == 'image/webp') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefilledrectangle($thumbnail, 0, 0, $new_width, $new_height, $transparent);
        }
        
        imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height);
        
        switch ($image_info['mime']) {
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($thumbnail, $dest_path, 85);
                break;
            case 'image/png':
                imagepng($thumbnail, $dest_path, 8);
                break;
            case 'image/gif':
                imagegif($thumbnail, $dest_path);
                break;
            case 'image/webp':
                imagewebp($thumbnail, $dest_path, 85);
                break;
        }
        
        imagedestroy($source);
        imagedestroy($thumbnail);
        
        return true;
    }

    public function update($id) {
        $this->db->where('id_image', $id);
        $image = $this->db->get('images_produit')->row();
        
        if (!$image) {
            echo json_encode(['success' => false, 'message' => 'Image non trouvée']);
            return;
        }
        
        $produit = $this->Produit_model->get_produit_by_id($image->id_produit);
        
        // Vérifier les droits
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        $est_principale = $this->input->post('est_principale') ? 1 : 0;
        
        // Si cette image devient principale, retirer le statut des autres
        if ($est_principale == 1) {
            $this->db->where('id_produit', $image->id_produit);
            $this->db->where('id_image !=', $id);
            $this->db->update('images_produit', ['est_principale' => 0]);
        }
        
        $data = [
            'texte_alt' => $this->input->post('texte_alt'),
            'est_principale' => $est_principale,
            'ordre_affichage' => $this->input->post('ordre_affichage') ?: 0
        ];
        
        $this->db->where('id_image', $id);
        $updated = $this->db->update('images_produit', $data);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Image mise à jour' : 'Aucune modification'
        ]);
    }

    public function delete($id) {
        $this->db->where('id_image', $id);
        $image = $this->db->get('images_produit')->row();
        
        if (!$image) {
            echo json_encode(['success' => false, 'message' => 'Image non trouvée']);
            return;
        }
        
        $produit = $this->Produit_model->get_produit_by_id($image->id_produit);
        
        // Vérifier les droits
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        // Supprimer les fichiers physiques
        $full_path = FCPATH . $image->url_image;
        if (file_exists($full_path)) {
            unlink($full_path);
        }
        
        $thumb_path = FCPATH . $image->url_miniature;
        if (file_exists($thumb_path)) {
            unlink($thumb_path);
        }
        
        // Supprimer de la base de données
        $this->db->where('id_image', $id);
        $deleted = $this->db->delete('images_produit');
        
        // Si l'image supprimée était principale, définir une nouvelle image principale
        if ($image->est_principale == 1) {
            $this->db->where('id_produit', $image->id_produit);
            $this->db->order_by('ordre_affichage', 'ASC');
            $this->db->limit(1);
            $first_image = $this->db->get('images_produit')->row();
            
            if ($first_image) {
                $this->db->where('id_image', $first_image->id_image);
                $this->db->update('images_produit', ['est_principale' => 1]);
            }
        }
        
        echo json_encode([
            'success' => $deleted,
            'message' => $deleted ? 'Image supprimée avec succès' : 'Erreur lors de la suppression'
        ]);
    }

    public function definir_principale($id) {
        $this->db->where('id_image', $id);
        $image = $this->db->get('images_produit')->row();
        
        if (!$image) {
            echo json_encode(['success' => false, 'message' => 'Image non trouvée']);
            return;
        }
        
        $produit = $this->Produit_model->get_produit_by_id($image->id_produit);
        
        // Vérifier les droits
        $id_vendeur = $this->get_vendeur_id();
        if (!$this->is_admin() && $produit->id_vendeur != $id_vendeur) {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            return;
        }
        
        // Retirer le statut principal de toutes les images du produit
        $this->db->where('id_produit', $image->id_produit);
        $this->db->update('images_produit', ['est_principale' => 0]);
        
        // Définir cette image comme principale
        $this->db->where('id_image', $id);
        $updated = $this->db->update('images_produit', ['est_principale' => 1]);
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Image définie comme principale' : 'Erreur'
        ]);
    }

    public function reordonner() {
        $orders = $this->input->post('orders');
        
        if (empty($orders) || !is_array($orders)) {
            echo json_encode(['success' => false, 'message' => 'Aucune donnée reçue']);
            return;
        }
        
        $updated = true;
        foreach ($orders as $id => $ordre) {
            $this->db->where('id_image', $id);
            if (!$this->db->update('images_produit', ['ordre_affichage' => (int)$ordre])) {
                $updated = false;
            }
        }
        
        echo json_encode([
            'success' => $updated,
            'message' => $updated ? 'Ordre mis à jour' : 'Erreur lors de la mise à jour'
        ]);
    }
}
?>