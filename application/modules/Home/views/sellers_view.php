<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Nos Vendeurs</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url() ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Vendeurs</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Statistiques Section -->
<section class="seller-stats-section">
    <div class="custom-container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-box">
                    <i class="ri-store-3-line"></i>
                    <h3><?= number_format($stats['sellers'] ?? 0) ?></h3>
                    <p>Vendeurs partenaires</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-box">
                    <i class="ri-shopping-bag-line"></i>
                    <h3><?= number_format($stats['products'] ?? 0) ?></h3>
                    <p>Produits disponibles</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-box">
                    <i class="ri-user-line"></i>
                    <h3><?= number_format($stats['customers'] ?? 0) ?></h3>
                    <p>Clients satisfaits</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-box">
                    <i class="ri-truck-line"></i>
                    <h3><?= number_format($stats['orders'] ?? 0) ?></h3>
                    <p>Commandes livrées</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Seller Section Start -->
<section class="seller-section section-t-space">
    <div class="custom-container">
        
        <!-- Filtres et recherche -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="search-box">
                    <input type="text" id="searchSeller" class="form-control" placeholder="Rechercher un vendeur...">
                    <i class="ri-search-line"></i>
                </div>
            </div>
            <div class="col-md-6">
                <div class="sort-box text-md-end">
                    <select id="sortSellers" class="form-select">
                        <option value="best">Les mieux notés</option>
                        <option value="most_products">Plus de produits</option>
                        <option value="recent">Récents</option>
                    </select>
                </div>
            </div>
        </div>
        
        <?php if (empty($sellers)): ?>
        <div class="text-center py-5">
            <i class="ri-store-line" style="font-size: 64px; color: #ddd;"></i>
            <h3 class="mt-3">Aucun vendeur trouvé</h3>
            <p>Revenez plus tard pour découvrir nos nouveaux vendeurs.</p>
        </div>
        <?php else: ?>
        
        <div class="row g-sm-4 g-3" id="sellersGrid">
            <?php foreach ($sellers as $seller): ?>
            <div class="col-lg-6 seller-item" 
                 data-seller-name="<?= strtolower(htmlspecialchars($seller['nom_boutique'])) ?>"
                 data-rating="<?= $seller['note_moyenne'] ?>"
                 data-products="<?= $seller['product_count'] ?>">
                <div class="seller-box-2 seller-box">
                    <div class="seller-head">
                        <h3><?= $seller['product_count'] ?> produits</h3>
                        <div class="seller-img">
                            <?php if (!empty($seller['logo_boutique'])): ?>
                                <img src="<?= base_url($seller['logo_boutique']) ?>" class="img-fluid" alt="<?= htmlspecialchars($seller['nom_boutique']) ?>">
                            <?php else: ?>
                                <i class="ri-store-3-fill" style="font-size: 60px; color: #ddd;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="seller-name">
                            <span>Depuis <?= date('Y', strtotime($seller['date_creation'])) ?></span>
                            <a href="<?= base_url('seller/' . $seller['slug_boutique']) ?>"><?= htmlspecialchars($seller['nom_boutique']) ?></a>
                            <div class="rating">
                                <ul>
                                    <?php 
                                    $fullStars = floor($seller['note_moyenne']);
                                    $halfStar = ($seller['note_moyenne'] - $fullStars) >= 0.5;
                                    for ($i = 1; $i <= 5; $i++):
                                        if ($i <= $fullStars):
                                    ?>
                                        <li><i class="ri-star-fill fill"></i></li>
                                    <?php elseif ($i == $fullStars + 1 && $halfStar): ?>
                                        <li><i class="ri-star-half-fill fill"></i></li>
                                    <?php else: ?>
                                        <li><i class="ri-star-line"></i></li>
                                    <?php endif; endfor; ?>
                                </ul>
                                <span>(<?= $seller['nombre_avis'] ?> avis)</span>
                            </div>
                        </div>
                    </div>

                    <div class="seller-contain">
                        <p class="details"><?= htmlspecialchars(substr(strip_tags($seller['description'] ?? ''), 0, 200)) ?>...</p>
                        <ul class="details-list">
                            <li>
                                <h4>Localisation:</h4>
                                <span><?= htmlspecialchars($seller['full_address'] ?? 'Adresse non renseignée') ?></span>
                            </li>
                            <?php if (!empty($seller['telephone'])): ?>
                            <li>
                                <h4>Téléphone:</h4>
                                <span><?= htmlspecialchars($seller['telephone']) ?></span>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="seller-bottom">
                        <ul class="left-box">
                            <li><?= $seller['product_count'] ?> produits en stock
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?= $seller['stock_percentage'] ?>%"></div>
                                </div>
                            </li>
                        </ul>
                        <div class="right-box">
                            <a href="<?= base_url('seller/' . $seller['slug_boutique']) ?>" class="btn btn-bg-theme">
                                Visiter <i class="ri-arrow-right-long-fill"></i>
                            </a>
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
                <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1 ?>">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                </li>
                <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#!">
                        <i class="ri-arrow-left-s-line"></i>
                    </a>
                </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>">
                        <span><?= $i ?></span>
                    </a>
                </li>
                <?php endfor; ?>
                
                <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1 ?>">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                </li>
                <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#!">
                        <i class="ri-arrow-right-s-line"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        
        <?php endif; ?>
    </div>
</section>
<!-- Seller Section End -->

<script>
// Recherche dynamique des vendeurs
document.getElementById('searchSeller')?.addEventListener('keyup', function() {
    let searchTerm = this.value.toLowerCase();
    let sellers = document.querySelectorAll('.seller-item');
    
    sellers.forEach(seller => {
        let sellerName = seller.getAttribute('data-seller-name');
        if (sellerName.includes(searchTerm)) {
            seller.style.display = '';
        } else {
            seller.style.display = 'none';
        }
    });
});

// Tri des vendeurs
document.getElementById('sortSellers')?.addEventListener('change', function() {
    let sortBy = this.value;
    let grid = document.getElementById('sellersGrid');
    let sellers = Array.from(document.querySelectorAll('.seller-item'));
    
    sellers.sort((a, b) => {
        if (sortBy === 'best') {
            return parseFloat(b.getAttribute('data-rating')) - parseFloat(a.getAttribute('data-rating'));
        } else if (sortBy === 'most_products') {
            return parseInt(b.getAttribute('data-products')) - parseInt(a.getAttribute('data-products'));
        }
        return 0;
    });
    
    sellers.forEach(seller => grid.appendChild(seller));
});
</script>

<style>
.seller-stats-section {
    padding: 40px 0;
    background: #f8f9fa;
}
.stat-box {
    text-align: center;
    padding: 30px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s;
}
.stat-box:hover {
    transform: translateY(-5px);
}
.stat-box i {
    font-size: 48px;
    color: #ff6b6b;
    margin-bottom: 15px;
}
.stat-box h3 {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #2c3e50;
}
.stat-box p {
    color: #7f8c8d;
    margin: 0;
}
.search-box {
    position: relative;
}
.search-box input {
    padding-right: 45px;
}
.search-box i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}
</style>