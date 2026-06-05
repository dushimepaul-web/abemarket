<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaiementsVendeurs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer les paiements d'un vendeur
     */
    public function get_by_vendeur($id_vendeur, $limit = null) {
        $this->db->where('id_vendeur', $id_vendeur)
                 ->order_by('date_creation', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get('paiements_vendeurs')->result();
    }

    /**
     * Ajouter un paiement
     */
    public function ajouter($data) {
        $this->db->insert('paiements_vendeurs', $data);
        return $this->db->insert_id();
    }
}
?>