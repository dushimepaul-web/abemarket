<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- Métadonnées dynamiques depuis la base de données -->
    <meta name="description" content="<?= htmlspecialchars($settings['site_description'] ?? 'AbeMarket - Votre MarketPlace au Burundi', ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="keywords" content="<?= htmlspecialchars($settings['site_keywords'] ?? 'marketplace, burundi, shopping, abemarkat', ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="author" content="<?= htmlspecialchars($settings['site_author'] ?? 'AbeMarket', ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Favicon dynamique -->
    <link rel="icon" href="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_favicon', 'favicon.svg')); ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_favicon', 'favicon.svg')); ?>">
    
    <meta name="title-color" content="#ff9900">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="<?= htmlspecialchars($settings['site_name'] ?? 'AbeMarket', ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="msapplication-TileImage" content="<?= base_url('assets/frontend/images/favicon/1.html'); ?>">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    
    <!-- Titre dynamique -->
    <title><?= isset($meta_title) ? htmlspecialchars($meta_title, ENT_QUOTES, 'UTF-8') : (htmlspecialchars($settings['site_name'] ?? 'AbeMarket', ENT_QUOTES, 'UTF-8') . ' - Votre MarketPlace au Burundi'); ?></title>

    <!-- Google Font Link -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/fonts/br-hendrix/stylesheet.css'); ?>">

    <!-- Bootstrap Link -->
    <link rel="stylesheet" id="rtl-link" type="text/css" href="<?= base_url('assets/frontend/css/vendors/bootstrap.css'); ?>">

    <!-- Iconsax Icon Link -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/vendors/iconsax.css'); ?>">

    <!-- Remix Icon Link -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/vendors/remixicon.css'); ?>">

    <!-- Swiper Link -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/vendors/swiper.css'); ?>">

    <!-- Style Link Principal -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/style.css'); ?>">
    
    <!-- CSS Personnalisé -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/custom.css'); ?>">
    <!-- jQuery (obligatoire pour les fonctionnalités AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style type="text/css">
        /* Navigation horizontale avec défilement */
.header-nav-middle {
    flex: 1;
    overflow-x: auto;
    overflow-y: hidden;
    white-space: nowrap;
    scrollbar-width: thin;
    -webkit-overflow-scrolling: touch;
}

.header-nav-middle::-webkit-scrollbar {
    height: 4px;
}

.header-nav-middle::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.header-nav-middle::-webkit-scrollbar-thumb {
    background: #ff9900;
    border-radius: 10px;
}

.main-nav .navbar-nav {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    white-space: nowrap;
}

.main-nav .navbar-nav .nav-item {
    float: none;
    display: inline-block;
    white-space: normal;
}

/* Pour les écrans plus petits */
@media (max-width: 1399px) {
    .header-nav-middle {
        overflow-x: auto;
    }
    
    .main-nav .navbar-nav {
        width: max-content;
    }
}
    </style>

    <!-- Google Analytics -->
    <?php if (!empty($settings['google_analytics'])): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($settings['google_analytics'], ENT_QUOTES, 'UTF-8'); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= htmlspecialchars($settings['google_analytics'], ENT_QUOTES, 'UTF-8'); ?>');
    </script>
    <?php endif; ?>
</head>

<body class="base-bg-color">
    <!-- Ajouter après l'ouverture de <body> -->
<div class="user-logged" data-logged="<?= $this->session->userdata('user_id') ? 'true' : 'false' ?>" style="display:none;"></div>
    

    <!-- Header Start -->
    <header class="header-style-1">
        <!-- Top Header -->
        <div class="top-header custom-container">
            <div class="left-header">
                <div class="dropdown-box">
                    <ul>
                        <li>
                            <div class="dropdown theme-form-select">
                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" id="select-language">
                                    <img src="<?= base_url('assets/frontend/images/country/france.svg'); ?>" class="img-fluid" alt="">
                                    <span>FR</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a onclick="setLanguage('fr')" class="dropdown-item" href="#">
                                            <img src="<?= base_url('assets/frontend/images/country/france.svg'); ?>" class="img-fluid" alt="">
                                            <span>FR</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a onclick="setLanguage('en')" class="dropdown-item" href="#">
                                            <img src="<?= base_url('assets/frontend/images/country/united-kingdom.png'); ?>" class="img-fluid" alt="">
                                            <span>EN</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown theme-form-select">
                                <button class="btn dropdown-toggle" data-bs-toggle="dropdown" type="button">
                                    <span>BIF</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">BIF</a></li>
                                    <li><a class="dropdown-item" href="#">USD</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="social-box">
                    <ul class="social-list">
                        <?php if (!empty($settings['site_facebook'])): ?>
                        <li><a href="<?= htmlspecialchars($settings['site_facebook'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><i class="ri-facebook-fill"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($settings['site_twitter'])): ?>
                        <li><a href="<?= htmlspecialchars($settings['site_twitter'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><i class="ri-twitter-x-line"></i></a></li>
                        <?php endif; ?>
                        <?php if (!empty($settings['site_instagram'])): ?>
                        <li><a href="<?= htmlspecialchars($settings['site_instagram'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><i class="ri-instagram-line"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="middle-header">
                <div class="middle-content">
                    <p><span>Livraison gratuite à partir de 50 000 BIF | Paiement sécurisé | Retours gratuits sous 14 jours</span></p>
                </div>
            </div>

<div class="right-header">
    <ul class="content-list">
        <?php if ($this->session->userdata('id_utilisateur')): ?>
            <!-- Utilisateur connecté -->
            <li><a href="<?= base_url('user/dashboard'); ?>">Mon Compte</a></li>
        <?php else: ?>
            <!-- Utilisateur non connecté - Ouvre la modale -->
            <li><a href="javascript:void(0);" onclick="openAuthModal('login')">Mon Compte</a></li>
        <?php endif; ?>
        
        <li><a href="<?= base_url('Home/contact'); ?>">Contactez-nous</a></li>
        <li><a href="<?= base_url('Home/about'); ?>">About us</a></li>
        <li><a href="<?= base_url('blog'); ?>">Blog</a></li>
        <li><a href="<?= base_url('wishlist'); ?>">Souhaits</a></li>
        <li><a href="<?= base_url('cart'); ?>">Panier</a></li>
        
        <?php if (!$this->session->userdata('id_utilisateur')): ?>
            <!-- Utilisateur non connecté - Affiche Connexion -->
            <li><a href="javascript:void(0);" onclick="openAuthModal('login')" class="login-btn">Connexion</a></li>
        <?php else: ?>
            <!-- Utilisateur connecté - Affiche Déconnexion -->
            <li><a href="<?= base_url('auth/logout'); ?>">Déconnexion</a></li>
        <?php endif; ?>
    </ul>
</div>
        </div>

        <!-- Main Header -->
        <div class="main-header custom-container">
            <div class="left-header">

                <button class="navbar-toggler d-xl-none d-inline navbar-menu-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#abmOffcanvas">
                    <span class="navbar-toggler-icon"><i class="ri-menu-line"></i></span>
                </button>





                <a href="<?= base_url(); ?>" class="header-logo">
                    <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>" class="logo-sm" alt="logo sm">
                </a>
            </div>

            <div class="middle-header searchInput" id="searchOffcanvas">
                <div class="search-overlay" id="searchOverlay"></div>
                <form action="<?= base_url('search'); ?>" method="GET" class="search-form">
                    <div class="input-group">
                        <div class="close-icon"><i class="ri-close-fill" id="close-btn"></i></div>
                        <div class="input-group-text">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <span>Toutes catégories</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (!empty($main_categories)): ?>
                                        <?php foreach($main_categories as $cat): ?>
                                        <li><a class="dropdown-item" href="<?= base_url('category/' . $cat['slug_categorie']); ?>"><?= htmlspecialchars($cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <input type="search" name="q" class="form-control" placeholder="Je recherche...">
                        <button class="search-button btn" type="submit"><i class="ri-search-line"></i></button>
                    </div>
                </form>
            </div>

            <div class="right-header">
                <ul class="header-icon">
                    <li><a href="#" id="searchClick"><i class="iconsax search-btn" data-icon-name="search-normal-2"></i></a></li>
                    <li class="contact-list">
                        <a href="tel:<?= htmlspecialchars($settings['site_phone'] ?? '+257 68 86 39 45', ENT_QUOTES, 'UTF-8'); ?>">
                            <i class="iconsax" data-icon-name="phone-calling"></i>
                            <div>
                                <h5>Appelez-nous</h5>
                                <h6 class="h5"><?= htmlspecialchars($settings['site_phone'] ?? '+257 68 86 39 45', ENT_QUOTES, 'UTF-8'); ?></h6>
                            </div>
                        </a>
                    </li>


<li class="dropdown-box">
    <a href="#"><i class="iconsax" data-icon-name="user-2"></i></a>
    <ul class="dropdown-list user-dropdown">
        <?php if ($this->session->userdata('logged_in')): ?>
            <li><a href="<?= base_url($this->session->userdata('role') === 'super_admin' ? 'Dashboard' : ($this->session->userdata('role') === 'vendeur' ? 'Home/User_dashboard' : 'Home/User_dashboard')); ?>">
                <i class="ri-dashboard-line"></i> Mon Dashboard
            </a></li>
            <li>
                <a href="<?= base_url('Auth/logout'); ?>" onclick="event.preventDefault(); logoutUser();">
                    <i class="ri-logout-box-line"></i> Déconnexion
                </a>
            </li>
        <?php else: ?>
            <li>
                <button class="btn login-btn" onclick="openAuthModal('login')" style="width: 100%; text-align: left;">
                    <i class="ri-login-circle-line"></i> Connexion
                </button>
            </li>
            <li>
                <button class="btn signup-btn" onclick="openAuthModal('signup')" style="width: 100%; text-align: left;">
                    <i class="ri-user-add-line"></i> Inscription
                </button>
            </li>
        <?php endif; ?>
    </ul>
</li>

<?php 
$isLoggedIn = $this->session->userdata('logged_in');
?>

<!-- Wishlist -->
<?php
$hdr_wishlist_count = 0;
if ($isLoggedIn) {
    $hdr_wishlist_count = $wishlist_count ?? 0;
} else {
    $guestWishlist = $this->session->userdata('guest_wishlist') ?: [];
    $hdr_wishlist_count = count($guestWishlist);
}
?>
<li>
    <a data-bs-toggle="offcanvas" href="#wishlistOffcanvas">
        <i class="iconsax" data-icon-name="heart"></i>
        <span class="label"><span id="wishlist-count-header"><?= $hdr_wishlist_count ?></span></span>
    </a>
</li>

<!-- Panier -->
<?php
$hdr_cart_count = 0;
if ($this->session->userdata('user_id')) {
    $hdr_cart_count = $this->session->userdata('cart_count') ?? 0;
} else {
    $guestCart = $this->session->userdata('guest_cart') ?: [];
    foreach ($guestCart as $item) {
        $hdr_cart_count += intval($item['quantity'] ?? 1);
    }
}
?>
<li>
    <a href="#" class="cart-icon" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
        <i class="iconsax" data-icon-name="basket-2"></i>
        <span class="label"><span id="cart-count-header"><?= $hdr_cart_count ?></span></span>
    </a>
</li>


                </ul>
            </div>
        </div>

        <!-- Nav Header -->
        <div class="nav-header custom-container d-flex">
            <div class="category-header d-sm-block d-none">
                <button class="btn category-button categoryButton d-xl-block d-none">
                    <i class="ri-menu-line"></i><span>Toutes catégories</span>
                </button>
            </div>

            <div class="header-nav-middle">
                <div class="main-nav navbar navbar-expand-xl navbar-light navbar-sticky">
                    <div class="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
                        <div class="offcanvas-header navbar-shadow">
                            <h5>Menu</h5>
                            <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas"><i class="ri-close-fill"></i></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="<?= base_url(); ?>">Accueil</a></li>
                                
                                
                                <li class="nav-item"><a class="nav-link" href="<?= base_url('sellers'); ?>">Vendeurs</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?= base_url('offres'); ?>">Promotions</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?= base_url('faq'); ?>">FAQ</a></li>
                                <?php if (!$this->session->userdata('user_id')): ?>
                                <li class="nav-item d-xl-none"><a class="nav-link" href="#authenticationModal" data-bs-toggle="modal">Connexion / Inscription</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>



            <div class="recent-header">
                <ul class="product-viwer">
                    <li class="order-tracking d-xxl-inline-block d-none">
                        <a href="<?= base_url('order-tracking'); ?>" class="product-link">Suivi commande</a>
                    </li>
                </ul>
            </div>
        </div>


        <!-- Offcanvas Catégories avec Hiérarchie -->
        <div class="offcanvas offcanvas-start category-offcanvas" id="categoryCanvas" tabindex="-1">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Toutes les catégories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
                    <i class="ri-close-fill"></i>
                </button>
            </div>
            <div class="offcanvas-body">
                <div class="category-menu-list">
                    <?php if (!empty($categories_hierarchy)): ?>
                        <ul class="category-tree">
                            <?php foreach($categories_hierarchy as $main_cat): ?>
                            <li class="category-item">
                                <div class="category-item-header">
                                    <a href="<?= base_url('category/' . $main_cat['slug_categorie']); ?>" class="category-main-link">
                                        <?php if(!empty($main_cat['url_image'])): ?>
                                        <img src="<?= base_url($main_cat['url_image']); ?>" class="category-icon" alt="">
                                        <?php elseif(!empty($main_cat['icone'])): ?>
                                        <i class="<?= htmlspecialchars($main_cat['icone'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                                        <?php else: ?>
                                        <i class="ri-folder-line"></i>
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($main_cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </a>
                                    <?php if(!empty($main_cat['children'])): ?>
                                    <button class="category-toggle-btn">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if(!empty($main_cat['children'])): ?>
                                <ul class="category-submenu">
                                    <?php foreach($main_cat['children'] as $sub_cat): ?>
                                    <li class="category-subitem">
                                        <div class="category-subitem-header">
                                            <a href="<?= base_url('category/' . $sub_cat['slug_categorie']); ?>" class="category-sub-link">
                                                <span><?= htmlspecialchars($sub_cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </a>
                                            <?php if(!empty($sub_cat['children'])): ?>
                                            <button class="category-sub-toggle-btn">
                                                <i class="ri-arrow-right-s-line"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if(!empty($sub_cat['children'])): ?>
                                        <ul class="category-level3">
                                            <?php foreach($sub_cat['children'] as $child_cat): ?>
                                            <li>
                                                <a href="<?= base_url('category/' . $child_cat['slug_categorie']); ?>">
                                                    <?= htmlspecialchars($child_cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?>
                                                </a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucune catégorie trouvée</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </header>
    <!-- Header End -->

    <!-- Mobile Menu Start -->
    <div class="mobile-menu d-sm-none">
        <ul>
            <li class="active"><a href="<?= base_url(); ?>"><i class="ri-home-2-line"></i><span>Accueil</span></a></li>
            <li class="mobile-category"><a data-bs-toggle="offcanvas" href="#categoryCanvas"><i class="ri-menu-line"></i><span>Catégories</span></a></li>
            <li><a data-bs-toggle="offcanvas" href="#cartOffcanvas"><i class="ri-shopping-cart-line"></i><span>Panier (<span class="cart-count"><?= $cart_count ?? 0; ?></span>)</span></a></li>
            <li><a data-bs-toggle="offcanvas" href="#wishlistOffcanvas"><i class="ri-heart-3-line"></i><span>Souhaits (<span class="wishlist-count"><?= $wishlist_count ?? 0; ?></span>)</span></a></li>
            <li><a href="<?= base_url('user/dashboard'); ?>"><i class="ri-user-3-line"></i><span>Compte</span></a></li>
        </ul>
    </div>
    <!-- Mobile Menu End -->

    