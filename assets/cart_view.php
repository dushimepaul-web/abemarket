<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Mon panier</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Panier</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Cart Section Start -->
<section class="cart-section section-t-space">
    <div class="custom-container">
        <?php if (empty($cartItems)): ?>
            <!-- Message si le panier est vide -->
            <div class="text-center py-5">
                <i class="ri-shopping-cart-line" style="font-size: 64px; color: #ccc;"></i>
                <h4 class="mt-3">Votre panier est vide</h4>
                <p>Ajoutez des produits à votre panier pour passer commande.</p>
                <a href="<?= base_url('shop'); ?>" class="btn theme-bg-color text-white mt-2">Continuer mes achats</a>
            </div>
        <?php else: ?>
            <div class="row g-sm-4 g-3">
                <div class="col-xl-8">
                    <div class="left-sidebar-box">
                        <!-- Liste des produits du panier -->
                        <ul class="cart-list" id="cart-list">
                            <?php foreach ($cartItems as $item): ?>
                            <li class="cart-item" data-cart-id="<?= $item['id_panier']; ?>" data-product-price="<?= $item['prix_effectif']; ?>">
                                <div class="cart-image-box">
                                    <a href="<?= base_url('product/' . $item['slug_produit']); ?>">
                                        <img src="<?= base_url(!empty($item['image_url']) ? $item['image_url'] : 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($item['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </a>
                                </div>
                                <div class="cart-contain">
                                    <a href="<?= base_url('product/' . $item['slug_produit']); ?>" class="name">
                                        <h4><?= htmlspecialchars($item['nom_produit'], ENT_QUOTES, 'UTF-8'); ?></h4>
                                    </a>
                                    <?php if (!empty($item['attributs_variante'])): ?>
                                    <p class="product-variant">
                                        <?php 
                                        $attrs = json_decode($item['attributs_variante'], true);
                                        if (!empty($attrs)) {
                                            foreach ($attrs as $key => $val) {
                                                echo '<span>' . ucfirst($key) . ': ' . htmlspecialchars($val, ENT_QUOTES, 'UTF-8') . '</span> ';
                                            }
                                        }
                                        ?>
                                    </p>
                                    <?php endif; ?>
                                    <div class="price-quantity">
                                        <h5 class="price"><?= number_format($item['prix_effectif'], 0, ',', ' '); ?> BIF</h5>
                                        <div class="quantity-box qty-container">
                                            <button class="btn qty-btn qty-btn-minus update-cart-btn" data-cart-id="<?= $item['id_panier']; ?>" data-change="-1">
                                                <i class="ri-subtract-line"></i>
                                            </button>
                                            <input type="number" name="qty" class="quantity form-control input-qty cart-qty" data-cart-id="<?= $item['id_panier']; ?>" data-price="<?= $item['prix_effectif']; ?>" value="<?= $item['quantite']; ?>" min="1" max="99">
                                            <button class="btn qty-btn qty-btn-plus update-cart-btn" data-cart-id="<?= $item['id_panier']; ?>" data-change="1">
                                                <i class="ri-add-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="close-button">
                                    <button class="btn remove-cart-item" data-cart-id="<?= $item['id_panier']; ?>">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="right-sidebar-box">
                        <div class="cart-summary-box">
                            <h3>Récapitulatif</h3>
                            
                            <!-- Code promo -->
                            <div class="coupon-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="couponCode" placeholder="Code promo" autocomplete="off">
                                    <button class="btn apply-coupon-btn" id="applyCoupon">Appliquer</button>
                                </div>
                                <div id="couponMessage" class="coupon-message mt-2"></div>
                            </div>

                            <!-- Détails des prix -->
                            <ul class="summary-list" id="summary-list">
                                <li>
                                    <span>Sous-total</span>
                                    <span id="subtotal"><?= number_format($subtotal, 0, ',', ' '); ?> BIF</span>
                                </li>
                                <li>
                                    <span>Livraison</span>
                                    <span id="frais-livraison"><?= number_format($frais_livraison, 0, ',', ' '); ?> BIF</span>
                                </li>
                                <li id="discount-row" style="display: none;" class="discount">
                                    <span>Réduction</span>
                                    <span id="discount-amount">- 0 BIF</span>
                                </li>
                                <li class="total">
                                    <span>Total</span>
                                    <span id="total"><?= number_format($total, 0, ',', ' '); ?> BIF</span>
                                </li>
                            </ul>

                            <!-- Boutons d'action -->
                            <div class="cart-btn-group">
                                <a href="<?= base_url('checkout'); ?>" class="btn check-out-button">Valider la commande</a>
                                <a href="<?= base_url('shop'); ?>" class="btn continue-shopping-btn">Continuer mes achats</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<!-- Cart Section End -->






<script>
$(document).ready(function() {
    let currentCoupon = null;
    let currentDiscount = 0;
    const fraisLivraison = <?= $frais_livraison ?? 2000; ?>;
    
    // Mise à jour de la quantité (boutons + et -)
    $('.update-cart-btn').click(function() {
        var cartId = $(this).data('cart-id');
        var change = $(this).data('change');
        var $qtyInput = $('.cart-qty[data-cart-id="' + cartId + '"]');
        var currentQty = parseInt($qtyInput.val());
        var newQty = currentQty + change;
        
        if (newQty >= 1 && newQty <= 99) {
            updateCartQuantity(cartId, newQty, $qtyInput);
        }
    });
    
    // Modification manuelle de la quantité
    $('.cart-qty').on('change', function() {
        var cartId = $(this).data('cart-id');
        var newQty = parseInt($(this).val());
        
        if (newQty >= 1 && newQty <= 99 && !isNaN(newQty)) {
            updateCartQuantity(cartId, newQty, $(this));
        } else if (newQty < 1) {
            $(this).val(1);
            updateCartQuantity(cartId, 1, $(this));
        } else if (newQty > 99) {
            $(this).val(99);
            updateCartQuantity(cartId, 99, $(this));
        }
    });
    
    // Suppression d'un article
    $('.remove-cart-item').click(function() {
        var cartId = $(this).data('cart-id');
        removeFromCart(cartId);
    });
    
    // Application du coupon
    $('#applyCoupon').click(function() {
        var couponCode = $('#couponCode').val().trim();
        if (couponCode) {
            applyCoupon(couponCode);
        } else {
            showMessage('Veuillez entrer un code promo', 'error');
        }
    });
    
    // Fonction de mise à jour de la quantité (sans loading visible)
    function updateCartQuantity(cartId, quantity, $input) {
        // Désactiver temporairement l'input mais sans overlay
        $input.prop('readonly', true);
        
        $.ajax({
            url: '<?= base_url("home/updateCart"); ?>',
            type: 'POST',
            data: {
                cart_id: cartId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Mettre à jour l'affichage localement
                    $input.val(quantity);
                    updateLocalCartTotals();
                    // Mettre à jour le compteur dans l'en-tête
                    if (response.cart_count !== undefined) {
                        $('.cart-count, .cart-badge, .label span').first().text(response.cart_count);
                    }
                } else {
                    showMessage(response.message || 'Erreur lors de la mise à jour', 'error');
                    // Recharger la page en cas d'erreur
                    setTimeout(function() { location.reload(); }, 1500);
                }
            },
            error: function() {
                showMessage('Erreur lors de la mise à jour', 'error');
                setTimeout(function() { location.reload(); }, 1500);
            },
            complete: function() {
                $input.prop('readonly', false);
            }
        });
    }
    
    // Mise à jour locale des totaux (sans rechargement)
    function updateLocalCartTotals() {
        let newSubtotal = 0;
        
        $('.cart-item').each(function() {
            let $item = $(this);
            let quantity = parseInt($item.find('.cart-qty').val());
            let price = parseFloat($item.find('.cart-qty').data('price'));
            let itemTotal = quantity * price;
            newSubtotal += itemTotal;
        });
        
        // Mettre à jour l'affichage
        let newTotal = newSubtotal + fraisLivraison - currentDiscount;
        
        $('#subtotal').text(formatNumber(newSubtotal) + ' BIF');
        $('#total').text(formatNumber(newTotal) + ' BIF');
        
        // Mettre à jour le sous-total dans le récapitulatif
        updateSummaryTotals(newSubtotal, newTotal);
    }
    
    // Mettre à jour les totaux dans le résumé
    function updateSummaryTotals(subtotal, total) {
        // Animation légère sur le total
        $('#total').css('transition', 'all 0.3s ease');
        $('#total').css('color', '#f68b1e');
        setTimeout(function() {
            $('#total').css('color', '');
        }, 300);
        
        // Réappliquer le coupon si nécessaire
        if (currentCoupon && currentDiscount > 0) {
            recalculateWithCoupon(subtotal);
        }
    }
    
    // Recalculer avec coupon
    function recalculateWithCoupon(subtotal) {
        $.ajax({
            url: '<?= base_url("home/applyCoupon"); ?>',
            type: 'POST',
            data: {
                coupon: currentCoupon,
                subtotal: subtotal
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    currentDiscount = response.discount;
                    let newTotal = subtotal + fraisLivraison - currentDiscount;
                    if ($('#discount-row').length) {
                        $('#discount-row').show();
                        $('#discount-amount').text('- ' + formatNumber(currentDiscount) + ' BIF');
                    }
                    $('#total').text(formatNumber(newTotal) + ' BIF');
                }
            }
        });
    }
    
    // Fonction de suppression d'article
    function removeFromCart(cartId) {
        if (confirm('Voulez-vous vraiment supprimer cet article ?')) {
            var $cartItem = $('.cart-item[data-cart-id="' + cartId + '"]');
            
            // Animation de disparition
            $cartItem.css('transition', 'all 0.3s ease');
            $cartItem.css('opacity', '0');
            $cartItem.css('transform', 'translateX(-20px)');
            
            $.ajax({
                url: '<?= base_url("home/removeFromCart"); ?>',
                type: 'POST',
                data: {
                    cart_id: cartId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Supprimer l'élément du DOM après animation
                        setTimeout(function() {
                            $cartItem.remove();
                            
                            // Mettre à jour le compteur du panier dans l'en-tête
                            if (response.cart_count !== undefined) {
                                $('.cart-count, .cart-badge, .label span').first().text(response.cart_count);
                            }
                            
                            // Vérifier si le panier est vide
                            if ($('.cart-item').length === 0) {
                                location.reload();
                            } else {
                                updateLocalCartTotals();
                            }
                        }, 300);
                        
                        showMessage('Article supprimé du panier', 'success');
                    } else {
                        showMessage(response.message || 'Erreur lors de la suppression', 'error');
                        // Remettre l'article visible
                        $cartItem.css('opacity', '1');
                        $cartItem.css('transform', 'translateX(0)');
                    }
                },
                error: function() {
                    showMessage('Erreur lors de la suppression', 'error');
                    $cartItem.css('opacity', '1');
                    $cartItem.css('transform', 'translateX(0)');
                }
            });
        }
    }
    
    // Fonction d'application de coupon
    function applyCoupon(couponCode) {
        $.ajax({
            url: '<?= base_url("home/applyCoupon"); ?>',
            type: 'POST',
            data: {
                coupon: couponCode
            },
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
                    $('#couponMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    $('#couponCode').prop('disabled', true);
                    
                    // Animation sur le total
                    $('#total').css('transition', 'all 0.3s ease');
                    $('#total').css('color', '#28a745');
                    setTimeout(function() {
                        $('#total').css('color', '');
                    }, 500);
                } else {
                    $('#couponMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                    currentDiscount = 0;
                    currentCoupon = null;
                }
            },
            error: function() {
                $('#couponMessage').html('<div class="alert alert-danger">Erreur lors de l\'application du code promo</div>');
            },
            complete: function() {
                $('#applyCoupon').prop('disabled', false).text('Appliquer');
                setTimeout(function() {
                    $('#couponMessage').fadeOut(function() {
                        $(this).html('').show();
                    });
                }, 3000);
            }
        });
    }
    
    // Formater les nombres
    function formatNumber(number) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(number));
    }
    
    // Afficher les messages
    function showMessage(message, type) {
        var alertClass = (type === 'error') ? 'alert-danger' : 'alert-success';
        var $alert = $('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
            '<i class="' + (type === 'error' ? 'ri-error-warning-line' : 'ri-checkbox-circle-line') + ' me-2"></i>' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '</div>');
        
        $('.cart-summary-box').prepend($alert);
        
        setTimeout(function() {
            $alert.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>

<style>
/* Styles additionnels pour le panier */
.cart-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.cart-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    position: relative;
    transition: all 0.3s ease;
}

.cart-image-box {
    width: 100px;
    flex-shrink: 0;
}

.cart-image-box img {
    width: 100%;
    border-radius: 8px;
}

.cart-contain {
    flex: 1;
}

.cart-contain .name h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #282828;
}

.product-variant {
    font-size: 12px;
    color: #666;
    margin-bottom: 10px;
}

.price-quantity {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}

.price-quantity .price {
    color: #f68b1e;
    font-weight: 700;
    margin: 0;
}

.quantity-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-box .qty-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #e5e5e5;
    background: #fff;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.quantity-box .qty-btn:hover {
    background: #f68b1e;
    border-color: #f68b1e;
    color: #fff;
}

.quantity-box .input-qty {
    width: 60px;
    text-align: center;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 6px;
    background: white;
}

.quantity-box .input-qty:read-only {
    background: #f9f9f9;
}

.close-button .btn {
    background: none;
    border: none;
    font-size: 20px;
    color: #999;
    cursor: pointer;
    transition: color 0.2s;
}

.close-button .btn:hover {
    color: #f68b1e;
}

.right-sidebar-box {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    position: sticky;
    top: 20px;
}

.cart-summary-box h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f68b1e;
}

.coupon-box {
    margin-bottom: 20px;
}

.coupon-box .input-group {
    display: flex;
    gap: 10px;
}

.coupon-box .form-control {
    flex: 1;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 10px;
}

.apply-coupon-btn {
    background: #f68b1e;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.apply-coupon-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.summary-list {
    list-style: none;
    padding: 0;
    margin: 0 0 20px 0;
}

.summary-list li {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-list li.total {
    border-top: 2px solid #e5e5e5;
    border-bottom: none;
    padding-top: 15px;
    margin-top: 5px;
    font-weight: 700;
    font-size: 18px;
}

.summary-list li.discount {
    color: #28a745;
}

.cart-btn-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.check-out-button {
    background: #f68b1e;
    color: #fff;
    text-align: center;
    padding: 12px;
    border-radius: 4px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.check-out-button:hover {
    background: #e07d18;
    color: #fff;
}

.continue-shopping-btn {
    background: #fff;
    color: #282828;
    text-align: center;
    padding: 12px;
    border-radius: 4px;
    border: 1px solid #e5e5e5;
    text-decoration: none;
    transition: all 0.2s;
}

.continue-shopping-btn:hover {
    background: #f5f5f5;
}

.alert {
    animation: slideDown 0.3s ease;
    margin-bottom: 15px;
}

@keyframes slideDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .cart-item {
        flex-wrap: wrap;
    }
    
    .cart-image-box {
        width: 80px;
    }
    
    .price-quantity {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>