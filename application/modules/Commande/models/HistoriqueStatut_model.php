<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HistoriqueStatut_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Ajouter une entrée dans l'historique
     */
    public function ajouter_historique($data) {
        $this->db->insert('historique_statut_commande', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Récupérer l'historique d'une commande
     */
    public function get_historique_by_commande($id_commande) {
        $this->db->select('h.*, u.prenom, u.nom')
                 ->from('historique_statut_commande h')
                 ->join('utilisateurs u', 'h.modifie_par = u.id_utilisateur', 'left')
                 ->where('h.id_commande', $id_commande)
                 ->order_by('h.date_creation', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Récupérer le dernier statut d'une commande
     */
    public function get_dernier_statut($id_commande) {
        $this->db->where('id_commande', $id_commande)
                 ->order_by('date_creation', 'DESC')
                 ->limit(1);
        
        $query = $this->db->get('historique_statut_commande');
        return $query->row();
    }
}
?>