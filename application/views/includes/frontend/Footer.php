     <!-- News-letter Section Start -->
<section class="section-block-space newsletter-section">
    <div class="custom-container">
        <div class="newsletter-box">
            <img src="<?= base_url('assets/frontend/images/newsletter/1.svg'); ?>" class="newsletter-1" alt="">
            <img src="<?= base_url('assets/frontend/images/newsletter/2.svg'); ?>" class="newsletter-2" alt="">
            <img src="<?= base_url('assets/frontend/images/newsletter/3.svg'); ?>" class="newsletter-3" alt="">
            <div class="row g-3">
                <div class="col-xl-6">
                    <div class="newsletter-content">
                        <svg>
                            <use xlink:href="<?= base_url('assets/frontend/images/newsletter/newsletter-icon.svg#newsletter'); ?>"></use>
                        </svg>
                        <div>
                            <h3>Abonnez-vous à notre newsletter</h3>
                            <h4>Recevez toutes les informations sur les événements, ventes et offres</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <!-- Formulaire corrigé avec action vers home/sabonner -->
                    <form class="newsletter-form" method="POST" action="<?= base_url('home/sabonner'); ?>">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control" placeholder="Votre adresse e-mail" required>
                            <button class="input-group-text btn newsletter-form-button" type="submit">S'abonner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- News-letter Section End -->




    <!-- Footer Section Start -->
    <footer class="footer-section">
        <div class="custom-container">
            <div class="main-footer">
                <div class="row g-sm-4 g-3">
                    <!-- Contact Info -->
                    <div class="col-xl-3 col-md-4 col-sm-7">
                        <div class="footer-title-2">
                            <h4>Coordonnées</h4>
                        </div>
                        <ul class="footer-content-list">
                            <li>
                                <a href="tel:<?= htmlspecialchars($settings['site_phone'] ?? '+257 68 86 39 45', ENT_QUOTES, 'UTF-8'); ?>" class="content-box">
                                    <svg>
                                        <use xlink:href="<?= base_url('assets/frontend/svg/footer-icon.svg#contact'); ?>"></use>
                                    </svg>
                                    <h4><?= htmlspecialchars($settings['site_phone'] ?? '+257 68 86 39 45', ENT_QUOTES, 'UTF-8'); ?></h4>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="content-box">
                                    <div class="footer-content-icon">
                                        <svg>
                                            <use xlink:href="<?= base_url('assets/frontend/svg/footer-icon.svg#location'); ?>"></use>
                                        </svg>
                                    </div>
                                    <h5><?= htmlspecialchars($settings['site_address'] ?? 'Rohero 1, Avenue Pierre Ndendandumwe, Bujumbura, Burundi', ENT_QUOTES, 'UTF-8'); ?></h5>
                                </a>
                            </li>
                            <li>
                                <a href="mailto:<?= htmlspecialchars($settings['site_email'] ?? 'contact@abemarket.com', ENT_QUOTES, 'UTF-8'); ?>" class="content-box">
                                    <div class="footer-content-icon">
                                        <svg>
                                            <use xlink:href="<?= base_url('assets/frontend/svg/footer-icon.svg#mail'); ?>"></use>
                                        </svg>
                                    </div>
                                    <h5><?= htmlspecialchars($settings['site_email'] ?? 'contact@abemarket.com', ENT_QUOTES, 'UTF-8'); ?></h5>
                                </a>
                            </li>
                        </ul>
                        <div class="social-icon-box">
                            <h5 class="content-color">Restez connectés :</h5>
                            <ul class="social-icon-list">
                                <?php if (!empty($settings['site_facebook'])): ?>
                                <li><a href="<?= htmlspecialchars($settings['site_facebook'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><i class="ri-facebook-fill"></i></a></li>
                                <?php endif; ?>
                                <?php if (!empty($settings['site_twitter'])): ?>
                                <li><a href="<?= htmlspecialchars($settings['site_twitter'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><i class="ri-twitter-x-line"></i></a></li>
                                <?php endif; ?>
                                <?php if (!empty($settings['site_instagram'])): ?>
                                <li><a href="<?= htmlspecialchars($settings['site_instagram'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><i class="ri-instagram-fill"></i></a></li>
                                <?php endif; ?>
                                <?php if (!empty($settings['site_youtube'])): ?>
                                <li><a href="<?= htmlspecialchars($settings['site_youtube'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><i class="ri-youtube-fill"></i></a></li>
                                <?php endif; ?>
                                <?php if (!empty($settings['site_linkedln'])): ?>
                                <li><a href="<?= htmlspecialchars($settings['site_linkedln'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><i class="ri-linkedin-fill"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Information -->
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <div class="footer-title">
                            <h4>Informations</h4>
                        </div>
                        <ul class="footer-list">
                            <li><a href="<?= base_url(); ?>">Accueil</a></li>
                            <li><a href="<?= base_url('Home/about'); ?>">À propos</a></li>
                            <li><a href="<?= base_url('Home/blog'); ?>">Blog</a></li>
                            <li><a href="<?= base_url('Home/offres'); ?>">Promotions</a></li>
                            <li><a href="<?= base_url('Home/search'); ?>">Recherche</a></li>
                            <li><a href="<?= base_url('Home/faq'); ?>">FAQ</a></li>
                            <li><a href="<?= base_url('Home/contact'); ?>">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Our Services -->
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <div class="footer-title">
                            <h4>Nos services</h4>
                        </div>
                        <ul class="footer-list">
                            <?php if (!empty($main_categories)): ?>
                                <?php 
                                $categories_affichees = array_slice($main_categories, 0, 5);
                                foreach($categories_affichees as $cat): 
                                ?>
                                <li><a href="<?= base_url('category/' . $cat['slug_categorie']); ?>"><?= htmlspecialchars($cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><a href="<?= base_url('shop'); ?>">Électronique</a></li>
                                <li><a href="<?= base_url('shop'); ?>">Mode & Habillement</a></li>
                                <li><a href="<?= base_url('shop'); ?>">Maison & Cuisine</a></li>
                                <li><a href="<?= base_url('shop'); ?>">Beauté & Santé</a></li>
                                <li><a href="<?= base_url('shop'); ?>">Alimentation</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- My Account -->
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <div class="footer-title">
                            <h4>Mon compte</h4>
                        </div>
                        <ul class="footer-list">
                            <?php if ($this->session->userdata('user_id')): ?>
                            <li><a href="<?= base_url('user/dashboard'); ?>">Mon compte</a></li>
                            <?php else: ?>
                            <li><a href="#authenticationModal" data-bs-toggle="modal">Connexion</a></li>
                            <?php endif; ?>
                            <li><a href="<?= base_url('home/cart'); ?>">Mon panier</a></li>
                            <li><a href="<?= base_url('home/checkout'); ?>">Validation</a></li>
                            <li><a href="<?= base_url('home/wishlist'); ?>">Ma liste de souhaits</a></li>
                            <li><a href="<?= base_url('order-tracking'); ?>">Suivi commande</a></li>
                        </ul>
                    </div>

                    <!-- Get Shopping App -->
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-4">
                        <div class="footer-title-2">
                            <h4>Téléchargez l'application</h4>
                        </div>
                        <ul class="footer-list-2">
                            <li><p>Commandes rapides et directes</p></li>
                            <li><p>Gagnez du temps, achetez simplement</p></li>
                            <li><p>Économisez plus sur l'application</p></li>
                        </ul>
                        <ul class="app-store-link">
                            <li>
                                <a href="https://play.google.com/store/apps" target="_blank">
                                    <img src="<?= base_url('assets/frontend/images/google-play.svg'); ?>" class="img-fluid" alt="Google Play">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.apple.com/in/app-store/" target="_blank">
                                    <img src="<?= base_url('assets/frontend/images/app-store.svg'); ?>" class="img-fluid" alt="App Store">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="sub-footer">
               <a href="<?= base_url(); ?>" 
   style="display: inline-block; 
          text-decoration: none; 
          transition: opacity 0.3s ease; 
          opacity: 1;"
   onmouseover="this.style.opacity='0.8'"
   onmouseout="this.style.opacity='1'">
    
    <img src="<?= base_url('attachments/Settings/' . $this->Model->get_setting('site_logo', 'logo.png')) ?>" 
         style="max-height: 40px; 
                width: auto; 
                display: block;"
         alt="logo sm">
</a>
               <!-- Liste des modes de paiement avec design inline -->
<ul class="payment-list" style="
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 15px;
    list-style: none;
    margin: 0;
    padding: 0;
">
    <?php 
    // Récupérer les modes de paiement actifs avec logo
    $payment_methods = $this->db->where('est_actif', 1)
                                ->where('logo_url IS NOT NULL', null, false)
                                ->order_by('ordre_affichage', 'ASC')
                                ->get('mode_payement')
                                ->result();
    
    if (!empty($payment_methods)):
        foreach($payment_methods as $payment):
            // Déterminer le chemin du logo
            $logo_path = !empty($payment->logo_url) ? base_url($payment->logo_url) : base_url('assets/frontend/images/payment/placeholder.svg');
    ?>
        <li style="
            display: inline-block;
            transition: transform 0.3s ease;
        ">
            <img src="<?= $logo_path; ?>" 
                 class="img-fluid" 
                 alt="<?= htmlspecialchars($payment->description, ENT_QUOTES, 'UTF-8'); ?>"
                 title="<?= htmlspecialchars($payment->description, ENT_QUOTES, 'UTF-8'); ?>"
                 style="
                     height: 30px;
                     width: auto;
                     max-width: 60px;
                     object-fit: contain;
                     filter: grayscale(0%);
                     transition: all 0.3s ease;
                     cursor: pointer;
                 "
                 onmouseover="this.style.filter='grayscale(0%)'; this.style.transform='scale(1.1)';"
                 onmouseout="this.style.filter='grayscale(0%)'; this.style.transform='scale(1)';">
        </li>
    <?php 
        endforeach;
    else:
        // Fallback : afficher les logos par défaut
    ?>
        <li style="display: inline-block;">
            <img src="<?= base_url('assets/frontend/images/payment/1.svg'); ?>" 
                 style="height: 30px; width: auto; max-width: 60px; object-fit: contain;"
                 class="img-fluid" alt="Visa">
        </li>
        <li style="display: inline-block;">
            <img src="<?= base_url('assets/frontend/images/payment/2.svg'); ?>" 
                 style="height: 30px; width: auto; max-width: 60px; object-fit: contain;"
                 class="img-fluid" alt="Mastercard">
        </li>
        <li style="display: inline-block;">
            <img src="<?= base_url('assets/frontend/images/payment/3.svg'); ?>" 
                 style="height: 30px; width: auto; max-width: 60px; object-fit: contain;"
                 class="img-fluid" alt="PayPal">
        </li>
        <li style="display: inline-block;">
            <img src="<?= base_url('assets/frontend/images/payment/4.svg'); ?>" 
                 style="height: 30px; width: auto; max-width: 60px; object-fit: contain;"
                 class="img-fluid" alt="American Express">
        </li>
        <li style="display: inline-block;">
            <img src="<?= base_url('assets/frontend/images/payment/5.svg'); ?>" 
                 style="height: 30px; width: auto; max-width: 60px; object-fit: contain;"
                 class="img-fluid" alt="Mobile Money">
        </li>
        <li style="display: inline-block;">
            <img src="<?= base_url('assets/frontend/images/payment/6.svg'); ?>" 
                 style="height: 30px; width: auto; max-width: 60px; object-fit: contain;"
                 class="img-fluid" alt="EcoCash">
        </li>
        <li style="display: inline-block;">
            <img src="<?= base_url('assets/frontend/images/payment/7.svg'); ?>" 
                 style="height: 30px; width: auto; max-width: 60px; object-fit: contain;"
                 class="img-fluid" alt="Lumicash">
        </li>
    <?php endif; ?>
</ul>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->









<!-- Cart Offcanvas Start -->
<?php 
// Calcul du panier AVANT le HTML
$cart_items = [];
$cart_subtotal = 0;
$cart_total_items = 0;

if ($this->session->userdata('user_id')) {
    $user_id = $this->session->userdata('user_id');
    try {
        $cart_items = $this->Home_model->getCartItems($user_id);
        if (!empty($cart_items) && is_array($cart_items)) {
            foreach ($cart_items as $item) {
                if (isset($item['sous_total'])) $cart_subtotal += floatval($item['sous_total']);
                if (isset($item['quantite'])) $cart_total_items += intval($item['quantite']);
            }
        }
    } catch (Exception $e) {
        log_message('error', 'Erreur récupération panier: ' . $e->getMessage());
        $cart_items = [];
    }
} else {
    $guestCart = $this->session->userdata('guest_cart') ?: [];
    foreach ($guestCart as $key => $item) {
        $qty = $item['quantity'] ?? 1;
        $price = $item['prix_base'] ?? 0;
        $cart_items[] = [
            'id_panier' => 'guest_' . $key,
            'id_produit' => $item['product_id'],
            'nom_produit' => $item['nom_produit'] ?? 'Produit',
            'slug_produit' => '',
            'prix_effectif' => $price,
            'sous_total' => $price * $qty,
            'quantite' => $qty,
            'image_url' => $item['image_url'] ?? 'assets/frontend/images/product/placeholder.png'
        ];
        $cart_subtotal += $price * $qty;
        $cart_total_items += $qty;
    }
}
?>
<div class="offcanvas offcanvas-end cart-offcanvas" id="cartOffcanvas">
    <div class="offcanvas-header">
        <div class="title-offcanvas">
            <h4>Mon panier <span id="cartCountBadge" class="badge bg-primary ms-2"><?= $cart_total_items ?></span></h4>
            <button class="btn close-btn" data-bs-dismiss="offcanvas">
                <i class="ri-close-fill"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="cart-product-box">
            
            <!-- Loading spinner (caché par défaut) -->
            <div id="cartLoadingSpinner" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
            </div>
            
            <ul class="product-box-list" id="cartItemsList">
                <?php if (!empty($cart_items) && count($cart_items) > 0): ?>
                    <?php foreach($cart_items as $item): ?>
                    <li class="vertical-product-box" data-cart-id="<?= isset($item['id_panier']) ? $item['id_panier'] : ''; ?>">
                        <a href="<?= base_url('product/' . ($item['slug_produit'] ?? '#')); ?>" class="product-image">
                            <img src="<?= base_url($item['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($item['nom_produit'] ?? 'Produit', ENT_QUOTES, 'UTF-8'); ?>">
                        </a>
                        <div class="product-content">
                            <a href="<?= base_url('product/' . ($item['slug_produit'] ?? '#')); ?>">
                                <h5 class="name title-color"><?= htmlspecialchars($item['nom_produit'] ?? 'Produit', ENT_QUOTES, 'UTF-8'); ?></h5>
                            </a>
                            <h5 class="price" data-price="<?= $item['prix_effectif'] ?? 0; ?>"><?= number_format($item['prix_effectif'] ?? 0, 0, ',', ' '); ?> BIF</h5>
                            <div class="quantity-box qty-container">
                                <button class="btn qty-btn-minus update-offcanvas-qty" data-cart-id="<?= $item['id_panier'] ?? ''; ?>" data-change="-1">
                                    <i class="ri-subtract-line"></i>
                                </button>
                                <input type="number" name="qty" class="quantity form-control input-qty offcanvas-qty" data-cart-id="<?= $item['id_panier'] ?? ''; ?>" value="<?= $item['quantite'] ?? 1; ?>" data-price="<?= $item['prix_effectif'] ?? 0; ?>" readonly>
                                <button class="btn qty-btn-plus update-offcanvas-qty" data-cart-id="<?= $item['id_panier'] ?? ''; ?>" data-change="1">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn close-button remove-offcanvas-item" data-cart-id="<?= $item['id_panier'] ?? ''; ?>">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </li>
                    <?php endforeach; ?>
                <?php else: ?>
                <li class="empty-cart" id="emptyCartMessage">
                    <svg>
                        <use xlink:href="<?= base_url('assets/frontend/images/inner-page/empty-cart.svg#emptyCart'); ?>"></use>
                    </svg>
                    <h4>Votre panier est vide.</h4>
                    <?php if(!($this->session->userdata('user_id'))): ?>
                    <p class="mt-3 text-muted">
                        <a href="<?= base_url('login'); ?>" class="text-primary">Connectez-vous</a> pour voir vos articles.
                    </p>
                    <?php else: ?>
                    <p class="mt-3 text-muted">Découvrez nos produits et ajoutez-les à votre panier.</p>
                    <a href="<?= base_url('shop'); ?>" class="btn btn-primary mt-2">Commencer mes achats</a>
                    <?php endif; ?>
                </li>
                <?php endif; ?>
            </ul>

            <div class="total-price-box" id="cartTotalBox" style="<?= empty($cart_items) ? 'display: none;' : ''; ?>">
                <h4 class="sub-total">Sous-total <span id="cartSubtotal"><?= number_format($cart_subtotal, 0, ',', ' '); ?> BIF</span></h4>
                <p class="tax-text">Taxes incluses <span>frais de livraison</span> calculés à la validation.</p>
                <div class="cart-btn-group">
                    <a href="<?= base_url('checkout'); ?>" class="btn check-out-button <?= empty($cart_items) ? 'disabled' : ''; ?>">Valider</a>
                    <a href="<?= base_url('cart'); ?>" class="btn cart-button">Voir le panier</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mise à jour dynamique du compteur de panier
function updateCartCount() {
    <?php if($this->session->userdata('user_id')): ?>
    $.ajax({
        url: '<?= base_url("cart/get_cart_count"); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.count !== undefined) {
                $('#cartCountBadge').text(response.count);
            }
        },
        error: function() {
            // Fallback: recharger la page ou utiliser la valeur PHP
            $('#cartCountBadge').text(<?= $cart_total_items ?>);
        }
    });
    <?php endif; ?>
}

// Mettre à jour le compteur après chaque modification du panier
$(document).on('click', '.update-offcanvas-qty, .remove-offcanvas-item', function() {
    setTimeout(updateCartCount, 500);
});

// Initialiser le compteur au chargement de la page
$(document).ready(function() {
    <?php if($this->session->userdata('user_id') && $cart_total_items > 0): ?>
    $('#cartCountBadge').text(<?= $cart_total_items ?>);
    <?php endif; ?>
});
</script>
<!-- Cart Offcanvas End -->




<script>
$(document).ready(function() {
    let currentCoupon = null;
    let currentDiscount = 0;
    const fraisLivraison = <?= $frais_livraison ?? 2000; ?>;
    
    // ========== FONCTIONS POUR L'OFFCANVAS ==========
    
    // Mise à jour de la quantité dans l'offcanvas
    $(document).on('click', '.update-offcanvas-qty', function() {
        let cartId = $(this).data('cart-id');
        let change = parseInt($(this).data('change'));
        let $qtyInput = $(`.offcanvas-qty[data-cart-id="${cartId}"]`);
        let currentQty = parseInt($qtyInput.val());
        let newQty = currentQty + change;
        
        if (newQty >= 1 && newQty <= 99) {
            updateCartQuantityOffcanvas(cartId, newQty, $qtyInput);
        }
    });
    
    // Suppression d'article dans l'offcanvas (AVEC SweetAlert)
    $(document).on('click', '.remove-offcanvas-item', function() {
        let cartId = $(this).data('cart-id');
        Swal.fire({
            title: 'Supprimer l\'article ?',
            text: 'Voulez-vous vraiment supprimer cet article du panier ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                removeFromCartOffcanvas(cartId, $(this).closest('li'));
            }
        });
    });
    
    // Mise à jour de la quantité dans l'offcanvas (AJAX) - SANS SweetAlert
    function updateCartQuantityOffcanvas(cartId, quantity, $input) {
        $input.prop('readonly', true);
        
        // Animation sur l'input
        $input.css('background-color', '#e8f5e9');
        setTimeout(function() {
            $input.css('background-color', '#f9f9f9');
        }, 300);
        
        $.ajax({
            url: '<?= base_url("home/updateCart"); ?>',
            type: 'POST',
            data: { cart_id: cartId, quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $input.val(quantity);
                    updateOffcanvasTotals();
                    updateCartCountHeader(response.cart_count);
                    
                    // Mettre à jour la page cart si elle est ouverte
                    if (window.location.pathname.includes('/cart')) {
                        updateLocalCartTotals();
                        updateCartPageQuantity(cartId, quantity);
                    }
                    
                    // Animation discrète de succès (flash vert)
                    $input.css('background-color', '#a5d6a7');
                    setTimeout(function() {
                        $input.css('background-color', '#f9f9f9');
                    }, 500);
                } else {
                    // Recharger en cas d'erreur
                    location.reload();
                }
            },
            error: function() {
                location.reload();
            },
            complete: function() {
                $input.prop('readonly', false);
            }
        });
    }
    
    // Suppression d'article dans l'offcanvas
    function removeFromCartOffcanvas(cartId, $item) {
        $item.css('transition', 'all 0.3s ease');
        $item.css('opacity', '0');
        $item.css('transform', 'translateX(-20px)');
        
        $.ajax({
            url: '<?= base_url("home/removeFromCart"); ?>',
            type: 'POST',
            data: { cart_id: cartId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    setTimeout(function() {
                        $item.remove();
                        updateCartCountHeader(response.cart_count);
                        
                        // Vérifier si le panier est vide
                        if ($('#cartItemsList li.vertical-product-box').length === 0) {
                            $('#cartItemsList').html(`
                                <li class="empty-cart" id="emptyCartMessage">
                                    <svg><use xlink:href="<?= base_url('assets/frontend/images/inner-page/empty-cart.svg#emptyCart'); ?>"></use></svg>
                                    <h4>Votre panier est vide.</h4>
                                </li>
                            `);
                        }
                        
                        updateOffcanvasTotals();
                        
                        // Mettre à jour la page cart si elle est ouverte
                        if (window.location.pathname.includes('/cart')) {
                            if ($('.cart-item').length === 1) {
                                location.reload();
                            } else {
                                $(`.cart-item[data-cart-id="${cartId}"]`).remove();
                                updateLocalCartTotals();
                            }
                        }
                        
                        Swal.fire({
                            title: 'Supprimé !',
                            text: 'Article supprimé du panier',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }, 300);
                } else {
                    $item.css('opacity', '1');
                    $item.css('transform', 'translateX(0)');
                    Swal.fire({
                        title: 'Erreur',
                        text: response.message || 'Erreur lors de la suppression',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                $item.css('opacity', '1');
                $item.css('transform', 'translateX(0)');
                Swal.fire({
                    title: 'Erreur',
                    text: 'Erreur lors de la suppression',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
    
    // Mettre à jour les totaux dans l'offcanvas
    function updateOffcanvasTotals() {
        let newSubtotal = 0;
        
        $('#cartItemsList li.vertical-product-box').each(function() {
            let $item = $(this);
            let quantity = parseInt($item.find('.offcanvas-qty').val());
            let price = parseFloat($item.find('.price').data('price'));
            if (!isNaN(quantity) && !isNaN(price)) {
                newSubtotal += quantity * price;
            }
        });
        
        $('#cartSubtotal').text(formatNumber(newSubtotal) + ' BIF');
        
        // Animation sur le total
        $('#cartSubtotal').css('transition', 'all 0.3s ease');
        $('#cartSubtotal').css('color', '#f68b1e');
        setTimeout(function() {
            $('#cartSubtotal').css('color', '');
        }, 500);
    }
    
    // Mettre à jour le compteur dans l'en-tête
    function updateCartCountHeader(count) {
        $('#cart-count-header').text(count);
        $('.cart-count, .cart-badge, .label span').first().text(count);
    }
    
    // Mettre à jour la quantité sur la page cart
    function updateCartPageQuantity(cartId, quantity) {
        let $cartPageInput = $(`.cart-qty[data-cart-id="${cartId}"]`);
        if ($cartPageInput.length) {
            $cartPageInput.val(quantity);
        }
    }
    
    // ========== FONCTIONS POUR LA PAGE CART ==========
    
    // Mise à jour de la quantité (boutons + et -)
    $('.update-cart-btn').click(function() {
        var cartId = $(this).data('cart-id');
        var change = $(this).data('change');
        var $qtyInput = $('.cart-qty[data-cart-id="' + cartId + '"]');
        var currentQty = parseInt($qtyInput.val());
        var newQty = currentQty + change;
        
        if (newQty >= 1 && newQty <= 99) {
            updateCartQuantityPage(cartId, newQty, $qtyInput);
        }
    });
    
    // Modification manuelle de la quantité
    $('.cart-qty').on('change', function() {
        var cartId = $(this).data('cart-id');
        var newQty = parseInt($(this).val());
        
        if (newQty >= 1 && newQty <= 99 && !isNaN(newQty)) {
            updateCartQuantityPage(cartId, newQty, $(this));
        } else if (newQty < 1) {
            $(this).val(1);
            updateCartQuantityPage(cartId, 1, $(this));
        } else if (newQty > 99) {
            $(this).val(99);
            updateCartQuantityPage(cartId, 99, $(this));
        }
    });
    
    // Suppression d'un article sur la page cart (AVEC SweetAlert)
    $('.remove-cart-item').click(function() {
        var cartId = $(this).data('cart-id');
        Swal.fire({
            title: 'Supprimer l\'article ?',
            text: 'Voulez-vous vraiment supprimer cet article du panier ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                removeFromCartPage(cartId, $(this).closest('.cart-item'));
            }
        });
    });
    
    // Mise à jour de la quantité sur la page cart - SANS SweetAlert
    function updateCartQuantityPage(cartId, quantity, $input) {
        $input.prop('readonly', true);
        
        // Animation sur l'input
        $input.css('background-color', '#e8f5e9');
        setTimeout(function() {
            $input.css('background-color', '#f9f9f9');
        }, 300);
        
        $.ajax({
            url: '<?= base_url("home/updateCart"); ?>',
            type: 'POST',
            data: { cart_id: cartId, quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $input.val(quantity);
                    updateLocalCartTotals();
                    updateCartCountHeader(response.cart_count);
                    
                    // Mettre à jour l'offcanvas si ouvert
                    let $offcanvasQty = $(`.offcanvas-qty[data-cart-id="${cartId}"]`);
                    if ($offcanvasQty.length) {
                        $offcanvasQty.val(quantity);
                        updateOffcanvasTotals();
                    }
                    
                    // Animation discrète de succès (flash vert)
                    $input.css('background-color', '#a5d6a7');
                    setTimeout(function() {
                        $input.css('background-color', '#f9f9f9');
                    }, 500);
                    
                    // Animation sur le total
                    $('#total').css('transition', 'all 0.3s ease');
                    $('#total').css('color', '#28a745');
                    setTimeout(function() {
                        $('#total').css('color', '');
                    }, 500);
                } else {
                    location.reload();
                }
            },
            error: function() {
                location.reload();
            },
            complete: function() {
                $input.prop('readonly', false);
            }
        });
    }
    
    // Suppression d'article sur la page cart
    function removeFromCartPage(cartId, $cartItem) {
        $cartItem.css('transition', 'all 0.3s ease');
        $cartItem.css('opacity', '0');
        $cartItem.css('transform', 'translateX(-20px)');
        
        $.ajax({
            url: '<?= base_url("home/removeFromCart"); ?>',
            type: 'POST',
            data: { cart_id: cartId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    setTimeout(function() {
                        $cartItem.remove();
                        updateCartCountHeader(response.cart_count);
                        
                        if ($('.cart-item').length === 0) {
                            location.reload();
                        } else {
                            updateLocalCartTotals();
                        }
                        
                        // Mettre à jour l'offcanvas
                        let $offcanvasItem = $(`.vertical-product-box[data-cart-id="${cartId}"]`);
                        if ($offcanvasItem.length) {
                            $offcanvasItem.remove();
                            updateOffcanvasTotals();
                            if ($('#cartItemsList li.vertical-product-box').length === 0) {
                                $('#cartItemsList').html(`
                                    <li class="empty-cart">
                                        <svg><use xlink:href="<?= base_url('assets/frontend/images/inner-page/empty-cart.svg#emptyCart'); ?>"></use></svg>
                                        <h4>Votre panier est vide.</h4>
                                    </li>
                                `);
                            }
                        }
                        
                        Swal.fire({
                            title: 'Supprimé !',
                            text: 'Article supprimé du panier',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }, 300);
                } else {
                    $cartItem.css('opacity', '1');
                    $cartItem.css('transform', 'translateX(0)');
                    Swal.fire({
                        title: 'Erreur',
                        text: response.message || 'Erreur lors de la suppression',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function() {
                $cartItem.css('opacity', '1');
                $cartItem.css('transform', 'translateX(0)');
                Swal.fire({
                    title: 'Erreur',
                    text: 'Erreur lors de la suppression',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
    
    // Mise à jour locale des totaux
    function updateLocalCartTotals() {
        let newSubtotal = 0;
        
        $('.cart-item').each(function() {
            let quantity = parseInt($(this).find('.cart-qty').val());
            let price = parseFloat($(this).find('.cart-qty').data('price'));
            if (!isNaN(quantity) && !isNaN(price)) {
                newSubtotal += quantity * price;
            }
        });
        
        let newTotal = newSubtotal + fraisLivraison - currentDiscount;
        
        $('#subtotal').text(formatNumber(newSubtotal) + ' BIF');
        $('#total').text(formatNumber(newTotal) + ' BIF');
        
        if (currentCoupon && currentDiscount > 0) {
            recalculateWithCoupon(newSubtotal);
        }
    }
    
    // Recalculer avec coupon
    function recalculateWithCoupon(subtotal) {
        $.ajax({
            url: '<?= base_url("home/applyCoupon"); ?>',
            type: 'POST',
            data: { coupon: currentCoupon, subtotal: subtotal },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    currentDiscount = response.discount;
                    let newTotal = subtotal + fraisLivraison - currentDiscount;
                    $('#discount-row').show();
                    $('#discount-amount').text('- ' + formatNumber(currentDiscount) + ' BIF');
                    $('#total').text(formatNumber(newTotal) + ' BIF');
                }
            }
        });
    }
    
    // Application du coupon
    $('#applyCoupon').click(function() {
        var couponCode = $('#couponCode').val().trim();
        if (couponCode) {
            applyCoupon(couponCode);
        } else {
            Swal.fire({
                title: 'Code promo vide',
                text: 'Veuillez entrer un code promo',
                icon: 'warning',
                confirmButtonText: 'OK',
                timer: 2000
            });
        }
    });
    
    function applyCoupon(couponCode) {
        $.ajax({
            url: '<?= base_url("home/applyCoupon"); ?>',
            type: 'POST',
            data: { coupon: couponCode },
            dataType: 'json',
            beforeSend: function() {
                $('#applyCoupon').prop('disabled', true).text('Vérification...');
            },
            success: function(response) {
                if (response.success) {
                    currentCoupon = couponCode;
                    currentDiscount = response.discount;
                    
                    let currentSubtotal = parseFloat($('#subtotal').text().replace(/[^0-9]/g, ''));
                    let newTotal = currentSubtotal + fraisLivraison - currentDiscount;
                    
                    $('#discount-row').show();
                    $('#discount-amount').text('- ' + formatNumber(currentDiscount) + ' BIF');
                    $('#total').text(formatNumber(newTotal) + ' BIF');
                    $('#couponCode').prop('disabled', true);
                    
                    Swal.fire({
                        title: 'Code promo appliqué !',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        timer: 2500
                    });
                } else {
                    Swal.fire({
                        title: 'Code promo invalide',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    currentDiscount = 0;
                    currentCoupon = null;
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Erreur',
                    text: 'Erreur lors de l\'application du code promo',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                $('#applyCoupon').prop('disabled', false).text('Appliquer');
            }
        });
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(number));
    }
});
</script>















<!-- Wishlist Offcanvas Start -->
<div class="offcanvas offcanvas-end wishlist-offcanvas cart-offcanvas" id="wishlistOffcanvas" tabindex="-1">
    <div class="offcanvas-header">
        <div class="title-offcanvas">
            <h4>Ma liste de souhaits <span id="wishlistCountBadge" class="badge bg-primary ms-2"><?= isset($wishlist_count) ? $wishlist_count : 0 ?></span></h4>
            <button class="btn close-btn" data-bs-dismiss="offcanvas">
                <i class="ri-close-fill"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="cart-product-box">
            <?php 
            // Récupération directe et sécurisée des données
            $user_id = $this->session->userdata('user_id');
            $wishlist_items = [];
            $wishlist_total = 0;
            
            if ($user_id) {
                $wishlist_items = $this->Home_model->getWishlist($user_id);
                $wishlist_total = count($wishlist_items);
            }
            ?>
            
            <ul class="product-box-list" id="wishlistItemsList">
                <?php if (!empty($wishlist_items) && $wishlist_total > 0): ?>
                    <?php foreach($wishlist_items as $item): ?>
                    <li>
                        <div class="vertical-product-box">
                            <a href="<?= base_url('product/' . $item['slug_produit']); ?>" class="product-image">
                                <img src="<?= base_url($item['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($item['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                <span class="stock-badge <?= $item['quantite_actuelle'] > 0 ? 'en-stock' : 'rupture'; ?>">
                                    <?= $item['quantite_actuelle'] > 0 ? 'En stock' : 'Rupture'; ?>
                                </span>
                            </a>
                            <div class="product-content">
                                <a href="<?= base_url('product/' . $item['slug_produit']); ?>">
                                    <h5 class="name title-color"><?= htmlspecialchars($item['nom_produit'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                </a>
                                <?php 
                                    $prix_affiche = (!empty($item['prix_promo']) && $item['prix_promo'] < $item['prix_base']) ? $item['prix_promo'] : $item['prix_base'];
                                ?>
                                <h5 class="price"><?= number_format($prix_affiche, 0, ',', ' '); ?> BIF</h5>
                                <?php if(!empty($item['prix_promo']) && $item['prix_promo'] < $item['prix_base']): ?>
                                    <span class="old-price"><?= number_format($item['prix_base'], 0, ',', ' '); ?> BIF</span>
                                <?php endif; ?>
                                <button class="btn cart-btn move-to-cart" data-product-id="<?= $item['id_produit']; ?>" data-product-name="<?= htmlspecialchars($item['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="ri-shopping-cart-line"></i> Ajouter au panier
                                </button>
                            </div>
                            <button class="btn wishlist-btn remove-wishlist-item" data-product-id="<?= $item['id_produit']; ?>">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </li>
                    <?php endforeach; ?>
                <?php else: ?>
                <li class="empty-cart">
                    <svg>
                        <use xlink:href="<?= base_url('assets/frontend/images/inner-page/empty-wishlist.svg#emptyWishlist'); ?>"></use>
                    </svg>
                    <h4>Votre liste de souhaits est vide.</h4>
                    <p class="mt-3">Connectez-vous pour voir vos produits favoris.</p>
                </li>
                <?php endif; ?>
            </ul>
            <div class="total-price-box">
                <p class="tax-text">Ajoutez vos produits préférés à votre panier pour finaliser votre achat.</p>
                <div class="cart-btn-group">
                    <a href="<?= base_url('wishlist'); ?>" class="btn check-out-button">Voir la liste complète</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Wishlist Offcanvas End -->






<script>
// ============================================
// GESTION DE LA WISHLIST (AJAX)
// ============================================

// Supprimer un élément de la wishlist
document.querySelectorAll('.remove-wishlist-item').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const listItem = this.closest('li');
        
        fetch('<?= base_url("home/removeFromWishlistAjax") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer l'élément de la liste
                listItem.remove();
                
                // Mettre à jour le compteur
                const wishlistCountBadge = document.getElementById('wishlistCountBadge');
                if (wishlistCountBadge) {
                    wishlistCountBadge.textContent = data.wishlist_count;
                }
                
                // Mettre à jour le compteur dans le header
                const headerWishlistCount = document.querySelector('.wishlist-count');
                if (headerWishlistCount) {
                    headerWishlistCount.textContent = data.wishlist_count;
                }
                
                // Vérifier si la wishlist est vide
                const itemsList = document.getElementById('wishlistItemsList');
                if (itemsList.children.length === 0) {
                    itemsList.innerHTML = '<li class="empty-cart"><svg><use xlink:href="<?= base_url("assets/frontend/images/inner-page/empty-wishlist.svg#emptyWishlist") ?>"></use></svg><h4>Votre liste de souhaits est vide.</h4></li>';
                }
                
                // Notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Supprimé',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message
                    });
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// Déplacer un produit de la wishlist vers le panier
document.querySelectorAll('.move-to-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const listItem = this.closest('li');
        const originalText = this.innerHTML;
        
        this.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Chargement...';
        this.disabled = true;
        
        fetch('<?= base_url("home/moveToCartFromWishlist") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer l'élément de la wishlist
                listItem.remove();
                
                // Mettre à jour les compteurs
                const wishlistCountBadge = document.getElementById('wishlistCountBadge');
                if (wishlistCountBadge) {
                    wishlistCountBadge.textContent = data.wishlist_count;
                }
                
                const headerWishlistCount = document.querySelector('.wishlist-count');
                if (headerWishlistCount) {
                    headerWishlistCount.textContent = data.wishlist_count;
                }
                
                const headerCartCount = document.querySelector('.cart-count');
                if (headerCartCount) {
                    headerCartCount.textContent = data.cart_count;
                }
                
                // Vérifier si la wishlist est vide
                const itemsList = document.getElementById('wishlistItemsList');
                if (itemsList.children.length === 0) {
                    itemsList.innerHTML = '<li class="empty-cart"><svg><use xlink:href="<?= base_url("assets/frontend/images/inner-page/empty-wishlist.svg#emptyWishlist") ?>"></use></svg><h4>Votre liste de souhaits est vide.</h4></li>';
                }
                
                // Notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Ajouté au panier',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message
                    });
                }
                this.innerHTML = originalText;
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.innerHTML = originalText;
            this.disabled = false;
        });
    });
});

// Rafraîchir la wishlist via AJAX (optionnel)
function refreshWishlistOffcanvas() {
    fetch('<?= base_url("home/getWishlistOffcanvas") ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const wishlistItemsList = document.getElementById('wishlistItemsList');
                const wishlistCountBadge = document.getElementById('wishlistCountBadge');
                
                if (wishlistItemsList) {
                    wishlistItemsList.innerHTML = data.html;
                }
                if (wishlistCountBadge) {
                    wishlistCountBadge.textContent = data.count;
                }
                
                // Réattacher les événements après mise à jour
                attachWishlistEvents();
            }
        })
        .catch(error => console.error('Error:', error));
}

function attachWishlistEvents() {
    // Réattacher les événements de suppression
    document.querySelectorAll('.remove-wishlist-item').forEach(btn => {
        btn.removeEventListener('click', btn._handler);
        btn._handler = function() {
            const productId = this.dataset.productId;
            const listItem = this.closest('li');
            
            fetch('<?= base_url("home/removeFromWishlistAjax") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    listItem.remove();
                    document.getElementById('wishlistCountBadge').textContent = data.wishlist_count;
                    document.querySelector('.wishlist-count').textContent = data.wishlist_count;
                    if (document.getElementById('wishlistItemsList').children.length === 0) {
                        document.getElementById('wishlistItemsList').innerHTML = '<li class="empty-cart"><svg><use xlink:href="<?= base_url("assets/frontend/images/inner-page/empty-wishlist.svg#emptyWishlist") ?>"></use></svg><h4>Votre liste de souhaits est vide.</h4></li>';
                    }
                }
            });
        };
        btn.addEventListener('click', btn._handler);
    });
    
    // Réattacher les événements "Ajouter au panier"
    document.querySelectorAll('.move-to-cart').forEach(btn => {
        btn.removeEventListener('click', btn._cartHandler);
        btn._cartHandler = function() {
            const productId = this.dataset.productId;
            const listItem = this.closest('li');
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="ri-loader-4-line ri-spin"></i>';
            this.disabled = true;
            
            fetch('<?= base_url("home/moveToCartFromWishlist") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    listItem.remove();
                    document.getElementById('wishlistCountBadge').textContent = data.wishlist_count;
                    document.querySelector('.wishlist-count').textContent = data.wishlist_count;
                    document.querySelector('.cart-count').textContent = data.cart_count;
                    if (document.getElementById('wishlistItemsList').children.length === 0) {
                        document.getElementById('wishlistItemsList').innerHTML = '<li class="empty-cart"><svg><use xlink:href="<?= base_url("assets/frontend/images/inner-page/empty-wishlist.svg#emptyWishlist") ?>"></use></svg><h4>Votre liste de souhaits est vide.</h4></li>';
                    }
                }
                this.innerHTML = originalText;
                this.disabled = false;
            });
        };
        btn.addEventListener('click', btn._cartHandler);
    });
}

// Initialiser les événements
document.addEventListener('DOMContentLoaded', attachWishlistEvents);
</script>

<style>
/* Styles pour la wishlist */
.wishlist-offcanvas .vertical-product-box {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
    position: relative;
}

.wishlist-offcanvas .product-image {
    width: 80px;
    height: 80px;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.wishlist-offcanvas .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.wishlist-offcanvas .stock-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 4px;
    background: rgba(0,0,0,0.7);
    color: white;
}

.wishlist-offcanvas .stock-badge.en-stock {
    background: #28a745;
}

.wishlist-offcanvas .stock-badge.rupture {
    background: #dc3545;
}

.wishlist-offcanvas .product-content {
    flex: 1;
}

.wishlist-offcanvas .product-content .name {
    font-size: 14px;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.wishlist-offcanvas .product-content .price {
    color: #f97316;
    font-weight: 600;
    margin-bottom: 8px;
}

.wishlist-offcanvas .product-content .old-price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
    margin-left: 8px;
}

.wishlist-offcanvas .cart-btn {
    background: #f97316;
    color: white;
    padding: 5px 12px;
    font-size: 12px;
    border-radius: 20px;
}

.wishlist-offcanvas .cart-btn:hover {
    background: #e0670e;
}

.wishlist-offcanvas .wishlist-btn {
    background: transparent;
    color: #dc3545;
    font-size: 18px;
    padding: 5px;
}

.wishlist-offcanvas .wishlist-btn:hover {
    transform: scale(1.1);
}

.wishlist-offcanvas .empty-cart {
    text-align: center;
    padding: 40px 20px;
}

.wishlist-offcanvas .empty-cart svg {
    width: 100px;
    height: 100px;
    margin-bottom: 15px;
}

#wishlistCountBadge {
    background: #f97316;
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 20px;
}
</style>


















<!-- Authentication Modal Start -->
<div class="modal authentication-modal fade" id="authenticationModal" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ri-close-fill"></i>
                </button>
                
              <!-- Login Box -->
<div class="authentication-box login-box" style="display: block;">
    <div class="auth-title">
        <div class="auth-icon">
            <i class="ri-lock-line"></i>
        </div>
        <h4>Connexion</h4>
        <p>Bienvenue ! Pour accéder à votre compte, veuillez saisir votre email et mot de passe.</p>
    </div>

    <form id="loginForm" method="POST" action="<?= base_url('auth/login'); ?>">
        <div class="mb-3">
            <div class="input-icon-group">
                <i class="ri-mail-line"></i>
                <input type="email" name="email" id="login_email" class="form-control" placeholder="Votre adresse email" required>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-icon-group">
                <i class="ri-lock-line"></i>
                <input type="password" name="password" id="login_password" class="form-control" placeholder="Votre mot de passe" required>
                <i class="ri-eye-off-line toggle-password" style="cursor: pointer;"></i>
            </div>
        </div>
        <div class="forgot-box">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
            </div>
            <a href="#" class="forgot-pass forgot-password-btn">Mot de passe oublié ?</a>
        </div>
        <button type="submit" class="btn btn-bg-theme mt-3 w-100" id="loginSubmitBtn">
            <i class="ri-login-box-line"></i> Se connecter
        </button>
        <div class="divider">
            <span>OU</span>
        </div>
        <h5 class="sign-up-next">Vous n'avez pas de compte ? 
            <button class="next-button btn" type="button">Inscrivez-vous</button>
        </h5>
    </form>
</div>
               <!-- Signup Box -->
<div class="authentication-box signup-box" style="display: none;">
    <div class="auth-title">
        <div class="auth-icon">
            <i class="ri-user-add-line"></i>
        </div>
        <h4>Inscription</h4>
        <p>Créez votre compte pour profiter de tous nos services.</p>
    </div>
    
    <form id="registerForm" method="POST" action="<?= base_url('auth/register'); ?>">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="input-icon-group">
                    <i class="ri-user-line"></i>
                    <input type="text" name="prenom" id="reg_prenom" class="form-control" placeholder="Votre prénom" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-icon-group">
                    <i class="ri-user-line"></i>
                    <input type="text" name="nom" id="reg_nom" class="form-control" placeholder="Votre nom" required>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-icon-group">
                <i class="ri-mail-line"></i>
                <input type="email" name="email" id="reg_email" class="form-control" placeholder="Votre adresse email" required>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-icon-group">
                <i class="ri-phone-line"></i>
                <input type="tel" name="telephone" id="reg_telephone" class="form-control" placeholder="Votre numéro de téléphone" required>
            </div>
        </div>
        <div class="mb-3">
            <div class="input-icon-group">
                <i class="ri-lock-line"></i>
                <input type="password" name="password" id="reg_password" class="form-control" placeholder="Créez votre mot de passe" required>
                <i class="ri-eye-off-line toggle-password" style="cursor: pointer;"></i>
            </div>
            <small class="text-muted">Minimum 6 caractères</small>
        </div>
        <div class="mb-3">
            <div class="input-icon-group">
                <i class="ri-lock-line"></i>
                <input type="password" name="confirm_password" id="reg_confirm_password" class="form-control" placeholder="Confirmez votre mot de passe" required>
                <i class="ri-eye-off-line toggle-password" style="cursor: pointer;"></i>
            </div>
        </div>
        <div class="forgot-box">
            <div class="form-check">
                <input type="checkbox" name="agree_terms" class="form-check-input" id="agreeTerms" required>
                <label class="form-check-label" for="agreeTerms">
                    J'accepte les <a href="<?= base_url('conditions'); ?>" target="_blank">conditions d'utilisation</a>
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-bg-theme mt-3 w-100" id="registerSubmitBtn">
            <i class="ri-user-add-line"></i> S'inscrire
        </button>
        <div class="divider">
            <span>OU</span>
        </div>
        <h5 class="sign-up-next">J'ai déjà un compte. 
            <button class="btn next-button2" type="button">Se connecter</button>
        </h5>
    </form>
</div>

              <!-- Forgot Password Box -->
<div class="authentication-box forgot-password-box" style="display: none;">
    <div class="auth-title">
        <div class="auth-icon">
            <i class="ri-key-line"></i>
        </div>
        <h4>Réinitialisation du mot de passe</h4>
        <p>Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>
    </div>
    <form id="forgotPasswordForm" method="POST" action="<?= base_url('auth/forgot_password'); ?>">
        <div class="mb-3">
            <div class="input-icon-group">
                <i class="ri-mail-line"></i>
                <input type="email" name="email" id="forgot_email" class="form-control" placeholder="Votre adresse email" required>
            </div>
        </div>
        <div class="d-flex flex-wrap-nowrap mt-3 gap-3">
            <button type="submit" class="btn btn-bg-theme w-100" id="forgotSubmitBtn">
                <i class="ri-send-plane-line"></i> Envoyer
            </button>
            <button type="button" class="btn btn-bg-theme w-100 cancel-btn login-back-btn">
                <i class="ri-arrow-go-back-line"></i> Annuler
            </button>
        </div>
    </form>
</div>
            </div>
        </div>
    </div>
</div>
<!-- Authentication Modal End -->
<style type="text/css">
    /* ============================================
   MODAL AUTHENTIFICATION
   ============================================ */

/* Conteneur principal du modal */
.authentication-modal .modal-dialog {
    max-width: 500px;
}

.authentication-modal .modal-content {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 30px 50px rgba(0, 0, 0, 0.2);
    border: none;
}

.authentication-modal .modal-body {
    padding: 0;
    position: relative;
}

/* Bouton de fermeture */
.authentication-modal .btn-close {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
    background: none;
    font-size: 24px;
    opacity: 0.7;
    transition: opacity 0.3s ease, transform 0.3s ease;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    cursor: pointer;
}

.authentication-modal .btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
    background: rgba(0, 0, 0, 0.05);
}

.authentication-modal .btn-close i {
    font-size: 20px;
    color: #666;
}

/* Conteneur des boxes */
.authentication-modal .authentication-box {
    padding: 40px 35px;
}

/* Icône de titre */
.authentication-modal .auth-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #ff6600, #ff8533);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 8px 20px rgba(255, 102, 0, 0.3);
}

.authentication-modal .auth-icon i {
    font-size: 32px;
    color: white;
}

/* Titre et description */
.authentication-modal .auth-title {
    text-align: center;
    margin-bottom: 30px;
}

.authentication-modal .auth-title h4 {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #1a1a1a;
}

.authentication-modal .auth-title p {
    font-size: 14px;
    color: #777;
    line-height: 1.5;
    margin-bottom: 0;
}

/* Groupes d'input avec icônes */
.authentication-modal .input-icon-group {
    position: relative;
    display: flex;
    align-items: center;
}

.authentication-modal .input-icon-group i:first-child {
    position: absolute;
    left: 15px;
    color: #aaa;
    font-size: 18px;
    z-index: 1;
    transition: color 0.3s ease;
}

.authentication-modal .input-icon-group input {
    width: 100%;
    height: 50px;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    padding: 0 45px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.authentication-modal .input-icon-group input:focus {
    border-color: #ff6600;
    box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.1);
    outline: none;
    background: white;
}

.authentication-modal .input-icon-group input:focus + i:first-child {
    color: #ff6600;
}

.authentication-modal .input-icon-group .toggle-password {
    position: absolute;
    right: 15px;
    color: #aaa;
    font-size: 18px;
    cursor: pointer;
    z-index: 1;
    transition: color 0.3s ease;
}

.authentication-modal .input-icon-group .toggle-password:hover {
    color: #ff6600;
}

/* Zone de texte d'aide */
.authentication-modal .text-muted {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
    display: block;
}

/* Box des options (se souvenir / mot de passe oublié) */
.authentication-modal .forgot-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px 0 10px;
}

.authentication-modal .forgot-box .form-check {
    padding-left: 1.8em;
}

.authentication-modal .forgot-box .form-check-input {
    width: 16px;
    height: 16px;
    margin-top: 2px;
    cursor: pointer;
}

.authentication-modal .forgot-box .form-check-input:checked {
    background-color: #ff6600;
    border-color: #ff6600;
}

.authentication-modal .forgot-box .form-check-label {
    font-size: 13px;
    color: #666;
    cursor: pointer;
}

.authentication-modal .forgot-pass {
    font-size: 13px;
    color: #ff6600;
    text-decoration: none;
    transition: color 0.3s ease;
}

.authentication-modal .forgot-pass:hover {
    color: #e55a00;
    text-decoration: underline;
}

/* Bouton principal */
.authentication-modal .btn-bg-theme {
    background: linear-gradient(135deg, #ff6600, #ff8533);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.authentication-modal .btn-bg-theme:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 102, 0, 0.3);
    background: linear-gradient(135deg, #e55a00, #ff6600);
}

.authentication-modal .btn-bg-theme:active {
    transform: translateY(0);
}

.authentication-modal .btn-bg-theme i {
    font-size: 18px;
}

/* Divider "OU" */
.authentication-modal .divider {
    position: relative;
    text-align: center;
    margin: 25px 0;
}

.authentication-modal .divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e0e0e0;
}

.authentication-modal .divider span {
    position: relative;
    background: white;
    padding: 0 20px;
    color: #999;
    font-size: 13px;
    font-weight: 500;
}

/* Navigation vers inscription/connexion */
.authentication-modal .sign-up-next {
    text-align: center;
    font-size: 14px;
    color: #666;
    margin-top: 20px;
    margin-bottom: 0;
}

.authentication-modal .sign-up-next button {
    color: #ff6600;
    font-weight: 600;
    background: none;
    border: none;
    padding: 0;
    margin-left: 5px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.authentication-modal .sign-up-next button:hover {
    color: #e55a00;
    text-decoration: underline;
}

/* Layout en deux colonnes pour prénom/nom */
.authentication-modal .row {
    margin-left: -8px;
    margin-right: -8px;
}

.authentication-modal .row [class*="col-"] {
    padding-left: 8px;
    padding-right: 8px;
}

/* Animation du modal */
.modal.fade .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.modal.show .modal-dialog {
    transform: scale(1);
}

/* ============================================
   RESPONSIVE
   ============================================ */

@media (max-width: 576px) {
    .authentication-modal .authentication-box {
        padding: 30px 20px;
    }
    
    .authentication-modal .auth-title h4 {
        font-size: 22px;
    }
    
    .authentication-modal .auth-icon {
        width: 55px;
        height: 55px;
    }
    
    .authentication-modal .auth-icon i {
        font-size: 26px;
    }
    
    .authentication-modal .input-icon-group input {
        height: 45px;
        font-size: 13px;
    }
    
    .authentication-modal .btn-bg-theme {
        padding: 10px 16px;
        font-size: 14px;
    }
    
    .authentication-modal .forgot-box {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}

@media (max-width: 480px) {
    .authentication-modal .row {
        flex-direction: column;
    }
    
    .authentication-modal .row [class*="col-"] {
        width: 100%;
    }
}

/* ============================================
   STYLES POUR LE DROPDOWN USER
   ============================================ */

/* Conteneur du dropdown */
.dropdown-box {
    position: relative;
}

.dropdown-box > a {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.dropdown-box > a:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.dropdown-box > a i {
    font-size: 22px;
    color: #333;
}

/* Menu dropdown */
.user-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    min-width: 200px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    padding: 8px 0;
    margin-top: 10px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
}

.dropdown-box:hover .user-dropdown {
    opacity: 1;
    visibility: visible;
    margin-top: 5px;
}

/* Items du dropdown */
.user-dropdown li {
    list-style: none;
}

.user-dropdown li a,
.user-dropdown .btn {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 10px 18px;
    color: #444;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    background: none;
    border: none;
    cursor: pointer;
}

.user-dropdown li a i,
.user-dropdown .btn i {
    font-size: 18px;
    width: 20px;
    color: #888;
    transition: color 0.3s ease;
}

.user-dropdown li a:hover,
.user-dropdown .btn:hover {
    background-color: #f8f9fa;
    color: #ff6600;
}

.user-dropdown li a:hover i,
.user-dropdown .btn:hover i {
    color: #ff6600;
}

/* Séparateur dans le dropdown */
.user-dropdown li.divider {
    height: 1px;
    background: #e0e0e0;
    margin: 8px 0;
    padding: 0;
}

.user-dropdown li span {
    display: block;
    padding: 8px 18px;
    font-size: 12px;
    color: #999;
    border-bottom: 1px solid #e0e0e0;
    margin-bottom: 5px;
}

/* Animation du dropdown */
@keyframes dropdownFadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-box:hover .user-dropdown {
    animation: dropdownFadeIn 0.3s ease forwards;
}

/* ============================================
   SPINNER DE CHARGEMENT
   ============================================ */

.ri-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* ============================================
   MESSAGE D'ERREUR/SUCCÈS SWEETALERT
   ============================================ */

.swal2-popup {
    border-radius: 15px !important;
}

.swal2-title {
    font-size: 20px !important;
}

.swal2-confirm {
    background-color: #ff6600 !important;
    border-radius: 8px !important;
}
</style>







    <!-- Quick View Modal Start -->
    <div class="modal fade quick-view-modal theme-modal" id="quickViewModal">
        <div class="modal-dialog modal-custom-size modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="ri-close-line"></i>
                    </button>
                    <div id="quickViewContent">
                        <!-- Quick view content loaded via AJAX -->
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick View Modal End -->

    <!-- Tap To Top Button Start -->
    <div class="tap-top-button">
        <button class="btn">
            <i class="iconsax" data-icon-name="arrow-up"></i>
        </button>
    </div>
    <!-- Tap To Top Button End -->

    <!-- Bg Overlay Start -->
    <div id="overlay" class="bg-overlay"></div>
    <!-- Bg Overlay End -->






<!-- Cookie Box - Version Premium Box 1 -->
<div class="cookie-bar-box1" id="cookieBar" style="display: none;">
    <div class="cookie-bar-box">
        <h4>🍪 Respect de votre vie privée</h4>
        <p>
            Nous utilisons des cookies pour personnaliser votre expérience d'achat, 
            analyser notre trafic et vous proposer des offres adaptées.
            <a href="<?= base_url('privacy-policy'); ?>">Politique de confidentialité</a>
        </p>
        <div class="cookie-buttons">
            <button class="btn decline-btn" id="declineCookieBtn">Continuer sans accepter</button>
            <button class="btn allow-btn" id="acceptCookieBtn">Tout accepter</button>
        </div>
    </div>
</div>




   <!-- JavaScript Files -->
<script src="<?= base_url('assets/frontend/js/bootstrap/bootstrap.bundle.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/bootstrap/bootstrap-validation.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/lazyload.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/swiper.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/custom-swiper.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/iconsax.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/category-hide-show.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/timer.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/timer-2.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/qty.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/wishlist-notify.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/cookie.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/theme-setting.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/gsap/gsap.min.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/gsap/split-type.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/script.js'); ?>"></script>
<script src="<?= base_url('assets/frontend/js/custom.js'); ?>"></script>

<!-- ========== SCRIPTS SPÉCIFIQUES POUR LE DASHBOARD ========== -->
<script src="<?= base_url('assets/js/image-change.js'); ?>"></script>
<script src="<?= base_url('assets/js/user-dashboard.js'); ?>"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Base URL for AJAX calls
var base_url = '<?= base_url(); ?>';
var isLoggedIn = <?= $this->session->userdata('user_id') ? 'true' : 'false'; ?>;

// CSRF Security variables and functions
var csrfName = <?= $this->config->item('csrf_protection') ? "'" . $this->security->get_csrf_token_name() . "'" : "null"; ?>;
var csrfCookieName = <?= $this->config->item('csrf_protection') ? "'" . $this->config->item('csrf_cookie_name') . "'" : "null"; ?>;
function getCsrfToken() {
    if (!csrfName || !csrfCookieName) return null;
    let name = csrfCookieName + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '<?= $this->security->get_csrf_hash(); ?>';
}

// Newsletter subscription
document.getElementById('newsletterModal')?.querySelector('form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    var form = this;
    var email = form.querySelector('input[name="email"]').value;
    
    fetch(form.action, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(response => response.text())
    .then(() => {
        alert('Merci pour votre inscription à notre newsletter !');
        bootstrap.Modal.getInstance(document.getElementById('newsletterModal')).hide();
        form.reset();
    })
    .catch(error => {
        alert('Une erreur est survenue. Veuillez réessayer.');
    });
});

// ============================================
// FONCTIONS GLOBALES
// ============================================

// Ouvrir le modal d'authentification
function openAuthModal(action) {
    const modal = document.getElementById('authenticationModal');
    if (!modal) return;
    
    const loginBox = document.querySelector('.login-box');
    const signupBox = document.querySelector('.signup-box');
    const forgotBox = document.querySelector('.forgot-password-box');
    
    if (loginBox) loginBox.style.display = 'none';
    if (signupBox) signupBox.style.display = 'none';
    if (forgotBox) forgotBox.style.display = 'none';
    
    if (action === 'signup') {
        if (signupBox) signupBox.style.display = 'block';
    } else if (action === 'forgot') {
        if (forgotBox) forgotBox.style.display = 'block';
    } else {
        if (loginBox) loginBox.style.display = 'block';
    }
    
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

// Fonction de déconnexion avec SweetAlert
function logoutUser() {
    Swal.fire({
        title: 'Déconnexion',
        text: 'Êtes-vous sûr de vouloir vous déconnecter ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff6600',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, déconnecter',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("auth/logout"); ?>';
        }
    });
}

// ============================================
// INITIALISATION AU CHARGEMENT DE LA PAGE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du modal
    const loginBox = document.querySelector('.login-box');
    const signupBox = document.querySelector('.signup-box');
    const forgotBox = document.querySelector('.forgot-password-box');
    const modal = document.getElementById('authenticationModal');
    
    // Fonction pour changer de box
    function switchAuthBox(boxType) {
        if (loginBox) loginBox.style.display = 'none';
        if (signupBox) signupBox.style.display = 'none';
        if (forgotBox) forgotBox.style.display = 'none';
        
        if (boxType === 'signup' && signupBox) {
            signupBox.style.display = 'block';
        } else if (boxType === 'forgot' && forgotBox) {
            forgotBox.style.display = 'block';
        } else if (loginBox) {
            loginBox.style.display = 'block';
        }
    }
    
    // Navigation entre les boxes
    document.querySelectorAll('.next-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            switchAuthBox('signup');
        });
    });
    
    document.querySelectorAll('.next-button2').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            switchAuthBox('login');
        });
    });
    
    document.querySelectorAll('.forgot-password-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            switchAuthBox('forgot');
        });
    });
    
    document.querySelectorAll('.cancel-btn, .login-back-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            switchAuthBox('login');
        });
    });
    
    // Fermeture du modal
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            switchAuthBox('login');
            const forms = modal.querySelectorAll('form');
            forms.forEach(function(form) {
                form.reset();
            });
        });
    }

    // ============================================
    // FORMULAIRE DE CONNEXION
    // ============================================
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('login_email').value;
            const password = document.getElementById('login_password').value;
            const remember = document.getElementById('rememberMe').checked ? 1 : 0;
            
            if (!email || !password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Champs manquants',
                    text: 'Veuillez remplir tous les champs',
                    confirmButtonColor: '#ff6600'
                });
                return;
            }
            
            const submitBtn = document.getElementById('loginSubmitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Connexion...';
            submitBtn.disabled = true;
            
            try {
                let bodyParams = new URLSearchParams();
                bodyParams.append('email', email);
                bodyParams.append('password', password);
                bodyParams.append('remember', remember);
                if (csrfName) {
                    bodyParams.append(csrfName, getCsrfToken());
                }

                const response = await fetch('<?= base_url("auth/login"); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: bodyParams.toString()
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Connexion réussie !',
                        text: data.message,
                        confirmButtonColor: '#ff6600',
                        timer: 2000,
                        showConfirmButton: true
                    }).then(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur de connexion',
                        text: data.message,
                        confirmButtonColor: '#ff6600',
                        confirmButtonText: 'Réessayer'
                    });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    document.getElementById('login_password').value = '';
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue',
                    confirmButtonColor: '#ff6600'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }

    // ============================================
    // FORMULAIRE D'INSCRIPTION AVEC VÉRIFICATION OTP
    // ============================================
    const registerForm = document.getElementById('registerForm');
    
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const prenom = document.getElementById('reg_prenom').value;
            const nom = document.getElementById('reg_nom').value;
            const email = document.getElementById('reg_email').value;
            const telephone = document.getElementById('reg_telephone').value;
            const password = document.getElementById('reg_password').value;
            const confirm_password = document.getElementById('reg_confirm_password').value;
            const agree_terms = document.getElementById('agreeTerms').checked ? 1 : 0;
            
            if (!prenom || !nom || !email || !telephone || !password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Champs manquants',
                    text: 'Veuillez remplir tous les champs',
                    confirmButtonColor: '#ff6600'
                });
                return;
            }
            
            if (password !== confirm_password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Les mots de passe ne correspondent pas',
                    confirmButtonColor: '#ff6600'
                });
                return;
            }
            
            if (password.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Le mot de passe doit contenir au moins 6 caractères',
                    confirmButtonColor: '#ff6600'
                });
                return;
            }
            
            if (!agree_terms) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Vous devez accepter les conditions d\'utilisation',
                    confirmButtonColor: '#ff6600'
                });
                return;
            }
            
            const submitBtn = document.getElementById('registerSubmitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Inscription...';
            submitBtn.disabled = true;
            
            try {
                let bodyParams = new URLSearchParams();
                bodyParams.append('prenom', prenom);
                bodyParams.append('nom', nom);
                bodyParams.append('email', email);
                bodyParams.append('telephone', telephone);
                bodyParams.append('password', password);
                bodyParams.append('confirm_password', confirm_password);
                bodyParams.append('agree_terms', agree_terms);
                if (csrfName) {
                    bodyParams.append(csrfName, getCsrfToken());
                }

                const response = await fetch('<?= base_url("auth/register"); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: bodyParams.toString()
                });
                
                const data = await response.json();
                
                if (data.success) {
                    const bsModal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                    if (bsModal) bsModal.hide();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Compte créé avec succès !',
                        html: `
                            <p>Un code de vérification a été envoyé à :</p>
                            <p><strong>${data.email}</strong></p>
                            <p>Veuillez vérifier votre boîte de réception.</p>
                        `,
                        confirmButtonColor: '#0a66c2',
                        confirmButtonText: 'OK, saisir le code',
                        allowOutsideClick: false
                    }).then(() => {
                        window.location.href = '<?= base_url("auth/otp_verification_page?email="); ?>' + encodeURIComponent(data.email) + '&user_id=' + data.user_id;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Inscription échouée',
                        text: data.message,
                        confirmButtonColor: '#ff6600',
                        confirmButtonText: 'Réessayer'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue',
                    confirmButtonColor: '#ff6600'
                });
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }

    // ============================================
    // FORMULAIRE MOT DE PASSE OUBLIÉ (UN SEUL !)
    // ============================================
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('forgot_email').value;
            
            if (!email) {
                Swal.fire({
                    icon: 'error',
                    title: 'Champ manquant',
                    text: 'Veuillez saisir votre adresse email',
                    confirmButtonColor: '#ff6600'
                });
                return;
            }
            
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Email invalide',
                    text: 'Veuillez saisir une adresse email valide',
                    confirmButtonColor: '#ff6600'
                });
                return;
            }
            
            const submitBtn = document.getElementById('forgotSubmitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Envoi en cours...';
            submitBtn.disabled = true;
            
            try {
                let bodyParams = new URLSearchParams();
                bodyParams.append('email', email);
                if (csrfName) {
                    bodyParams.append(csrfName, getCsrfToken());
                }

                const response = await fetch('<?= base_url("auth/forgot_password") ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: bodyParams.toString()
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Code envoyé !',
                        html: `Un code de réinitialisation a été envoyé à :<br><strong>${email}</strong><br><br>Veuillez vérifier votre boîte de réception.`,
                        confirmButtonColor: '#ff6600',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = data.redirect_url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: data.message,
                        confirmButtonColor: '#ff6600'
                    });
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue. Veuillez réessayer.',
                    confirmButtonColor: '#ff6600'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }
});
</script>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
