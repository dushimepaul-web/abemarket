<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banners_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Récupère toutes les bannières avec pagination
     */
    public function get_all_banners($limit = null, $offset = 0) {
        $this->db->select('*');
        $this->db->from('banners');
        $this->db->order_by('ordre_affichage', 'ASC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Récupère les bannières actives pour le frontend
     * @param string $position - Position spécifique (home_main, home_bottom, etc.)
     * @param int $limit - Nombre de bannières à récupérer
     * @return array
     */
    public function get_active_banners($position = null, $limit = null) {
        $this->db->select('*');
        $this->db->from('banners');
        $this->db->where('est_actif', 1);
        $this->db->where('(date_debut IS NULL OR date_debut <= NOW())', NULL, FALSE);
        $this->db->where('(date_fin IS NULL OR date_fin >= NOW())', NULL, FALSE);
        
        if ($position) {
            $this->db->where('position', $position);
        }
        
        $this->db->order_by('ordre_affichage', 'ASC');
        
        if ($limit !== null) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Récupère les bannières par position spécifique
     * @param string $position - Position (home_main, home_bottom, etc.)
     * @param int $limit - Nombre de bannières
     * @return array
     */
    public function get_banners_by_position($position, $limit = null) {
        return $this->get_active_banners($position, $limit);
    }

    /**
     * Récupère les bannières pour le slider principal (home_main)
     */
    public function get_slider_banners($limit = 5) {
        return $this->get_active_banners('home_main', $limit);
    }

    /**
     * Récupère les bannières pour le bas de page (home_bottom)
     */
    public function get_bottom_banners($limit = 4) {
        return $this->get_active_banners('home_bottom', $limit);
    }

    /**
     * Récupère une bannière par son ID
     */
    public function get_banner_by_id($id) {
        if (!is_numeric($id)) {
            return null;
        }
        
        $this->db->where('id_banner', (int)$id);
        $query = $this->db->get('banners');
        return $query->row();
    }

    /**
     * Ajoute une bannière
     */
    public function add_banner($data) {
        // Validation des données
        if (empty($data['title']) || empty($data['image'])) {
            return false;
        }
        
        $this->db->insert('banners', $data);
        return $this->db->insert_id();
    }

    /**
     * Met à jour une bannière
     */
    public function update_banner($id, $data) {
        if (!is_numeric($id)) {
            return false;
        }
        
        $this->db->where('id_banner', (int)$id);
        return $this->db->update('banners', $data);
    }

    /**
     * Supprime une bannière
     */
    public function delete_banner($id) {
        if (!is_numeric($id)) {
            return false;
        }
        
        $this->db->where('id_banner', (int)$id);
        return $this->db->delete('banners');
    }

    /**
     * Compte le nombre total de bannières
     * @param int $est_actif - Filtrer par statut (null = tous)
     */
    public function count_all($est_actif = null) {
        if ($est_actif !== null) {
            $this->db->where('est_actif', $est_actif);
        }
        return $this->db->count_all_results('banners');
    }

    /**
     * Compte les bannières actives
     */
    public function count_active() {
        $this->db->where('est_actif', 1);
        $this->db->where('(date_debut IS NULL OR date_debut <= NOW())', NULL, FALSE);
        $this->db->where('(date_fin IS NULL OR date_fin >= NOW())', NULL, FALSE);
        return $this->db->count_all_results('banners');
    }

    /**
     * Compte les bannières par position
     */
    public function count_by_position($position) {
        $this->db->where('position', $position);
        return $this->db->count_all_results('banners');
    }

    /**
     * Change le statut d'une bannière
     */
    public function toggle_status($id, $status) {
        if (!is_numeric($id)) {
            return false;
        }
        
        $this->db->where('id_banner', (int)$id);
        return $this->db->update('banners', ['est_actif' => (int)$status]);
    }

    /**
     * Supprime les bannières expirées
     */
    public function delete_expired_banners() {
        $this->db->where('date_fin <', date('Y-m-d H:i:s'));
        $this->db->where('date_fin IS NOT NULL');
        return $this->db->delete('banners');
    }

    /**
     * Désactive les bannières expirées (sans les supprimer)
     */
    public function disable_expired_banners() {
        $this->db->set('est_actif', 0);
        $this->db->where('date_fin <', date('Y-m-d H:i:s'));
        $this->db->where('date_fin IS NOT NULL');
        $this->db->where('est_actif', 1);
        return $this->db->update('banners');
    }

    /**
     * Récupère les bannières à expirer bientôt
     * @param int $days - Nombre de jours avant expiration
     */
    public function get_expiring_banners($days = 7) {
        $this->db->select('*');
        $this->db->from('banners');
        $this->db->where('est_actif', 1);
        $this->db->where('date_fin IS NOT NULL');
        $this->db->where('date_fin <=', date('Y-m-d H:i:s', strtotime("+$days days")));
        $this->db->where('date_fin >', date('Y-m-d H:i:s'));
        $this->db->order_by('date_fin', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
}
?>