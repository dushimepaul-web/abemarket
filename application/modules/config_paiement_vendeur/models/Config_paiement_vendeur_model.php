<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config_paiement_vendeur_model extends CI_Model {

    /**
     * GET ALL
     */
    public function get_all() {

        $this->db->select('c.*, v.nom_boutique');

        $this->db->from(
            'config_paiement_vendeur c'
        );

        $this->db->join(
            'vendeurs v',
            'v.id_vendeur = c.id_vendeur',
            'left'
        );

        return $this->db
                    ->order_by(
                        'c.id_config',
                        'DESC'
                    )
                    ->get()
                    ->result();
    }

    /**
     * GET BY ID
     */
    public function get_by_id($id) {

        return $this->db
            ->where(
                'id_config',
                $id
            )
            ->get(
                'config_paiement_vendeur'
            )
            ->row();
    }

    /**
     * INSERT
     */
    public function insert($data) {

        $this->db->insert(
            'config_paiement_vendeur',
            $data
        );

        return $this->db->insert_id();
    }

    /**
     * UPDATE
     */
    public function update($id,$data) {

        $this->db->where(
            'id_config',
            $id
        );

        return $this->db->update(
            'config_paiement_vendeur',
            $data
        );
    }

    /**
     * DELETE
     */
    public function delete($id) {

        return $this->db
            ->where(
                'id_config',
                $id
            )
            ->delete(
                'config_paiement_vendeur'
            );
    }

    /**
     * ACTIVER / DESACTIVER
     */
    public function toggle_status($id) {

        $config =
            $this->get_by_id($id);

        if (!$config) return false;

        $new_status =
            $config->est_actif ? 0 : 1;

        return $this->db
            ->where(
                'id_config',
                $id
            )
            ->update(
                'config_paiement_vendeur',
                [
                    'est_actif' =>
                        $new_status
                ]
            );
    }

    /**
     * VERIFIER CONFIG
     */
    public function verifier($id) {

        return $this->db
            ->where(
                'id_config',
                $id
            )
            ->update(
                'config_paiement_vendeur',
                [
                    'est_verifie' => 1
                ]
            );
    }

}