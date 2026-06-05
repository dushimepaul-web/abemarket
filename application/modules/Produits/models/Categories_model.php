<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function count_all() {
        return $this->db->count_all('categories');
    }

    public function get_all_categories($limit = null, $offset = null) {
        $this->db->select('c.*, p.nom_categorie as parent_nom, p.slug_categorie as parent_slug');
        $this->db->from('categories c');
        $this->db->join('categories p', 'p.id_categorie = c.id_parent', 'left');
        $this->db->order_by('c.niveau', 'ASC');
        $this->db->order_by('c.ordre_affichage', 'ASC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    public function get_parent_categories($exclude_id = null) {
        $this->db->where('id_parent IS NULL');
        $this->db->order_by('nom_categorie', 'ASC');
        
        if ($exclude_id) {
            $this->db->where('id_categorie !=', $exclude_id);
        }
        
        return $this->db->get('categories')->result();
    }

    public function get_categorie_by_id($id) {
        return $this->db->where('id_categorie', $id)->get('categories')->row();
    }

    public function get_categorie_by_slug($slug) {
        return $this->db->where('slug_categorie', $slug)->get('categories')->row();
    }

    public function add_categorie($data) {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    public function update_categorie($id, $data) {
        $this->db->where('id_categorie', $id);
        $this->db->update('categories', $data);
        return $this->db->affected_rows() > 0;
    }

    public function delete_categorie($id) {
        $this->db->where('id_categorie', $id);
        $this->db->delete('categories');
        return $this->db->affected_rows() > 0;
    }

    public function has_children($id) {
        return $this->db->where('id_parent', $id)->count_all_results('categories') > 0;
    }

    // Récupérer TOUTES les catégories pour le select (ordre hiérarchique)
public function get_categories_hierarchique($exclude_id = null) {
    // Récupérer toutes les catégories
    $this->db->order_by('niveau', 'ASC');
    $this->db->order_by('ordre_affichage', 'ASC');
    $this->db->order_by('nom_categorie', 'ASC');
    
    if ($exclude_id) {
        $this->db->where('id_categorie !=', $exclude_id);
    }
    
    $categories = $this->db->get('categories')->result();
    
    // Organiser par niveau pour l'indentation
    $categories_organisees = [];
    foreach ($categories as $cat) {
        $categories_organisees[] = $cat;
    }
    
    return $categories_organisees;
}

// Alternative : Récupération avec arborescence complète
public function get_categories_arborescence($exclude_id = null) {
    $this->db->select('c1.*, c2.nom_categorie as parent_nom');
    $this->db->from('categories c1');
    $this->db->join('categories c2', 'c2.id_categorie = c1.id_parent', 'left');
    $this->db->order_by('c1.niveau', 'ASC');
    $this->db->order_by('c1.ordre_affichage', 'ASC');
    
    if ($exclude_id) {
        $this->db->where('c1.id_categorie !=', $exclude_id);
    }
    
    return $this->db->get()->result();
}
}
?>