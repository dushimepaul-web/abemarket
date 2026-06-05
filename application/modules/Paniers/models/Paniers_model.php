<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paniers_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter tous les paniers
     */
    public function count_all($filters = []) {
        $this->db->from('paniers p');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = p.id_utilisateur');
        $this->db->join('produits pr', 'pr.id_produit = p.id_produit');
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->or_like('pr.nom_produit', $filters['search']);
            $this->db->group_end();
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(p.date_ajout) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(p.date_ajout) <=', $filters['date_fin']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les paniers (admin)
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('p.*, u.prenom, u.nom, u.email, pr.nom_produit, pr.prix_base, pr.prix_promo, v.sku as variante_sku')
                 ->from('paniers p')
                 ->join('utilisateurs u', 'u.id_utilisateur = p.id_utilisateur')
                 ->join('produits pr', 'pr.id_produit = p.id_produit')
                 ->join('variantes_produit v', 'v.id_variante = p.id_variante', 'left')
                 ->order_by('p.date_ajout', 'DESC');
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->or_like('pr.nom_produit', $filters['search']);
            $this->db->group_end();
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(p.date_ajout) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(p.date_ajout) <=', $filters['date_fin']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer le panier d'un utilisateur
     */
    public function get_by_utilisateur($id_utilisateur) {
        $this->db->select('p.*, pr.nom_produit, pr.sku, pr.prix_base, pr.prix_promo, v.sku as variante_sku, v.attributs_variante')
                 ->from('paniers p')
                 ->join('produits pr', 'pr.id_produit = p.id_produit')
                 ->join('variantes_produit v', 'v.id_variante = p.id_variante', 'left')
                 ->where('p.id_utilisateur', $id_utilisateur)
                 ->order_by('p.date_ajout', 'ASC');
        
        $items = $this->db->get()->result();
        
        foreach ($items as $item) {
            // Calculer le prix
            if ($item->prix_promo && $item->prix_promo < $item->prix_base) {
                $item->prix_actuel = $item->prix_promo;
            } else {
                $item->prix_actuel = $item->prix_base;
            }
            
            // Décoder les attributs
            if ($item->attributs_variante) {
                $item->attributs = json_decode($item->attributs_variante, true);
            } else {
                $item->attributs = [];
            }
        }
        
        return $items;
    }

    /**
     * Récupérer le total du panier d'un utilisateur
     */
    public function get_total_by_utilisateur($id_utilisateur) {
        $this->db->select('SUM(CASE WHEN pr.prix_promo IS NOT NULL AND pr.prix_promo < pr.prix_base THEN pr.prix_promo * p.quantite ELSE pr.prix_base * p.quantite END) as total')
                 ->from('paniers p')
                 ->join('produits pr', 'pr.id_produit = p.id_produit')
                 ->where('p.id_utilisateur', $id_utilisateur);
        
        $result = $this->db->get()->row();
        return $result->total ?? 0;
    }

    /**
     * Récupérer un article par ID
     */
    public function get_article_by_id($id_panier) {
        return $this->db->where('id_panier', $id_panier)->get('paniers')->row();
    }

    /**
     * Ajouter un article au panier
     */
    public function ajouter($id_utilisateur, $id_produit, $id_variante = null, $quantite = 1) {
        // Vérifier si l'article existe déjà
        $this->db->where('id_utilisateur', $id_utilisateur);
        $this->db->where('id_produit', $id_produit);
        
        if ($id_variante) {
            $this->db->where('id_variante', $id_variante);
        } else {
            $this->db->where('id_variante IS NULL');
        }
        
        $existing = $this->db->get('paniers')->row();
        
        if ($existing) {
            // Mettre à jour la quantité
            $this->db->where('id_panier', $existing->id_panier);
            $this->db->update('paniers', ['quantite' => $existing->quantite + $quantite]);
            return $existing->id_panier;
        } else {
            // Ajouter nouvel article
            $data = [
                'id_utilisateur' => $id_utilisateur,
                'id_produit' => $id_produit,
                'id_variante' => $id_variante,
                'quantite' => $quantite,
                'date_ajout' => date('Y-m-d H:i:s'),
                'date_modification' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('paniers', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Mettre à jour la quantité
     */
    public function update_quantite($id_panier, $quantite) {
        if ($quantite <= 0) {
            return $this->delete_article($id_panier);
        }
        
        $this->db->where('id_panier', $id_panier);
        $this->db->update('paniers', ['quantite' => $quantite]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un article
     */
    public function delete_article($id_panier) {
        $this->db->where('id_panier', $id_panier);
        $this->db->delete('paniers');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Vider le panier d'un utilisateur
     */
    public function vider_panier($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        $this->db->delete('paniers');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(DISTINCT p.id_utilisateur) as utilisateurs_actifs,
            COUNT(p.id_panier) as total_articles,
            SUM(p.quantite) as total_quantite,
            SUM(CASE WHEN pr.prix_promo IS NOT NULL AND pr.prix_promo < pr.prix_base THEN pr.prix_promo * p.quantite ELSE pr.prix_base * p.quantite END) as valeur_totale
        ');
        $this->db->from('paniers p');
        $this->db->join('produits pr', 'pr.id_produit = p.id_produit');
        
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(p.date_ajout) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(p.date_ajout) <=', $filters['date_fin']);
        }
        
        $result = $this->db->get()->row();
        
        if (!$result->utilisateurs_actifs) $result->utilisateurs_actifs = 0;
        if (!$result->total_articles) $result->total_articles = 0;
        if (!$result->total_quantite) $result->total_quantite = 0;
        if (!$result->valeur_totale) $result->valeur_totale = 0;
        
        return $result;
    }

    /**
     * Export des paniers
     */
    public function get_all_paniers_export($filters = []) {
        $this->db->select('p.*, u.prenom, u.nom, u.email, pr.nom_produit, pr.prix_base, v.sku as variante_sku')
                 ->from('paniers p')
                 ->join('utilisateurs u', 'u.id_utilisateur = p.id_utilisateur')
                 ->join('produits pr', 'pr.id_produit = p.id_produit')
                 ->join('variantes_produit v', 'v.id_variante = p.id_variante', 'left')
                 ->order_by('p.date_ajout', 'DESC');
        
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(p.date_ajout) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(p.date_ajout) <=', $filters['date_fin']);
        }
        
        return $this->db->get()->result();
    }
}
?>