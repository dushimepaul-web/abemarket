<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// =============================================
// ROUTES POUR UTILISATEURS
// =============================================
$route['Profils'] = 'Utilisateurs/Profils/index';
$route['Profils/add'] = 'Utilisateurs/Profils/add';
$route['Profils/edit/(:num)'] = 'Utilisateurs/Profils/edit/$1';
$route['Profils/delete/(:num)'] = 'Utilisateurs/Profils/delete/$1';
$route['Profils/view/(:num)'] = 'Utilisateurs/Profils/view/$1';

$route['Profile'] = 'Utilisateurs/Profile/index';
$route['Profile/update'] = 'Utilisateurs/Profile/update';
$route['Profile/changepassword'] = 'Utilisateurs/Profile/changepassword';

$route['Roles'] = 'Utilisateurs/Roles/index';
$route['Roles/assigner'] = 'Utilisateurs/Roles/assigner';
$route['Roles/get_user_roles/(:num)'] = 'Utilisateurs/Roles/get_user_roles/$1';
$route['Roles/ajouter_profil'] = 'Utilisateurs/Roles/ajouter_profil';
$route['Roles/modifier_profil/(:num)'] = 'Utilisateurs/Roles/modifier_profil/$1';
$route['Roles/supprimer_profil/(:num)'] = 'Utilisateurs/Roles/supprimer_profil/$1';
$route['Roles/get_user_details/(:num)'] = 'Utilisateurs/Roles/get_user_details/$1';
$route['Roles/modifier_utilisateur'] = 'Utilisateurs/Roles/modifier_utilisateur';
$route['Roles/supprimer_utilisateur/(:num)'] = 'Utilisateurs/Roles/supprimer_utilisateur/$1';

$route['Customers'] = 'Utilisateurs/Customers/index';
$route['Customers/view/(:num)'] = 'Utilisateurs/Customers/view/$1';
$route['Customers/edit/(:num)'] = 'Utilisateurs/Customers/edit/$1';
$route['Customers/delete/(:num)'] = 'Utilisateurs/Customers/delete/$1';
$route['Customers/toggle_status/(:num)'] = 'Utilisateurs/Customers/toggle_status/$1';

// =============================================
// ROUTES POUR VENDEURS
// =============================================
$route['Sellers'] = 'Utilisateurs/Sellers/index';
$route['Sellers/index'] = 'Utilisateurs/Sellers/index';
$route['Sellers/add'] = 'Utilisateurs/Sellers/add';
$route['Sellers/view/(:num)'] = 'Utilisateurs/Sellers/view/$1';
$route['Sellers/edit/(:num)'] = 'Utilisateurs/Sellers/edit/$1';
$route['Sellers/delete/(:num)'] = 'Utilisateurs/Sellers/delete/$1';
$route['Sellers/approve/(:num)'] = 'Utilisateurs/Sellers/approve/$1';
$route['Sellers/suspend/(:num)'] = 'Utilisateurs/Sellers/suspend/$1';
$route['Sellers/ban/(:num)'] = 'Utilisateurs/Sellers/ban/$1';
$route['Sellers/search_users'] = 'Utilisateurs/Sellers/search_users';
$route['Sellers/get_user_details'] = 'Utilisateurs/Sellers/get_user_details';
$route['Sellers/get_communes'] = 'Utilisateurs/Sellers/get_communes';
$route['Sellers/get_quartiers'] = 'Utilisateurs/Sellers/get_quartiers';

// =============================================
// ROUTES POUR CATEGORIES
// =============================================
$route['Categories'] = 'Produits/Categories/index';
$route['categories'] = 'Produits/Categories/index';
$route['categories/index'] = 'Produits/Categories/index';
$route['categories/index/(:num)'] = 'Produits/Categories/index/$1';
$route['categories/add'] = 'Produits/Categories/add';
$route['categories/edit/(:any)'] = 'Produits/Categories/edit/$1';
$route['categories/delete/(:any)'] = 'Produits/Categories/delete/$1';
$route['categories/toggle_status/(:any)'] = 'Produits/Categories/toggle_status/$1';




// ==================== ROUTES POUR LA LOCALISATION (PROVINCES, COMMUNES, QUARTIERS) ====================

// ----- Provinces -----
$route['provinces']                             = 'Adresse/Location/provinces';
$route['province/add']                          = 'Adresse/Location/province_add_edit';
$route['province/edit/(:num)']                  = 'Adresse/Location/province_add_edit/$1';
$route['province/detail/(:num)']                = 'Adresse/Location/province_detail/$1';
$route['province/delete/(:num)']                = 'Adresse/Location/supprimer/province/$1';

// ----- Communes -----
$route['communes']                              = 'Adresse/Location/communes';
$route['communes/(:num)']                       = 'Adresse/Location/communes/$1';  // communes par province
$route['commune/add']                           = 'Adresse/Location/commune_add_edit';
$route['commune/add/(:num)']                    = 'Adresse/Location/commune_add_edit/$1';  // ajout avec province_id
$route['commune/edit/(:num)']                   = 'Adresse/Location/commune_add_edit/$1';
$route['commune/detail/(:num)']                 = 'Adresse/Location/commune_detail/$1';
$route['commune/delete/(:num)']                 = 'Adresse/Location/supprimer/commune/$1';

// ----- Quartiers -----
$route['quartiers']                             = 'Adresse/Location/quartiers';
$route['quartiers/(:num)']                      = 'Adresse/Location/quartiers/$1';  // quartiers par commune
$route['quartier/add']                          = 'Adresse/Location/quartier_add_edit';
$route['quartier/add/(:num)']                   = 'Adresse/Location/quartier_add_edit/$1';  // ajout avec commune_id
$route['quartier/edit/(:num)']                  = 'Adresse/Location/quartier_add_edit/$1';
$route['quartier/detail/(:num)']                = 'Adresse/Location/quartier_detail/$1';
$route['quartier/delete/(:num)']                = 'Adresse/Location/supprimer/quartier/$1';

// ----- AJAX Routes pour chargement dynamique -----
$route['api/get_communes/(:num)']               = 'Adresse/Location/get_communes_by_province/$1';
$route['api/get_quartiers/(:num)']              = 'Adresse/Location/get_quartiers_by_commune/$1';





// =============================================
$route['admin/about'] = 'about/index';
$route['admin/about/list'] = 'about/index';
$route['admin/about/edit/(:num)'] = 'about/edit/$1';
$route['admin/about/update/(:num)'] = 'about/update/$1';
$route['admin/about/toggle/(:num)'] = 'about/toggle/$1';
$route['admin/about/updateOrder'] = 'about/updateOrder';



// ------------------------------------------------------------
// Routes pour le module Blog
$route['admin/blog/posts'] = 'blog/posts/index';
$route['admin/blog/posts/add'] = 'blog/posts/add';
$route['admin/blog/posts/edit/(:num)'] = 'blog/posts/edit/$1';
$route['admin/blog/posts/delete/(:num)'] = 'blog/posts/delete/$1';
$route['admin/blog/posts/toggle/(:num)'] = 'blog/posts/toggle/$1';

$route['admin/blog/categories'] = 'blog/categories/index';
$route['admin/blog/categories/add'] = 'blog/categories/add';
$route['admin/blog/categories/edit/(:num)'] = 'blog/categories/edit/$1';
$route['admin/blog/categories/delete/(:num)'] = 'blog/categories/delete/$1';
$route['admin/blog/categories/toggle/(:num)'] = 'blog/categories/toggle/$1';

$route['admin/blog/comments'] = 'blog/comments/index';
$route['admin/blog/comments/approve/(:num)'] = 'blog/comments/approve/$1';
$route['admin/blog/comments/delete/(:num)'] = 'blog/comments/delete/$1';
// ------------------------------------------------------------
// 3. Module FAQ
// ------------------------------------------------------------
$route['admin/faq'] = 'faq/index';
$route['admin/faq/list'] = 'faq/index';
$route['admin/faq/add'] = 'faq/add';
$route['admin/faq/edit/(:num)'] = 'faq/edit/$1';
$route['admin/faq/update/(:num)'] = 'faq/update/$1';
$route['admin/faq/delete/(:num)'] = 'faq/delete/$1';
$route['admin/faq/toggle/(:num)'] = 'faq/toggle/$1';
$route['admin/faq/updateOrder'] = 'faq/updateOrder';

// ------------------------------------------------------------
// 4. Module TEAM (Membres de l'équipe)
// ------------------------------------------------------------
$route['admin/team'] = 'team/index';
$route['admin/team/list'] = 'team/index';
$route['admin/team/add'] = 'team/add';
$route['admin/team/edit/(:num)'] = 'team/edit/$1';
$route['admin/team/update/(:num)'] = 'team/update/$1';
$route['admin/team/delete/(:num)'] = 'team/delete/$1';
$route['admin/team/toggle/(:num)'] = 'team/toggle/$1';
$route['admin/team/updateOrder'] = 'team/updateOrder';

// ------------------------------------------------------------
// Routes pour le module Testimonials
$route['admin/testimonials'] = 'testimonials/index';
$route['admin/testimonials/add'] = 'testimonials/add';
$route['admin/testimonials/edit/(:num)'] = 'testimonials/edit/$1';
$route['admin/testimonials/delete/(:num)'] = 'testimonials/delete/$1';
$route['admin/testimonials/approve/(:num)'] = 'testimonials/approve/$1';
$route['admin/testimonials/updateOrder'] = 'testimonials/updateOrder';

// ------------------------------------------------------------
// 6. Module CONTACT MESSAGES (Messages de contact)
// ------------------------------------------------------------
$route['admin/contact/messages'] = 'contact_messages/index';
$route['admin/contact/messages/list'] = 'contact_messages/index';
$route['admin/contact/messages/view/(:num)'] = 'contact_messages/view/$1';
$route['admin/contact/messages/reply/(:num)'] = 'contact_messages/reply/$1';
$route['admin/contact/messages/delete/(:num)'] = 'contact_messages/delete/$1';
$route['admin/contact/messages/mark-read/(:num)'] = 'contact_messages/mark_read/$1';





// Articles commandés
$route['articles-commandes']                       = 'Commande/ArticleCommande/index';
$route['ArticleCommande']                       = 'Commande/ArticleCommande/index';
$route['article-commande/detail/(:num)']           = 'Commande/ArticleCommande/detail/$1';
$route['article-commande/change-statut']           = 'Commande/ArticleCommande/change_statut';
$route['article-commande/demander-retour']         = 'Commande/ArticleCommande/demander_retour';
$route['article-commande/exporter']                = 'Commande/ArticleCommande/exporter';

// Litiges
$route['litiges']                                  = 'Commande/LitigeCommande/index';
$route['litige/detail/(:num)']                     = 'Commande/LitigeCommande/detail/$1';
$route['litige/edit/(:num)']                       = 'Commande/LitigeCommande/edit/$1';
$route['litige/update/(:num)']                     = 'Commande/LitigeCommande/update/$1';
$route['litige/change-statut']                     = 'Commande/LitigeCommande/change_statut';
$route['litige/assigner-mediateur']                = 'Commande/LitigeCommande/assigner_mediateur';
$route['litige/creer']                             = 'Commande/LitigeCommande/creer';
$route['litige/save']                              = 'Commande/LitigeCommande/save';
$route['litige/exporter']                          = 'Commande/LitigeCommande/exporter';



// Articles commandés
$route['article-commande/add']                      = 'Commande/ArticleCommande/add_edit';
$route['article-commande/edit/(:num)']              = 'Commande/ArticleCommande/add_edit/$1';
$route['article-commande/save']                     = 'Commande/ArticleCommande/save';
$route['article-commande/update/(:num)']            = 'Commande/ArticleCommande/update/$1';
$route['article-commande/get-variantes']            = 'Commande/ArticleCommande/get_variantes';

// Litiges
$route['litige/add']                                = 'Commande/LitigeCommande/add_edit';
$route['litige/edit/(:num)']                        = 'Commande/LitigeCommande/add_edit/$1';
$route['litige/save']                               = 'Commande/LitigeCommande/save';
$route['litige/update/(:num)']                      = 'Commande/LitigeCommande/update/$1';
// Suppressions
$route['article-commande/delete/(:num)']           = 'Commande/ArticleCommande/delete/$1';
$route['litige/delete/(:num)']                     = 'Commande/LitigeCommande/delete/$1';



// Zones
$route['zones']                                 = 'Adresse/Location/zones';
$route['zones/(:num)']                          = 'Adresse/Location/zones/$1';
$route['zone/add']                              = 'Adresse/Location/zone_add_edit';
$route['zone/add/(:num)']                       = 'Adresse/Location/zone_add_edit/$1';
$route['zone/edit/(:num)']                      = 'Adresse/Location/zone_add_edit/$1';
$route['zone/detail/(:num)']                    = 'Adresse/Location/zone_detail/$1';
$route['zone/delete/(:num)']                    = 'Adresse/Location/supprimer/zone/$1';

// Collines
$route['collines']                              = 'Adresse/Location/collines';
$route['collines/(:num)']                       = 'Adresse/Location/collines/$1';
$route['colline/add']                           = 'Adresse/Location/colline_add_edit';
$route['colline/add/(:num)']                    = 'Adresse/Location/colline_add_edit/$1';
$route['colline/edit/(:num)']                   = 'Adresse/Location/colline_add_edit/$1';
$route['colline/detail/(:num)']                 = 'Adresse/Location/colline_detail/$1';
$route['colline/delete/(:num)']                 = 'Adresse/Location/supprimer/colline/$1';

// AJAX pour chargement hiérarchique
$route['api/get_zones/(:num)']                  = 'Adresse/Location/get_zones_by_quartier/$1';
$route['api/get_collines/(:num)']               = 'Adresse/Location/get_collines_by_zone/$1';



// Zones de livraison
$route['zone-livraison']                            = 'Adresse/ZoneLivraison/index';
$route['zone-livraison/add']                        = 'Adresse/ZoneLivraison/add_edit';
$route['zone-livraison/edit/(:num)']                = 'Adresse/ZoneLivraison/add_edit/$1';
$route['zone-livraison/save']                       = 'Adresse/ZoneLivraison/save';
$route['zone-livraison/detail/(:num)']              = 'Adresse/ZoneLivraison/detail/$1';
$route['zone-livraison/delete/(:num)']              = 'Adresse/ZoneLivraison/delete/$1';
$route['zone-livraison/toggle-statut/(:num)']       = 'Adresse/ZoneLivraison/toggle_statut/$1';
$route['zone-livraison/exporter']                   = 'Adresse/ZoneLivraison/exporter';

// AJAX
$route['zone-livraison/get-communes']               = 'Adresse/ZoneLivraison/get_communes';
$route['zone-livraison/get-quartiers']              = 'Adresse/ZoneLivraison/get_quartiers';



// Avis produits
// =============================================
// ROUTES POUR AVISPRODUIT (DANS LE MODULE PRODUITS)
// =============================================
// Routes pour AvisProduit
$route['avis-produits'] = 'Produits/AvisProduit/index';
$route['avis-produits/index/(:num)'] = 'Produits/AvisProduit/index/$1';
$route['avis-produit/detail/(:num)'] = 'Produits/AvisProduit/detail/$1';
$route['avis-produit/approuver/(:num)'] = 'Produits/AvisProduit/approuver/$1';
$route['avis-produit/rejeter/(:num)'] = 'Produits/AvisProduit/rejeter/$1';
$route['avis-produit/desapprouver/(:num)'] = 'Produits/AvisProduit/desapprouver/$1';
$route['avis-produit/enattente/(:num)'] = 'Produits/AvisProduit/enattente/$1';
$route['avis-produit/delete/(:num)'] = 'Produits/AvisProduit/delete/$1';
$route['avis-produit/repondre'] = 'Produits/AvisProduit/repondre';



// Routes pour ImageProduit
$route['ImageProduit/index/(:num)'] = 'Produits/ImageProduit/index/$1';
$route['ImageProduit/upload/(:num)'] = 'Produits/ImageProduit/upload/$1';
$route['ImageProduit/update/(:num)'] = 'Produits/ImageProduit/update/$1';
$route['ImageProduit/delete/(:num)'] = 'Produits/ImageProduit/delete/$1';
$route['ImageProduit/definir_principale/(:num)'] = 'Produits/ImageProduit/definir_principale/$1';
$route['ImageProduit/reordonner'] = 'Produits/ImageProduit/reordonner';





/// =============================================
// ROUTES POUR VARIANTEPRODUIT (DANS LE MODULE PRODUITS)
// =============================================

// Routes principales avec slug
$route['VarianteProduit/index/(:any)'] = 'Produits/VarianteProduit/index/$1';
$route['VarianteProduit/add/(:any)'] = 'Produits/VarianteProduit/add/$1';

// Routes avec slug + id (édition et suppression)
$route['VarianteProduit/edit/(:any)/(:num)'] = 'Produits/VarianteProduit/edit/$1/$2';
$route['VarianteProduit/delete/(:any)/(:num)'] = 'Produits/VarianteProduit/delete/$1/$2';

// Routes AJAX (sans paramètres)
$route['VarianteProduit/save'] = 'Produits/VarianteProduit/save';
$route['VarianteProduit/update_stock'] = 'Produits/VarianteProduit/update_stock';
$route['VarianteProduit/check_sku'] = 'Produits/VarianteProduit/check_sku';

// Route pour la liste (sans paramètre)
$route['VarianteProduit'] = 'Produits/VarianteProduit/index';

// QR Confirmations
$route['qr']                                    = 'Commande/QrConfirmation/index';
$route['qr/generate/(:num)']                    = 'Commande/QrConfirmation/generate/$1';
$route['qr/view/(:num)']                        = 'Commande/QrConfirmation/view/$1';
$route['qr/verify/(:any)']                      = 'Commande/QrConfirmation/verify/$1';
$route['qr/confirm']                            = 'Commande/QrConfirmation/confirm';
$route['qr/regenerate/(:num)']                  = 'Commande/QrConfirmation/regenerate/$1';
$route['qr/merci']                              = 'Commande/QrConfirmation/thanks';

// API
$route['api/get_transporteur_id']               = 'Api/get_transporteur_id';



// =============================================
// ROUTES POUR APPROVISIONNEMENTS
// =============================================

// Routes principales
$route['approvisionnements'] = 'Approvisionnements/index';
$route['approvisionnements/index'] = 'Approvisionnements/index';
$route['approvisionnements/index/(:num)'] = 'Approvisionnements/index/$1';

// Routes pour l'ajout
$route['approvisionnements/add'] = 'Approvisionnements/add';

// Routes pour l'historique
$route['approvisionnements/historique'] = 'Approvisionnements/historique';
$route['approvisionnements/historique/(:num)'] = 'Approvisionnements/historique/$1';

// Routes pour le détail
$route['approvisionnements/view/(:num)'] = 'Approvisionnements/view/$1';

// Routes AJAX pour les variantes
$route['approvisionnements/get_variantes'] = 'Approvisionnements/get_variantes';
$route['approvisionnements/get_variantes_by_slug'] = 'Approvisionnements/get_variantes_by_slug';
$route['approvisionnements/get_variantes_with_stock'] = 'Approvisionnements/get_variantes_with_stock';
$route['approvisionnements/get_stock_actuel'] = 'Approvisionnements/get_stock_actuel';

// Routes AJAX pour l'ajout de stock
$route['approvisionnements/ajouter_stock'] = 'Approvisionnements/ajouter_stock';
$route['approvisionnements/ajouter_stock_produit'] = 'Approvisionnements/ajouter_stock_produit';
$route['approvisionnements/ajouter_stock_variante'] = 'Approvisionnements/ajouter_stock_variante';

// Routes AJAX pour la suppression
$route['approvisionnements/delete/(:num)'] = 'Approvisionnements/delete/$1';


// =============================================
// ROUTES POUR TENTATIVES DE CONNEXION
// =============================================

// Routes principales
$route['tentatives-connexion']                                      = 'Utilisateurs/TentativesConnexion/index';
$route['tentatives-connexion/index']                                = 'Utilisateurs/TentativesConnexion/index';
$route['tentatives-connexion/index/(:num)']                         = 'Utilisateurs/TentativesConnexion/index/$1';

// Routes pour la blacklist IP
$route['tentatives-connexion/blacklister_ip']                       = 'Utilisateurs/TentativesConnexion/blacklister_ip';
$route['tentatives-connexion/retirer_blacklist']                    = 'Utilisateurs/TentativesConnexion/retirer_blacklist';
$route['tentatives-connexion/bloquer_ip']                           = 'Utilisateurs/TentativesConnexion/bloquer_ip';
$route['tentatives-connexion/debloquer_ip']                         = 'Utilisateurs/TentativesConnexion/debloquer_ip';
$route['tentatives-connexion/nettoyer_expirees']                    = 'Utilisateurs/TentativesConnexion/nettoyer_expirees';

// Routes pour la gestion des tentatives
$route['tentatives-connexion/delete/(:num)']                        = 'Utilisateurs/TentativesConnexion/delete/$1';
$route['tentatives-connexion/delete_multiple']                      = 'Utilisateurs/TentativesConnexion/delete_multiple';
$route['tentatives-connexion/vider']                                = 'Utilisateurs/TentativesConnexion/vider';

// Routes pour l'exportation
$route['tentatives-connexion/exporter']                             = 'Utilisateurs/TentativesConnexion/exporter';

// Route pour la vérification d'IP (API)
$route['tentatives-connexion/verifier']                             = 'Utilisateurs/TentativesConnexion/verifier';

// Route pour le debug
$route['tentatives-connexion/debug']                                = 'Utilisateurs/TentativesConnexion/debug';



// Routes pour Paiements Vendeurs
$route['paiements-vendeurs']                                 = 'PaiementsVendeurs/index';
$route['paiements-vendeurs/index']                           = 'PaiementsVendeurs/index';
$route['paiements-vendeurs/index/(:num)']                    = 'PaiementsVendeurs/index/$1';
$route['paiements-vendeurs/detail/(:num)']                   = 'PaiementsVendeurs/detail/$1';
$route['paiements-vendeurs/payer/(:num)']                    = 'PaiementsVendeurs/payer/$1';
$route['paiements-vendeurs/en_cours/(:num)']                 = 'PaiementsVendeurs/en_cours/$1';
$route['paiements-vendeurs/echoue/(:num)']                   = 'PaiementsVendeurs/echoue/$1';
$route['paiements-vendeurs/generer']                         = 'PaiementsVendeurs/generer';
$route['paiements-vendeurs/exporter']                        = 'PaiementsVendeurs/exporter';
$route['paiements-vendeurs/delete/(:num)']                   = 'PaiementsVendeurs/delete/$1';



// Routes pour Documents Vendeur
$route['documents-vendeur']                                 = 'DocumentsVendeur/index';
$route['documents-vendeur/index']                           = 'DocumentsVendeur/index';
$route['documents-vendeur/index/(:num)']                    = 'DocumentsVendeur/index/$1';
$route['documents-vendeur/mes-documents']                   = 'DocumentsVendeur/mes_documents';
$route['documents-vendeur/add']                             = 'DocumentsVendeur/add';
$route['documents-vendeur/edit/(:num)']                     = 'DocumentsVendeur/edit/$1';
$route['documents-vendeur/verifier/(:num)']                 = 'DocumentsVendeur/verifier/$1';
$route['documents-vendeur/delete/(:num)']                   = 'DocumentsVendeur/delete/$1';
$route['documents-vendeur/download/(:num)']                 = 'DocumentsVendeur/download/$1';
$route['documents-vendeur/detail/(:num)']                   = 'DocumentsVendeur/detail/$1';




// Routes pour Mode de Paiement
$route['mode-payement']                                     = 'ModePayement/index';
$route['mode-payement/index']                               = 'ModePayement/index';
$route['mode-payement/index/(:num)']                        = 'ModePayement/index/$1';
$route['mode-payement/add']                                 = 'ModePayement/add';
$route['mode-payement/edit/(:num)']                         = 'ModePayement/edit/$1';
$route['mode-payement/delete/(:num)']                       = 'ModePayement/delete/$1';
$route['mode-payement/toggle_status/(:num)']                = 'ModePayement/toggle_status/$1';
$route['mode-payement/detail/(:num)']                       = 'ModePayement/detail/$1';




// Routes pour Historique Statut Commande
$route['historique-statut-commande/(:num)']                    = 'Commande/HistoriqueStatutCommande/index/$1';
$route['historique-statut-commande/index/(:num)']              = 'Commande/HistoriqueStatutCommande/index/$1';
$route['historique-statut-commande/add']                       = 'Commande/HistoriqueStatutCommande/add';
$route['historique-statut-commande/delete/(:num)']             = 'Commande/HistoriqueStatutCommande/delete/$1';
$route['historique-statut-commande/exporter/(:num)']           = 'Commande/HistoriqueStatutCommande/exporter/$1';



// Routes pour Codes OTP
$route['codes-otp'] = 'CodesOtp/index';
$route['codes-otp/index'] = 'CodesOtp/index';
$route['codes-otp/index/(:num)'] = 'CodesOtp/index/$1';
$route['codes-otp/detail/(:num)'] = 'CodesOtp/detail/$1';
$route['codes-otp/generate'] = 'CodesOtp/generate';
$route['codes-otp/delete/(:num)'] = 'CodesOtp/delete/$1';
$route['codes-otp/nettoyer'] = 'CodesOtp/nettoyer';
$route['codes-otp/exporter'] = 'CodesOtp/exporter';



// Routes pour Transactions Paiement
$route['transactions-paiement'] = 'TransactionsPaiement/index';
$route['transactions-paiement/index'] = 'TransactionsPaiement/index';
$route['transactions-paiement/index/(:num)'] = 'TransactionsPaiement/index/$1';
$route['transactions-paiement/detail/(:num)'] = 'TransactionsPaiement/detail/$1';
$route['transactions-paiement/update_statut/(:num)'] = 'TransactionsPaiement/update_statut/$1';
$route['transactions-paiement/delete/(:num)'] = 'TransactionsPaiement/delete/$1';
$route['transactions-paiement/exporter'] = 'TransactionsPaiement/exporter';
$route['transactions-paiement/statistiques'] = 'TransactionsPaiement/statistiques';


// Routes pour Paniers
$route['paniers'] = 'Paniers/index';
$route['paniers/index'] = 'Paniers/index';
$route['paniers/index/(:num)'] = 'Paniers/index/$1';
$route['paniers/mon_panier'] = 'Paniers/mon_panier';
$route['paniers/detail/(:num)'] = 'Paniers/detail/$1';
$route['paniers/delete_article/(:num)'] = 'Paniers/delete_article/$1';
$route['paniers/vider/(:num)'] = 'Paniers/vider/$1';
$route['paniers/update_quantite'] = 'Paniers/update_quantite';
$route['paniers/exporter'] = 'Paniers/exporter';


// Routes pour Notifications
$route['notifications'] = 'Notifications/index';
$route['notifications/index'] = 'Notifications/index';
$route['notifications/index/(:num)'] = 'Notifications/index/$1';
$route['notifications/mes_notifications'] = 'Notifications/mes_notifications';
$route['notifications/detail/(:num)'] = 'Notifications/detail/$1';
$route['notifications/create'] = 'Notifications/create';
$route['notifications/marquer_lue/(:num)'] = 'Notifications/marquer_lue/$1';
$route['notifications/marquer_toutes_lues'] = 'Notifications/marquer_toutes_lues';
$route['notifications/delete/(:num)'] = 'Notifications/delete/$1';
$route['notifications/supprimer_toutes'] = 'Notifications/supprimer_toutes';
$route['notifications/count_non_lues'] = 'Notifications/count_non_lues';
$route['notifications/exporter'] = 'Notifications/exporter';



// Routes pour Transporteurs
$route['transporteurs'] = 'Transporteurs/index';
$route['transporteurs/index'] = 'Transporteurs/index';
$route['transporteurs/index/(:num)'] = 'Transporteurs/index/$1';
$route['transporteurs/add'] = 'Transporteurs/add';
$route['transporteurs/edit/(:num)'] = 'Transporteurs/edit/$1';
$route['transporteurs/detail/(:num)'] = 'Transporteurs/detail/$1';
$route['transporteurs/delete/(:num)'] = 'Transporteurs/delete/$1';
$route['transporteurs/change_statut/(:num)'] = 'Transporteurs/change_statut/$1';
$route['transporteurs/toggle_disponible/(:num)'] = 'Transporteurs/toggle_disponible/$1';
$route['transporteurs/update_position/(:num)'] = 'Transporteurs/update_position/$1';
$route['transporteurs/exporter'] = 'Transporteurs/exporter';



// Routes pour Points Relais
$route['points-relais'] = 'PointsRelais/index';
$route['points-relais/index'] = 'PointsRelais/index';
$route['points-relais/index/(:num)'] = 'PointsRelais/index/$1';
$route['points-relais/add'] = 'PointsRelais/add';
$route['points-relais/edit/(:num)'] = 'PointsRelais/edit/$1';
$route['points-relais/detail/(:num)'] = 'PointsRelais/detail/$1';
$route['points-relais/delete/(:num)'] = 'PointsRelais/delete/$1';
$route['points-relais/toggle_status/(:num)'] = 'PointsRelais/toggle_status/$1';
$route['points-relais/exporter'] = 'PointsRelais/exporter';
$route['points-relais/get_quartiers'] = 'PointsRelais/get_quartiers';



// Suivi GPS
$route['suivi-gps'] = 'SuiviGps/index';
$route['suivi-gps/index/(:num)'] = 'SuiviGps/index/$1';
$route['suivi-gps/carte'] = 'SuiviGps/carte';
$route['suivi-gps/add'] = 'SuiviGps/add_edit';
$route['suivi-gps/edit/(:num)'] = 'SuiviGps/add_edit/$1';
$route['suivi-gps/save'] = 'SuiviGps/save';
$route['suivi-gps/delete/(:num)'] = 'SuiviGps/delete/$1';
$route['suivi-gps/detail/(:num)'] = 'SuiviGps/detail/$1';
$route['suivi-gps/positions_actives'] = 'SuiviGps/positions_actives';
$route['suivi-gps/derniere_position/(:num)'] = 'SuiviGps/derniere_position/$1';
$route['suivi-gps/historique_positions/(:num)'] = 'SuiviGps/historique_positions/$1';
$route['suivi-gps/suivi_commande/(:num)'] = 'SuiviGps/suivi_commande/$1';
$route['suivi-gps/enregistrer_position'] = 'SuiviGps/enregistrer_position';
$route['suivi-gps/exporter'] = 'SuiviGps/exporter';




// Liste de souhaits
$route['liste-souhaits'] = 'ListeSouhaits/index';
$route['liste-souhaits/admin-list'] = 'ListeSouhaits/admin_list';
$route['liste-souhaits/admin-list/(:num)'] = 'ListeSouhaits/admin_list/$1';
$route['liste-souhaits/ajouter'] = 'ListeSouhaits/ajouter';
$route['liste-souhaits/supprimer/(:num)'] = 'ListeSouhaits/supprimer/$1';
$route['liste-souhaits/supprimer_api'] = 'ListeSouhaits/supprimer_api';
$route['liste-souhaits/vider'] = 'ListeSouhaits/vider';
$route['liste-souhaits/detail/(:num)'] = 'ListeSouhaits/detail/$1';
$route['liste-souhaits/exporter'] = 'ListeSouhaits/exporter';


// Évaluations vendeurs
$route['evaluations-vendeurs'] = 'EvaluationsVendeurs/index';
$route['evaluations-vendeurs/index/(:num)'] = 'EvaluationsVendeurs/index/$1';
$route['evaluations-vendeurs/mes-evaluations'] = 'EvaluationsVendeurs/mes_evaluations';
$route['evaluations-vendeurs/ajouter'] = 'EvaluationsVendeurs/ajouter';
$route['evaluations-vendeurs/approuver/(:num)'] = 'EvaluationsVendeurs/approuver/$1';
$route['evaluations-vendeurs/rejeter/(:num)'] = 'EvaluationsVendeurs/rejeter/$1';
$route['evaluations-vendeurs/supprimer/(:num)'] = 'EvaluationsVendeurs/supprimer/$1';
$route['evaluations-vendeurs/detail/(:num)'] = 'EvaluationsVendeurs/detail/$1';
$route['evaluations-vendeurs/exporter'] = 'EvaluationsVendeurs/exporter';


// Logs audit
$route['logs-audit'] = 'LogsAudit/index';
$route['logs-audit/index/(:num)'] = 'LogsAudit/index/$1';
$route['logs-audit/detail/(:num)'] = 'LogsAudit/detail/$1';
$route['logs-audit/nettoyer'] = 'LogsAudit/nettoyer';
$route['logs-audit/exporter'] = 'LogsAudit/exporter';
$route['logs-audit/statistiques'] = 'LogsAudit/statistiques';


// Soldes vendeurs
$route['soldes-vendeurs'] = 'SoldesVendeurs/index';
$route['soldes-vendeurs/index/(:num)'] = 'SoldesVendeurs/index/$1';
$route['soldes-vendeurs/mon-solde'] = 'SoldesVendeurs/mon_solde';
$route['soldes-vendeurs/detail/(:num)'] = 'SoldesVendeurs/detail/$1';
$route['soldes-vendeurs/update-solde'] = 'SoldesVendeurs/update_solde';
$route['soldes-vendeurs/recalculer/(:num)'] = 'SoldesVendeurs/recalculer/$1';
$route['soldes-vendeurs/recalculer-tous'] = 'SoldesVendeurs/recalculer_tous';
$route['soldes-vendeurs/exporter'] = 'SoldesVendeurs/exporter';




// Routes pour l'accueil
$route['home'] = 'home/index';
$route['about'] = 'home/about';
$route['blog'] = 'home/blog';
$route['offres'] = 'home/offres';
$route['faq'] = 'home/faq';
$route['contact'] = 'home/contact';
$route['shop'] = 'home/shop';
$route['cart'] = 'home/cart';
$route['checkout'] = 'home/checkout';
$route['checkout/process'] = 'home/processOrder';
$route['payment/pending/(:any)'] = 'home/payment_pending/$1';
$route['payment/submit-reference'] = 'home/submit_payment_reference';
$route['wishlist'] = 'home/wishlist';
$route['privacy-policy'] = 'home/privacy_policy';
$route['sellers'] = 'home/sellers';
$route['search'] = 'home/search';

// Routes dynamiques
$route['category/(:any)'] = 'home/category/$1';
$route['product/(:any)'] = 'home/product/$1';
$route['seller/(:any)'] = 'home/seller/$1';
$route['order-success/(:any)'] = 'home/order_success/$1';






// Routes pour User_dashboard
$route['user_dashboard/ajax_change_email'] = 'Home/User_dashboard/ajax_change_email';
$route['user_dashboard/ajax_change_password'] = 'Home/User_dashboard/ajax_change_password';
$route['user_dashboard/ajax_update_profile'] = 'Home/User_dashboard/ajax_update_profile';
$route['user_dashboard/ajax_add_address'] = 'Home/User_dashboard/ajax_add_address';
$route['user_dashboard/ajax_delete_address/(:num)'] = 'Home/User_dashboard/ajax_delete_address/$1';
$route['user_dashboard/ajax_get_orders'] = 'Home/User_dashboard/ajax_get_orders';
$route['user_dashboard/ajax_order_detail/(:num)'] = 'Home/User_dashboard/ajax_order_detail/$1';
$route['user_dashboard/ajax_add_wishlist'] = 'Home/User_dashboard/ajax_add_wishlist';
$route['user_dashboard/ajax_remove_wishlist'] = 'Home/User_dashboard/ajax_remove_wishlist';
$route['user_dashboard/ajax_mark_notification_read'] = 'Home/User_dashboard/ajax_mark_notification_read';

// Routes vendeur
$route['user_dashboard/ajax_add_product'] = 'Home/User_dashboard/ajax_add_product';
$route['user_dashboard/ajax_update_order_status'] = 'Home/User_dashboard/ajax_update_order_status';
$route['user_dashboard/ajax_update_stock'] = 'Home/User_dashboard/ajax_update_stock';
$route['user_dashboard/ajax_delete_product'] = 'Home/User_dashboard/ajax_delete_product';
$route['user_dashboard/ajax_get_seller_stats'] = 'Home/User_dashboard/ajax_get_seller_stats';
$route['user_dashboard/logout'] = 'Home/User_dashboard/logout';
$route['user_dashboard'] = 'Home/User_dashboard/index';
$route['User_dashboard'] = 'Home/User_dashboard/index';
$route['Home/User_dashboard/ajax_get_address/(:num)'] = 'Home/User_dashboard/ajax_get_address/$1';


$route['User_dashboard/complete_profile'] = 'Home/User_dashboard/complete_profile';
$route['User_dashboard/save_complete_profile'] = 'Home/User_dashboard/save_complete_profile';
$route['User_dashboard/get_quartiers'] = 'Home/User_dashboard/get_quartiers';
$route['User_dashboard/get_communes'] = 'Home/User_dashboard/get_communes';




$route['auth/verify_code_page'] = 'Auth/verify_code_page';
$route['auth/verify_code'] = 'Auth/verify_code';
$route['auth/resend_reset_code'] = 'Auth/resend_reset_code';
$route['auth/forgot_password'] = 'Auth/forgot_password';
$route['auth/otp_verification_page'] = 'Auth/otp_verification_page';

// Order tracking
$route['order-tracking'] = 'Home/Ordertracking/index';
$route['order-tracking/(:any)'] = 'Home/Ordertracking/index/$1';
$route['update-order-status'] = 'Home/Ordertracking/updateOrderStatus';
$route['confirm-delivery'] = 'Home/Ordertracking/confirmDeliveryByQR';

// Tableau de bord utilisateur frontend
$route['user/dashboard'] = 'home/User_dashboard';

// =============================================
// ROUTES POUR DASHBOARDS MULTI-RÔLES
// =============================================
$route['Vendeur/Dashboard'] = 'Vendeur/Dashboard/index';
$route['Client/Dashboard'] = 'Client/Dashboard/index';
$route['Livreur/Dashboard'] = 'Livreur/Dashboard/index';
$route['Finance/Dashboard'] = 'Finance/Dashboard/index';
$route['Support/Dashboard'] = 'Support/Dashboard/index';

// Ma Boutique
$route['ma-boutique'] = 'MaBoutique/MaBoutique/index';
$route['ma-boutique/update'] = 'MaBoutique/MaBoutique/update';
$route['ma-boutique/update_paiement'] = 'MaBoutique/MaBoutique/update_paiement';
$route['ma-boutique/get_communes'] = 'MaBoutique/MaBoutique/get_communes';
