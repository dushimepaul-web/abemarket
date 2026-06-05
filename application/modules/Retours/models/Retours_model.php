<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retours_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter toutes les demandes
     */
    public function count_all($filters = []) {
        $this->db->from('retours_remboursements r');
        $this->db->join('commandes c', 'c.id_commande = r.id_commande', 'left');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = r.id_utilisateur', 'left');
        
        if (!empty($filters['statut'])) {
            $this->db->where('r.statut', $filters['statut']);
        }
        if (!empty($filters['motif'])) {
            $this->db->where('r.motif', $filters['motif']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(r.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(r.date_creation) <=', $filters['date_fin']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer toutes les demandes
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('r.*, c.numero_commande, CONCAT(COALESCE(u.prenom, ""), " ", COALESCE(u.nom, "")) as client_nom, u.email')
                 ->from('retours_remboursements r')
                 ->join('commandes c', 'c.id_commande = r.id_commande', 'left')
                 ->join('utilisateurs u', 'u.id_utilisateur = r.id_utilisateur', 'left')
                 ->order_by('r.date_creation', 'DESC');
        
        if (!empty($filters['statut'])) {
            $this->db->where('r.statut', $filters['statut']);
        }
        if (!empty($filters['motif'])) {
            $this->db->where('r.motif', $filters['motif']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(r.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(r.date_creation) <=', $filters['date_fin']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Récupérer les demandes d'un utilisateur
     */
    public function get_by_utilisateur($id_utilisateur) {
        $this->db->select('r.*, c.numero_commande, c.montant_total')
                 ->from('retours_remboursements r')
                 ->join('commandes c', 'c.id_commande = r.id_commande', 'left')
                 ->where('r.id_utilisateur', $id_utilisateur)
                 ->order_by('r.date_creation', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer une demande par ID
     */
    public function get_by_id($id) {
        $this->db->select('r.*, c.numero_commande, c.montant_total, u.prenom, u.nom, u.email')
                 ->from('retours_remboursements r')
                 ->join('commandes c', 'c.id_commande = r.id_commande', 'left')
                 ->join('utilisateurs u', 'u.id_utilisateur = r.id_utilisateur', 'left')
                 ->where('r.id_retour', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer un article par ID
     */
    public function get_article_by_id($id_article) {
        if (!$id_article) return null;
        
        return $this->db->where('id_article', $id_article)->get('articles_commande')->row();
    }

    /**
     * Demander un retour
     */
    public function demander($data) {
        $this->db->insert('retours_remboursements', $data);
        return $this->db->insert_id();
    }

    /**
     * Mettre à jour une demande
     */
    public function update($id, $data) {
        $this->db->where('id_retour', $id);
        $this->db->update('retours_remboursements', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer une demande
     */
    public function delete($id) {
        $this->db->where('id_retour', $id);
        $this->db->delete('retours_remboursements');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques globales
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN statut = "demande" THEN 1 ELSE 0 END) as demandes,
            SUM(CASE WHEN statut = "en_cours" THEN 1 ELSE 0 END) as en_cours,
            SUM(CASE WHEN statut = "approuve" THEN 1 ELSE 0 END) as approuves,
            SUM(CASE WHEN statut = "refuse" THEN 1 ELSE 0 END) as refuses,
            SUM(CASE WHEN statut = "rembourse" THEN 1 ELSE 0 END) as rembourses,
            COALESCE(SUM(montant_demande), 0) as total_demande,
            COALESCE(SUM(montant_approuve), 0) as total_approuve
        ');
        
        $result = $this->db->get('retours_remboursements')->row();
        
        // S'assurer que les valeurs ne sont pas null
        if (!$result->total) $result->total = 0;
        if (!$result->demandes) $result->demandes = 0;
        if (!$result->en_cours) $result->en_cours = 0;
        if (!$result->approuves) $result->approuves = 0;
        if (!$result->refuses) $result->refuses = 0;
        if (!$result->rembourses) $result->rembourses = 0;
        if (!$result->total_demande) $result->total_demande = 0;
        if (!$result->total_approuve) $result->total_approuve = 0;
        
        return $result;
    }

    /**
     * Statistiques par utilisateur
     */
    public function get_stats_by_utilisateur($id_utilisateur) {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN statut = "demande" THEN 1 ELSE 0 END) as demandes,
            SUM(CASE WHEN statut = "approuve" THEN 1 ELSE 0 END) as approuves,
            SUM(CASE WHEN statut = "refuse" THEN 1 ELSE 0 END) as refuses,
            SUM(CASE WHEN statut = "rembourse" THEN 1 ELSE 0 END) as rembourses,
            COALESCE(SUM(montant_demande), 0) as total_demande
        ');
        $this->db->where('id_utilisateur', $id_utilisateur);
        
        $result = $this->db->get('retours_remboursements')->row();
        
        if (!$result->total) $result->total = 0;
        if (!$result->demandes) $result->demandes = 0;
        if (!$result->approuves) $result->approuves = 0;
        if (!$result->refuses) $result->refuses = 0;
        if (!$result->rembourses) $result->rembourses = 0;
        if (!$result->total_demande) $result->total_demande = 0;
        
        return $result;
    }

    /**
     * Ajouter une demande de test (pour debug)
     */
    public function ajouter_test($id_commande, $id_utilisateur, $montant) {
        $data = [
            'id_commande' => $id_commande,
            'id_utilisateur' => $id_utilisateur,
            'type' => 'retour_produit',
            'motif' => 'produit_defectueux',
            'description' => 'Demande de test automatique',
            'montant_demande' => $montant,
            'statut' => 'demande',
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('retours_remboursements', $data);
        return $this->db->insert_id();
    }
}
?>