<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter toutes les notifications
     */
    public function count_all($filters = []) {
        $this->db->from('notifications n');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = n.id_utilisateur');
        
        if (!empty($filters['categorie'])) {
            $this->db->where('n.categorie', $filters['categorie']);
        }
        if (isset($filters['est_lue']) && $filters['est_lue'] !== '') {
            $this->db->where('n.est_lue', $filters['est_lue']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(n.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(n.date_creation) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->or_like('n.titre', $filters['search']);
            $this->db->or_like('n.message', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer toutes les notifications
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('n.*, u.prenom, u.nom, u.email, CONCAT(u.prenom, " ", u.nom) as utilisateur_nom')
                 ->from('notifications n')
                 ->join('utilisateurs u', 'u.id_utilisateur = n.id_utilisateur')
                 ->order_by('n.date_creation', 'DESC');
        
        if (!empty($filters['categorie'])) {
            $this->db->where('n.categorie', $filters['categorie']);
        }
        if (isset($filters['est_lue']) && $filters['est_lue'] !== '') {
            $this->db->where('n.est_lue', $filters['est_lue']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(n.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(n.date_creation) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->or_like('n.titre', $filters['search']);
            $this->db->or_like('n.message', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer les notifications d'un utilisateur
     */
    public function get_by_utilisateur($id_utilisateur, $limit = 50) {
        $this->db->where('id_utilisateur', $id_utilisateur)
                 ->order_by('date_creation', 'DESC')
                 ->limit($limit);
        
        return $this->db->get('notifications')->result();
    }

    /**
     * Récupérer une notification par ID
     */
    public function get_by_id($id) {
        $this->db->select('n.*, u.prenom, u.nom, u.email')
                 ->from('notifications n')
                 ->join('utilisateurs u', 'u.id_utilisateur = n.id_utilisateur')
                 ->where('n.id_notification', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Créer une notification
     */
    public function creer($id_utilisateur, $type_canal, $categorie, $titre, $message) {
        $data = [
            'id_utilisateur' => $id_utilisateur,
            'type_canal' => $type_canal,
            'categorie' => $categorie,
            'titre' => $titre,
            'message' => $message,
            'est_lue' => 0,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('notifications', $data);
        return $this->db->insert_id();
    }

    /**
     * Marquer une notification comme lue
     */
    public function marquer_lue($id) {
        $this->db->where('id_notification', $id);
        $this->db->update('notifications', [
            'est_lue' => 1,
            'date_lecture' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Marquer toutes les notifications d'un utilisateur comme lues
     */
    public function marquer_toutes_lues($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        $this->db->where('est_lue', 0);
        $this->db->update('notifications', [
            'est_lue' => 1,
            'date_lecture' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer une notification
     */
    public function delete($id) {
        $this->db->where('id_notification', $id);
        $this->db->delete('notifications');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer toutes les notifications d'un utilisateur
     */
    public function supprimer_toutes_utilisateur($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        $this->db->delete('notifications');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer toutes les notifications
     */
    public function supprimer_toutes() {
        $this->db->empty_table('notifications');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Compter les notifications non lues
     */
    public function count_non_lues($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        $this->db->where('est_lue', 0);
        return $this->db->count_all_results('notifications');
    }

    /**
     * Statistiques globales
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN est_lue = 1 THEN 1 ELSE 0 END) as lues,
            SUM(CASE WHEN est_lue = 0 THEN 1 ELSE 0 END) as non_lues,
            SUM(CASE WHEN categorie = "commande" THEN 1 ELSE 0 END) as commande,
            SUM(CASE WHEN categorie = "paiement" THEN 1 ELSE 0 END) as paiement,
            SUM(CASE WHEN categorie = "livraison" THEN 1 ELSE 0 END) as livraison,
            SUM(CASE WHEN categorie = "securite" THEN 1 ELSE 0 END) as securite,
            SUM(CASE WHEN categorie = "systeme" THEN 1 ELSE 0 END) as systeme,
            COUNT(DISTINCT id_utilisateur) as utilisateurs_concernes
        ');
        
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(date_creation) <=', $filters['date_fin']);
        }
        
        $result = $this->db->get('notifications')->row();
        
        if (!$result->total) $result->total = 0;
        if (!$result->lues) $result->lues = 0;
        if (!$result->non_lues) $result->non_lues = 0;
        if (!$result->commande) $result->commande = 0;
        if (!$result->paiement) $result->paiement = 0;
        if (!$result->livraison) $result->livraison = 0;
        if (!$result->securite) $result->securite = 0;
        if (!$result->systeme) $result->systeme = 0;
        if (!$result->utilisateurs_concernes) $result->utilisateurs_concernes = 0;
        
        return $result;
    }

    /**
     * Statistiques par utilisateur
     */
    public function get_stats_by_utilisateur($id_utilisateur) {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN est_lue = 1 THEN 1 ELSE 0 END) as lues,
            SUM(CASE WHEN est_lue = 0 THEN 1 ELSE 0 END) as non_lues
        ');
        $this->db->where('id_utilisateur', $id_utilisateur);
        
        $result = $this->db->get('notifications')->row();
        
        if (!$result->total) $result->total = 0;
        if (!$result->lues) $result->lues = 0;
        if (!$result->non_lues) $result->non_lues = 0;
        
        return $result;
    }
}
?>