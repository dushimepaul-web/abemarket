<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendeur_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ================================
    // LISTE DES VENDEURS
    // ================================
    public function get_all($limit = null, $offset = null, $filters = []) {

        $this->db->select('v.*')
                 ->from('vendeurs v')
                 ->order_by('v.nom_boutique', 'ASC');

        // filtre recherche
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('v.nom_boutique', $filters['search']);
            $this->db->or_like('v.email', $filters['search']);
            $this->db->group_end();
        }

        // filtre statut (si tu as champ statut)
        if (isset($filters['statut']) && $filters['statut'] !== '') {
            $this->db->where('v.statut', $filters['statut']);
        }

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    // ================================
    //  GET BY ID
    // ================================
    public function get_by_id($id) {

        return $this->db->where('id_vendeur', $id)
                        ->get('vendeurs')
                        ->row();
    }

    // ================================
    //  INSERT VENDEUR
    // ================================
    public function insert($data) {

        $this->db->insert('vendeurs', $data);

        return $this->db->insert_id();
    }

    // ================================
    //  UPDATE VENDEUR
    // ================================
    public function update($id, $data) {

        $this->db->where('id_vendeur', $id)
                 ->update('vendeurs', $data);

        return $this->db->affected_rows() > 0;
    }

    // ================================
    //  DELETE VENDEUR
    // ================================
    public function delete($id) {

        $this->db->where('id_vendeur', $id)
                 ->delete('vendeurs');

        return $this->db->affected_rows() > 0;
    }

    // ================================
    //  STATISTIQUES VENDEUR
    // ================================
    public function get_stats($id_vendeur) {

        $this->db->select('
            COUNT(DISTINCT c.id_commande) as total_commandes,
            SUM(c.montant_total) as total_revenus
        ')
        ->from('commandes c')
        ->join('articles_commande ac', 'c.id_commande = ac.id_commande')
        ->where('ac.id_vendeur', $id_vendeur)
        ->where('c.statut_commande', 'livre');

        return $this->db->get()->row();
    }

    // ================================
    //  TOP VENDEURS
    // ================================
    public function get_top_vendeurs($limit = 5) {

        $this->db->select('
            v.id_vendeur,
            v.nom_boutique,
            COUNT(c.id_commande) as nb_commandes,
            SUM(c.montant_total) as total_revenus
        ')
        ->from('vendeurs v')
        ->join('articles_commande ac', 'v.id_vendeur = ac.id_vendeur')
        ->join('commandes c', 'ac.id_commande = c.id_commande')
        ->where('c.statut_commande', 'livre')
        ->group_by('v.id_vendeur')
        ->order_by('total_revenus', 'DESC')
        ->limit($limit);

        return $this->db->get()->result();
    }
}
?>