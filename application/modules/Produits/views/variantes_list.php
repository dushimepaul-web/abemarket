<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Gestion des variantes - <?= htmlspecialchars($produit->nom_produit) ?></h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('VarianteProduit/add/' . $produit->slug_produit) ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus me-1"></i>Ajouter une variante
                            </a>
                            <a href="<?= base_url('Produits') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour produits
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>SKU</th>
                                        <th>Attributs</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($variantes)): ?>
                                        <?php foreach ($variantes as $v): ?>
                                            <tr>
                                                <td>#<?= $v->id_variante ?></td>
                                                <td><code><?= htmlspecialchars($v->sku) ?></code></td>
                                                <td>
                                                    <?php if (!empty($v->attributs)): ?>
                                                        <?php foreach ($v->attributs as $type => $valeur): ?>
                                                            <span class="badge bg-info me-1 mb-1">
                                                                <?= ucfirst($type) ?>: <?= htmlspecialchars($valeur) ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= number_format($v->prix ?? $produit->prix_base, 0, ',', ' ') ?> FBu</strong>
                                                </div>
                                                <td>
                                                    <?php if ($v->quantite_actuelle > 0): ?>
                                                        <span class="badge bg-success"><?= $v->quantite_actuelle ?> en stock</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Rupture</span>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary update-stock" 
                                                            data-slug="<?= $produit->slug_produit ?>"
                                                            data-id="<?= $v->id_variante ?>" 
                                                            data-stock="<?= $v->quantite_actuelle ?>">
                                                        <iconify-icon icon="solar:refresh-bold-duotone" class="fs-14"></iconify-icon>
                                                    </button>
                                                </div>
                                                <td>
                                                    <?php if ($v->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactif</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('VarianteProduit/edit/' . $produit->slug_produit . '/' . $v->id_variante) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-danger btn-sm delete-variante" 
                                                                data-slug="<?= $produit->slug_produit ?>"
                                                                data-id="<?= $v->id_variante ?>" 
                                                                data-sku="<?= htmlspecialchars($v->sku) ?>" 
                                                                title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <iconify-icon icon="solar:box-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucune variante</h5>
                                                <p class="text-muted">Ce produit n'a pas encore de variantes</p>
                                                <a href="<?= base_url('VarianteProduit/add/' . $produit->slug_produit) ?>" class="btn btn-primary mt-2">
                                                    Ajouter une variante
                                                </a>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mettre à jour le stock -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Mettre à jour le stock</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockForm">
                <div class="modal-body">
                    <input type="hidden" name="slug_produit" id="stock_slug_produit">
                    <input type="hidden" name="id_variante" id="stock_id_variante">
                    <div class="mb-3">
                        <label class="form-label">Quantité à ajouter/retirer</label>
                        <input type="number" name="quantite" id="stock_quantite" class="form-control" required>
                        <small class="text-muted">Nombre positif pour ajouter, négatif pour retirer</small>
                    </div>
                    <div class="alert alert-info">
                        Stock actuel: <strong id="stock_actuel">0</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Mettre à jour le stock
$(document).on('click', '.update-stock', function() {
    const slug = $(this).data('slug');
    const id = $(this).data('id');
    const stock = $(this).data('stock');
    
    $('#stock_slug_produit').val(slug);
    $('#stock_id_variante').val(id);
    $('#stock_actuel').text(stock);
    $('#stockModal').modal('show');
});

$('#stockForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?= base_url("VarianteProduit/update_stock") ?>',
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire('Succès', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Erreur', response.message, 'error');
            }
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            Swal.fire('Erreur', 'Erreur de communication: ' + xhr.status, 'error');
        }
    });
});

// Supprimer
$(document).on('click', '.delete-variante', function() {
    const slug = $(this).data('slug');
    const id = $(this).data('id');
    const sku = $(this).data('sku');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer la variante " + sku,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("VarianteProduit/delete/") ?>' + slug + '/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Supprimé!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Erreur', 'Erreur de communication: ' + xhr.status, 'error');
                }
            });
        }
    });
});
</script>

<style>
.btn-soft-primary { background-color: #cfe2ff; border-color: #cfe2ff; color: #0d6efd; }
.btn-soft-primary:hover { background-color: #b6d4fe; }
.btn-soft-danger { background-color: #f8d7da; border-color: #f8d7da; color: #dc3545; }
.btn-soft-danger:hover { background-color: #f5c2c7; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>