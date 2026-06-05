<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArticleCommande_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupérer tous les articles avec filtres (admin)
     */
    public function get_all_articles($limit = null, $offset = null, $filters = []) {
        $this->db->select('ac.*, 
                          c.numero_commande, c.statut_commande,
                          u.prenom, u.nom, u.email,
                          p.nom_produit, p.sku, p.marque,
                          v.attributs_variante, v.sku as variante_sku,
                          vd.nom_boutique,
                          i.url_image as image_url')
                 ->from('articles_commande ac')
                 ->join('commandes c', 'ac.id_commande = c.id_commande')
                 ->join('utilisateurs u', 'c.id_utilisateur = u.id_utilisateur', 'left')
                 ->join('produits p', 'ac.id_produit = p.id_produit', 'left')
                 ->join('variantes_produit v', 'ac.id_variante = v.id_variante', 'left')
                 ->join('vendeurs vd', 'ac.id_vendeur = vd.id_vendeur', 'left')
                 ->join('images_produit i', 'p.id_produit = i.id_produit AND i.est_principale = 1', 'left')
                 ->order_by('ac.date_creation', 'DESC');
        
        // Appliquer les filtres
        if (!empty($filters['statut_article'])) {
            $this->db->where('ac.statut_article', $filters['statut_article']);
        }
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('ac.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('c.numero_commande', $filters['search']);
            $this->db->or_like('p.nom_produit', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Récupérer les articles d'une commande
     */
    public function get_articles_by_commande($id_commande) {
        $this->db->select('ac.*, p.nom_produit, p.sku, p.marque,
                          v.attributs_variante, v.sku as variante_sku,
                          vd.nom_boutique, vd.id_vendeur,
                          i.url_image as image_url')
                 ->from('articles_commande ac')
                 ->join('produits p', 'ac.id_produit = p.id_produit', 'left')
                 ->join('variantes_produit v', 'ac.id_variante = v.id_variante', 'left')
                 ->join('vendeurs vd', 'ac.id_vendeur = vd.id_vendeur', 'left')
                 ->join('images_produit i', 'p.id_produit = i.id_produit AND i.est_principale = 1', 'left')
                 ->where('ac.id_commande', $id_commande);
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Récupérer les articles d'un vendeur
     */
    public function get_articles_by_vendeur($id_vendeur, $limit = null, $offset = null, $filters = []) {
        $this->db->select('ac.*, 
                          c.numero_commande, c.statut_commande,
                          u.prenom, u.nom, u.email, u.telephone,
                          p.nom_produit, p.sku,
                          v.attributs_variante,
                          i.url_image as image_url')
                 ->from('articles_commande ac')
                 ->join('commandes c', 'ac.id_commande = c.id_commande')
                 ->join('utilisateurs u', 'c.id_utilisateur = u.id_utilisateur', 'left')
                 ->join('produits p', 'ac.id_produit = p.id_produit', 'left')
                 ->join('variantes_produit v', 'ac.id_variante = v.id_variante', 'left')
                 ->join('images_produit i', 'p.id_produit = i.id_produit AND i.est_principale = 1', 'left')
                 ->where('ac.id_vendeur', $id_vendeur)
                 ->order_by('ac.date_creation', 'DESC');
        
        if (!empty($filters['statut_article'])) {
            $this->db->where('ac.statut_article', $filters['statut_article']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('c.numero_commande', $filters['search']);
            $this->db->or_like('p.nom_produit', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Récupérer un article par son ID
     */
    public function get_article_by_id($id_article) {
        $this->db->select('ac.*, 
                          c.numero_commande, c.statut_commande, c.date_creation as commande_date,
                          u.prenom, u.nom, u.email, u.telephone,
                          a.nom_complet as destinataire, a.adresse_ligne, a.telephone as tel_livraison,
                          p.nom_produit, p.sku, p.marque, p.description_courte,
                          v.attributs_variante, v.sku as variante_sku, v.prix as variante_prix,
                          vd.nom_boutique, vd.telephone as vendeur_telephone,
                          i.url_image as image_url')
                 ->from('articles_commande ac')
                 ->join('commandes c', 'ac.id_commande = c.id_commande')
                 ->join('utilisateurs u', 'c.id_utilisateur = u.id_utilisateur', 'left')
                 ->join('adresses a', 'c.id_adresse_livraison = a.id_adresse', 'left')
                 ->join('produits p', 'ac.id_produit = p.id_produit', 'left')
                 ->join('variantes_produit v', 'ac.id_variante = v.id_variante', 'left')
                 ->join('vendeurs vd', 'ac.id_vendeur = vd.id_vendeur', 'left')
                 ->join('images_produit i', 'p.id_produit = i.id_produit AND i.est_principale = 1', 'left')
                 ->where('ac.id_article', $id_article);
        
        $query = $this->db->get();
        return $query->row();
    }
    
    /**
     * Mettre à jour le statut d'un article
     */
    public function update_statut_article($id_article, $statut, $commentaire = null) {
        $this->db->where('id_article', $id_article);
        $this->db->update('articles_commande', ['statut_article' => $statut]);
        
        if ($this->db->affected_rows() > 0) {
            // Ajouter dans l'historique de la commande
            $article = $this->get_article_by_id($id_article);
            if ($article) {
                $this->load->model('HistoriqueStatut_model');
                $this->HistoriqueStatut_model->ajouter_historique([
                    'id_commande' => $article->id_commande,
                    'statut' => $statut,
                    'commentaire' => $commentaire ?: "Article #{$id_article} : {$article->nom_produit}",
                    'modifie_par' => $this->session->userdata('id_utilisateur')
                ]);
            }
            return true;
        }
        return false;
    }
    
    /**
     * Marquer un article comme retourné
     */
    public function marquer_retourne($id_article, $motif, $commentaire = null) {
        $this->db->where('id_article', $id_article);
        $this->db->update('articles_commande', [
            'est_retourne' => 1,
            'motif_retour' => $motif,
            'date_demande_retour' => date('Y-m-d H:i:s'),
            'statut_article' => 'retourne'
        ]);
        
        if ($this->db->affected_rows() > 0) {
            $article = $this->get_article_by_id($id_article);
            if ($article) {
                $this->load->model('HistoriqueStatut_model');
                $this->HistoriqueStatut_model->ajouter_historique([
                    'id_commande' => $article->id_commande,
                    'statut' => 'retourne',
                    'commentaire' => $commentaire ?: "Retour demandé pour: {$article->nom_produit} - Motif: {$motif}",
                    'modifie_par' => $this->session->userdata('id_utilisateur')
                ]);
            }
            return true;
        }
        return false;
    }
    
    /**
     * Compter les articles par statut
     */
    public function count_articles_by_statut($id_vendeur = null) {
        $this->db->select('statut_article, COUNT(*) as total')
                 ->from('articles_commande');
        
        if ($id_vendeur) {
            $this->db->where('id_vendeur', $id_vendeur);
        }
        
        $this->db->group_by('statut_article');
        $query = $this->db->get();
        $results = $query->result();
        
        $stats = [];
        foreach ($results as $r) {
            $stats[$r->statut_article] = $r->total;
        }
        
        return $stats;
    }
    
    /**
     * Compter les articles par statut pour une commande
     */
    public function count_articles_by_commande_statut($id_commande) {
        $this->db->select('statut_article, COUNT(*) as total')
                 ->from('articles_commande')
                 ->where('id_commande', $id_commande)
                 ->group_by('statut_article');
        
        $query = $this->db->get();
        $results = $query->result();
        
        $stats = [];
        foreach ($results as $r) {
            $stats[$r->statut_article] = $r->total;
        }
        
        return $stats;
    }
    
    /**
     * Obtenir les statistiques des ventes par vendeur
     */
    public function get_ventes_stats($id_vendeur = null) {
        $this->db->select('
            COUNT(*) as total_articles,
            SUM(quantite) as total_quantite,
            SUM(prix_total) as chiffre_affaires,
            SUM(montant_commission) as total_commissions,
            SUM(revenus_vendeur) as revenus_vendeur,
            AVG(prix_unitaire) as prix_moyen
        ');
        
        if ($id_vendeur) {
            $this->db->where('id_vendeur', $id_vendeur);
        }
        
        $query = $this->db->get('articles_commande');
        return $query->row();
    }
    
    /**
     * Récupérer les articles avec avis non laissés
     */
    public function get_articles_sans_avis($id_utilisateur) {
        $this->db->select('ac.*, p.nom_produit, p.slug_produit, i.url_image')
                 ->from('articles_commande ac')
                 ->join('commandes c', 'ac.id_commande = c.id_commande')
                 ->join('produits p', 'ac.id_produit = p.id_produit', 'left')
                 ->join('images_produit i', 'p.id_produit = i.id_produit AND i.est_principale = 1', 'left')
                 ->where('c.id_utilisateur', $id_utilisateur)
                 ->where('ac.avis_laisse', 0)
                 ->where('ac.statut_article', 'livre')
                 ->order_by('ac.date_creation', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Marquer un article comme ayant reçu un avis
     */
    public function marquer_avis_laisse($id_article) {
        $this->db->where('id_article', $id_article);
        $this->db->update('articles_commande', ['avis_laisse' => 1]);
        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Exporter les articles en CSV
     */
    public function exporter_articles($id_vendeur = null, $filters = []) {
        if ($id_vendeur) {
            $articles = $this->get_articles_by_vendeur($id_vendeur, null, null, $filters);
        } else {
            $articles = $this->get_all_articles(null, null, $filters);
        }
        
        $data = [];
        $headers = ['ID', 'N° Commande', 'Produit', 'Variante', 'Quantité', 'Prix unitaire', 'Total', 'Commission', 'Revenu vendeur', 'Statut', 'Client', 'Date'];
        $data[] = $headers;
        
        foreach ($articles as $a) {
            $attrs = $a->attributs_variante ? json_decode($a->attributs_variante, true) : [];
            $variante = '';
            if (isset($attrs['taille'])) $variante .= 'Taille: ' . $attrs['taille'];
            if (isset($attrs['couleur'])) $variante .= ($variante ? ' | ' : '') . 'Couleur: ' . $attrs['couleur'];
            
            $row = [
                $a->id_article,
                $a->numero_commande,
                $a->nom_produit,
                $variante ?: '-',
                $a->quantite,
                number_format($a->prix_unitaire, 2),
                number_format($a->prix_total, 2),
                number_format($a->montant_commission, 2),
                number_format($a->revenus_vendeur, 2),
                $a->statut_article,
                ($a->prenom ?? '') . ' ' . ($a->nom ?? ''),
                date('d/m/Y', strtotime($a->date_creation))
            ];
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * Obtenir les statuts disponibles
     */
    public function get_statuts_options() {
        return [
            'en_attente' => ['label' => 'En attente', 'color' => 'warning', 'icon' => 'clock'],
            'prepare' => ['label' => 'En préparation', 'color' => 'primary', 'icon' => 'package'],
            'expedie' => ['label' => 'Expédié', 'color' => 'info', 'icon' => 'truck'],
            'livre' => ['label' => 'Livré', 'color' => 'success', 'icon' => 'check-circle'],
            'retourne' => ['label' => 'Retourné', 'color' => 'danger', 'icon' => 'refresh'],
            'annule' => ['label' => 'Annulé', 'color' => 'secondary', 'icon' => 'x-circle']
        ];
    }
}
?>