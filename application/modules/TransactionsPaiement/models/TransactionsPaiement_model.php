<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TransactionsPaiement_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->helper('string');
    }

    /**
     * Générer une référence unique
     */
    public function generer_reference() {
        return 'TXN_' . date('Ymd') . '_' . strtoupper(random_string('alnum', 8));
    }

    /**
     * Compter toutes les transactions
     */
    public function count_all($filters = []) {
        $this->db->from('transactions_paiement t');
        $this->db->join('utilisateurs u', 'u.id_utilisateur = t.id_utilisateur');
        $this->db->join('commandes c', 'c.id_commande = t.id_commande', 'left');
        
        if (!empty($filters['statut'])) {
            $this->db->where('t.statut', $filters['statut']);
        }
        if (!empty($filters['type_transaction'])) {
            $this->db->where('t.type_transaction', $filters['type_transaction']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(t.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(t.date_creation) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('t.reference_interne', $filters['search']);
            $this->db->or_like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer toutes les transactions
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('t.*, u.prenom, u.nom, u.email, c.numero_commande, mp.description as mode_paiement, CONCAT(u.prenom, " ", u.nom) as client_nom')
                 ->from('transactions_paiement t')
                 ->join('utilisateurs u', 'u.id_utilisateur = t.id_utilisateur')
                 ->join('commandes c', 'c.id_commande = t.id_commande', 'left')
                 ->join('mode_payement mp', 'mp.id_mode_payement = t.id_mode_payement', 'left')
                 ->order_by('t.date_creation', 'DESC');
        
        if (!empty($filters['statut'])) {
            $this->db->where('t.statut', $filters['statut']);
        }
        if (!empty($filters['type_transaction'])) {
            $this->db->where('t.type_transaction', $filters['type_transaction']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(t.date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(t.date_creation) <=', $filters['date_fin']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('t.reference_interne', $filters['search']);
            $this->db->or_like('u.email', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer une transaction par ID
     */
    public function get_by_id($id) {
        $this->db->select('t.*, u.prenom, u.nom, u.email, c.numero_commande, mp.description as mode_paiement')
                 ->from('transactions_paiement t')
                 ->join('utilisateurs u', 'u.id_utilisateur = t.id_utilisateur')
                 ->join('commandes c', 'c.id_commande = t.id_commande', 'left')
                 ->join('mode_payement mp', 'mp.id_mode_payement = t.id_mode_payement', 'left')
                 ->where('t.id_transaction', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer une transaction par référence
     */
    public function get_by_reference($reference) {
        return $this->db->where('reference_interne', $reference)->get('transactions_paiement')->row();
    }

    /**
     * Créer une transaction
     */
    public function creer($data) {
        if (empty($data['reference_interne'])) {
            $data['reference_interne'] = $this->generer_reference();
        }
        
        $this->db->insert('transactions_paiement', $data);
        return $this->db->insert_id();
    }

    /**
     * Mettre à jour une transaction
     */
    public function update($id, $data) {
        $this->db->where('id_transaction', $id);
        $this->db->update('transactions_paiement', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer une transaction
     */
    public function delete($id) {
        $this->db->where('id_transaction', $id);
        $this->db->delete('transactions_paiement');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques globales
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN statut = "confirme" THEN 1 ELSE 0 END) as confirmees,
            SUM(CASE WHEN statut = "en_attente" THEN 1 ELSE 0 END) as en_attente,
            SUM(CASE WHEN statut = "echoue" THEN 1 ELSE 0 END) as echouees,
            SUM(CASE WHEN statut = "annule" THEN 1 ELSE 0 END) as annulees,
            SUM(CASE WHEN type_transaction = "paiement" THEN montant_net ELSE 0 END) as total_paiements,
            SUM(CASE WHEN type_transaction = "remboursement" THEN montant_net ELSE 0 END) as total_remboursements,
            SUM(CASE WHEN type_transaction = "virement_vendeur" THEN montant_net ELSE 0 END) as total_virements,
            SUM(montant_net) as total_net
        ');
        
        if (!empty($filters['date_debut'])) {
            $this->db->where('DATE(date_creation) >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('DATE(date_creation) <=', $filters['date_fin']);
        }
        
        $result = $this->db->get('transactions_paiement')->row();
        
        if (!$result->total) $result->total = 0;
        if (!$result->confirmees) $result->confirmees = 0;
        if (!$result->en_attente) $result->en_attente = 0;
        if (!$result->echouees) $result->echouees = 0;
        if (!$result->annulees) $result->annulees = 0;
        if (!$result->total_paiements) $result->total_paiements = 0;
        if (!$result->total_remboursements) $result->total_remboursements = 0;
        if (!$result->total_virements) $result->total_virements = 0;
        if (!$result->total_net) $result->total_net = 0;
        
        return $result;
    }

    /**
     * Statistiques mensuelles
     */
    public function get_stats_mensuelles($year = null) {
        if (!$year) $year = date('Y');
        
        $this->db->select('
            MONTH(date_creation) as mois,
            COUNT(*) as total,
            SUM(montant_net) as montant_total,
            SUM(CASE WHEN type_transaction = "paiement" THEN montant_net ELSE 0 END) as paiements,
            SUM(CASE WHEN type_transaction = "remboursement" THEN montant_net ELSE 0 END) as remboursements
        ');
        $this->db->where('YEAR(date_creation)', $year);
        $this->db->where('statut', 'confirme');
        $this->db->group_by('MONTH(date_creation)');
        $this->db->order_by('mois', 'ASC');
        
        return $this->db->get('transactions_paiement')->result();
    }

    /**
     * Statistiques par mode de paiement
     */
    public function get_stats_par_mode() {
        $this->db->select('
            mp.description as mode_paiement,
            COUNT(*) as total,
            SUM(montant_net) as montant_total
        ');
        $this->db->from('transactions_paiement t');
        $this->db->join('mode_payement mp', 'mp.id_mode_payement = t.id_mode_payement');
        $this->db->where('t.statut', 'confirme');
        $this->db->group_by('t.id_mode_payement');
        $this->db->order_by('montant_total', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Statistiques par type de transaction
     */
    public function get_stats_par_type() {
        $this->db->select('
            type_transaction,
            COUNT(*) as total,
            SUM(montant_net) as montant_total,
            AVG(montant_net) as montant_moyen
        ');
        $this->db->where('statut', 'confirme');
        $this->db->group_by('type_transaction');
        
        return $this->db->get('transactions_paiement')->result();
    }
}
?>