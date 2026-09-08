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
        <p class="mt-3 text-muted">Découvrez nos produits et ajoutez-les à votre panier.</p>
        <a href="<?= base_url('shop'); ?>" class="btn btn-primary mt-2">Commencer mes achats</a>
    </li>
    <?php endif; ?>
</ul>

<div class="total-price-box" id="cartTotalBox" style="<?= empty($cart_items) ? 'display: none;' : ''; ?>">
    <h4 class="sub-total">Sous-total <span id="cartSubtotal"><?= number_format($cart_subtotal ?? 0, 0, ',', ' '); ?> BIF</span></h4>
    <p class="tax-text">Taxes incluses <span>frais de livraison</span> calculés à la validation.</p>
    <div class="cart-btn-group">
        <a href="<?= base_url('checkout'); ?>" class="btn check-out-button <?= empty($cart_items) ? 'disabled' : ''; ?>">Valider</a>
        <a href="<?= base_url('cart'); ?>" class="btn cart-button">Voir le panier</a>
    </div>
</div>
