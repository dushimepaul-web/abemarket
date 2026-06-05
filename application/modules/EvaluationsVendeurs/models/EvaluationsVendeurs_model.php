<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EvaluationsVendeurs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer toutes les évaluations
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('e.*, 
                          v.nom_boutique as vendeur_nom,
                          CONCAT(u.prenom, " ", u.nom) as client_nom,
                          c.numero_commande as commande_numero')
                 ->from('evaluations_vendeurs e')
                 ->join('vendeurs v', 'v.id_vendeur = e.id_vendeur')
                 ->join('utilisateurs u', 'u.id_utilisateur = e.id_utilisateur')
                 ->join('commandes c', 'c.id_commande = e.id_commande')
                 ->order_by('e.date_creation', 'DESC');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('e.id_vendeur', $filters['id_vendeur']);
        }
        if (isset($filters['est_approuve']) && $filters['est_approuve'] !== '') {
            $this->db->where('e.est_approuve', $filters['est_approuve']);
        }
        if (!empty($filters['note_min'])) {
            $this->db->where('e.note_globale >=', $filters['note_min']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(e.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(e.date_creation) <=', $filters['date_fin']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Compter les évaluations
     */
    public function count_all($filters = []) {
        $this->db->from('evaluations_vendeurs e');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('e.id_vendeur', $filters['id_vendeur']);
        }
        if (isset($filters['est_approuve']) && $filters['est_approuve'] !== '') {
            $this->db->where('e.est_approuve', $filters['est_approuve']);
        }
        if (!empty($filters['note_min'])) {
            $this->db->where('e.note_globale >=', $filters['note_min']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(e.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(e.date_creation) <=', $filters['date_fin']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer une évaluation par ID
     */
    public function get_by_id($id_evaluation) {
        $this->db->where('id_evaluation', $id_evaluation);
        return $this->db->get('evaluations_vendeurs')->row();
    }

    /**
     * Récupérer une évaluation avec détails
     */
    public function get_by_id_with_details($id_evaluation) {
        $this->db->select('e.*, 
                          v.nom_boutique as vendeur_nom,
                          v.slug_boutique,
                          v.logo_boutique,
                          CONCAT(u.prenom, " ", u.nom) as client_nom,
                          u.email as client_email,
                          u.telephone as client_telephone,
                          c.numero_commande as commande_numero,
                          c.montant_total as commande_montant')
                 ->from('evaluations_vendeurs e')
                 ->join('vendeurs v', 'v.id_vendeur = e.id_vendeur')
                 ->join('utilisateurs u', 'u.id_utilisateur = e.id_utilisateur')
                 ->join('commandes c', 'c.id_commande = e.id_commande')
                 ->where('e.id_evaluation', $id_evaluation);
        
        return $this->db->get()->row();
    }

    /**
     * Ajouter une évaluation
     */
    public function ajouter($data) {
        $this->db->insert('evaluations_vendeurs', $data);
        return $this->db->insert_id();
    }

    /**
     * Approuver une évaluation
     */
    public function approuver($id_evaluation) {
        $this->db->where('id_evaluation', $id_evaluation);
        return $this->db->update('evaluations_vendeurs', ['est_approuve' => 1]);
    }

    /**
     * Supprimer une évaluation
     */
    public function supprimer($id_evaluation) {
        $this->db->where('id_evaluation', $id_evaluation);
        return $this->db->delete('evaluations_vendeurs');
    }

    /**
     * Vérifier si déjà évalué
     */
    public function a_deja_evalue($id_vendeur, $id_commande, $id_utilisateur) {
        $this->db->where('id_vendeur', $id_vendeur)
                 ->where('id_commande', $id_commande)
                 ->where('id_utilisateur', $id_utilisateur);
        return $this->db->count_all_results('evaluations_vendeurs') > 0;
    }

    /**
     * Statistiques générales
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(e.id_evaluation) as total_evaluations,
            AVG(e.note_globale) as note_moyenne,
            AVG(e.note_communication) as note_communication_moyenne,
            AVG(e.note_livraison) as note_livraison_moyenne,
            SUM(CASE WHEN e.est_approuve = 1 THEN 1 ELSE 0 END) as approuvees,
            SUM(CASE WHEN e.est_approuve = 0 THEN 1 ELSE 0 END) as en_attente,
            COUNT(DISTINCT e.id_vendeur) as vendeurs_evalues
        ');
        $this->db->from('evaluations_vendeurs e');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('e.id_vendeur', $filters['id_vendeur']);
        }
        if (isset($filters['est_approuve']) && $filters['est_approuve'] !== '') {
            $this->db->where('e.est_approuve', $filters['est_approuve']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(e.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(e.date_creation) <=', $filters['date_fin']);
        }
        
        $result = $this->db->get()->row();
        
        if (!$result->total_evaluations) $result->total_evaluations = 0;
        if (!$result->note_moyenne) $result->note_moyenne = 0;
        if (!$result->note_communication_moyenne) $result->note_communication_moyenne = 0;
        if (!$result->note_livraison_moyenne) $result->note_livraison_moyenne = 0;
        if (!$result->approuvees) $result->approuvees = 0;
        if (!$result->en_attente) $result->en_attente = 0;
        if (!$result->vendeurs_evalues) $result->vendeurs_evalues = 0;
        
        return $result;
    }

    /**
     * Statistiques par vendeur
     */
    public function get_vendeur_stats($id_vendeur) {
        $this->db->select('
            COUNT(id_evaluation) as total_evaluations,
            AVG(note_globale) as note_moyenne,
            AVG(note_communication) as note_communication_moyenne,
            AVG(note_livraison) as note_livraison_moyenne
        ');
        $this->db->from('evaluations_vendeurs')
                 ->where('id_vendeur', $id_vendeur)
                 ->where('est_approuve', 1);
        
        $result = $this->db->get()->row();
        
        if (!$result->total_evaluations) $result->total_evaluations = 0;
        if (!$result->note_moyenne) $result->note_moyenne = 0;
        if (!$result->note_communication_moyenne) $result->note_communication_moyenne = 0;
        if (!$result->note_livraison_moyenne) $result->note_livraison_moyenne = 0;
        
        return $result;
    }

    /**
     * Répartition des notes
     */
    public function get_note_repartition($id_vendeur = null) {
        $this->db->select('note_globale, COUNT(*) as total')
                 ->from('evaluations_vendeurs')
                 ->where('est_approuve', 1)
                 ->group_by('note_globale');
        
        if ($id_vendeur) {
            $this->db->where('id_vendeur', $id_vendeur);
        }
        
        $results = $this->db->get()->result();
        
        $repartition = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($results as $r) {
            $repartition[$r->note_globale] = $r->total;
        }
        
        return $repartition;
    }
}
?>