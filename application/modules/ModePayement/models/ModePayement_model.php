<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModePayement_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter tous les modes de paiement
     */
    public function count_all($filters = []) {
        $this->db->from('mode_payement');
        
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('est_actif', $filters['est_actif']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('type', $filters['type']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('code', $filters['search']);
            $this->db->or_like('description', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les modes de paiement
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->order_by('ordre_affichage', 'ASC');
        $this->db->order_by('id_mode_payement', 'ASC');
        
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('est_actif', $filters['est_actif']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('type', $filters['type']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('code', $filters['search']);
            $this->db->or_like('description', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get('mode_payement')->result();
    }

    /**
     * Récupérer les modes de paiement actifs (pour le frontend)
     */
    public function get_actifs() {
        $this->db->where('est_actif', 1)
                 ->order_by('ordre_affichage', 'ASC');
        
        return $this->db->get('mode_payement')->result();
    }

    /**
     * Récupérer un mode de paiement par ID
     */
    public function get_by_id($id) {
        return $this->db->where('id_mode_payement', $id)->get('mode_payement')->row();
    }

    /**
     * Récupérer un mode de paiement par code
     */
    public function get_by_code($code) {
        return $this->db->where('code', $code)->get('mode_payement')->row();
    }

    /**
     * Ajouter un mode de paiement
     */
    public function add($data) {
        $this->db->insert('mode_payement', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier un mode de paiement
     */
    public function update($id, $data) {
        $this->db->where('id_mode_payement', $id);
        $this->db->update('mode_payement', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un mode de paiement
     */
    public function delete($id) {
        $this->db->where('id_mode_payement', $id);
        $this->db->delete('mode_payement');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN est_actif = 1 THEN 1 ELSE 0 END) as actifs,
            SUM(CASE WHEN est_actif = 0 THEN 1 ELSE 0 END) as inactifs,
            SUM(CASE WHEN type = "mobile_money" THEN 1 ELSE 0 END) as mobile_money,
            SUM(CASE WHEN type = "carte_bancaire" THEN 1 ELSE 0 END) as carte_bancaire,
            SUM(CASE WHEN type = "virement" THEN 1 ELSE 0 END) as virement,
            SUM(CASE WHEN type = "especes_livraison" THEN 1 ELSE 0 END) as especes_livraison
        ');
        
        return $this->db->get('mode_payement')->row();
    }
}
?>