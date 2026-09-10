<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    private $last_error = null;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Récupère les paramètres du site (table settings)
     */
    public function getSiteSettings() {
        $query = $this->db->get('settings');
        $settings = [];
        foreach ($query->result_array() as $row) {
            $settings[$row['KeyValue']] = $row['Value'];
        }
        return $settings;
    }
    
    /**
     * Récupère les frais de livraison depuis les settings
     */
    public function getDeliveryFee() {
        $settings = $this->getSiteSettings();
        return isset($settings['frais_livraison']) ? (int)$settings['frais_livraison'] : 2000;
    }
    
    /**
     * Récupère les catégories principales (niveau 0)
     */
    public function getMainCategories() {
        $this->db->select('id_categorie, nom_categorie, slug_categorie, icone, url_image, description, niveau, ordre_affichage');
        $this->db->from('categories');
        $this->db->where('est_actif', 1);
        $this->db->where('niveau', 0);
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les catégories avec leurs sous-catégories
     */
    public function getCategoriesWithSub() {
        $categories = $this->getMainCategories();
        $result = [];
        foreach ($categories as $cat) {
            $result[$cat['id_categorie']] = [
                'children' => $this->getSubCategories($cat['id_categorie'])
            ];
        }
        return $result;
    }
    
    /**
     * Récupère les sous-catégories d'une catégorie parente
     */
    public function getSubCategories($parentId) {
        $this->db->select('id_categorie, nom_categorie, slug_categorie, icone, url_image, description');
        $this->db->from('categories');
        $this->db->where('id_parent', $parentId);
        $this->db->where('est_actif', 1);
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère une catégorie par son slug
     */
    public function getCategoryBySlug($slug) {
        $this->db->select('*');
        $this->db->from('categories');
        $this->db->where('slug_categorie', $slug);
        $this->db->where('est_actif', 1);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * Récupère les produits en tendance (basé sur les vues récentes)
     */
    public function getTrendingProducts($limit = 8) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.nombre_vues, p.quantite_actuelle,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.quantite_actuelle >', 0);
        $this->db->order_by('p.nombre_vues', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les produits par catégorie
     */
    public function getProductsByCategory($categoryId, $limit = 6) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.id_categorie', $categoryId);
        $this->db->where('p.quantite_actuelle >', 0);
        $this->db->order_by('p.note_moyenne', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les tags populaires (marques)
     */
    public function getPopularTags($limit = 20) {
        $this->db->select('p.marque as tag, COUNT(p.id_produit) as count');
        $this->db->from('produits p');
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.marque IS NOT NULL');
        $this->db->where('p.marque !=', '');
        $this->db->group_by('p.marque');
        $this->db->order_by('count', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les produits en flash sale (promotion active)
     */
    public function getFlashSaleProducts($limit = 12) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo, 
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.prix_promo IS NOT NULL');
        $this->db->where('p.prix_promo <', 'p.prix_base', FALSE);
        $this->db->where('(p.date_debut_promo IS NULL OR p.date_debut_promo <= NOW())', NULL, FALSE);
        $this->db->where('(p.date_fin_promo IS NULL OR p.date_fin_promo >= NOW())', NULL, FALSE);
        $this->db->where('p.quantite_actuelle >', 0);
        $this->db->order_by('p.prix_promo', 'ASC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les produits recommandés (les mieux notés)
     */
    public function getRecommendedProducts($limit = 10) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.quantite_actuelle >', 0);
        $this->db->order_by('p.note_moyenne', 'DESC');
        $this->db->order_by('p.nombre_ventes', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les produits les plus vendus
     */
    public function getTopSellingProducts($limit = 10) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.quantite_actuelle >', 0);
        $this->db->order_by('p.nombre_ventes', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les nouveaux produits
     */
    public function getNewProducts($limit = 8) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.date_creation,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.quantite_actuelle >', 0);
        $this->db->order_by('p.date_creation', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les vendeurs en vedette
     */
    public function getFeaturedSellers($limit = 6) {
        $this->db->select('v.id_vendeur, v.nom_boutique, v.slug_boutique, v.logo_boutique, v.description,
                           v.note_moyenne, v.nombre_avis, v.total_commandes,
                           u.prenom, u.nom');
        $this->db->from('vendeurs v');
        $this->db->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur');
        $this->db->where('v.statut', 'actif');
        $this->db->where('v.est_approuve', 1);
        $this->db->order_by('v.note_moyenne', 'DESC');
        $this->db->order_by('v.total_commandes', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère tous les vendeurs
     */
    public function getAllSellers() {
        $this->db->select('v.id_vendeur, v.nom_boutique, v.slug_boutique, v.logo_boutique, v.description,
                           v.note_moyenne, v.nombre_avis, v.total_commandes, v.date_creation,
                           u.prenom, u.nom');
        $this->db->from('vendeurs v');
        $this->db->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur');
        $this->db->where('v.statut', 'actif');
        $this->db->where('v.est_approuve', 1);
        $this->db->order_by('v.note_moyenne', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère un vendeur par son slug
     */
    public function getSellerBySlug($slug) {
        $this->db->select('v.*, u.nom, u.prenom, u.email, u.telephone');
        $this->db->from('vendeurs v');
        $this->db->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur');
        $this->db->where('v.slug_boutique', $slug);
        $this->db->where('v.statut', 'actif');
        $this->db->where('v.est_approuve', 1);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * Récupère les produits d'un vendeur
     */
    public function getSellerProducts($sellerId, $limit = 20) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->where('p.id_vendeur', $sellerId);
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->order_by('p.nombre_ventes', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Recherche de produits
     */
    public function searchProducts($keyword, $limit = 10) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->group_start();
        $this->db->like('p.nom_produit', $keyword);
        $this->db->or_like('p.description', $keyword);
        $this->db->or_like('p.marque', $keyword);
        $this->db->group_end();
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.quantite_actuelle >', 0);
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère un produit par son slug
     */
    public function getProductBySlug($slug) {
        $this->db->select('p.*, 
                           c.nom_categorie, c.slug_categorie,
                           v.nom_boutique, v.slug_boutique,
                           u.nom as vendeur_nom, u.prenom as vendeur_prenom');
        $this->db->from('produits p');
        $this->db->join('categories c', 'p.id_categorie = c.id_categorie', 'left');
        $this->db->join('vendeurs v', 'p.id_vendeur = v.id_vendeur', 'left');
        $this->db->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur', 'left');
        $this->db->where('p.slug_produit', $slug);
        $this->db->where('p.est_actif', 1);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    /**
     * Incrémente le compteur de vues d'un produit
     */
    public function incrementProductViews($productId) {
        $this->db->set('nombre_vues', 'nombre_vues + 1', FALSE);
        $this->db->where('id_produit', $productId);
        $this->db->update('produits');
    }
    
    /**
     * Récupère les images d'un produit
     */
    public function getProductImages($productId) {
        $this->db->select('*');
        $this->db->from('images_produit');
        $this->db->where('id_produit', $productId);
        $this->db->order_by('est_principale', 'DESC');
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }



    
/**
 * Récupère les informations de contact
 */
public function getContactInfo() {
    // Vérifier si la table existe
    if (!$this->db->table_exists('contact_info')) {
        return [];
    }
    
    $this->db->where('est_actif', 1);
    $this->db->order_by('ordre_affichage', 'ASC');
    $query = $this->db->get('contact_info');
    $info = [];
    foreach ($query->result_array() as $row) {
        $info[$row['info_key']] = $row;
    }
    return $info;
}

/**
 * Récupère les sujets du formulaire de contact
 */
public function getContactSujets() {
    // Vérifier si la table existe
    if ($this->db->table_exists('contact_sujets')) {
        $this->db->where('est_actif', 1);
        $this->db->order_by('ordre_affichage', 'ASC');
        $result = $this->db->get('contact_sujets')->result_array();
        if (!empty($result)) {
            return $result;
        }
    }
    
    // Retourner les sujets par défaut
    return [
        ['code' => 'question', 'libelle' => 'Question générale'],
        ['code' => 'commande', 'libelle' => 'Problème de commande'],
        ['code' => 'livraison', 'libelle' => 'Problème de livraison'],
        ['code' => 'produit', 'libelle' => 'Information produit'],
        ['code' => 'partenariat', 'libelle' => 'Partenariat / Devenir vendeur'],
        ['code' => 'autre', 'libelle' => 'Autre']
    ];
}
/**
 * Sauvegarde un message de contact
 */
public function saveContactMessage($data) {
    // Vérifier si la table existe
    if (!$this->db->table_exists('contact_messages')) {
        log_message('error', 'La table contact_messages n\'existe pas !');
        return false;
    }
    
    // Préparer les données d'insertion
    $insert_data = [
        'nom' => trim($data['name']),
        'email' => trim($data['email']),
        'telephone' => isset($data['phone']) ? trim($data['phone']) : null,
        'sujet' => isset($data['topic']) ? $data['topic'] : 'question',
        'message' => trim($data['message']),
        'ip_address' => $this->input->ip_address(),
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    // Ajouter l'ID utilisateur si connecté
    if ($this->session->userdata('user_id')) {
        $insert_data['id_utilisateur'] = $this->session->userdata('user_id');
    }
    
    // Afficher la requête pour debug
    // echo $this->db->last_query(); // Décommenter pour debug
    
    // Insérer dans la base
    $result = $this->db->insert('contact_messages', $insert_data);
    
    if ($result) {
        log_message('info', 'Message contact inséré. ID: ' . $this->db->insert_id());
        
        // Envoyer un email de notification à l'administrateur
        try {
            $settings = $this->getSiteSettings();
            $admin_email = !empty($settings['admin_email']) ? $settings['admin_email'] : (!empty($settings['site_email']) ? $settings['site_email'] : 'abemarket@abe.bi');
            
            $this->load->library('cpanel_email_lib');
            $subject = 'Nouveau message de contact - ' . ($insert_data['sujet'] ?? 'Support');
            $html_message = '<h3>Nouveau message reçu depuis le formulaire de contact</h3>' .
                            '<p><strong>Nom :</strong> ' . htmlspecialchars($insert_data['nom']) . '</p>' .
                            '<p><strong>Email :</strong> ' . htmlspecialchars($insert_data['email']) . '</p>' .
                            '<p><strong>Téléphone :</strong> ' . htmlspecialchars($insert_data['telephone'] ?? 'Non fourni') . '</p>' .
                            '<p><strong>Sujet :</strong> ' . htmlspecialchars($insert_data['sujet']) . '</p>' .
                            '<p><strong>Message :</strong><br>' . nl2br(htmlspecialchars($insert_data['message'])) . '</p>';
            
            $this->cpanel_email_lib->send_email($admin_email, $subject, $html_message);
        } catch (Exception $e) {
            log_message('error', 'Erreur envoi email contact admin: ' . $e->getMessage());
        }

        return true;
    } else {
        log_message('error', 'Erreur insertion contact: ' . $this->db->last_query());
        return false;
    }
}

/**
 * Récupère le contenu de la page À propos
 */
public function getAboutContent() {
    if (!$this->db->table_exists('about_content')) {
        return [];
    }
    $this->db->where('est_actif', 1);
    $this->db->order_by('ordre_affichage', 'ASC');
    $query = $this->db->get('about_content');
    $content = [];
    foreach ($query->result_array() as $row) {
        $content[$row['section_key']] = $row;
    }
    return $content;
}

/**
 * Récupère les membres de l'équipe
 */
public function getTeamMembers() {
    if (!$this->db->table_exists('team_members')) {
        return [];
    }
    $this->db->where('est_actif', 1);
    $this->db->order_by('ordre_affichage', 'ASC');
    return $this->db->get('team_members')->result_array();
}

/**
 * Récupère les témoignages clients
 */
public function getTestimonials() {
    if (!$this->db->table_exists('testimonials')) {
        return [];
    }
    $this->db->where('est_approuve', 1);
    $this->db->order_by('ordre_affichage', 'ASC');
    return $this->db->get('testimonials')->result_array();
}
    







/**
 * Récupère les articles de blog
 */
public function getBlogPosts($limit = 10, $offset = 0, $categorie_slug = null) {
    $this->db->select('p.*, c.nom as categorie_nom, c.slug as categorie_slug, u.prenom, u.nom as auteur_nom');
    $this->db->from('blog_posts p');
    $this->db->join('blog_categories c', 'c.id_categorie = p.id_categorie', 'left');
    $this->db->join('utilisateurs u', 'u.id_utilisateur = p.id_utilisateur', 'left');
    $this->db->where('p.status', 'publie');
    $this->db->where('p.date_publication <=', date('Y-m-d H:i:s'));
    
    if ($categorie_slug) {
        $this->db->where('c.slug', $categorie_slug);
    }
    
    $this->db->order_by('p.date_publication', 'DESC');
    $this->db->limit($limit, $offset);
    
    return $this->db->get()->result_array();
}

/**
 * Récupère un article par son slug
 */
public function getBlogPostBySlug($slug) {
    $this->db->select('p.*, c.nom as categorie_nom, c.slug as categorie_slug, u.prenom, u.nom as auteur_nom, u.avatar_url');
    $this->db->from('blog_posts p');
    $this->db->join('blog_categories c', 'c.id_categorie = p.id_categorie', 'left');
    $this->db->join('utilisateurs u', 'u.id_utilisateur = p.id_utilisateur', 'left');
    $this->db->where('p.slug', $slug);
    $this->db->where('p.status', 'publie');
    
    return $this->db->get()->row_array();
}

/**
 * Incrémente le compteur de vues d'un article
 */
public function incrementBlogViews($postId) {
    $this->db->set('views', 'views + 1', FALSE);
    $this->db->where('id_post', $postId);
    $this->db->update('blog_posts');
}

/**
 * Récupère les articles récents
 */
public function getRecentBlogPosts($limit = 5) {
    $this->db->select('id_post, title, slug, featured_image, date_publication');
    $this->db->from('blog_posts');
    $this->db->where('status', 'publie');
    $this->db->where('date_publication <=', date('Y-m-d H:i:s'));
    $this->db->order_by('date_publication', 'DESC');
    $this->db->limit($limit);
    return $this->db->get()->result_array();
}

/**
 * Récupère les catégories du blog avec comptage
 */
public function getBlogCategories() {
    $this->db->select('c.*, COUNT(p.id_post) as total_articles');
    $this->db->from('blog_categories c');
    $this->db->join('blog_posts p', 'p.id_categorie = c.id_categorie AND p.status = "publie"', 'left');
    $this->db->where('c.est_actif', 1);
    $this->db->group_by('c.id_categorie');
    $this->db->order_by('c.ordre_affichage', 'ASC');
    return $this->db->get()->result_array();
}

/**
 * Récupère les tags populaires
 */
public function getPopularBlogTags($limit = 10) {
    $this->db->select('tags');
    $this->db->from('blog_posts');
    $this->db->where('status', 'publie');
    $this->db->where('tags IS NOT NULL');
    $posts = $this->db->get()->result_array();
    
    $tags = [];
    foreach ($posts as $post) {
        $postTags = explode(',', $post['tags']);
        foreach ($postTags as $tag) {
            $tag = trim($tag);
            if (!empty($tag)) {
                $tags[$tag] = ($tags[$tag] ?? 0) + 1;
            }
        }
    }
    
    arsort($tags);
    return array_slice($tags, 0, $limit, true);
}

/**
 * Récupère les articles similaires
 */
public function getSimilarBlogPosts($currentId, $categorieId, $limit = 3) {
    $this->db->select('id_post, title, slug, featured_image, date_publication');
    $this->db->from('blog_posts');
    $this->db->where('status', 'publie');
    $this->db->where('id_post !=', $currentId);
    $this->db->where('id_categorie', $categorieId);
    $this->db->order_by('date_publication', 'DESC');
    $this->db->limit($limit);
    return $this->db->get()->result_array();
}

/**
 * Compte le nombre total d'articles
 */
public function countBlogPosts($categorie_slug = null) {
    $this->db->from('blog_posts p');
    $this->db->where('p.status', 'publie');
    $this->db->where('p.date_publication <=', date('Y-m-d H:i:s'));
    
    if ($categorie_slug) {
        $this->db->join('blog_categories c', 'c.id_categorie = p.id_categorie');
        $this->db->where('c.slug', $categorie_slug);
    }
    
    return $this->db->count_all_results();
}
    
    /**
     * Récupère les variantes d'un produit
     */
    public function getProductVariants($productId) {
        $this->db->select('*');
        $this->db->from('variantes_produit');
        $this->db->where('id_produit', $productId);
        $this->db->where('quantite_actuelle >', 0);
        $this->db->where('est_actif', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les avis d'un produit (table avis_produits)
     */
    public function getProductReviews($productId, $limit = 10) {
        $this->db->select('a.*, u.nom, u.prenom, u.avatar_url');
        $this->db->from('avis_produits a');
        $this->db->join('utilisateurs u', 'a.id_utilisateur = u.id_utilisateur');
        $this->db->where('a.id_produit', $productId);
        $this->db->where('a.est_approuve', 1);
        $this->db->order_by('a.date_creation', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les produits similaires
     */
    public function getSimilarProducts($categoryId, $productId, $limit = 10) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        $this->db->where('p.id_categorie', $categoryId);
        $this->db->where('p.id_produit !=', $productId);
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.quantite_actuelle >', 0);
        $this->db->order_by('RAND()');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    // ========== PANIER ==========
    
    /**
     * Récupère le nombre d'articles dans le panier (table paniers)
     */
    public function getCartCount($userId) {
        $this->db->select('COALESCE(SUM(quantite), 0) as total');
        $this->db->from('paniers');
        $this->db->where('id_utilisateur', $userId);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result ? (int)$result['total'] : 0;
    }
    
/**
 * Récupère les articles du panier
 */
public function getCartItems($userId) {
    $this->db->select('p.id_panier, p.id_produit, p.id_variante, p.quantite,
                       pr.id_vendeur, pr.nom_produit, pr.slug_produit, pr.prix_base, pr.prix_promo,
                       (SELECT url_image FROM images_produit WHERE id_produit = pr.id_produit AND est_principale = 1 LIMIT 1) as image_url,
                       v.attributs_variante');
    $this->db->from('paniers p');
    $this->db->join('produits pr', 'p.id_produit = pr.id_produit');
    $this->db->join('variantes_produit v', 'p.id_variante = v.id_variante', 'left');
    $this->db->where('p.id_utilisateur', $userId);
    $query = $this->db->get();
    $items = $query->result_array();
    
    foreach ($items as &$item) {
        $prixBase = floatval($item['prix_base']);
        $prixPromo = $item['prix_promo'] ? floatval($item['prix_promo']) : null;
        $item['prix_effectif'] = ($prixPromo && $prixPromo < $prixBase) ? $prixPromo : $prixBase;
        $item['prix_unitaire'] = $item['prix_effectif']; // AJOUTER CETTE LIGNE
        $item['sous_total'] = $item['prix_effectif'] * intval($item['quantite']);
    }
    
    return $items;
}
    
    /**
     * Ajoute un produit au panier
     */
    public function addToCart($userId, $productId, $variantId = null, $quantity = 1) {
        $this->db->where('id_utilisateur', $userId);
        $this->db->where('id_produit', $productId);
        if ($variantId) {
            $this->db->where('id_variante', $variantId);
        }
        $query = $this->db->get('paniers');
        $existing = $query->row_array();
        
        if ($existing) {
            $newQuantity = $existing['quantite'] + $quantity;
            $this->db->where('id_panier', $existing['id_panier']);
            return $this->db->update('paniers', ['quantite' => $newQuantity]);
        } else {
            $data = [
                'id_utilisateur' => $userId,
                'id_produit' => $productId,
                'id_variante' => $variantId,
                'quantite' => $quantity,
                'date_ajout' => date('Y-m-d H:i:s')
            ];
            return $this->db->insert('paniers', $data);
        }
    }
    
    /**
     * Met à jour la quantité dans le panier
     */
    public function updateCartQuantity($cartId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($cartId);
        }
        $this->db->where('id_panier', $cartId);
        return $this->db->update('paniers', ['quantite' => $quantity]);
    }
    
    /**
     * Supprime un article du panier
     */
    public function removeFromCart($cartId) {
        $this->db->where('id_panier', $cartId);
        return $this->db->delete('paniers');
    }
    
    /**
     * Vide le panier
     */
    public function clearCart($userId) {
        $this->db->where('id_utilisateur', $userId);
        return $this->db->delete('paniers');
    }
    
    // ========== LISTE DE SOUHAITS ==========
    
    /**
     * Récupère le nombre d'articles dans la liste de souhaits
     */
    public function getWishlistCount($userId) {
        $this->db->select('COUNT(*) as count');
        $this->db->from('liste_souhaits');
        $this->db->where('id_utilisateur', $userId);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result ? (int)$result['count'] : 0;
    }
    
    /**
     * Vérifie si un produit est dans la liste des souhaits
     */
    public function isInWishlist($userId, $productId) {
        $this->db->where('id_utilisateur', $userId);
        $this->db->where('id_produit', $productId);
        $query = $this->db->get('liste_souhaits');
        return $query->num_rows() > 0;
    }
    
    /**
     * Ajoute un produit à la liste des souhaits
     */
    public function addToWishlist($userId, $productId) {
        if ($this->isInWishlist($userId, $productId)) {
            return false;
        }
        $data = [
            'id_utilisateur' => $userId,
            'id_produit' => $productId,
            'date_ajout' => date('Y-m-d H:i:s')
        ];
        return $this->db->insert('liste_souhaits', $data);
    }
    
    /**
     * Supprime un produit de la liste des souhaits
     */
    public function removeFromWishlist($userId, $productId) {
        $this->db->where('id_utilisateur', $userId);
        $this->db->where('id_produit', $productId);
        return $this->db->delete('liste_souhaits');
    }
    
    /**
     * Récupère la liste de souhaits d'un utilisateur
     */
    public function getWishlist($userId) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url,
                           w.date_ajout');
        $this->db->from('liste_souhaits w');
        $this->db->join('produits p', 'w.id_produit = p.id_produit');
        $this->db->where('w.id_utilisateur', $userId);
        $this->db->where('p.est_actif', 1);
        $this->db->order_by('w.date_ajout', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère la liste des souhaits pour l'offcanvas
     */
    public function getWishlistForOffcanvas($userId, $limit = 5) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url,
                           w.date_ajout');
        $this->db->from('liste_souhaits w');
        $this->db->join('produits p', 'w.id_produit = p.id_produit');
        $this->db->where('w.id_utilisateur', $userId);
        $this->db->where('p.est_actif', 1);
        $this->db->order_by('w.date_ajout', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère la liste des souhaits avec les détails des produits
     */
    public function getWishlistWithDetails($userId) {
        $this->db->select('
            p.id_produit,
            p.nom_produit,
            p.slug_produit,
            p.prix_base,
            p.prix_promo,
            p.quantite_actuelle,
            p.note_moyenne,
            p.nombre_avis,
            (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url,
            l.date_ajout
        ');
        $this->db->from('liste_souhaits l');
        $this->db->join('produits p', 'l.id_produit = p.id_produit');
        $this->db->where('l.id_utilisateur', $userId);
        $this->db->where('p.est_actif', 1);
        $this->db->order_by('l.date_ajout', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les IDs des produits dans la wishlist
     */
    public function getUserWishlistIds($userId) {
        $this->db->select('id_produit');
        $this->db->from('liste_souhaits');
        $this->db->where('id_utilisateur', $userId);
        $query = $this->db->get();
        return array_column($query->result_array(), 'id_produit');
    }
    
    // ========== PROFILS UTILISATEUR ==========
    
    /**
     * Récupère les profils d'un utilisateur
     */
    public function getUserProfils($userId) {
        $this->db->select('p.id_profil, p.description');
        $this->db->from('utilisateur_profils up');
        $this->db->join('profils p', 'up.id_profil = p.id_profil');
        $this->db->where('up.id_utilisateur', $userId);
        $this->db->where('p.est_actif', 1);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    // ========== PRODUITS AVEC PAGINATION ==========
    
    /**
     * Récupère les produits avec pagination
     */
    public function getProductsPaginated($categoryId, $sort, $perPage, $offset) {
        $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                           p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                           (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
        $this->db->from('produits p');
        if (!empty($categoryId)) {
            $this->db->where('p.id_categorie', $categoryId);
        }
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.quantite_actuelle >', 0);
        
        switch ($sort) {
            case 'price_asc':
                $this->db->order_by('COALESCE(p.prix_promo, p.prix_base)', 'ASC');
                break;
            case 'price_desc':
                $this->db->order_by('COALESCE(p.prix_promo, p.prix_base)', 'DESC');
                break;
            case 'rating':
                $this->db->order_by('p.note_moyenne', 'DESC');
                break;
            case 'bestselling':
                $this->db->order_by('p.nombre_ventes', 'DESC');
                break;
            default:
                $this->db->order_by('p.date_creation', 'DESC');
                break;
        }
        
        $this->db->limit($perPage, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Compte le nombre total de produits
     */
    public function countAllProducts($categoryId = null) {
        $this->db->from('produits p');
        if (!empty($categoryId)) {
            $this->db->where('p.id_categorie', $categoryId);
        }
        $this->db->where('p.est_actif', 1);
        $this->db->where('p.statut', 'actif');
        $this->db->where('p.quantite_actuelle >', 0);
        return $this->db->count_all_results();
    }
    
    // ========== COUPONS ET COMMANDES ==========
    
    /**
     * Vérifie et applique un coupon
     */
    public function checkCoupon($code, $total) {
        $this->db->where('code', $code);
        $this->db->where('est_actif', 1);
        $this->db->where('date_debut <= NOW()', NULL, FALSE);
        $this->db->where('date_fin >= NOW()', NULL, FALSE);
        $query = $this->db->get('coupons');
        $coupon = $query->row_array();
        
        if (!$coupon) {
            return ['valid' => false, 'message' => 'Code promo invalide'];
        }
        if ($coupon['limite_utilisation'] && $coupon['nombre_utilisations'] >= $coupon['limite_utilisation']) {
            return ['valid' => false, 'message' => 'Ce code promo a atteint sa limite d\'utilisation'];
        }
        if ($coupon['montant_min_achat'] && $total < $coupon['montant_min_achat']) {
            return ['valid' => false, 'message' => 'Montant minimum requis : ' . number_format($coupon['montant_min_achat'], 0, ',', ' ') . ' BIF'];
        }
        
        if ($coupon['type_reduction'] == 'pourcentage') {
            $reduction = ($total * $coupon['valeur_reduction']) / 100;
            if ($coupon['montant_max_reduction']) {
                $reduction = min($reduction, $coupon['montant_max_reduction']);
            }
        } else {
            $reduction = $coupon['valeur_reduction'];
        }
        
        return [
            'valid' => true, 
            'coupon' => $coupon,
            'reduction' => $reduction,
            'message' => 'Code promo appliqué !'
        ];
    }
    
    /**
     * Crée une commande
     */
    public function createOrder($orderData, $cartItems, $addressData, $paymentData) {
        $this->db->trans_begin();

        $this->db->insert('adresses', $addressData);
        $addressId = $this->db->insert_id();
        $orderData['id_adresse_livraison'] = $addressId;

        $this->db->insert('commandes', $orderData);
        $orderId = $this->db->insert_id();
        
        foreach ($cartItems as $item) {
            if (empty($item['id_vendeur'])) {
                $error = 'Le produit "' . $item['nom_produit'] . '" n\'a pas de vendeur assigné. Veuillez vérifier les paramètres du produit.';
                log_message('error', 'Order creation failed: ' . $error . ' (Product ID: ' . $item['id_produit'] . ')');
                $this->last_error = $error;
                $this->db->trans_rollback();
                return false;
            }
            
            $articleData = [
                'id_commande' => $orderId,
                'id_produit' => $item['id_produit'],
                'id_variante' => $item['id_variante'] ?? null,
                'id_vendeur' => $item['id_vendeur'],
                'nom_produit' => $item['nom_produit'],
                'prix_unitaire' => $item['prix_effectif'],
                'quantite' => $item['quantite'],
                'prix_total' => $item['sous_total']
            ];
            
            $this->db->insert('articles_commande', $articleData);
            
            // Vérifier si l'insertion a échoué
            if ($this->db->affected_rows() <= 0) {
                $dbError = $this->db->error();
                $error = 'Erreur lors de l\'insertion du produit "' . $item['nom_produit'] . '". Détail: ' . (isset($dbError['message']) ? $dbError['message'] : 'Erreur inconnue');
                log_message('error', 'Order creation failed: ' . $error);
                $this->last_error = $error;
                $this->db->trans_rollback();
                return false;
            }
            
            // Décrémentation du stock après insertion de l'article
            $this->load->model('Produit_model');
            $this->Produit_model->update_stock_by_id(
                $item['id_produit'],
                -$item['quantite'],
                $item['id_variante'] ?? null
            );
        }

        $paymentData['id_commande'] = $orderId;
        $this->db->insert('transactions_paiement', $paymentData);

        if ($this->db->trans_status() === false) {
            $error = 'Erreur lors de l\'enregistrement de la transaction de paiement';
            log_message('error', 'Order creation failed (transaction): ' . $error);
            $this->last_error = $error;
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        
        // Log de succès
        log_message('info', 'Order created successfully: Order ID = ' . $orderId . ', Total = ' . $orderData['montant_total'] . ' BIF');
        
        return $orderId;
    }
    
    /**
     * Récupère le dernier message d'erreur lors de la création de commande
     */
    public function getLastError() {
        return isset($this->last_error) ? $this->last_error : null;
    }
    
    /**
     * Récupère les commandes d'un utilisateur
     */
    public function getUserOrders($userId, $limit = 10) {
        $this->db->select('c.*, mp.description as mode_paiement');
        $this->db->from('commandes c');
        $this->db->join('mode_payement mp', 'c.id_mode_payement = mp.id_mode_payement', 'left');
        $this->db->where('c.id_utilisateur', $userId);
        $this->db->order_by('c.date_creation', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    // ========== MODES DE PAIEMENT ET PROVINCES ==========
    
    /**
     * Récupère les modes de paiement
     */
    public function getPaymentMethods() {
        $this->db->select('*');
        $this->db->from('mode_payement');
        $this->db->where('est_actif', 1);
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPaymentMethodById($id) {
        return $this->db->where('id_mode_payement', $id)
            ->where('est_actif', 1)
            ->get('mode_payement')
            ->row_array();
    }
    
    /**
     * Récupère les provinces
     */
    public function getProvinces() {
        $this->db->select('id_province, province_name as nom');
        $this->db->from('provinces');
        $this->db->where('est_actif', 1);
        $this->db->order_by('province_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Récupère les communes actives par province
     */
    public function get_communes_by_province($id_province) {
        return $this->db->select('id_commune, commune_name as nom')
                        ->from('communes')
                        ->where('id_province', $id_province)
                        ->where('est_actif', 1)
                        ->order_by('commune_name', 'ASC')
                        ->get()
                        ->result_array();
    }

    /**
     * Récupère les quartiers actifs par commune
     */
    public function get_quartiers_by_commune($id_commune) {
        return $this->db->select('id_quartier, quartier_name as nom')
                        ->from('quartiers')
                        ->where('id_commune', $id_commune)
                        ->where('est_actif', 1)
                        ->order_by('quartier_name', 'ASC')
                        ->get()
                        ->result_array();
    }

    /**
     * Récupère les zones actives par commune
     */
    public function get_zones_by_commune($id_commune) {
        return $this->db->select('id_zone, zone_name as nom')
                        ->from('zones')
                        ->where('id_commune', $id_commune)
                        ->where('est_actif', 1)
                        ->order_by('zone_name', 'ASC')
                        ->get()
                        ->result_array();
    }

    /**
     * Récupère les collines actives par zone
     */
    public function get_collines_by_zone($id_zone) {
        return $this->db->select('id_colline, colline_name as nom')
                        ->from('collines')
                        ->where('id_zone', $id_zone)
                        ->where('est_actif', 1)
                        ->order_by('colline_name', 'ASC')
                        ->get()
                        ->result_array();
    }
    
   
     

    /**
     * Récupère les FAQs
     */
    public function getFaqs() {
        if ($this->db->table_exists('faq')) {
            $this->db->select('*');
            $this->db->from('faq');
            $this->db->where('est_actif', 1);
            $this->db->order_by('ordre_affichage', 'ASC');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
        }
        return [];
    }
    
    // ========== BANNIÈRES ==========
    
    /**
     * Récupère toutes les bannières actives pour le frontend
     */
    public function getBanners() {
        $this->db->select('*');
        $this->db->from('banners');
        $this->db->where('est_actif', 1);
        $this->db->where('(date_debut IS NULL OR date_debut <= NOW())', NULL, FALSE);
        $this->db->where('(date_fin IS NULL OR date_fin >= NOW())', NULL, FALSE);
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les bannières du slider principal (home_main)
     */
    public function getSliderBanners() {
        $this->db->select('*');
        $this->db->from('banners');
        $this->db->where('est_actif', 1);
        $this->db->where('position', 'home_main');
        $this->db->where('(date_debut IS NULL OR date_debut <= NOW())', NULL, FALSE);
        $this->db->where('(date_fin IS NULL OR date_fin >= NOW())', NULL, FALSE);
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les bannières du bas de page (home_bottom)
     */
    public function getBottomBanners() {
        $this->db->select('*');
        $this->db->from('banners');
        $this->db->where('est_actif', 1);
        $this->db->where('position', 'home_bottom');
        $this->db->where('(date_debut IS NULL OR date_debut <= NOW())', NULL, FALSE);
        $this->db->where('(date_fin IS NULL OR date_fin >= NOW())', NULL, FALSE);
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Récupère les bannières actives par position
     */
    public function getBannersByPosition($position) {
        $this->db->select('*');
        $this->db->from('banners');
        $this->db->where('est_actif', 1);
        $this->db->where('position', $position);
        $this->db->where('(date_debut IS NULL OR date_debut <= NOW())', NULL, FALSE);
        $this->db->where('(date_fin IS NULL OR date_fin >= NOW())', NULL, FALSE);
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    /**
     * Vérifie si des bannières existent
     */
    public function hasBanners() {
        return $this->db->where('est_actif', 1)
            ->where('(date_debut IS NULL OR date_debut <= NOW())', NULL, FALSE)
            ->where('(date_fin IS NULL OR date_fin >= NOW())', NULL, FALSE)
            ->count_all_results('banners') > 0;
    }
    
    /**
     * Récupère les bannières pour le carrousel mobile/desktop
     */
    public function getCarouselBanners() {
        return $this->getSliderBanners();
    }
    
    // ========== CATÉGORIES HIÉRARCHIE ==========
    
    /**
     * Récupère les catégories avec leur hiérarchie complète (parents et enfants)
     */
    public function getCategoriesHierarchy() {
        $this->db->select('id_categorie, id_parent, nom_categorie, slug_categorie, icone, url_image, niveau, ordre_affichage');
        $this->db->from('categories');
        $this->db->where('est_actif', 1);
        $this->db->order_by('niveau', 'ASC');
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        $all_categories = $query->result_array();
        
        $categories_tree = [];
        $categories_by_id = [];
        
        foreach ($all_categories as $cat) {
            $cat['children'] = [];
            $categories_by_id[$cat['id_categorie']] = $cat;
        }
        
        foreach ($categories_by_id as $id => $cat) {
            if ($cat['niveau'] == 0 || empty($cat['id_parent'])) {
                $categories_tree[] = &$categories_by_id[$id];
            } else {
                if (isset($categories_by_id[$cat['id_parent']])) {
                    $categories_by_id[$cat['id_parent']]['children'][] = &$categories_by_id[$id];
                }
            }
        }
        
        return $categories_tree;
    }
    
    /**
     * Récupère toutes les catégories avec leurs sous-catégories (pour le menu mobile)
     */
    public function getAllCategoriesWithSub() {
        $this->db->select('id_categorie, id_parent, nom_categorie, slug_categorie, icone, niveau, ordre_affichage');
        $this->db->from('categories');
        $this->db->where('est_actif', 1);
        $this->db->order_by('niveau', 'ASC');
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        $categories = $query->result_array();
        
        $result = [];
        foreach ($categories as $cat) {
            if ($cat['niveau'] == 0) {
                $result[] = [
                    'id' => $cat['id_categorie'],
                    'name' => $cat['nom_categorie'],
                    'slug' => $cat['slug_categorie'],
                    'icon' => $cat['icone'] ?? '📦',
                    'subs' => []
                ];
            }
        }
        
        foreach ($result as &$main) {
            foreach ($categories as $cat) {
                if ($cat['id_parent'] == $main['id']) {
                    $main['subs'][] = [
                        'title' => strtoupper($cat['nom_categorie']),
                        'items' => $this->getSubSubCategories($cat['id_categorie'], $categories)
                    ];
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Récupère les sous-sous-catégories (niveau 2 et plus)
     */
    private function getSubSubCategories($parent_id, $all_categories) {
        $items = [];
        foreach ($all_categories as $cat) {
            if ($cat['id_parent'] == $parent_id) {
                $items[] = $cat['nom_categorie'];
            }
        }
        return $items;
    }
    
    /**
     * Récupère les sous-catégories d'une catégorie parente (pour AJAX)
     */
    public function getSubCategoriesByParent($parent_id) {
        $this->db->select('id_categorie, nom_categorie, slug_categorie');
        $this->db->from('categories');
        $this->db->where('id_parent', $parent_id);
        $this->db->where('est_actif', 1);
        $this->db->order_by('ordre_affichage', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    // ========== NEWSLETTER ==========
    
    /**
     * Abonne un email à la newsletter
     */
    public function subscribeNewsletter($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('newsletter_abonnes');
        
        if ($query->num_rows() > 0) {
            return ['success' => false, 'message' => 'Cet email est déjà abonné'];
        }
        
        $data = [
            'email' => $email,
            'date_inscription' => date('Y-m-d H:i:s'),
            'est_actif' => 1
        ];
        
        if ($this->db->insert('newsletter_abonnes', $data)) {
            return ['success' => true, 'message' => 'Inscription réussie !'];
        }
        
        return ['success' => false, 'message' => 'Erreur lors de l\'inscription'];
    }



// ========== PRODUITS PAR CATÉGORIE POUR LA SECTION "ARTICLES DE CUISINE / HIGH-TECH" ==========

/**
 * Récupère les produits par catégorie avec limite
 * Utilisé pour les sections spécifiques de la home
 */
public function getProductsByCategoryLimit($categoryId, $limit = 5) {
    $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                       p.note_moyenne, p.nombre_avis, p.quantite_actuelle,
                       (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
    $this->db->from('produits p');
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->where('p.id_categorie', $categoryId);
    $this->db->where('p.quantite_actuelle >', 0);
    $this->db->order_by('p.nombre_ventes', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Récupère plusieurs catégories avec leurs produits (pour la home)
 * Pour les sections: Maison & Cuisine, Électronique, etc.
 */
public function getHomeCategoriesWithProducts($categoryIds = [3, 1, 8], $limit = 5) {
    $result = [];
    foreach ($categoryIds as $catId) {
        $category = $this->db->select('id_categorie, nom_categorie, slug_categorie')
                            ->where('id_categorie', $catId)
                            ->where('est_actif', 1)
                            ->get('categories')
                            ->row_array();
        if ($category) {
            $result[$catId] = [
                'id' => $category['id_categorie'],
                'name' => $category['nom_categorie'],
                'slug' => $category['slug_categorie'],
                'products' => $this->getProductsByCategoryLimit($catId, $limit)
            ];
        }
    }
    return $result;
}

// ========== AVIS / TÉMOIGNAGES POUR LA HOME ==========

/**
 * Récupère les témoignages clients pour la home
 * Utilise la table avis_produits (existe dans votre DB)
 */
public function getHomeTestimonials($limit = 6) {
    $this->db->select('a.id_avis, a.note, a.commentaire, a.date_creation,
                       u.nom, u.prenom, u.avatar_url,
                       p.nom_produit, p.slug_produit');
    $this->db->from('avis_produits a');
    $this->db->join('utilisateurs u', 'a.id_utilisateur = u.id_utilisateur');
    $this->db->join('produits p', 'a.id_produit = p.id_produit');
    $this->db->where('a.est_approuve', 1);
    $this->db->where('a.note >=', 4);
    $this->db->order_by('a.date_creation', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    
    $testimonials = [];
    foreach ($query->result_array() as $row) {
        $testimonials[] = [
            'id' => $row['id_avis'],
            'nom' => $row['prenom'] . ' ' . $row['nom'],
            'avatar' => !empty($row['avatar_url']) ? base_url($row['avatar_url']) : base_url('assets/frontend/images/avatar/default.png'),
            'note' => $row['note'],
            'commentaire' => $row['commentaire'],
            'produit' => $row['nom_produit'],
            'produit_slug' => $row['slug_produit'],
            'date' => $row['date_creation']
        ];
    }
    return $testimonials;
}

/**
 * Récupère les avis récents pour le slider
 */
public function getRecentReviews($limit = 10) {
    $this->db->select('a.*, u.nom, u.prenom, u.avatar_url, p.nom_produit, p.slug_produit');
    $this->db->from('avis_produits a');
    $this->db->join('utilisateurs u', 'a.id_utilisateur = u.id_utilisateur');
    $this->db->join('produits p', 'a.id_produit = p.id_produit');
    $this->db->where('a.est_approuve', 1);
    $this->db->order_by('a.date_creation', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    return $query->result_array();
}

// ========== STATISTIQUES POUR LA HOME ==========

/**
 * Récupère les statistiques du site
 * Nombre de produits, vendeurs, clients, commandes
 */
public function getHomeStats() {
    // Nombre de produits actifs
    $totalProducts = $this->db->where('est_actif', 1)
                              ->where('statut', 'actif')
                              ->count_all_results('produits');
    
    // Nombre de vendeurs approuvés (profil vendeur uniquement)
    $totalSellers = $this->db->select('COUNT(*) as count')
                             ->from('vendeurs v')
                             ->join('utilisateur_profils up', 'up.id_utilisateur = v.id_utilisateur AND up.id_profil = 4')
                             ->where('v.statut', 'actif')
                             ->where('v.est_approuve', 1)
                             ->get()
                             ->row_array();
    $totalSellers = $totalSellers ? (int)$totalSellers['count'] : 0;
    
    // Nombre de clients (utilisateurs avec profil client)
    $totalCustomers = $this->db->where('est_actif', 1)
                               ->count_all_results('utilisateurs');
    
    // Nombre de commandes livrées
    $totalOrders = $this->db->where('statut_commande', 'livre')
                            ->count_all_results('commandes');
    
    return [
        'products' => $totalProducts,
        'sellers' => $totalSellers,
        'customers' => $totalCustomers,
        'orders' => $totalOrders
    ];
}








// ========== MARQUES POPULAIRES ==========

/**
 * Récupère les marques populaires avec leurs logos
 */
public function getPopularBrands($limit = 8) {
    $this->db->select('p.marque, COUNT(p.id_produit) as count');
    $this->db->from('produits p');
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->where('p.marque IS NOT NULL');
    $this->db->where('p.marque !=', '');
    $this->db->group_by('p.marque');
    $this->db->order_by('count', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    return $query->result_array();
}

// ========== COUPONS ACTIFS ==========

/**
 * Récupère les coupons actifs pour la home
 */
public function getActiveCoupons($limit = 3) {
    $this->db->select('*');
    $this->db->from('coupons');
    $this->db->where('est_actif', 1);
    $this->db->where('date_debut <= NOW()', NULL, FALSE);
    $this->db->where('date_fin >= NOW()', NULL, FALSE);
    $this->db->order_by('date_fin', 'ASC');
    $this->db->limit($limit);
    $query = $this->db->get();
    return $query->result_array();
}

// ========== PRODUITS PAR VENDEUR (pour la section vendeurs) ==========

/**
 * Récupère les produits d'un vendeur avec limite
 */
public function getSellerProductsLimit($sellerId, $limit = 4) {
    $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                       p.note_moyenne,
                       (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
    $this->db->from('produits p');
    $this->db->where('p.id_vendeur', $sellerId);
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->order_by('p.nombre_ventes', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    return $query->result_array();
}






// ========== MÉTHODES POUR LA PAGE PRODUIT ==========

/**
 * Récupère le nombre total d'avis pour un produit
 * @param int $productId ID du produit
 * @return int Nombre d'avis
 */
public function countProductReviews($productId) {
    $this->db->where('id_produit', $productId);
    $this->db->where('est_approuve', 1);
    return $this->db->count_all_results('avis_produits');
}

/**
 * Récupère la moyenne des notes pour un produit
 * @param int $productId ID du produit
 * @return float Note moyenne
 */
public function getProductAverageRating($productId) {
    $this->db->select_avg('note', 'average');
    $this->db->where('id_produit', $productId);
    $this->db->where('est_approuve', 1);
    $query = $this->db->get('avis_produits');
    $result = $query->row_array();
    return round($result['average'] ?? 0, 1);
}

/**
 * Récupère la répartition des notes pour un produit
 * @param int $productId ID du produit
 * @return array Répartition des notes (5,4,3,2,1 étoiles)
 */
public function getProductRatingDistribution($productId) {
    $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    
    $this->db->select('note, COUNT(*) as count');
    $this->db->where('id_produit', $productId);
    $this->db->where('est_approuve', 1);
    $this->db->group_by('note');
    $query = $this->db->get('avis_produits');
    
    foreach ($query->result_array() as $row) {
        if (isset($distribution[$row['note']])) {
            $distribution[$row['note']] = $row['count'];
        }
    }
    
    return $distribution;
}

/**
 * Récupère les avis d'un produit avec pagination
 * @param int $productId ID du produit
 * @param int $limit Nombre d'avis par page
 * @param int $offset Décalage
 * @return array Avis
 */
public function getProductReviewsPaginated($productId, $limit = 5, $offset = 0) {
    $this->db->select('a.*, u.nom, u.prenom, u.avatar_url');
    $this->db->from('avis_produits a');
    $this->db->join('utilisateurs u', 'a.id_utilisateur = u.id_utilisateur');
    $this->db->where('a.id_produit', $productId);
    $this->db->where('a.est_approuve', 1);
    $this->db->order_by('a.date_creation', 'DESC');
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Vérifie si l'utilisateur a déjà acheté ce produit
 * @param int $userId ID utilisateur
 * @param int $productId ID produit
 * @return bool
 */
public function hasUserPurchasedProduct($userId, $productId) {
    if (!$userId) return false;
    
    $this->db->select('ac.id_article');
    $this->db->from('articles_commande ac');
    $this->db->join('commandes c', 'ac.id_commande = c.id_commande');
    $this->db->where('c.id_utilisateur', $userId);
    $this->db->where('ac.id_produit', $productId);
    $this->db->where('c.statut_commande', 'livre');
    
    return $this->db->count_all_results() > 0;
}

/**
 * Vérifie si l'utilisateur a déjà laissé un avis sur ce produit
 * @param int $userId ID utilisateur
 * @param int $productId ID produit
 * @return bool
 */
public function hasUserReviewedProduct($userId, $productId) {
    if (!$userId) return false;
    
    $this->db->where('id_utilisateur', $userId);
    $this->db->where('id_produit', $productId);
    return $this->db->count_all_results('avis_produits') > 0;
}

/**
 * Ajoute un avis sur un produit
 * @param array $data Données de l'avis
 * @return bool
 */
public function addProductReview($data) {
    $review_data = [
        'id_produit' => $data['product_id'],
        'id_utilisateur' => $data['user_id'],
        'id_commande' => $data['commande_id'] ?? null,
        'note' => $data['rating'],
        'titre' => $data['title'] ?? null,
        'commentaire' => $data['comment'],
        'achat_verifie' => $data['verified_purchase'] ?? 0,
        'est_approuve' => 0,
        'date_creation' => date('Y-m-d H:i:s')
    ];
    
    $result = $this->db->insert('avis_produits', $review_data);
    
    if ($result) {
        // Mettre à jour la note moyenne du produit
        $avgRating = $this->getProductAverageRating($data['product_id']);
        $totalReviews = $this->countProductReviews($data['product_id']);
        
        $this->db->where('id_produit', $data['product_id']);
        $this->db->update('produits', [
            'note_moyenne' => $avgRating,
            'nombre_avis' => $totalReviews
        ]);
    }
    
    return $result;
}

/**
 * Récupère la quantité en stock d'une variante
 * @param int $variantId ID de la variante
 * @return int Quantité disponible
 */
public function getVariantStock($variantId) {
    $this->db->select('quantite_actuelle');
    $this->db->where('id_variante', $variantId);
    $this->db->where('est_actif', 1);
    $query = $this->db->get('variantes_produit');
    $result = $query->row_array();
    return $result ? (int)$result['quantite_actuelle'] : 0;
}

/**
 * Récupère les attributs d'une variante par son ID
 * @param int $variantId ID de la variante
 * @return array|null
 */
public function getVariantById($variantId) {
    $this->db->select('*');
    $this->db->from('variantes_produit');
    $this->db->where('id_variante', $variantId);
    $this->db->where('est_actif', 1);
    $query = $this->db->get();
    return $query->row_array();
}

/**
 * Récupère les produits récemment consultés par l'utilisateur (session)
 * @param array $productIds IDs des produits récents
 * @return array Produits récents
 */
public function getRecentProducts($productIds) {
    if (empty($productIds)) return [];
    
    $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                       p.note_moyenne, p.nombre_avis,
                       (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
    $this->db->from('produits p');
    $this->db->where_in('p.id_produit', $productIds);
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $query = $this->db->get();
    
    // Conserver l'ordre original
    $products = $query->result_array();
    $orderedProducts = [];
    foreach ($productIds as $id) {
        foreach ($products as $product) {
            if ($product['id_produit'] == $id) {
                $orderedProducts[] = $product;
                break;
            }
        }
    }
    return $orderedProducts;
}

/**
 * Récupère le vendeur d'un produit avec ses infos complètes
 * @param int $sellerId ID du vendeur
 * @return array|null
 */
public function getSellerDetails($sellerId) {
    if (!$sellerId) return null;
    
    $this->db->select('v.*, u.nom, u.prenom, u.email, u.telephone, u.avatar_url');
    $this->db->from('vendeurs v');
    $this->db->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur');
    $this->db->where('v.id_vendeur', $sellerId);
    $this->db->where('v.statut', 'actif');
    $query = $this->db->get();
    return $query->row_array();
}

/**
 * Récupère les questions/réponses d'un produit (si table existe)
 * @param int $productId ID du produit
 * @return array
 */
public function getProductQuestions($productId, $limit = 5) {
    // Vérifier si la table existe
    if (!$this->db->table_exists('questions_produits')) {
        return [];
    }
    
    $this->db->select('q.*, u.nom, u.prenom, u.avatar_url');
    $this->db->from('questions_produits q');
    $this->db->join('utilisateurs u', 'q.id_utilisateur = u.id_utilisateur');
    $this->db->where('q.id_produit', $productId);
    $this->db->where('q.est_approuve', 1);
    $this->db->order_by('q.date_creation', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Calcule le pourcentage de recommandation du produit
 * @param int $productId ID du produit
 * @return int Pourcentage (0-100)
 */
public function getProductRecommendationPercent($productId) {
    $total = $this->countProductReviews($productId);
    if ($total == 0) return 0;
    
    $positiveReviews = $this->db->where('id_produit', $productId)
                                ->where('note >=', 4)
                                ->where('est_approuve', 1)
                                ->count_all_results('avis_produits');
    
    return round(($positiveReviews / $total) * 100);
}





// ========== MÉTHODES POUR LA PAGE SHOP ==========

/**
 * Compte les produits d'une catégorie spécifique (sans sous-catégories)
 * @param int $categoryId ID de la catégorie
 * @return int Nombre de produits
 */
public function countProductsByCategoryOnly($categoryId) {
    $this->db->from('produits p');
    $this->db->where('p.id_categorie', $categoryId);
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->where('p.quantite_actuelle >', 0);
    return $this->db->count_all_results();
}

/**
 * Récupère les produits d'une catégorie spécifique (sans sous-catégories)
 * @param int $categoryId ID de la catégorie
 * @param string $sort Type de tri
 * @param int $perPage Nombre par page
 * @param int $offset Décalage
 * @return array Produits
 */
public function getProductsByCategoryOnly($categoryId, $sort, $perPage, $offset) {
    $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                       p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                       p.id_vendeur, v.nom_boutique, v.slug_boutique,
                       (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
    $this->db->from('produits p');
    $this->db->join('vendeurs v', 'p.id_vendeur = v.id_vendeur', 'left');
    
    if (!empty($categoryId)) {
        $this->db->where('p.id_categorie', $categoryId);
    }
    
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->where('p.quantite_actuelle >', 0);
    
    // Application du tri
    switch ($sort) {
        case 'price_asc':
            $this->db->order_by('COALESCE(p.prix_promo, p.prix_base)', 'ASC');
            break;
        case 'price_desc':
            $this->db->order_by('COALESCE(p.prix_promo, p.prix_base)', 'DESC');
            break;
        case 'rating':
            $this->db->order_by('p.note_moyenne', 'DESC');
            break;
        case 'bestselling':
            $this->db->order_by('p.nombre_ventes', 'DESC');
            break;
        default:
            $this->db->order_by('p.date_creation', 'DESC');
            break;
    }
    
    $this->db->limit($perPage, $offset);
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Récupère les filtres pour la page shop (prix min/max)
 * @return array Filtres disponibles
 */

/**
 * Récupère les produits avec filtres (prix, marques, note)
 * @param array $filters Filtres appliqués
 * @param string $sort Type de tri
 * @param int $perPage Nombre par page
 * @param int $offset Décalage
 * @return array Produits
 */






/**
 * Récupère l'ID d'une catégorie par son ID (avec ses sous-catégories)
 * @param int $categoryId ID de la catégorie
 * @return array Liste des IDs (catégorie + sous-catégories)
 */




/**
 * Récupère tous les produits actifs (sans filtre de catégorie)
 */
public function getAllProducts($sort, $perPage, $offset) {
    $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                       p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                       p.id_vendeur,
                       (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
    $this->db->from('produits p');
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->where('p.quantite_actuelle >', 0);
    
    // Tri
    switch ($sort) {
        case 'price_asc':
            $this->db->order_by('COALESCE(p.prix_promo, p.prix_base)', 'ASC');
            break;
        case 'price_desc':
            $this->db->order_by('COALESCE(p.prix_promo, p.prix_base)', 'DESC');
            break;
        case 'rating':
            $this->db->order_by('p.note_moyenne', 'DESC');
            break;
        case 'bestselling':
            $this->db->order_by('p.nombre_ventes', 'DESC');
            break;
        default:
            $this->db->order_by('p.date_creation', 'DESC');
            break;
    }
    
    $this->db->limit($perPage, $offset);
    $query = $this->db->get();
    $results = $query->result_array();
    
    // Calculer le prix effectif
    foreach ($results as &$product) {
        $prixBase = floatval($product['prix_base']);
        $prixPromo = $product['prix_promo'] ? floatval($product['prix_promo']) : null;
        $product['prix_effectif'] = ($prixPromo && $prixPromo < $prixBase) ? $prixPromo : $prixBase;
    }
    
    return $results;
}

/**
 * Compte tous les produits actifs
 */
public function countAllActiveProducts() {
    $this->db->from('produits p');
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->where('p.quantite_actuelle >', 0);
    return $this->db->count_all_results();
}




/**
 * Récupère les catégories principales avec comptage de produits
 */
public function getMainCategoriesWithCount() {
    $this->db->select('c.id_categorie, c.nom_categorie, c.slug_categorie, c.url_image, c.ordre_affichage,
                       COUNT(p.id_produit) as product_count');
    $this->db->from('categories c');
    $this->db->join('produits p', 'p.id_categorie = c.id_categorie AND p.est_actif = 1 AND p.statut = "actif" AND p.quantite_actuelle > 0', 'left');
    $this->db->where('c.est_actif', 1);
    $this->db->where('c.niveau', 0);
    $this->db->group_by('c.id_categorie');
    $this->db->order_by('c.ordre_affichage', 'ASC');
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Récupère une catégorie par son ID
 */
public function getCategoryById($id) {
    $this->db->select('*');
    $this->db->from('categories');
    $this->db->where('id_categorie', $id);
    $this->db->where('est_actif', 1);
    $query = $this->db->get();
    return $query->row_array();
}

/**
 * Récupère les produits filtrés
 */
public function getFilteredProducts($filters, $sort, $limit, $offset) {
    $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                       p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                       p.marque, p.description,
                       (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
    $this->db->from('produits p');
    
    // Filtre catégorie
    if (!empty($filters['category_id'])) {
        $this->db->where('p.id_categorie', $filters['category_id']);
    }
    
    // Filtre prix
    if (!empty($filters['min_price'])) {
        $this->db->where('COALESCE(p.prix_promo, p.prix_base) >=', $filters['min_price']);
    }
    if (!empty($filters['max_price'])) {
        $this->db->where('COALESCE(p.prix_promo, p.prix_base) <=', $filters['max_price']);
    }
    
    // Filtre marques
    if (!empty($filters['brands']) && is_array($filters['brands'])) {
        $this->db->where_in('p.marque', $filters['brands']);
    }
    
    // Filtre note
    if (!empty($filters['rating'])) {
        $this->db->where('p.note_moyenne >=', $filters['rating'] - 0.5);
    }
    
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->where('p.quantite_actuelle >', 0);
    
    // Tri
    switch ($sort) {
        case 'price_asc':
            $this->db->order_by('COALESCE(p.prix_promo, p.prix_base)', 'ASC');
            break;
        case 'price_desc':
            $this->db->order_by('COALESCE(p.prix_promo, p.prix_base)', 'DESC');
            break;
        case 'rating':
            $this->db->order_by('p.note_moyenne', 'DESC');
            break;
        case 'bestselling':
            $this->db->order_by('p.nombre_ventes', 'DESC');
            break;
        default:
            $this->db->order_by('p.date_creation', 'DESC');
            break;
    }
    
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $products = $query->result_array();
    
    // Calculer le prix effectif
    foreach ($products as &$product) {
        $prixBase = floatval($product['prix_base']);
        $prixPromo = $product['prix_promo'] ? floatval($product['prix_promo']) : null;
        $product['prix_effectif'] = ($prixPromo && $prixPromo < $prixBase) ? $prixPromo : $prixBase;
    }
    
    return $products;
}

/**
 * Compte les produits avec filtres
 */
public function countProductsWithFilters($filters) {
    $this->db->from('produits p');
    
    if (!empty($filters['category_id'])) {
        $this->db->where('p.id_categorie', $filters['category_id']);
    }
    
    if (!empty($filters['min_price'])) {
        $this->db->where('COALESCE(p.prix_promo, p.prix_base) >=', $filters['min_price']);
    }
    if (!empty($filters['max_price'])) {
        $this->db->where('COALESCE(p.prix_promo, p.prix_base) <=', $filters['max_price']);
    }
    
    if (!empty($filters['brands']) && is_array($filters['brands'])) {
        $this->db->where_in('p.marque', $filters['brands']);
    }
    
    if (!empty($filters['rating'])) {
        $this->db->where('p.note_moyenne >=', $filters['rating'] - 0.5);
    }
    
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->where('p.quantite_actuelle >', 0);
    
    return $this->db->count_all_results();
}

/**
 * Récupère les comptages de produits par catégorie avec les filtres actuels
 */
public function getCategoryProductCounts($currentFilters = []) {
    $this->db->select('c.id_categorie, COUNT(p.id_produit) as count');
    $this->db->from('categories c');
    $this->db->join('produits p', 'p.id_categorie = c.id_categorie AND p.est_actif = 1 AND p.statut = "actif" AND p.quantite_actuelle > 0', 'left');
    
    // Appliquer les filtres de prix, marques, note aux comptages
    if (!empty($currentFilters['min_price'])) {
        $this->db->where('COALESCE(p.prix_promo, p.prix_base) >=', $currentFilters['min_price']);
    }
    if (!empty($currentFilters['max_price'])) {
        $this->db->where('COALESCE(p.prix_promo, p.prix_base) <=', $currentFilters['max_price']);
    }
    if (!empty($currentFilters['brands']) && is_array($currentFilters['brands'])) {
        $this->db->where_in('p.marque', $currentFilters['brands']);
    }
    if (!empty($currentFilters['rating'])) {
        $this->db->where('p.note_moyenne >=', $currentFilters['rating'] - 0.5);
    }
    
    $this->db->where('c.est_actif', 1);
    $this->db->where('c.niveau', 0);
    $this->db->group_by('c.id_categorie');
    
    $query = $this->db->get();
    $results = $query->result_array();
    
    $counts = [];
    foreach ($results as $row) {
        $counts[$row['id_categorie']] = $row['count'];
    }
    
    return $counts;
}

/**
 * Récupère les filtres pour la page shop
 */
public function getShopFilters() {
    // Prix min et max
    $this->db->select_min('COALESCE(prix_promo, prix_base)', 'min_price');
    $this->db->select_max('COALESCE(prix_promo, prix_base)', 'max_price');
    $this->db->where('est_actif', 1);
    $this->db->where('statut', 'actif');
    $this->db->where('quantite_actuelle >', 0);
    $priceRange = $this->db->get('produits')->row_array();
    
    // Marques disponibles
    $this->db->distinct();
    $this->db->select('marque');
    $this->db->from('produits');
    $this->db->where('est_actif', 1);
    $this->db->where('statut', 'actif');
    $this->db->where('quantite_actuelle >', 0);
    $this->db->where('marque IS NOT NULL');
    $this->db->where('marque !=', '');
    $this->db->order_by('marque', 'ASC');
    $brands = $this->db->get()->result_array();
    
    // Répartition des notes
    $ratingDistribution = [];
    for ($i = 5; $i >= 1; $i--) {
        $this->db->select('COUNT(*) as count');
        $this->db->from('produits');
        $this->db->where('est_actif', 1);
        $this->db->where('statut', 'actif');
        $this->db->where('quantite_actuelle >', 0);
        $this->db->where('note_moyenne >=', $i - 0.5);
        $result = $this->db->get()->row_array();
        $ratingDistribution[$i] = (int)($result['count'] ?? 0);
    }
    
    return [
        'min_price' => floor($priceRange['min_price'] ?? 0),
        'max_price' => ceil($priceRange['max_price'] ?? 1000000),
        'brands' => array_column($brands, 'marque'),
        'rating_distribution' => $ratingDistribution
    ];
}


// ========== MÉTHODES POUR LA PAGE VENDEURS ==========

/**
 * Récupère tous les vendeurs actifs avec pagination
 * @param int $limit Nombre par page
 * @param int $offset Décalage
 * @return array Liste des vendeurs
 */
public function getAllSellersPaginated($limit = 12, $offset = 0) {
    $this->db->select('v.id_vendeur, v.nom_boutique, v.slug_boutique, v.logo_boutique, v.description,
                       v.note_moyenne, v.nombre_avis, v.total_commandes, v.date_creation,
                       v.telephone, v.whatsapp,
                       v.id_province, v.id_commune, v.id_quartier,
                       v.latitude, v.longitude,
                       u.prenom, u.nom, u.email');
    $this->db->from('vendeurs v');
    $this->db->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur');
    $this->db->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur AND up.id_profil = 4');
    $this->db->where('v.statut', 'actif');
    $this->db->where('v.est_approuve', 1);
    $this->db->order_by('v.total_commandes', 'DESC');
    $this->db->order_by('v.note_moyenne', 'DESC');
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $sellers = $query->result_array();
    
    // Ajouter le nombre de produits pour chaque vendeur
    foreach ($sellers as &$seller) {
        $seller['product_count'] = $this->countSellerProducts($seller['id_vendeur']);
        // Calculer le pourcentage de stock moyen (simulé basé sur les produits)
        $seller['stock_percentage'] = $this->getSellerStockPercentage($seller['id_vendeur']);
        // Formatter l'adresse complète
        $seller['full_address'] = $this->getSellerFullAddress($seller);
    }
    
    return $sellers;
}

/**
 * Compte le nombre total de vendeurs actifs
 * @return int Nombre de vendeurs
 */
public function countAllSellers() {
    $this->db->from('vendeurs v');
    $this->db->join('utilisateur_profils up', 'up.id_utilisateur = v.id_utilisateur AND up.id_profil = 4');
    $this->db->where('v.statut', 'actif');
    $this->db->where('v.est_approuve', 1);
    return $this->db->count_all_results();
}

/**
 * Compte le nombre de produits d'un vendeur
 * @param int $sellerId ID du vendeur
 * @return int Nombre de produits
 */
public function countSellerProducts($sellerId) {
    $this->db->select('COUNT(*) as count');
    $this->db->from('produits');
    $this->db->where('id_vendeur', $sellerId);
    $this->db->where('est_actif', 1);
    $this->db->where('statut', 'actif');
    $query = $this->db->get();
    $result = $query->row_array();
    return $result ? (int)$result['count'] : 0;
}

/**
 * Calcule le pourcentage de stock moyen du vendeur
 * @param int $sellerId ID du vendeur
 * @return int Pourcentage (0-100)
 */
public function getSellerStockPercentage($sellerId) {
    $this->db->select('AVG(quantite_actuelle / NULLIF(seuil_stock_bas + quantite_actuelle, 0) * 100) as avg_stock');
    $this->db->from('produits');
    $this->db->where('id_vendeur', $sellerId);
    $this->db->where('est_actif', 1);
    $this->db->where('statut', 'actif');
    $query = $this->db->get();
    $result = $query->row_array();
    $percentage = round($result['avg_stock'] ?? 50);
    
    // Retourner un pourcentage entre 20 et 95% pour l'affichage
    return min(95, max(20, $percentage));
}

/**
 * Récupère l'adresse complète formatée d'un vendeur
 * @param array $seller Données du vendeur
 * @return string Adresse formatée
 */
public function getSellerFullAddress($seller) {
    $addressParts = [];
    
    if (!empty($seller['id_province'])) {
        $province = $this->getProvinceName($seller['id_province']);
        if ($province) $addressParts[] = $province;
    }
    
    if (!empty($seller['id_commune'])) {
        $commune = $this->getCommuneName($seller['id_commune']);
        if ($commune) $addressParts[] = $commune;
    }
    
    if (empty($addressParts)) {
        return 'Adresse non renseignée';
    }
    
    return implode(', ', $addressParts);
}

/**
 * Récupère le nom d'une province par son ID
 * @param int $provinceId ID de la province
 * @return string|null Nom de la province
 */
public function getProvinceName($provinceId) {
    $this->db->select('province_name');
    $this->db->from('provinces');
    $this->db->where('id_province', $provinceId);
    $query = $this->db->get();
    $result = $query->row_array();
    return $result ? $result['province_name'] : null;
}

/**
 * Récupère le nom d'une commune par son ID
 * @param int $communeId ID de la commune
 * @return string|null Nom de la commune
 */
public function getCommuneName($communeId) {
    $this->db->select('commune_name');
    $this->db->from('communes');
    $this->db->where('id_commune', $communeId);
    $query = $this->db->get();
    $result = $query->row_array();
    return $result ? $result['commune_name'] : null;
}

/**
 * Récupère les produits populaires d'un vendeur (pour affichage en aperçu)
 * @param int $sellerId ID du vendeur
 * @param int $limit Nombre de produits
 * @return array Produits populaires
 */
public function getSellerTopProducts($sellerId, $limit = 3) {
    $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                       (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
    $this->db->from('produits p');
    $this->db->where('p.id_vendeur', $sellerId);
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->order_by('p.nombre_ventes', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Récupère un vendeur par son slug avec tous ses détails
 * @param string $slug Slug de la boutique
 * @return array|null Détails du vendeur
 */
public function getSellerBySlugWithDetails($slug) {
    return $this->getSellerBySlugWithFullDetails($slug);
}

/**
 * Récupère les avis des clients pour un vendeur
 * @param int $sellerId ID du vendeur
 * @param int $limit Nombre d'avis
 * @return array Avis des clients
 */
public function getSellerReviews($sellerId, $limit = 5) {
    $this->db->select('e.*, u.nom, u.prenom, u.avatar_url');
    $this->db->from('evaluations_vendeurs e');
    $this->db->join('utilisateurs u', 'e.id_utilisateur = u.id_utilisateur');
    $this->db->where('e.id_vendeur', $sellerId);
    $this->db->where('e.est_approuve', 1);
    $this->db->order_by('e.date_creation', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Récupère la répartition des notes pour un vendeur
 * @param int $sellerId ID du vendeur
 * @return array Distribution des notes
 */
public function getSellerRatingDistribution($sellerId) {
    $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    
    $this->db->select('note_globale, COUNT(*) as count');
    $this->db->from('evaluations_vendeurs');
    $this->db->where('id_vendeur', $sellerId);
    $this->db->where('est_approuve', 1);
    $this->db->group_by('note_globale');
    $query = $this->db->get();
    
    foreach ($query->result_array() as $row) {
        if (isset($distribution[$row['note_globale']])) {
            $distribution[$row['note_globale']] = $row['count'];
        }
    }
    
    return $distribution;
}

/**
 * Récupère les vendeurs en vedette (mieux notés)
 * @param int $limit Nombre de vendeurs
 * @return array Vendeurs en vedette
 */
public function getFeaturedSellersWithProducts($limit = 6) {
    $this->db->select('v.id_vendeur, v.nom_boutique, v.slug_boutique, v.logo_boutique, v.description,
                       v.note_moyenne, v.nombre_avis, v.total_commandes,
                       u.prenom, u.nom');
    $this->db->from('vendeurs v');
    $this->db->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur');
    $this->db->join('utilisateur_profils up', 'up.id_utilisateur = u.id_utilisateur AND up.id_profil = 4');
    $this->db->where('v.statut', 'actif');
    $this->db->where('v.est_approuve', 1);
    $this->db->order_by('v.note_moyenne', 'DESC');
    $this->db->order_by('v.total_commandes', 'DESC');
    $this->db->limit($limit);
    $query = $this->db->get();
    $sellers = $query->result_array();
    
    foreach ($sellers as &$seller) {
        $seller['product_count'] = $this->countSellerProducts($seller['id_vendeur']);
        $seller['featured_products'] = $this->getSellerTopProducts($seller['id_vendeur'], 3);
    }
    
    return $sellers;
}



// ========== MÉTHODES POUR LA PAGE DÉTAIL D'UN VENDEUR ==========

/**
 * Récupère un vendeur par son slug avec tous les détails
 * @param string $slug Slug de la boutique
 * @return array|null Données du vendeur
 */
public function getSellerBySlugWithFullDetails($slug) {
    $this->db->select('v.*, u.nom, u.prenom, u.email, u.telephone, u.avatar_url');
    $this->db->from('vendeurs v');
    $this->db->join('utilisateurs u', 'v.id_utilisateur = u.id_utilisateur');
    $this->db->where('v.slug_boutique', $slug);
    $this->db->where('v.statut', 'actif');
    $this->db->where('v.est_approuve', 1);
    $query = $this->db->get();
    $seller = $query->row_array();
    
    if ($seller) {
        // Nombre de produits
        $seller['product_count'] = $this->countSellerProducts($seller['id_vendeur']);
        // Nombre de followers (utilisateurs qui ont acheté chez ce vendeur)
        $seller['followers'] = $this->countSellerFollowers($seller['id_vendeur']);
        // Nombre d'avis
        $seller['total_reviews'] = $this->countSellerReviews($seller['id_vendeur']);
        // Adresse formatée
        $seller['full_address'] = $this->getSellerFullAddress($seller);
    }
    
    return $seller;
}

/**
 * Compte le nombre de followers d'un vendeur (clients uniques)
 * @param int $sellerId ID du vendeur
 * @return int Nombre de followers
 */
public function countSellerFollowers($sellerId) {
    $this->db->select('COUNT(DISTINCT c.id_utilisateur) as count');
    $this->db->from('commandes c');
    $this->db->join('articles_commande ac', 'c.id_commande = ac.id_commande');
    $this->db->where('ac.id_vendeur', $sellerId);
    $this->db->where('c.statut_commande', 'livre');
    $query = $this->db->get();
    $result = $query->row_array();
    return $result ? (int)$result['count'] : 0;
}

/**
 * Compte le nombre d'avis pour un vendeur
 * @param int $sellerId ID du vendeur
 * @return int Nombre d'avis
 */
public function countSellerReviews($sellerId) {
    $this->db->where('id_vendeur', $sellerId);
    $this->db->where('est_approuve', 1);
    return $this->db->count_all_results('evaluations_vendeurs');
}

/**
 * Récupère les produits d'un vendeur avec pagination
 * @param int $sellerId ID du vendeur
 * @param int $limit Nombre par page
 * @param int $offset Décalage
 * @return array Produits du vendeur
 */
public function getSellerProductsPaginated($sellerId, $limit = 12, $offset = 0) {
    $this->db->select('p.id_produit, p.nom_produit, p.slug_produit, p.prix_base, p.prix_promo,
                       p.note_moyenne, p.nombre_avis, p.quantite_actuelle, p.nombre_ventes,
                       p.description_courte, p.description,
                       (SELECT url_image FROM images_produit WHERE id_produit = p.id_produit AND est_principale = 1 LIMIT 1) as image_url');
    $this->db->from('produits p');
    $this->db->where('p.id_vendeur', $sellerId);
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->order_by('p.nombre_ventes', 'DESC');
    $this->db->order_by('p.date_creation', 'DESC');
    $this->db->limit($limit, $offset);
    $query = $this->db->get();
    $products = $query->result_array();
    
    // Calculer le prix effectif pour chaque produit
    foreach ($products as &$product) {
        $prixBase = floatval($product['prix_base']);
        $prixPromo = $product['prix_promo'] ? floatval($product['prix_promo']) : null;
        $product['prix_effectif'] = ($prixPromo && $prixPromo < $prixBase) ? $prixPromo : $prixBase;
        $product['discount_percent'] = $prixPromo ? round((($prixBase - $prixPromo) / $prixBase) * 100) : 0;
        $product['stock_status'] = $product['quantite_actuelle'] > 0 ? 'instock' : 'outofstock';
    }
    
    return $products;
}

/**
 * Récupère les catégories des produits d'un vendeur (pour les filtres)
 * @param int $sellerId ID du vendeur
 * @return array Catégories avec comptage
 */
public function getSellerProductCategories($sellerId) {
    $this->db->select('c.id_categorie, c.nom_categorie, c.slug_categorie, COUNT(p.id_produit) as product_count');
    $this->db->from('categories c');
    $this->db->join('produits p', 'p.id_categorie = c.id_categorie');
    $this->db->where('p.id_vendeur', $sellerId);
    $this->db->where('p.est_actif', 1);
    $this->db->where('p.statut', 'actif');
    $this->db->group_by('c.id_categorie');
    $this->db->order_by('product_count', 'DESC');
    $query = $this->db->get();
    return $query->result_array();
}

/**
 * Récupère la plage de prix des produits d'un vendeur
 * @param int $sellerId ID du vendeur
 * @return array Min et max price
 */
public function getSellerPriceRange($sellerId) {
    $this->db->select_min('COALESCE(prix_promo, prix_base)', 'min_price');
    $this->db->select_max('COALESCE(prix_promo, prix_base)', 'max_price');
    $this->db->from('produits');
    $this->db->where('id_vendeur', $sellerId);
    $this->db->where('est_actif', 1);
    $this->db->where('statut', 'actif');
    $query = $this->db->get();
    return $query->row_array();
}

/**
 * Récupère la note moyenne d'un vendeur formatée
 * @param int $sellerId ID du vendeur
 * @return array Note moyenne et nombre d'étoiles pleines/vides
 */
public function getSellerRatingFormatted($sellerId) {
    $seller = $this->db->select('note_moyenne, nombre_avis')
                       ->where('id_vendeur', $sellerId)
                       ->get('vendeurs')
                       ->row_array();
    
    $rating = floatval($seller['note_moyenne'] ?? 0);
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    
    return [
        'average' => $rating,
        'full_stars' => $fullStars,
        'half_star' => $halfStar,
        'empty_stars' => 5 - $fullStars - ($halfStar ? 1 : 0),
        'total_reviews' => intval($seller['nombre_avis'] ?? 0)
    ];
}


}
?>
