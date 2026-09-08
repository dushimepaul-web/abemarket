<!-- search_view.php -->
<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Résultats de recherche</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Recherche</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Search Section Start -->
<section class="search-section section-t-space">
    <div class="custom-container">
        <div class="row">
            <div class="col-xl-5 col-lg-7 col-md-10 m-auto">
                <form action="<?= base_url('home/search'); ?>" method="get" class="search-box theme-form input-group">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="ri-search-line"></i>
                    </span>
                    <input type="text" class="form-control" name="q" placeholder="Rechercher un produit..." value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </form>
                <ul class="search-suggestion-list">
                    <li>Catégories populaires :</li>
                    <li><a href="<?= base_url('shop?category=8'); ?>">Téléphones</a></li>
                    <li><a href="<?= base_url('shop?category=1'); ?>">Électronique</a></li>
                    <li><a href="<?= base_url('shop?category=14'); ?>">Épicerie</a></li>
                    <li><a href="<?= base_url('shop?category=3'); ?>">Maison</a></li>
                    <li><a href="<?= base_url('shop?category=11'); ?>">Mode Homme</a></li>
                    <li><a href="<?= base_url('shop?category=12'); ?>">Mode Femme</a></li>
                </ul>
            </div>
            <div class="col-12">
                <div class="title d-block text-center">
                    <h3>Besoin d'inspiration ?</h3>
                </div>

                <?php if (!empty($keyword)): ?>
                    <div class="search-result-info text-center mb-4">
                        <p><?= count($products); ?> résultat(s) trouvé(s) pour "<strong><?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?></strong>"</p>
                    </div>
                <?php endif; ?>

                <?php if (empty($products)): ?>
                    <div class="text-center py-5">
                        <i class="ri-search-line" style="font-size: 64px; color: #ccc;"></i>
                        <h4 class="mt-3">Aucun produit trouvé</h4>
                        <p>Essayez avec d'autres mots-clés ou parcourez nos catégories.</p>
                        <a href="<?= base_url('shop'); ?>" class="btn theme-bg-color text-white mt-2">Voir tous les produits</a>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-xl-5 row-cols-lg-4 row-cols-sm-3 row-cols-2 g-sm-4 g-3">
                        <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="product-box-4-main">
                                <div class="productMain product-box-4 pro-bg-white">
                                    <div class="product-image">
                                        <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                            <img src="<?= base_url(!empty($product['image_url']) ? $product['image_url'] : 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid productImage" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </a>
                                        <div class="quick-view-button-box">
                                            <button class="btn view-btn quick-view-btn" data-product-id="<?= $product['id_produit']; ?>" data-bs-toggle="modal" data-bs-target="#quickViewModal">Aperçu rapide</button>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h4 class="sub-name productName"><?= htmlspecialchars($product['marque'] ?? 'Produit', ENT_QUOTES, 'UTF-8'); ?></h4>
                                        <a href="<?= base_url('product/' . $product['slug_produit']); ?>" class="name">
                                            <h5><?= htmlspecialchars(substr($product['nom_produit'], 0, 45), ENT_QUOTES, 'UTF-8'); ?><?= strlen($product['nom_produit']) > 45 ? '...' : ''; ?></h5>
                                        </a>
                                        <ul class="rating">
                                            <?php 
                                            $rating = round($product['note_moyenne'] ?? 0);
                                            for ($i = 1; $i <= 5; $i++): 
                                            ?>
                                                <li><i class="ri-star-fill <?= $i <= $rating ? 'fill' : ''; ?>"></i></li>
                                            <?php endfor; ?>
                                            <li><span>(<?= $product['nombre_avis'] ?? 0; ?>)</span></li>
                                        </ul>
                                        <h5 class="price">
                                            <?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF
                                            <?php if (!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                            <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                            <?php endif; ?>
                                        </h5>
                                        <div class="option-box">
                                            <button class="btn select-btn add-to-cart-btn" data-product-id="<?= $product['id_produit']; ?>">Ajouter au panier</button>
                                            <ul class="option-list">
                                                <li>
                                                    <a href="#" class="add-to-wishlist" data-product-id="<?= $product['id_produit']; ?>">
                                                        <i class="ri-heart-3-line"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url('compare/' . $product['id_produit']); ?>">
                                                        <i class="ri-repeat-2-line"></i>
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

                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <nav class="custom-pagination mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= base_url('home/search?q=' . urlencode($keyword) . '&page=' . ($currentPage - 1)); ?>">
                                    <i class="ri-arrow-left-s-line"></i>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?= base_url('home/search?q=' . urlencode($keyword) . '&page=' . $i); ?>">
                                    <span><?= $i; ?></span>
                                </a>
                            </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= base_url('home/search?q=' . urlencode($keyword) . '&page=' . ($currentPage + 1)); ?>">
                                    <i class="ri-arrow-right-s-line"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- Search Section End -->
<script>
$(document).on('click', '.add-to-cart-btn', function(e) {
    e.preventDefault();
    let productId = $(this).data('product-id');
    $.ajax({
        url: '<?= base_url("home/addToCart") ?>',
        type: 'POST',
        data: { product_id: productId, quantity: 1 },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#cart-count-header').text(response.cart_count);
                $('.cart-count').text(response.cart_count);
                Swal.fire({ icon: 'success', title: 'Ajouté !', text: 'Produit ajouté au panier', timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Erreur', text: response.message || 'Erreur lors de l\'ajout' });
            }
        }
    });
});
</script>