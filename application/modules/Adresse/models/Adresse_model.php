<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adresse_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer les adresses d'un utilisateur avec les noms des provinces/communes/quartiers
     */
    public function get_adresses_by_user($id_utilisateur, $limit = null, $offset = null) {
        $this->db->select('a.*, p.province_name, c.commune_name, q.quartier_name')
                 ->from('adresses a')
                 ->join('provinces p', 'a.id_province = p.id_province', 'left')
                 ->join('communes c', 'a.id_commune = c.id_commune', 'left')
                 ->join('quartiers q', 'a.id_quartier = q.id_quartier', 'left')
                 ->where('a.id_utilisateur', $id_utilisateur)
                 ->where('a.est_actif', 1)
                 ->order_by('a.est_par_defaut DESC, a.date_creation DESC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Compter le nombre d'adresses d'un utilisateur
     */
    public function count_adresses_by_user($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur)
                 ->where('est_actif', 1)
                 ->from('adresses');
        return $this->db->count_all_results();
    }

    /**
     * Récupérer une adresse par son ID
     */
    public function get_adresse_by_id($id_adresse, $id_utilisateur = null) {
        $this->db->select('a.*, p.province_name, c.commune_name, q.quartier_name')
                 ->from('adresses a')
                 ->join('provinces p', 'a.id_province = p.id_province', 'left')
                 ->join('communes c', 'a.id_commune = c.id_commune', 'left')
                 ->join('quartiers q', 'a.id_quartier = q.id_quartier', 'left')
                 ->where('a.id_adresse', $id_adresse);

        if ($id_utilisateur !== null) {
            $this->db->where('a.id_utilisateur', $id_utilisateur);
        }

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Récupérer plusieurs adresses par leurs IDs
     */
    public function get_adresses_by_ids($ids, $id_utilisateur) {
        if (empty($ids)) {
            return [];
        }

        $this->db->select('a.*, p.province_name, c.commune_name, q.quartier_name')
                 ->from('adresses a')
                 ->join('provinces p', 'a.id_province = p.id_province', 'left')
                 ->join('communes c', 'a.id_commune = c.id_commune', 'left')
                 ->join('quartiers q', 'a.id_quartier = q.id_quartier', 'left')
                 ->where_in('a.id_adresse', $ids)
                 ->where('a.id_utilisateur', $id_utilisateur)
                 ->where('a.est_actif', 1);

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Ajouter une nouvelle adresse
     */
    public function ajouter_adresse($data) {
        $this->db->insert('adresses', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier une adresse
     */
    public function modifier_adresse($id_adresse, $data, $id_utilisateur) {
        $this->db->where('id_adresse', $id_adresse)
                 ->where('id_utilisateur', $id_utilisateur)
                 ->update('adresses', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer une adresse (soft delete)
     */
    public function supprimer_adresse($id_adresse, $id_utilisateur) {
        $this->db->where('id_adresse', $id_adresse)
                 ->where('id_utilisateur', $id_utilisateur)
                 ->update('adresses', ['est_actif' => 0]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Définir une adresse comme par défaut
     */
    public function set_adresse_par_defaut($id_adresse, $id_utilisateur) {
        $this->db->where('id_adresse', $id_adresse)
                 ->where('id_utilisateur', $id_utilisateur)
                 ->update('adresses', ['est_par_defaut' => 1]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Retirer le flag "par défaut" de toutes les adresses d'un utilisateur
     */
    public function remove_default_flag($id_utilisateur, $exclude_id = null) {
        $this->db->where('id_utilisateur', $id_utilisateur);
        if ($exclude_id !== null) {
            $this->db->where('id_adresse !=', $exclude_id);
        }
        $this->db->update('adresses', ['est_par_defaut' => 0]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Obtenir l'adresse par défaut d'un utilisateur
     */
    public function get_default_adresse($id_utilisateur) {
        $this->db->where('id_utilisateur', $id_utilisateur)
                 ->where('est_par_defaut', 1)
                 ->where('est_actif', 1);
        $query = $this->db->get('adresses');
        return $query->row();
    }
}
?>