<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ZoneLivraison_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer toutes les zones de livraison
     */
    public function get_all_zones_livraison($limit = null, $offset = null) {
        $this->db->select('z.*, p.province_name')
                 ->from('zones_livraison z')
                 ->join('provinces p', 'z.id_province = p.id_province', 'left')
                 ->order_by('z.nom_zone', 'ASC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Compter les zones de livraison
     */
    public function count_zones_livraison() {
        return $this->db->from('zones_livraison')->count_all_results();
    }

    /**
     * Récupérer une zone de livraison par son ID
     */
    public function get_zone_livraison_by_id($id) {
        $this->db->select('z.*, p.province_name')
                 ->from('zones_livraison z')
                 ->join('provinces p', 'z.id_province = p.id_province', 'left')
                 ->where('z.id_zone_liv', $id);
        
        $query = $this->db->get();
        $zone = $query->row();
        
        if ($zone) {
            // Décoder les IDs des communes
            $zone->communes_ids = !empty($zone->ids_communes) ? explode(',', $zone->ids_communes) : [];
            $zone->quartiers_ids = !empty($zone->ids_quartiers) ? explode(',', $zone->ids_quartiers) : [];
            
            // Récupérer les noms des communes
            if (!empty($zone->communes_ids)) {
                $this->db->select('id_commune, commune_name');
                $this->db->where_in('id_commune', $zone->communes_ids);
                $zone->communes = $this->db->get('communes')->result();
            } else {
                $zone->communes = [];
            }
            
            // Récupérer les noms des quartiers
            if (!empty($zone->quartiers_ids)) {
                $this->db->select('q.id_quartier, q.quartier_name, c.commune_name');
                $this->db->from('quartiers q');
                $this->db->join('communes c', 'q.id_commune = c.id_commune');
                $this->db->where_in('q.id_quartier', $zone->quartiers_ids);
                $zone->quartiers = $this->db->get()->result();
            } else {
                $zone->quartiers = [];
            }
        }
        
        return $zone;
    }

    /**
     * Ajouter une zone de livraison
     */
    public function ajouter_zone_livraison($data) {
        // Convertir les arrays en string pour stockage
        if (isset($data['communes_ids']) && is_array($data['communes_ids'])) {
            $data['ids_communes'] = implode(',', array_filter($data['communes_ids']));
            unset($data['communes_ids']);
        }
        
        if (isset($data['quartiers_ids']) && is_array($data['quartiers_ids'])) {
            $data['ids_quartiers'] = implode(',', array_filter($data['quartiers_ids']));
            unset($data['quartiers_ids']);
        }
        
        $this->db->insert('zones_livraison', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier une zone de livraison
     */
    public function modifier_zone_livraison($id, $data) {
        // Convertir les arrays en string pour stockage
        if (isset($data['communes_ids']) && is_array($data['communes_ids'])) {
            $data['ids_communes'] = implode(',', array_filter($data['communes_ids']));
            unset($data['communes_ids']);
        }
        
        if (isset($data['quartiers_ids']) && is_array($data['quartiers_ids'])) {
            $data['ids_quartiers'] = implode(',', array_filter($data['quartiers_ids']));
            unset($data['quartiers_ids']);
        }
        
        $this->db->where('id_zone_liv', $id);
        $this->db->update('zones_livraison', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer une zone de livraison
     */
    public function supprimer_zone_livraison($id) {
        $this->db->where('id_zone_liv', $id);
        $this->db->delete('zones_livraison');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Calculer les frais de livraison pour une adresse
     */
    public function calculer_frais_livraison($id_province, $id_commune, $id_quartier = null, $poids_total = 0, $montant_total = 0) {
        $this->db->select('*')
                 ->from('zones_livraison')
                 ->where('est_actif', 1);
        
        // Filtrer par province
        if ($id_province) {
            $this->db->where('id_province', $id_province);
        }
        
        $zones = $this->db->get()->result();
        
        foreach ($zones as $zone) {
            $communes_ids = !empty($zone->ids_communes) ? explode(',', $zone->ids_communes) : [];
            $quartiers_ids = !empty($zone->ids_quartiers) ? explode(',', $zone->ids_quartiers) : [];
            
            $zone_valide = false;
            
            // Vérifier si la commune est dans la zone
            if (!empty($communes_ids) && in_array($id_commune, $communes_ids)) {
                $zone_valide = true;
            }
            
            // Vérifier si le quartier est dans la zone
            if ($id_quartier && !empty($quartiers_ids) && in_array($id_quartier, $quartiers_ids)) {
                $zone_valide = true;
            }
            
            if ($zone_valide) {
                $frais = $zone->cout_base;
                
                // Ajouter le coût par kg
                if ($zone->cout_par_kg && $poids_total > 0) {
                    $frais += ($poids_total * $zone->cout_par_kg);
                }
                
                // Vérifier le seuil de livraison gratuite
                if ($zone->seuil_livraison_gratuite && $montant_total >= $zone->seuil_livraison_gratuite) {
                    $frais = 0;
                }
                
                return [
                    'zone_id' => $zone->id_zone_liv,
                    'zone_nom' => $zone->nom_zone,
                    'frais' => round($frais, 2),
                    'delai_min' => $zone->delai_min_jours,
                    'delai_max' => $zone->delai_max_jours
                ];
            }
        }
        
        // Zone par défaut si aucune zone trouvée
        return [
            'zone_id' => null,
            'zone_nom' => 'Standard',
            'frais' => 5000,
            'delai_min' => 3,
            'delai_max' => 7
        ];
    }

    /**
     * Obtenir les statistiques des zones de livraison
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total_zones,
            SUM(CASE WHEN est_actif = 1 THEN 1 ELSE 0 END) as zones_actives,
            AVG(cout_base) as cout_moyen,
            MIN(cout_base) as cout_min,
            MAX(cout_base) as cout_max
        ');
        
        $query = $this->db->get('zones_livraison');
        return $query->row();
    }
}
?>