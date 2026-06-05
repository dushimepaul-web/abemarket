<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AvisProduit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter le nombre total d'avis (pour pagination)
     */
    public function count_all_avis($filters = []) {
        $this->db->from('avis_produits a')
                 ->join('produits p', 'a.id_produit = p.id_produit')
                 ->join('utilisateurs u', 'a.id_utilisateur = u.id_utilisateur');
        
        if (!empty($filters['est_approuve']) && $filters['est_approuve'] !== '') {
            $this->db->where('a.est_approuve', $filters['est_approuve']);
        }
        if (!empty($filters['note'])) {
            $this->db->where('a.note', $filters['note']);
        }
        if (!empty($filters['id_produit'])) {
            $this->db->where('a.id_produit', $filters['id_produit']);
        }
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('p.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('p.nom_produit', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->or_like('a.titre', $filters['search']);
            $this->db->or_like('a.commentaire', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les avis (admin)
     */
    public function get_all_avis($limit = null, $offset = null, $filters = []) {
        $this->db->select('a.*, p.nom_produit, p.sku, u.prenom, u.nom, u.email')
                 ->from('avis_produits a')
                 ->join('produits p', 'a.id_produit = p.id_produit')
                 ->join('utilisateurs u', 'a.id_utilisateur = u.id_utilisateur')
                 ->order_by('a.date_creation', 'DESC');
        
        if (isset($filters['est_approuve']) && $filters['est_approuve'] !== '') {
            $this->db->where('a.est_approuve', $filters['est_approuve']);
        }
        if (!empty($filters['note'])) {
            $this->db->where('a.note', $filters['note']);
        }
        if (!empty($filters['id_produit'])) {
            $this->db->where('a.id_produit', $filters['id_produit']);
        }
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('p.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('p.nom_produit', $filters['search']);
            $this->db->or_like('u.prenom', $filters['search']);
            $this->db->or_like('u.nom', $filters['search']);
            $this->db->or_like('a.titre', $filters['search']);
            $this->db->or_like('a.commentaire', $filters['search']);
            $this->db->group_end();
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Récupérer les avis d'un produit
     */
    public function get_avis_by_produit($id_produit, $limit = null, $offset = null) {
        $this->db->select('a.*, u.prenom, u.nom')
                 ->from('avis_produits a')
                 ->join('utilisateurs u', 'a.id_utilisateur = u.id_utilisateur')
                 ->where('a.id_produit', $id_produit)
                 ->where('a.est_approuve', 1)
                 ->order_by('a.date_creation', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Compter les avis d'un produit
     */
    public function count_avis_by_produit($id_produit) {
        $this->db->where('id_produit', $id_produit)
                 ->where('est_approuve', 1);
        return $this->db->count_all_results('avis_produits');
    }

    /**
     * Obtenir la note moyenne d'un produit
     */
    public function get_note_moyenne_produit($id_produit) {
        $this->db->select_avg('note')
                 ->where('id_produit', $id_produit)
                 ->where('est_approuve', 1);
        $query = $this->db->get('avis_produits');
        $result = $query->row();
        return round($result->note ?? 0, 1);
    }

    /**
     * Récupérer un avis par son ID
     */
    public function get_avis_by_id($id) {
        $this->db->select('a.*, p.nom_produit, p.sku, p.id_vendeur, u.prenom, u.nom, u.email')
                 ->from('avis_produits a')
                 ->join('produits p', 'a.id_produit = p.id_produit')
                 ->join('utilisateurs u', 'a.id_utilisateur = u.id_utilisateur')
                 ->where('a.id_avis', $id);
        
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Ajouter un avis
     */
    public function ajouter_avis($data) {
        // Gérer les URLs des médias
        if (isset($data['urls_medias']) && is_array($data['urls_medias'])) {
            $data['urls_medias'] = json_encode($data['urls_medias']);
        }
        
        $this->db->insert('avis_produits', $data);
        return $this->db->insert_id();
    }

    /**
     * Modifier un avis
     */
    public function modifier_avis($id, $data) {
        if (isset($data['urls_medias']) && is_array($data['urls_medias'])) {
            $data['urls_medias'] = json_encode($data['urls_medias']);
        }
        
        $this->db->where('id_avis', $id);
        $this->db->update('avis_produits', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Approuver un avis
     */
    public function approuver_avis($id) {
        $this->db->where('id_avis', $id);
        $this->db->update('avis_produits', [
            'est_approuve' => 1,
            'date_modification' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Rejeter un avis
     */
    public function rejeter_avis($id) {
        $this->db->where('id_avis', $id);
        $this->db->update('avis_produits', [
            'est_approuve' => 0,
            'date_modification' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un avis
     */
    public function supprimer_avis($id) {
        $this->db->where('id_avis', $id);
        $this->db->delete('avis_produits');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Ajouter une réponse vendeur
     */
    public function ajouter_reponse($id_avis, $reponse, $id_utilisateur) {
        $this->db->where('id_avis', $id_avis);
        $this->db->update('avis_produits', [
            'reponse_vendeur' => $reponse,
            'date_reponse_vendeur' => date('Y-m-d H:i:s'),
            'id_utilisateur_reponse' => $id_utilisateur
        ]);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Obtenir les statistiques des avis (avec filtre vendeur optionnel)
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(*) as total_avis,
            SUM(CASE WHEN est_approuve = 1 THEN 1 ELSE 0 END) as approuves,
            SUM(CASE WHEN est_approuve = 0 THEN 1 ELSE 0 END) as en_attente,
            AVG(CASE WHEN est_approuve = 1 THEN note ELSE NULL END) as note_moyenne,
            COUNT(DISTINCT a.id_produit) as produits_notes
        ');
        $this->db->from('avis_produits a');
        $this->db->join('produits p', 'a.id_produit = p.id_produit');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('p.id_vendeur', $filters['id_vendeur']);
        }
        
        $query = $this->db->get();
        $result = $query->row();
        $result->note_moyenne = round($result->note_moyenne ?? 0, 1);
        return $result;
    }

    /**
     * Obtenir la répartition des notes (avec filtre vendeur optionnel)
     */
    public function get_repartition_notes($filters = []) {
        $this->db->select('a.note, COUNT(*) as total')
                 ->from('avis_produits a')
                 ->join('produits p', 'a.id_produit = p.id_produit')
                 ->where('a.est_approuve', 1);
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('p.id_vendeur', $filters['id_vendeur']);
        }
        
        $this->db->group_by('a.note')
                 ->order_by('a.note', 'DESC');
        
        $query = $this->db->get();
        $results = $query->result();
        
        $repartition = [];
        for ($i = 5; $i >= 1; $i--) {
            $found = false;
            foreach ($results as $r) {
                if ($r->note == $i) {
                    $repartition[$i] = $r->total;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $repartition[$i] = 0;
            }
        }
        
        return $repartition;
    }
}
?>