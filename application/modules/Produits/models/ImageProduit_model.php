<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageProduit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer toutes les images d'un produit
     */
    public function get_images_by_produit($id_produit) {
        $query = $this->db->where('id_produit', $id_produit)
                          ->order_by('est_principale', 'DESC')
                          ->order_by('ordre_affichage', 'ASC')
                          ->get('images_produit');
        return $query->result();
    }

    /**
     * Récupérer les images d'une variante
     */
    public function get_images_by_variante($id_variante) {
        $query = $this->db->where('id_variante', $id_variante)
                          ->order_by('ordre_affichage', 'ASC')
                          ->get('images_produit');
        return $query->result();
    }

    /**
     * Récupérer une image par son ID
     */
    public function get_image_by_id($id) {
        $query = $this->db->where('id_image', $id)->get('images_produit');
        return $query->row();
    }

    /**
     * Récupérer l'image principale d'un produit
     */
    public function get_image_principale($id_produit) {
        $query = $this->db->where('id_produit', $id_produit)
                          ->where('est_principale', 1)
                          ->get('images_produit');
        return $query->row();
    }

    /**
     * Ajouter une image
     */
    public function ajouter_image($data) {
        // Si c'est l'image principale, retirer le statut des autres
        if (isset($data['est_principale']) && $data['est_principale'] == 1) {
            $this->retirer_principale($data['id_produit'], $data['id_variante'] ?? null);
        }
        
        $this->db->insert('images_produit', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier une image
     */
    public function modifier_image($id, $data) {
        $image = $this->get_image_by_id($id);
        
        // Si on définit comme principale, retirer le statut des autres
        if (isset($data['est_principale']) && $data['est_principale'] == 1 && $image) {
            $this->retirer_principale($image->id_produit, $image->id_variante);
        }
        
        $this->db->where('id_image', $id);
        $this->db->update('images_produit', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer une image
     */
    public function supprimer_image($id) {
        $image = $this->get_image_by_id($id);
        
        if ($image) {
            // Supprimer le fichier physique
            if (!empty($image->url_image) && file_exists(FCPATH . $image->url_image)) {
                unlink(FCPATH . $image->url_image);
            }
            if (!empty($image->url_miniature) && file_exists(FCPATH . $image->url_miniature)) {
                unlink(FCPATH . $image->url_miniature);
            }
        }
        
        $this->db->where('id_image', $id);
        $this->db->delete('images_produit');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Définir une image comme principale
     */
    public function definir_principale($id) {
        $image = $this->get_image_by_id($id);
        
        if ($image) {
            $this->retirer_principale($image->id_produit, $image->id_variante);
            
            $this->db->where('id_image', $id);
            $this->db->update('images_produit', ['est_principale' => 1]);
            return $this->db->affected_rows() > 0;
        }
        
        return false;
    }

    /**
     * Retirer le statut principale de toutes les images d'un produit/variante
     */
    private function retirer_principale($id_produit, $id_variante = null) {
        $this->db->where('id_produit', $id_produit);
        if ($id_variante) {
            $this->db->where('id_variante', $id_variante);
        }
        $this->db->update('images_produit', ['est_principale' => 0]);
    }

    /**
     * Réorganiser l'ordre des images
     */
    public function reordonner_images($orders) {
        foreach ($orders as $id_image => $ordre) {
            $this->db->where('id_image', $id_image);
            $this->db->update('images_produit', ['ordre_affichage' => $ordre]);
        }
        return true;
    }

    /**
     * Upload d'image
     */
    public function upload_image($file, $type = 'produit') {
        $config['upload_path'] = './uploads/' . $type . 's/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 5120; // 5MB
        $config['encrypt_name'] = true;
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }
        
        $this->load->library('upload', $config);
        
        if ($this->upload->do_upload($file)) {
            $upload_data = $this->upload->data();
            
            // Créer la miniature
            $this->load->library('image_lib');
            
            $thumb_config = [
                'source_image' => $upload_data['full_path'],
                'new_image' => $upload_data['file_path'] . 'thumb_' . $upload_data['file_name'],
                'width' => 150,
                'height' => 150,
                'maintain_ratio' => true
            ];
            
            $this->image_lib->initialize($thumb_config);
            $this->image_lib->resize();
            
            return [
                'success' => true,
                'url_image' => 'uploads/' . $type . 's/' . $upload_data['file_name'],
                'url_miniature' => 'uploads/' . $type . 's/thumb_' . $upload_data['file_name']
            ];
        } else {
            return [
                'success' => false,
                'error' => $this->upload->display_errors('', '')
            ];
        }
    }
}
?>