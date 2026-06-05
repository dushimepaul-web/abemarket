<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utilisateurs_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * LISTE DES UTILISATEURS
     */
    public function get_all()
    {
        return $this->db
            ->select('*')
            ->from('utilisateurs')
            ->order_by('id_utilisateur','DESC')
            ->get()
            ->result();
    }

    /**
     * UTILISATEUR PAR ID
     */
    public function get_by_id($id)
    {
        return $this->db
            ->where('id_utilisateur', $id)
            ->get('utilisateurs')
            ->row();
    }

    /**
     * AJOUTER UTILISATEUR
     */
    public function insert($data)
    {
        $this->db->insert('utilisateurs', $data);
        return $this->db->insert_id();
    }

    /**
     * MODIFIER UTILISATEUR
     */
    public function update($id, $data)
    {
        return $this->db
            ->where('id_utilisateur', $id)
            ->update('utilisateurs', $data);
    }

    /**
     * SUPPRIMER
     */
    public function delete($id)
    {
        return $this->db
            ->where('id_utilisateur', $id)
            ->delete('utilisateurs');
    }

}