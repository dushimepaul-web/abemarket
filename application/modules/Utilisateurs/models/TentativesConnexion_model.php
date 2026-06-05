<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TentativesConnexion_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter toutes les tentatives avec filtres
     */
    public function count_all($filters = []) {
        $this->db->from('tentatives_connexion t');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = t.id_utilisateur', 'left');
        
        if (isset($filters['reussie']) && $filters['reussie'] !== '') {
            $this->db->where('t.reussie', $filters['reussie']);
        }
        if (!empty($filters['ip'])) {
            $this->db->where('t.adresse_ip', $filters['ip']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(t.date_tentative) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(t.date_tentative) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('u.email', $filters['search']);
            $this->db->or_like('t.email_tente', $filters['search']);
            $this->db->or_like('t.adresse_ip', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer toutes les tentatives
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('t.*, u.id_utilisateur, u.email as utilisateur_email, u.prenom, u.nom')
                 ->from('tentatives_connexion t')
                 ->join('utilisateurs u', 'u.id_utilisateur = t.id_utilisateur', 'left')
                 ->order_by('t.date_tentative', 'DESC');
        
        if (isset($filters['reussie']) && $filters['reussie'] !== '') {
            $this->db->where('t.reussie', $filters['reussie']);
        }
        if (!empty($filters['ip'])) {
            $this->db->where('t.adresse_ip', $filters['ip']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(t.date_tentative) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(t.date_tentative) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('u.email', $filters['search']);
            $this->db->or_like('t.email_tente', $filters['search']);
            $this->db->or_like('t.adresse_ip', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer les statistiques
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN reussie = 1 THEN 1 ELSE 0 END) as succes,
            SUM(CASE WHEN reussie = 0 THEN 1 ELSE 0 END) as echec,
            COUNT(DISTINCT adresse_ip) as ips_uniques,
            SUM(CASE WHEN DATE(date_tentative) = CURDATE() THEN 1 ELSE 0 END) as aujourdhui
        ');
        $this->db->from('tentatives_connexion');
        
        if (isset($filters['reussie']) && $filters['reussie'] !== '') {
            $this->db->where('reussie', $filters['reussie']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(date_tentative) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(date_tentative) <=', $filters['date_fin']);
        }
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer les IPs les plus actives
     */
    public function get_top_ips($limit = 10) {
        $this->db->select('adresse_ip, COUNT(*) as nombre, SUM(CASE WHEN reussie = 1 THEN 1 ELSE 0 END) as succes, SUM(CASE WHEN reussie = 0 THEN 1 ELSE 0 END) as echec')
                 ->from('tentatives_connexion')
                 ->group_by('adresse_ip')
                 ->order_by('nombre', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Supprimer une tentative
     */
    public function delete($id) {
        $this->db->where('id_tentative', $id);
        $this->db->delete('tentatives_connexion');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer plusieurs tentatives
     */
    public function delete_multiple($ids) {
        $this->db->where_in('id_tentative', $ids);
        $this->db->delete('tentatives_connexion');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Vider l'historique
     */
    public function vider($date_limite = null) {
        if ($date_limite) {
            $this->db->where('DATE(date_tentative) <', $date_limite);
        }
        $this->db->delete('tentatives_connexion');
        return $this->db->affected_rows() > 0;
    }
}
?>