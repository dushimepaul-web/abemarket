<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            Gestion des Collines
                            <?php if(isset($zone)): ?>
                                <small class="text-muted">- <?= htmlspecialchars($zone->zone_name) ?> (<?= htmlspecialchars($zone->quartier_name) ?>)</small>
                            <?php endif; ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <?php if(isset($zone)): ?>
                                <a href="<?= base_url('colline/add/' . $zone->id_zone) ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus me-1"></i>Ajouter une colline
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('colline/add') ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus me-1"></i>Ajouter une colline
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('zones') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Zones
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de la colline</th>
                                        <th>Zone</th>
                                        <th>Quartier</th>
                                        <th>Commune</th>
                                        <th>Province</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($collines)): ?>
                                        <?php foreach ($collines as $colline): ?>
                                            <tr>
                                                <td><?= $colline->id_colline ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <iconify-icon icon="solar:mountains-bold-duotone" class="fs-20 text-success"></iconify-icon>
                                                        <?= htmlspecialchars($colline->colline_name) ?>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($colline->zone_name ?? '-') ?></td>
                                                <td><?= htmlspecialchars($colline->quartier_name ?? '-') ?></td>
                                                <td><?= htmlspecialchars($colline->commune_name ?? '-') ?></td>
                                                <td><?= htmlspecialchars($colline->province_name ?? '-') ?></td>
                                                <td><?= $colline->latitude ?? '-' ?></td>
                                                <td><?= $colline->longitude ?? '-' ?></td>
                                                <td>
                                                    <?php if ($colline->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('colline/detail/' . $colline->id_colline) ?>" class="btn btn-light btn-sm" title="Voir">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('colline/edit/' . $colline->id_colline) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-danger btn-sm" title="Supprimer" onclick="confirmDelete('colline', <?= $colline->id_colline ?>, '<?= addslashes($colline->colline_name) ?>')">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <iconify-icon icon="solar:mountains-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucune colline enregistrée</h5>
                                                <?php if(isset($zone)): ?>
                                                    <a href="<?= base_url('colline/add/' . $zone->id_zone) ?>" class="btn btn-primary mt-2">Ajouter une colline</a>
                                                <?php else: ?>
                                                    <a href="<?= base_url('colline/add') ?>" class="btn btn-primary mt-2">Ajouter une colline</a>
                                                <?php endif; ?>
                                            </td>
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
function confirmDelete(type, id, name) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer " + name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("Location/supprimer/") ?>' + type + '/' + id, function(response) {
                if (response.success) Swal.fire('Supprimé!', response.message, 'success').then(() => location.reload());
                else Swal.fire('Erreur!', response.message, 'error');
            }, 'json');
        }
    });
}
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>