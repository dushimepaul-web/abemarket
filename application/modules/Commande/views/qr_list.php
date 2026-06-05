<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestion des QR codes de livraison</h4>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-primary bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:qr-code-bold-duotone" class="fs-24 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->total_qr ?? 0) ?></h5>
                                        <small class="text-muted">Total QR codes</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-success bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-24 text-success"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->utilises ?? 0) ?></h5>
                                        <small class="text-muted">Utilisés</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-warning bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-24 text-warning"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->actifs ?? 0) ?></h5>
                                        <small class="text-muted">Actifs</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-danger bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:calendar-remove-bold-duotone" class="fs-24 text-danger"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->expires ?? 0) ?></h5>
                                        <small class="text-muted">Expirés</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Statut</th>
                                        <th>Date création</th>
                                        <th>Expiration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($qr_codes)): ?>
                                        <?php foreach ($qr_codes as $qr): ?>
                                            <tr>
                                                <td>#<?= $qr->id_qr ?></td>
                                                <td>
                                                    <strong><?= $qr->numero_commande ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= number_format($qr->montant_total, 0, ',', ' ') ?> FBu</small>
                                                 </div>
                                                <td>
                                                    <?= htmlspecialchars($qr->prenom ?? '') ?> <?= htmlspecialchars($qr->nom ?? '') ?>
                                                 </div>
                                                <td>
                                                    <?php if ($qr->est_utilise): ?>
                                                        <span class="badge bg-success">Utilisé</span>
                                                        <?php if ($qr->date_utilisation): ?>
                                                            <br><small class="text-muted">le <?= date('d/m/Y H:i', strtotime($qr->date_utilisation)) ?></small>
                                                        <?php endif; ?>
                                                    <?php elseif (strtotime($qr->date_expiration) < time()): ?>
                                                        <span class="badge bg-danger">Expiré</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php endif; ?>
                                                 </div>
                                                <td><?= date('d/m/Y H:i', strtotime($qr->date_creation)) ?> </div>
                                                <td>
                                                    <?= date('d/m/Y H:i', strtotime($qr->date_expiration)) ?>
                                                    <?php if (strtotime($qr->date_expiration) > time() && !$qr->est_utilise): ?>
                                                        <br><small class="text-success">Valide</small>
                                                    <?php endif; ?>
                                                 </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('qr/view/' . $qr->id_commande) ?>" class="btn btn-primary btn-sm" title="Voir QR code">
                                                            <iconify-icon icon="solar:qr-code-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('Commande/detail/' . $qr->id_commande) ?>" class="btn btn-light btn-sm" title="Voir commande">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <?php if (!$qr->est_utilise && strtotime($qr->date_expiration) > time()): ?>
                                                            <button type="button" class="btn btn-warning btn-sm regenerate" data-id="<?= $qr->id_commande ?>" title="Régénérer">
                                                                <iconify-icon icon="solar:refresh-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <iconify-icon icon="solar:qr-code-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucun QR code généré</h5>
                                                <p class="text-muted">Générez des QR codes depuis les détails des commandes</p>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('.regenerate').on('click', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous régénérer ce QR code ? L\'ancien ne sera plus valide.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, régénérer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("qr/regenerate/") ?>' + id, function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                }
            }, 'json');
        }
    });
});
</script>

<style>
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>