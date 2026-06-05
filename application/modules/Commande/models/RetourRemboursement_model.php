<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RetourRemboursement_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupérer toutes les demandes de retour/remboursement
     */
    public function get_all_retours($limit = null, $offset = null, $filters = []) {
        $this->db->select('r.*, 
                          c.numero_commande,
                          u.prenom, u.nom, u.email,
                          ac.nom_produit, ac.quantite, ac.prix_unitaire,
                          t.prenom as traiteur_prenom, t.nom as traiteur_nom')
                 ->from('retours_remboursements r')
                 ->join('commandes c', 'r.id_commande = c.id_commande')
                 ->join('utilisateurs u', 'r.id_utilisateur = u.id_utilisateur', 'left')
                 ->join('articles_commande ac', 'r.id_article = ac.id_article', 'left')
                 ->join('utilisateurs t', 'r.traite_par = t.id_utilisateur', 'left')
                 ->order_by('r.date_creation', 'DESC');
        
        if (!empty($filters['statut'])) {
            $this->db->where('r.statut', $filters['statut']);
        }
        if (!empty($filters['type'])) {
            $this->db->where('r.type', $filters['type']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Récupérer une demande de retour par son ID
     */
    public function get_retour_by_id($id_retour) {
        $this->db->select('r.*, 
                          c.numero_commande, c.montant_total,
                          u.prenom, u.nom, u.email, u.telephone,
                          ac.nom_produit, ac.quantite, ac.prix_unitaire, ac.id_vendeur,
                          vd.nom_boutique,
                          t.prenom as traiteur_prenom, t.nom as traiteur_nom')
                 ->from('retours_remboursements r')
                 ->join('commandes c', 'r.id_commande = c.id_commande')
                 ->join('utilisateurs u', 'r.id_utilisateur = u.id_utilisateur', 'left')
                 ->join('articles_commande ac', 'r.id_article = ac.id_article', 'left')
                 ->join('vendeurs vd', 'ac.id_vendeur = vd.id_vendeur', 'left')
                 ->join('utilisateurs t', 'r.traite_par = t.id_utilisateur', 'left')
                 ->where('r.id_retour', $id_retour);
        
        $query = $this->db->get();
        return $query->row();
    }
    
    /**
     * Créer une demande de retour/remboursement
     */
    public function creer_retour($data) {
        $this->db->insert('retours_remboursements', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Approuver une demande de retour/remboursement
     */
    public function approuver_retour($id_retour, $montant_approuve, $traite_par, $commentaire = null) {
        $this->db->where('id_retour', $id_retour);
        $this->db->update('retours_remboursements', [
            'montant_approuve' => $montant_approuve,
            'statut' => 'approuve',
            'traite_par' => $traite_par,
            'date_traitement' => date('Y-m-d H:i:s'),
            'motif_refus' => $commentaire
        ]);
        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Refuser une demande de retour/remboursement
     */
    public function refuser_retour($id_retour, $motif_refus, $traite_par) {
        $this->db->where('id_retour', $id_retour);
        $this->db->update('retours_remboursements', [
            'statut' => 'refuse',
            'traite_par' => $traite_par,
            'date_traitement' => date('Y-m-d H:i:s'),
            'motif_refus' => $motif_refus
        ]);
        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Marquer comme remboursé
     */
    public function marquer_rembourse($id_retour, $reference_transaction = null) {
        $this->db->where('id_retour', $id_retour);
        $this->db->update('retours_remboursements', [
            'statut' => 'rembourse',
            'date_remboursement' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Obtenir les statistiques des retours
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN statut = "demande" THEN 1 ELSE 0 END) as demandes,
            SUM(CASE WHEN statut = "en_cours" THEN 1 ELSE 0 END) as en_cours,
            SUM(CASE WHEN statut = "approuve" THEN 1 ELSE 0 END) as approuves,
            SUM(CASE WHEN statut = "refuse" THEN 1 ELSE 0 END) as refuses,
            SUM(CASE WHEN statut = "rembourse" THEN 1 ELSE 0 END) as rembourses,
            SUM(montant_demande) as total_demande,
            SUM(montant_approuve) as total_approuve
        ');
        
        $query = $this->db->get('retours_remboursements');
        return $query->row();
    }
    
    /**
     * Obtenir les motifs de retour disponibles
     */
    public function get_motifs_options() {
        return [
            'produit_defectueux' => ['label' => 'Produit defectueux', 'icon' => 'alert-circle'],
            'non_conforme' => ['label' => 'Non conforme à la description', 'icon' => 'close-circle'],
            'erreur_livraison' => ['label' => 'Erreur de livraison', 'icon' => 'truck'],
            'changement_avis' => ['label' => 'Changement d\'avis', 'icon' => 'refresh'],
            'produit_endommage' => ['label' => 'Produit endommagé pendant le transport', 'icon' => 'package'],
            'autre' => ['label' => 'Autre motif', 'icon' => 'help-circle']
        ];
    }
}
?>