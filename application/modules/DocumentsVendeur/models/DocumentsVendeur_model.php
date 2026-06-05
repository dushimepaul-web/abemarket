<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DocumentsVendeur_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Types de documents disponibles
     */
    public function get_types_document() {
        return [
            'carte_identite' => 'Carte d\'identité nationale',
            'passeport' => 'Passeport',
            'licence_commerce' => 'Licence de commerce',
            'attestation_fiscale' => 'Attestation fiscale',
            'justificatif_domicile' => 'Justificatif de domicile'
        ];
    }

    /**
     * Compter tous les documents
     */
    public function count_all($filters = []) {
        $this->db->from('documents_vendeur d');
        $this->db->join('vendeurs v', 'v.id_vendeur = d.id_vendeur');
        
        if (!empty($filters['statut_verification'])) {
            $this->db->where('d.statut_verification', $filters['statut_verification']);
        }
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('d.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['type_document'])) {
            $this->db->where('d.type_document', $filters['type_document']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les documents
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('d.*, v.nom_boutique as vendeur_nom')
                 ->from('documents_vendeur d')
                 ->join('vendeurs v', 'v.id_vendeur = d.id_vendeur')
                 ->order_by('d.date_upload', 'DESC');
        
        if (!empty($filters['statut_verification'])) {
            $this->db->where('d.statut_verification', $filters['statut_verification']);
        }
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('d.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['type_document'])) {
            $this->db->where('d.type_document', $filters['type_document']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer les documents d'un vendeur
     */
    public function get_by_vendeur($id_vendeur) {
        $this->db->where('id_vendeur', $id_vendeur)
                 ->order_by('date_upload', 'DESC');
        
        return $this->db->get('documents_vendeur')->result();
    }

    /**
     * Récupérer un document par ID
     */
    public function get_by_id($id) {
        $this->db->select('d.*, v.nom_boutique as vendeur_nom')
                 ->from('documents_vendeur d')
                 ->join('vendeurs v', 'v.id_vendeur = d.id_vendeur')
                 ->where('d.id_document', $id);
        
        return $this->db->get()->row();
    }

    /**
     * Ajouter un document
     */
    public function add($data) {
        $this->db->insert('documents_vendeur', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier un document
     */
    public function update($id, $data) {
        $this->db->where('id_document', $id);
        $this->db->update('documents_vendeur', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un document
     */
    public function delete($id) {
        $this->db->where('id_document', $id);
        $this->db->delete('documents_vendeur');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN statut_verification = "en_attente" THEN 1 ELSE 0 END) as en_attente,
            SUM(CASE WHEN statut_verification = "verifie" THEN 1 ELSE 0 END) as verifies,
            SUM(CASE WHEN statut_verification = "refuse" THEN 1 ELSE 0 END) as refuses
        ');
        
        return $this->db->get('documents_vendeur')->row();
    }
}
?>