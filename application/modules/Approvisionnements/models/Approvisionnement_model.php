<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approvisionnement_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter tous les approvisionnements
     */
    public function count_all($id_vendeur = null) {
        if ($id_vendeur) {
            $this->db->where('id_vendeur', $id_vendeur);
        }
        return $this->db->count_all_results('approvisionnements');
    }

    /**
     * Récupérer tous les approvisionnements
     */
    public function get_all($limit = null, $offset = null, $id_vendeur = null) {
        $this->db->select('a.*, p.nom_produit, p.sku, p.slug_produit, v.sku as variante_sku')
                 ->from('approvisionnements a')
                 ->join('produits p', 'p.id_produit = a.id_produit')
                 ->join('variantes_produit v', 'v.id_variante = a.id_variante', 'left')
                 ->order_by('a.date_appro', 'DESC')
                 ->order_by('a.date_creation', 'DESC');
        
        if ($id_vendeur) {
            $this->db->where('a.id_vendeur', $id_vendeur);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer un approvisionnement par ID
     */
    public function get_by_id($id) {
        $this->db->select('a.*, p.nom_produit, p.sku, p.slug_produit, p.prix_base, p.quantite_actuelle as stock_actuel, v.sku as variante_sku, v.attributs_variante')
                 ->from('approvisionnements a')
                 ->join('produits p', 'p.id_produit = a.id_produit')
                 ->join('variantes_produit v', 'v.id_variante = a.id_variante', 'left')
                 ->where('a.id_appro', $id);
        
        $result = $this->db->get()->row();
        
        if ($result && $result->attributs_variante) {
            $result->attributs = json_decode($result->attributs_variante, true);
        }
        
        return $result;
    }

    /**
     * Ajouter un approvisionnement
     */
    public function add($data) {
        $this->db->insert('approvisionnements', $data);
        return $this->db->insert_id();
    }

    /**
     * Supprimer un approvisionnement
     */
    public function delete($id) {
        $this->db->where('id_appro', $id);
        $this->db->delete('approvisionnements');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques des approvisionnements
     */
    public function get_stats($id_vendeur = null) {
        $this->db->select('
            COUNT(*) as total_appro,
            COALESCE(SUM(quantite_recue), 0) as total_quantite,
            COALESCE(SUM(cout_total), 0) as total_cout,
            COALESCE(AVG(prix_achat_unitaire), 0) as prix_moyen,
            COUNT(DISTINCT fournisseur) as nb_fournisseurs,
            SUM(CASE WHEN MONTH(date_appro) = MONTH(CURRENT_DATE()) AND YEAR(date_appro) = YEAR(CURRENT_DATE()) THEN quantite_recue ELSE 0 END) as quantite_mois
        ');
        
        if ($id_vendeur) {
            $this->db->where('id_vendeur', $id_vendeur);
        }
        
        return $this->db->get('approvisionnements')->row();
    }
}
?>