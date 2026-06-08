<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commande_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter toutes les commandes
     */
    public function count_all($filters = [], $id_vendeur = null, $is_admin = true) {
        if (!$is_admin && $id_vendeur) {
            // Compter les commandes distinctes du vendeur via articles_commande
            $this->db->select('c.id_commande')
                     ->from('commandes c')
                     ->join('articles_commande a', 'a.id_commande = c.id_commande')
                     ->where('a.id_vendeur', $id_vendeur)
                     ->group_by('c.id_commande');
        } else {
            $this->db->from('commandes c');
        }
        
        $this->db->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur');
        
        if (!empty($filters['statut_commande'])) {
            $this->db->where('c.statut_commande', $filters['statut_commande']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(c.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(c.date_creation) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('c.numero_commande', $filters['search']);
            $this->db->or_like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->group_end();
        }
        
        if (!$is_admin && $id_vendeur) {
            $query = $this->db->get();
            return $query->num_rows();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer toutes les commandes
     */
    public function get_all($limit = null, $offset = null, $filters = [], $id_vendeur = null, $is_admin = true) {
        $this->db->select('c.*, u.prenom, u.nom, u.email, CONCAT(u.prenom, " ", u.nom) as client_nom')
                 ->from('commandes c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->order_by('c.date_creation', 'DESC');
        
        if (!$is_admin && $id_vendeur) {
            $this->db->join('articles_commande a', 'a.id_commande = c.id_commande');
            $this->db->where('a.id_vendeur', $id_vendeur);
            $this->db->distinct();
        }
        
        if (!empty($filters['statut_commande'])) {
            $this->db->where('c.statut_commande', $filters['statut_commande']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(c.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(c.date_creation) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('c.numero_commande', $filters['search']);
            $this->db->or_like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer une commande par ID
     */
    public function get_by_id($id) {
        $this->db->select('c.*, u.prenom, u.nom, u.email, u.telephone, mp.description as mode_paiement')
                 ->from('commandes c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->join('mode_payement mp', 'mp.id_mode_payement = c.id_mode_payement', 'left')
                 ->where('c.id_commande', $id);
        
        return $this->db->get()->row();
    }


/**
 * Mettre à jour une commande
 */
public function update($id, $data) {
    $this->db->where('id_commande', $id);
    $this->db->update('commandes', $data);
    return $this->db->affected_rows() > 0;
}


    /**
 * Récupérer une commande par ID
 */
public function get_commande_by_id($id) {
    $this->db->select('c.*, u.prenom, u.nom, u.email, u.telephone, mp.description as mode_paiement')
             ->from('commandes c')
             ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
             ->join('mode_payement mp', 'mp.id_mode_payement = c.id_mode_payement', 'left')
             ->where('c.id_commande', $id);
    
    return $this->db->get()->row();
}

    /**
     * Récupérer les articles d'une commande
     */
    /**
 * Récupérer les articles d'une commande
 */
public function get_articles($id_commande, $id_vendeur = null) {
    $this->db->select('a.*, p.nom_produit, p.sku as sku_produit')
             ->from('articles_commande a')
             ->join('produits p', 'p.id_produit = a.id_produit', 'left')
             ->where('a.id_commande', $id_commande);
    
    if ($id_vendeur) {
        $this->db->where('a.id_vendeur', $id_vendeur);
    }
    
    return $this->db->get()->result();
}
    /**
     * Récupérer l'historique des statuts d'une commande
     */
    public function get_historique_statuts($id_commande) {
        $this->db->select('h.*, u.prenom, u.nom')
                 ->from('historique_statut_commande h')
                 ->join('utilisateurs u', 'u.id_utilisateur = h.modifie_par', 'left')
                 ->where('h.id_commande', $id_commande)
                 ->order_by('h.date_creation', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer le QR code d'une commande
     */
    public function get_qr_by_commande($id_commande) {
        return $this->db->where('id_commande', $id_commande)->get('qr_confirmations')->row();
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function update_statut($id_commande, $statut) {
        $this->db->where('id_commande', $id_commande);
        $this->db->update('commandes', ['statut_commande' => $statut]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques
     */
    public function get_stats($id_vendeur = null, $is_admin = true) {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN statut_commande = "en_attente" THEN 1 ELSE 0 END) as en_attente,
            SUM(CASE WHEN statut_commande = "confirme" THEN 1 ELSE 0 END) as confirme,
            SUM(CASE WHEN statut_commande = "en_preparation" THEN 1 ELSE 0 END) as en_preparation,
            SUM(CASE WHEN statut_commande = "expedie" THEN 1 ELSE 0 END) as expedie,
            SUM(CASE WHEN statut_commande = "livre" THEN 1 ELSE 0 END) as livre,
            SUM(CASE WHEN statut_commande = "annule" THEN 1 ELSE 0 END) as annule,
            SUM(montant_total) as chiffre_affaires
        ');
        $this->db->from('commandes c');
        
        if (!$is_admin && $id_vendeur) {
            $this->db->join('articles_commande a', 'a.id_commande = c.id_commande');
            $this->db->where('a.id_vendeur', $id_vendeur);
        }
        
        return $this->db->get()->row();
    }
}
?>