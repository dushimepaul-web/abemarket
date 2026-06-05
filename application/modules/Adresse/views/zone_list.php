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
                            Gestion des Zones
                            <?php if(isset($quartier)): ?>
                                <small class="text-muted">- <?= htmlspecialchars($quartier->quartier_name) ?> (<?= htmlspecialchars($quartier->commune_name) ?>)</small>
                            <?php endif; ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <?php if(isset($quartier)): ?>
                                <a href="<?= base_url('zone/add/' . $quartier->id_quartier) ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus me-1"></i>Ajouter une zone
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('zone/add') ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus me-1"></i>Ajouter une zone
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('quartiers') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Quartiers
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de la zone</th>
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
                                    <?php if (!empty($zones)): ?>
                                        <?php foreach ($zones as $zone): ?>
                                            <tr>
                                                <td><?= $zone->id_zone ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <iconify-icon icon="solar:map-point-wave-bold-duotone" class="fs-20 text-info"></iconify-icon>
                                                        <?= htmlspecialchars($zone->zone_name) ?>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($zone->quartier_name ?? '-') ?></td>
                                                <td><?= htmlspecialchars($zone->commune_name ?? '-') ?></td>
                                                <td><?= htmlspecialchars($zone->province_name ?? '-') ?></td>
                                                <td><?= $zone->latitude ?? '-' ?></td>
                                                <td><?= $zone->longitude ?? '-' ?></td>
                                                <td>
                                                    <?php if ($zone->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('zone/detail/' . $zone->id_zone) ?>" class="btn btn-light btn-sm" title="Voir">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('zone/edit/' . $zone->id_zone) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('collines/' . $zone->id_zone) ?>" class="btn btn-soft-info btn-sm" title="Voir les collines">
                                                            <iconify-icon icon="solar:mountains-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-danger btn-sm" title="Supprimer" onclick="confirmDelete('zone', <?= $zone->id_zone ?>, '<?= addslashes($zone->zone_name) ?>')">
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
                                                <h5>Aucune zone enregistrée</h5>
                                                <?php if(isset($quartier)): ?>
                                                    <a href="<?= base_url('zone/add/' . $quartier->id_quartier) ?>" class="btn btn-primary mt-2">Ajouter une zone</a>
                                                <?php else: ?>
                                                    <a href="<?= base_url('zone/add') ?>" class="btn btn-primary mt-2">Ajouter une zone</a>
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
        text: "Vous allez supprimer " + name + " et toutes ses données associées",
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

<style>
.btn-soft-primary { background-color: #cfe2ff; border-color: #cfe2ff; color: #0d6efd; }
.btn-soft-primary:hover { background-color: #b6d4fe; }
.btn-soft-danger { background-color: #f8d7da; border-color: #f8d7da; color: #dc3545; }
.btn-soft-danger:hover { background-color: #f5c2c7; }
.btn-soft-info { background-color: #cff4fc; border-color: #cff4fc; color: #0dcaf0; }
.btn-soft-info:hover { background-color: #b6effb; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>