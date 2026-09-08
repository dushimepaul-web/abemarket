<style>
.wishlist-section .product-box-4-main {
    position: relative;
    transition: transform 0.3s ease;
}

.wishlist-section .product-box-4-main:hover {
    transform: translateY(-5px);
}

.remove-from-wishlist {
    cursor: pointer;
}

.remove-from-wishlist:hover {
    color: #f68b1e;
}

.select-option-box .close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #fff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.select-option-box .close-btn:hover {
    background: #f68b1e;
    color: #fff;
}
</style>

<!-- wishlist_view.php -->
<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Ma liste de souhaits</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Wishlist</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Wishlist Section Start -->
<section class="section-t-space wishlist-section">
    <div class="custom-container">
        <?php if (empty($wishlist)): ?>
            <!-- Message si la wishlist est vide -->
            <div class="text-center py-5">
                <i class="ri-heart-3-line" style="font-size: 64px; color: #ccc;"></i>
                <h4 class="mt-3">Votre liste de souhaits est vide</h4>
                <p>Explorez nos produits et ajoutez vos articles préférés à votre wishlist.</p>
                <a href="<?= base_url('shop'); ?>" class="btn theme-bg-color text-white mt-2">Découvrir nos produits</a>
            </div>
        <?php else: ?>
            <!-- Grille des produits de la wishlist -->
            <div class="row g-sm-4 g-3 row-cols-xxl-5 row-cols-xl-4 row-cols-md-3 row-cols-2">
                <?php foreach ($wishlist as $item): ?>
                <div class="col">
                    <div class="product-box-4-main">
                        <!-- Options de sélection (couleurs/taille - optionnel) -->
                        <div class="select-option-box">
                            <div class="select-box">
                                <div>
                                    <?php if (!empty($item['couleurs'])): ?>
                                    <div class="color-box">
                                        <h3 class="h5">Couleurs</h3>
                                        <ul class="color-list">
                                            <?php foreach ($item['couleurs'] as $color): ?>
                                            <li>
                                                <a href="#" style="background-color: <?= $color; ?>;"></a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item['tailles'])): ?>
                                    <div class="size-box">
                                        <h3 class="h5">Tailles</h3>
                                        <ul class="size-list">
                                            <?php foreach ($item['tailles'] as $size): ?>
                                            <li><a href="#"><?= $size; ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>

                                    <button class="btn add-cart-btn add-to-cart-btn" data-product-id="<?= $item['id_produit']; ?>">Ajouter au panier</button>
                                    <button class="close-btn btn remove-from-wishlist" data-product-id="<?= $item['id_produit']; ?>">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Carte produit -->
                        <div class="productMain product-box-4 pro-bg-white">
                            <div class="product-image">
                                <a href="<?= base_url('product/' . $item['slug_produit']); ?>">
                                    <img src="<?= base_url(!empty($item['image_url']) ? $item['image_url'] : 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid productImage" alt="<?= htmlspecialchars($item['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                </a>
                                <div class="quick-view-button-box">
                                    <button class="btn view-btn quick-view-btn" data-product-id="<?= $item['id_produit']; ?>" data-bs-toggle="modal" data-bs-target="#quickViewModal">Aperçu rapide</button>
                                </div>
                            </div>
                            <div class="product-content">
                                <h4 class="sub-name productName"><?= htmlspecialchars($item['marque'] ?? 'Produit', ENT_QUOTES, 'UTF-8'); ?></h4>
                                <a href="<?= base_url('product/' . $item['slug_produit']); ?>" class="name">
                                    <h5><?= htmlspecialchars(substr($item['nom_produit'], 0, 45), ENT_QUOTES, 'UTF-8'); ?><?= strlen($item['nom_produit']) > 45 ? '...' : ''; ?></h5>
                                </a>
                                <ul class="rating">
                                    <?php 
                                    $rating = round($item['note_moyenne'] ?? 0);
                                    for ($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <li><i class="ri-star-fill <?= $i <= $rating ? 'fill' : ''; ?>"></i></li>
                                    <?php endfor; ?>
                                    <li><span>(<?= $item['nombre_avis'] ?? 0; ?>)</span></li>
                                </ul>
                                <h5 class="price">
                                    <?= number_format($item['prix_promo'] ?? $item['prix_base'], 0, ',', ' '); ?> BIF
                                    <?php if (!empty($item['prix_promo']) && $item['prix_promo'] < $item['prix_base']): ?>
                                    <del><?= number_format($item['prix_base'], 0, ',', ' '); ?> BIF</del>
                                    <?php endif; ?>
                                </h5>
                                <div class="option-box">
                                    <button class="btn select-btn add-to-cart-btn" data-product-id="<?= $item['id_produit']; ?>">Choisir les options</button>
                                    <ul class="option-list">
                                        <li>
                                            <a href="#" class="remove-from-wishlist" data-product-id="<?= $item['id_produit']; ?>">
                                                <i class="ri-heart-3-line"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= base_url('compare/' . $item['id_produit']); ?>">
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

            <!-- Pagination (si nécessaire) -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
            <nav class="custom-pagination mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= base_url('home/wishlist?page=' . ($currentPage - 1)); ?>">
                            <i class="ri-arrow-left-s-line"></i>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $currentPage) ? 'active' : ''; ?>">
                        <a class="page-link" href="<?= base_url('home/wishlist?page=' . $i); ?>">
                            <span><?= $i; ?></span>
                        </a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?= base_url('home/wishlist?page=' . ($currentPage + 1)); ?>">
                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<!-- Wishlist Section End -->
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