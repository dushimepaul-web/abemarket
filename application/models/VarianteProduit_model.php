<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VarianteProduit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_variantes_by_produit($id_produit) {
        $this->db->where('id_produit', $id_produit);
        $this->db->order_by('date_creation', 'ASC');
        $query = $this->db->get('variantes_produit');
        
        $variantes = $query->result();
        
        foreach ($variantes as $v) {
            $v->attributs = json_decode($v->attributs_variante, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                $v->attributs = [];
            }
        }
        
        return $variantes;
    }

    public function get_variante_by_id($id) {
        $this->db->where('id_variante', $id);
        $query = $this->db->get('variantes_produit');
        $variante = $query->row();
        
        if ($variante) {
            $variante->attributs = json_decode($variante->attributs_variante, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                $variante->attributs = [];
            }
        }
        
        return $variante;
    }

    public function get_variante_by_sku($sku) {
        $this->db->where('sku', $sku);
        $query = $this->db->get('variantes_produit');
        return $query->row();
    }

    public function ajouter_variante($data) {
        if (isset($data['attributs']) && is_array($data['attributs'])) {
            $data['attributs_variante'] = json_encode($data['attributs']);
            unset($data['attributs']);
        }
        
        if ($this->sku_exists($data['sku'])) {
            return false;
        }
        
        $this->db->insert('variantes_produit', $data);
        return $this->db->insert_id();
    }

    public function modifier_variante($id, $data) {
        if (isset($data['attributs']) && is_array($data['attributs'])) {
            $data['attributs_variante'] = json_encode($data['attributs']);
            unset($data['attributs']);
        }
        
        if (isset($data['sku']) && $this->sku_exists($data['sku'], $id)) {
            return false;
        }
        
        $this->db->where('id_variante', $id);
        $this->db->update('variantes_produit', $data);
        return $this->db->affected_rows() > 0;
    }

    public function supprimer_variante($id) {
        $this->db->where('id_variante', $id);
        $this->db->delete('variantes_produit');
        return $this->db->affected_rows() > 0;
    }

    public function get_types_attributs() {
        return [
            'taille' => 'Taille',
            'couleur' => 'Couleur',
            'materiau' => 'Matériau',
            'style' => 'Style',
            'capacite' => 'Capacité',
            'puissance' => 'Puissance',
            'genre' => 'Genre',
            'autre' => 'Autre'
        ];
    }

    public function sku_exists($sku, $exclude_id = null) {
        $this->db->where('sku', $sku);
        if ($exclude_id) {
            $this->db->where('id_variante !=', $exclude_id);
        }
        return $this->db->get('variantes_produit')->num_rows() > 0;
    }
}
?>