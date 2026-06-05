<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SoldesVendeurs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Récupérer tous les soldes
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('s.*, v.nom_boutique, v.slug_boutique, v.statut as vendeur_statut')
                 ->from('soldes_vendeurs s')
                 ->join('vendeurs v', 'v.id_vendeur = s.id_vendeur')
                 ->order_by('s.solde_disponible', 'DESC');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('s.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['solde_min'])) {
            $this->db->where('s.solde_disponible >=', $filters['solde_min']);
        }
        if (!empty($filters['solde_max'])) {
            $this->db->where('s.solde_disponible <=', $filters['solde_max']);
        }
        if (isset($filters['statut']) && $filters['statut'] !== '') {
            $this->db->where('v.statut', $filters['statut']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Compter les soldes
     */
    public function count_all($filters = []) {
        $this->db->from('soldes_vendeurs s')
                 ->join('vendeurs v', 'v.id_vendeur = s.id_vendeur');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('s.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['solde_min'])) {
            $this->db->where('s.solde_disponible >=', $filters['solde_min']);
        }
        if (!empty($filters['solde_max'])) {
            $this->db->where('s.solde_disponible <=', $filters['solde_max']);
        }
        if (isset($filters['statut']) && $filters['statut'] !== '') {
            $this->db->where('v.statut', $filters['statut']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer le solde d'un vendeur
     */
    public function get_by_vendeur($id_vendeur) {
        $this->db->where('id_vendeur', $id_vendeur);
        return $this->db->get('soldes_vendeurs')->row();
    }

    /**
     * Récupérer un solde avec détails
     */
    public function get_by_id_with_details($id_solde) {
        $this->db->select('s.*, v.nom_boutique, v.slug_boutique, v.logo_boutique, v.statut as vendeur_statut')
                 ->from('soldes_vendeurs s')
                 ->join('vendeurs v', 'v.id_vendeur = s.id_vendeur')
                 ->where('s.id_solde', $id_solde);
        
        return $this->db->get()->row();
    }

    /**
     * Créer un solde pour un vendeur
     */
    public function create($id_vendeur) {
        $data = [
            'id_vendeur' => $id_vendeur,
            'solde_disponible' => 0,
            'solde_en_attente' => 0,
            'total_gagne' => 0,
            'total_retire' => 0,
            'date_modification' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('soldes_vendeurs', $data);
        return $this->db->insert_id();
    }

    /**
     * Mettre à jour un solde
     */
    public function update($id_vendeur, $data) {
        $data['date_modification'] = date('Y-m-d H:i:s');
        $this->db->where('id_vendeur', $id_vendeur);
        return $this->db->update('soldes_vendeurs', $data);
    }

    /**
     * Ajouter au solde disponible
     */
    public function add_disponible($id_vendeur, $montant) {
        $this->db->set('solde_disponible', 'solde_disponible + ' . $montant, false)
                 ->set('total_gagne', 'total_gagne + ' . $montant, false)
                 ->set('date_modification', date('Y-m-d H:i:s'))
                 ->where('id_vendeur', $id_vendeur)
                 ->update('soldes_vendeurs');
        
        return $this->db->affected_rows() > 0;
    }

    /**
     * Ajouter au solde en attente
     */
    public function add_en_attente($id_vendeur, $montant) {
        $this->db->set('solde_en_attente', 'solde_en_attente + ' . $montant, false)
                 ->set('date_modification', date('Y-m-d H:i:s'))
                 ->where('id_vendeur', $id_vendeur)
                 ->update('soldes_vendeurs');
        
        return $this->db->affected_rows() > 0;
    }

    /**
     * Transférer du solde en attente vers disponible
     */
    public function transfert_attente_vers_disponible($id_vendeur, $montant) {
        $this->db->set('solde_disponible', 'solde_disponible + ' . $montant, false)
                 ->set('solde_en_attente', 'solde_en_attente - ' . $montant, false)
                 ->set('date_modification', date('Y-m-d H:i:s'))
                 ->where('id_vendeur', $id_vendeur);
        
        return $this->db->update('soldes_vendeurs');
    }

    /**
     * Retirer du solde (paiement effectué)
     */
    public function retirer($id_vendeur, $montant) {
        $this->db->set('solde_disponible', 'solde_disponible - ' . $montant, false)
                 ->set('total_retire', 'total_retire + ' . $montant, false)
                 ->set('dernier_paiement', date('Y-m-d H:i:s'))
                 ->set('date_modification', date('Y-m-d H:i:s'))
                 ->where('id_vendeur', $id_vendeur);
        
        return $this->db->update('soldes_vendeurs');
    }

    /**
     * Recalculer le solde d'un vendeur
     */
    public function recalculer_solde($id_vendeur) {
        // Calculer le total gagné (revenus des commandes)
        $this->db->select('SUM(ac.revenus_vendeur) as total_revenus')
                 ->from('articles_commande ac')
                 ->join('commandes c', 'c.id_commande = ac.id_commande')
                 ->where('ac.id_vendeur', $id_vendeur)
                 ->where('c.statut_commande', 'livre')
                 ->where('ac.statut_article', 'livre');
        
        $revenus = $this->db->get()->row();
        $total_gagne = $revenus->total_revenus ?? 0;
        
        // Calculer le total retiré (paiements effectués)
        $this->db->select('SUM(montant_net) as total_paye')
                 ->from('paiements_vendeurs')
                 ->where('id_vendeur', $id_vendeur)
                 ->where('statut', 'paye');
        
        $paiements = $this->db->get()->row();
        $total_retire = $paiements->total_paye ?? 0;
        
        // Solde disponible = total_gagne - total_retire
        $solde_disponible = $total_gagne - $total_retire;
        
        // Vérifier si le solde existe
        $solde = $this->get_by_vendeur($id_vendeur);
        
        if (!$solde) {
            $this->create($id_vendeur);
            $solde = $this->get_by_vendeur($id_vendeur);
        }
        
        $data = [
            'total_gagne' => $total_gagne,
            'total_retire' => $total_retire,
            'solde_disponible' => $solde_disponible,
            'date_modification' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id_vendeur', $id_vendeur);
        return $this->db->update('soldes_vendeurs', $data);
    }

    /**
     * Statistiques globales
     */
    public function get_stats($filters = []) {
        $this->db->select('
            SUM(s.solde_disponible) as total_solde_disponible,
            SUM(s.solde_en_attente) as total_solde_attente,
            SUM(s.total_gagne) as total_gagne,
            SUM(s.total_retire) as total_retire,
            COUNT(s.id_solde) as total_vendeurs,
            AVG(s.solde_disponible) as moyenne_solde
        ');
        $this->db->from('soldes_vendeurs s')
                 ->join('vendeurs v', 'v.id_vendeur = s.id_vendeur');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('s.id_vendeur', $filters['id_vendeur']);
        }
        if (isset($filters['statut']) && $filters['statut'] !== '') {
            $this->db->where('v.statut', $filters['statut']);
        }
        
        $result = $this->db->get()->row();
        
        if (!$result->total_solde_disponible) $result->total_solde_disponible = 0;
        if (!$result->total_solde_attente) $result->total_solde_attente = 0;
        if (!$result->total_gagne) $result->total_gagne = 0;
        if (!$result->total_retire) $result->total_retire = 0;
        if (!$result->total_vendeurs) $result->total_vendeurs = 0;
        if (!$result->moyenne_solde) $result->moyenne_solde = 0;
        
        return $result;
    }

    /**
     * Top vendeurs par solde
     */
    public function get_top_vendeurs($limit = 10) {
        $this->db->select('s.*, v.nom_boutique, v.logo_boutique')
                 ->from('soldes_vendeurs s')
                 ->join('vendeurs v', 'v.id_vendeur = s.id_vendeur')
                 ->where('s.solde_disponible >', 0)
                 ->order_by('s.solde_disponible', 'DESC')
                 ->limit($limit);
        
        return $this->db->get()->result();
    }
}
?>