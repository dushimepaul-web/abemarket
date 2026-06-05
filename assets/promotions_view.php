<!-- promotions_view.php -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Promotions</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('/'); ?>">Accueil</a></li>
                    <li class="breadcrumb-item active">Promotions</li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<section class="section-t-space">
    <div class="custom-container">
        <div class="title text-center">
            <h2>Offres spéciales</h2>
            <p>Profitez de nos réductions exceptionnelles</p>
        </div>
        
        <?php if (empty($promotions)): ?>
            <div class="text-center py-5">
                <i class="ri-flashlight-line" style="font-size: 64px; color: #ccc;"></i>
                <h4 class="mt-3">Aucune promotion en cours</h4>
                <p>Revenez bientôt pour découvrir nos offres</p>
            </div>
        <?php else: ?>
            <div class="row g-sm-4 g-3">
                <?php foreach ($promotions as $product): ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                    <div class="product-box-4-main">
                        <div class="productMain product-box-4 pro-bg-white">
                            <div class="product-image">
                                <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                    <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="">
                                </a>
                                <div class="sale-tag">-<?= round((($product['prix_base'] - $product['prix_promo']) / $product['prix_base']) * 100); ?>%</div>
                            </div>
                            <div class="product-content">
                                <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="name">
                                    <h5><?= htmlspecialchars(substr($product['nom_produit'], 0, 45), ENT_QUOTES, 'UTF-8'); ?></h5>
                                </a>
                                <h5 class="price">
                                    <?= number_format($product['prix_promo'], 0, ',', ' '); ?> BIF
                                    <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                </h5>
                                <button class="btn add-to-cart-btn" data-product-id="<?= $product['id_produit']; ?>">
                                    Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav class="custom-pagination mt-5">
                <ul class="pagination justify-content-center">
                    <!-- Liens de pagination -->
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>