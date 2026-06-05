<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonials_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Récupère tous les témoignages
     */
    public function getAll($limit = 20, $offset = 0, $filters = []) {
        $this->db->select('*');
        $this->db->from('testimonials');
        
        // Filtres
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nom', $filters['search']);
            $this->db->or_like('prenom', $filters['search']);
            $this->db->or_like('message', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['est_approuve']) && $filters['est_approuve'] !== '') {
            $this->db->where('est_approuve', $filters['est_approuve']);
        }
        
        if (isset($filters['note']) && $filters['note'] !== '') {
            $this->db->where('note', $filters['note']);
        }
        
        $this->db->order_by('ordre_affichage', 'ASC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    /**
     * Compte le nombre total de témoignages
     */
    public function countAll($filters = []) {
        $this->db->from('testimonials');
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nom', $filters['search']);
            $this->db->or_like('prenom', $filters['search']);
            $this->db->or_like('message', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['est_approuve']) && $filters['est_approuve'] !== '') {
            $this->db->where('est_approuve', $filters['est_approuve']);
        }
        
        if (isset($filters['note']) && $filters['note'] !== '') {
            $this->db->where('note', $filters['note']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupère un témoignage par son ID
     */
    public function getById($id) {
        $this->db->where('id_testimonial', $id);
        return $this->db->get('testimonials')->row();
    }

    /**
     * Ajoute un témoignage
     */
    public function add($data) {
        $insert_data = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'poste' => $data['poste'] ?? null,
            'message' => $data['message'],
            'photo_url' => $data['photo_url'] ?? null,
            'note' => $data['note'] ?? 5,
            'est_approuve' => $data['est_approuve'] ?? 1,
            'ordre_affichage' => $data['ordre_affichage'] ?? 0,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('testimonials', $insert_data);
        return $this->db->insert_id();
    }

    /**
     * Met à jour un témoignage
     */
    public function update($id, $data) {
        $update_data = [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'poste' => $data['poste'] ?? null,
            'message' => $data['message'],
            'photo_url' => $data['photo_url'] ?? null,
            'note' => $data['note'] ?? 5,
            'est_approuve' => $data['est_approuve'] ?? 1,
            'ordre_affichage' => $data['ordre_affichage'] ?? 0
        ];
        
        $this->db->where('id_testimonial', $id);
        return $this->db->update('testimonials', $update_data);
    }

    /**
     * Supprime un témoignage
     */
    public function delete($id) {
        $this->db->where('id_testimonial', $id);
        return $this->db->delete('testimonials');
    }

    /**
     * Approuve/Désapprouve un témoignage
     */
    public function approve($id, $status) {
        $this->db->where('id_testimonial', $id);
        return $this->db->update('testimonials', ['est_approuve' => $status]);
    }

    /**
     * Met à jour l'ordre d'affichage
     */
    public function updateOrder($id, $ordre) {
        $this->db->where('id_testimonial', $id);
        return $this->db->update('testimonials', ['ordre_affichage' => $ordre]);
    }

    /**
     * Récupère les statistiques
     */
    public function getStats() {
        $stats = new stdClass();
        $stats->total = $this->db->count_all('testimonials');
        $stats->approuves = $this->db->where('est_approuve', 1)->count_all_results('testimonials');
        $stats->desapprouves = $this->db->where('est_approuve', 0)->count_all_results('testimonials');
        
        // Statistiques par note
        $stats->note_5 = $this->db->where('note', 5)->count_all_results('testimonials');
        $stats->note_4 = $this->db->where('note', 4)->count_all_results('testimonials');
        $stats->note_3 = $this->db->where('note', 3)->count_all_results('testimonials');
        $stats->note_2 = $this->db->where('note', 2)->count_all_results('testimonials');
        $stats->note_1 = $this->db->where('note', 1)->count_all_results('testimonials');
        
        // Note moyenne
        $this->db->select('AVG(note) as moyenne');
        $query = $this->db->get('testimonials');
        $stats->note_moyenne = round($query->row()->moyenne ?? 0, 1);
        
        return $stats;
    }
}