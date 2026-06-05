<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:cart-bold-duotone" class="me-2"></iconify-icon>
                            Panier de <?= htmlspecialchars($utilisateur->prenom . ' ' . $utilisateur->nom) ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-danger" id="btnViderPanier">
                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                Vider le panier
                            </button>
                            <a href="<?= base_url('paniers') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Variante</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </table>
                                </thead>
                                <tbody>
                                    <?php if (!empty($articles)): ?>
                                        <?php foreach ($articles as $a): 
                                            $total_ligne = $a->prix_actuel * $a->quantite;
                                        ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($a->nom_produit) ?></strong>
                                                    <br><small class="text-muted">SKU: <?= htmlspecialchars($a->sku) ?></small>
                                                </div>
                                                <td>
                                                    <?php if (!empty($a->attributs)): ?>
                                                        <?php foreach ($a->attributs as $type => $val): ?>
                                                            <span class="badge bg-info me-1"><?= ucfirst($type) ?>: <?= htmlspecialchars($val) ?></span>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Standard</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <input type="number" class="form-control form-control-sm quantite-input" 
                                                               data-id="<?= $a->id_panier ?>" 
                                                               value="<?= $a->quantite ?>" 
                                                               style="width: 70px;" min="1">
                                                    </div>
                                                </div>
                                                <td><?= number_format($a->prix_actuel, 0, ',', ' ') ?> FBu</div>
                                                <td><strong class="text-primary"><?= number_format($total_ligne, 0, ',', ' ') ?> FBu</strong></div>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-article" data-id="<?= $a->id_panier ?>" data-produit="<?= htmlspecialchars($a->nom_produit) ?>">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                    </button>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <iconify-icon icon="solar:cart-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Panier vide</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="4" class="text-end"><strong class="fs-5">Total général :</strong></div>
                                        <td><strong class="fs-4 text-primary"><?= number_format($total, 0, ',', ' ') ?> FBu</strong></div>
                                        <td></div>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Modifier la quantité
$('.quantite-input').on('change', function() {
    const id = $(this).data('id');
    const quantite = $(this).val();
    
    if (quantite < 1) {
        Swal.fire('Erreur', 'La quantité doit être au moins 1', 'error');
        location.reload();
        return;
    }
    
    $.ajax({
        url: '<?= base_url("paniers/update_quantite") ?>',
        type: 'POST',
        data: {id_panier: id, quantite: quantite},
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                Swal.fire('Erreur', response.message, 'error');
            }
        }
    });
});

// Supprimer un article
$('.delete-article').on('click', function() {
    const id = $(this).data('id');
    const produit = $(this).data('produit');
    
    Swal.fire({
        title: 'Confirmation',
        text: `Supprimer "${produit}" du panier ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("paniers/delete_article/") ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Supprimé', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur', response.message, 'error');
                    }
                }
            });
        }
    });
});

// Vider le panier
$('#btnViderPanier').on('click', function() {
    Swal.fire({
        title: 'Confirmation',
        text: 'Vider complètement ce panier ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, vider',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("paniers/vider/") . $utilisateur->id_utilisateur ?>',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Succès', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur', response.message, 'error');
                    }
                }
            });
        }
    });
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>