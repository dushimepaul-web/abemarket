<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendeurs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer un vendeur par son ID
     */
    public function get_by_id($id_vendeur) {
        $this->db->select('v.*, u.email, u.prenom, u.nom, u.telephone')
                 ->from('vendeurs v')
                 ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
                 ->where('v.id_vendeur', $id_vendeur);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer un vendeur par ID utilisateur
     */
    public function get_by_user_id($id_utilisateur) {
        $this->db->select('v.*, u.email, u.prenom, u.nom')
                 ->from('vendeurs v')
                 ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
                 ->where('v.id_utilisateur', $id_utilisateur);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer un vendeur par slug de boutique
     */
    public function get_by_slug($slug_boutique) {
        $this->db->select('v.*, u.email, u.prenom, u.nom')
                 ->from('vendeurs v')
                 ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
                 ->where('v.slug_boutique', $slug_boutique);
        
        return $this->db->get()->row();
    }

    /**
     * Récupérer tous les vendeurs
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('v.*, u.email, CONCAT(u.prenom, " ", u.nom) as proprietaire')
                 ->from('vendeurs v')
                 ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
                 ->order_by('v.date_creation', 'DESC');
        
        if (!empty($filters['statut'])) {
            $this->db->where('v.statut', $filters['statut']);
        }
        if (!empty($filters['type_vendeur'])) {
            $this->db->where('v.type_vendeur', $filters['type_vendeur']);
        }
        if (!empty($filters['est_approuve'])) {
            $this->db->where('v.est_approuve', $filters['est_approuve']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Compter les vendeurs
     */
    public function count_all($filters = []) {
        $this->db->from('vendeurs v');
        
        if (!empty($filters['statut'])) {
            $this->db->where('v.statut', $filters['statut']);
        }
        if (!empty($filters['type_vendeur'])) {
            $this->db->where('v.type_vendeur', $filters['type_vendeur']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer les vendeurs actifs
     */
    public function get_actifs() {
        $this->db->where('statut', 'actif')
                 ->where('est_approuve', 1);
        return $this->db->get('vendeurs')->result();
    }

    /**
     * Récupérer les vendeurs en attente d'approbation
     */
    public function get_en_attente() {
        $this->db->where('statut', 'en_attente')
                 ->where('est_approuve', 0);
        return $this->db->get('vendeurs')->result();
    }

    /**
     * Créer un vendeur
     */
    public function create($data) {
        $this->db->insert('vendeurs', $data);
        return $this->db->insert_id();
    }

    /**
     * Mettre à jour un vendeur
     */
    public function update($id_vendeur, $data) {
        $data['date_modification'] = date('Y-m-d H:i:s');
        $this->db->where('id_vendeur', $id_vendeur);
        return $this->db->update('vendeurs', $data);
    }
    
    /**
     * Approuver un vendeur
     */
    public function approuver($id_vendeur, $id_admin) {
        $data = [
            'est_approuve' => 1,
            'statut' => 'actif',
            'date_approbation' => date('Y-m-d H:i:s'),
            'approuve_par' => $id_admin
        ];
        return $this->update($id_vendeur, $data);
    }

    /**
     * Suspendre un vendeur
     */
    public function suspendre($id_vendeur, $motif = null) {
        $data = [
            'statut' => 'suspendu'
        ];
        return $this->update($id_vendeur, $data);
    }

    /**
     * Activer un vendeur
     */
    public function activer($id_vendeur) {
        $data = [
            'statut' => 'actif'
        ];
        return $this->update($id_vendeur, $data);
    }

    /**
     * Bannir un vendeur
     */
    public function bannir($id_vendeur) {
        $data = [
            'statut' => 'banni'
        ];
        return $this->update($id_vendeur, $data);
    }

    /**
     * Supprimer un vendeur
     */
    public function delete($id_vendeur) {
        $this->db->where('id_vendeur', $id_vendeur);
        return $this->db->delete('vendeurs');
    }

    /**
     * Vérifier si le slug de boutique existe
     */
    public function slug_exists($slug, $exclude_id = null) {
        $this->db->where('slug_boutique', $slug);
        if ($exclude_id) {
            $this->db->where('id_vendeur !=', $exclude_id);
        }
        return $this->db->count_all_results('vendeurs') > 0;
    }

    /**
     * Générer un slug unique
     */
    public function generate_slug($nom_boutique) {
        $slug = url_title($nom_boutique, '-', true);
        $original_slug = $slug;
        $counter = 1;
        
        while ($this->slug_exists($slug)) {
            $slug = $original_slug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Statistiques des vendeurs
     */
    public function get_stats() {
        $this->db->select('
            COUNT(*) as total_vendeurs,
            SUM(CASE WHEN statut = "actif" THEN 1 ELSE 0 END) as actifs,
            SUM(CASE WHEN statut = "en_attente" THEN 1 ELSE 0 END) as en_attente,
            SUM(CASE WHEN statut = "suspendu" THEN 1 ELSE 0 END) as suspendus,
            SUM(CASE WHEN type_vendeur = "entreprise" THEN 1 ELSE 0 END) as entreprises,
            SUM(CASE WHEN type_vendeur = "particulier" THEN 1 ELSE 0 END) as particuliers
        ');
        $this->db->from('vendeurs');
        
        return $this->db->get()->row();
    }

    /**
     * Derniers vendeurs inscrits
     */
    public function get_recents($limit = 10) {
        $this->db->select('v.*, u.email, CONCAT(u.prenom, " ", u.nom) as proprietaire')
                 ->from('vendeurs v')
                 ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
                 ->order_by('v.date_creation', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Vendeurs avec le plus de ventes
     */
    public function get_top_vendeurs($limit = 10) {
        $this->db->select('v.id_vendeur, v.nom_boutique, v.logo_boutique, v.note_moyenne, COUNT(c.id_commande) as total_commandes, SUM(c.montant_total) as total_ventes')
                 ->from('vendeurs v')
                 ->join('articles_commande ac', 'ac.id_vendeur = v.id_vendeur')
                 ->join('commandes c', 'c.id_commande = ac.id_commande')
                 ->where('c.statut_commande', 'livre')
                 ->group_by('v.id_vendeur')
                 ->order_by('total_ventes', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }
}
?>