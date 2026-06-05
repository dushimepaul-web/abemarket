<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2><?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('category/' . ($product['slug_categorie'] ?? '')); ?>">
                            <?= htmlspecialchars($product['nom_categorie'] ?? 'Catégorie', ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars(substr($product['nom_produit'], 0, 50), ENT_QUOTES, 'UTF-8'); ?></li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Product Left Sidebar Start -->
<section class="product-section section-t-space">
    <div class="custom-container">
        <div class="row">
            <div class="col-xxl-9 col-xl-8 col-lg-7">
                <div class="left-card">
                    <div class="row g-xxl-5 g-md-4 g-3">
                        <!-- Galerie d'images -->
                        <div class="col-xxl-6">
                            <div class="product-left-box">
                                <div class="row g-sm-4 g-2">
                                    <div class="col-12">
                                        <div class="swiper product-original-slider product-original-box">
                                            <div class="swiper-wrapper">
                                                <?php if(!empty($images)): ?>
                                                    <?php foreach($images as $img): ?>
                                                    <div class="swiper-slide">
                                                        <div class="slider-image slider-image-2">
                                                            <img src="<?= base_url($img['url_image']); ?>" class="img-fluid" alt="<?= htmlspecialchars($img['texte_alt'] ?? $product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="swiper-slide">
                                                        <div class="slider-image slider-image-2">
                                                            <img src="<?= base_url('assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="Image non disponible">
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="swiper thumbnail-product-slider product-thumbnail-box">
                                            <div class="swiper-wrapper">
                                                <?php if(!empty($images)): ?>
                                                    <?php foreach($images as $img): ?>
                                                    <div class="swiper-slide">
                                                        <div class="sidebar-image sidebar-image-2">
                                                            <img src="<?= base_url($img['url_image']); ?>" class="img-fluid" alt="">
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="swiper-slide">
                                                        <div class="sidebar-image sidebar-image-2">
                                                            <img src="<?= base_url('assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="">
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Infos produit -->
                        <div class="col-xxl-6 border-left-cls">
                            <div class="right-box-contain">
                                <!-- Stats -->
                                <div class="product-count">
                                    <ul>
                                        <li>
                                            <i class="ri-flashlight-line"></i>
                                            <h3><?= number_format($product['nombre_ventes'] ?? 0); ?> Commandes</h3>
                                        </li>
                                        <li>
                                            <i class="ri-eye-line"></i>
                                            <h3><?= number_format($product['nombre_vues'] ?? 0); ?> Vues</h3>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Vendeur -->
                                <?php if(!empty($seller)): ?>
                                <h4 class="offer-top">Visitez <a href="<?= base_url('seller/' . $seller['slug_boutique']); ?>"><?= htmlspecialchars($seller['nom_boutique'], ENT_QUOTES, 'UTF-8'); ?></a></h4>
                                <?php endif; ?>

                                <!-- Nom produit -->
                                <h5 class="name"><?= htmlspecialchars($product['nom_produit'], ENT_QUOTES, 'UTF-8'); ?></h5>

                                <!-- Prix -->
                                <h6 class="product-price">
                                    <?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF
                                    <?php if(!empty($product['prix_promo']) && $product['prix_promo'] < $product['prix_base']): ?>
                                    <del><?= number_format($product['prix_base'], 0, ',', ' '); ?> BIF</del>
                                    <?php endif; ?>
                                </h6>

                                <!-- Notes et avis -->
                                <div class="price-rating">
                                    <ul class="rating-review-sold-box">
                                        <li>
                                            <h3><i class="ri-star-fill"></i> <?= number_format($averageRating ?? $product['note_moyenne'] ?? 0, 1); ?> / 5</h3>
                                        </li>
                                        <li></li>
                                        <li>
                                            <h3><?= number_format($totalReviews ?? $product['nombre_avis'] ?? 0); ?>+ Avis</h3>
                                        </li>
                                        <li></li>
                                        <li>
                                            <h3><?= number_format($product['nombre_ventes'] ?? 0); ?>+ Vendus</h3>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Description courte -->
                                <div class="product-contain">
                                    <p><?= nl2br(htmlspecialchars($product['description_courte'] ?? substr(strip_tags($product['description'] ?? ''), 0, 200), ENT_QUOTES, 'UTF-8')); ?></p>
                                </div>

                                <!-- Variantes (Taille, Couleur, etc.) -->
                                <?php if(!empty($variants)): ?>
                                <div class="product-package product-spacing">
                                    <div class="product-title">
                                        <h4>Variantes :</h4>
                                    </div>
                                    <form class="select-package circle-package" id="variantForm">
                                        <?php 
                                        $attributes = [];
                                        foreach($variants as $variant):
                                            $attrs = json_decode($variant['attributs_variante'], true);
                                            if($attrs):
                                                foreach($attrs as $key => $val):
                                                    if(!isset($attributes[$key])) $attributes[$key] = [];
                                                    if(!in_array($val, $attributes[$key])) $attributes[$key][] = $val;
                                                endforeach;
                                            endif;
                                        endforeach;
                                        
                                        foreach($attributes as $attrName => $attrValues):
                                        ?>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold"><?= ucfirst($attrName); ?> :</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php foreach($attrValues as $val): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input variant-option" type="radio" name="<?= $attrName; ?>" value="<?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?>" data-attribute="<?= $attrName; ?>">
                                                    <label class="form-check-label"><?= htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?></label>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <input type="hidden" id="selectedVariantId" value="">
                                    </form>
                                </div>
                                <?php endif; ?>

                                <!-- Stock restant -->
                                <div class="hurry-up-box">
                                    <h5>Il reste <span class="theme-color" id="stockCount"><?= $product['quantite_actuelle'] ?? 0; ?></span> articles en stock, dépêchez-vous !</h5>
                                    <div class="progress">
                                        <?php 
                                        $maxStock = ($product['quantite_actuelle'] ?? 0) + ($product['nombre_ventes'] ?? 0);
                                        $stockPercent = $maxStock > 0 ? (($product['nombre_ventes'] ?? 0) / $maxStock) * 100 : 0;
                                        ?>
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: <?= min($stockPercent, 100); ?>%"></div>
                                    </div>
                                </div>

                                <!-- Quantité -->
                                <div class="text-md-start text-center">
                                    <div class="qty-box h-100 qty-container quantity-box-2">
                                        <button class="btn qty-btn qty-btn-minus" id="qtyMinus">
                                            <i class="ri-subtract-line"></i>
                                        </button>
                                        <input type="number" readonly name="qty" class="qty-input form-control input-qty" id="productQty" value="1" min="1" max="<?= $product['quantite_actuelle'] ?? 99; ?>">
                                        <button class="btn qty-btn qty-btn-plus" id="qtyPlus">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Boutons action -->
                                <div class="button-group d-lg-none mt-3">
                                    <button class="btn buy-btn theme-bg-color text-white" id="buyNowBtn">Acheter maintenant</button>
                                    <button class="btn buy-btn-2 theme-border fw-500 add-to-cart-main" data-product-id="<?= $product['id_produit']; ?>">
                                        <i class="ri-shopping-bag-line"></i> Ajouter au panier
                                    </button>
                                </div>

                                <!-- Liens utiles -->
                                <ul class="size-delivery-info">
                                    <li>
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#sizeChartModal">
                                            <i class="ri-ruler-line"></i>
                                            <span>Guide des tailles</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#deliveryModal">
                                            <i class="ri-truck-line"></i>
                                            <span>Livraison & Retour</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#questionModal">
                                            <i class="ri-questionnaire-line"></i>
                                            <span>Poser une question</span>
                                        </button>
                                    </li>
                                </ul>

                                <!-- Détails produit -->
                                <div class="about-item-box product-spacing border-top-space">
                                    <div class="product-title">
                                        <h4>À propos :</h4>
                                    </div>
                                    <ul class="about-item-list">
                                        <li>Marque : <span><?= htmlspecialchars($product['marque'] ?? 'Non spécifiée', ENT_QUOTES, 'UTF-8'); ?></span></li>
                                        <li>Catégorie : <span><?= htmlspecialchars($product['nom_categorie'] ?? 'Non spécifiée', ENT_QUOTES, 'UTF-8'); ?></span></li>
                                        <li>État : <span>Neuf</span></li>
                                        <li>Référence : <span><?= htmlspecialchars($product['sku'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span></li>
                                        <li>Code produit : <span><?= htmlspecialchars($product['code_produit'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span></li>
                                    </ul>
                                </div>

                                <!-- Description -->
                                <div class="description-box product-spacing border-top-space">
                                    <div class="product-title">
                                        <h4>Description :</h4>
                                    </div>
                                    <div class="description-list">
                                        <?= $product['description'] ?? '<p>Aucune description disponible pour ce produit.</p>'; ?>
                                    </div>
                                </div>

                                <!-- Livraison -->
                                <div class="shipping-info-box product-spacing border-top-space">
                                    <div class="product-title">
                                        <h4>Informations de livraison</h4>
                                    </div>
                                    <ul class="shipping-info-list">
                                        <li>Livraison : <span>Expédition depuis le Burundi</span></li>
                                        <li>Frais : <span>Livraison gratuite à partir de 50 000 BIF</span></li>
                                        <li>Délai : <span>Livraison estimée entre 3 et 7 jours ouvrés</span></li>
                                    </ul>
                                </div>

                                <!-- Paiement sécurisé -->
                                <div class="payment-option product-spacing border-top-space">
                                    <div class="product-title">
                                        <h4>Paiement sécurisé</h4>
                                    </div>
                                    <ul>
                                        <li><a href="#!"><img src="<?= base_url('assets/frontend/images/payment/1.svg'); ?>" alt="Visa"></a></li>
                                        <li><a href="#!"><img src="<?= base_url('assets/frontend/images/payment/2.svg'); ?>" alt="Mastercard"></a></li>
                                        <li><a href="#!"><img src="<?= base_url('assets/frontend/images/payment/3.svg'); ?>" alt="PayPal"></a></li>
                                        <li><a href="#!"><img src="<?= base_url('assets/frontend/images/payment/4.svg'); ?>" alt="American Express"></a></li>
                                    </ul>
                                </div>

                                <!-- Partage -->
                                <div class="social-option product-spacing border-top-space">
                                    <div class="product-title">
                                        <h4>Partager</h4>
                                    </div>
                                    <ul>
                                        <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()); ?>" target="_blank"><i class="ri-facebook-fill"></i></a></li>
                                        <li><a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()); ?>&text=<?= urlencode($product['nom_produit']); ?>" target="_blank"><i class="ri-twitter-x-line"></i></a></li>
                                        <li><a href="https://wa.me/?text=<?= urlencode($product['nom_produit'] . ' - ' . current_url()); ?>" target="_blank"><i class="ri-whatsapp-fill"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar droite -->
            <div class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block">
                <div class="right-sidebar-box">
                    <div class="side-product-detail">
                        <div class="side-title">
                            <h4>Récapitulatif</h4>
                        </div>

                        <div class="side-product-box">
                            <div class="product-image">
                                <img src="<?= base_url($images[0]['url_image'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="">
                            </div>
                            <div class="product-contain">
                                <h5>Quantité</h5>
                                <h4 id="sidebarQty">1</h4>
                            </div>
                        </div>

                        <div class="total-price-box">
                            <h4><span>Prix total :</span> <span id="sidebarTotalPrice"><?= number_format($product['prix_promo'] ?? $product['prix_base'], 0, ',', ' '); ?> BIF</span></h4>
                        </div>

                        <div class="button-group">
                            <button class="btn buy-btn theme-bg-color text-white w-100 mb-2" id="buyNowBtnSidebar">Acheter maintenant</button>
                            <button class="btn buy-btn theme-border fw-500 w-100 add-to-cart-main" data-product-id="<?= $product['id_produit']; ?>">
                                <i class="ri-shopping-bag-line"></i> Ajouter au panier
                            </button>
                        </div>

                        <div class="seller-product mt-3">
                            <h5><a href="#"><i class="ri-message-2-fill"></i> Contacter le vendeur</a></h5>
                            <h5><a href="#" id="shareProductBtn"><i class="ri-share-fill"></i> Partager</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Onglets Description / Avis -->
<section class="section-t-space">
    <div class="custom-container">
        <div class="product-section-box m-0">
            <ul class="nav nav-tabs custom-nav" id="myTab">
                <li class="nav-item">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">Description</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button">Spécifications</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button">Avis (<?= $totalReviews ?? 0; ?>)</button>
                </li>
            </ul>

            <div class="tab-content custom-tab" id="myTabContent">
                <!-- Description -->
                <div class="tab-pane fade active show" id="description">
                    <div class="product-description">
                        <?= $product['description'] ?? '<p>Aucune description disponible.</p>'; ?>
                    </div>
                </div>

                <!-- Spécifications -->
                <div class="tab-pane fade" id="specs">
                    <div class="table-responsive">
                        <table class="table info-table">
                            <tbody>
                                <tr><th>Marque</th><td><?= htmlspecialchars($product['marque'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                <tr><th>Catégorie</th><td><?= htmlspecialchars($product['nom_categorie'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                <tr><th>Référence</th><td><?= htmlspecialchars($product['sku'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                <tr><th>Code produit</th><td><?= htmlspecialchars($product['code_produit'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></td></tr>
                                <tr><th>Stock disponible</th><td><?= number_format($product['quantite_actuelle'] ?? 0); ?> unités</td></tr>
                                <?php if(!empty($product['poids_kg'])): ?>
                                <tr><th>Poids</th><td><?= $product['poids_kg']; ?> kg</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Avis -->
                <div class="tab-pane fade" id="review">
                    <div class="review-box">
                        <div class="row g-xl-5 g-md-4 g-3">
                            <!-- Statistiques avis -->
                            <div class="col-xl-6 b-end">
                                <div class="review-title"><h4>Avis clients</h4></div>
                                <div class="customer-review-box">
                                    <h5><?= number_format($averageRating ?? $product['note_moyenne'] ?? 0, 1); ?> <span>/5</span></h5>
                                    <div class="product-rating">
                                        <ul class="rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                            <li class="<?= $i <= round($averageRating ?? $product['note_moyenne'] ?? 0) ? 'theme-color' : ''; ?>">
                                                <i class="ri-star-fill <?= $i <= round($averageRating ?? $product['note_moyenne'] ?? 0) ? 'fill' : ''; ?>"></i>
                                            </li>
                                            <?php endfor; ?>
                                        </ul>
                                        <h6><?= number_format($totalReviews ?? 0); ?> évaluations</h6>
                                    </div>
                                </div>

                                <div class="rating-box">
                                    <ul>
                                        <?php 
                                        $total = $totalReviews ?? 1;
                                        for($star = 5; $star >= 1; $star--):
                                            $count = $ratingDistribution[$star] ?? 0;
                                            $percent = $total > 0 ? round(($count / $total) * 100) : 0;
                                        ?>
                                        <li>
                                            <div class="rating-list">
                                                <h5><?= $star; ?> Étoile</h5>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: <?= $percent; ?>%"><?= $percent; ?>%</div>
                                                </div>
                                            </div>
                                        </li>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>

                            <!-- Formulaire ajout avis -->
                            <div class="col-xl-6">
                                <div class="review-title"><h4 class="fw-500">Ajouter un avis</h4></div>
                                <?php if($hasPurchased && !$hasReviewed): ?>
                                <form id="addReviewForm">
                                    <input type="hidden" name="product_id" value="<?= $product['id_produit']; ?>">
                                    <div class="row g-sm-4 g-3">
                                        <div class="col-12">
                                            <div class="review-form-box theme-form">
                                                <label class="form-label">Note</label>
                                                <div class="rating-input">
                                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                                    <input type="radio" name="rating" value="<?= $i; ?>" id="star<?= $i; ?>">
                                                    <label for="star<?= $i; ?>"><i class="ri-star-line"></i></label>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="review-form-box theme-form">
                                                <label class="form-label">Votre commentaire</label>
                                                <textarea class="form-control" name="comment" rows="4" placeholder="Partagez votre expérience..." required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn theme-bg-color text-light">Publier l'avis</button>
                                        </div>
                                    </div>
                                </form>
                                <?php elseif($hasReviewed): ?>
                                <div class="alert alert-success">Vous avez déjà laissé un avis sur ce produit.</div>
                                <?php elseif(!$hasPurchased): ?>
                                <div class="alert alert-info">Vous devez acheter ce produit pour laisser un avis.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Liste des avis -->
                        <div class="product-review-box mt-4">
                            <div class="review-title">
                                <h4 class="fw-500">Avis des clients</h4>
                            </div>
                            <div class="review-people">
                                <ul class="review-list" id="reviewsList">
                                    <?php if(!empty($reviews)): ?>
                                        <?php foreach($reviews as $review): ?>
                                        <li>
                                            <div class="people-box">
                                                <div><div class="people-image"><img src="<?= base_url($review['avatar_url'] ?? 'assets/frontend/images/avatar/default.png'); ?>" class="img-fluid" alt=""></div></div>
                                                <div class="people-comment">
                                                    <div class="name">
                                                        <a href="#"><?= htmlspecialchars($review['prenom'] . ' ' . $review['nom'], ENT_QUOTES, 'UTF-8'); ?></a>
                                                        <div class="product-rating">
                                                            <ul class="rating">
                                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                                <li><i class="ri-star-fill <?= $i <= $review['note'] ? 'fill' : ''; ?>"></i></li>
                                                                <?php endfor; ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="date-time"><h5 class="text-content h6"><?= date('d/m/Y', strtotime($review['date_creation'])); ?></h5></div>
                                                    <div class="reply"><p><?= nl2br(htmlspecialchars($review['commentaire'], ENT_QUOTES, 'UTF-8')); ?></p></div>
                                                </div>
                                            </div>
                                        </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="text-center py-4">Aucun avis pour le moment. Soyez le premier à donner votre avis !</li>
                                    <?php endif; ?>
                                </ul>
                                <?php if(($totalReviewPages ?? 0) > 1): ?>
                                <nav><ul class="pagination justify-content-center"><?php for($i = 1; $i <= $totalReviewPages; $i++): ?><li class="page-item <?= $i == $currentReviewPage ? 'active' : ''; ?>"><a class="page-link review-page-link" href="?review_page=<?= $i; ?>"><?= $i; ?></a></li><?php endfor; ?></ul></nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produits similaires -->
<?php if(!empty($similarProducts)): ?>
<section class="product-list-section section-block-space">
    <div class="custom-container">
        <div class="related-title"><h2>Produits similaires</h2></div>
        <div class="swiper related-products product-option-box slider-pagination-lg">
            <div class="swiper-wrapper">
                <?php foreach($similarProducts as $similar): ?>
                <div class="swiper-slide">
                    <div class="productMain product-box-4 pro-bg-white">
                        <div class="product-image">
                            <a href="<?= base_url('product/' . $similar['slug_produit']); ?>">
                                <img src="<?= base_url($similar['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt="<?= htmlspecialchars($similar['nom_produit'], ENT_QUOTES, 'UTF-8'); ?>">
                            </a>
                        </div>
                        <div class="product-content">
                            <a href="<?= base_url('product/' . $similar['slug_produit']); ?>" class="name"><h5><?= htmlspecialchars(substr($similar['nom_produit'], 0, 40), ENT_QUOTES, 'UTF-8'); ?></h5></a>
                            <ul class="rating"><?php for($i = 1; $i <= 5; $i++): ?><li><i class="ri-star-fill <?= $i <= round($similar['note_moyenne'] ?? 0) ? 'fill' : ''; ?>"></i></li><?php endfor; ?></ul>
                            <h5 class="price"><?= number_format($similar['prix_promo'] ?? $similar['prix_base'], 0, ',', ' '); ?> BIF <?php if(!empty($similar['prix_promo'])): ?><del><?= number_format($similar['prix_base'], 0, ',', ' '); ?> BIF</del><?php endif; ?></h5>
                            <button class="btn add-to-cart-btn w-100 mt-2" data-product-id="<?= $similar['id_produit']; ?>">Ajouter au panier</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Produits récents -->
<?php if(!empty($recentProducts) && count($recentProducts) > 1): ?>
<section class="product-list-section section-t-space">
    <div class="custom-container">
        <div class="related-title"><h2>Récemment consultés</h2></div>
        <div class="row g-4">
            <?php foreach($recentProducts as $recent): if($recent['id_produit'] == $product['id_produit']) continue; ?>
            <div class="col-lg-3 col-md-4 col-6">
                <div class="product-box-4 pro-bg-white">
                    <div class="product-image"><a href="<?= base_url('product/' . $recent['slug_produit']); ?>"><img src="<?= base_url($recent['image_url'] ?? 'assets/frontend/images/product/placeholder.png'); ?>" class="img-fluid" alt=""></a></div>
                    <div class="product-content"><a href="<?= base_url('product/' . $recent['slug_produit']); ?>" class="name"><h6><?= htmlspecialchars(substr($recent['nom_produit'], 0, 35), ENT_QUOTES, 'UTF-8'); ?></h6></a><h5 class="price"><?= number_format($recent['prix_promo'] ?? $recent['prix_base'], 0, ',', ' '); ?> BIF</h5></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- JavaScript pour la page produit -->
<script>
$(document).ready(function() {
    // Gestion de la quantité
    let currentQty = 1;
    const maxQty = <?= $product['quantite_actuelle'] ?? 99; ?>;
    const basePrice = <?= $product['prix_promo'] ?? $product['prix_base']; ?>;
    
    $('#qtyPlus').click(function() {
        if(currentQty < maxQty) {
            currentQty++;
            $('#productQty').val(currentQty);
            $('#sidebarQty').text(currentQty);
            updateTotalPrice();
        }
    });
    
    $('#qtyMinus').click(function() {
        if(currentQty > 1) {
            currentQty--;
            $('#productQty').val(currentQty);
            $('#sidebarQty').text(currentQty);
            updateTotalPrice();
        }
    });
    
    function updateTotalPrice() {
        let total = basePrice * currentQty;
        $('#sidebarTotalPrice').text(new Intl.NumberFormat('fr-FR').format(total) + ' BIF');
    }
    
    // Gestion des variantes
    $('.variant-option').change(function() {
        let selectedAttrs = {};
        $('.variant-option:checked').each(function() {
            selectedAttrs[$(this).data('attribute')] = $(this).val();
        });
        
        $.ajax({
            url: '<?= base_url("home/getVariantPrice"); ?>',
            type: 'POST',
            data: { product_id: <?= $product['id_produit']; ?>, attributes: selectedAttrs },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#selectedVariantId').val(response.variant_id);
                    $('.product-price').html(response.price.toLocaleString('fr-FR') + ' BIF' + (response.old_price ? ' <del>' + response.old_price.toLocaleString('fr-FR') + ' BIF</del>' : ''));
                    updateTotalPrice();
                }
            }
        });
    });
    
    // Ajout au panier
    $('.add-to-cart-main, .add-to-cart-btn').click(function(e) {
        e.preventDefault();
        let productId = $(this).data('product-id');
        let variantId = $('#selectedVariantId').val();
        let quantity = currentQty;
        
        $.ajax({
            url: '<?= base_url("home/addToCart"); ?>',
            type: 'POST',
            data: { product_id: productId, variant_id: variantId, quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#cart-count-header').text(response.cart_count);
                    Swal.fire({ icon: 'success', title: 'Ajouté !', text: 'Produit ajouté au panier', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Erreur', text: response.message });
                }
            }
        });
    });
    
    // Acheter maintenant
    $('#buyNowBtn, #buyNowBtnSidebar').click(function() {
        let variantId = $('#selectedVariantId').val();
        let quantity = currentQty;
        window.location.href = '<?= base_url("checkout"); ?>?product_id=<?= $product['id_produit']; ?>&variant_id=' + variantId + '&quantity=' + quantity;
    });
    
    // Ajout d'avis
    $('#addReviewForm').submit(function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        
        $.ajax({
            url: '<?= base_url("home/addProductReview"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    Swal.fire({ icon: 'success', title: 'Merci !', text: 'Votre avis a été soumis et sera publié après validation.', timer: 3000, showConfirmButton: false });
                    location.reload();
                } else {
                    Swal.fire({ icon: 'error', title: 'Erreur', text: response.message });
                }
            }
        });
    });
});
</script>

<style>
.rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; }
.rating-input input { display: none; }
.rating-input label { font-size: 24px; cursor: pointer; color: #ddd; }
.rating-input input:checked ~ label,
.rating-input label:hover,
.rating-input label:hover ~ label { color: #ffc107; }
.stock-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
.stock-badge.en-stock { background: #d4edda; color: #155724; }
.stock-badge.stock-bas { background: #fff3cd; color: #856404; }
.stock-badge.rupture { background: #f8d7da; color: #721c24; }
</style>