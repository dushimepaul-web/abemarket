<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Gestion des Provinces</h4>
                        <a href="<?= base_url('Adresse/Location/province_add_edit') ?>" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i>Ajouter une province
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom de la province</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($provinces)): ?>
                                        <?php foreach ($provinces as $province): ?>
                                            <tr>
                                                <td><?= $province->id_province ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <iconify-icon icon="solar:map-point-bold-duotone" class="fs-20 text-primary"></iconify-icon>
                                                        <?= htmlspecialchars($province->province_name) ?>
                                                    </div>
                                                </td>
                                                <td><?= $province->latitude ?? '-' ?></td>
                                                <td><?= $province->longitude ?? '-' ?></td>
                                                <td>
                                                    <?php if ($province->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('Adresse/Location/province_detail/' . $province->id_province) ?>" class="btn btn-light btn-sm" title="Voir">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('Adresse/Location/province_add_edit/' . $province->id_province) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-danger btn-sm" title="Supprimer" onclick="confirmDelete('province', <?= $province->id_province ?>, '<?= addslashes($province->province_name) ?>')">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <iconify-icon icon="solar:map-point-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucune province enregistrée</h5>
                                                <a href="<?= base_url('Adresse/Location/province_add_edit') ?>" class="btn btn-primary mt-2">Ajouter une province</a>
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
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("Location/supprimer/") ?>' + type + '/' + id, function(response) {
                if (response.success) {
                    Swal.fire('Supprimé!', response.message, 'success').then(() => { location.reload(); });
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                }
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
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>