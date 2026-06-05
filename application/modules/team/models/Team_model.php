<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Team_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Récupère tous les membres de l'équipe
     */
    public function getAll($limit = 20, $offset = 0, $filters = []) {
        $this->db->select('*');
        $this->db->from('team_members');
        
        // Filtres
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nom', $filters['search']);
            $this->db->or_like('prenom', $filters['search']);
            $this->db->or_like('poste', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('est_actif', $filters['est_actif']);
        }
        
        $this->db->order_by('ordre_affichage', 'ASC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    /**
     * Compte le nombre total de membres
     */
    public function countAll($filters = []) {
        $this->db->from('team_members');
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nom', $filters['search']);
            $this->db->or_like('prenom', $filters['search']);
            $this->db->or_like('poste', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('est_actif', $filters['est_actif']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupère un membre par son ID
     */
    public function getById($id) {
        $this->db->where('id_member', $id);
        return $this->db->get('team_members')->row();
    }

    /**
     * Ajoute un membre
     */
    public function add($data) {
        $data['date_creation'] = date('Y-m-d H:i:s');
        $this->db->insert('team_members', $data);
        return $this->db->insert_id();
    }

    /**
     * Met à jour un membre
     */
    public function update($id, $data) {
        $this->db->where('id_member', $id);
        return $this->db->update('team_members', $data);
    }

    /**
     * Supprime un membre
     */
    public function delete($id) {
        $this->db->where('id_member', $id);
        return $this->db->delete('team_members');
    }

    /**
     * Active/Désactive un membre
     */
    public function toggle($id, $status) {
        $this->db->where('id_member', $id);
        return $this->db->update('team_members', ['est_actif' => $status]);
    }

    /**
     * Met à jour l'ordre d'affichage
     */
    public function updateOrder($id, $ordre) {
        $this->db->where('id_member', $id);
        return $this->db->update('team_members', ['ordre_affichage' => $ordre]);
    }

    /**
     * Récupère les statistiques
     */
    public function getStats() {
        $stats = new stdClass();
        $stats->total = $this->db->count_all('team_members');
        $stats->actifs = $this->db->where('est_actif', 1)->count_all_results('team_members');
        $stats->inactifs = $this->db->where('est_actif', 0)->count_all_results('team_members');
        
        return $stats;
    }
}