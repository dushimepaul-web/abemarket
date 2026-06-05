<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SuiviGps_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter tous les suivis
     */
    public function count_all($filters = []) {
        $this->db->from('suivi_gps s');
        $this->db->join('commandes c', 'c.id_commande = s.id_commande', 'left');
        $this->db->join('transporteurs t', 't.id_transporteur = s.id_transporteur');
        
        if (!empty($filters['id_transporteur'])) {
            $this->db->where('s.id_transporteur', $filters['id_transporteur']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(s.timestamp_gps) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(s.timestamp_gps) <=', $filters['date_fin']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les suivis
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('s.*, c.numero_commande, t.nom as transporteur_nom')
                 ->from('suivi_gps s')
                 ->join('commandes c', 'c.id_commande = s.id_commande', 'left')
                 ->join('transporteurs t', 't.id_transporteur = s.id_transporteur')
                 ->order_by('s.timestamp_gps', 'DESC');
        
        if (!empty($filters['id_transporteur'])) {
            $this->db->where('s.id_transporteur', $filters['id_transporteur']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(s.timestamp_gps) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(s.timestamp_gps) <=', $filters['date_fin']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Dernière position d'un transporteur
     */
    public function get_derniere_position($id_transporteur) {
        $this->db->where('id_transporteur', $id_transporteur)
                 ->order_by('timestamp_gps', 'DESC')
                 ->limit(1);
        
        return $this->db->get('suivi_gps')->row();
    }

    /**
     * Historique des positions d'un transporteur
     */
    public function get_historique($id_transporteur, $date_debut = null, $date_fin = null) {
        $this->db->where('id_transporteur', $id_transporteur)
                 ->order_by('timestamp_gps', 'ASC');
        
        if ($date_debut) {
            $this->db->where('DATE(timestamp_gps) >=', $date_debut);
        }
        if ($date_fin) {
            $this->db->where('DATE(timestamp_gps) <=', $date_fin);
        }
        
        return $this->db->get('suivi_gps')->result();
    }

    /**
     * Suivi d'une commande
     */
    public function get_suivi_by_commande($id_commande) {
        $this->db->where('id_commande', $id_commande)
                 ->order_by('timestamp_gps', 'ASC');
        
        return $this->db->get('suivi_gps')->result();
    }

    /**
     * Ajouter une position
     */
    public function ajouter_position($data) {
        $this->db->insert('suivi_gps', $data);
        return $this->db->insert_id();
    }

    /**
     * Positions actives (dernières 5 minutes)
     */
    public function get_positions_actives() {
        $this->db->select('s.*, t.nom as transporteur_nom, t.type_vehicule')
                 ->from('suivi_gps s')
                 ->join('transporteurs t', 't.id_transporteur = s.id_transporteur')
                 ->where('s.timestamp_gps >', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                 ->group_by('s.id_transporteur')
                 ->order_by('s.timestamp_gps', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Distance parcourue par un transporteur
     */
    public function get_distance_parcourue($id_transporteur, $date_debut, $date_fin) {
        $positions = $this->get_historique($id_transporteur, $date_debut, $date_fin);
        
        $distance = 0;
        for ($i = 1; $i < count($positions); $i++) {
            $lat1 = $positions[$i-1]->latitude;
            $lon1 = $positions[$i-1]->longitude;
            $lat2 = $positions[$i]->latitude;
            $lon2 = $positions[$i]->longitude;
            $distance += $this->distance_haversine($lat1, $lon1, $lat2, $lon2);
        }
        
        return round($distance, 2);
    }

    /**
     * Calcul de distance entre deux points GPS (formule de Haversine)
     */
    private function distance_haversine($lat1, $lon1, $lat2, $lon2) {
        $R = 6371; // Rayon de la Terre en km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c;
    }


    /**
 * Récupérer un suivi par son ID
 */
public function get_by_id($id_suivi) {
    $this->db->select('s.*, c.numero_commande, t.nom as transporteur_nom')
             ->from('suivi_gps s')
             ->join('commandes c', 'c.id_commande = s.id_commande', 'left')
             ->join('transporteurs t', 't.id_transporteur = s.id_transporteur')
             ->where('s.id_suivi', $id_suivi);
    
    return $this->db->get()->row();
}

    /**
     * Statistiques
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(DISTINCT s.id_transporteur) as transporteurs_actifs,
            COUNT(s.id_suivi) as total_points,
            COUNT(DISTINCT s.id_commande) as commandes_suivies,
            AVG(s.vitesse_kmh) as vitesse_moyenne
        ');
        $this->db->from('suivi_gps s');
        
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(s.timestamp_gps) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(s.timestamp_gps) <=', $filters['date_fin']);
        }
        
        $result = $this->db->get()->row();
        
        if (!$result->transporteurs_actifs) $result->transporteurs_actifs = 0;
        if (!$result->total_points) $result->total_points = 0;
        if (!$result->commandes_suivies) $result->commandes_suivies = 0;
        if (!$result->vitesse_moyenne) $result->vitesse_moyenne = 0;
        
        return $result;
    }
}
?>