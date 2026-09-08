<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2 id="page-title"><?= !empty($currentCategoryName) ? 'Shop - ' . htmlspecialchars($currentCategoryName) : 'Boutique' ?></h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url() ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Boutique</li>
                    <?php if(!empty($currentCategoryName)): ?>
                    <li class="breadcrumb-item active" id="breadcrumb-category"><?= htmlspecialchars($currentCategoryName) ?></li>
                    <?php endif; ?>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Shop Section Start -->
<section class="section-t-space shop-section">
    <div class="custom-container">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-custom-3">
                <div class="left-box">
                    <div class="shop-left-sidebar">
                        <button class="back-button btn">
                            <i class="ri-arrow-left-line"></i> Back
                        </button>

                        <div class="filter-category-2">
                            <div class="filter-title">
                                <h2>Filters</h2>
                                <a href="<?= base_url('shop') ?>" class="clear-all" id="clearAllFilters">Clear All</a>
                            </div>
                        </div>

                        <div class="accordion custom-accordion-2">
                            <!-- Categories Filter -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseTwo">
                                        <span>Categories</span>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="search-box">
                                            <input type="search" class="form-control category-search" id="categorySearch"
                                                placeholder="Search ..">
                                            <button class="search-button btn">
                                                <i class="ri-search-2-line"></i>
                                            </button>
                                        </div>

                                        <ul class="category-list custom-padding custom-height" id="categoryList">
                                            <?php if(!empty($categories)): ?>
                                                <?php foreach($categories as $cat): ?>
                                                <li>
                                                    <div class="form-check category-list-box">
                                                        <input class="checkbox_animated category-checkbox" 
                                                               type="checkbox" 
                                                               value="<?= $cat['id_categorie'] ?>"
                                                               data-slug="<?= $cat['slug_categorie'] ?>"
                                                               data-name="<?= htmlspecialchars($cat['nom_categorie']) ?>"
                                                               id="cat_<?= $cat['id_categorie'] ?>"
                                                               <?= ($currentCategory == $cat['id_categorie']) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="cat_<?= $cat['id_categorie'] ?>">
                                                            <span class="name"><?= htmlspecialchars($cat['nom_categorie']) ?></span>
                                                            <span class="number" id="cat-count-<?= $cat['id_categorie'] ?>">(<?= $cat['product_count'] ?? 0 ?>)</span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <li>Aucune catégorie disponible</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Filter -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseThree">
                                        <span>Price</span>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <div class="price-range-slider">
                                            <div class="price-inputs mb-3">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label class="small">Min</label>
                                                        <input type="number" class="form-control form-control-sm" id="minPriceInput" placeholder="Min">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="small">Max</label>
                                                        <input type="number" class="form-control form-control-sm" id="maxPriceInput" placeholder="Max">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="priceSlider"></div>
                                            <div class="price-values mt-2 d-flex justify-content-between">
                                                <span id="minPriceDisplay"><?= number_format($filters['min_price'] ?? 0, 0, ',', ' ') ?> BIF</span>
                                                <span id="maxPriceDisplay"><?= number_format($filters['max_price'] ?? 1000000, 0, ',', ' ') ?> BIF</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Brands Filter -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseFour">
                                        <span>Brands</span>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <ul class="category-list custom-padding" id="brandList">
                                            <?php if(!empty($filters['brands'])): ?>
                                                <?php foreach($filters['brands'] as $brand): ?>
                                                <li>
                                                    <div class="form-check category-list-box">
                                                        <input class="checkbox_animated brand-checkbox" 
                                                               type="checkbox" 
                                                               value="<?= htmlspecialchars($brand) ?>"
                                                               id="brand_<?= preg_replace('/[^a-zA-Z0-9]/', '_', $brand) ?>"
                                                               <?= in_array($brand, $selected_brands ?? []) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="brand_<?= preg_replace('/[^a-zA-Z0-9]/', '_', $brand) ?>">
                                                            <span class="name"><?= htmlspecialchars($brand) ?></span>
                                                        </label>
                                                    </div>
                                                </li>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <li>Aucune marque disponible</li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Review Filter -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseFive">
                                        <span>Customer Review</span>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <ul class="category-list custom-padding" id="ratingList">
                                            <?php for($i = 5; $i >= 1; $i--): ?>
                                            <li>
                                                <div class="form-check category-list-box">
                                                    <input class="checkbox_animated rating-checkbox" 
                                                           type="checkbox" 
                                                           value="<?= $i ?>"
                                                           id="rating_<?= $i ?>"
                                                           <?= ($selected_rating == $i) ? 'checked' : '' ?>>
                                                    <div class="form-check-label category-rating-box">
                                                        <ul class="rating">
                                                            <?php for($j = 1; $j <= 5; $j++): ?>
                                                            <li>
                                                                <i class="ri-star-fill <?= ($j <= $i) ? 'fill' : '' ?>"></i>
                                                            </li>
                                                            <?php endfor; ?>
                                                        </ul>
                                                        <span class="text-content">(<?= $filters['rating_distribution'][$i] ?? 0 ?>)</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php endfor; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Area -->
            <div class="col-custom-9">
                <!-- Category Slider -->
                <?php if(!empty($categories)): ?>
                <div class="light-bg-color light-sm-box mb-14">
                    <div class="title-2 title-2-sm slider-button-sm">
                        <h3>Browse by categories</h3>
                        <div class="title-slider-button">
                            <div class="electronic-category-prev swiper-btn-prev">
                                <i class="ri-arrow-left-s-line"></i>
                            </div>
                            <div class="electronic-category-next swiper-btn-next">
                                <i class="ri-arrow-right-s-line"></i>
                            </div>
                        </div>
                    </div>

                    <div class="swiper electronic-category-slider-2">
                        <div class="swiper-wrapper">
                            <?php foreach($categories as $cat): ?>
                            <div class="swiper-slide">
                                <div class="category-sm-box">
                                    <a href="#" class="category-image quick-category" data-id="<?= $cat['id_categorie'] ?>" data-name="<?= htmlspecialchars($cat['nom_categorie']) ?>">
                                        <?php if(!empty($cat['url_image'])): ?>
                                        <img src="<?= base_url($cat['url_image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($cat['nom_categorie']) ?>">
                                        <?php else: ?>
                                        <img src="<?= base_url('assets/frontend/images/product/placeholder.png') ?>" class="img-fluid" alt="">
                                        <?php endif; ?>
                                    </a>
                                    <h4><?= htmlspecialchars($cat['nom_categorie']) ?></h4>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Sort and Filter Bar -->
                <div class="show-button show-button-2">
                    <div class="top-filter-menu">
                        <div class="category-dropdown">
                            <div class="filter-button d-inline-block d-lg-none">
                                <a href="#!"><i class="ri-equalizer-2-line"></i> Filter Menu</a>
                            </div>
                            <div class="d-flex align-items-center dropdown-box">
                                <h5 class="text-content">Sort By :</h5>
                                <div class="dropdown">
                                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown">
                                        <span id="selectedSort">
                                            <?php
                                            switch($currentSort):
                                                case 'price_asc': echo 'Price: Low to High'; break;
                                                case 'price_desc': echo 'Price: High to Low'; break;
                                                case 'rating': echo 'Best Rating'; break;
                                                case 'bestselling': echo 'Best Selling'; break;
                                                default: echo 'Newest First';
                                            endswitch;
                                            ?>
                                        </span>
                                        <i class="ri-arrow-down-s-line"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item sort-item" href="#" data-sort="newest">Newest First</a></li>
                                        <li><a class="dropdown-item sort-item" href="#" data-sort="price_asc">Price: Low to High</a></li>
                                        <li><a class="dropdown-item sort-item" href="#" data-sort="price_desc">Price: High to Low</a></li>
                                        <li><a class="dropdown-item sort-item" href="#" data-sort="rating">Best Rating</a></li>
                                        <li><a class="dropdown-item sort-item" href="#" data-sort="bestselling">Best Selling</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="grid-option d-none d-md-block">
                            <ul>
                                <li class="two-grid"><button class="btn grid-btn" data-grid="2"><svg><use xlink:href="<?= base_url('assets/svg/grid-option.svg'); ?>#gridTwo"></use></svg></button></li>
                                <li class="three-grid"><button class="btn grid-btn" data-grid="3"><svg><use xlink:href="<?= base_url('assets/svg/grid-option.svg'); ?>#gridThree"></use></svg></button></li>
                                <li class="grid-btn"><button class="btn grid-btn" data-grid="4"><svg><use xlink:href="<?= base_url('assets/svg/grid-option.svg'); ?>#gridFour"></use></svg></button></li>
                                <li class="five-grid d-xxl-inline-block d-none active"><button class="btn grid-btn" data-grid="5"><svg><use xlink:href="<?= base_url('assets/svg/grid-option.svg'); ?>#gridFive"></use></svg></button></li>
                                <li class="list-btn"><button class="btn list-view-btn"><svg><use xlink:href="<?= base_url('assets/svg/grid-option.svg'); ?>#list"></use></svg></button></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Chargement des produits...</p>
                </div>

                <!-- Products Grid Container -->
                <div id="productsContainer">
                    <?php if(!empty($products)): ?>
                    <?php $this->load->view('partials/product_grid', ['products' => $products]); ?>
                    <?php else: ?>
                    <div class="empty-shop-state text-center py-5">
                        <div class="empty-state-icon mb-4">
                            <i class="ri-shopping-bag-line" style="font-size: 80px; color: #ccc;"></i>
                        </div>
                        <h4>Aucun produit trouvé</h4>
                        <p class="text-muted">Nous n'avons trouvé aucun produit correspondant à vos critères.</p>
                        <a href="<?= base_url('shop') ?>" class="btn btn-primary mt-3">Voir tous les produits</a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination Container -->
                <div id="paginationContainer">
                    <?php if(!empty($products) && $totalPages > 1): ?>
                    <?php $this->load->view('partials/pagination', ['totalPages' => $totalPages, 'currentPage' => $currentPage]); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Shop Section End -->

<script>
$(document).ready(function() {
    let currentFilters = {
        category: <?= $currentCategory ?: 'null' ?>,
        min_price: <?= $selected_min_price ?: 'null' ?>,
        max_price: <?= $selected_max_price ?: 'null' ?>,
        brands: <?= json_encode($selected_brands ?? []) ?>,
        rating: <?= $selected_rating ?: 'null' ?>,
        sort: '<?= $currentSort ?>',
        page: <?= $currentPage ?>
    };
    
    let isAjaxLoading = false;
    
    // Initialize price slider with noUiSlider if available
    if (typeof noUiSlider !== 'undefined' && $('#priceSlider').length) {
        let minPrice = <?= $filters['min_price'] ?? 0 ?>;
        let maxPrice = <?= $filters['max_price'] ?? 1000000 ?>;
        let startMin = <?= $selected_min_price ?: $filters['min_price'] ?? 0 ?>;
        let startMax = <?= $selected_max_price ?: $filters['max_price'] ?? 1000000 ?>;
        
        noUiSlider.create(document.getElementById('priceSlider'), {
            start: [startMin, startMax],
            connect: true,
            step: 1000,
            range: {
                'min': minPrice,
                'max': maxPrice
            },
            format: {
                to: function(value) { return Math.round(value); },
                from: function(value) { return Number(value); }
            }
        });
        
        $('#priceSlider').on('slide', function(values, handle) {
            $('#minPriceInput').val(Math.round(values[0]));
            $('#maxPriceInput').val(Math.round(values[1]));
            $('#minPriceDisplay').text(new Intl.NumberFormat().format(Math.round(values[0])) + ' BIF');
            $('#maxPriceDisplay').text(new Intl.NumberFormat().format(Math.round(values[1])) + ' BIF');
        });
        
        $('#priceSlider').on('set', function(values) {
            currentFilters.min_price = Math.round(values[0]);
            currentFilters.max_price = Math.round(values[1]);
            currentFilters.page = 1;
            applyFiltersAjax();
        });
    }
    
    // Manual price inputs
    let priceTimeout;
    $('#minPriceInput, #maxPriceInput').on('input', function() {
        clearTimeout(priceTimeout);
        priceTimeout = setTimeout(function() {
            let minVal = parseInt($('#minPriceInput').val()) || <?= $filters['min_price'] ?? 0 ?>;
            let maxVal = parseInt($('#maxPriceInput').val()) || <?= $filters['max_price'] ?? 1000000 ?>;
            if (minVal > maxVal) {
                let temp = minVal;
                minVal = maxVal;
                maxVal = temp;
            }
            currentFilters.min_price = minVal;
            currentFilters.max_price = maxVal;
            currentFilters.page = 1;
            applyFiltersAjax();
        }, 500);
    });
    
    // Category filter
    $('.category-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('.category-checkbox').not(this).prop('checked', false);
            currentFilters.category = $(this).val();
            let categoryName = $(this).data('name');
            $('#page-title').text('Shop - ' + categoryName);
            $('#breadcrumb-category').text(categoryName);
        } else {
            currentFilters.category = null;
            $('#page-title').text('Boutique');
            $('#breadcrumb-category').remove();
        }
        currentFilters.page = 1;
        applyFiltersAjax();
    });
    
    // Quick category click (from slider)
    $('.quick-category').on('click', function(e) {
        e.preventDefault();
        let catId = $(this).data('id');
        let catName = $(this).data('name');
        
        $('.category-checkbox').prop('checked', false);
        $('#cat_' + catId).prop('checked', true);
        
        currentFilters.category = catId;
        currentFilters.page = 1;
        $('#page-title').text('Shop - ' + catName);
        
        if ($('#breadcrumb-category').length) {
            $('#breadcrumb-category').text(catName);
        } else {
            $('.breadcrumb').append('<li class="breadcrumb-item active" id="breadcrumb-category">' + catName + '</li>');
        }
        
        applyFiltersAjax();
    });
    
    // Brand filter
    $('.brand-checkbox').on('change', function() {
        let selectedBrands = [];
        $('.brand-checkbox:checked').each(function() {
            selectedBrands.push($(this).val());
        });
        currentFilters.brands = selectedBrands;
        currentFilters.page = 1;
        applyFiltersAjax();
    });
    
    // Rating filter
    $('.rating-checkbox').on('change', function() {
        if ($(this).is(':checked')) {
            $('.rating-checkbox').not(this).prop('checked', false);
            currentFilters.rating = $(this).val();
        } else {
            currentFilters.rating = null;
        }
        currentFilters.page = 1;
        applyFiltersAjax();
    });
    
    // Sort filter
    $('.sort-item').on('click', function(e) {
        e.preventDefault();
        let sort = $(this).data('sort');
        currentFilters.sort = sort;
        currentFilters.page = 1;
        
        let sortText = $(this).text();
        $('#selectedSort').text(sortText);
        
        applyFiltersAjax();
    });
    
    // Clear all filters
    $('#clearAllFilters').on('click', function(e) {
        e.preventDefault();
        $('.category-checkbox').prop('checked', false);
        $('.brand-checkbox').prop('checked', false);
        $('.rating-checkbox').prop('checked', false);
        
        currentFilters = {
            category: null,
            min_price: null,
            max_price: null,
            brands: [],
            rating: null,
            sort: 'newest',
            page: 1
        };
        
        $('#selectedSort').text('Newest First');
        $('#page-title').text('Boutique');
        $('#breadcrumb-category').remove();
        
        if (typeof noUiSlider !== 'undefined' && $('#priceSlider').length && $('#priceSlider')[0].noUiSlider) {
            $('#priceSlider')[0].noUiSlider.set([<?= $filters['min_price'] ?? 0 ?>, <?= $filters['max_price'] ?? 1000000 ?>]);
        }
        
        applyFiltersAjax();
        window.history.pushState({}, '', '<?= base_url("shop") ?>');
    });
    
    // Pagination (delegated)
    $(document).on('click', '.page-number, .page-link-prev, .page-link-next', function(e) {
        e.preventDefault();
        let page = $(this).data('page');
        if (page && page > 0 && !$(this).parent().hasClass('disabled')) {
            currentFilters.page = page;
            applyFiltersAjax();
            $('html, body').animate({ scrollTop: 0 }, 300);
        }
    });
    
    // Add to cart (delegated)
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
                    showToast('Produit ajouté au panier', 'success');
                    $('#cart-count-header').text(response.cart_count);
                    $('.cart-count').text(response.cart_count);
                } else {
                    showToast(response.message || 'Erreur lors de l\'ajout', 'error');
                }
            },
            error: function() {
                showToast('Erreur lors de l\'ajout au panier', 'error');
            }
        });
    });
    
    // Add to wishlist (delegated)
    $(document).on('click', '.add-to-wishlist', function(e) {
        e.preventDefault();
        let productId = $(this).data('product');
        
        $.ajax({
            url: '<?= base_url("home/addToWishlist") ?>',
            type: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showToast('Produit ajouté à votre liste de souhaits', 'success');
                    updateWishlistCount();
                    $(this).find('i').removeClass('ri-heart-3-line').addClass('ri-heart-fill');
                } else {
                    showToast(response.message || 'Erreur', 'info');
                }
            }.bind(this),
            error: function() {
                showToast('Erreur', 'error');
            }
        });
    });
    
    // AJAX filter function
    function applyFiltersAjax() {
        if (isAjaxLoading) return;
        isAjaxLoading = true;
        
        $('#loadingSpinner').show();
        $('#productsContainer').addClass('opacity-50');
        
        let filterData = {};
        if (currentFilters.category) filterData.category = currentFilters.category;
        if (currentFilters.min_price) filterData.min_price = currentFilters.min_price;
        if (currentFilters.max_price) filterData.max_price = currentFilters.max_price;
        if (currentFilters.brands && currentFilters.brands.length) filterData.brands = currentFilters.brands;
        if (currentFilters.rating) filterData.rating = currentFilters.rating;
        if (currentFilters.sort && currentFilters.sort !== 'newest') filterData.sort = currentFilters.sort;
        if (currentFilters.page > 1) filterData.page = currentFilters.page;
        
        // Update URL without reload
        let urlParams = new URLSearchParams();
        for (let key in filterData) {
            if (filterData[key] !== null && filterData[key] !== undefined && filterData[key] !== '') {
                urlParams.set(key, filterData[key]);
            }
        }
        let newUrl = '<?= base_url("shop") ?>' + (urlParams.toString() ? '?' + urlParams.toString() : '');
        window.history.pushState({ filters: currentFilters }, '', newUrl);
        
        $.ajax({
            url: '<?= base_url("home/ajax_filter_products") ?>',
            type: 'POST',
            data: filterData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#productsContainer').html(response.products_html);
                    $('#paginationContainer').html(response.pagination_html);
                    
                    // Update category counts
                    if (response.category_counts) {
                        for (let catId in response.category_counts) {
                            $('#cat-count-' + catId).text('(' + response.category_counts[catId] + ')');
                        }
                    }
                } else {
                    $('#productsContainer').html('<div class="empty-shop-state text-center py-5"><div class="empty-state-icon mb-4"><i class="ri-shopping-bag-line" style="font-size: 80px; color: #ccc;"></i></div><h4>Aucun produit trouvé</h4><p class="text-muted">' + (response.message || 'Aucun produit ne correspond à vos critères.') + '</p><a href="<?= base_url('shop') ?>" class="btn btn-primary mt-3">Voir tous les produits</a></div>');
                    $('#paginationContainer').html('');
                }
            },
            error: function() {
                showToast('Erreur lors du chargement des produits', 'error');
            },
            complete: function() {
                isAjaxLoading = false;
                $('#loadingSpinner').hide();
                $('#productsContainer').removeClass('opacity-50');
                initProductHover();
            }
        });
    }
    
    function initProductHover() {
        // Re-initialize any product hover effects
        $('.product-box-4-main').each(function() {
            let mainDiv = $(this);
            let selectOptionBox = mainDiv.find('.select-option-box');
            
            mainDiv.on('mouseenter', function() {
                selectOptionBox.addClass('show');
            });
            mainDiv.on('mouseleave', function() {
                selectOptionBox.removeClass('show');
            });
        });
    }
    
    function showToast(message, type) {
        let toastHtml = '<div class="toast-notification ' + type + '">' + message + '</div>';
        $('body').append(toastHtml);
        setTimeout(function() {
            $('.toast-notification').fadeOut(300, function() { $(this).remove(); });
        }, 3000);
    }
    
    function updateCartCount() {
        $.get('<?= base_url("home/getCartCount") ?>', function(data) {
            $('.cart-count').text(data);
        });
    }
    
    function updateWishlistCount() {
        $.get('<?= base_url("home/getWishlistCount") ?>', function(data) {
            $('.wishlist-count').text(data);
        });
    }
    
    function closeSidebar() {
        $('.select-option-box').removeClass('show');
    }
    
    initProductHover();
    
    // Category search
    $('#categorySearch').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $('#categoryList li').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Grid view
    let currentGrid = localStorage.getItem('productGrid') || 5;
    $('.grid-btn[data-grid="' + currentGrid + '"]').parent().addClass('active');
    $('.product-list-section').removeClass('row-cols-xxl-5 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2')
        .addClass('row-cols-xxl-' + currentGrid + ' row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2');
    
    $('.grid-btn').on('click', function() {
        let grid = $(this).data('grid');
        localStorage.setItem('productGrid', grid);
        $('.grid-option ul li').removeClass('active');
        $(this).parent().addClass('active');
        $('.product-list-section').removeClass('row-cols-xxl-5 row-cols-xxl-4 row-cols-xxl-3 row-cols-xxl-2')
            .addClass('row-cols-xxl-' + grid);
    });
    
    // Back button
    $('.back-button').on('click', function() {
        window.history.back();
    });
});
</script>

<style>
.opacity-50 { opacity: 0.5; transition: opacity 0.3s; }
.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    z-index: 9999;
    animation: slideIn 0.3s ease;
}
.toast-notification.error { background: #dc3545; }
.toast-notification.info { background: #17a2b8; }
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
.empty-shop-state {
    background: #f9f9f9;
    border-radius: 10px;
    padding: 60px 20px;
}
.clear-all {
    font-size: 14px;
    color: #ff6b6b;
    text-decoration: none;
}
.clear-all:hover { text-decoration: underline; }
.price-range-slider { padding: 10px 5px; }
</style>