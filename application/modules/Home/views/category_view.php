<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2><?= htmlspecialchars($category['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('shop'); ?>">Boutique</a>
                    </li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($category['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?></li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Shop Section Start -->
<section class="shop-section section-t-space">
    <div class="custom-container">
        <div class="row g-lg-4 g-3">
            <!-- Sidebar Filtres -->
            <div class="col-xxl-3 col-xl-4">
                <div class="left-sidebar-box">
                    <!-- Catégories -->
                    <div class="category-sidebar">
                        <div class="sidebar-title">
                            <h4>Catégories</h4>
                        </div>
                        <ul class="category-list" id="categoryFilter">
                            <li>
                                <a href="<?= base_url('shop'); ?>" class="<?= empty($currentCategory) ? 'active' : ''; ?>">
                                    Toutes les catégories
                                </a>
                            </li>
                            <?php if(!empty($categories)): ?>
                                <?php foreach($categories as $cat): ?>
                                <li>
                                    <a href="<?= base_url('shop?category=' . $cat['id_categorie'] . '&sort=' . $currentSort); ?>" 
                                       class="<?= ($currentCategory == $cat['id_categorie']) ? 'active' : ''; ?>">
                                        <?= htmlspecialchars($cat['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?>
                                        <span>(<?= $this->Home_model->countProductsByCategoryOnly($cat['id_categorie']); ?>)</span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Sous-catégories -->
                    <?php if(!empty($subcategories)): ?>
                    <div class="category-sidebar mt-4">
                        <div class="sidebar-title">
                            <h4>Sous-catégories</h4>
                        </div>
                        <ul class="category-list">
                            <?php foreach($subcategories as $sub): ?>
                            <li>
                                <a href="<?= base_url('category/' . $sub['slug_categorie']); ?>">
                                    <?= htmlspecialchars($sub['nom_categorie'], ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- Filtre Prix -->
                    <div class="price-sidebar mt-4">
                        <div class="sidebar-title">
                            <h4>Prix</h4>
                        </div>
                        <div class="price-range">
                            <div class="range-slider">
                                <input type="range" class="form-range" id="priceMin" min="<?= $filters['min_price'] ?? 0; ?>" max="<?= $filters['max_price'] ?? 1000000; ?>" value="<?= $min_price ?? $filters['min_price'] ?? 0; ?>">
                                <input type="range" class="form-range" id="priceMax" min="<?= $filters['min_price'] ?? 0; ?>" max="<?= $filters['max_price'] ?? 1000000; ?>" value="<?= $max_price ?? $filters['max_price'] ?? 1000000; ?>">
                            </div>
                            <div class="price-values d-flex justify-content-between mt-2">
                                <span id="priceMinValue"><?= number_format($min_price ?? $filters['min_price'] ?? 0, 0, ',', ' '); ?> BIF</span>
                                <span>-</span>
                                <span id="priceMaxValue"><?= number_format($max_price ?? $filters['max_price'] ?? 1000000, 0, ',', ' '); ?> BIF</span>
                            </div>
                            <button class="btn btn-sm theme-bg-color text-white mt-3 w-100" id="applyPriceFilter">Appliquer</button>
                        </div>
                    </div>

                    <!-- Marques -->
                    <?php if(!empty($filters['brands'])): ?>
                    <div class="brand-sidebar mt-4">
                        <div class="sidebar-title">
                            <h4>Marques</h4>
                        </div>
                        <ul class="brand-list">
                            <?php foreach($filters['brands'] as $brand): ?>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input brand-filter" type="checkbox" value="<?= htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?>" id="brand_<?= md5($brand); ?>">
                                    <label class="form-check-label" for="brand_<?= md5($brand); ?>">
                                        <?= htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?>
                                    </label>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="btn btn-sm theme-bg-color text-white mt-3 w-100" id="applyBrandFilter">Filtrer</button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Produits -->
            <div class="col-xxl-9 col-xl-8">
                <!-- Barre de tri -->
                <div class="shop-top-bar">
                    <div class="result-count">
                        <p><span><?= number_format($totalProducts ?? 0); ?></span> produits trouvés</p>
                    </div>
                    <div class="product-sort">
                        <label>Trier par :</label>
                        <select class="form-select" id="sortProducts">
                            <option value="newest" <?= $currentSort == 'newest' ? 'selected' : ''; ?>>Plus récents</option>
                            <option value="price_asc" <?= $currentSort == 'price_asc' ? 'selected' : ''; ?>>Prix croissant</option>
                            <option value="price_desc" <?= $currentSort == 'price_desc' ? 'selected' : ''; ?>>Prix décroissant</option>
                            <option value="rating" <?= $currentSort == 'rating' ? 'selected' : ''; ?>>Mieux notés</option>
                            <option value="bestselling" <?= $currentSort == 'bestselling' ? 'selected' : ''; ?>>Meilleures ventes</option>
                        </select>
                    </div>
                    <div class="grid-list">
                        <button class="btn grid-btn active" data-view="grid"><i class="ri-grid-line"></i></button>
                        <button class="btn list-btn" data-view="list"><i class="ri-list-unordered"></i></button>
                    </div>
                </div>

                <!-- Grille produits -->
                <div class="row g-4" id="productsContainer">
                    <?php if(!empty($products)): ?>
                        <?php foreach($products as $product): ?>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 product-item">
                            <div class="product-box">
                                <div class="product-image">
                                    <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                        <img src="<?= base_url($product['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </a>
                                    <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                    <span class="sale-tag">-<?= round((($product['prix_base'] - $product['prix_promo']) / $product['prix_base']) * 100); ?>%</span>
                                    <?php endif; ?>
                                    <div class="product-action">
                                        <button class="btn add-to-cart-btn" data-product-id="<?= $product['id_produit']; ?>">
                                            <i class="ri-shopping-cart-line"></i>
                                        </button>
                                        <button class="btn wishlist-btn add-to-wishlist" data-product-id="<?= $product['id_produit']; ?>">
                                            <i class="ri-heart-3-line"></i>
                                        </button>
                                        <button class="btn quick-view-btn" data-product-id="<?= $product['id_produit']; ?>">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <a href="<?= base_url('product/' . $product['slug_produit']); ?>">
                                        <h5 class="product-name"><?= htmlspecialchars(substr($product['nom_produit'], 0, 50), ENT_QUOTES, 'UTF-8'); ?></h5>
                                    </a>
                                    <div class="rating">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="ri-star-fill <?= $i <= round($product['note_moyenne'] ?? 0) ? 'fill' : ''; ?>"></i>
                                        <?php endfor; ?>
                                        <span>(<?= $product['nombre_avis'] ?? 0; ?>)</span>
                                    </div>
                                    <div class="price">
                                        <span class="current-price"><?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF</span>
                                        <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                        <span class="old-price"><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="stock-info <?= $product['quantite_actuelle'] > 10 ? 'in-stock' : ($product['quantite_actuelle'] > 0 ? 'low-stock' : 'out-stock'); ?>">
                                        <?php if($product['quantite_actuelle'] > 10): ?>
                                        <i class="ri-checkbox-circle-line"></i> En stock
                                        <?php elseif($product['quantite_actuelle'] > 0): ?>
                                        <i class="ri-alert-line"></i> Stock limité
                                        <?php else: ?>
                                        <i class="ri-close-circle-line"></i> Rupture
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <i class="ri-search-line" style="font-size: 64px; color: #ccc;"></i>
                            <h4 class="mt-3">Aucun produit trouvé</h4>
                            <p>Essayez d'autres critères de recherche ou parcourez d'autres catégories.</p>
                            <a href="<?= base_url('shop'); ?>" class="btn theme-bg-color text-white">Voir tous les produits</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if(($totalPages ?? 1) > 1): ?>
                <?php
                $filterParams = http_build_query(array_filter([
                    'sort' => $currentSort,
                    'min_price' => $min_price ?? null,
                    'max_price' => $max_price ?? null,
                    'brands' => $brands ?? null,
                ]));
                ?>
                <nav class="pagination-box mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage - 1; ?>&<?= $filterParams; ?>">
                                <i class="ri-arrow-left-s-line"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php 
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                        if($start > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=1&<?= $filterParams; ?>">1</a></li>
                        <?php if($start > 2): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for($i = $start; $i <= $end; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>&<?= $filterParams; ?>"><?= $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if($end < $totalPages): ?>
                        <?php if($end < $totalPages - 1): ?><li class="page-item disabled"><span class="page-link">...</span></li><?php endif; ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $totalPages; ?>&<?= $filterParams; ?>"><?= $totalPages; ?></a></li>
                        <?php endif; ?>
                        
                        <?php if($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $currentPage + 1; ?>&<?= $filterParams; ?>">
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- Shop Section End -->

<!-- Scripts -->
<script>
$(document).ready(function() {
    // Add to cart
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

    // Tri des produits
    $('#sortProducts').change(function() {
        let sort = $(this).val();
        let url = new URL(window.location.href);
        url.searchParams.set('sort', sort);
        window.location.href = url.toString();
    });
    
    // Filtre prix
    let priceMin = <?= $min_price ?? $filters['min_price'] ?? 0; ?>;
    let priceMax = <?= $max_price ?? $filters['max_price'] ?? 1000000; ?>;
    let globalMin = <?= $filters['min_price'] ?? 0; ?>;
    let globalMax = <?= $filters['max_price'] ?? 1000000; ?>;
    
    $('#priceMin').on('input', function() {
        let val = parseInt($(this).val());
        if(val > priceMax) $(this).val(priceMax);
        priceMin = parseInt($(this).val());
        $('#priceMinValue').text(new Intl.NumberFormat('fr-FR').format(priceMin) + ' BIF');
    });
    
    $('#priceMax').on('input', function() {
        let val = parseInt($(this).val());
        if(val < priceMin) $(this).val(priceMin);
        priceMax = parseInt($(this).val());
        $('#priceMaxValue').text(new Intl.NumberFormat('fr-FR').format(priceMax) + ' BIF');
    });
    
    $('#applyPriceFilter').click(function() {
        let url = new URL(window.location.href);
        url.searchParams.set('min_price', priceMin);
        url.searchParams.set('max_price', priceMax);
        window.location.href = url.toString();
    });
    
    // Filtre marques
    $('#applyBrandFilter').click(function() {
        let selectedBrands = [];
        $('.brand-filter:checked').each(function() {
            selectedBrands.push($(this).val());
        });
        if(selectedBrands.length > 0) {
            let url = new URL(window.location.href);
            url.searchParams.set('brands', selectedBrands.join(','));
            window.location.href = url.toString();
        }
    });
    
    // Vue grille/liste
    $('.grid-btn').click(function() {
        $('.grid-btn').addClass('active');
        $('.list-btn').removeClass('active');
        $('.product-item').removeClass('list-view').addClass('grid-view');
        localStorage.setItem('productView', 'grid');
    });
    
    $('.list-btn').click(function() {
        $('.list-btn').addClass('active');
        $('.grid-btn').removeClass('active');
        $('.product-item').removeClass('grid-view').addClass('list-view');
        localStorage.setItem('productView', 'list');
    });
    
    // Restaurer la vue
    let savedView = localStorage.getItem('productView');
    if(savedView === 'list') {
        $('.list-btn').click();
    }
});
</script>

<style>
/* Styles pour la page catégorie */
.category-sidebar .category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.category-sidebar .category-list li {
    margin-bottom: 8px;
}
.category-sidebar .category-list li a {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    color: #666;
    text-decoration: none;
    transition: all 0.3s;
}
.category-sidebar .category-list li a:hover,
.category-sidebar .category-list li a.active {
    color: #f97316;
    padding-left: 8px;
}
.category-sidebar .category-list li a span {
    color: #999;
    font-size: 12px;
}

/* Filtre prix */
.price-range .form-range {
    width: 100%;
    margin: 10px 0;
}

/* Badge vente */
.sale-tag {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #dc3545;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: bold;
    z-index: 2;
}

/* Actions produit */
.product-action {
    position: absolute;
    bottom: 10px;
    right: 10px;
    display: flex;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s;
}
.product-box:hover .product-action {
    opacity: 1;
}
.product-action .btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.product-action .btn:hover {
    background: #f97316;
    color: white;
}

/* Stock info */
.stock-info {
    font-size: 12px;
    margin-top: 8px;
}
.stock-info.in-stock { color: #28a745; }
.stock-info.low-stock { color: #ffc107; }
.stock-info.out-stock { color: #dc3545; }

/* Vue liste */
.product-item.list-view {
    width: 100%;
}
.product-item.list-view .product-box {
    display: flex;
    gap: 20px;
}
.product-item.list-view .product-image {
    width: 200px;
    flex-shrink: 0;
}
.product-item.list-view .product-content {
    flex: 1;
}

/* Barre de tri */
.shop-top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}
.product-sort {
    display: flex;
    align-items: center;
    gap: 10px;
}
.product-sort select {
    width: 180px;
    padding: 8px 12px;
    border-radius: 5px;
    border: 1px solid #ddd;
}
.grid-list {
    display: flex;
    gap: 8px;
}
.grid-list .btn {
    padding: 6px 12px;
    border: 1px solid #ddd;
    background: white;
}
.grid-list .btn.active {
    background: #f97316;
    color: white;
    border-color: #f97316;
}

/* Responsive */
@media (max-width: 768px) {
    .shop-top-bar {
        flex-direction: column;
        align-items: stretch;
    }
    .product-sort select {
        width: 100%;
    }
    .product-item.list-view .product-box {
        flex-direction: column;
    }
    .product-item.list-view .product-image {
        width: 100%;
    }
}
</style>