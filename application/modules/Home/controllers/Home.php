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

        $data['wishlist'] = $this->Home_model->getWishlist($this->session->userdata('user_id'));
        
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
    $this->output->set_content_type('application/json');
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
    $this->output->set_content_type('application/json');
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
    $this->output->set_content_type('application/json');
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
    $this->output->set_content_type('application/json');
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
    $this->output->set_content_type('application/json');
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
        $this->output->set_content_type('application/json');
        $province_id = $this->input->post('province_id');
        if (!$province_id) {
            echo json_encode([]);
            return;
        }
        
        $communes = $this->Home_model->get_communes_by_province($province_id);
        echo json_encode($communes);
    }

    /**
     * Récupération des quartiers par commune (AJAX)
     */
    public function get_quartiers() {
        $this->output->set_content_type('application/json');
        $commune_id = $this->input->post('commune_id');
        if (!$commune_id) {
            echo json_encode([]);
            return;
        }
        
        $quartiers = $this->Home_model->get_quartiers_by_commune($commune_id);
        echo json_encode($quartiers);
    }

    /**
     * Récupération des zones par commune (AJAX)
     */
    public function get_zones() {
        $this->output->set_content_type('application/json');
        $commune_id = $this->input->post('commune_id');
        if (!$commune_id) {
            echo json_encode([]);
            return;
        }
        
        $zones = $this->Home_model->get_zones_by_commune($commune_id);
        echo json_encode($zones);
    }

    /**
     * Récupération des collines par zone (AJAX)
     */
    public function get_collines() {
        $this->output->set_content_type('application/json');
        $zone_id = $this->input->post('zone_id');
        if (!$zone_id) {
            echo json_encode([]);
            return;
        }
        
        $collines = $this->Home_model->get_collines_by_zone($zone_id);
        echo json_encode($collines);
    }


    /**
     * API pour récupérer les sous-catégories d'une catégorie (AJAX)
     */
    public function getSubCategories() {
        $this->output->set_content_type('application/json');
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
        $data['total_pages'] = 1;
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
            redirect('contact');
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
 * Affiche la page boutique avec filtres
 */
public function shop() {
    // Récupérer les paramètres de filtrage
    $categoryId = $this->input->get('category', TRUE);
    $page = (int)$this->input->get('page', TRUE) ?: 1;
    $sort = $this->input->get('sort', TRUE) ?: 'newest';
    $rating = $this->input->get('rating', TRUE);
    $minPrice = $this->input->get('min_price', TRUE);
    $maxPrice = $this->input->get('max_price', TRUE);
    $brands = $this->input->get('brands', TRUE);
    $perPage = 12;
    
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    
    // Récupérer les catégories avec comptage de produits
    $data['categories'] = $this->Home_model->getMainCategoriesWithCount();
    $data['currentCategory'] = $categoryId;
    $data['currentCategoryName'] = '';
    
    if ($categoryId) {
        $cat = $this->Home_model->getCategoryById($categoryId);
        $data['currentCategoryName'] = $cat ? $cat['nom_categorie'] : '';
    }
    
    $data['currentSort'] = $sort;
    $data['currentPage'] = $page;
    $data['selected_rating'] = $rating;
    $data['selected_brands'] = $brands ? explode(',', $brands) : [];
    $data['selected_min_price'] = $minPrice;
    $data['selected_max_price'] = $maxPrice;
    
    // Récupérer les filtres disponibles
    $data['filters'] = $this->Home_model->getShopFilters();
    
    // Préparer les filtres pour la requête
    $filters = [
        'category_id' => $categoryId,
        'min_price' => $minPrice,
        'max_price' => $maxPrice,
        'brands' => $brands ? explode(',', $brands) : [],
        'rating' => $rating
    ];
    
    // Récupérer les produits avec les filtres
    $data['products'] = $this->Home_model->getFilteredProducts($filters, $sort, $perPage, ($page - 1) * $perPage);
    $data['totalProducts'] = $this->Home_model->countProductsWithFilters($filters);
    $data['totalPages'] = ceil($data['totalProducts'] / $perPage);
    
    // Données utilisateur
    if ($this->session->userdata('user_id')) {
        $user_id = $this->session->userdata('user_id');
        $data['cart_count'] = $this->Home_model->getCartCount($user_id);
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($user_id);
        $data['user_profils'] = $this->Home_model->getUserProfils($user_id);
    } else {
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['user_profils'] = [];
    }
    
    $data['meta_title'] = 'Boutique - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $this->render('shop_view', $data);
}

/**
 * AJAX - Filtrage des produits sans rechargement
 */
public function ajax_filter_products() {
    // Vérifier si c'est une requête AJAX
    if (!$this->input->is_ajax_request()) {
        show_404();
        return;
    }
    
    // Récupérer les paramètres de filtrage
    $categoryId = $this->input->post('category', TRUE);
    $page = (int)$this->input->post('page', TRUE) ?: 1;
    $sort = $this->input->post('sort', TRUE) ?: 'newest';
    $rating = $this->input->post('rating', TRUE);
    $minPrice = $this->input->post('min_price', TRUE);
    $maxPrice = $this->input->post('max_price', TRUE);
    $brands = $this->input->post('brands', TRUE);
    $perPage = 12;
    
    // Préparer les filtres
    $filters = [
        'category_id' => $categoryId,
        'min_price' => $minPrice,
        'max_price' => $maxPrice,
        'brands' => is_array($brands) ? $brands : ($brands ? explode(',', $brands) : []),
        'rating' => $rating
    ];
    
    // Récupérer les produits filtrés
    $products = $this->Home_model->getFilteredProducts($filters, $sort, $perPage, ($page - 1) * $perPage);
    $totalProducts = $this->Home_model->countProductsWithFilters($filters);
    $totalPages = ceil($totalProducts / $perPage);
    
    // Récupérer les comptages par catégorie pour mise à jour
    $categoryCounts = $this->Home_model->getCategoryProductCounts($filters);
    
    // Générer le HTML
    $products_html = '';
    $pagination_html = '';
    
    if (!empty($products)) {
        $products_html = $this->load->view('partials/product_grid', ['products' => $products], TRUE);
        if ($totalPages > 1) {
            $pagination_html = $this->load->view('partials/pagination', [
                'totalPages' => $totalPages,
                'currentPage' => $page
            ], TRUE);
        }
    }
    
    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'success' => true,
            'products_html' => $products_html,
            'pagination_html' => $pagination_html,
            'total_products' => $totalProducts,
            'total_pages' => $totalPages,
            'current_page' => $page,
            'category_counts' => $categoryCounts
        ]));
}

/**
 * AJAX - Récupérer le nombre d'articles dans le panier
 */
public function getCartCount() {
    if ($this->session->userdata('user_id')) {
        $count = $this->Home_model->getCartCount($this->session->userdata('user_id'));
    } else {
        $count = 0;
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($count));
}

/**
 * AJAX - Récupérer le nombre d'articles dans la wishlist
 */
public function getWishlistCount() {
    if ($this->session->userdata('user_id')) {
        $count = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
    } else {
        $count = 0;
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($count));
}

    /**
     * Affiche les détails d'un produit
     */
   
    /**
     * Affiche la page des vendeurs
     */
   public function sellers() {
    // Pagination
    $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
    $per_page = 8; // 8 vendeurs par page (2 lignes x 4 colonnes)
    $offset = ($page - 1) * $per_page;
    
    // Récupération des données
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    
    // Vendeurs avec pagination
    $data['sellers'] = $this->Home_model->getAllSellersPaginated($per_page, $offset);
    $data['total_sellers'] = $this->Home_model->countAllSellers();
    $data['current_page'] = $page;
    $data['per_page'] = $per_page;
    $data['total_pages'] = ceil($data['total_sellers'] / $per_page);
    
    // Vendeurs en vedette (en haut de page)
    $data['featured_sellers'] = $this->Home_model->getFeaturedSellersWithProducts(6);
    
    // Statistiques
    $data['stats'] = $this->Home_model->getHomeStats();
    
    // Session
    if ($this->session->userdata('user_id')) {
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
    } else {
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['user_profils'] = [];
    }
    
    // Meta données
    $data['meta_title'] = 'Nos Vendeurs - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $data['meta_description'] = 'Découvrez nos vendeurs partenaires sur ' . ($data['settings']['site_name'] ?? 'AbeMarket') . '. Produits de qualité, livraison rapide au Burundi.';
    
    $this->render('sellers_view', $data);
}

    /**
     * Page détail d'un vendeur
     */
    public function seller_details($slug) {
        // Redirige vers la méthode seller plus complète
        $this->seller($slug);
    }
    





    /**
 * Affiche la page d'un vendeur spécifique
 */
public function seller($slug) {
    // Récupérer le vendeur avec tous ses détails
    $seller = $this->Home_model->getSellerBySlugWithFullDetails($slug);
    
    if (!$seller) {
        show_404();
        return;
    }
    
    // Pagination des produits
    $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
    $per_page = 12;
    $offset = ($page - 1) * $per_page;
    
    // Paramètres de tri
    $sort = $this->input->get('sort') ? $this->input->get('sort') : 'popular';
    
    // Récupérer les produits avec pagination
    $data['products'] = $this->Home_model->getSellerProductsPaginated($seller['id_vendeur'], $per_page, $offset);
    $data['total_products'] = $seller['product_count'];
    $data['current_page'] = $page;
    $data['per_page'] = $per_page;
    $data['total_pages'] = ceil($data['total_products'] / $per_page);
    
    // Filtres
    $data['product_categories'] = $this->Home_model->getSellerProductCategories($seller['id_vendeur']);
    $data['price_range'] = $this->Home_model->getSellerPriceRange($seller['id_vendeur']);
    $data['seller_rating'] = $this->Home_model->getSellerRatingFormatted($seller['id_vendeur']);
    
    // Données de base
    $data['settings'] = $this->Home_model->getSiteSettings();
    $data['main_categories'] = $this->Home_model->getMainCategories();
    $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
    $data['seller'] = $seller;
    
    // Session
    if ($this->session->userdata('user_id')) {
        $data['cart_count'] = $this->Home_model->getCartCount($this->session->userdata('user_id'));
        $data['wishlist_count'] = $this->Home_model->getWishlistCount($this->session->userdata('user_id'));
        $data['user_profils'] = $this->Home_model->getUserProfils($this->session->userdata('user_id'));
        // Récupérer les IDs des produits dans la wishlist
        $data['wishlist_ids'] = $this->Home_model->getUserWishlistIds($this->session->userdata('user_id'));
    } else {
        $data['cart_count'] = 0;
        $data['wishlist_count'] = 0;
        $data['user_profils'] = [];
        $data['wishlist_ids'] = [];
    }
    
    $data['meta_title'] = $seller['nom_boutique'] . ' - ' . ($data['settings']['site_name'] ?? 'AbeMarket');
    $data['meta_description'] = substr(strip_tags($seller['description'] ?? ''), 0, 160);
    
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
        
        // Vérifier que le produit existe et a un vendeur assigné
        $this->load->model('Produit_model');
        $product = $this->Produit_model->get_produit_by_id($productId);
        
        if (!$product) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Produit non trouvé']));
            return;
        }
        
        if (empty($product['id_vendeur'])) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => false, 'message' => 'Ce produit n\'a pas de vendeur assigné. Contactez le support.']));
            return;
        }
        
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
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
            return;
        }
        
        $data['settings'] = $this->Home_model->getSiteSettings();
        $data['main_categories'] = $this->Home_model->getMainCategories();
        $data['categories_with_sub'] = $this->Home_model->getCategoriesWithSub();
        $data['cartItems'] = $this->Home_model->getCartItems($this->session->userdata('user_id'));
        
        // Calculer le total
        $data['subtotal'] = 0;
        foreach ($data['cartItems'] as $item) {
            $data['subtotal'] += $item['sous_total'];
        }
        $data['frais_livraison'] = $this->Home_model->getDeliveryFee();
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
        $data['frais_livraison'] = $this->Home_model->getDeliveryFee();
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
        $this->form_validation->set_rules('quartier', 'Quartier', 'required');
        $this->form_validation->set_rules('zone', 'Zone', 'required');
        $this->form_validation->set_rules('colline', 'Colline', 'required');
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

        $paymentMethod = $this->Home_model->getPaymentMethodById($this->input->post('payment_method', TRUE));
        if (!$paymentMethod || $paymentMethod['type'] !== 'mobile_money') {
            $this->session->set_flashdata('error', 'Veuillez choisir un moyen de paiement Mobile Money actif.');
            redirect('home/checkout');
            return;
        }
        
        // Calculer le total
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['sous_total'];
        }
        $frais_livraison = $this->Home_model->getDeliveryFee();
        $total = $subtotal + $frais_livraison;
        
        // Générer le numéro de commande
        $numero_commande = 'CMD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        $reference_transaction = 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
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
        
        // Insérer la commande
        $addressData = [
            'id_utilisateur' => $this->session->userdata('user_id'),
            'type_adresse' => 'domicile',
            'nom_complet' => $this->input->post('nom_complet', TRUE),
            'telephone' => $this->input->post('telephone', TRUE),
            'id_province' => $this->input->post('province', TRUE),
            'id_commune' => $this->input->post('commune', TRUE),
            'id_quartier' => $this->input->post('quartier', TRUE),
            'id_zone' => $this->input->post('zone', TRUE),
            'id_colline' => $this->input->post('colline', TRUE),
            'adresse_ligne' => $this->input->post('adresse', TRUE),
            'point_repere' => $this->input->post('point_repere', TRUE),
            'latitude' => 0,
            'longitude' => 0,
            'est_actif' => 1
        ];
        $paymentData = [
            'reference_interne' => $reference_transaction,
            'id_utilisateur' => $this->session->userdata('user_id'),
            'id_mode_payement' => $paymentMethod['id_mode_payement'],
            'type_transaction' => 'paiement',
            'montant' => $total,
            'frais' => 0,
            'montant_net' => $total,
            'devise' => 'BIF',
            'telephone_payeur' => $this->input->post('telephone', TRUE),
            'nom_payeur' => $this->input->post('nom_complet', TRUE),
            'statut' => 'en_attente',
            'message_statut' => 'En attente de la confirmation Mobile Money.',
            'adresse_ip' => $this->input->ip_address()
        ];
        $orderId = $this->Home_model->createOrder($orderData, $cartItems, $addressData, $paymentData);
        if (!$orderId) {
            // Récupérer le message d'erreur détaillé du modèle
            $errorMessage = $this->Home_model->getLastError();
            if (!$errorMessage) {
                $errorMessage = 'La commande n\'a pas pu être enregistrée. Veuillez contacter le support.';
            }
            $this->session->set_flashdata('error', $errorMessage);
            redirect('home/checkout');
            return;
        }
        
        // Vider le panier
        $this->Home_model->clearCart($this->session->userdata('user_id'));
        
        // Rediriger vers la page de succès
        $this->session->set_flashdata('success', 'Votre commande a été enregistrée avec succès. Numéro: ' . $numero_commande);
        redirect('payment/pending/' . $reference_transaction);
    }
    
    /**
     * Page de succès de commande
     */
    public function payment_pending($reference) {
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
            return;
        }
        $data['payment'] = $this->db->select('t.*, c.numero_commande, mp.description, mp.instructions')
            ->from('transactions_paiement t')
            ->join('commandes c', 'c.id_commande = t.id_commande')
            ->join('mode_payement mp', 'mp.id_mode_payement = t.id_mode_payement')
            ->where('t.reference_interne', $reference)
            ->where('t.id_utilisateur', $this->session->userdata('user_id'))
            ->get()->row_array();
        if (!$data['payment']) {
            show_404();
            return;
        }
        $data['meta_title'] = 'Paiement Mobile Money - AbeMarket';
        $this->render('payment_pending_view', $data);
    }

    public function submit_payment_reference() {
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
            return;
        }
        $reference = $this->input->post('reference_interne', TRUE);
        $operatorReference = trim($this->input->post('reference_operateur', TRUE));
        $updated = !empty($reference) && !empty($operatorReference) && $this->db
            ->where('reference_interne', $reference)
            ->where('id_utilisateur', $this->session->userdata('user_id'))
            ->where_in('statut', ['initie', 'en_attente'])
            ->update('transactions_paiement', [
                'reference_operateur' => $operatorReference,
                'statut' => 'en_attente',
                'message_statut' => 'Reference Mobile Money soumise : validation administrative requise.'
            ]);
        $this->session->set_flashdata($updated ? 'success' : 'error', $updated ? 'Reference envoyee. Le paiement sera verifie avant preparation.' : 'Reference de paiement invalide.');
        redirect('payment/pending/' . $reference);
    }

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
    $exists = $this->db->get('newsletter_abonnes')->num_rows();
    
    if ($exists > 0) {
        $this->output->set_content_type('application/json')
                     ->set_output(json_encode(['success' => false, 'message' => 'Cet email est déjà inscrit']));
        return;
    }
    
    // Insérer le nouvel abonné
    $data = [
        'email' => $email,
        'date_inscription' => date('Y-m-d H:i:s'),
        'est_actif' => 1
    ];
    
    $result = $this->db->insert('newsletter_abonnes', $data);
    
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
    $email = $this->input->post('email', TRUE);
    
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : site_url('home');
    
    // Validation email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->session->set_flashdata('error', 'Email invalide');
        redirect($referer);
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
    
    redirect($referer);
}






/**
 * Récupère la liste des souhaits pour l'offcanvas (AJAX)
 */
public function getWishlistOffcanvas() {
    $this->output->set_content_type('application/json');
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
    $this->output->set_content_type('application/json');
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Veuillez vous connecter']);
        return;
    }
    
    $productId = $this->input->post('product_id', TRUE);
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
    $this->output->set_content_type('application/json');
    if (!$this->session->userdata('user_id')) {
        echo json_encode(['success' => false, 'message' => 'Veuillez vous connecter']);
        return;
    }
    
    $productId = $this->input->post('product_id', TRUE);
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
    $this->output->set_content_type('application/json');
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
    $this->output->set_content_type('application/json');
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
