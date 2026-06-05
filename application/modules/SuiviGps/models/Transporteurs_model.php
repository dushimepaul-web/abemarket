<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transporteurs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer les transporteurs disponibles
     */
    public function get_disponibles() {
        $this->db->where('est_disponible', 1)
                 ->where('statut', 'actif');
        return $this->db->get('transporteurs')->result();
    }

    /**
     * Récupérer un transporteur par son ID
     */
    public function get_by_id($id_transporteur) {
        $this->db->where('id_transporteur', $id_transporteur);
        return $this->db->get('transporteurs')->row();
    }

    /**
     * Récupérer tous les transporteurs
     */
    public function get_all($limit = null, $offset = null) {
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get('transporteurs')->result();
    }
}
?>