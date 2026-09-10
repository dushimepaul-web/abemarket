<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2><?= htmlspecialchars($seller['nom_boutique']) ?></h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url() ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('sellers') ?>">Vendeurs</a>
                    </li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($seller['nom_boutique']) ?></li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Seller Details Section Start -->
<section class="seller-details-section section-t-space">
    <div class="custom-container">
        <div class="seller-top-box">
            <div class="profile-image">
                <div>
                    <div class="band-image">
                        <?php if (!empty($seller['logo_boutique'])): ?>
                            <img src="<?= base_url($seller['logo_boutique']) ?>" alt="<?= htmlspecialchars($seller['nom_boutique']) ?>" class="img-fluid">
                        <?php else: ?>
                            <i class="ri-store-3-fill" style="font-size: 80px; color: #ddd;"></i>
                        <?php endif; ?>
                    </div>
                    <h3><?= htmlspecialchars($seller['nom_boutique']) ?></h3>
                    <div class="rating">
                        <ul>
                            <?php for ($i = 1; $i <= $seller_rating['full_stars']; $i++): ?>
                                <li><i class="ri-star-fill fill"></i></li>
                            <?php endfor; ?>
                            <?php if ($seller_rating['half_star']): ?>
                                <li><i class="ri-star-half-fill fill"></i></li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $seller_rating['empty_stars']; $i++): ?>
                                <li><i class="ri-star-line"></i></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <h4><?= number_format($seller['followers'] ?? 0) ?> followers | <?= $seller_rating['total_reviews'] ?> avis</h4>
                </div>
            </div>
            <div class="profile-detail">
                <div>
                    <p><?= nl2br(htmlspecialchars($seller['description'] ?? 'Aucune description disponible.')) ?></p>
                </div>
            </div>
            <div class="vendor-contact">
                <div>
                    <h5>Nous contacter:</h5>
                    <div class="footer-social">
                        <ul>
                            <li>
                                <a href="https://www.facebook.com/" target="_blank">
                                    <i class="ri-facebook-fill"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.google.com/" target="_blank">
                                    <i class="ri-google-fill"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.twitter.com/" target="_blank">
                                    <i class="ri-twitter-x-fill"></i>
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/" target="_blank">
                                    <i class="ri-instagram-fill"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="vendor-details-box">
                        <h6>Pour toute question:</h6>
                        <ul class="vendor-details">
                            <?php if (!empty($seller['telephone'])): ?>
                            <li>
                                <i class="ri-smartphone-line"></i>
                                <h5><?= htmlspecialchars($seller['telephone']) ?></h5>
                            </li>
                            <?php endif; ?>
                            <?php if (!empty($seller['whatsapp'])): ?>
                            <li>
                                <i class="ri-whatsapp-line"></i>
                                <h5><a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $seller['whatsapp']) ?>" target="_blank"><?= htmlspecialchars($seller['whatsapp']) ?></a></h5>
                            </li>
                            <?php endif; ?>
                            <?php if (!empty($seller['email'])): ?>
                            <li>
                                <i class="ri-mail-line"></i>
                                <h5>
                                    <a href="mailto:<?= htmlspecialchars($seller['email']) ?>"><?= htmlspecialchars($seller['email']) ?></a>
                                </h5>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php if (!empty($seller['full_address']) && $seller['full_address'] !== 'Adresse non renseignée'): ?>
                    <div class="vendor-details-box" style="margin-top:15px;">
                        <h6><i class="ri-map-pin-line"></i> Adresse:</h6>
                        <p style="margin:5px 0 0;color:#555;"><?= htmlspecialchars($seller['full_address']) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($seller['latitude']) && !empty($seller['longitude'])): ?>
                    <div style="margin-top:15px;">
                        <h6><i class="ri-map-2-line"></i> Localisation:</h6>
                        <div id="sellerMap" style="height:200px;border-radius:8px;margin-top:8px;border:1px solid #eee;"></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Seller Details Section End -->

<!-- Shop Section Start -->
<section class="section-t-space shop-section">
    <div class="custom-container">
        <div class="row">
            <div class="col-custom-3">
                <div class="left-box">
                    <div class="shop-left-sidebar">
                        <button class="btn back-button">
                            <i class="ri-arrow-left-line"></i> Retour
                        </button>

                        <!-- Filtre Catégories -->
                        <div class="filter-category-2">
                            <div class="filter-title">
                                <h2>Catégories</h2>
                                <a href="<?= base_url('seller/' . $seller['slug_boutique']) ?>">Effacer</a>
                            </div>
                            <ul>
                                <?php foreach ($product_categories as $cat): ?>
                                <li>
                                    <a href="<?= base_url('seller/' . $seller['slug_boutique'] . '?category=' . $cat['id_categorie']) ?>">
                                        <?= htmlspecialchars($cat['nom_categorie']) ?> (<?= $cat['product_count'] ?>)
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Filtre Prix -->
                        <div class="accordion custom-accordion-2">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseThree">
                                        <span>Prix</span>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="price-range-slider">
                                            <div class="slider-container">
                                                <div class="range-slider">
                                                    <div class="range-fill"></div>
                                                    <input type="range" id="minRange" min="<?= floor($price_range['min_price'] ?? 0) ?>" 
                                                           max="<?= ceil($price_range['max_price'] ?? 1000000) ?>" 
                                                           value="<?= floor($price_range['min_price'] ?? 0) ?>" step="1000">
                                                    <input type="range" id="maxRange" min="<?= floor($price_range['min_price'] ?? 0) ?>" 
                                                           max="<?= ceil($price_range['max_price'] ?? 1000000) ?>" 
                                                           value="<?= ceil($price_range['max_price'] ?? 1000000) ?>" step="1000">
                                                </div>
                                                <div class="price-values">
                                                    <span id="min-price"><?= number_format(floor($price_range['min_price'] ?? 0), 0, ',', ' ') ?> BIF</span>
                                                    <span class="dash">-</span>
                                                    <span id="max-price"><?= number_format(ceil($price_range['max_price'] ?? 1000000), 0, ',', ' ') ?> BIF</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-custom-9">
                <!-- Tri -->
                <div class="show-button show-button-2">
                    <div class="top-filter-menu">
                        <div class="category-dropdown">
                            <div class="filter-button d-inline-block d-lg-none">
                                <a href="#!"><i class="ri-equalizer-2-line"></i> Filtrer</a>
                            </div>

                            <div class="d-flex align-items-center dropdown-box">
                                <h5 class="text-content">Trier par :</h5>
                                <div class="dropdown">
                                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown">
                                        <span id="selectedSort">
                                            <?php 
                                                $sort = $this->input->get('sort');
                                                if ($sort == 'price_asc') echo 'Prix croissant';
                                                elseif ($sort == 'price_desc') echo 'Prix décroissant';
                                                elseif ($sort == 'rating') echo 'Meilleures notes';
                                                else echo 'Les plus populaires';
                                            ?>
                                        </span>
                                        <i class="ri-arrow-down-s-line"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="?sort=popular">Les plus populaires</a></li>
                                        <li><a class="dropdown-item" href="?sort=price_asc">Prix croissant</a></li>
                                        <li><a class="dropdown-item" href="?sort=price_desc">Prix décroissant</a></li>
                                        <li><a class="dropdown-item" href="?sort=rating">Meilleures notes</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des produits -->
                <?php if (empty($products)): ?>
                <div class="text-center py-5">
                    <i class="ri-store-line" style="font-size: 64px; color: #ddd;"></i>
                    <h3 class="mt-3">Aucun produit trouvé</h3>
                    <p>Ce vendeur n'a pas encore de produits disponibles.</p>
                </div>
                <?php else: ?>

                <div class="row g-sm-4 g-3 row-cols-xxl-5 row-cols-xl-4 row-cols-lg-3 row-cols-md-3 row-cols-2 product-list-section">
                    <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="product-box-4-main">
                            <div class="select-option-box">
                                <div class="select-box">
                                    <div>
                                        <div class="color-box">
                                            <h4 class="h5">Couleurs</h4>
                                            <ul class="color-list">
                                                <li><a href="#!" style="background-color: #DAA520;"></a></li>
                                                <li><a href="#!" style="background-color: #CDC6B4;"></a></li>
                                                <li><a href="#!" style="background-color: #D9B061;"></a></li>
                                            </ul>
                                        </div>
                                        <div class="size-box">
                                            <h4 class="h5">Tailles</h4>
                                            <ul class="size-list">
                                                <li><a href="#!">xs</a></li>
                                                <li><a href="#!">s</a></li>
                                                <li><a href="#!">m</a></li>
                                                <li><a href="#!">l</a></li>
                                                <li><a href="#!">xl</a></li>
                                            </ul>
                                        </div>
                                        <button class="btn add-cart-btn add-to-cart" data-product-id="<?= $product['id_produit'] ?>">Ajouter au panier</button>
                                        <button class="close-btn btn" onclick="closeSidebar()">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="productMain product-box-4 pro-bg-white">
                                <div class="product-image">
                                    <a href="<?= base_url('product/' . $product['slug_produit']) ?>">
                                        <?php if (!empty($product['image_url'])): ?>
                                            <img src="<?= base_url($product['image_url']) ?>" class="img-fluid productImage" alt="<?= htmlspecialchars($product['nom_produit']) ?>">
                                        <?php else: ?>
                                            <img src="<?= base_url('assets/frontend/images/product/placeholder.jpg') ?>" class="img-fluid productImage" alt="Image non disponible">
                                        <?php endif; ?>
                                    </a>
                                    <?php if ($product['discount_percent'] > 0): ?>
                                    <div class="label-block">
                                        <span class="badge badge-theme">-<?= $product['discount_percent'] ?>%</span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="quick-view-button-box">
                                        <button class="btn view-btn" data-bs-target="#quickViewModal" data-bs-toggle="modal" data-product-id="<?= $product['id_produit'] ?>">Aperçu rapide</button>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <h4 class="sub-name productName"><?= htmlspecialchars(substr($product['nom_produit'], 0, 30)) ?></h4>
                                    <a href="<?= base_url('product/' . $product['slug_produit']) ?>" class="name">
                                        <h5><?= htmlspecialchars($product['nom_produit']) ?></h5>
                                    </a>
                                    <ul class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <li><i class="ri-star<?= $i <= round($product['note_moyenne']) ? '-fill fill' : '-line' ?>"></i></li>
                                        <?php endfor; ?>
                                    </ul>
                                    <p class="product-details"><?= htmlspecialchars(substr(strip_tags($product['description_courte'] ?? $product['description'] ?? ''), 0, 120)) ?>...</p>
                                    <h5 class="price">
                                        <?= number_format($product['prix_effectif'], 0, ',', ' ') ?> BIF
                                        <?php if ($product['prix_promo']): ?>
                                            <del><?= number_format($product['prix_base'], 0, ',', ' ') ?> BIF</del>
                                        <?php endif; ?>
                                    </h5>
                                    <div class="option-box">
                                        <button class="btn select-btn">Options</button>
                                        <ul class="option-list">
                                            <li>
                                                <a href="#" class="wishlistProduct <?= in_array($product['id_produit'], $wishlist_ids ?? []) ? 'active' : '' ?>" data-product-id="<?= $product['id_produit'] ?>">
                                                    <i class="ri-heart-3-line"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="compareProduct" data-product-id="<?= $product['id_produit'] ?>">
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
                <?php if ($total_pages > 1): ?>
                <nav class="custom-pagination">
                    <ul class="pagination justify-content-center">
                        <?php if ($current_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $current_page - 1 ?>&sort=<?= $this->input->get('sort') ?>">
                                <i class="ri-arrow-left-s-line"></i>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#!"><i class="ri-arrow-left-s-line"></i></a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&sort=<?= $this->input->get('sort') ?>">
                                <span><?= $i ?></span>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $current_page + 1 ?>&sort=<?= $this->input->get('sort') ?>">
                                <i class="ri-arrow-right-s-line"></i>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#!"><i class="ri-arrow-right-s-line"></i></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
                
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- Shop Section End -->

<script>
// Gestionnaire de tri
document.querySelectorAll('.dropdown-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        let sortValue = this.getAttribute('href').split('=')[1];
        document.getElementById('selectedSort').innerText = this.innerText;
        let url = new URL(window.location.href);
        url.searchParams.set('sort', sortValue);
        window.location.href = url.toString();
    });
});

// Slider de prix
const minRange = document.getElementById('minRange');
const maxRange = document.getElementById('maxRange');
const minPriceSpan = document.getElementById('min-price');
const maxPriceSpan = document.getElementById('max-price');

if (minRange && maxRange) {
    function updatePriceRange() {
        let minVal = parseInt(minRange.value);
        let maxVal = parseInt(maxRange.value);
        if (minVal > maxVal) {
            [minVal, maxVal] = [maxVal, minVal];
        }
        minPriceSpan.innerText = new Intl.NumberFormat().format(minVal) + ' BIF';
        maxPriceSpan.innerText = new Intl.NumberFormat().format(maxVal) + ' BIF';
    }
    
    minRange.addEventListener('input', updatePriceRange);
    maxRange.addEventListener('input', updatePriceRange);
    updatePriceRange();
}
</script>

<?php if (!empty($seller['latitude']) && !empty($seller['longitude'])): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = <?= floatval($seller['latitude']) ?>;
    var lng = <?= floatval($seller['longitude']) ?>;
    var map = L.map('sellerMap').setView([lat, lng], 15);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    L.marker([lat, lng]).addTo(map).bindPopup('<?= htmlspecialchars($seller["nom_boutique"]) ?>');
});
</script>
<?php endif; ?>