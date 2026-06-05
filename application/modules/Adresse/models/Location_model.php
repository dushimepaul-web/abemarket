<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ==================== PROVINCES ====================
    
    public function get_all_provinces() {
        return $this->db->order_by('province_name', 'ASC')->get('provinces')->result();
    }

    public function get_all_provinces_actives() {
        return $this->db->where('est_actif', 1)
                        ->order_by('province_name', 'ASC')
                        ->get('provinces')->result();
    }
    
    public function get_province_by_id($id) {
        return $this->db->where('id_province', $id)->get('provinces')->row();
    }
    
    public function get_province_by_name($name) {
        return $this->db->where('province_name', $name)->get('provinces')->row();
    }
    
    public function ajouter_province($data) {
        $this->db->insert('provinces', $data);
        return $this->db->insert_id();
    }
    
    public function modifier_province($id, $data) {
        $this->db->where('id_province', $id)->update('provinces', $data);
        return $this->db->affected_rows() > 0;
    }
    
    public function supprimer_province($id) {
        $has_communes = $this->db->where('id_province', $id)->get('communes')->num_rows() > 0;
        if ($has_communes) return false;
        $this->db->where('id_province', $id)->delete('provinces');
        return $this->db->affected_rows() > 0;
    }

    // ==================== COMMUNES ====================
    
    public function get_all_communes() {
        $this->db->select('c.*, p.province_name')
                 ->from('communes c')
                 ->join('provinces p', 'c.id_province = p.id_province')
                 ->order_by('c.commune_name', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_communes_by_province($id_province) {
        return $this->db->where('id_province', $id_province)
                        ->order_by('commune_name', 'ASC')
                        ->get('communes')->result();
    }
    
    public function get_communes_actives_by_province($id_province) {
        return $this->db->where('id_province', $id_province)
                        ->where('est_actif', 1)
                        ->order_by('commune_name', 'ASC')
                        ->get('communes')->result();
    }
    
    public function get_commune_by_id($id) {
        $this->db->select('c.*, p.province_name, p.latitude as province_latitude, p.longitude as province_longitude')
                 ->from('communes c')
                 ->join('provinces p', 'c.id_province = p.id_province')
                 ->where('c.id_commune', $id);
        return $this->db->get()->row();
    }
    
    public function get_commune_by_name($name, $id_province) {
        return $this->db->where('commune_name', $name)
                        ->where('id_province', $id_province)
                        ->get('communes')->row();
    }
    
    public function ajouter_commune($data) {
        $this->db->insert('communes', $data);
        return $this->db->insert_id();
    }
    
    public function modifier_commune($id, $data) {
        $this->db->where('id_commune', $id)->update('communes', $data);
        return $this->db->affected_rows() > 0;
    }
    
    public function supprimer_commune($id) {
        $has_quartiers = $this->db->where('id_commune', $id)->get('quartiers')->num_rows() > 0;
        if ($has_quartiers) return false;
        $this->db->where('id_commune', $id)->delete('communes');
        return $this->db->affected_rows() > 0;
    }

    // ==================== QUARTIERS ====================
    
    public function get_all_quartiers() {
        $this->db->select('q.*, c.commune_name, p.province_name')
                 ->from('quartiers q')
                 ->join('communes c', 'q.id_commune = c.id_commune')
                 ->join('provinces p', 'c.id_province = p.id_province')
                 ->order_by('q.quartier_name', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_quartiers_by_commune($id_commune) {
        return $this->db->where('id_commune', $id_commune)
                        ->order_by('quartier_name', 'ASC')
                        ->get('quartiers')->result();
    }
    
    public function get_quartiers_actifs_by_commune($id_commune) {
        return $this->db->where('id_commune', $id_commune)
                        ->where('est_actif', 1)
                        ->order_by('quartier_name', 'ASC')
                        ->get('quartiers')->result();
    }
    
    public function get_quartier_by_id($id) {
        $this->db->select('q.*, c.commune_name, c.id_province, p.province_name')
                 ->from('quartiers q')
                 ->join('communes c', 'q.id_commune = c.id_commune')
                 ->join('provinces p', 'c.id_province = p.id_province')
                 ->where('q.id_quartier', $id);
        return $this->db->get()->row();
    }
    
    public function get_quartier_by_name($name, $id_commune) {
        return $this->db->where('quartier_name', $name)
                        ->where('id_commune', $id_commune)
                        ->get('quartiers')->row();
    }
    
    public function ajouter_quartier($data) {
        $this->db->insert('quartiers', $data);
        return $this->db->insert_id();
    }
    
    public function modifier_quartier($id, $data) {
        $this->db->where('id_quartier', $id)->update('quartiers', $data);
        return $this->db->affected_rows() > 0;
    }
    
    public function supprimer_quartier($id) {
        $has_zones = $this->db->where('id_quartier', $id)->get('zones')->num_rows() > 0;
        if ($has_zones) return false;
        $this->db->where('id_quartier', $id)->delete('quartiers');
        return $this->db->affected_rows() > 0;
    }

    // ==================== ZONES ====================
    // NOTE: La table 'zones' a une colonne 'id_commune' et NON PAS 'id_quartier'
    
    public function get_all_zones() {
        $this->db->select('z.*, c.commune_name, p.province_name')
                 ->from('zones z')
                 ->join('communes c', 'z.id_commune = c.id_commune')
                 ->join('provinces p', 'c.id_province = p.id_province')
                 ->order_by('z.zone_name', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_zones_by_commune($id_commune) {
        return $this->db->where('id_commune', $id_commune)
                        ->order_by('zone_name', 'ASC')
                        ->get('zones')->result();
    }
    
    public function get_zones_actives_by_commune($id_commune) {
        return $this->db->where('id_commune', $id_commune)
                        ->where('est_actif', 1)
                        ->order_by('zone_name', 'ASC')
                        ->get('zones')->result();
    }
    
    public function get_zone_by_id($id) {
        $this->db->select('z.*, c.commune_name, c.id_province, p.province_name')
                 ->from('zones z')
                 ->join('communes c', 'z.id_commune = c.id_commune')
                 ->join('provinces p', 'c.id_province = p.id_province')
                 ->where('z.id_zone', $id);
        return $this->db->get()->row();
    }
    
    public function get_zone_by_name($name, $id_commune) {
        return $this->db->where('zone_name', $name)
                        ->where('id_commune', $id_commune)
                        ->get('zones')->row();
    }
    
    public function ajouter_zone($data) {
        $this->db->insert('zones', $data);
        return $this->db->insert_id();
    }
    
    public function modifier_zone($id, $data) {
        $this->db->where('id_zone', $id)->update('zones', $data);
        return $this->db->affected_rows() > 0;
    }
    
    public function supprimer_zone($id) {
        $has_collines = $this->db->where('id_zone', $id)->get('collines')->num_rows() > 0;
        if ($has_collines) return false;
        $this->db->where('id_zone', $id)->delete('zones');
        return $this->db->affected_rows() > 0;
    }

    // ==================== COLLINES ====================
    
    public function get_all_collines() {
        $this->db->select('col.*, z.zone_name, c.commune_name, p.province_name')
                 ->from('collines col')
                 ->join('zones z', 'col.id_zone = z.id_zone')
                 ->join('communes c', 'z.id_commune = c.id_commune')
                 ->join('provinces p', 'c.id_province = p.id_province')
                 ->order_by('col.colline_name', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_collines_by_zone($id_zone) {
        return $this->db->where('id_zone', $id_zone)
                        ->order_by('colline_name', 'ASC')
                        ->get('collines')->result();
    }
    
    public function get_collines_actives_by_zone($id_zone) {
        return $this->db->where('id_zone', $id_zone)
                        ->where('est_actif', 1)
                        ->order_by('colline_name', 'ASC')
                        ->get('collines')->result();
    }
    
    public function get_colline_by_id($id) {
        $this->db->select('col.*, z.zone_name, z.id_commune, c.commune_name, c.id_province, p.province_name')
                 ->from('collines col')
                 ->join('zones z', 'col.id_zone = z.id_zone')
                 ->join('communes c', 'z.id_commune = c.id_commune')
                 ->join('provinces p', 'c.id_province = p.id_province')
                 ->where('col.id_colline', $id);
        return $this->db->get()->row();
    }
    
    public function get_colline_by_name($name, $id_zone) {
        return $this->db->where('colline_name', $name)
                        ->where('id_zone', $id_zone)
                        ->get('collines')->row();
    }
    
    public function ajouter_colline($data) {
        $this->db->insert('collines', $data);
        return $this->db->insert_id();
    }
    
    public function modifier_colline($id, $data) {
        $this->db->where('id_colline', $id)->update('collines', $data);
        return $this->db->affected_rows() > 0;
    }
    
    public function supprimer_colline($id) {
        $this->db->where('id_colline', $id)->delete('collines');
        return $this->db->affected_rows() > 0;
    }

    // ==================== MÉTHODES UTILITAIRES ====================
    
    public function province_exists($id) {
        return $this->db->where('id_province', $id)->get('provinces')->num_rows() > 0;
    }
    
    public function commune_exists($id) {
        return $this->db->where('id_commune', $id)->get('communes')->num_rows() > 0;
    }
    
    public function quartier_exists($id) {
        return $this->db->where('id_quartier', $id)->get('quartiers')->num_rows() > 0;
    }
    
    public function zone_exists($id) {
        return $this->db->where('id_zone', $id)->get('zones')->num_rows() > 0;
    }
    
    public function colline_exists($id) {
        return $this->db->where('id_colline', $id)->get('collines')->num_rows() > 0;
    }
    
    public function count_communes_by_province($id_province) {
        return $this->db->where('id_province', $id_province)->get('communes')->num_rows();
    }
    
    public function count_quartiers_by_commune($id_commune) {
        return $this->db->where('id_commune', $id_commune)->get('quartiers')->num_rows();
    }
    
    public function count_zones_by_commune($id_commune) {
        return $this->db->where('id_commune', $id_commune)->get('zones')->num_rows();
    }
    
    public function count_collines_by_zone($id_zone) {
        return $this->db->where('id_zone', $id_zone)->get('collines')->num_rows();
    }

    // ==================== HIÉRARCHIE ====================
    
    public function get_hierarchie_complete() {
        $provinces = $this->get_all_provinces();
        foreach ($provinces as $province) {
            $province->communes = $this->get_communes_by_province($province->id_province);
            foreach ($province->communes as $commune) {
                $commune->quartiers = $this->get_quartiers_by_commune($commune->id_commune);
                $commune->zones = $this->get_zones_by_commune($commune->id_commune);
                foreach ($commune->zones as $zone) {
                    $zone->collines = $this->get_collines_by_zone($zone->id_zone);
                }
            }
        }
        return $provinces;
    }
    
    public function get_hierarchie_from_zone($id_zone) {
        $zone = $this->get_zone_by_id($id_zone);
        if ($zone) {
            $zone->commune = $this->get_commune_by_id($zone->id_commune);
            if ($zone->commune) {
                $zone->commune->province = $this->get_province_by_id($zone->commune->id_province);
            }
        }
        return $zone;
    }
    
    public function get_hierarchie_from_colline($id_colline) {
        $colline = $this->get_colline_by_id($id_colline);
        if ($colline) {
            $colline->zone = $this->get_zone_by_id($colline->id_zone);
            if ($colline->zone) {
                $colline->zone->commune = $this->get_commune_by_id($colline->zone->id_commune);
                if ($colline->zone->commune) {
                    $colline->zone->commune->province = $this->get_province_by_id($colline->zone->commune->id_province);
                }
            }
        }
        return $colline;
    }

    // ==================== AJAX ====================
    
    public function get_communes_by_province_json($id_province) {
        return $this->get_communes_by_province($id_province);
    }
    
    public function get_quartiers_by_commune_json($id_commune) {
        return $this->get_quartiers_by_commune($id_commune);
    }
    
    public function get_zones_by_commune_json($id_commune) {
        return $this->get_zones_by_commune($id_commune);
    }
    
    public function get_collines_by_zone_json($id_zone) {
        return $this->get_collines_by_zone($id_zone);
    }
}
?>