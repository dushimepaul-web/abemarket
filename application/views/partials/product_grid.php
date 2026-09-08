<div class="row g-sm-4 g-3 row-cols-xxl-5 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section">
    <?php foreach($products as $product): ?>
    <div class="col">
        <div class="product-box-4-main">
            <div class="productMain product-box-4 pro-bg-white">
                <div class="product-image">
                    <a href="<?= base_url('product/' . $product['slug_produit']) ?>">
                        <?php 
                        // CORRECTION ICI : Utiliser base_url() pour l'image
                        $image_url = !empty($product['image_url']) ? base_url($product['image_url']) : base_url('assets/images/product/placeholder.png');
                        ?>
                        <img src="<?= $image_url ?>" class="img-fluid productImage" alt="<?= htmlspecialchars($product['nom_produit']) ?>">
                    </a>
                    <div class="quick-view-button-box">
                        <button class="btn view-btn quick-view" data-product="<?= $product['id_produit'] ?>">Quick View</button>
                    </div>
                </div>
                <div class="product-content">
                    <h5 class="sub-name productName"><?= htmlspecialchars($product['marque'] ?? 'Product') ?></h5>
                    <a href="<?= base_url('product/' . $product['slug_produit']) ?>" class="name">
                        <h5><?= htmlspecialchars($product['nom_produit']) ?></h5>
                    </a>
                    <ul class="rating">
                        <?php $rating = round($product['note_moyenne'] ?? 0); ?>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                        <li><i class="ri-star-fill <?= ($i <= $rating) ? 'fill' : '' ?>"></i></li>
                        <?php endfor; ?>
                    </ul>
                    <p class="product-details"><?= htmlspecialchars(substr($product['description'] ?? '', 0, 150)) ?>...</p>
                    <h5 class="price">
                        <?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' ') ?> BIF
                        <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                        <del><?= number_format($product['prix_base'], 0, ',', ' ') ?> BIF</del>
                        <?php endif; ?>
                    </h5>
                    <div class="option-box">
                        <button class="btn add-cart-btn add-to-cart-btn" data-product-id="<?= $product['id_produit'] ?>" style="width:100%;padding:10px 0;background:#ff6b35;color:#fff;border:none;border-radius:8px;font-weight:600;font-size:14px;transition:all 0.3s;">
                            <i class="ri-shopping-cart-2-line"></i> Ajouter au panier
                        </button>
                        <ul class="option-list" style="display:flex;gap:10px;margin-top:8px;">
                            <li>
                                <a href="#" class="wishlistProduct add-to-wishlist" data-product="<?= $product['id_produit'] ?>">
                                    <i class="ri-heart-3-line"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>