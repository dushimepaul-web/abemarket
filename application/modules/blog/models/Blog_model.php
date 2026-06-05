<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ========== ARTICLES ==========
    
    public function getAllPosts($limit = 20, $offset = 0, $status = null) {
        $this->db->select('p.*, c.nom as categorie_nom, c.slug as categorie_slug, u.prenom, u.nom as auteur_nom');
        $this->db->from('blog_posts p');
        $this->db->join('blog_categories c', 'c.id_categorie = p.id_categorie', 'left');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = p.id_utilisateur', 'left');
        
        if ($status) {
            $this->db->where('p.status', $status);
        }
        
        $this->db->order_by('p.date_creation', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function countAllPosts($status = null) {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results('blog_posts');
    }

    public function getPostById($id) {
        $this->db->select('p.*, c.nom as categorie_nom, u.prenom, u.nom as auteur_nom');
        $this->db->from('blog_posts p');
        $this->db->join('blog_categories c', 'c.id_categorie = p.id_categorie', 'left');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = p.id_utilisateur', 'left');
        $this->db->where('p.id_post', $id);
        return $this->db->get()->row_array();
    }

    public function getPostBySlug($slug) {
        $this->db->where('slug', $slug);
        return $this->db->get('blog_posts')->row_array();
    }

    public function addPost($data) {
        $this->db->insert('blog_posts', $data);
        return $this->db->insert_id();
    }

    public function updatePost($id, $data) {
        $this->db->where('id_post', $id);
        return $this->db->update('blog_posts', $data);
    }

    public function deletePost($id) {
        $this->db->where('id_post', $id);
        return $this->db->delete('blog_posts');
    }

    public function togglePostStatus($id, $status) {
        $this->db->where('id_post', $id);
        return $this->db->update('blog_posts', ['status' => $status]);
    }

    // ========== CATÉGORIES ==========
    
    public function getAllCategories() {
        $this->db->select('c.*, COUNT(p.id_post) as total_articles');
        $this->db->from('blog_categories c');
        $this->db->join('blog_posts p', 'p.id_categorie = c.id_categorie', 'left');
        $this->db->group_by('c.id_categorie');
        $this->db->order_by('c.ordre_affichage', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getCategoryById($id) {
        $this->db->where('id_categorie', $id);
        return $this->db->get('blog_categories')->row_array();
    }

    public function getCategoryBySlug($slug) {
        $this->db->where('slug', $slug);
        return $this->db->get('blog_categories')->row_array();
    }

    public function addCategory($data) {
        return $this->db->insert('blog_categories', $data);
    }

    public function updateCategory($id, $data) {
        $this->db->where('id_categorie', $id);
        return $this->db->update('blog_categories', $data);
    }

    public function deleteCategory($id) {
        // Vérifier si la catégorie a des articles
        $this->db->where('id_categorie', $id);
        $count = $this->db->count_all_results('blog_posts');
        
        if ($count > 0) {
            return false; // Ne pas supprimer si des articles existent
        }
        
        $this->db->where('id_categorie', $id);
        return $this->db->delete('blog_categories');
    }

    public function toggleCategoryStatus($id, $status) {
        $this->db->where('id_categorie', $id);
        return $this->db->update('blog_categories', ['est_actif' => $status]);
    }

    public function updateCategoryOrder($id, $ordre) {
        $this->db->where('id_categorie', $id);
        return $this->db->update('blog_categories', ['ordre_affichage' => $ordre]);
    }

    // ========== COMMENTAIRES ==========
    
    public function getAllComments($limit = 20, $offset = 0, $status = null) {
        $this->db->select('c.*, p.title as post_title, p.slug as post_slug, u.email, u.prenom, u.nom');
        $this->db->from('blog_comments c');
        $this->db->join('blog_posts p', 'p.id_post = c.id_post');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur', 'left');
        
        if ($status !== null) {
            $this->db->where('c.est_approuve', $status);
        }
        
        $this->db->order_by('c.date_creation', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    public function countAllComments($status = null) {
        if ($status !== null) {
            $this->db->where('est_approuve', $status);
        }
        return $this->db->count_all_results('blog_comments');
    }

    public function getCommentById($id) {
        $this->db->select('c.*, p.title as post_title, p.slug as post_slug, u.email, u.prenom, u.nom');
        $this->db->from('blog_comments c');
        $this->db->join('blog_posts p', 'p.id_post = c.id_post');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur', 'left');
        $this->db->where('c.id_commentaire', $id);
        return $this->db->get()->row_array();
    }

    public function approveComment($id, $status) {
        $this->db->where('id_commentaire', $id);
        return $this->db->update('blog_comments', ['est_approuve' => $status]);
    }

    public function deleteComment($id) {
        $this->db->where('id_commentaire', $id);
        return $this->db->delete('blog_comments');
    }

    // ========== UTILITAIRES ==========
    
    public function generateSlug($title, $table = 'blog_posts', $id = 0) {
        $slug = url_title($title, 'dash', true);
        $slug = substr($slug, 0, 100);
        
        $original_slug = $slug;
        $counter = 1;
        
        while ($this->checkSlugExists($slug, $table, $id)) {
            $slug = $original_slug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    private function checkSlugExists($slug, $table, $id = 0) {
        $this->db->where('slug', $slug);
        if ($id > 0) {
            $this->db->where('id_post !=', $id);
        }
        return $this->db->get($table)->num_rows() > 0;
    }
}