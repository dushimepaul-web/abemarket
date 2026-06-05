<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendeurs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer un vendeur par ID
     */
    public function get_by_id($id_vendeur) {
        $this->db->where('id_vendeur', $id_vendeur);
        return $this->db->get('vendeurs')->row();
    }

    /**
     * Récupérer un vendeur par ID utilisateur
     */
    public function get_by_user_id($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        return $this->db->get('vendeurs')->row();
    }

    /**
     * Récupérer tous les vendeurs actifs
     */
    public function get_actifs() {
        $this->db->where('statut', 'actif');
        return $this->db->get('vendeurs')->result();
    }
}
?>