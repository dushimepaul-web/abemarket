<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4>Mes Produits</h4>
                <a href="<?= base_url('ProduitsVendeur/add') ?>" class="btn btn-primary">
                    <i class="ri-add-line"></i> Ajouter un produit
                </a>
            </div>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h3><?= $total_produits ?></h3>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-success"><?= $produits_actifs ?></h3>
                        <small class="text-muted">Actifs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-secondary"><?= $produits_inactifs ?></h3>
                        <small class="text-muted">Inactifs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-warning"><?= $produits_stock_bas ?></h3>
                        <small class="text-muted">Stock bas</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="text-danger"><?= $rupture_stock ?></h3>
                        <small class="text-muted">Rupture stock</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (empty($produits)): ?>
                    <div class="text-center py-5">
                        <i class="ri-shopping-bag-line" style="font-size: 48px; color: #ccc;"></i>
                        <p class="mt-3 text-muted">Aucun produit trouvé</p>
                        <a href="<?= base_url('ProduitsVendeur/add') ?>" class="btn btn-primary mt-2">Ajouter votre premier produit</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produits as $p): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($p['image_url'])): ?>
                                                <img src="<?= base_url($p['image_url']) ?>" alt="" width="50" height="50" style="object-fit: cover; border-radius: 8px;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width:50px;height:50px;border-radius:8px;">
                                                    <i class="ri-image-line text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($p['nom_produit']) ?></strong>
                                            <br><small class="text-muted"><?= htmlspecialchars($p['sku'] ?? '') ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($p['nom_categorie'] ?? '-') ?></td>
                                        <td>
                                            <strong><?= number_format($p['prix_base'], 0, ',', '.') ?> FC</strong>
                                            <?php if (!empty($p['prix_promo'])): ?>
                                                <br><small class="text-danger"><?= number_format($p['prix_promo'], 0, ',', '.') ?> FC</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="<?= $p['statut_stock'] == 'rupture_stock' ? 'text-danger' : ($p['statut_stock'] == 'stock_bas' ? 'text-warning' : 'text-success') ?>">
                                                <?= $p['quantite_actuelle'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($p['statut'] == 'actif'): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php elseif ($p['statut'] == 'brouillon'): ?>
                                                <span class="badge bg-secondary">Brouillon</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning"><?= htmlspecialchars($p['statut']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= base_url('ProduitsVendeur/view/' . $p['slug_produit']) ?>" class="btn btn-outline-info" title="Voir"><i class="ri-eye-line"></i></a>
                                                <a href="<?= base_url('ProduitsVendeur/edit/' . $p['slug_produit']) ?>" class="btn btn-outline-primary" title="Modifier"><i class="ri-edit-line"></i></a>
                                                <a href="<?= base_url('ProduitsVendeur/images/' . $p['slug_produit']) ?>" class="btn btn-outline-secondary" title="Images"><i class="ri-image-line"></i></a>
                                                <a href="<?= base_url('ProduitsVendeur/variantes/' . $p['slug_produit']) ?>" class="btn btn-outline-warning" title="Variantes"><i class="ri-stack-line"></i></a>
                                                <a href="<?= base_url('ProduitsVendeur/delete/' . $p['slug_produit']) ?>" class="btn btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer ce produit ?')"><i class="ri-delete-bin-line"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
