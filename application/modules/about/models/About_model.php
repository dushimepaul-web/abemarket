<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Récupère tous les contenus "À propos"
     */
    public function getAll() {
        $this->db->order_by('ordre_affichage', 'ASC');
        return $this->db->get('about_content')->result_array();
    }

    /**
     * Récupère un contenu par sa clé (section_key)
     */
    public function getByKey($key) {
        $this->db->where('section_key', $key);
        return $this->db->get('about_content')->row_array();
    }

    /**
     * Récupère un contenu par son ID
     */
    public function getById($id) {
        $this->db->where('id_about', $id);
        return $this->db->get('about_content')->row_array();
    }

    /**
     * Met à jour un contenu
     */
    public function update($id, $data) {
        $data['date_modification'] = date('Y-m-d H:i:s');
        $this->db->where('id_about', $id);
        return $this->db->update('about_content', $data);
    }

    /**
     * Met à jour un contenu par sa clé
     */
    public function updateByKey($key, $data) {
        $data['date_modification'] = date('Y-m-d H:i:s');
        $this->db->where('section_key', $key);
        return $this->db->update('about_content', $data);
    }

    /**
     * Active/Désactive un contenu
     */
    public function toggleStatus($id, $status) {
        $this->db->where('id_about', $id);
        return $this->db->update('about_content', ['est_actif' => $status]);
    }

    /**
     * Met à jour l'ordre d'affichage
     */
    public function updateOrder($id, $ordre) {
        $this->db->where('id_about', $id);
        return $this->db->update('about_content', ['ordre_affichage' => $ordre]);
    }
}