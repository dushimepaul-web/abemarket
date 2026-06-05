<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commande_model extends CI_Model {

    public function get_all()
    {
        return $this->db
            ->order_by('id_commande','DESC')
            ->get('commandes')
            ->result();
    }

    /**
 * Récupérer une commande par ID
 */
public function get_by_id($id) {
    $this->db->select('c.*, u.prenom, u.nom, u.email, u.telephone, mp.description as mode_paiement')
             ->from('commandes c')
             ->join('utilisateurs u', 'u.id_utilisateur = c.id_utilisateur')
             ->join('mode_payement mp', 'mp.id_mode_payement = c.id_mode_payement', 'left')
             ->where('c.id_commande', $id);
    
    return $this->db->get()->row();
}

}
