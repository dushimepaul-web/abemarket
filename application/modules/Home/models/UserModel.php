<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // ==================== AUTHENTIFICATION & UTILISATEUR ====================
    
    /**
     * Vérifie si l'utilisateur a le rôle vendeur
     * @param int $user_id
     * @return bool
     */
    public function is_vendeur($user_id) {
        $this->db->select('up.id_profil')
                 ->from('utilisateur_profils up')
                 ->where('up.id_utilisateur', $user_id);
        $result = $this->db->get()->row_array();
        return ($result && $result['id_profil'] == 4);
    }
    
    /**
     * Récupère l'ID vendeur à partir de l'ID utilisateur
     * @param int $user_id
     * @return int|null
     */
    public function get_vendeur_id($user_id) {
        $vendeur = $this->db->select('id_vendeur')
                            ->where('id_utilisateur', $user_id)
                            ->get('vendeurs')
                            ->row_array();
        return $vendeur ? $vendeur['id_vendeur'] : null;
    }

    // ==================== COMMANDES CLIENT ====================
    
    /**
     * Récupère toutes les commandes d'un utilisateur
     */
    public function get_user_orders($user_id, $limit = null) {
        $this->db->select('c.*, m.description as mode_paiement, m.logo_url as mode_paiement_logo')
                 ->from('commandes c')
                 ->join('mode_payement m', 'c.id_mode_payement = m.id_mode_payement', 'left')
                 ->where('c.id_utilisateur', $user_id)
                 ->order_by('c.date_creation', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $commandes = $this->db->get()->result_array();
        
        foreach ($commandes as &$commande) {
            $commande['articles'] = $this->get_order_articles($commande['id_commande']);
            $commande['historique_statuts'] = $this->get_order_status_history($commande['id_commande']);
        }
        
        return $commandes;
    }

    /**
     * Récupère les articles d'une commande
     */
    public function get_order_articles($commande_id) {
        return $this->db->select('a.*, p.nom_produit, p.slug_produit, p.note_moyenne, p.main_image')
                        ->from('articles_commande a')
                        ->join('produits p', 'a.id_produit = p.id_produit', 'left')
                        ->where('a.id_commande', $commande_id)
                        ->get()
                        ->result_array();
    }

    /**
     * Récupère l'historique des statuts d'une commande
     */
    public function get_order_status_history($commande_id) {
        return $this->db->select('*')
                        ->from('historique_statut_commande')
                        ->where('id_commande', $commande_id)
                        ->order_by('date_creation', 'DESC')
                        ->get()
                        ->result_array();
    }

    // ==================== COMMANDES REÇUES (VENDEUR) ====================
    
    /**
     * Récupère les commandes reçues par un vendeur
     */
    public function get_seller_orders($user_id) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return [];
        
        return $this->db->select('c.id_commande, c.numero_commande, c.date_creation, c.statut_commande,
                                  a.nom_produit, a.quantite, a.prix_unitaire, a.prix_total, a.statut_article,
                                  u.prenom, u.nom, u.email, u.telephone')
                        ->from('articles_commande a')
                        ->join('commandes c', 'a.id_commande = c.id_commande')
                        ->join('utilisateurs u', 'c.id_utilisateur = u.id_utilisateur')
                        ->where('a.id_vendeur', $vendeur_id)
                        ->order_by('c.date_creation', 'DESC')
                        ->get()
                        ->result_array();
    }
    
    /**
     * Met à jour le statut d'une commande (vendeur)
     */
    public function update_order_status($commande_id, $status) {
        $order = $this->db->where('id_commande', $commande_id)->get('commandes')->row();
        if (!$order) return false;
        
        $this->db->where('id_commande', $commande_id)
                 ->update('commandes', ['statut_commande' => $status]);
        
        $this->db->insert('historique_statut_commande', [
            'id_commande' => $commande_id,
            'statut' => $status,
            'commentaire' => 'Statut mis à jour par le vendeur',
            'modifie_par' => $this->session->userdata('id_utilisateur')
        ]);
        
        return true;
    }

    // ==================== PRODUITS (VENDEUR) ====================
    
    /**
     * Récupère tous les produits d'un vendeur
     */
    public function get_seller_products($user_id) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return [];
        
        return $this->db->select('p.*, c.nom_categorie')
                        ->from('produits p')
                        ->join('categories c', 'p.id_categorie = c.id_categorie', 'left')
                        ->where('p.id_vendeur', $vendeur_id)
                        ->where('p.statut !=', 'supprime')
                        ->order_by('p.date_creation', 'DESC')
                        ->get()
                        ->result_array();
    }
    
    /**
     * Ajoute un nouveau produit (vendeur)
     */
    public function add_product($user_id, $data) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return false;
        
        $data['id_vendeur'] = $vendeur_id;
        $data['sku'] = 'SKU' . time() . rand(100, 999);
        $data['code_produit'] = 'PROD' . time() . rand(100, 999);
        $data['slug_produit'] = url_title($data['nom_produit'], '-', true);
        $data['date_creation'] = date('Y-m-d H:i:s');
        $data['statut'] = 'actif';
        $data['est_actif'] = 1;
        
        $this->db->insert('produits', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Met à jour un produit (vendeur)
     */
    public function update_product($product_id, $user_id, $data) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return false;
        
        $data['date_modification'] = date('Y-m-d H:i:s');
        
        return $this->db->where('id_produit', $product_id)
                        ->where('id_vendeur', $vendeur_id)
                        ->update('produits', $data);
    }
    
    /**
     * Supprime un produit (soft delete)
     */
    public function delete_product($product_id, $user_id) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return false;
        
        return $this->db->where('id_produit', $product_id)
                        ->where('id_vendeur', $vendeur_id)
                        ->update('produits', ['statut' => 'supprime', 'est_actif' => 0]);
    }
    
    /**
     * Met à jour le stock d'un produit
     */
    public function update_product_stock($product_id, $user_id, $quantity) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return false;
        
        $statut_stock = ($quantity > 10) ? 'en_stock' : (($quantity > 0) ? 'stock_bas' : 'rupture_stock');
        
        return $this->db->where('id_produit', $product_id)
                        ->where('id_vendeur', $vendeur_id)
                        ->update('produits', [
                            'quantite_actuelle' => $quantity,
                            'statut_stock' => $statut_stock
                        ]);
    }

    // ==================== GAINS VENDEUR ====================
    
    /**
     * Récupère les gains totaux d'un vendeur (commandes livrées)
     */
    public function get_seller_earnings($user_id) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return 0;
        
        $result = $this->db->select_sum('revenus_vendeur')
                           ->where('id_vendeur', $vendeur_id)
                           ->where('statut_article', 'livre')
                           ->get('articles_commande')
                           ->row();
        
        return $result->revenus_vendeur ?? 0;
    }
    
    /**
     * Récupère les gains en attente (commandes expédiées)
     */
    public function get_seller_pending_earnings($user_id) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return 0;
        
        $result = $this->db->select_sum('revenus_vendeur')
                           ->where('id_vendeur', $vendeur_id)
                           ->where('statut_article', 'expedie')
                           ->get('articles_commande')
                           ->row();
        
        return $result->revenus_vendeur ?? 0;
    }
    
    /**
     * Récupère les gains disponibles (dans le compte vendeur)
     */
    public function get_seller_available_earnings($user_id) {
        $vendeur_id = $this->get_vendeur_id($user_id);
        if (!$vendeur_id) return 0;
        
        $solde = $this->db->select('solde_disponible')
                          ->where('id_vendeur', $vendeur_id)
                          ->get('soldes_vendeurs')
                          ->row_array();
        
        return $solde ? $solde['solde_disponible'] : 0;
    }

    // ==================== ADRESSES ====================
    
    /**
     * Récupère toutes les adresses d'un utilisateur
     */
    public function get_user_addresses($user_id) {
        return $this->db->select('a.*, p.province_name')
                        ->from('adresses a')
                        ->join('provinces p', 'a.id_province = p.id_province', 'left')
                        ->where('a.id_utilisateur', $user_id)
                        ->where('a.est_actif', 1)
                        ->order_by('a.est_par_defaut', 'DESC')
                        ->get()
                        ->result_array();
    }

    /**
     * Ajoute une nouvelle adresse
     */
    public function add_address($user_id, $data) {
        $data['id_utilisateur'] = $user_id;
        $data['date_creation'] = date('Y-m-d H:i:s');
        
        if (isset($data['est_par_defaut']) && $data['est_par_defaut'] == 1) {
            $this->db->where('id_utilisateur', $user_id)
                     ->update('adresses', ['est_par_defaut' => 0]);
        } else {
            // Vérifier si c'est la première adresse
            $count = $this->db->where('id_utilisateur', $user_id)->count_all_results('adresses');
            if ($count == 0) {
                $data['est_par_defaut'] = 1;
            }
        }
        
        $this->db->insert('adresses', $data);
        return $this->db->insert_id();
    }
    
    /**
     * Supprime une adresse
     */
    public function delete_address($address_id, $user_id) {
        return $this->db->where('id_adresse', $address_id)
                        ->where('id_utilisateur', $user_id)
                        ->update('adresses', ['est_actif' => 0]);
    }

    // ==================== PROFIL UTILISATEUR ====================
    
    /**
     * Récupère les informations du profil utilisateur
     */
    public function get_user_profile($user_id) {
        $user = $this->db->select('u.*, up.id_profil, p.description as profil_nom')
                         ->from('utilisateurs u')
                         ->join('utilisateur_profils up', 'u.id_utilisateur = up.id_utilisateur', 'left')
                         ->join('profils p', 'up.id_profil = p.id_profil', 'left')
                         ->where('u.id_utilisateur', $user_id)
                         ->get()
                         ->row_array();
        
        if ($user) {
            $vendeur = $this->db->where('id_utilisateur', $user_id)
                                ->get('vendeurs')
                                ->row_array();
            if ($vendeur) {
                $user['est_vendeur'] = true;
                $user['infos_vendeur'] = $vendeur;
            } else {
                $user['est_vendeur'] = false;
            }
        }
        
        return $user;
    }

    /**
     * Met à jour le profil utilisateur
     */
    public function update_user_profile($user_id, $data) {
        $allowed_fields = ['prenom', 'nom', 'telephone', 'avatar_url'];
        $update_data = array_intersect_key($data, array_flip($allowed_fields));
        
        if (empty($update_data)) return false;
        
        $update_data['date_modification'] = date('Y-m-d H:i:s');
        
        return $this->db->where('id_utilisateur', $user_id)
                        ->update('utilisateurs', $update_data);
    }

    /**
     * Change le mot de passe
     */
    public function change_password($user_id, $old_password, $new_password) {
        $user = $this->db->select('mot_de_passe')
                         ->where('id_utilisateur', $user_id)
                         ->get('utilisateurs')
                         ->row_array();
        
        if (!$user) return 'Utilisateur non trouvé';
        
        $password_correct = false;
        if (password_verify($old_password, $user['mot_de_passe'])) {
            $password_correct = true;
        } elseif (strlen($user['mot_de_passe']) === 32 && md5($old_password) === $user['mot_de_passe']) {
            $password_correct = true;
        }
        
        if (!$password_correct) {
            return 'Ancien mot de passe incorrect';
        }
        
        $this->db->where('id_utilisateur', $user_id)
                 ->update('utilisateurs', ['mot_de_passe' => md5($new_password)]);
        
        return true;
    }

    /**
     * Change l'email
     */
    public function change_email($user_id, $new_email) {
        $existing = $this->db->where('email', $new_email)
                             ->where('id_utilisateur !=', $user_id)
                             ->get('utilisateurs')
                             ->num_rows();
        
        if ($existing > 0) return 'Cet email est déjà utilisé';
        
        $this->db->where('id_utilisateur', $user_id)
                 ->update('utilisateurs', [
                     'email' => $new_email,
                     'email_verifie' => 0,
                     'date_modification' => date('Y-m-d H:i:s')
                 ]);
        
        return true;
    }

    // ==================== WISHLIST ====================
    
    /**
     * Récupère la wishlist
     */
  /**
 * Récupère les produits dans la wishlist de l'utilisateur
 * @param int $user_id
 * @return array
 */
public function get_wishlist($user_id) {
    return $this->db->select('l.*, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo, p.note_moyenne')
                    ->from('liste_souhaits l')
                    ->join('produits p', 'l.id_produit = p.id_produit')
                    ->where('l.id_utilisateur', $user_id)
                    ->where('p.est_actif', 1)
                    ->order_by('l.date_ajout', 'DESC')
                    ->get()
                    ->result_array();
}
    


    /**
 * Récupère les produits dans la wishlist avec l'image principale
 * @param int $user_id
 * @return array
 */
public function get_wishlist_with_images($user_id) {
    $wishlist = $this->db->select('l.*, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo, p.note_moyenne')
                         ->from('liste_souhaits l')
                         ->join('produits p', 'l.id_produit = p.id_produit')
                         ->where('l.id_utilisateur', $user_id)
                         ->where('p.est_actif', 1)
                         ->order_by('l.date_ajout', 'DESC')
                         ->get()
                         ->result_array();
    
    // Ajouter l'image principale pour chaque produit
    foreach ($wishlist as &$item) {
        $image = $this->db->select('url_image')
                          ->from('images_produit')
                          ->where('id_produit', $item['id_produit'])
                          ->where('est_principale', 1)
                          ->limit(1)
                          ->get()
                          ->row_array();
        
        $item['main_image'] = $image ? $image['url_image'] : 'default-product.png';
    }
    
    return $wishlist;
}


    /**
     * Ajoute à la wishlist
     */
    public function add_to_wishlist($user_id, $product_id) {
        $exists = $this->db->where('id_utilisateur', $user_id)
                           ->where('id_produit', $product_id)
                           ->get('liste_souhaits')
                           ->num_rows();
        
        if ($exists > 0) return false;
        
        return $this->db->insert('liste_souhaits', [
            'id_utilisateur' => $user_id,
            'id_produit' => $product_id,
            'date_ajout' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Supprime de la wishlist
     */
    public function remove_from_wishlist($user_id, $product_id) {
        return $this->db->where('id_utilisateur', $user_id)
                        ->where('id_produit', $product_id)
                        ->delete('liste_souhaits');
    }

    // ==================== AVIS ====================
    
    /**
     * Récupère les avis de l'utilisateur
     */
    public function get_user_reviews($user_id) {
        return $this->db->select('a.*, p.nom_produit, p.slug_produit')
                        ->from('avis_produits a')
                        ->join('produits p', 'a.id_produit = p.id_produit')
                        ->where('a.id_utilisateur', $user_id)
                        ->order_by('a.date_creation', 'DESC')
                        ->get()
                        ->result_array();
    }

    // ==================== STATISTIQUES DASHBOARD ====================
    
    /**
     * Récupère les statistiques du dashboard
     */
    public function get_dashboard_stats($user_id) {
        $total_commandes = $this->db->where('id_utilisateur', $user_id)
                                    ->count_all_results('commandes');
        
        $commandes_en_cours = $this->db->where('id_utilisateur', $user_id)
                                       ->where_in('statut_commande', ['en_attente', 'confirme', 'en_preparation', 'expedie', 'en_livraison'])
                                       ->count_all_results('commandes');
        
        $commandes_livre = $this->db->where('id_utilisateur', $user_id)
                                    ->where('statut_commande', 'livre')
                                    ->count_all_results('commandes');
        
        $wishlist_count = $this->db->where('id_utilisateur', $user_id)
                                   ->count_all_results('liste_souhaits');
        
        $addresses_count = $this->db->where('id_utilisateur', $user_id)
                                    ->where('est_actif', 1)
                                    ->count_all_results('adresses');
        
        $total_depense = $this->db->select_sum('montant_total')
                                  ->where('id_utilisateur', $user_id)
                                  ->where('statut_paiement', 'paye')
                                  ->get('commandes')
                                  ->row()
                                  ->montant_total ?? 0;
        
        $stats = [
            'total_commandes' => (int)$total_commandes,
            'commandes_en_cours' => (int)$commandes_en_cours,
            'commandes_livre' => (int)$commandes_livre,
            'wishlist_count' => (int)$wishlist_count,
            'addresses_count' => (int)$addresses_count,
            'total_depense' => (float)$total_depense
        ];
        
        // Stats vendeur si applicable
        if ($this->is_vendeur($user_id)) {
            $stats['total_produits'] = count($this->get_seller_products($user_id));
            $stats['commandes_recues'] = count($this->get_seller_orders($user_id));
            $stats['total_gains'] = $this->get_seller_earnings($user_id);
            $stats['gains_en_attente'] = $this->get_seller_pending_earnings($user_id);
            $stats['gains_disponible'] = $this->get_seller_available_earnings($user_id);
        }
        
        return $stats;
    }

    // ==================== NOTIFICATIONS ====================
    
    public function get_notifications($user_id, $limit = 10) {
        return $this->db->where('id_utilisateur', $user_id)
                        ->order_by('date_creation', 'DESC')
                        ->limit($limit)
                        ->get('notifications')
                        ->result_array();
    }
    
    public function mark_notification_read($notification_id, $user_id) {
        return $this->db->where('id_notification', $notification_id)
                        ->where('id_utilisateur', $user_id)
                        ->update('notifications', [
                            'est_lue' => 1,
                            'date_lecture' => date('Y-m-d H:i:s')
                        ]);
    }
    
    public function count_unread_notifications($user_id) {
        return $this->db->where('id_utilisateur', $user_id)
                        ->where('est_lue', 0)
                        ->count_all_results('notifications');
    }

    // ==================== CARTES BANCAIRES ====================
    
    public function get_saved_cards($user_id) {
        // Table à créer si besoin
        if (!$this->db->table_exists('cartes_bancaires')) {
            return [];
        }
        
        return $this->db->where('id_utilisateur', $user_id)
                        ->where('est_actif', 1)
                        ->get('cartes_bancaires')
                        ->result_array();
    }
}
?>