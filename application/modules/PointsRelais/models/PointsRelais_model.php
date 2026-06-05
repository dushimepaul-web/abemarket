<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PointsRelais_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter tous les points relais
     */
    public function count_all($filters = []) {
        $this->db->from('points_relais p');
        $this->db->join('communes c', 'c.id_commune = p.id_commune', 'left');
        
        if (!empty($filters['type'])) {
            $this->db->where('p.type', $filters['type']);
        }
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('p.est_actif', $filters['est_actif']);
        }
        if (!empty($filters['id_commune'])) {
            $this->db->where('p.id_commune', $filters['id_commune']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('p.nom', $filters['search']);
            $this->db->or_like('p.adresse', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les points relais
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('p.*, c.commune_name')
                 ->from('points_relais p')
                 ->join('communes c', 'c.id_commune = p.id_commune', 'left')
                 ->order_by('p.date_creation', 'DESC');
        
        if (!empty($filters['type'])) {
            $this->db->where('p.type', $filters['type']);
        }
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('p.est_actif', $filters['est_actif']);
        }
        if (!empty($filters['id_commune'])) {
            $this->db->where('p.id_commune', $filters['id_commune']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('p.nom', $filters['search']);
            $this->db->or_like('p.adresse', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer les points relais actifs
     */
    public function get_actifs() {
        return $this->db->where('est_actif', 1)
                        ->order_by('nom', 'ASC')
                        ->get('points_relais')
                        ->result();
    }

    /**
     * Récupérer un point relais par ID
     */
    public function get_by_id($id) {
        $this->db->select('p.*, c.commune_name')
                 ->from('points_relais p')
                 ->join('communes c', 'c.id_commune = p.id_commune', 'left')
                 ->where('p.id_point', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Ajouter un point relais
     */
    public function add($data) {
        $this->db->insert('points_relais', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier un point relais
     */
    public function update($id, $data) {
        $this->db->where('id_point', $id);
        $this->db->update('points_relais', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un point relais
     */
    public function delete($id) {
        $this->db->where('id_point', $id);
        $this->db->delete('points_relais');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN type = "boutique_partenaire" THEN 1 ELSE 0 END) as boutiques,
            SUM(CASE WHEN type = "kiosque" THEN 1 ELSE 0 END) as kiosques,
            SUM(CASE WHEN type = "bureau_poste" THEN 1 ELSE 0 END) as bureaux,
            SUM(CASE WHEN est_actif = 1 THEN 1 ELSE 0 END) as actifs,
            SUM(CASE WHEN est_actif = 0 THEN 1 ELSE 0 END) as inactifs,
            SUM(capacite_max) as capacite_totale
        ');
        
        $result = $this->db->get('points_relais')->row();
        
        if (!$result->total) $result->total = 0;
        if (!$result->boutiques) $result->boutiques = 0;
        if (!$result->kiosques) $result->kiosques = 0;
        if (!$result->bureaux) $result->bureaux = 0;
        if (!$result->actifs) $result->actifs = 0;
        if (!$result->inactifs) $result->inactifs = 0;
        if (!$result->capacite_totale) $result->capacite_totale = 0;
        
        return $result;
    }

    /**
     * Récupérer les points relais proches
     */
    public function get_proches($latitude, $longitude, $rayon_km = 5) {
        $rayon_deg = $rayon_km / 111; // Approximation: 1 degré ≈ 111 km
        
        $this->db->where('est_actif', 1);
        $this->db->where('latitude BETWEEN ' . ($latitude - $rayon_deg) . ' AND ' . ($latitude + $rayon_deg));
        $this->db->where('longitude BETWEEN ' . ($longitude - $rayon_deg) . ' AND ' . ($longitude + $rayon_deg));
        $this->db->order_by('ABS(latitude - ' . $latitude . ') + ABS(longitude - ' . $longitude . ')', 'ASC');
        $this->db->limit(10);
        
        return $this->db->get('points_relais')->result();
    }
}
?>