<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendeur_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer tous les vendeurs
     */
    public function get_all() {

        $this->db->select('*');

        $this->db->from('vendeurs');

        $this->db->order_by(
            'id_vendeur',
            'DESC'
        );

        return $this->db
                    ->get()
                    ->result();
    }

    /**
     * Récupérer vendeur par ID
     */
    public function get_by_id($id) {

        return $this->db
            ->where(
                'id_vendeur',
                $id
            )
            ->get(
                'vendeurs'
            )
            ->row();
    }

    /**
     * Insérer vendeur
     */
    public function insert($data) {

        $this->db->insert(
            'vendeurs',
            $data
        );

        return $this->db->insert_id();
    }

    /**
     * Mettre à jour vendeur
     */
    public function update($id,$data) {

        $this->db->where(
            'id_vendeur',
            $id
        );

        return $this->db->update(
            'vendeurs',
            $data
        );
    }

    /**
     * Supprimer vendeur
     */
    public function delete($id) {

        return $this->db
            ->where(
                'id_vendeur',
                $id
            )
            ->delete(
                'vendeurs'
            );
    }

    /**
     * Vérifier si vendeur existe
     */
    public function exists($id) {

        return $this->db
            ->where(
                'id_vendeur',
                $id
            )
            ->count_all_results(
                'vendeurs'
            ) > 0;
    }

}