<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        // Charger la librairie session
        $this->load->library('session');

        $this->load->model('Home_model');
        
        // Vérifier si l'utilisateur est connecté
        if (!$this->session->userdata('id_utilisateur')) {
            $this->session->set_userdata('redirect_url', current_url());
            $this->session->set_flashdata('error', 'Veuillez vous connecter pour accéder à votre tableau de bord');
            redirect('auth/login');
        }
        
        // Charger le modèle
        $this->load->model('UserModel');
        $this->load->model('Produit_model');
        $this->load->model('Model');
    }
    
    public function index() {
        $user_id = $this->session->userdata('id_utilisateur');
         $data['settings'] = $this->Home_model->getSiteSettings();
        
        // Récupérer les données utilisateur
        $data['user'] = $this->UserModel->get_user_profile($user_id);
        $data['is_vendeur'] = $this->UserModel->is_vendeur($user_id);
        
        // Récupérer les données du dashboard
        $data['stats'] = $this->UserModel->get_dashboard_stats($user_id);
        $data['commandes'] = $this->UserModel->get_user_orders($user_id);
        $data['adresses'] = $this->UserModel->get_user_addresses($user_id);
        $data['wishlist'] = $this->UserModel->get_wishlist($user_id);
        $data['avis'] = $this->UserModel->get_user_reviews($user_id);
        $data['cartes'] = $this->UserModel->get_saved_cards($user_id);
        
        // Si l'utilisateur est vendeur, charger ses données spécifiques
        if ($data['is_vendeur']) {
            $data['mes_produits'] = $this->UserModel->get_seller_products($user_id);
            $data['commandes_recues'] = $this->UserModel->get_seller_orders($user_id);
            $data['stats']['total_produits'] = count($data['mes_produits']);
            $data['stats']['commandes_recues'] = count($data['commandes_recues']);
            $data['stats']['total_gains'] = $this->UserModel->get_seller_earnings($user_id);
            $data['stats']['gains_en_attente'] = $this->UserModel->get_seller_pending_earnings($user_id);
            $data['stats']['gains_disponible'] = $this->UserModel->get_seller_available_earnings($user_id);
        }


        // ========== PANIER & WISHLIST ==========
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['cartItems'] = [];
        $data['user_profils'] = [];
        
        if ($this->session->userdata('id_utilisateur')) {
            $user_id = $this->session->userdata('id_utilisateur');
            $data['cart_count'] = $this->Home_model->getCartCount($user_id);
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($user_id);
            $data['cartItems'] = $this->Home_model->getCartItems($user_id);
            $data['user_profils'] = $this->Home_model->getUserProfils($user_id);
        }
        
        // Modes de paiement
        $data['modes_paiement'] = $this->db->where('est_actif', 1)
                                           ->order_by('ordre_affichage')
                                           ->get('mode_payement')
                                           ->result_array();
        
        // Catégories pour les vendeurs
        $data['categories'] = $this->db->where('est_actif', 1)
                                       ->order_by('nom_categorie')
                                       ->get('categories')
                                       ->result_array();
        
        // Provinces pour adresse
        $data['provinces'] = $this->db->where('est_actif', 1)
                                      ->order_by('province_name')
                                      ->get('provinces')
                                      ->result_array();
        
        $this->load->view('user_dashboard_view', $data);
    }
    

    
    // ==================== MÉTHODES CLIENTS ====================
    
    /**
     * AJAX - Récupère les commandes (filtrage/pagination)
     */
    public function ajax_get_orders() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        $status = $this->input->get('status');
        $limit = $this->input->get('limit') ?: 10;
        $offset = $this->input->get('offset') ?: 0;
        
        $this->db->select('c.*, m.description as mode_paiement')
                 ->from('commandes c')
                 ->join('mode_payement m', 'c.id_mode_payement = m.id_mode_payement', 'left')
                 ->where('c.id_utilisateur', $user_id)
                 ->order_by('c.date_creation', 'DESC')
                 ->limit($limit, $offset);
        
        if ($status && $status !== 'all') {
            $this->db->where('c.statut_commande', $status);
        }
        
        $commandes = $this->db->get()->result_array();
        
        foreach ($commandes as &$commande) {
            $commande['articles'] = $this->UserModel->get_order_articles($commande['id_commande']);
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => true,
                 'data' => $commandes,
                 'total' => count($commandes)
             ]));
    }
    
    /**
     * AJAX - Récupère les détails d'une commande
     */
    public function ajax_order_detail($commande_id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        $commande = $this->db->select('c.*, m.description as mode_paiement')
                             ->from('commandes c')
                             ->join('mode_payement m', 'c.id_mode_payement = m.id_mode_payement', 'left')
                             ->where('c.id_commande', $commande_id)
                             ->where('c.id_utilisateur', $user_id)
                             ->get()
                             ->row_array();
        
        if (!$commande) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => false, 'message' => 'Commande non trouvée']));
            return;
        }
        
        $commande['articles'] = $this->UserModel->get_order_articles($commande_id);
        $commande['historique'] = $this->UserModel->get_order_status_history($commande_id);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => true, 'data' => $commande]));
    }
    
    /**
     * AJAX - Met à jour le profil
     */
    public function ajax_update_profile() {
    // if (!$this->input->is_ajax_request()) { show_404(); }
    
    $user_id = $this->session->userdata('id_utilisateur');
    
    // Gestion de l'upload de l'avatar avec votre fonction
    $avatar_url = null;
    if (!empty($_FILES['avatar']['name'])) {
        $uploaded_file = $this->upload_image($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name']);
        
        if ($uploaded_file === NULL) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode([
                     'success' => false,
                     'message' => 'Format de fichier non valide. Formats acceptés: gif, jpg, png, jpeg, webp, svg'
                 ]));
            return;
        }
        
        $avatar_url = 'attachments/Users/' . $uploaded_file;
    }
    
    // Si seul l'avatar est mis à jour (pas d'autres champs)
    if ($avatar_url && !$this->input->post('prenom') && !$this->input->post('nom')) {
        $result = $this->UserModel->update_user_profile($user_id, ['avatar_url' => $avatar_url]);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => $result,
                 'message' => $result ? 'Avatar mis à jour avec succès' : 'Erreur lors de la mise à jour',
                 'avatar_url' => base_url($avatar_url)
             ]));
        return;
    }
    
    // Mise à jour complète du profil
    $this->form_validation->set_rules('prenom', 'Prénom', 'required|max_length[100]');
    $this->form_validation->set_rules('nom', 'Nom', 'required|max_length[100]');
    $this->form_validation->set_rules('telephone', 'Téléphone', 'max_length[20]');
    
    if ($this->form_validation->run() == FALSE) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => false,
                 'message' => strip_tags(validation_errors())
             ]));
        return;
    }
    
    $data = [
        'prenom' => $this->input->post('prenom'),
        'nom' => $this->input->post('nom'),
        'telephone' => $this->input->post('telephone')
    ];
    
    if ($avatar_url) {
        $data['avatar_url'] = $avatar_url;
    }
    
    $result = $this->UserModel->update_user_profile($user_id, $data);
    
    $response = [
        'success' => $result,
        'message' => $result ? 'Profil mis à jour avec succès' : 'Erreur lors de la mise à jour'
    ];
    
    if ($avatar_url) {
        $response['avatar_url'] = $avatar_url;
    }
    
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($response));
}


    /**
     * AJAX - Change le mot de passe
     */
    public function ajax_change_password() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        $this->form_validation->set_rules('old_password', 'Ancien mot de passe', 'required');
        $this->form_validation->set_rules('new_password', 'Nouveau mot de passe', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirmation', 'required|matches[new_password]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode([
                     'success' => false,
                     'errors' => validation_errors()
                 ]));
            return;
        }
        
        $result = $this->UserModel->change_password(
            $user_id,
            $this->input->post('old_password'),
            $this->input->post('new_password')
        );
        
        if ($result === true) {
            $response = ['success' => true, 'message' => 'Mot de passe changé avec succès'];
        } else {
            $response = ['success' => false, 'message' => $result];
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
    }
    
    /**
     * AJAX - Change l'email
     */
    public function ajax_change_email() {
        if (!$this->input->is_ajax_request()) {
         show_404();
       }
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode([
                     'success' => false,
                     'errors' => validation_errors()
                 ]));
            return;
        }
        
        $result = $this->UserModel->change_email($user_id, $this->input->post('email'));
        
        if ($result === true) {
            $this->session->set_userdata('email', $this->input->post('email'));
            $response = ['success' => true, 'message' => 'Email changé avec succès'];
        } else {
            $response = ['success' => false, 'message' => $result];
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($response));
    }
    
    /**
     * AJAX - Ajoute une adresse
     */
    public function ajax_add_address() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        $this->form_validation->set_rules('nom_complet', 'Nom complet', 'required|max_length[200]');
        $this->form_validation->set_rules('telephone', 'Téléphone', 'required|max_length[20]');
        $this->form_validation->set_rules('adresse_ligne', 'Adresse', 'required|max_length[255]');
        $this->form_validation->set_rules('type_adresse', 'Type', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode([
                     'success' => false,
                     'errors' => validation_errors()
                 ]));
            return;
        }
        
        $data = [
            'type_adresse' => $this->input->post('type_adresse'),
            'est_par_defaut' => $this->input->post('est_par_defaut') ? 1 : 0,
            'nom_complet' => $this->input->post('nom_complet'),
            'telephone' => $this->input->post('telephone'),
            'id_province' => $this->input->post('id_province') ?: null,
            'adresse_ligne' => $this->input->post('adresse_ligne'),
            'point_repere' => $this->input->post('point_repere')
        ];
        
        $address_id = $this->UserModel->add_address($user_id, $data);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => (bool)$address_id,
                 'message' => $address_id ? 'Adresse ajoutée avec succès' : 'Erreur lors de l\'ajout'
             ]));
    }
    
    /**
     * AJAX - Supprime une adresse
     */
    public function ajax_delete_address($address_id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        $result = $this->UserModel->delete_address($address_id, $user_id);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => $result,
                 'message' => $result ? 'Adresse supprimée' : 'Erreur lors de la suppression'
             ]));
    }
    
    /**
     * AJAX - Récupère une adresse par son ID
     */
    public function ajax_get_address($address_id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        $address = $this->db->where('id_adresse', $address_id)
                            ->where('id_utilisateur', $user_id)
                            ->get('adresses')
                            ->row_array();
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => (bool)$address,
                 'data' => $address
             ]));
    }
    
    /**
     * AJAX - Ajoute à la wishlist
     */
    public function ajax_add_wishlist() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        $product_id = $this->input->post('product_id');
        
        if (!$product_id) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => false, 'message' => 'Produit non spécifié']));
            return;
        }
        
        $result = $this->UserModel->add_to_wishlist($user_id, $product_id);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => $result,
                 'message' => $result ? 'Ajouté à la wishlist' : 'Déjà dans la wishlist'
             ]));
    }
    
    /**
     * AJAX - Supprime de la wishlist
     */
    public function ajax_remove_wishlist() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        $product_id = $this->input->post('product_id');
        
        $result = $this->UserModel->remove_from_wishlist($user_id, $product_id);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => $result,
                 'message' => $result ? 'Retiré de la wishlist' : 'Erreur'
             ]));
    }
    
    /**
     * AJAX - Marque une notification comme lue
     */
    public function ajax_mark_notification_read() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        $notification_id = $this->input->post('notification_id');
        
        $result = $this->UserModel->mark_notification_read($notification_id, $user_id);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => $result]));
    }







    /**
 * AJAX - Changer l'avatar uniquement
 */
public function ajax_change_avatar() {
    if (empty($_FILES['avatar']['name'])) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => false,
                 'message' => 'Aucune image sélectionnée'
             ]));
        return;
    }
    
    $user_id = $this->session->userdata('id_utilisateur');
    
    // Upload avec votre fonction
    $uploaded_file = $this->upload_image($_FILES['avatar']['tmp_name'], $_FILES['avatar']['name']);
    
    if ($uploaded_file === NULL) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => false,
                 'message' => 'Format de fichier non valide. Formats acceptés: gif, jpg, png, jpeg, webp, svg'
             ]));
        return;
    }
    
    $avatar_url = 'attachments/Users/' . $uploaded_file;
    
    // Supprimer l'ancien avatar s'il existe
    $user = $this->UserModel->get_user_profile($user_id);
    if ($user && !empty($user['avatar_url']) && $user['avatar_url'] != 'attachments/Users/default-avatar.png') {
        $old_file = FCPATH . $user['avatar_url'];
        if (file_exists($old_file)) {
            @unlink($old_file);
        }
    }
    
    $result = $this->UserModel->update_user_profile($user_id, ['avatar_url' => $avatar_url]);
    
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode([
             'success' => $result,
             'message' => $result ? 'Avatar mis à jour avec succès' : 'Erreur lors de la mise à jour',
             'avatar_url' => base_url($avatar_url)
         ]));
}


    



    /**
 * AJAX - Récupère une adresse par son ID
 /**
 * AJAX - Récupère toutes les adresses de l'utilisateur
 */
public function ajax_get_addresses() {
    $user_id = $this->session->userdata('id_utilisateur');
    
    $addresses = $this->UserModel->get_user_addresses($user_id);
    
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode([
             'success' => true,
             'data' => $addresses
         ]));
}
/**
 * AJAX - Met à jour une adresse
 */
public function ajax_update_address() {
    // if (!$this->input->is_ajax_request()) { show_404(); }
    
    $user_id = $this->session->userdata('id_utilisateur');
    $address_id = $this->input->post('address_id');
    
    $this->form_validation->set_rules('nom_complet', 'Nom complet', 'required');
    $this->form_validation->set_rules('telephone', 'Téléphone', 'required');
    $this->form_validation->set_rules('adresse_ligne', 'Adresse', 'required');
    
    if ($this->form_validation->run() == FALSE) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => false,
                 'message' => strip_tags(validation_errors())
             ]));
        return;
    }
    
    $data = [
        'type_adresse' => $this->input->post('type_adresse'),
        'est_par_defaut' => $this->input->post('est_par_defaut') ? 1 : 0,
        'nom_complet' => $this->input->post('nom_complet'),
        'telephone' => $this->input->post('telephone'),
        'adresse_ligne' => $this->input->post('adresse_ligne'),
        'point_repere' => $this->input->post('point_repere')
    ];
    
    // Si cette adresse devient par défaut, désactiver les autres
    if ($data['est_par_defaut'] == 1) {
        $this->db->where('id_utilisateur', $user_id)
                 ->update('adresses', ['est_par_defaut' => 0]);
    }
    
    $result = $this->db->where('id_adresse', $address_id)
                       ->where('id_utilisateur', $user_id)
                       ->update('adresses', $data);
    
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode([
             'success' => $result,
             'message' => $result ? 'Adresse mise à jour avec succès' : 'Erreur lors de la mise à jour'
         ]));
}


/**
 * AJAX - Met à jour les informations de la boutique vendeur
 */
public function ajax_update_boutique() {
    $user_id = $this->session->userdata('id_utilisateur');
    
    if (!$this->UserModel->is_vendeur($user_id)) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => false, 'message' => 'Accès non autorisé']));
        return;
    }
    
    $vendeur = $this->db->get_where('vendeurs', ['id_utilisateur' => $user_id])->row();
    
    if (!$vendeur) {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => false, 'message' => 'Boutique non trouvée']));
        return;
    }
    
    $data = [
        'nom_boutique' => $this->input->post('nom_boutique'),
        'description' => $this->input->post('description'),
        'telephone' => $this->input->post('telephone'),
        'whatsapp' => $this->input->post('whatsapp')
    ];
    
    if (!empty($_FILES['logo_boutique']['name'])) {
        $uploaded_file = $this->upload_image($_FILES['logo_boutique']['tmp_name'], $_FILES['logo_boutique']['name']);
        if ($uploaded_file) {
            $data['logo_boutique'] = 'attachments/Users/' . $uploaded_file;
        }
    }
    
    $result = $this->db->where('id_vendeur', $vendeur->id_vendeur)->update('vendeurs', $data);
    
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode([
             'success' => $result,
             'message' => $result ? 'Boutique mise à jour avec succès' : 'Erreur lors de la mise à jour'
         ]));
}




 // Ajouter un produit
    public function add()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $nom_produit = trim($this->input->post('nom_produit'));
            $id_categorie = $this->input->post('id_categorie');
            $id_vendeur = $this->input->post('id_vendeur');
            $prix_base = $this->input->post('prix_base');
            $quantite_actuelle = $this->input->post('quantite_actuelle');
            
            // Validation
            if (empty($nom_produit) || empty($prix_base)) {
                $this->session->set_flashdata('error', 'Le nom du produit et le prix sont requis.');
                redirect(base_url('Produits/add'));
                return;
            }
            
            // Générer SKU, code produit et slug
            $sku = $this->generateSku($nom_produit);
            $code_produit = $this->generateCodeProduit();
            $slug = $this->Produit_model->generate_unique_slug($nom_produit);
            
            $date_debut_promo = $this->input->post('date_debut_promo');
            $date_fin_promo = $this->input->post('date_fin_promo');
            
            $seuil_stock_bas = $this->input->post('seuil_stock_bas') ?: 5;
            if ($quantite_actuelle <= 0) {
                $statut_stock = 'rupture_stock';
            } elseif ($quantite_actuelle <= $seuil_stock_bas) {
                $statut_stock = 'stock_bas';
            } else {
                $statut_stock = 'en_stock';
            }
            
            $data = [
                'id_vendeur' => !empty($id_vendeur) ? $id_vendeur : null,
                'id_categorie' => !empty($id_categorie) ? $id_categorie : null,
                'sku' => $sku,
                'code_produit' => $code_produit,
                'nom_produit' => $nom_produit,
                'slug_produit' => $slug,
                'description_courte' => $this->input->post('description_courte'),
                'description' => $this->input->post('description'),
                'marque' => $this->input->post('marque'),
                'prix_base' => $prix_base,
                'prix_promo' => $this->input->post('prix_promo') ?: null,
                'date_debut_promo' => !empty($date_debut_promo) ? date('Y-m-d H:i:s', strtotime($date_debut_promo)) : null,
                'date_fin_promo' => !empty($date_fin_promo) ? date('Y-m-d H:i:s', strtotime($date_fin_promo)) : null,
                'quantite_actuelle' => $quantite_actuelle ?: 0,
                'seuil_stock_bas' => $seuil_stock_bas,
                'statut_stock' => $statut_stock,
                'poids_kg' => $this->input->post('poids_kg') ?: null,
                'type_produit' => $this->input->post('type_produit') ?: 'simple',
                'statut' => $this->input->post('statut') ?: 'brouillon',
                'date_publication' => $this->input->post('statut') == 'actif' ? date('Y-m-d H:i:s') : null,
                'est_actif' => $this->input->post('est_actif') ? 1 : 0,
                'date_creation' => date('Y-m-d H:i:s')
            ];
            
            $produit_id = $this->Produit_model->add_produit($data);
            
            if ($produit_id) {
                // Gestion des images
                if (!empty($_FILES['images']['name'][0])) {
                    $this->upload_images($produit_id, $_FILES['images']);
                }

                $this->session->set_flashdata('success', 'Produit créé avec succès.');
                redirect(base_url('User_dashboard'));
            } else {
                $this->session->set_flashdata('error', 'Erreur lors de la création du produit.');
                redirect(base_url('User_dashboard'));
            }
        }
        
        // Récupérer les catégories et vendeurs pour les selects
        $data['categories'] = $this->Model->read('categories', ['est_actif' => 1], 'nom_categorie', 'ASC');
        $data['vendeurs'] = $this->db->select('v.id_vendeur, v.nom_boutique, u.prenom, u.nom')
            ->from('vendeurs v')
            ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
            ->where('v.est_approuve', 1)
            ->where('v.statut', 'actif')
            ->get()
            ->result_array();
        
        $this->load->view('produits_add_edit', $data);
    }

    /**
     * Générer SKU
     */
    private function generateSku($nom)
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nom), 0, 3));
        if (empty($prefix)) $prefix = 'PRD';
        return $prefix . '-' . date('Ymd') . '-' . rand(100, 999);
    }

    /**
     * Générer code produit
     */
    private function generateCodeProduit()
    {
        return 'PROD-' . date('Ymd') . '-' . rand(1000, 9999);
    }

    /**
     * Upload multiple d'images
     */
    private function upload_images($produit_id, $files)
    {
        $vendeur = $this->db->where('id_utilisateur', $this->session->userdata('id_utilisateur'))->get('vendeurs')->row_array();
        $vendeur_id = $vendeur ? $vendeur['id_vendeur'] : 0;
        $ref_folder = FCPATH . 'uploads/produits/' . $vendeur_id . '/';
        
        if (!is_dir($ref_folder)) {
            mkdir($ref_folder, 0777, TRUE);
        }
        
        $existing_images = $this->Produit_model->get_images_by_produit_id($produit_id);
        $ordre_actuel = count($existing_images);
        $is_first_upload = ($ordre_actuel == 0);
        
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] == 0) {
                $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $filename = date("YmdHis") . '_' . uniqid() . '.' . strtolower($ext);
                
                if (move_uploaded_file($files['tmp_name'][$i], $ref_folder . $filename)) {
                    $est_principale = 0;
                    if ($is_first_upload && $i == 0) {
                        $est_principale = 1;
                    }
                    
                    $img_data = [
                        'id_produit' => $produit_id,
                        'url_image' => 'uploads/produits/' . $vendeur_id . '/' . $filename,
                        'est_principale' => $est_principale,
                        'ordre_affichage' => $ordre_actuel + $i + 1
                    ];
                    $this->db->insert('images_produit', $img_data);
                }
            }
        }
    }



    // ==================== MÉTHODES VENDEUR ====================
    
    /**
     * AJAX - Ajouter un produit
     */
    public function ajax_add_product() {
       
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        if (!$this->UserModel->is_vendeur($user_id)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => false, 'message' => 'Accès non autorisé']));
            return;
        }
        
        $this->form_validation->set_rules('nom_produit', 'Nom du produit', 'required');
        $this->form_validation->set_rules('prix_base', 'Prix', 'required|numeric');
        $this->form_validation->set_rules('quantite_actuelle', 'Quantité', 'required|numeric');
        $this->form_validation->set_rules('id_categorie', 'Catégorie', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => false, 'message' => validation_errors()]));
            return;
        }
        
        // Upload de l'image
        $main_image = 'default-product.png';
        if (!empty($_FILES['main_image']['name'])) {
            $config['upload_path'] = './uploads/produits/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|webp';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = true;
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('main_image')) {
                $upload_data = $this->upload->data();
                $main_image = $upload_data['file_name'];
            }
        }
        
        $data = [
            'id_categorie' => $this->input->post('id_categorie'),
            'nom_produit' => $this->input->post('nom_produit'),
            'description_courte' => $this->input->post('description_courte'),
            'description' => $this->input->post('description'),
            'marque' => $this->input->post('marque'),
            'prix_base' => $this->input->post('prix_base'),
            'prix_promo' => $this->input->post('prix_promo') ?: null,
            'quantite_actuelle' => $this->input->post('quantite_actuelle')
        ];
        
        $product_id = $this->UserModel->add_product($user_id, $data);
        
        // Sauvegarder l'image principale dans images_produit
        if ($product_id && $main_image !== 'default-product.png') {
            $this->db->insert('images_produit', [
                'id_produit' => $product_id,
                'url_image' => 'uploads/produits/' . $main_image,
                'est_principale' => 1,
                'ordre_affichage' => 1
            ]);
        }
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => (bool)$product_id,
                 'message' => $product_id ? 'Produit ajouté avec succès' : 'Erreur lors de l\'ajout'
             ]));
    }




    
    /**
     * AJAX - Mettre à jour le statut d'une commande (vendeur)
     */
    public function ajax_update_order_status() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        if (!$this->UserModel->is_vendeur($user_id)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => false, 'message' => 'Accès non autorisé']));
            return;
        }
        
        $json = json_decode(file_get_contents('php://input'), true);
        $order_id = $json['order_id'] ?? $this->input->post('order_id');
        $status = $json['status'] ?? $this->input->post('status');
        
        $result = $this->UserModel->update_order_status($order_id, $status);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => $result,
                 'message' => $result ? 'Statut mis à jour' : 'Erreur'
             ]));
    }
    
    /**
     * AJAX - Mettre à jour le stock (vendeur)
     */
    public function ajax_update_stock() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        if (!$this->UserModel->is_vendeur($user_id)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => false, 'message' => 'Accès non autorisé']));
            return;
        }
        
        $json = json_decode(file_get_contents('php://input'), true);
        $product_id = $json['product_id'] ?? $this->input->post('product_id');
        $quantity = $json['quantity'] ?? $this->input->post('quantity');
        
        $result = $this->UserModel->update_product_stock($product_id, $user_id, $quantity);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => $result,
                 'message' => $result ? 'Stock mis à jour' : 'Erreur'
             ]));
    }
    
    /**
     * AJAX - Supprimer un produit (vendeur)
     */
    public function ajax_delete_product() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        $product_id = $this->input->post('product_id');
        
        if (!$this->UserModel->is_vendeur($user_id)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => false, 'message' => 'Accès non autorisé']));
            return;
        }
        
        $result = $this->UserModel->delete_product($product_id, $user_id);
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode([
                 'success' => $result,
                 'message' => $result ? 'Produit supprimé' : 'Erreur'
             ]));
    }
    
    /**
     * AJAX - Récupérer les statistiques du vendeur
     */
    public function ajax_get_seller_stats() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('id_utilisateur');
        
        if (!$this->UserModel->is_vendeur($user_id)) {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['success' => false, 'message' => 'Accès non autorisé']));
            return;
        }
        
        $stats = [
            'total_produits' => count($this->UserModel->get_seller_products($user_id)),
            'commandes_recues' => count($this->UserModel->get_seller_orders($user_id)),
            'total_gains' => $this->UserModel->get_seller_earnings($user_id),
            'gains_en_attente' => $this->UserModel->get_seller_pending_earnings($user_id),
            'gains_disponible' => $this->UserModel->get_seller_available_earnings($user_id)
        ];
        
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(['success' => true, 'data' => $stats]));
    }
    
    /**
     * Déconnexion
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }


    











    // ============================================
// PAGE DE COMPLÉTION DU PROFIL VENDEUR
// ============================================

public function complete_profile() {
    $user_id = $this->session->userdata('id_utilisateur');
    
    if (!$user_id) {
        redirect(base_url('auth/login'));
    }
    
    // Vérifier si l'utilisateur a déjà un profil vendeur
    $existing_seller = $this->Model->readOne('vendeurs', ['id_utilisateur' => $user_id]);
    if ($existing_seller) {
        redirect(base_url('Sellers'));
    }
    
    // Récupérer les informations de l'utilisateur
    $user = $this->db->get_where('utilisateurs', ['id_utilisateur' => $user_id])->row_array();
    
    // Charger les provinces
    $data['provinces'] = $this->db->select('id_province, province_name')
        ->where('est_actif', 1)
        ->order_by('province_name')
        ->get('provinces')
        ->result_array();
    
    $data['user'] = $user;
    $this->load->view('seller_complete_profile', $data);
}

// ============================================
// SAUVEGARDER LE PROFIL VENDEUR COMPLET
// ============================================
// ============================================
// SAUVEGARDER LE PROFIL VENDEUR COMPLET
// ============================================

public function save_complete_profile() {
    $this->output->set_content_type('application/json');
    
    $user_id = $this->session->userdata('id_utilisateur');
    
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
        return;
    }
    
    $nom_boutique = trim($this->input->post('nom_boutique'));
    $description = trim($this->input->post('description'));
    $type_vendeur = $this->input->post('type_vendeur');
    $nom_entreprise = trim($this->input->post('nom_entreprise'));
    $numero_nif = trim($this->input->post('numero_nif'));
    $numero_rc = trim($this->input->post('numero_rc'));
    $whatsapp = trim($this->input->post('whatsapp'));
    
    // Informations de localisation
    $id_province = $this->input->post('id_province') ?: null;
    $id_commune = $this->input->post('id_commune') ?: null;
    $id_quartier = $this->input->post('id_quartier') ?: null;
    $latitude = $this->input->post('latitude') ?: null;
    $longitude = $this->input->post('longitude') ?: null;
    
    // Configuration de paiement
    $methode_paiement = $this->input->post('methode_paiement');
    $operateur_mobile = $this->input->post('operateur_mobile');
    $numero_mobile_money = $this->input->post('numero_mobile_money');
    $nom_abonne_mobile = $this->input->post('nom_abonne_mobile');
    $nom_banque = $this->input->post('nom_banque');
    $numero_compte = $this->input->post('numero_compte');
    $nom_titulaire = $this->input->post('nom_titulaire');
    
    if (empty($nom_boutique)) {
        echo json_encode(['success' => false, 'message' => 'Le nom de la boutique est obligatoire']);
        return;
    }
    
    $slug = $this->createSlug($nom_boutique);
    
    // Vérifier si le slug existe déjà
    $existing = $this->db->get_where('vendeurs', ['slug_boutique' => $slug])->row();
    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'Ce nom de boutique existe déjà']);
        return;
    }
    
    // Gestion du logo
    $logo_boutique = null;
    if (!empty($_FILES['logo_boutique']['name'])) {
        $upload_logo = $this->upload_image($_FILES['logo_boutique']['tmp_name'], $_FILES['logo_boutique']['name']);
        if ($upload_logo) {
            $logo_boutique = 'attachments/Users/' . $upload_logo;
        }
    }
    
    $user = $this->db->get_where('utilisateurs', ['id_utilisateur' => $user_id])->row();
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
        return;
    }
    
    $vendeur_data = [
        'id_utilisateur' => $user_id,
        'nom_boutique' => $nom_boutique,
        'slug_boutique' => $slug,
        'logo_boutique' => $logo_boutique,
        'description' => $description,
        'type_vendeur' => $type_vendeur,
        'nom_entreprise' => !empty($nom_entreprise) ? $nom_entreprise : null,
        'numero_nif' => !empty($numero_nif) ? $numero_nif : null,
        'numero_rc' => !empty($numero_rc) ? $numero_rc : null,
        'id_province' => $id_province,
        'id_commune' => $id_commune,
        'id_quartier' => $id_quartier,
        'latitude' => !empty($latitude) ? $latitude : null,
        'longitude' => !empty($longitude) ? $longitude : null,
        'telephone' => $user->telephone,
        'whatsapp' => !empty($whatsapp) ? $whatsapp : $user->telephone,
        'taux_commission' => 10,
        'delai_paiement_jours' => 7,
        'est_approuve' => 0,
        'statut' => 'en_attente',
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    // Insertion du vendeur
    $this->db->insert('vendeurs', $vendeur_data);
    $vendeur_id = $this->db->insert_id();
    
    if ($vendeur_id) {
        // Créer le solde initial
        $this->db->insert('soldes_vendeurs', [
            'id_vendeur' => $vendeur_id,
            'solde_disponible' => 0,
            'solde_en_attente' => 0,
            'total_gagne' => 0,
            'total_retire' => 0
        ]);
        
        // Créer la configuration de paiement
        $config_paiement = [
            'id_vendeur' => $vendeur_id,
            'methode_principale' => $methode_paiement,
            'est_verifie' => 0,
            'est_actif' => 1,
            'date_creation' => date('Y-m-d H:i:s')
        ];
        
        if ($methode_paiement == 'mobile_money') {
            $config_paiement['operateur_mobile'] = $operateur_mobile;
            $config_paiement['numero_mobile_money'] = $numero_mobile_money;
            $config_paiement['nom_abonne_mobile'] = $nom_abonne_mobile;
        } else {
            $config_paiement['nom_banque'] = $nom_banque;
            $config_paiement['numero_compte'] = $numero_compte;
            $config_paiement['nom_titulaire'] = $nom_titulaire;
        }
        
        $this->db->insert('config_paiement_vendeur', $config_paiement);
        
        // Ajouter le profil vendeur à l'utilisateur
        $existing_profile = $this->db->get_where('utilisateur_profils', [
            'id_utilisateur' => $user_id,
            'id_profil' => 4
        ])->row();
        
        if (!$existing_profile) {
            $this->db->insert('utilisateur_profils', [
                'id_utilisateur' => $user_id,
                'id_profil' => 4,
                'attribue_par' => $user_id,
                'date_attribution' => date('Y-m-d H:i:s')
            ]);
        }
        
        // Mettre à jour la session avec le rôle vendeur
        $this->session->set_userdata('role', 'vendeur');
        $this->session->set_userdata('id_profil', 4);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Boutique créée avec succès ! Votre demande est en attente de validation.',
            'redirect_url' => base_url('User_dashboard')
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création de la boutique']);
    }
}

// Fonction pour créer un slug
private function createSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}



// Upload d'image
public function upload_image($nom_file, $nom_champ) {
    $ref_folder = FCPATH . 'attachments/Users/';
    
    // Vérifier et créer le dossier s'il n'existe pas
    if (!is_dir($ref_folder)) {
        mkdir($ref_folder, 0777, TRUE);
    }
    
    $code = date("YmdHis") . uniqid();
    $file_extension = pathinfo($nom_champ, PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension);
    $valid_ext = array('gif', 'jpg', 'png', 'jpeg', 'webp', 'svg');
    
    if (!in_array($file_extension, $valid_ext)) {
        return NULL;
    }
    
    $new_filename = $code . "." . $file_extension;
    move_uploaded_file($nom_file, $ref_folder . $new_filename);
    
    return $new_filename;
}




// Obtenir les communes par province (AJAX)
    public function get_communes()
    {
        $this->output->set_content_type('application/json');
        $id_province = $this->input->post('id_province');
        if ($id_province) {
            $communes = $this->db->select('id_commune, commune_name')
                ->where('id_province', $id_province)
                ->where('est_actif', 1)
                ->order_by('commune_name')
                ->get('communes')
                ->result_array();
            echo json_encode($communes);
        } else {
            echo json_encode([]);
        }
    }

    // Obtenir les quartiers par commune (AJAX)
    public function get_quartiers()
    {
        $this->output->set_content_type('application/json');
        $id_commune = $this->input->post('id_commune');
        if ($id_commune) {
            $quartiers = $this->db->select('id_quartier, quartier_name')
                ->where('id_commune', $id_commune)
                ->where('est_actif', 1)
                ->order_by('quartier_name')
                ->get('quartiers')
                ->result_array();
            echo json_encode($quartiers);
        } else {
            echo json_encode([]);
        }
    }

}
?>