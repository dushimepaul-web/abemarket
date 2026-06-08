<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:cart-bold-duotone" class="me-2"></iconify-icon>
                            Mon Panier
                        </h4>
                        <span class="badge bg-primary fs-14">Total : <?= number_format($total ?? 0, 0, ',', ' ') ?> FBu</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($articles)): ?>
                        <div class="text-center py-5">
                            <iconify-icon icon="solar:cart-large-2-bold-duotone" class="fs-48 text-muted"></iconify-icon>
                            <h5 class="mt-3">Votre panier est vide</h5>
                            <a href="<?= base_url() ?>" class="btn btn-primary mt-2">Continuer mes achats</a>
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Variante</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité</th>
                                        <th>Total</th>
                                        <th>Date ajout</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articles as $a): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if ($a->url_image): ?>
                                                <img src="<?= base_url($a->url_image) ?>" alt="" class="avatar-sm rounded">
                                                <?php endif; ?>
                                                <span class="fw-semibold"><?= htmlspecialchars($a->nom_produit) ?></span>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($a->variante_sku ?? 'Standard') ?></td>
                                        <td><?= number_format($a->prix, 0, ',', ' ') ?> FBu</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm qte-input" style="width:70px" value="<?= $a->quantite ?>" min="1" data-id="<?= $a->id_panier ?>">
                                        </td>
                                        <td class="fw-bold"><?= number_format($a->quantite * $a->prix, 0, ',', ' ') ?> FBu</td>
                                        <td><?= date('d/m/Y', strtotime($a->date_ajout)) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $a->id_panier ?>">
                                                <i class="bx bx-trash"></i>
                                            </button>
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
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>

<script>
$(document).ready(function() {
    $('.qte-input').change(function() {
        var id = $(this).data('id');
        var qte = $(this).val();
        $.post('<?= base_url("paniers/update_quantite") ?>', {id_panier: id, quantite: qte}, function(resp) {
            if (resp.success) location.reload();
        }, 'json');
    });

    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Supprimer ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.get('<?= base_url("paniers/delete_article/") ?>' + id, function(resp) {
                    if (resp.success) Swal.fire('Supprimé', '', 'success').then(function() { location.reload(); });
                    else Swal.fire('Erreur', resp.message, 'error');
                }, 'json');
            }
        });
    });
});
</script>
