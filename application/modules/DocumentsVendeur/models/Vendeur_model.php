<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendeur_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer un vendeur par ID
     */
    public function get_vendeur_by_id($id_vendeur) {
        $this->db->select('v.*, u.email, u.prenom, u.nom')
                 ->from('vendeurs v')
                 ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
                 ->where('v.id_vendeur', $id_vendeur);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer un vendeur par ID utilisateur
     */
    public function get_vendeur_by_user_id($id_utilisateur) {
        $this->db->select('v.*, u.email, u.prenom, u.nom')
                 ->from('vendeurs v')
                 ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
                 ->where('v.id_utilisateur', $id_utilisateur);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer tous les vendeurs
     */
    public function get_all_vendeurs($limit = null, $offset = null, $filters = []) {
        $this->db->select('v.*, u.email, u.prenom, u.nom')
                 ->from('vendeurs v')
                 ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur');
        
        if (!empty($filters['statut'])) {
            $this->db->where('v.statut', $filters['statut']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('v.nom_boutique', $filters['search']);
            $this->db->or_like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('v.date_creation', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Compter les vendeurs
     */
    public function count_all_vendeurs($filters = []) {
        $this->db->from('vendeurs v');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur');
        
        if (!empty($filters['statut'])) {
            $this->db->where('v.statut', $filters['statut']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('v.nom_boutique', $filters['search']);
            $this->db->or_like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Mettre à jour le statut d'un vendeur
     */
    public function update_statut($id_vendeur, $statut) {
        $this->db->where('id_vendeur', $id_vendeur);
        $this->db->update('vendeurs', ['statut' => $statut]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Approuver un vendeur
     */
    public function approuver($id_vendeur, $approuve_par = null) {
        $data = [
            'est_approuve' => 1,
            'date_approbation' => date('Y-m-d H:i:s'),
            'approuve_par' => $approuve_par,
            'statut' => 'actif'
        ];
        
        $this->db->where('id_vendeur', $id_vendeur);
        $this->db->update('vendeurs', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques des vendeurs
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN statut = "actif" THEN 1 ELSE 0 END) as actifs,
            SUM(CASE WHEN statut = "en_attente" THEN 1 ELSE 0 END) as en_attente,
            SUM(CASE WHEN statut = "suspendu" THEN 1 ELSE 0 END) as suspendus,
            SUM(CASE WHEN est_approuve = 1 THEN 1 ELSE 0 END) as approuves
        ');
        
        return $this->db->get('vendeurs')->row();
    }
}
?>