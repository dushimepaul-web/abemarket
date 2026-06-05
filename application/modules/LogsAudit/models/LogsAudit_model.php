<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogsAudit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer tous les logs
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('l.*, CONCAT(u.prenom, " ", u.nom) as utilisateur_nom, u.email as utilisateur_email')
                 ->from('logs_audit l')
                 ->join('utilisateurs u', 'u.id_utilisateur = l.id_utilisateur', 'left')
                 ->order_by('l.date_creation', 'DESC');
        
        if (!empty($filters['id_utilisateur'])) {
            $this->db->where('l.id_utilisateur', $filters['id_utilisateur']);
        }
        if (!empty($filters['type_action'])) {
            $this->db->where('l.type_action', $filters['type_action']);
        }
        if (!empty($filters['table_cible'])) {
            $this->db->where('l.table_cible', $filters['table_cible']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(l.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(l.date_creation) <=', $filters['date_fin']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Compter les logs
     */
    public function count_all($filters = []) {
        $this->db->from('logs_audit l');
        
        if (!empty($filters['id_utilisateur'])) {
            $this->db->where('l.id_utilisateur', $filters['id_utilisateur']);
        }
        if (!empty($filters['type_action'])) {
            $this->db->where('l.type_action', $filters['type_action']);
        }
        if (!empty($filters['table_cible'])) {
            $this->db->where('l.table_cible', $filters['table_cible']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(l.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(l.date_creation) <=', $filters['date_fin']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer un log par ID
     */
    public function get_by_id($id_log) {
        $this->db->where('id_log', $id_log);
        return $this->db->get('logs_audit')->row();
    }

    /**
     * Récupérer un log avec détails
     */
    public function get_by_id_with_details($id_log) {
        $this->db->select('l.*, CONCAT(u.prenom, " ", u.nom) as utilisateur_nom, u.email as utilisateur_email')
                 ->from('logs_audit l')
                 ->join('utilisateurs u', 'u.id_utilisateur = l.id_utilisateur', 'left')
                 ->where('l.id_log', $id_log);
        
        return $this->db->get()->row();
    }

    /**
     * Ajouter un log
     */
    public function ajouter($data) {
        $this->db->insert('logs_audit', $data);
        return $this->db->insert_id();
    }

    /**
     * Logger une action
     */
    public function log($id_utilisateur, $type_action, $table_cible = null, $id_cible = null, $valeurs_avant = null, $valeurs_apres = null) {
        $data = [
            'id_utilisateur' => $id_utilisateur,
            'type_action' => $type_action,
            'table_cible' => $table_cible,
            'id_cible' => $id_cible,
            'valeurs_avant' => $valeurs_avant ? json_encode($valeurs_avant) : null,
            'valeurs_apres' => $valeurs_apres ? json_encode($valeurs_apres) : null,
            'adresse_ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        return $this->ajouter($data);
    }

    /**
     * Nettoyer les anciens logs
     */
    public function clean_old_logs($jours = 90) {
        $date_limite = date('Y-m-d H:i:s', strtotime('-' . $jours . ' days'));
        $this->db->where('date_creation <', $date_limite);
        $this->db->delete('logs_audit');
        return $this->db->affected_rows();
    }

    /**
     * Supprimer tous les logs
     */
    public function truncate() {
        return $this->db->truncate('logs_audit');
    }

    /**
     * Obtenir les types d'actions distincts
     */
    public function get_distinct_types() {
        $this->db->distinct()
                 ->select('type_action')
                 ->from('logs_audit')
                 ->order_by('type_action', 'ASC');
        
        $results = $this->db->get()->result();
        return array_map(function($r) { return $r->type_action; }, $results);
    }

    /**
     * Obtenir les tables cibles distinctes
     */
    public function get_distinct_tables() {
        $this->db->distinct()
                 ->select('table_cible')
                 ->from('logs_audit')
                 ->where('table_cible IS NOT NULL')
                 ->order_by('table_cible', 'ASC');
        
        $results = $this->db->get()->result();
        return array_map(function($r) { return $r->table_cible; }, $results);
    }

    /**
     * Statistiques générales
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(id_log) as total_logs,
            COUNT(DISTINCT id_utilisateur) as utilisateurs_actifs,
            COUNT(DISTINCT type_action) as types_actions,
            COUNT(DISTINCT table_cible) as tables_modifiees,
            MIN(date_creation) as premier_log,
            MAX(date_creation) as dernier_log
        ');
        $this->db->from('logs_audit');
        
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(date_creation) <=', $filters['date_fin']);
        }
        
        $result = $this->db->get()->row();
        
        if (!$result->total_logs) $result->total_logs = 0;
        if (!$result->utilisateurs_actifs) $result->utilisateurs_actifs = 0;
        if (!$result->types_actions) $result->types_actions = 0;
        if (!$result->tables_modifiees) $result->tables_modifiees = 0;
        
        return $result;
    }

    /**
     * Statistiques par type d'action
     */
    public function get_stats_by_action($limit = null) {
        $this->db->select('type_action, COUNT(*) as total')
                 ->from('logs_audit')
                 ->group_by('type_action')
                 ->order_by('total', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Statistiques par table cible
     */
    public function get_stats_by_table($limit = null) {
        $this->db->select('table_cible, COUNT(*) as total')
                 ->from('logs_audit')
                 ->where('table_cible IS NOT NULL')
                 ->group_by('table_cible')
                 ->order_by('total', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Statistiques par jour
     */
    public function get_stats_by_day($jours = 30) {
        $this->db->select('DATE(date_creation) as date, COUNT(*) as total')
                 ->from('logs_audit')
                 ->where('date_creation >', date('Y-m-d H:i:s', strtotime('-' . $jours . ' days')))
                 ->group_by('DATE(date_creation)')
                 ->order_by('date', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Statistiques par utilisateur
     */
    public function get_stats_by_user($limit = 10) {
        $this->db->select('l.id_utilisateur, CONCAT(u.prenom, " ", u.nom) as nom, COUNT(*) as total')
                 ->from('logs_audit l')
                 ->join('utilisateurs u', 'u.id_utilisateur = l.id_utilisateur', 'left')
                 ->group_by('l.id_utilisateur')
                 ->order_by('total', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Rechercher dans les logs
     */
    public function search($keyword, $limit = 50) {
        $this->db->select('l.*, CONCAT(u.prenom, " ", u.nom) as utilisateur_nom')
                 ->from('logs_audit l')
                 ->join('utilisateurs u', 'u.id_utilisateur = l.id_utilisateur', 'left')
                 ->group_start()
                     ->like('l.type_action', $keyword)
                     ->or_like('l.table_cible', $keyword)
                     ->or_like('l.adresse_ip', $keyword)
                     ->or_like('CONCAT(u.prenom, " ", u.nom)', $keyword)
                 ->group_end()
                 ->order_by('l.date_creation', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }
}
?>