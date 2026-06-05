<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produits_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer un produit par son ID
     */
    public function get_by_id($id_produit) {
        $this->db->where('id_produit', $id_produit)
                 ->where('statut !=', 'supprime');
        return $this->db->get('produits')->row();
    }

    /**
     * Récupérer un produit par son slug
     */
    public function get_by_slug($slug_produit) {
        $this->db->where('slug_produit', $slug_produit)
                 ->where('statut !=', 'supprime');
        return $this->db->get('produits')->row();
    }

    /**
     * Récupérer tous les produits
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->where('statut !=', 'supprime');
        
        if (!empty($filters['id_categorie'])) {
            $this->db->where('id_categorie', $filters['id_categorie']);
        }
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['statut'])) {
            $this->db->where('statut', $filters['statut']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('date_creation', 'DESC');
        return $this->db->get('produits')->result();
    }
}
?>