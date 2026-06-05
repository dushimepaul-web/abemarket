<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transporteurs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter tous les transporteurs
     */
    public function count_all($filters = []) {
        $this->db->from('transporteurs');
        
        if (!empty($filters['statut'])) {
            $this->db->where('statut', $filters['statut']);
        }
        if (!empty($filters['type_vehicule'])) {
            $this->db->where('type_vehicule', $filters['type_vehicule']);
        }
        if (isset($filters['est_disponible']) && $filters['est_disponible'] !== '') {
            $this->db->where('est_disponible', $filters['est_disponible']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nom', $filters['search']);
            $this->db->or_like('telephone', $filters['search']);
            $this->db->or_like('plaque', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les transporteurs
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->order_by('date_creation', 'DESC');
        
        if (!empty($filters['statut'])) {
            $this->db->where('statut', $filters['statut']);
        }
        if (!empty($filters['type_vehicule'])) {
            $this->db->where('type_vehicule', $filters['type_vehicule']);
        }
        if (isset($filters['est_disponible']) && $filters['est_disponible'] !== '') {
            $this->db->where('est_disponible', $filters['est_disponible']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('nom', $filters['search']);
            $this->db->or_like('telephone', $filters['search']);
            $this->db->or_like('plaque', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get('transporteurs')->result();
    }

    /**
     * Récupérer les transporteurs disponibles
     */
    public function get_disponibles() {
        return $this->db->where('est_disponible', 1)
                        ->where('statut', 'actif')
                        ->get('transporteurs')
                        ->result();
    }

    /**
     * Récupérer un transporteur par ID
     */
    public function get_by_id($id) {
        return $this->db->where('id_transporteur', $id)->get('transporteurs')->row();
    }

    /**
     * Récupérer un transporteur par utilisateur
     */
    public function get_by_user_id($id_utilisateur) {
        return $this->db->where('id_utilisateur', $id_utilisateur)->get('transporteurs')->row();
    }

    /**
     * Ajouter un transporteur
     */
    public function add($data) {
        $this->db->insert('transporteurs', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier un transporteur
     */
    public function update($id, $data) {
        $this->db->where('id_transporteur', $id);
        $this->db->update('transporteurs', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un transporteur
     */
    public function delete($id) {
        $this->db->where('id_transporteur', $id);
        $this->db->delete('transporteurs');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Mettre à jour les statistiques
     */
    public function update_stats($id, $livraison_reussie = true) {
        $this->db->set('livraisons_totales', 'livraisons_totales + 1', FALSE);
        if ($livraison_reussie) {
            $this->db->set('livraisons_reussies', 'livraisons_reussies + 1', FALSE);
        }
        $this->db->where('id_transporteur', $id);
        $this->db->update('transporteurs');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Mettre à jour la note moyenne
     */
    public function update_note($id, $nouvelle_note) {
        $transporteur = $this->get_by_id($id);
        if ($transporteur) {
            $nouv_moyenne = (($transporteur->note_moyenne * $transporteur->livraisons_totales) + $nouvelle_note) / ($transporteur->livraisons_totales + 1);
            $this->db->where('id_transporteur', $id);
            $this->db->update('transporteurs', ['note_moyenne' => $nouv_moyenne]);
        }
        return $this->db->affected_rows() > 0;
    }

    /**
     * Statistiques
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN statut = "actif" THEN 1 ELSE 0 END) as actifs,
            SUM(CASE WHEN statut = "inactif" THEN 1 ELSE 0 END) as inactifs,
            SUM(CASE WHEN statut = "suspendu" THEN 1 ELSE 0 END) as suspendus,
            SUM(CASE WHEN est_disponible = 1 AND statut = "actif" THEN 1 ELSE 0 END) as disponibles,
            SUM(CASE WHEN type_vehicule = "moto" THEN 1 ELSE 0 END) as motos,
            SUM(CASE WHEN type_vehicule = "voiture" THEN 1 ELSE 0 END) as voitures,
            SUM(CASE WHEN type_vehicule = "camionnette" THEN 1 ELSE 0 END) as camionnettes,
            AVG(note_moyenne) as note_moyenne,
            SUM(livraisons_totales) as total_livraisons
        ');
        
        return $this->db->get('transporteurs')->row();
    }
}
?>