<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">
        <div class="row mb-3">
            <div class="col-12">
                <a href="<?= base_url('ProduitsVendeur') ?>" class="text-decoration-none"><i class="ri-arrow-left-line"></i> Retour à mes produits</a>
                <h4 class="mt-2"><?= $is_edit ? 'Modifier le produit' : 'Ajouter un produit' ?></h4>
            </div>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url($is_edit ? 'ProduitsVendeur/edit/' . $produit['slug_produit'] : 'ProduitsVendeur/add') ?>" method="POST" enctype="multipart/form-data">
            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Informations générales</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nom du produit *</label>
                            <input type="text" class="form-control" name="nom_produit" value="<?= htmlspecialchars($produit['nom_produit'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Catégorie *</label>
                            <select class="form-control" name="id_categorie" required>
                                <option value="">-- Choisir --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id_categorie'] ?>" <?= ($produit['id_categorie'] ?? '') == $cat['id_categorie'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Prix de base (FC) *</label>
                            <input type="number" class="form-control" name="prix_base" value="<?= $produit['prix_base'] ?? '' ?>" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Prix promotionnel (FC)</label>
                            <input type="number" class="form-control" name="prix_promo" value="<?= $produit['prix_promo'] ?? '' ?>" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Marque</label>
                            <input type="text" class="form-control" name="marque" value="<?= htmlspecialchars($produit['marque'] ?? '') ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description courte</label>
                            <input type="text" class="form-control" name="description_courte" value="<?= htmlspecialchars($produit['description_courte'] ?? '') ?>" maxlength="255">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4"><?= htmlspecialchars($produit['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Stock & Livraison</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantité en stock *</label>
                            <input type="number" class="form-control" name="quantite_actuelle" value="<?= $produit['quantite_actuelle'] ?? 0 ?>" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Seuil stock bas</label>
                            <input type="number" class="form-control" name="seuil_stock_bas" value="<?= $produit['seuil_stock_bas'] ?? 5 ?>" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Poids (kg)</label>
                            <input type="number" step="0.01" class="form-control" name="poids_kg" value="<?= $produit['poids_kg'] ?? '' ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Type de produit</label>
                            <select class="form-control" name="type_produit">
                                <option value="simple" <?= ($produit['type_produit'] ?? 'simple') == 'simple' ? 'selected' : '' ?>>Simple</option>
                                <option value="variable" <?= ($produit['type_produit'] ?? '') == 'variable' ? 'selected' : '' ?>>Variable (taille/couleur)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Statut</label>
                            <select class="form-control" name="statut">
                                <option value="brouillon" <?= ($produit['statut'] ?? 'brouillon') == 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                                <option value="actif" <?= ($produit['statut'] ?? '') == 'actif' ? 'selected' : '' ?>>Actif</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="est_actif" value="1" id="est_actif" <?= ($produit['est_actif'] ?? 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="est_actif">Produit actif</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Promotion</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date début promo</label>
                            <input type="datetime-local" class="form-control" name="date_debut_promo" value="<?= !empty($produit['date_debut_promo']) ? date('Y-m-d\TH:i', strtotime($produit['date_debut_promo'])) : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date fin promo</label>
                            <input type="datetime-local" class="form-control" name="date_fin_promo" value="<?= !empty($produit['date_fin_promo']) ? date('Y-m-d\TH:i', strtotime($produit['date_fin_promo'])) : '' ?>">
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!$is_edit): ?>
            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Images</h5></div>
                <div class="card-body">
                    <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                    <small class="text-muted">Vous pourrez ajouter plus d'images après la création.</small>
                </div>
            </div>
            <?php endif; ?>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="ri-save-line"></i> <?= $is_edit ? 'Mettre à jour' : 'Créer le produit' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
