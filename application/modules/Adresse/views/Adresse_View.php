<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<!-- ==================================================== -->
<!-- Start right Content here -->
<!-- ==================================================== -->
<div class="page-content">

    <!-- Start Container Fluid -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Gestion des adresses</h4>

                        <a href="<?= base_url('Adresse/adresse_add_edit') ?>" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i>Ajouter une adresse
                        </a>

                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item" id="exportAddresses">Exporter (CSV)</a>
                                <a href="#" class="dropdown-item" id="importAddresses">Importer</a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered" id="addressesTable">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                                <label class="form-check-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th>Adresse & Destinataire</th>
                                        <th>Téléphone</th>
                                        <th>Localisation</th>
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($adresses)): ?>
                                        <?php foreach ($adresses as $adresse): ?>
                                            <tr class="<?= $adresse->est_par_defaut ? 'table-primary' : '' ?>">
                                                <tr>
                                                    <div class="form-check ms-1">
                                                        <input type="checkbox" class="form-check-input address-check" value="<?= $adresse->id_adresse ?>">
                                                        <label class="form-check-label"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                            <?php if ($adresse->type_adresse == 'domicile'): ?>
                                                                <iconify-icon icon="solar:home-2-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                                                            <?php elseif ($adresse->type_adresse == 'travail'): ?>
                                                                <iconify-icon icon="solar:buildings-bold-duotone" class="fs-32 text-info"></iconify-icon>
                                                            <?php else: ?>
                                                                <iconify-icon icon="solar:map-point-bold-duotone" class="fs-32 text-warning"></iconify-icon>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <a href="<?= base_url('Adresse/adresse_detail/' . $adresse->id_adresse) ?>" class="text-dark fw-medium fs-15">
                                                                <?= htmlspecialchars($adresse->nom_complet) ?>
                                                            </a>
                                                            <p class="text-muted mb-0 mt-1 fs-13">
                                                                <span>Adresse : </span><?= htmlspecialchars(substr($adresse->adresse_ligne, 0, 50)) ?>...
                                                            </p>
                                                            <p class="text-muted mb-0 mt-1 fs-12">
                                                                <span class="badge bg-light-subtle text-dark"><?= ucfirst($adresse->type_adresse) ?></span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                  </td>
                                                <tr>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <iconify-icon icon="solar:phone-bold" class="fs-16 text-muted"></iconify-icon>
                                                        <span><?= htmlspecialchars($adresse->telephone) ?></span>
                                                    </div>
                                                  </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <?php if ($adresse->province_name): ?>
                                                            <small class="text-muted">
                                                                <iconify-icon icon="solar:map-point-bold" class="fs-12 me-1"></iconify-icon>
                                                                <?= htmlspecialchars($adresse->province_name) ?>
                                                            </small>
                                                        <?php endif; ?>
                                                        <?php if ($adresse->commune_name): ?>
                                                            <small class="text-muted">
                                                                <iconify-icon icon="solar:building-bold" class="fs-12 me-1"></iconify-icon>
                                                                <?= htmlspecialchars($adresse->commune_name) ?>
                                                            </small>
                                                        <?php endif; ?>
                                                        <?php if ($adresse->quartier_name): ?>
                                                            <small class="text-muted">
                                                                <iconify-icon icon="solar:flag-bold" class="fs-12 me-1"></iconify-icon>
                                                                <?= htmlspecialchars($adresse->quartier_name) ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                  </td>
                                                <td>
                                                    <?php if ($adresse->est_par_defaut): ?>
                                                        <span class="badge bg-success px-2 py-1">
                                                            <i class="bx bx-check-circle me-1"></i>Par défaut
                                                        </span>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-soft-primary" onclick="setDefaultAddress(<?= $adresse->id_adresse ?>)">
                                                            <i class="bx bx-star me-1"></i>Définir
                                                        </button>
                                                    <?php endif; ?>
                                                  </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('Adresse/adresse_detail/' . $adresse->id_adresse) ?>" class="btn btn-light btn-sm" title="Voir">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('Adresse/adresse_add_edit/' . $adresse->id_adresse) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-danger btn-sm" title="Supprimer" onclick="confirmDelete(<?= $adresse->id_adresse ?>, '<?= addslashes($adresse->nom_complet) ?>')">
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
                                                <h5>Aucune adresse enregistrée</h5>
                                                <p class="text-muted">Cliquez sur "Ajouter une adresse" pour en créer une</p>
                                                <a href="<?= base_url('Adresse/adresse_add_edit') ?>" class="btn btn-primary mt-2">
                                                    <i class="bx bx-plus me-1"></i>Ajouter ma première adresse
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($adresses)): ?>
                        <div class="card-footer border-top">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end mb-0">
                                    <?= $this->pagination->create_links() ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
    <!-- End Container Fluid -->

</div>
<!-- ==================================================== -->
<!-- End Page Content -->
<!-- ==================================================== -->

<!-- Modal Importation (seulement pour l'import) -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Importer des adresses (CSV)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="importForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Fichier CSV <span class="text-danger">*</span></label>
                        <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                        <small class="text-muted">Format: nom_complet,telephone,adresse_ligne,province_name,commune_name,quartier_name,type_adresse</small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="skip_first_row" id="skip_first_row" class="form-check-input" value="1" checked>
                            <label class="form-check-label">Ignorer la première ligne (en-têtes)</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="importBtn">Importer</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Sélectionner toutes les adresses
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.address-check');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Supprimer adresse
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous allez supprimer l'adresse de '" + name + "'",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("Adresse/supprimer/") ?>' + id, function(response) {
                    if (response.success) {
                        Swal.fire('Supprimé!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur!', response.message, 'error');
                    }
                }, 'json').fail(function() {
                    Swal.fire('Erreur!', 'Erreur lors de la suppression', 'error');
                });
            }
        });
    }

    // Définir adresse par défaut
    function setDefaultAddress(id) {
        Swal.fire({
            title: 'Confirmation',
            text: 'Voulez-vous définir cette adresse comme adresse par défaut ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            confirmButtonText: 'Oui, définir',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url("Adresse/set_default/") ?>' + id, function(response) {
                    if (response.success) {
                        Swal.fire('Succès!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur!', response.message, 'error');
                    }
                }, 'json').fail(function() {
                    Swal.fire('Erreur!', 'Erreur lors de la mise à jour', 'error');
                });
            }
        });
    }

    // Exporter les adresses
    $('#exportAddresses').on('click', function(e) {
        e.preventDefault();
        const selectedIds = [];
        $('.address-check:checked').each(function() {
            selectedIds.push($(this).val());
        });

        let url = '<?= base_url("Adresse/exporter") ?>';
        if (selectedIds.length > 0) {
            url += '?ids=' + selectedIds.join(',');
        }

        window.location.href = url;
    });

    // Importer des adresses
    $('#importAddresses').on('click', function(e) {
        e.preventDefault();
        $('#importModal').modal('show');
    });

    $('#importBtn').on('click', function() {
        const fileInput = document.getElementById('csv_file');
        if (!fileInput.files.length) {
            Swal.fire('Erreur', 'Veuillez sélectionner un fichier CSV', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('csv_file', fileInput.files[0]);
        formData.append('skip_first_row', $('#skip_first_row').is(':checked') ? '1' : '0');

        const importBtn = $('#importBtn');
        importBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Importation...');

        $.ajax({
            url: '<?= base_url("Adresse/importer") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur', response.message || 'Erreur lors de l\'importation', 'error');
                    importBtn.prop('disabled', false).html('Importer');
                }
            },
            error: function() {
                Swal.fire('Erreur', 'Erreur de connexion au serveur', 'error');
                importBtn.prop('disabled', false).html('Importer');
            }
        });
    });
</script>

<style>
    .avatar-md {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 8px;
    }

    .bg-light-subtle {
        background-color: #f8f9fa;
    }

    .table-primary {
        background-color: #e7f1ff !important;
    }

    .btn-soft-primary {
        background-color: #cfe2ff;
        border-color: #cfe2ff;
        color: #0d6efd;
    }

    .btn-soft-primary:hover {
        background-color: #b6d4fe;
    }

    .btn-soft-danger {
        background-color: #f8d7da;
        border-color: #f8d7da;
        color: #dc3545;
    }

    .btn-soft-danger:hover {
        background-color: #f5c2c7;
    }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>