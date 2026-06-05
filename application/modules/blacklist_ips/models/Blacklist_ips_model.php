<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blacklist_ips_model extends CI_Model {

    /**
     * Liste
     */
    public function get_all() {

        $this->db->order_by(
            'date_creation',
            'DESC'
        );

        return $this->db
            ->get('blacklist_ips')
            ->result();
    }

    /**
     * Par ID
     */
    public function get_by_id($id) {

        return $this->db
            ->where(
                'id_blacklist',
                $id
            )
            ->get('blacklist_ips')
            ->row();
    }

    /**
     * Ajouter
     */
    public function insert($data) {

        $this->db->insert(
            'blacklist_ips',
            $data
        );

        return $this->db->insert_id();
    }

    /**
     * Modifier
     */
    public function update($id,$data) {

        $this->db->where(
            'id_blacklist',
            $id
        );

        $this->db->update(
            'blacklist_ips',
            $data
        );

        return true;
    }

    /**
     * Supprimer
     */
    public function delete($id) {

        $this->db->where(
            'id_blacklist',
            $id
        );

        $this->db->delete(
            'blacklist_ips'
        );

        return true;
    }

    /**
     * Vérifier IP bloquée
     */
    public function is_blocked($ip) {

        $this->db->where(
            'adresse_ip',
            $ip
        );

        $this->db->group_start();

        $this->db->where(
            'date_fin IS NULL',
            null,
            false
        );

        $this->db->or_where(
            'date_fin >=',
            date('Y-m-d H:i:s')
        );

        $this->db->group_end();

        return $this->db
                    ->get('blacklist_ips')
                    ->num_rows() > 0;
    }

}