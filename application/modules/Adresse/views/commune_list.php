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
                            Gestion des Communes
                            <?php if(isset($province)): ?>
                                <small class="text-muted">- <?= htmlspecialchars($province->province_name) ?></small>
                            <?php endif; ?>
                        </h4>
                        <div>
                            <?php if(isset($province)): ?>
                                <a href="<?= base_url('Adresse/Location/commune_add_edit/' . $province->id_province) ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus me-1"></i>Ajouter une commune
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('Adresse/Location/commune_add_edit') ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus me-1"></i>Ajouter une commune
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('Adresse/Location/provinces') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Provinces
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de la commune</th>
                                        <th>Province</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($communes)): ?>
                                        <?php foreach ($communes as $commune): ?>
                                            <tr>
                                                <td><?= $commune->id_commune ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <iconify-icon icon="solar:building-bold-duotone" class="fs-20 text-info"></iconify-icon>
                                                        <?= htmlspecialchars($commune->commune_name) ?>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($commune->province_name ?? '-') ?></td>
                                                <td><?= $commune->latitude ?? '-' ?></td>
                                                <td><?= $commune->longitude ?? '-' ?></td>
                                                <td><?= $commune->est_actif ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-danger">Inactif</span>' ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('Adresse/Location/commune_detail/' . $commune->id_commune) ?>" class="btn btn-light btn-sm" title="Voir">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('Adresse/Location/quartiers/' . $commune->id_commune) ?>" class="btn btn-soft-info btn-sm" title="Quartiers">
                                                            <iconify-icon icon="solar:flag-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('Adresse/Location/commune_add_edit/' . $commune->id_commune) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-danger btn-sm" title="Supprimer" onclick="confirmDelete('commune', <?= $commune->id_commune ?>, '<?= addslashes($commune->commune_name) ?>')">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center py-5">
                                            <iconify-icon icon="solar:building-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                            <h5>Aucune commune enregistrée</h5>
                                            <a href="<?= base_url('Adresse/Location/commune_add_edit' . (isset($province) ? '/' . $province->id_province : '')) ?>" class="btn btn-primary mt-2">Ajouter une commune</a>
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

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>