<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Récupère toutes les FAQ
     */
    public function getAll($limit = 20, $offset = 0, $filters = []) {
        $this->db->select('*');
        $this->db->from('faq');
        
        // Filtres
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('question', $filters['search']);
            $this->db->or_like('reponse', $filters['search']);
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
     * Compte le nombre total de FAQ
     */
    public function countAll($filters = []) {
        $this->db->from('faq');
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('question', $filters['search']);
            $this->db->or_like('reponse', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('est_actif', $filters['est_actif']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupère une FAQ par son ID
     */
    public function getById($id) {
        $this->db->where('id_faq', $id);
        return $this->db->get('faq')->row();
    }

    /**
     * Ajoute une FAQ
     */
    public function add($data) {
        $this->db->insert('faq', $data);
        return $this->db->insert_id();
    }

    /**
     * Met à jour une FAQ
     */
    public function update($id, $data) {
        $this->db->where('id_faq', $id);
        return $this->db->update('faq', $data);
    }

    /**
     * Supprime une FAQ
     */
    public function delete($id) {
        $this->db->where('id_faq', $id);
        return $this->db->delete('faq');
    }

    /**
     * Active/Désactive une FAQ
     */
    public function toggle($id, $status) {
        $this->db->where('id_faq', $id);
        return $this->db->update('faq', ['est_actif' => $status]);
    }

    /**
     * Met à jour l'ordre d'affichage
     */
    public function updateOrder($id, $ordre) {
        $this->db->where('id_faq', $id);
        return $this->db->update('faq', ['ordre_affichage' => $ordre]);
    }

    /**
     * Récupère les statistiques
     */
    public function getStats() {
        $stats = new stdClass();
        $stats->total = $this->db->count_all('faq');
        $stats->actifs = $this->db->where('est_actif', 1)->count_all_results('faq');
        $stats->inactifs = $this->db->where('est_actif', 0)->count_all_results('faq');
        $stats->categories = 0; // Pas de catégories
        
        return $stats;
    }
}