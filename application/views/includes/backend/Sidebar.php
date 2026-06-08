<!-- ========== App Menu Start ========== -->
<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="index.html" class="logo-dark">
            <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>" class="logo-sm" alt="logo sm">
            <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>" class="logo-lg" alt="logo dark">
        </a>

        <a href="index.html" class="logo-light">
            <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>" class="logo-sm" alt="logo sm">
            <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>" class="logo-lg" alt="logo light">
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">General</li>

            <?php if (has_permission('viewDashboard') || has_role(['client'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Dashboard">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Dashboard </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageProduits')): ?>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarProducts">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Products </span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>Produits">Produits</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>avis-produits">Avis_Produits</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageCategories')): ?>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCategory">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Category </span>
                </a>
                <div class="collapse" id="sidebarCategory">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>Categories">List</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageMesProduits')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Produits">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Mes Produits </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_permission('viewMesCommandes') || has_permission('manageMesCommandes')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Commande">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Mes Commandes </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_permission('viewMesSoldes')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>soldes-vendeurs/mon-solde">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:dollar-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Mes Soldes </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageMesAvis')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>avis-produits">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:star-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Mes Avis </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageMaBoutique')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>ma-boutique">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:shop-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Ma Boutique </span>
                </a>
            </li>
            <?php endif; ?>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Profile">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Mon Profil </span>
                </a>
            </li>

            <?php if (has_permission('manageMesApprovisionnements')): ?>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarInventory" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarInventory">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:box-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Inventory </span>
                </a>
                <div class="collapse" id="sidebarInventory">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>Approvisionnements">Approvisionnement</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageCommandes')): ?>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarOrders">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Orders </span>
                </a>
                <div class="collapse" id="sidebarOrders">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>Commande">Commandes</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>articles-commandes">articles-commandes</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>litiges">Litiges</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>qr">Qr Code Confirmation</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageUtilisateurs')): ?>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAttributes" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAttributes">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:confetti-minimalistic-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Adresse </span>
                </a>
                <div class="collapse" id="sidebarAttributes">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>Adresse">Adresse</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>provinces">provinces</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>communes">Communes</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>quartiers">quartiers</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>collines">collines</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>zones">zones</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url() ?>zone-livraison">zone-livraison</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Settings">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Settings </span>
                </a>
            </li>
            <?php endif; ?>

            <li class="menu-title mt-2">Users</li>

            <?php if (has_permission('manageProfils')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Profils">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Profile </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_role('super_admin')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Roles">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Roles </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageUtilisateurs')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Utilisateurs">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Utilisateurs </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>tentatives-connexion">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Tentatives Connexion </span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageUtilisateurs')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Customers">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Customers</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageVendeurs')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url() ?>Sellers">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Sellers</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="menu-title mt-2">NTURO Modules</li>
            <?php endif; ?>

            <?php if (has_permission('manageCoupons')): ?>
            <!-- Coupons -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCoupons" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarCoupons">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:leaf-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Coupons </span>
                </a>
                <div class="collapse" id="sidebarCoupons">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Coupons') ?>">Liste</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Coupons/add') ?>">Ajouter</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('managePaiements')): ?>
            <!-- Paiements vendeurs -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarPaiements" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPaiements">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Paiements vendeurs </span>
                </a>
                <div class="collapse" id="sidebarPaiements">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('PaiementsVendeurs') ?>">Liste</a>
                        </li>
                       
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageVendeurs')): ?>
            <!-- Documents vendeur -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarDocuments" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDocuments">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:file-text-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Documents vendeur </span>
                </a>
                <div class="collapse" id="sidebarDocuments">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('DocumentsVendeur') ?>">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('managePaiements')): ?>
            <!-- Mode paiement -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarModePaiement" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarModePaiement">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:card-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Mode paiement </span>
                </a>
                <div class="collapse" id="sidebarModePaiement">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('ModePayement') ?>">Liste</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('ModePayement/ajouter') ?>">Ajouter</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageCommandes')): ?>
            <!-- Historique commandes -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarHistorique" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarHistorique">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:history-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Historique commandes </span>
                </a>
                <div class="collapse" id="sidebarHistorique">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Historique_statut_commande') ?>">Liste</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Historique_statut_commande/add') ?>">Ajouter</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageRetours')): ?>
            <!-- Retours Remboursements -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarRetour" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarRetour">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Retours Remboursements </span>
                </a>
                <div class="collapse" id="sidebarRetour">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Retours') ?>">Liste</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Retours_remboursement/add') ?>">Ajouter</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <!-- Banners -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarBanners" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarBanners">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Banners </span>
                </a>
                <div class="collapse" id="sidebarBanners">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('banners') ?>">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <!-- Codes OTP -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarOTP" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarOTP">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:shield-keyhole-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Codes OTP </span>
                </a>
                <div class="collapse" id="sidebarOTP">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('CodesOtp') ?>">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('managePaiements')): ?>
            <!-- Config Paiement Vendeur -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarConfigPaiement" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarConfigPaiement">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Config Paiement Vendeur </span>
                </a>
                <div class="collapse" id="sidebarConfigPaiement">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Config_paiement_vendeur') ?>">Liste</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Config_paiement_vendeur/ajouter') ?>">Ajouter</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('managePaiements')): ?>
            <!-- Transactions paiement -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarTransactions" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTransactions">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:card-transfer-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Transactions paiement </span>
                </a>
                <div class="collapse" id="sidebarTransactions">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('TransactionsPaiement') ?>">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>




            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="nav-item">
    <a class="nav-link menu-arrow" href="#sidebarAbout" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAbout">
        <span class="nav-icon">
            <iconify-icon icon="solar:info-square-bold-duotone"></iconify-icon>
        </span>
        <span class="nav-text"> À propos </span>
    </a>
    <div class="collapse" id="sidebarAbout">
        <ul class="nav sub-navbar-nav">
            <li class="sub-nav-item">
                <a class="sub-nav-link" href="<?= base_url('admin/about') ?>">Gestion du contenu</a>
            </li>
        </ul>
    </div>
</li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="nav-item">
    <a class="nav-link menu-arrow" href="#sidebarBlog" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarBlog">
        <span class="nav-icon">
            <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
        </span>
        <span class="nav-text"> Blog </span>
    </a>
    <div class="collapse" id="sidebarBlog">
        <ul class="nav sub-navbar-nav">
            <li class="sub-nav-item">
                <a class="sub-nav-link" href="<?= base_url('admin/blog/posts') ?>">Articles</a>
            </li>
            <li class="sub-nav-item">
                <a class="sub-nav-link" href="<?= base_url('admin/blog/categories') ?>">Catégories</a>
            </li>
            <li class="sub-nav-item">
                <a class="sub-nav-link" href="<?= base_url('admin/blog/comments') ?>">Commentaires</a>
            </li>
        </ul>
    </div>
</li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="nav-item">
    <a class="nav-link menu-arrow" href="#sidebarFaq" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarFaq">
        <span class="nav-icon">
            <iconify-icon icon="solar:question-circle-bold-duotone"></iconify-icon>
        </span>
        <span class="nav-text"> FAQ </span>
    </a>
    <div class="collapse" id="sidebarFaq">
        <ul class="nav sub-navbar-nav">
            <li class="sub-nav-item">
                <a class="sub-nav-link" href="<?= base_url('admin/faq') ?>">Gestion des FAQ</a>
            </li>
        </ul>
    </div>
</li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="nav-item">
    <a class="nav-link menu-arrow" href="#sidebarTeam" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTeam">
        <span class="nav-icon">
            <iconify-icon icon="solar:users-group-rounded-bold-duotone"></iconify-icon>
        </span>
        <span class="nav-text"> Équipe </span>
    </a>
    <div class="collapse" id="sidebarTeam">
        <ul class="nav sub-navbar-nav">
            <li class="sub-nav-item">
                <a class="sub-nav-link" href="<?= base_url('admin/team') ?>">Membres de l'équipe</a>
            </li>
        </ul>
    </div>
</li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="nav-item">
    <a class="nav-link menu-arrow" href="#sidebarTestimonials" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTestimonials">
        <span class="nav-icon">
            <iconify-icon icon="solar:chat-round-like-bold-duotone"></iconify-icon>
        </span>
        <span class="nav-text"> Témoignages </span>
    </a>
    <div class="collapse" id="sidebarTestimonials">
        <ul class="nav sub-navbar-nav">
            <li class="sub-nav-item">
                <a class="sub-nav-link" href="<?= base_url('admin/testimonials') ?>">Gestion des témoignages</a>
            </li>
        </ul>
    </div>
</li>
            <?php endif; ?>


            <?php if (has_permission('manageMonPanier')): ?>
            <!-- Panier -->
            <?php if (has_role(['super_admin', 'admin'])): ?>
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarPanier" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPanier">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:cart-3-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Panier </span>
                </a>
                <div class="collapse" id="sidebarPanier">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('paniers') ?>">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('paniers/mon_panier') ?>">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:cart-3-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Mon Panier </span>
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

            <?php if (has_permission('viewMesNotifications')): ?>
            <!-- Notifications -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarNotifications" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarNotifications">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:bell-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Notifications </span>
                </a>
                <div class="collapse" id="sidebarNotifications">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Notifications') ?>">Liste notifications</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageTransporteurs')): ?>
            <!-- Transporteurs -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarTransporteurs" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTransporteurs">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:delivery-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Transporteurs </span>
                </a>
                <div class="collapse" id="sidebarTransporteurs">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('Transporteurs') ?>">Liste transporteurs</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageLivraisons')): ?>
            <!-- Points relais -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarPointsRelais" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPointsRelais">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Points relais </span>
                </a>
                <div class="collapse" id="sidebarPointsRelais">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('points-relais') ?>">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('viewCarteGps')): ?>
            <!-- Suivi GPS -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarSuivisGPS" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSuivisGPS">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:map-point-wave-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Suivi GPS </span>
                </a>
                <div class="collapse" id="sidebarSuivisGPS">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('suivi-gps') ?>">Liste GPS</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <!-- Liste de souhaits -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarWishlist" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarWishlist">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:heart-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Liste de souhaits </span>
                </a>
                <div class="collapse" id="sidebarWishlist">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('liste-souhaits') ?>">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageAvis')): ?>
            <!-- Evaluations vendeurs -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarEvaluations" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarEvaluations">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:star-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Evaluations vendeurs </span>
                </a>
                <div class="collapse" id="sidebarEvaluations">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('EvaluationsVendeurs') ?>">Liste</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_role(['super_admin', 'admin'])): ?>
            <!-- Logs Audit -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarLogsAudit" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLogsAudit">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:shield-keyhole-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Logs Audit </span>
                </a>
                <div class="collapse" id="sidebarLogsAudit">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('LogsAudit') ?>">Liste Logs</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <?php if (has_permission('manageSoldesVendeurs')): ?>
            <!-- Soldes vendeurs -->
            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarSoldes" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSoldes">
                    <span class="nav-icon">
                        <iconify-icon icon="solar:wallet-money-bold-duotone"></iconify-icon>
                    </span>
                    <span class="nav-text"> Soldes vendeurs </span>
                </a>
                <div class="collapse" id="sidebarSoldes">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="<?= base_url('soldes-vendeurs') ?>">Liste soldes</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>



        </ul>
    </div>
</div>
<!-- ========== App Menu End ========== -->