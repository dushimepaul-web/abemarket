<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ListeSouhaits_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer les souhaits d'un utilisateur
     */
    public function get_by_user($id_utilisateur) {
        $this->db->select('l.*, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo, p.note_moyenne, p.statut')
                 ->from('liste_souhaits l')
                 ->join('produits p', 'p.id_produit = l.id_produit')
                 ->where('l.id_utilisateur', $id_utilisateur)
                 ->where('p.statut', 'actif')
                 ->order_by('l.date_ajout', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Compter les souhaits d'un utilisateur
     */
    public function count_by_user($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        return $this->db->count_all_results('liste_souhaits');
    }

    /**
     * Récupérer tous les souhaits (Admin)
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('l.*, 
                          CONCAT(u.prenom, " ", u.nom) as utilisateur_nom, 
                          u.email as utilisateur_email,
                          p.nom_produit as produit_nom,
                          p.sku as produit_sku')
                 ->from('liste_souhaits l')
                 ->join('utilisateurs u', 'u.id_utilisateur = l.id_utilisateur')
                 ->join('produits p', 'p.id_produit = l.id_produit')
                 ->order_by('l.date_ajout', 'DESC');
        
        if (!empty($filters['id_utilisateur'])) {
            $this->db->where('l.id_utilisateur', $filters['id_utilisateur']);
        }
        if (!empty($filters['id_produit'])) {
            $this->db->where('l.id_produit', $filters['id_produit']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(l.date_ajout) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(l.date_ajout) <=', $filters['date_fin']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Compter tous les souhaits (Admin)
     */
    public function count_all($filters = []) {
        $this->db->from('liste_souhaits l');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = l.id_utilisateur');
        $this->db->join('produits p', 'p.id_produit = l.id_produit');
        
        if (!empty($filters['id_utilisateur'])) {
            $this->db->where('l.id_utilisateur', $filters['id_utilisateur']);
        }
        if (!empty($filters['id_produit'])) {
            $this->db->where('l.id_produit', $filters['id_produit']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(l.date_ajout) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(l.date_ajout) <=', $filters['date_fin']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer un souhait par son ID
     */
    public function get_by_id($id_souhait) {
        $this->db->where('id_souhait', $id_souhait);
        return $this->db->get('liste_souhaits')->row();
    }

    /**
     * Récupérer un souhait avec détails
     */
    public function get_by_id_with_details($id_souhait) {
        $this->db->select('l.*, 
                          CONCAT(u.prenom, " ", u.nom) as utilisateur_nom, 
                          u.email as utilisateur_email,
                          u.telephone as utilisateur_telephone,
                          p.nom_produit as produit_nom,
                          p.sku as produit_sku,
                          p.prix_base,
                          p.prix_promo,
                          c.nom_categorie')
                 ->from('liste_souhaits l')
                 ->join('utilisateurs u', 'u.id_utilisateur = l.id_utilisateur')
                 ->join('produits p', 'p.id_produit = l.id_produit')
                 ->join('categories c', 'c.id_categorie = p.id_categorie', 'left')
                 ->where('l.id_souhait', $id_souhait);
        
        return $this->db->get()->row();
    }

    /**
     * Vérifier si un produit est dans la liste
     */
    public function existe($id_utilisateur, $id_produit) {
        $this->db->where('id_utilisateur', $id_utilisateur)
                 ->where('id_produit', $id_produit);
        return $this->db->count_all_results('liste_souhaits') > 0;
    }

    /**
     * Ajouter un souhait
     */
    public function ajouter($data) {
        $this->db->insert('liste_souhaits', $data);
        return $this->db->insert_id();
    }

    /**
     * Supprimer un souhait
     */
    public function supprimer($id_souhait) {
        $this->db->where('id_souhait', $id_souhait);
        return $this->db->delete('liste_souhaits');
    }

    /**
     * Supprimer par produit et utilisateur
     */
    public function supprimer_by_produit_user($id_produit, $id_utilisateur) {
        $this->db->where('id_produit', $id_produit)
                 ->where('id_utilisateur', $id_utilisateur);
        return $this->db->delete('liste_souhaits');
    }

    /**
     * Vider la liste d'un utilisateur
     */
    public function vider($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        return $this->db->delete('liste_souhaits');
    }

    /**
     * Statistiques
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(DISTINCT l.id_utilisateur) as utilisateurs_actifs,
            COUNT(l.id_souhait) as total_souhaits,
            COUNT(DISTINCT l.id_produit) as produits_souhaites,
            AVG((SELECT COUNT(*) FROM liste_souhaits ls WHERE ls.id_utilisateur = l.id_utilisateur)) as moyenne_par_utilisateur
        ');
        $this->db->from('liste_souhaits l');
        
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(l.date_ajout) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(l.date_ajout) <=', $filters['date_fin']);
        }
        
        $result = $this->db->get()->row();
        
        if (!$result->utilisateurs_actifs) $result->utilisateurs_actifs = 0;
        if (!$result->total_souhaits) $result->total_souhaits = 0;
        if (!$result->produits_souhaites) $result->produits_souhaites = 0;
        if (!$result->moyenne_par_utilisateur) $result->moyenne_par_utilisateur = 0;
        
        return $result;
    }

    /**
     * Top produits les plus souhaités
     */
    public function get_top_produits($limit = 10) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, COUNT(l.id_souhait) as total_souhaits')
                 ->from('liste_souhaits l')
                 ->join('produits p', 'p.id_produit = l.id_produit')
                 ->group_by('l.id_produit')
                 ->order_by('total_souhaits', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }
}
?>