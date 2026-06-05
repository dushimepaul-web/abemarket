<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commande_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer une commande par son ID
     */
    public function get_by_id($id_commande) {
        $this->db->select('c.*, u.prenom, u.nom, u.email, u.telephone')
                 ->from('commandes c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->where('c.id_commande', $id_commande);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer une commande par son numéro
     */
    public function get_by_numero($numero_commande) {
        $this->db->select('c.*, u.prenom, u.nom, u.email, u.telephone')
                 ->from('commandes c')
                 ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
                 ->where('c.numero_commande', $numero_commande);
        
        return $this->db->get()->row();
    }

    /**
     * Vérifier si une commande appartient à un utilisateur
     */
    public function belongs_to_user($id_commande, $id_utilisateur) {
        $this->db->where('id_commande', $id_commande)
                 ->where('id_utilisateur', $id_utilisateur);
        return $this->db->count_all_results('commandes') > 0;
    }
}
?>