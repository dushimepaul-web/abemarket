<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Gestion des zones de livraison</h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('zone-livraison/add') ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus me-1"></i>Ajouter une zone
                            </a>
                            <a href="<?= base_url('zone-livraison/exporter') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-export me-1"></i>Exporter
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-primary bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:map-point-wave-bold-duotone" class="fs-24 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->total_zones ?? 0) ?></h5>
                                        <small class="text-muted">Total zones</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-success bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-24 text-success"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->zones_actives ?? 0) ?></h5>
                                        <small class="text-muted">Zones actives</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-info bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:wallet-bold-duotone" class="fs-24 text-info"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->cout_moyen ?? 0, 0, ',', ' ') ?> FBu</h5>
                                        <small class="text-muted">Coût moyen</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-warning bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:chart-2-bold-duotone" class="fs-24 text-warning"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->cout_max ?? 0, 0, ',', ' ') ?> FBu</h5>
                                        <small class="text-muted">Coût max</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de la zone</th>
                                        <th>Province</th>
                                        <th>Communes</th>
                                        <th>Coût base</th>
                                        <th>Seuil gratuité</th>
                                        <th>Délai (jours)</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($zones)): ?>
                                        <?php foreach ($zones as $z): ?>
                                            <tr>
                                                <td><?= $z->id_zone_liv ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <iconify-icon icon="solar:map-point-wave-bold-duotone" class="fs-20 text-primary"></iconify-icon>
                                                        <strong><?= htmlspecialchars($z->nom_zone) ?></strong>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($z->province_name ?? 'Toutes les provinces') ?></td>
                                                <td>
                                                    <?php if ($z->ids_communes): ?>
                                                        <?php 
                                                        $communes_ids = explode(',', $z->ids_communes);
                                                        $total = count($communes_ids);
                                                        ?>
                                                        <span class="badge bg-info"><?= $total ?> commune(s)</span>
                                                    <?php else: ?>
                                                        <span class="text-muted">Toutes</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= number_format($z->cout_base, 0, ',', ' ') ?> FBu</strong>
                                                    <?php if ($z->cout_par_kg): ?>
                                                        <br><small class="text-muted">+<?= number_format($z->cout_par_kg, 0) ?> FBu/kg</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($z->seuil_livraison_gratuite): ?>
                                                        <span class="text-success">≥ <?= number_format($z->seuil_livraison_gratuite, 0, ',', ' ') ?> FBu</span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($z->delai_min_jours && $z->delai_max_jours): ?>
                                                        <?= $z->delai_min_jours ?> - <?= $z->delai_max_jours ?> jours
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($z->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('zone-livraison/detail/' . $z->id_zone_liv) ?>" class="btn btn-light btn-sm" title="Voir">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('zone-livraison/edit/' . $z->id_zone_liv) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-<?= $z->est_actif ? 'warning' : 'success' ?> btn-sm toggle-statut" data-id="<?= $z->id_zone_liv ?>" data-statut="<?= $z->est_actif ?>" title="<?= $z->est_actif ? 'Désactiver' : 'Activer' ?>">
                                                            <iconify-icon icon="solar:<?= $z->est_actif ? 'user-block-rounded' : 'user-check' ?>-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-soft-danger btn-sm delete-zone" data-id="<?= $z->id_zone_liv ?>" data-nom="<?= htmlspecialchars($z->nom_zone) ?>" title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:map-point-wave-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucune zone de livraison</h5>
                                                <p class="text-muted">Cliquez sur "Ajouter une zone" pour commencer</p>
                                                <a href="<?= base_url('zone-livraison/add') ?>" class="btn btn-primary mt-2">Ajouter une zone</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($zones)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Changer le statut
$('.toggle-statut').on('click', function() {
    const id = $(this).data('id');
    const statutActuel = $(this).data('statut');
    const nouveauStatut = statutActuel == 1 ? 'désactiver' : 'activer';
    
    Swal.fire({
        title: 'Confirmation',
        text: `Voulez-vous ${nouveauStatut} cette zone de livraison ?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("zone-livraison/toggle-statut/") ?>' + id, function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                }
            }, 'json');
        }
    });
});

// Supprimer une zone
$('.delete-zone').on('click', function() {
    const id = $(this).data('id');
    const nom = $(this).data('nom');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer la zone " + nom,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("zone-livraison/delete/") ?>' + id, function(response) {
                if (response.success) {
                    Swal.fire('Supprimé!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                }
            }, 'json');
        }
    });
});
</script>

<style>
.btn-soft-primary { background-color: #cfe2ff; border-color: #cfe2ff; color: #0d6efd; }
.btn-soft-primary:hover { background-color: #b6d4fe; }
.btn-soft-danger { background-color: #f8d7da; border-color: #f8d7da; color: #dc3545; }
.btn-soft-danger:hover { background-color: #f5c2c7; }
.btn-soft-warning { background-color: #fff3cd; border-color: #fff3cd; color: #ffc107; }
.btn-soft-warning:hover { background-color: #ffeaa7; }
.btn-soft-success { background-color: #d1e7dd; border-color: #d1e7dd; color: #198754; }
.btn-soft-success:hover { background-color: #b8e0c4; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>