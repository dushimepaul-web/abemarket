<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaiementsVendeurs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Compter tous les paiements
     */
    public function count_all($filters = []) {
        $this->db->from('paiements_vendeurs p');
        $this->db->join('vendeurs v', 'v.id_vendeur = p.id_vendeur');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('p.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['statut'])) {
            $this->db->where('p.statut', $filters['statut']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('p.date_debut_periode >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('p.date_fin_periode <=', $filters['date_fin']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Récupérer tous les paiements
     */
    public function get_all($limit = null, $offset = null, $filters = []) {
        $this->db->select('p.*, v.nom_boutique as vendeur_nom')
                 ->from('paiements_vendeurs p')
                 ->join('vendeurs v', 'v.id_vendeur = p.id_vendeur')
                 ->order_by('p.date_creation', 'DESC');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('p.id_vendeur', $filters['id_vendeur']);
        }
        if (!empty($filters['statut'])) {
            $this->db->where('p.statut', $filters['statut']);
        }
        if (!empty($filters['date_debut'])) {
            $this->db->where('p.date_debut_periode >=', $filters['date_debut']);
        }
        if (!empty($filters['date_fin'])) {
            $this->db->where('p.date_fin_periode <=', $filters['date_fin']);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Récupérer un paiement par ID
     */
   /**
 * Récupérer un paiement par ID
 */
public function get_by_id($id) {
    $this->db->select('p.*, v.nom_boutique as vendeur_nom, u.email as vendeur_email')
             ->from('paiements_vendeurs p')
             ->join('vendeurs v', 'v.id_vendeur = p.id_vendeur')
             ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur', 'left')
             ->where('p.id_paiement', $id);
    
    return $this->db->get()->row();
}

    /**
     * Ajouter un paiement
     */
    public function add($data) {
        $this->db->insert('paiements_vendeurs', $data);
        return $this->db->insert_id();
    }

    /**
     * Mettre à jour un paiement
     */
    public function update($id, $data) {
        $this->db->where('id_paiement', $id);
        $this->db->update('paiements_vendeurs', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Supprimer un paiement
     */
    public function delete($id) {
        $this->db->where('id_paiement', $id);
        $this->db->delete('paiements_vendeurs');
        return $this->db->affected_rows() > 0;
    }

    /**
     * Récupérer les commandes d'un vendeur sur une période
     */
    public function get_commandes_by_periode($id_vendeur, $date_debut, $date_fin) {
        $this->db->select('c.*, a.prix_total, a.montant_commission, a.revenus_vendeur')
                 ->from('commandes c')
                 ->join('articles_commande a', 'a.id_commande = c.id_commande')
                 ->where('a.id_vendeur', $id_vendeur)
                 ->where('c.date_creation >=', $date_debut)
                 ->where('c.date_creation <=', $date_fin . ' 23:59:59')
                 ->where('c.statut_commande', 'livre')
                 ->group_by('c.id_commande')
                 ->order_by('c.date_creation', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Générer les paiements pour une période
     */
    public function generer_paiements($date_debut, $date_fin, $id_vendeur = null) {
        // Récupérer les vendeurs concernés
        $this->db->select('v.id_vendeur, v.nom_boutique')
                 ->from('vendeurs v');
        
        if ($id_vendeur) {
            $this->db->where('v.id_vendeur', $id_vendeur);
        }
        
        $vendeurs = $this->db->get()->result();
        
        if (empty($vendeurs)) {
            return ['success' => false, 'message' => 'Aucun vendeur trouvé'];
        }
        
        $count = 0;
        
        foreach ($vendeurs as $v) {
            // Calculer les totaux
            $this->db->select('
                COUNT(DISTINCT c.id_commande) as nombre_commandes,
                SUM(a.prix_total) as total_revenus,
                SUM(a.montant_commission) as total_commissions,
                SUM(a.revenus_vendeur) as total_net
            ')
            ->from('commandes c')
            ->join('articles_commande a', 'a.id_commande = c.id_commande')
            ->where('a.id_vendeur', $v->id_vendeur)
            ->where('c.date_creation >=', $date_debut)
            ->where('c.date_creation <=', $date_fin . ' 23:59:59')
            ->where('c.statut_commande', 'livre');
            
            $totals = $this->db->get()->row();
            
            if ($totals->nombre_commandes > 0) {
                // Vérifier si le paiement existe déjà
                $exists = $this->db->where('id_vendeur', $v->id_vendeur)
                                  ->where('date_debut_periode', $date_debut)
                                  ->where('date_fin_periode', $date_fin)
                                  ->get('paiements_vendeurs')
                                  ->num_rows() > 0;
                
                if (!$exists) {
                    $data = [
                        'id_vendeur' => $v->id_vendeur,
                        'date_debut_periode' => $date_debut,
                        'date_fin_periode' => $date_fin,
                        'nombre_commandes' => $totals->nombre_commandes,
                        'total_revenus' => $totals->total_revenus,
                        'total_commissions' => $totals->total_commissions,
                        'total_remboursements' => 0,
                        'montant_net' => $totals->total_net,
                        'statut' => 'en_attente',
                        'date_creation' => date('Y-m-d H:i:s')
                    ];
                    
                    $this->add($data);
                    $count++;
                }
            }
        }
        
        return [
            'success' => true,
            'message' => $count . ' paiement(s) généré(s) pour la période'
        ];
    }

    /**
     * Statistiques
     */
    public function get_stats($filters = []) {
        $this->db->select('
            COUNT(*) as total_paiements,
            SUM(CASE WHEN statut = "en_attente" THEN 1 ELSE 0 END) as en_attente,
            SUM(CASE WHEN statut = "en_cours" THEN 1 ELSE 0 END) as en_cours,
            SUM(CASE WHEN statut = "paye" THEN 1 ELSE 0 END) as payes,
            SUM(CASE WHEN statut = "echoue" THEN 1 ELSE 0 END) as echoues,
            SUM(montant_net) as total_a_payer,
            SUM(CASE WHEN statut = "paye" THEN montant_net ELSE 0 END) as total_paye
        ');
        $this->db->from('paiements_vendeurs');
        
        if (!empty($filters['id_vendeur'])) {
            $this->db->where('id_vendeur', $filters['id_vendeur']);
        }
        
        return $this->db->get()->row();
    }
}
?>