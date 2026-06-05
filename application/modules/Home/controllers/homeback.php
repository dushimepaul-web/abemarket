<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Home_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
    }
    
    /**
     * Affiche la page d'accueil
     */
    public function index() {
        // Récupérer les paramètres du site
        $data['settings'] = $this->Home_model->getSiteSettings();
        
        // Récupérer les catégories pour le menu
        $data['categories_hierarchy'] = $this->Home_model->getCategoriesHierarchy();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        
        // ========== BANNIÈRES ==========
        $data['slider_banners'] = $this->Home_model->getSliderBanners();
        $data['bottom_banners'] = $this->Home_model->getBottomBanners();
        
        // ========== PRODUITS ==========
        $data['flashSaleProducts'] = $this->Home_model->getFlashSaleProducts(12);
        $data['topSellingProducts'] = $this->Home_model->getTopSellingProducts(10);
        $data['newProducts'] = $this->Home_model->getNewProducts(8);
        $data['trendingProducts'] = $this->Home_model->getTrendingProducts(8);
        $data['recommendedProducts'] = $this->Home_model->getRecommendedProducts(10);
        
        // ========== PRODUITS PAR CATÉGORIE (pour les sections spécifiques) ==========
        // Catégories: 3=Maison & Cuisine, 1=Électronique, 8=Téléphones
        $data['categoryProducts'] = $this->Home_model->getHomeCategoriesWithProducts([3, 1, 8], 5);
        
        // ========== VENDEURS ==========
        $data['featuredSellers'] = $this->Home_model->getFeaturedSellers(6);
        
        // ========== AVIS / TÉMOIGNAGES ==========
        $data['testimonials'] = $this->Home_model->getHomeTestimonials(6);
        
        // ========== STATISTIQUES ==========
        $data['homeStats'] = $this->Home_model->getHomeStats();
        
        // ========== MARQUES POPULAIRES ==========
        $data['popularBrands'] = $this->Home_model->getPopularBrands(8);
        
        // ========== BLOG (désactivé - tables blog_posts n'existent pas) ==========
        $data['recentPosts'] = [];
        
        // ========== COUPONS ==========
        $data['activeCoupons'] = $this->Home_model->getActiveCoupons(3);
        
        // ========== PANIER & WISHLIST ==========
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['cartItems'] = [];
        $data['user_profils'] = [];
        
        if ($this->session->userdata('user_id')) {
            $user_id = $this->session->userdata('user_id');
            $data['cart_count'] = $this->Home_model->getCartCount($user_id);
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($user_id);
            $data['cartItems'] = $this->Home_model->getCartItems($user_id);
            $data['user_profils'] = $this->Home_model->getUserProfils($user_id);
        }
        
        $data['meta_title'] = isset($data['settings']['site_name']) ? $data['settings']['site_name'] : 'AbeMarket';
        $data['meta_description'] = 'Achetez en ligne les meilleurs produits à prix imbattables. Livraison rapide au Burundi.';
        
        $this->render('home_view', $data);
    }



















// ========== PAGE CATÉGORIE (pour afficher les produits par catégorie) ==========

/**
 * Affiche les produits d'une catégorie spécifique
 * URL: /category/{slug}
 */
public function category($slug) {
    $category = $this->Home_model->getCategoryBySlug($slug);
    
    if (!$category) {
        show_404();
        return;
    }
    
    $page = (int)$this->input->get('page', TRUE) ?: 1;
    $sort = $this->input->get('sort', TRUE) ?: 'newest';
    $perPage = 12;
    $offset = ($page - 1) * $perPage;
    
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    $data['category'] = $category;
    $data['currentSort'] = $sort;
    $data['currentPage'] = $page;
    
    // Récupérer les produits de la catégorie (y compris sous-catégories)
    $data['products'] = $this->Home_model->getProductsPaginated($category['id_categorie'], $sort, $perPage, $offset);
    $data['totalProducts'] = $this->Home_model->countAllProducts($category['id_categorie']);
    $data['totalPages'] = ceil($data['totalProducts'] / $perPage);
    
    // Récupérer les sous-catégories pour le filtre
    $data['subcategories'] = $this->Home_model->getSubCategories($category['id_categorie']);
    
    if ($this->session->userdata('user_id')) {
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
    } else {
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['user_profils'] = [];
    }
    
    $data['meta_title'] = $category['nom_categorie'] . ' - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $data['meta_description'] = $category['description'] ?? 'Découvrez notre sélection de ' . $category['nom_categorie'];
    
    $this->render('category_view', $data);
}







/**
 * Affiche les détails d'un produit
 */
public function product($slug) {
    $product = $this->Home_model->getProductBySlug($slug);
    
    if (!$product) {
        show_404();
        return;
    }
    
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    $data['categories_hierarchy'] = $this->Home_model->getCategoriesHierarchy();
    
    // Incrémenter le compteur de vues
    $this->Home_model->incrementProductViews($product['id_produit']);
    
    // Récupérer les données associées
    $data['product'] = $product;
    $data['images'] = $this->Home_model->getProductImages($product['id_produit']);
    $data['variants'] = $this->Home_model->getProductVariants($product['id_produit']);
    $data['reviews'] = $this->Home_model->getProductReviews($product['id_produit']);
    $data['similarProducts'] = $this->Home_model->getSimilarProducts($product['id_categorie'], $product['id_produit']);
    
    // Statistiques des avis
    $data['totalReviews'] = $this->Home_model->countProductReviews($product['id_produit']);
    $data['averageRating'] = $this->Home_model->getProductAverageRating($product['id_produit']);
    $data['ratingDistribution'] = $this->Home_model->getProductRatingDistribution($product['id_produit']);
    
    // Vendeur
    $data['seller'] = $this->Home_model->getSellerDetails($product['id_vendeur']);
    
    // Vérifier si l'utilisateur peut laisser un avis
    $user_id = $this->session->userdata('user_id');
    $data['hasPurchased'] = $this->Home_model->hasUserPurchasedProduct($user_id, $product['id_produit']);
    $data['hasReviewed'] = $this->Home_model->hasUserReviewedProduct($user_id, $product['id_produit']);
    
    // Produits récemment consultés (session)
    $recentProducts = $this->session->userdata('recent_products') ?: [];
    if (!in_array($product['id_produit'], $recentProducts)) {
        array_unshift($recentProducts, $product['id_produit']);
        $recentProducts = array_slice($recentProducts, 0, 5);
        $this->session->set_userdata('recent_products', $recentProducts);
    }
    $data['recentProducts'] = $this->Home_model->getRecentProducts($recentProducts);
    
    // Panier et wishlist
    $data['cart_count'] = 0;
    $data['wishlist_count'] = 0;
    $data['cartItems'] = [];
    $data['user_profils'] = [];
    
    if ($user_id) {
        $data['cart_count'] = $this->Home_model->getCartCount($user_id);
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($user_id);
        $data['cartItems'] = $this->Home_model->getCartItems($user_id);
        $data['user_profils'] = $this->Home_model->getUserProfils($user_id);
        $data['in_wishlist'] = $this->Home_model->isInWishlist($user_id, $product['id_produit']);
    } else {
        $data['in_wishlist'] = false;
    }
    
    $data['meta_title'] = $product['nom_produit'] . ' - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $this->render('product_view', $data);
}







// ========== PAGE TABLEAU DE BORD UTILISATEUR ==========

/**
 * Tableau de bord utilisateur
 */
public function user_dashboard() {
    if (!$this->session->userdata('user_id')) {
        redirect('auth/login');
        return;
    }
    
    $user_id = $this->session->userdata('user_id');
    
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    
    // Informations utilisateur
    $data['user'] = $this->db->get_where('utilisateurs', ['id_utilisateur' => $user_id])->row_array();
    
    // Commandes récentes
    $data['recent_orders'] = $this->Home_model->getUserOrders($user_id, 5);
    
    // Adresses
    $data['addresses'] = $this->db->get_where('adresses', ['id_utilisateur' => $user_id, 'est_actif' => 1])->result_array();
    
    // Wishlist
    $data['wishlist_count'] = $this->Home_model->getWishlistCount($user_id);
    
    // Notifications non lues
    $data['notifications_count'] = $this->db->where('id_utilisateur', $user_id)
                                           ->where('est_lue', 0)
                                           ->count_all_results('notifications');
    
    $data['cart_count'] = $this->Home_model->getCartCount($user_id);
    $data['user_profils'] = $this->Home_model->getUserProfils($user_id);
    
    $data['meta_title'] = 'Mon tableau de bord - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $this->render('user_dashboard_view', $data);
}

// ========== MÉTHODES AJAX POUR LE DASHBOARD ==========

/**
 * Changement d'email (AJAX)
 */
public function ajax_change_email() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        return;
    }
    
    $new_email = $this->input->post('email', TRUE);
    $user_id = $this->session->userdata('user_id');
    
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email invalide']);
        return;
    }
    
    // Vérifier si email déjà utilisé
    $exists = $this->db->where('email', $new_email)
                       ->where('id_utilisateur !=', $user_id)
                       ->get('utilisateurs')
                       ->num_rows();
    
    if ($exists > 0) {
        echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé']);
        return;
    }
    
    $this->db->where('id_utilisateur', $user_id);
    $result = $this->db->update('utilisateurs', ['email' => $new_email, 'email_verifie' => 0]);
    
    echo json_encode(['success' => $result, 'message' => $result ? 'Email mis à jour' : 'Erreur']);
}

/**
 * Changement de mot de passe (AJAX)
 */
public function ajax_change_password() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        return;
    }
    
    $old_password = $this->input->post('old_password', TRUE);
    $new_password = $this->input->post('new_password', TRUE);
    $confirm_password = $this->input->post('confirm_password', TRUE);
    $user_id = $this->session->userdata('user_id');
    
    // Vérifier ancien mot de passe
    $user = $this->db->select('mot_de_passe')
                     ->where('id_utilisateur', $user_id)
                     ->get('utilisateurs')
                     ->row_array();
    
    $password_correct = false;
    if (password_verify($old_password, $user['mot_de_passe'])) {
        $password_correct = true;
    } elseif (strlen($user['mot_de_passe']) === 32 && md5($old_password) === $user['mot_de_passe']) {
        $password_correct = true;
    }
    
    if (!$password_correct) {
        echo json_encode(['success' => false, 'message' => 'Ancien mot de passe incorrect']);
        return;
    }
    
    if (strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Le nouveau mot de passe doit avoir au moins 6 caractères']);
        return;
    }
    
    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas']);
        return;
    }
    
    $result = $this->db->where('id_utilisateur', $user_id)
                       ->update('utilisateurs', ['mot_de_passe' => password_hash($new_password, PASSWORD_BCRYPT)]);
    
    echo json_encode(['success' => $result, 'message' => $result ? 'Mot de passe mis à jour' : 'Erreur']);
}

/**
 * Mise à jour du profil (AJAX)
 */
public function ajax_update_profile() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        return;
    }
    
    $user_id = $this->session->userdata('user_id');
    $data = [
        'prenom' => $this->input->post('prenom', TRUE),
        'nom' => $this->input->post('nom', TRUE),
        'telephone' => $this->input->post('telephone', TRUE)
    ];
    
    $result = $this->db->where('id_utilisateur', $user_id)->update('utilisateurs', $data);
    
    echo json_encode(['success' => $result, 'message' => $result ? 'Profil mis à jour' : 'Erreur']);
}

/**
 * Ajout d'adresse (AJAX)
 */
public function ajax_add_address() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        return;
    }
    
    $user_id = $this->session->userdata('user_id');
    
    $data = [
        'id_utilisateur' => $user_id,
        'type_adresse' => $this->input->post('type_adresse', TRUE),
        'nom_complet' => $this->input->post('nom_complet', TRUE),
        'telephone' => $this->input->post('telephone', TRUE),
        'id_province' => $this->input->post('province', TRUE),
        'id_commune' => $this->input->post('commune', TRUE),
        'adresse_ligne' => $this->input->post('adresse', TRUE),
        'point_repere' => $this->input->post('point_repere', TRUE),
        'est_par_defaut' => $this->input->post('est_par_defaut', TRUE) ? 1 : 0
    ];
    
    // Si c'est l'adresse par défaut, désactiver les autres
    if ($data['est_par_defaut'] == 1) {
        $this->db->where('id_utilisateur', $user_id)
                 ->update('adresses', ['est_par_defaut' => 0]);
    }
    
    $result = $this->db->insert('adresses', $data);
    
    echo json_encode([
        'success' => $result, 
        'message' => $result ? 'Adresse ajoutée' : 'Erreur',
        'address_id' => $result ? $this->db->insert_id() : null
    ]);
}

/**
 * Suppression d'adresse (AJAX)
 */
public function ajax_delete_address($id) {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        return;
    }
    
    $user_id = $this->session->userdata('user_id');
    
    $result = $this->db->where('id_adresse', $id)
                       ->where('id_utilisateur', $user_id)
                       ->delete('adresses');
    
    echo json_encode(['success' => $result, 'message' => $result ? 'Adresse supprimée' : 'Erreur']);
}

/**
 * Récupération des communes par province (AJAX)
 */
public function get_communes() {
    $province_id = $this->input->post('province_id');
    if (!$province_id) {
        echo json_encode([]);
        return;
    }
    
    $communes = $this->db->select('id_commune, commune_name as nom')
                         ->where('id_province', $province_id)
                         ->where('est_actif', 1)
                         ->get('communes')
                         ->result_array();
    
    echo json_encode($communes);
}

/**
 * Récupération des quartiers par commune (AJAX)
 */
public function get_quartiers() {
    $commune_id = $this->input->post('commune_id');
    if (!$commune_id) {
        echo json_encode([]);
        return;
    }
    
    $quartiers = $this->db->select('id_quartier, quartier_name as nom')
                          ->where('id_commune', $commune_id)
                          ->where('est_actif', 1)
                          ->get('quartiers')
                          ->result_array();
    
    echo json_encode($quartiers);
}


    /**
     * API pour récupérer les sous-catégories d'une catégorie (AJAX)
     */
    public function getSubCategories() {
        $category_id = $this->input->post('category_id');
        if (!$category_id) {
            echo json_encode(['error' => 'No category ID']);
            return;
        }
        
        $subcategories = $this->Home_model->getSubCategoriesByParent($category_id);
        echo json_encode($subcategories);
    }
    
    /**
     * API pour récupérer toutes les catégories en JSON (pour mobile menu)
     */
    public function getAllCategoriesJson() {
        $categories = $this->Home_model->getAllCategoriesWithSub();
        header('Content-Type: application/json');
        echo json_encode($categories);
    }
    
    /**
     * API pour récupérer les bannières en JSON
     */
    public function getBannersJson() {
        $banners = $this->Home_model->getSliderBanners();
        header('Content-Type: application/json');
        echo json_encode($banners);
    }

  
    /**
     * Affiche la page À propos
     */
    public function about() {
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    $data['categories_hierarchy'] = $this->Home_model->getCategoriesHierarchy();
    
    // Récupérer le contenu dynamique
    $data['about_content'] = $this->Home_model->getAboutContent();
    $data['team_members'] = $this->Home_model->getTeamMembers();
    $data['testimonials'] = $this->Home_model->getTestimonials();
    
    // Gestion du panier
    if ($this->session->userdata('user_id')) {
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
    } else {
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['user_profils'] = [];
    }
    
    $data['meta_title'] = 'À propos - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $this->render('about_view', $data);
}
    
    /**
     * Affiche la page Blog
     */
    public function blog() {
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['articles'] = $this->Home_model->getBlogPosts(10);
        
        if ($this->session->userdata('user_id')) {
            $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
            $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        } else {
            $data['cart_count'] = 0;
            $data['wishlist_count'] = 0;
            $data['user_profils'] = [];
        }
        
        $data['meta_title'] = 'Blog - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('blog_view', $data);
    }
    
    /**
     * Affiche la page Promotions / Offres
     */
    public function offres() {
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['promotions'] = $this->Home_model->getFlashSaleProducts(20);
        
        if ($this->session->userdata('user_id')) {
            $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
            $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        } else {
            $data['cart_count'] = 0;
            $data['wishlist_count'] = 0;
            $data['user_profils'] = [];
        }
        
        $data['meta_title'] = 'Promotions - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('promotions_view', $data);
    }
    
    /**
     * Affiche la page FAQ
     */
    public function faq() {
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['faqs'] = $this->Home_model->getFaqs();
        
        if ($this->session->userdata('user_id')) {
            $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
            $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        } else {
            $data['cart_count'] = 0;
            $data['wishlist_count'] = 0;
            $data['user_profils'] = [];
        }
        
        $data['meta_title'] = 'FAQ - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('faq_view', $data);
    }
    
    /**
     * Affiche la page Contact
     */
    public function contact() {
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    $data['categories_hierarchy'] = $this->Home_model->getCategoriesHierarchy();
    
    // Récupérer les informations de contact dynamiques
    $data['contact_info'] = $this->Home_model->getContactInfo();
    $data['contact_sujets'] = $this->Home_model->getContactSujets();
    
    // Gestion du panier
    if ($this->session->userdata('user_id')) {
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
    } else {
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['user_profils'] = [];
    }
    
    // Traitement du formulaire de contact
    if ($this->input->post('submit')) {
        $this->form_validation->set_rules('name', 'Nom', 'required|min_length[2]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Téléphone', 'required|min_length[8]');
        $this->form_validation->set_rules('topic', 'Sujet', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required|min_length[10]');
        
        if ($this->form_validation->run() == TRUE) {
            $message_data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'topic' => $this->input->post('topic'),
                'message' => $this->input->post('message')
            ];
            
            $result = $this->Home_model->saveContactMessage($message_data);
            
            if ($result) {
                $this->session->set_flashdata('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
            } else {
                $this->session->set_flashdata('error', 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer ou nous contacter directement par téléphone.');
            }
            redirect('home/contact');
        } else {
            // Conserver les données saisies
            $data['old_input'] = $this->input->post();
            $this->session->set_flashdata('error', validation_errors());
        }
    }
    
    $data['meta_title'] = 'Contact - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $this->render('contact_view', $data);
}


    
    /**
     * Page de recherche
     */
    public function search() {
        $keyword = $this->input->get('q', TRUE);
        
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['keyword'] = $keyword;
        
        if (!empty($keyword)) {
            $data['products'] = $this->Home_model->searchProducts($keyword, 30);
        } else {
            $data['products'] = [];
        }
        
        if ($this->session->userdata('user_id')) {
            $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
            $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        } else {
            $data['cart_count'] = 0;
            $data['wishlist_count'] = 0;
            $data['user_profils'] = [];
        }
        
        $data['meta_title'] = 'Recherche : ' . $keyword . ' - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('search_view', $data);
    }
    
    /**
     * Page de suivi de commande
     */
    public function order_tracking() {
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
            return;
        }
        
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['orders'] = $this->Home_model->getUserOrders($this->session->userdata('user_id'));
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        
        $data['meta_title'] = 'Suivi de commande - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('order_tracking_view', $data);
    }
    
    /**
     * Recherche AJAX pour l'autocomplétion
     */
    public function ajaxSearch() {
        $keyword = $this->input->get('q', TRUE);
        if (strlen($keyword) < 2) {
            echo json_encode([]);
            return;
        }
        
        $products = $this->Home_model->searchProducts($keyword, 10);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($products));
    }
    
    /**
     * Affiche les produits par catégorie (page shop)
     */
    /**
 * Affiche la page boutique (shop)
 */
public function shop() {
    // Récupérer les paramètres GET
    $categoryId = $this->input->get('category', TRUE);
    $page = (int)$this->input->get('page', TRUE) ?: 1;
    $sort = $this->input->get('sort', TRUE) ?: 'newest';
    $min_price = $this->input->get('min_price', TRUE);
    $max_price = $this->input->get('max_price', TRUE);
    $brands = $this->input->get('brands', TRUE);
    $rating = $this->input->get('rating', TRUE);
    
    $perPage = 12;
    $offset = ($page - 1) * $perPage;
    
    // Construire les filtres
    $filters = [
        'category_id' => $categoryId,
        'min_price' => $min_price,
        'max_price' => $max_price,
        'brands' => $brands,
        'rating' => $rating
    ];
    
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    
    // Récupérer les catégories pour le filtre
    $data['categories'] = $this->Home_model->getMainCategories();
    $data['currentCategory'] = $categoryId;
    $data['currentSort'] = $sort;
    $data['currentPage'] = $page;
    
    // Récupérer les filtres disponibles (prix min/max, marques)
    $data['filters'] = $this->Home_model->getShopFilters();
    $data['min_price'] = $min_price ?: $data['filters']['min_price'];
    $data['max_price'] = $max_price ?: $data['filters']['max_price'];
    $data['selected_brands'] = $brands ? explode(',', $brands) : [];
    $data['selected_rating'] = $rating;
    
    // Récupérer les produits avec pagination et filtres
    $data['products'] = $this->Home_model->getProductsWithFilters($filters, $sort, $perPage, $offset);
    $data['totalProducts'] = $this->Home_model->countProductsWithFilters($filters);
    $data['totalPages'] = ceil($data['totalProducts'] / $perPage);
    
    // Panier et wishlist
    if ($this->session->userdata('user_id')) {
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
    } else {
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['user_profils'] = [];
    }
    
    $data['meta_title'] = 'Boutique - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $this->render('shop_view', $data);
}
    
    /**
     * Affiche les détails d'un produit
     */
   
    /**
     * Affiche la page des vendeurs
     */
    public function sellers() {
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['sellers'] = $this->Home_model->getAllSellers();
        
        if ($this->session->userdata('user_id')) {
            $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
            $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        } else {
            $data['cart_count'] = 0;
            $data['wishlist_count'] = 0;
            $data['user_profils'] = [];
        }
        
        $data['meta_title'] = 'Vendeurs - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('sellers_view', $data);
    }
    
    /**
     * Affiche la page d'un vendeur spécifique
     */
    public function seller($slug) {
        $seller = $this->Home_model->getSellerBySlug($slug);
        
        if (!$seller) {
            show_404();
            return;
        }
        
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        
        // Récupérer les produits du vendeur
        $data['seller'] = $seller;
        $data['products'] = $this->Home_model->getSellerProducts($seller['id_vendeur']);
        
        if ($this->session->userdata('user_id')) {
            $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
            $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        } else {
            $data['cart_count'] = 0;
            $data['wishlist_count'] = 0;
            $data['user_profils'] = [];
        }
        
        $data['meta_title'] = $seller['nom_boutique'] . ' - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('seller_view', $data);
    }
    
    /**
     * Ajoute un produit au panier (AJAX)
     */
    public function addToCart() {
        if (!$this->session->userdata('user_id')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Veuillez vous connecter']));
            return;
        }
        
        $productId = $this->input->post('product_id', TRUE);
        $variantId = $this->input->post('variant_id', TRUE) ?: null;
        $quantity = (int)$this->input->post('quantity', TRUE) ?: 1;
        
        $result = $this->Home_model->addToCart(
            $this->session->userdata('user_id'),
            $productId,
            $variantId,
            $quantity
        );
        
        $cartCount = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'cart_count' => $cartCount,
                'message' => $result ? 'Produit ajouté au panier' : 'Erreur lors de l\'ajout'
            ]));
    }
    
    /**
     * Met à jour la quantité dans le panier (AJAX)
     */
    public function updateCart() {
        if (!$this->session->userdata('user_id')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Veuillez vous connecter']));
            return;
        }
        
        $cartId = $this->input->post('cart_id', TRUE);
        $quantity = (int)$this->input->post('quantity', TRUE);
        
        $result = $this->Home_model->updateCartQuantity($cartId, $quantity);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'message' => $result ? 'Panier mis à jour' : 'Erreur lors de la mise à jour'
            ]));
    }
    
    /**
     * Supprime un produit du panier (AJAX)
     */
    public function removeFromCart() {
        if (!$this->session->userdata('user_id')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Veuillez vous connecter']));
            return;
        }
        
        $cartId = $this->input->post('cart_id', TRUE);
        $result = $this->Home_model->removeFromCart($cartId);
        
        $cartCount = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'cart_count' => $cartCount,
                'message' => $result ? 'Produit supprimé du panier' : 'Erreur lors de la suppression'
            ]));
    }
    
    /**
     * Ajoute un produit à la liste de souhaits (AJAX)
     */
    public function addToWishlist() {
        if (!$this->session->userdata('user_id')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Veuillez vous connecter']));
            return;
        }
        
        $productId = $this->input->post('product_id', TRUE);
        $result = $this->Home_model->addToWishlist($this->session->userdata('user_id'), $productId);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'message' => $result ? 'Ajouté à votre liste de souhaits' : 'Déjà dans votre liste de souhaits'
            ]));
    }
    
    /**
     * Supprime un produit de la liste de souhaits (AJAX)
     */
    public function removeFromWishlist() {
        if (!$this->session->userdata('user_id')) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Veuillez vous connecter']));
            return;
        }
        
        $productId = $this->input->post('product_id', TRUE);
        $result = $this->Home_model->removeFromWishlist($this->session->userdata('user_id'), $productId);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'message' => $result ? 'Retiré de votre liste de souhaits' : 'Erreur lors de la suppression'
            ]));
    }
    
    /**
     * Affiche la liste de souhaits
     */
    public function wishlist() {
        if (!$this->session->userdata('user_id')) {
           redirect('auth/login');
            return;
        }
        
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['wishlist'] = $this->Home_model->getWishlist($this->session->userdata('user_id'));
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        
        $data['meta_title'] = 'Ma liste de souhaits - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('wishlist_view', $data);
    }
    
    /**
     * Affiche le panier
     */
    public function cart() {
       // if (!$this->session->userdata('user_id')) {
        //    redirect('auth/login');
        //    return;
       // }
        
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['cartItems'] = $this->Home_model->getCartItems($this->session->userdata('user_id'));
        
        // Calculer le total
        $data['subtotal'] = 0;
        foreach ($data['cartItems'] as $item) {
            $data['subtotal'] += $item['sous_total'];
        }
        $data['frais_livraison'] = 2000;
        $data['total'] = $data['subtotal'] + $data['frais_livraison'];
        
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        
        $data['meta_title'] = 'Mon panier - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('cart_view', $data);
    }
    
    /**
     * Page de checkout
     */
    public function checkout() {
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
            return;
        }
        
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['cartItems'] = $this->Home_model->getCartItems($this->session->userdata('user_id'));
        
        if (empty($data['cartItems'])) {
            redirect('home/cart');
            return;
        }
        
        // Calculer les totaux
        $data['subtotal'] = 0;
        foreach ($data['cartItems'] as $item) {
            $data['subtotal'] += $item['sous_total'];
        }
        $data['frais_livraison'] = 2000;
        $data['total'] = $data['subtotal'] + $data['frais_livraison'];
        
        // Récupérer les modes de paiement
        $data['paymentMethods'] = $this->Home_model->getPaymentMethods();
        
        // Récupérer les provinces pour l'adresse
        $data['provinces'] = $this->Home_model->getProvinces();
        
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        
        $data['meta_title'] = 'Validation de commande - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('checkout_view', $data);
    }
    
    /**
     * Traite la commande
     */
    public function processOrder() {
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
            return;
        }
        
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('payment_method', 'Mode de paiement', 'required');
        $this->form_validation->set_rules('nom_complet', 'Nom complet', 'required');
        $this->form_validation->set_rules('telephone', 'Téléphone', 'required');
        $this->form_validation->set_rules('province', 'Province', 'required');
        $this->form_validation->set_rules('commune', 'Commune', 'required');
        $this->form_validation->set_rules('adresse', 'Adresse', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('home/checkout');
            return;
        }
        
        // Récupérer les articles du panier
        $cartItems = $this->Home_model->getCartItems($this->session->userdata('user_id'));
        
        if (empty($cartItems)) {
            $this->session->set_flashdata('error', 'Votre panier est vide');
            redirect('home/cart');
            return;
        }
        
        // Calculer le total
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['sous_total'];
        }
        $frais_livraison = 2000;
        $total = $subtotal + $frais_livraison;
        
        // Générer le numéro de commande
        $numero_commande = 'CMD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Préparer les données de la commande
        $orderData = [
            'numero_commande' => $numero_commande,
            'id_utilisateur' => $this->session->userdata('user_id'),
            'sous_total' => $subtotal,
            'frais_livraison' => $frais_livraison,
            'montant_total' => $total,
            'id_mode_payement' => $this->input->post('payment_method', TRUE),
            'statut_commande' => 'en_attente',
            'statut_paiement' => 'en_attente',
            'note_client' => $this->input->post('note', TRUE),
            'canal_commande' => 'web'
        ];
        
        // Insérer la commande (à implémenter dans le modèle)
        // $orderId = $this->Home_model->createOrder($orderData, $cartItems);
        
        // Vider le panier
        $this->Home_model->clearCart($this->session->userdata('user_id'));
        
        // Rediriger vers la page de succès
        $this->session->set_flashdata('success', 'Votre commande a été enregistrée avec succès. Numéro: ' . $numero_commande);
        redirect('home/order_success/' . $numero_commande);
    }
    
    /**
     * Page de succès de commande
     */
    public function order_success($numero_commande) {
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['numero_commande'] = $numero_commande;
        
        if ($this->session->userdata('user_id')) {
            $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
            $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        } else {
            $data['cart_count'] = 0;
            $data['wishlist_count'] = 0;
            $data['user_profils'] = [];
        }
        
        $data['meta_title'] = 'Commande confirmée - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
        $this->render('order_success_view', $data);
    }
    



    /**
 * Traitement de la newsletter (AJAX)
 */
public function newsletter_subscribe() {
    $email = $this->input->post('email', TRUE);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => false, 'message' => 'Email invalide']));
        return;
    }
    
    // Vérifier si l'email existe déjà
    $this->db->where('email', $email);
    $exists = $this->db->get('newsletter_subscribers')->num_rows();
    
    if ($exists > 0) {
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => false, 'message' => 'Cet email est déjà inscrit']));
        return;
    }
    
    // Insérer le nouvel abonné
    $data = [
        'email' => $email,
        'date_inscription' => date('Y-m-d H:i:s'),
        'est_actif' => 1,
        'ip_address' => $this->input->ip_address()
    ];
    
    $result = $this->db->insert('newsletter_subscribers', $data);
    
    $this->output->set_content_type('application/json')
                 ->set_output(json_encode([
                     'success' => $result,
                     'message' => $result ? 'Inscription réussie !' : 'Erreur lors de l\'inscription'
                 ]));
}

/**
 * Page de politique de confidentialité
 */
public function privacy_policy() {
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    
    if ($this->session->userdata('user_id')) {
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
    } else {
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['user_profils'] = [];
    }
    
    $data['meta_title'] = 'Politique de confidentialité - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $this->render('privacy_policy_view', $data);
}


    /**
     * Fonction utilitaire pour afficher une vue
     */
    private function render($view, $data = []) {
        // S'assurer que les données essentielles sont toujours présentes
        if (!isset($data['settings'])) {
            $data['settings'] = $this->Home_model->getSiteSettings();
        }
        
        if (!isset($data['main_categories'])) {
            $data['main_categories'] = $this->Home_model->getMainCategories();
        }
        
        if (!isset($data['categories_with_sub'])) {
            $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        }
        
        if (!isset($data['cart_count']) && $this->session->userdata('user_id')) {
            $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        } elseif (!isset($data['cart_count'])) {
            $data['cart_count'] = 0;
        }
        
        if (!isset($data['wishlist_count']) && $this->session->userdata('user_id')) {
            $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        } elseif (!isset($data['wishlist_count'])) {
            $data['wishlist_count'] = 0;
        }
        
        if (!isset($data['user_profils']) && $this->session->userdata('user_id')) {
            $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        } elseif (!isset($data['user_profils'])) {
            $data['user_profils'] = [];
        }
        
        // Charger le header
        $this->load->view('includes/frontend/Header', $data);
        
        // Charger la vue principale
        $this->load->view($view, $data);
        
        // Charger le footer
        $this->load->view('includes/frontend/Footer', $data);
    }

    public function sabonner() {
    $email = $this->input->post('email');
    
    // Validation email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->session->set_flashdata('error', 'Email invalide');
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    // Vérifier si l'email existe déjà
    $this->db->where('email', $email);
    $existant = $this->db->get('newsletter_abonnes')->row();
    
    if ($existant) {
        if ($existant->est_actif == 0) {
            // Réactiver l'abonnement
            $this->db->where('id_abonne', $existant->id_abonne);
            $this->db->update('newsletter_abonnes', ['est_actif' => 1]);
            $this->session->set_flashdata('success', 'Votre abonnement a été réactivé !');
        } else {
            $this->session->set_flashdata('info', 'Vous êtes déjà inscrit à notre newsletter');
        }
    } else {
        // Nouvel abonné
        $this->db->insert('newsletter_abonnes', [
            'email' => $email,
            'est_actif' => 1
        ]);
        $this->session->set_flashdata('success', 'Merci pour votre inscription à la newsletter !');
    }
    
    redirect($_SERVER['HTTP_REFERER']);
}






/**
 * Récupère la liste des souhaits pour l'offcanvas (AJAX)
 */
public function getWishlistOffcanvas() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Non connecté']);
        return;
    }
    
    $userId = $this->session->userdata('user_id');
    $wishlist = $this->Home_model->getWishlistWithDetails($userId);
    $count = $this->Home_model->getWishlistCount($userId);
    
    $html = '';
    if (empty($wishlist)) {
        $html = '<li class="empty-cart">
                    <svg><use xlink:href="' . base_url('assets/frontend/images/inner-page/empty-wishlist.svg#emptyWishlist') . '"></use></svg>
                    <h4>Votre liste de souhaits est vide.</h4>
                 </li>';
    } else {
        foreach ($wishlist as $item) {
            $imageUrl = !empty($item['image_url']) ? base_url($item['image_url']) : base_url('assets/frontend/images/product/placeholder.png');
            $prix = (!empty($item['prix_promo']) && $item['prix_promo'] < $item['prix_base']) ? $item['prix_promo'] : $item['prix_base'];
            $stockClass = $item['quantite_actuelle'] > 0 ? 'in-stock' : 'out-stock';
            
            $html .= '<li id="wishlist-item-' . $item['id_produit'] . '">
                        <div class="vertical-product-box">
                            <a href="' . base_url('product/' . $item['slug_produit']) . '" class="product-image">
                                <img src="' . $imageUrl . '" class="img-fluid" alt="' . htmlspecialchars($item['nom_produit']) . '">
                                <span class="stock-badge ' . $stockClass . '">' . ($item['quantite_actuelle'] > 0 ? 'En stock' : 'Rupture') . '</span>
                            </a>
                            <div class="product-content">
                                <a href="' . base_url('product/' . $item['slug_produit']) . '">
                                    <h5 class="name title-color">' . htmlspecialchars(substr($item['nom_produit'], 0, 40)) . '</h5>
                                </a>
                                <h5 class="price">' . number_format($prix, 0, ',', ' ') . ' BIF</h5>
                                <div class="rating">
                                    ' . $this->generateStarRating($item['note_moyenne'] ?? 0) . '
                                    <span>(' . ($item['nombre_avis'] ?? 0) . ')</span>
                                </div>
                                <button class="btn cart-btn move-to-cart-from-wishlist" data-product-id="' . $item['id_produit'] . '">
                                    <i class="ri-shopping-cart-line"></i> Ajouter au panier
                                </button>
                            </div>
                            <button class="btn wishlist-btn remove-from-wishlist" data-product-id="' . $item['id_produit'] . '">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>';
        }
    }
    
    echo json_encode([
        'success' => true,
        'html' => $html,
        'count' => $count,
        'hasItems' => $count > 0
    ]);
}






/**
 * Génère les étoiles de notation
 */
private function generateStarRating($rating) {
    $stars = '';
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $fullStars) {
            $stars .= '<i class="ri-star-fill fill"></i>';
        } elseif ($halfStar && $i == $fullStars + 1) {
            $stars .= '<i class="ri-star-half-fill fill"></i>';
        } else {
            $stars .= '<i class="ri-star-line"></i>';
        }
    }
    return $stars;
}

/**
 * Ajouter au panier depuis la wishlist (AJAX)
 */
public function moveToCartFromWishlist() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Veuillez vous connecter']);
        return;
    }
    
    $productId = $this->input->post('product_id');
    $userId = $this->session->userdata('user_id');
    
    $cartResult = $this->Home_model->addToCart($userId, $productId, null, 1);
    $this->Home_model->removeFromWishlist($userId, $productId);
    
    echo json_encode([
        'success' => $cartResult,
        'wishlist_count' => $this->Home_model->getWishlistCount($userId),
        'cart_count' => $this->Home_model->getCartCount($userId),
        'message' => $cartResult ? 'Produit ajouté au panier' : 'Erreur'
    ]);
}

/**
 * Supprimer de la wishlist (AJAX)
 */
public function removeFromWishlistAjax() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Veuillez vous connecter']);
        return;
    }
    
    $productId = $this->input->post('product_id');
    $userId = $this->session->userdata('user_id');
    
    $result = $this->Home_model->removeFromWishlist($userId, $productId);
    
    echo json_encode([
        'success' => $result,
        'wishlist_count' => $this->Home_model->getWishlistCount($userId),
        'message' => $result ? 'Produit retiré de votre liste' : 'Erreur'
    ]);
}

/**
 * Récupérer les IDs des produits dans la wishlist (pour l'affichage des icônes)
 */
public function getUserWishlistIds() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'wishlist_ids' => []]);
        return;
    }
    
    $userId = $this->session->userdata('user_id');
    $wishlist = $this->Home_model->getWishlistWithDetails($userId);
    $ids = array_column($wishlist, 'id_produit');
    
    echo json_encode(['success' => true, 'wishlist_ids' => $ids]);
}


/**
 * Applique un coupon (AJAX)
 */
public function applyCoupon() {
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Veuillez vous connecter']);
        return;
    }
    
    $couponCode = $this->input->post('coupon', TRUE);
    $subtotal = $this->input->post('subtotal', TRUE);
    
    if (!$couponCode) {
        echo json_encode(['success' => false, 'message' => 'Code promo invalide']);
        return;
    }
    
    // Récupérer le sous-total actuel du panier
    if (!$subtotal) {
        $cartItems = $this->Home_model->getCartItems($this->session->userdata('user_id'));
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['sous_total'];
        }
    }
    
    $result = $this->Home_model->checkCoupon($couponCode, $subtotal);
    
    echo json_encode($result);
}

}