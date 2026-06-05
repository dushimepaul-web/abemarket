<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HistoriqueStatutCommande_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer l'historique d'une commande
     */
    public function get_by_commande($id_commande) {
        $this->db->select('h.*, u.prenom, u.nom, u.email')
                 ->from('historique_statut_commande h')
                 ->join('utilisateurs u', 'u.id_utilisateur = h.modifie_par', 'left')
                 ->where('h.id_commande', $id_commande)
                 ->order_by('h.date_creation', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer le dernier statut d'une commande
     */
    public function get_last_statut($id_commande) {
        $this->db->where('id_commande', $id_commande)
                 ->order_by('date_creation', 'DESC')
                 ->limit(1);
        
        return $this->db->get('historique_statut_commande')->row();
    }

    /**
     * Ajouter un historique
     */
    public function add($data) {
        $this->db->insert('historique_statut_commande', $data);
        return $this->db->insert_id();
    }

    /**
     * Supprimer un historique
     */
    public function delete($id) {
        $this->db->where('id_historique', $id);
        $this->db->delete('historique_statut_commande');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Compter pour une commande
     */
    public function count_by_commande($id_commande) {
        return $this->db->where('id_commande', $id_commande)
                        ->count_all_results('historique_statut_commande');
    }
}
?>