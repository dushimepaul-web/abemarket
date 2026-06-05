<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupons_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter tous les coupons avec filtres
     */
    public function count_all($filters = []) {
        $this->db->from('coupons');
        
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('est_actif', $filters['est_actif']);
        }
        if (!empty($filters['type_reduction'])) {
            $this->db->where('type_reduction', $filters['type_reduction']);
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
     * Récupérer tous les coupons
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->order_by('date_creation', 'DESC');
        
        if (isset($filters['est_actif']) && $filters['est_actif'] !== '') {
            $this->db->where('est_actif', $filters['est_actif']);
        }
        if (!empty($filters['type_reduction'])) {
            $this->db->where('type_reduction', $filters['type_reduction']);
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
        
        return $this->db->get('coupons')->result();
    }

    /**
     * Récupérer un coupon par ID
     */
    public function get_by_id($id) {
        return $this->db->where('id_coupon', $id)->get('coupons')->row();
    }

    /**
     * Récupérer un coupon par code
     */
    public function get_by_code($code) {
        return $this->db->where('code', $code)->get('coupons')->row();
    }

    /**
     * Ajouter un coupon
     */
    public function add($data) {
        $this->db->insert('coupons', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier un coupon
     */
    public function update($id, $data) {
        $this->db->where('id_coupon', $id);
        $this->db->update('coupons', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un coupon
     */
    public function delete($id) {
        $this->db->where('id_coupon', $id);
        $this->db->delete('coupons');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Incrémenter le nombre d'utilisations
     */
    public function incrementer_utilisation($id) {
        $this->db->set('nombre_utilisations', 'nombre_utilisations + 1', FALSE);
        $this->db->where('id_coupon', $id);
        $this->db->update('coupons');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Valider un coupon
     */
    public function valider_coupon($code, $montant_achat = 0) {
        $coupon = $this->get_by_code($code);
        
        if (!$coupon) {
            return false;
        }
        
        // Vérifier si actif
        if ($coupon->est_actif != 1) {
            return false;
        }
        
        // Vérifier les dates
        $now = date('Y-m-d H:i:s');
        if ($coupon->date_debut && $coupon->date_debut > $now) {
            return false;
        }
        if ($coupon->date_fin && $coupon->date_fin < $now) {
            return false;
        }
        
        // Vérifier la limite d'utilisation
        if ($coupon->limite_utilisation && $coupon->nombre_utilisations >= $coupon->limite_utilisation) {
            return false;
        }
        
        // Vérifier le montant minimum d'achat
        if ($coupon->montant_min_achat && $montant_achat < $coupon->montant_min_achat) {
            return false;
        }
        
        // Calculer la réduction
        $reduction = 0;
        if ($coupon->type_reduction == 'pourcentage') {
            $reduction = ($montant_achat * $coupon->valeur_reduction) / 100;
            if ($coupon->montant_max_reduction && $reduction > $coupon->montant_max_reduction) {
                $reduction = $coupon->montant_max_reduction;
            }
        } elseif ($coupon->type_reduction == 'montant_fixe') {
            $reduction = $coupon->valeur_reduction;
            if ($reduction > $montant_achat) {
                $reduction = $montant_achat;
            }
        }
        
        $coupon->reduction_calculee = $reduction;
        
        return $coupon;
    }

    /**
     * Statistiques
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN est_actif = 1 THEN 1 ELSE 0 END) as actifs,
            SUM(CASE WHEN est_actif = 0 THEN 1 ELSE 0 END) as inactifs,
            SUM(CASE WHEN type_reduction = "pourcentage" THEN 1 ELSE 0 END) as pourcentage,
            SUM(CASE WHEN type_reduction = "montant_fixe" THEN 1 ELSE 0 END) as montant_fixe,
            SUM(CASE WHEN type_reduction = "livraison_gratuite" THEN 1 ELSE 0 END) as livraison_gratuite,
            SUM(nombre_utilisations) as total_utilisations
        ');
        
        return $this->db->get('coupons')->row();
    }
}
?>