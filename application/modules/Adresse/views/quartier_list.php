<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">
                            Gestion des Quartiers
                            <?php if(isset($commune)): ?>
                                <small class="text-muted">- <?= htmlspecialchars($commune->commune_name) ?> (<?= htmlspecialchars($commune->province_name) ?>)</small>
                            <?php endif; ?>
                        </h4>
                        <div>
                            <?php if(isset($commune)): ?>
                                <a href="<?= base_url('Adresse/Location/quartier_add_edit/' . $commune->id_commune) ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus me-1"></i>Ajouter un quartier
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('Adresse/Location/communes') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Communes
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom du quartier</th>
                                        <th>Commune</th>
                                        <th>Province</th>
                                        <th>Zone</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($quartiers)): ?>
                                        <?php foreach ($quartiers as $quartier): ?>
                                            <tr>
                                                <td><?= $quartier->id_quartier ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <iconify-icon icon="solar:flag-bold-duotone" class="fs-20 text-warning"></iconify-icon>
                                                        <?= htmlspecialchars($quartier->quartier_name) ?>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($quartier->commune_name ?? '-') ?></td>
                                                <td><?= htmlspecialchars($quartier->province_name ?? '-') ?></td>
                                                <td><?= htmlspecialchars($quartier->zone ?? '-') ?></td>
                                                <td><?= $quartier->latitude ?? '-' ?></td>
                                                <td><?= $quartier->longitude ?? '-' ?></td>
                                                <td><?= $quartier->est_actif ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-danger">Inactif</span>' ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('Adresse/Location/quartier_detail/' . $quartier->id_quartier) ?>" class="btn btn-light btn-sm" title="Voir">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('Adresse/Location/quartier_add_edit/' . $quartier->id_quartier) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-danger btn-sm" title="Supprimer" onclick="confirmDelete('quartier', <?= $quartier->id_quartier ?>, '<?= addslashes($quartier->quartier_name) ?>')">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="9" class="text-center py-5">
                                            <iconify-icon icon="solar:flag-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                            <h5>Aucun quartier enregistré</h5>
                                            <?php if(isset($commune)): ?>
                                                <a href="<?= base_url('Adresse/Location/quartier_add_edit/' . $commune->id_commune) ?>" class="btn btn-primary mt-2">Ajouter un quartier</a>
                                            <?php endif; ?>
                                        </td></tr>
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