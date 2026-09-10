<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">
        <div class="row mb-3">
            <div class="col-12">
                <a href="<?= base_url('ProduitsVendeur') ?>" class="text-decoration-none"><i class="ri-arrow-left-line"></i> Retour</a>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h4><?= htmlspecialchars($produit['nom_produit']) ?></h4>
                    <div>
                        <a href="<?= base_url('ProduitsVendeur/edit/' . $produit['slug_produit']) ?>" class="btn btn-primary"><i class="ri-edit-line"></i> Modifier</a>
                        <a href="<?= base_url('ProduitsVendeur/images/' . $produit['slug_produit']) ?>" class="btn btn-secondary"><i class="ri-image-line"></i> Images</a>
                        <a href="<?= base_url('ProduitsVendeur/variantes/' . $produit['slug_produit']) ?>" class="btn btn-warning"><i class="ri-stack-line"></i> Variantes</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header"><h5>Détails</h5></div>
                    <div class="card-body">
                        <table class="table">
                            <tr><th width="150">SKU</th><td><?= htmlspecialchars($produit['sku'] ?? '-') ?></td></tr>
                            <tr><th>Code produit</th><td><?= htmlspecialchars($produit['code_produit'] ?? '-') ?></td></tr>
                            <tr><th>Catégorie</th><td><?= htmlspecialchars($produit['nom_categorie'] ?? '-') ?></td></tr>
                            <tr><th>Marque</th><td><?= htmlspecialchars($produit['marque'] ?? '-') ?></td></tr>
                            <tr><th>Prix base</th><td><strong><?= number_format($produit['prix_base'], 0, ',', '.') ?> FC</strong></td></tr>
                            <tr><th>Prix promo</th><td><?= !empty($produit['prix_promo']) ? number_format($produit['prix_promo'], 0, ',', '.') . ' FC' : '-' ?></td></tr>
                            <tr><th>Stock</th><td>
                                <span class="<?= $produit['statut_stock'] == 'rupture_stock' ? 'text-danger' : ($produit['statut_stock'] == 'stock_bas' ? 'text-warning' : 'text-success') ?>">
                                    <?= $produit['quantite_actuelle'] ?> unités (seuil: <?= $produit['seuil_stock_bas'] ?>)
                                </span>
                            </td></tr>
                            <tr><th>Type</th><td><?= htmlspecialchars($produit['type_produit'] ?? 'simple') ?></td></tr>
                            <tr><th>Statut</th><td>
                                <?php if ($produit['statut'] == 'actif'): ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($produit['statut']) ?></span>
                                <?php endif; ?>
                            </td></tr>
                            <tr><th>Publication</th><td><?= $produit['date_publication'] ? date('d/m/Y H:i', strtotime($produit['date_publication'])) : 'Non publié' ?></td></tr>
                        </table>
                    </div>
                </div>

                <?php if (!empty($produit['description'])): ?>
                <div class="card mb-4">
                    <div class="card-header"><h5>Description</h5></div>
                    <div class="card-body"><?= nl2br(htmlspecialchars($produit['description'])) ?></div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header"><h5>Images</h5></div>
                    <div class="card-body">
                        <?php if (empty($images)): ?>
                            <p class="text-muted text-center">Aucune image</p>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($images as $img): ?>
                                    <div class="col-6 mb-2">
                                        <img src="<?= base_url($img['url_image']) ?>" alt="" class="img-fluid rounded" style="height:100px; object-fit:cover; width:100%;">
                                        <?php if (!empty($img['est_principale'])): ?>
                                            <span class="badge bg-primary">Principale</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
