<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LitigeCommande_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Récupérer tous les litiges (admin)
     */
    public function get_all_litiges($limit = null, $offset = null, $filters = []) {
        $this->db->select('l.*, 
                          c.numero_commande, c.montant_total,
                          u_prenom.prenom as plaignant_prenom, u_prenom.nom as plaignant_nom, u_prenom.email as plaignant_email,
                          u_def.prenom as defendeur_prenom, u_def.nom as defendeur_nom,
                          vd.nom_boutique as vendeur_nom,
                          med.prenom as med_prenom, med.nom as med_nom')
                 ->from('litiges_commandes l')
                 ->join('commandes c', 'l.id_commande = c.id_commande')
                 ->join('utilisateurs u_prenom', 'l.id_plaignant = u_prenom.id_utilisateur', 'left')
                 ->join('utilisateurs u_def', 'l.id_defendeur = u_def.id_utilisateur', 'left')
                 ->join('vendeurs vd', 'l.id_defendeur = vd.id_utilisateur', 'left')
                 ->join('utilisateurs med', 'l.mediateur_id = med.id_utilisateur', 'left')
                 ->order_by('l.date_creation', 'DESC');
        
        // Appliquer les filtres
        if (!empty($filters['statut'])) {
            $this->db->where('l.statut', $filters['statut']);
        }
        if (!empty($filters['type_plaignant'])) {
            $this->db->where('l.type_plaignant', $filters['type_plaignant']);
        }
        if (!empty($filters['raison'])) {
            $this->db->where('l.raison', $filters['raison']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('c.numero_commande', $filters['search']);
            $this->db->or_like('u_prenom.prenom', $filters['search']);
            $this->db->or_like('u_prenom.nom', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Récupérer les litiges d'un utilisateur
     */
    public function get_litiges_by_user($id_utilisateur, $limit = null, $offset = null) {
        $this->db->select('l.*, c.numero_commande, c.montant_total,
                          u_def.prenom as defendeur_prenom, u_def.nom as defendeur_nom,
                          med.prenom as med_prenom, med.nom as med_nom')
                 ->from('litiges_commandes l')
                 ->join('commandes c', 'l.id_commande = c.id_commande')
                 ->join('utilisateurs u_def', 'l.id_defendeur = u_def.id_utilisateur', 'left')
                 ->join('utilisateurs med', 'l.mediateur_id = med.id_utilisateur', 'left')
                 ->where('l.id_plaignant', $id_utilisateur)
                 ->order_by('l.date_creation', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Récupérer un litige par son ID
     */
    public function get_litige_by_id($id_litige) {
        $this->db->select('l.*, 
                          c.numero_commande, c.montant_total, c.date_creation as commande_date,
                          u_prenom.prenom as plaignant_prenom, u_prenom.nom as plaignant_nom, u_prenom.email as plaignant_email, u_prenom.telephone as plaignant_telephone,
                          u_def.prenom as defendeur_prenom, u_def.nom as defendeur_nom, u_def.email as defendeur_email, u_def.telephone as defendeur_telephone,
                          vd.nom_boutique, vd.id_vendeur,
                          med.prenom as med_prenom, med.nom as med_nom, med.email as med_email')
                 ->from('litiges_commandes l')
                 ->join('commandes c', 'l.id_commande = c.id_commande')
                 ->join('utilisateurs u_prenom', 'l.id_plaignant = u_prenom.id_utilisateur', 'left')
                 ->join('utilisateurs u_def', 'l.id_defendeur = u_def.id_utilisateur', 'left')
                 ->join('vendeurs vd', 'l.id_defendeur = vd.id_utilisateur', 'left')
                 ->join('utilisateurs med', 'l.mediateur_id = med.id_utilisateur', 'left')
                 ->where('l.id_litige', $id_litige);
        
        $query = $this->db->get();
        return $query->row();
    }
    
    /**
     * Créer un nouveau litige
     */
    public function creer_litige($data) {
        $this->db->insert('litiges_commandes', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Mettre à jour un litige
     */
    public function update_litige($id_litige, $data) {
        $this->db->where('id_litige', $id_litige);
        $this->db->update('litiges_commandes', $data);
        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Changer le statut d'un litige
     */
    public function update_statut($id_litige, $statut, $decision = null, $montant_rembourse = null) {
        $data = ['statut' => $statut];
        if ($decision !== null) {
            $data['decision'] = $decision;
        }
        if ($montant_rembourse !== null) {
            $data['montant_rembourse'] = $montant_rembourse;
        }
        if ($statut == 'resolu_acheteur' || $statut == 'resolu_vendeur' || $statut == 'ferme') {
            $data['date_resolution'] = date('Y-m-d H:i:s');
        }
        
        $this->db->where('id_litige', $id_litige);
        $this->db->update('litiges_commandes', $data);
        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Assigner un médiateur
     */
    public function assigner_mediateur($id_litige, $id_mediateur) {
        $this->db->where('id_litige', $id_litige);
        $this->db->update('litiges_commandes', [
            'mediateur_id' => $id_mediateur,
            'statut' => 'en_mediation'
        ]);
        return $this->db->affected_rows() > 0;
    }
    
    /**
     * Compter les litiges par statut
     */
    public function count_litiges_by_statut() {
        $this->db->select('statut, COUNT(*) as total')
                 ->from('litiges_commandes')
                 ->group_by('statut');
        
        $query = $this->db->get();
        $results = $query->result();
        
        $stats = [];
        foreach ($results as $r) {
            $stats[$r->statut] = $r->total;
        }
        
        return $stats;
    }
    
    /**
     * Compter les litiges par raison
     */
    public function count_litiges_by_raison() {
        $this->db->select('raison, COUNT(*) as total')
                 ->from('litiges_commandes')
                 ->group_by('raison');
        
        $query = $this->db->get();
        $results = $query->result();
        
        $stats = [];
        foreach ($results as $r) {
            $stats[$r->raison] = $r->total;
        }
        
        return $stats;
    }
    
    /**
     * Obtenir les raisons de litige disponibles
     */
    public function get_raisons_options() {
        return [
            'non_recu' => ['label' => 'Non reçu', 'icon' => 'box', 'color' => 'danger'],
            'endommage' => ['label' => 'Produit endommagé', 'icon' => 'alert-triangle', 'color' => 'warning'],
            'non_conforme' => ['label' => 'Non conforme', 'icon' => 'close-circle', 'color' => 'danger'],
            'paiement' => ['label' => 'Problème de paiement', 'icon' => 'card', 'color' => 'info'],
            'autre' => ['label' => 'Autre motif', 'icon' => 'help-circle', 'color' => 'secondary']
        ];
    }
    
    /**
     * Obtenir les statuts disponibles
     */
    public function get_statuts_options() {
        return [
            'ouvert' => ['label' => 'Ouvert', 'color' => 'warning', 'icon' => 'flag'],
            'en_mediation' => ['label' => 'En médiation', 'color' => 'info', 'icon' => 'chat'],
            'resolu_acheteur' => ['label' => 'Résolu (Acheteur)', 'color' => 'success', 'icon' => 'check-circle'],
            'resolu_vendeur' => ['label' => 'Résolu (Vendeur)', 'color' => 'success', 'icon' => 'check-circle'],
            'ferme' => ['label' => 'Fermé', 'color' => 'secondary', 'icon' => 'lock']
        ];
    }
    
    /**
     * Exporter les litiges en CSV
     */
    public function exporter_litiges($filters = []) {
        $litiges = $this->get_all_litiges(null, null, $filters);
        
        $data = [];
        $headers = ['ID', 'N° Commande', 'Plaignant', 'Défendeur', 'Raison', 'Statut', 'Montant remboursé', 'Date création', 'Date résolution'];
        $data[] = $headers;
        
        foreach ($litiges as $l) {
            $row = [
                $l->id_litige,
                $l->numero_commande,
                ($l->plaignant_prenom ?? '') . ' ' . ($l->plaignant_nom ?? ''),
                ($l->defendeur_prenom ?? '') . ' ' . ($l->defendeur_nom ?? '') . ($l->vendeur_nom ? " ({$l->vendeur_nom})" : ''),
                $l->raison,
                $l->statut,
                number_format($l->montant_rembourse ?? 0, 2),
                date('d/m/Y H:i', strtotime($l->date_creation)),
                $l->date_resolution ? date('d/m/Y H:i', strtotime($l->date_resolution)) : '-'
            ];
            $data[] = $row;
        }
        
        return $data;
    }
}
?>