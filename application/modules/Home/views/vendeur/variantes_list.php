<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">
        <div class="row mb-3">
            <div class="col-12">
                <a href="<?= base_url('ProduitsVendeur') ?>" class="text-decoration-none"><i class="ri-arrow-left-line"></i> Retour</a>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h4>Variantes — <?= htmlspecialchars($produit->nom_produit) ?></h4>
                    <div>
                        <a href="<?= base_url('ProduitsVendeur/edit/' . $produit->slug_produit) ?>" class="btn btn-outline-primary btn-sm"><i class="ri-edit-line"></i> Modifier</a>
                        <a href="<?= base_url('ProduitsVendeur/images/' . $produit->slug_produit) ?>" class="btn btn-outline-secondary btn-sm"><i class="ri-image-line"></i> Images</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Variantes (<?= count($variantes) ?>)</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVarianteModal">
                    <i class="ri-add-line"></i> Ajouter une variante
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($variantes)): ?>
                    <div class="text-center py-4">
                        <i class="ri-stack-line" style="font-size:48px; color:#ccc;"></i>
                        <p class="text-muted mt-2">Aucune variante. Ce produit est de type simple.</p>
                        <p class="text-muted">Pour créer des variantes, changez le type du produit en "Variable" lors de la modification.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>SKU</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Attributs</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($variantes as $v): ?>
                                    <tr>
                                        <td><?= $v->id_variante ?></td>
                                        <td><?= htmlspecialchars($v->sku_variante ?? '-') ?></td>
                                        <td><strong><?= number_format($v->prix_variante, 0, ',', '.') ?> FC</strong></td>
                                        <td><?= $v->stock_variante ?></td>
                                        <td>
                                            <?php if (!empty($v->attributs)): ?>
                                                <?php foreach ($v->attributs as $key => $val): ?>
                                                    <span class="badge bg-light text-dark"><?= htmlspecialchars($key) ?>: <?= htmlspecialchars($val) ?></span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($v->est_actif): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm"><i class="ri-edit-line"></i></button>
                                            <button class="btn btn-outline-danger btn-sm"><i class="ri-delete-bin-line"></i></button>
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

<div class="modal fade" id="addVarianteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une variante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addVarianteForm">
                    <div class="mb-3">
                        <label class="form-label">SKU variante</label>
                        <input type="text" class="form-control" name="sku_variante">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prix (FC) *</label>
                        <input type="number" class="form-control" name="prix_variante" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock *</label>
                        <input type="number" class="form-control" name="stock_variante" required min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Taille</label>
                        <input type="text" class="form-control" name="attribut_taille" placeholder="ex: S, M, L, XL">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Couleur</label>
                        <input type="text" class="form-control" name="attribut_couleur" placeholder="ex: Rouge, Bleu">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveVarianteBtn">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('saveVarianteBtn')?.addEventListener('click', async function() {
    const form = document.getElementById('addVarianteForm');
    const formData = new FormData(form);
    const attributs = {};
    if (formData.get('attribut_taille')) attributs.taille = formData.get('attribut_taille');
    if (formData.get('attribut_couleur')) attributs.couleur = formData.get('attribut_couleur');
    formData.append('attributs_variante', JSON.stringify(attributs));

    try {
        const resp = await fetch('<?= base_url("ProduitsVendeur/add_variante/" . $produit->slug_produit) ?>', {
            method: 'POST',
            body: formData
        });
        const data = await resp.json();
        if (data.success) location.reload();
        else alert(data.error || 'Erreur');
    } catch(err) { alert('Erreur réseau'); }
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
