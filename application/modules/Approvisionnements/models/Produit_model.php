<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ============================================
    // Méthodes de base pour les produits
    // ============================================

    /**
     * Récupérer tous les produits d'un vendeur
     */
    public function get_produits_by_vendeur($id_vendeur, $limit = null, $offset = null) {
        $this->db->select('p.*, c.nom_categorie')
                 ->from('produits p')
                 ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                 ->where('p.id_vendeur', $id_vendeur)
                 ->where('p.statut !=', 'supprime')
                 ->order_by('p.date_creation', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Récupérer tous les produits (admin)
     */
    public function get_all_produits($limit = null, $offset = null) {
        $this->db->select('p.*, c.nom_categorie, v.nom_boutique')
                 ->from('produits p')
                 ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                 ->join('vendeurs v', 'p.id_vendeur = v.id_vendeur', 'left')
                 ->where('p.statut !=', 'supprime')
                 ->order_by('p.date_creation', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Récupérer un produit par son ID
     */
    public function get_produit_by_id($id_produit, $id_vendeur = null) {
        $this->db->select('p.*, c.nom_categorie')
                 ->from('produits p')
                 ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                 ->where('p.id_produit', $id_produit);
        
        if ($id_vendeur) {
            $this->db->where('p.id_vendeur', $id_vendeur);
        }
        
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Récupérer un produit par son slug
     */
    public function get_produit_by_slug($slug) {
        $this->db->where('slug_produit', $slug);
        $query = $this->db->get('produits');
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        
        return null;
    }

    /**
     * Ajouter un produit
     */
    public function add_produit($data) {
        $this->db->insert('produits', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier un produit
     */
    public function update_produit($id_produit, $data, $id_vendeur = null) {
        if ($id_vendeur) {
            $this->db->where('id_vendeur', $id_vendeur);
        }
        $this->db->where('id_produit', $id_produit);
        $this->db->update('produits', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un produit (soft delete)
     */
    public function delete_produit($id_produit, $id_vendeur = null) {
        if ($id_vendeur) {
            $this->db->where('id_vendeur', $id_vendeur);
        }
        $this->db->where('id_produit', $id_produit);
        $this->db->update('produits', ['statut' => 'supprime']);
        return $this->db->affected_rows() > 0;
    }

    // ============================================
    // Méthodes pour la pagination et les filtres
    // ============================================

    /**
     * Compter tous les produits avec filtres (pour admin)
     */
    public function count_all_produits($filters = []) {
        if (!empty($filters['id_categorie'])) {
            $this->db->where('id_categorie', $filters['id_categorie']);
        }
        if (!empty($filters['statut_stock'])) {
            $this->db->where('statut_stock', $filters['statut_stock']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nom_produit', $filters['search']);
            $this->db->or_like('sku', $filters['search']);
            $this->db->group_end();
        }
        $this->db->where('statut !=', 'supprime');
        return $this->db->count_all_results('produits');
    }

    /**
     * Récupérer tous les produits paginés avec filtres (pour admin)
     */
    public function get_all_produits_paginated($limit, $offset, $filters = []) {
        $this->db->select('p.*, c.nom_categorie')
                 ->from('produits p')
                 ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                 ->where('p.statut !=', 'supprime');
        
        if (!empty($filters['id_categorie'])) {
            $this->db->where('p.id_categorie', $filters['id_categorie']);
        }
        if (!empty($filters['statut_stock'])) {
            $this->db->where('p.statut_stock', $filters['statut_stock']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('p.nom_produit', $filters['search']);
            $this->db->or_like('p.sku', $filters['search']);
            $this->db->group_end();
        }
        
        $this->db->order_by('p.date_creation', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Compter les produits d'un vendeur avec filtres
     */
    public function count_produits_by_vendeur($id_vendeur, $filters = []) {
        $this->db->where('id_vendeur', $id_vendeur);
        $this->db->where('statut !=', 'supprime');
        
        if (!empty($filters['id_categorie'])) {
            $this->db->where('id_categorie', $filters['id_categorie']);
        }
        if (!empty($filters['statut_stock'])) {
            $this->db->where('statut_stock', $filters['statut_stock']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nom_produit', $filters['search']);
            $this->db->or_like('sku', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results('produits');
    }

    /**
     * Récupérer les produits d'un vendeur paginés avec filtres
     */
    public function get_produits_by_vendeur_paginated($id_vendeur, $limit, $offset, $filters = []) {
        $this->db->select('p.*, c.nom_categorie')
                 ->from('produits p')
                 ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                 ->where('p.id_vendeur', $id_vendeur)
                 ->where('p.statut !=', 'supprime');
        
        if (!empty($filters['id_categorie'])) {
            $this->db->where('p.id_categorie', $filters['id_categorie']);
        }
        if (!empty($filters['statut_stock'])) {
            $this->db->where('p.statut_stock', $filters['statut_stock']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('p.nom_produit', $filters['search']);
            $this->db->or_like('p.sku', $filters['search']);
            $this->db->group_end();
        }
        
        $this->db->order_by('p.date_creation', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    // ============================================
    // Méthodes pour les statistiques
    // ============================================

    /**
     * Statistiques des produits (admin)
     */
    public function get_produits_stats() {
        $this->db->where('statut !=', 'supprime');
        $total = $this->db->count_all_results('produits');
        
        $actifs = $this->db->where('est_actif', 1)->where('statut !=', 'supprime')->count_all_results('produits');
        $inactifs = $this->db->where('est_actif', 0)->where('statut !=', 'supprime')->count_all_results('produits');
        $stock_bas = $this->db->where('statut_stock', 'stock_bas')->where('statut !=', 'supprime')->count_all_results('produits');
        $rupture = $this->db->where('statut_stock', 'rupture_stock')->where('statut !=', 'supprime')->count_all_results('produits');
        
        return [
            'total' => $total,
            'actifs' => $actifs,
            'inactifs' => $inactifs,
            'stock_bas' => $stock_bas,
            'rupture' => $rupture
        ];
    }

    /**
     * Statistiques des produits par vendeur
     */
    public function get_produits_stats_by_vendeur($id_vendeur) {
        $this->db->where('id_vendeur', $id_vendeur);
        $this->db->where('statut !=', 'supprime');
        $total = $this->db->count_all_results('produits');
        
        $actifs = $this->db->where('id_vendeur', $id_vendeur)->where('est_actif', 1)->where('statut !=', 'supprime')->count_all_results('produits');
        $inactifs = $this->db->where('id_vendeur', $id_vendeur)->where('est_actif', 0)->where('statut !=', 'supprime')->count_all_results('produits');
        $stock_bas = $this->db->where('id_vendeur', $id_vendeur)->where('statut_stock', 'stock_bas')->where('statut !=', 'supprime')->count_all_results('produits');
        $rupture = $this->db->where('id_vendeur', $id_vendeur)->where('statut_stock', 'rupture_stock')->where('statut !=', 'supprime')->count_all_results('produits');
        
        return [
            'total' => $total,
            'actifs' => $actifs,
            'inactifs' => $inactifs,
            'stock_bas' => $stock_bas,
            'rupture' => $rupture
        ];
    }

    // ============================================
    // Méthodes pour les images
    // ============================================

    /**
     * Récupérer l'image principale d'un produit
     */
    public function get_main_image($id_produit) {
        $this->db->select('url_miniature, url_image');
        $this->db->where('id_produit', $id_produit);
        $this->db->where('est_principale', 1);
        $this->db->limit(1);
        $image = $this->db->get('images_produit')->row();
        
        if ($image) {
            return $image->url_miniature ?: $image->url_image;
        }
        
        // Si pas d'image principale, prendre la première image
        $this->db->select('url_miniature, url_image');
        $this->db->where('id_produit', $id_produit);
        $this->db->limit(1);
        $image = $this->db->get('images_produit')->row();
        
        return $image ? ($image->url_miniature ?: $image->url_image) : null;
    }

    /**
     * Ajouter une image à un produit
     */
    public function add_image($data) {
        $this->db->insert('images_produit', $data);
        return $this->db->insert_id();
    }

    // ============================================
    // Méthodes de vérification
    // ============================================

    /**
     * Vérifier si un produit appartient à un vendeur
     */
    public function check_produit_owner($id_produit, $id_vendeur) {
        $query = $this->db->where('id_produit', $id_produit)
                          ->where('id_vendeur', $id_vendeur)
                          ->get('produits');
        return $query->num_rows() > 0;
    }

    /**
     * Générer un slug unique
     */
    public function generate_unique_slug($nom, $id_exclude = null) {
        $slug = url_title(convert_accented_characters($nom), 'dash', true);
        $slug = strtolower($slug);
        
        $this->db->where('slug_produit', $slug);
        if ($id_exclude) {
            $this->db->where('id_produit !=', $id_exclude);
        }
        $count = $this->db->count_all_results('produits');
        
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return $slug;
    }
}
?>