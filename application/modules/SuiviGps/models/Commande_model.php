<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commande_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer une commande par son ID
     */
    public function get_by_id($id_commande) {
        $this->db->select('c.*, u.prenom, u.nom, u.email, u.telephone')
                 ->from('commandes c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->where('c.id_commande', $id_commande);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer une commande par son numéro
     */
    public function get_by_numero($numero_commande) {
        $this->db->select('c.*, u.prenom, u.nom, u.email, u.telephone')
                 ->from('commandes c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->where('c.numero_commande', $numero_commande);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer toutes les commandes
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('c.*, u.prenom, u.nom, u.email')
                 ->from('commandes c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->order_by('c.date_creation', 'DESC');
        
        if (!empty($filters['statut'])) {
            $this->db->where('c.statut_commande', $filters['statut']);
        }
        if (!empty($filters['id_utilisateur'])) {
            $this->db->where('c.id_utilisateur', $filters['id_utilisateur']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(c.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(c.date_creation) <=', $filters['date_fin']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Compter les commandes
     */
    public function count_all($filters = []) {
        $this->db->from('commandes c');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur');
        
        if (!empty($filters['statut'])) {
            $this->db->where('c.statut_commande', $filters['statut']);
        }
        if (!empty($filters['id_utilisateur'])) {
            $this->db->where('c.id_utilisateur', $filters['id_utilisateur']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(c.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(c.date_creation) <=', $filters['date_fin']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer les articles d'une commande
     */
    public function get_articles($id_commande) {
        $this->db->where('id_commande', $id_commande);
        return $this->db->get('articles_commande')->result();
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function update_statut($id_commande, $statut, $commentaire = null, $id_utilisateur = null) {
        $this->db->where('id_commande', $id_commande);
        $this->db->update('commandes', [
            'statut_commande' => $statut,
            'date_modification' => date('Y-m-d H:i:s')
        ]);
        
        if ($this->db->affected_rows() > 0) {
            // Ajouter à l'historique
            $this->db->insert('historique_statut_commande', [
                'id_commande' => $id_commande,
                'statut' => $statut,
                'commentaire' => $commentaire,
                'modifie_par' => $id_utilisateur,
                'date_creation' => date('Y-m-d H:i:s')
            ]);
            return true;
        }
        return false;
    }

    /**
     * Récupérer l'historique des statuts
     */
    public function get_historique($id_commande) {
        $this->db->where('id_commande', $id_commande)
                 ->order_by('date_creation', 'ASC');
        return $this->db->get('historique_statut_commande')->result();
    }

    /**
     * Mettre à jour les informations de livraison
     */
    public function update_livraison($id_commande, $data) {
        $this->db->where('id_commande', $id_commande);
        return $this->db->update('commandes', $data);
    }

    /**
     * Commandes par statut
     */
    public function get_count_by_statut() {
        $this->db->select('statut_commande, COUNT(*) as total')
                 ->from('commandes')
                 ->group_by('statut_commande');
        return $this->db->get()->result();
    }

    /**
     * Total des ventes par période
     */
    public function get_total_ventes($date_debut = null, $date_fin = null) {
        $this->db->select('SUM(montant_total) as total')
                 ->from('commandes')
                 ->where('statut_commande !=', 'annule');
        
        if ($date_debut) {
            $this->db->where('DATE(date_creation) >=', $date_debut);
        }
        if ($date_fin) {
            $this->db->where('DATE(date_creation) <=', $date_fin);
        }
        
        $result = $this->db->get()->row();
        return $result->total ?? 0;
    }

    /**
     * Commandes récentes
     */
    public function get_recentes($limit = 10) {
        $this->db->select('c.*, u.prenom, u.nom')
                 ->from('commandes c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->order_by('c.date_creation', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }
}
?>