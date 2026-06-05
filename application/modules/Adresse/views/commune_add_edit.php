<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1"><?= isset($commune) ? 'Modifier la commune' : 'Ajouter une commune' ?></h4>
                        <a href="<?= base_url('Adresse/Location/communes' . (isset($province_id) ? '/' . $province_id : '')) ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="communeForm" method="post" action="<?= isset($commune) ? base_url('Adresse/Location/modifier/commune/' . $commune->id_commune) : base_url('Adresse/Location/ajouter/commune') ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Province <span class="text-danger">*</span></label>
                                    <select name="id_province" id="id_province" class="form-select" required>
                                        <option value="">Sélectionner une province</option>
                                        <?php foreach ($provinces as $prov): ?>
                                            <option value="<?= $prov->id_province ?>" <?= isset($commune) && $commune->id_province == $prov->id_province ? 'selected' : (isset($province_id) && $province_id == $prov->id_province ? 'selected' : '') ?>>
                                                <?= htmlspecialchars($prov->province_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de la commune <span class="text-danger">*</span></label>
                                    <input type="text" name="commune_name" id="commune_name" class="form-control" required value="<?= isset($commune) ? htmlspecialchars($commune->commune_name) : '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" value="<?= isset($commune) ? $commune->latitude : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" value="<?= isset($commune) ? $commune->longitude : '' ?>">
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" <?= isset($commune) && $commune->est_actif ? 'checked' : (isset($commune) ? '' : 'checked') ?>>
                                <label class="form-check-label">Actif</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i><?= isset($commune) ? 'Mettre à jour' : 'Enregistrer' ?></button>
                                <a href="<?= base_url('Adresse/Location/communes' . (isset($province_id) ? '/' . $province_id : '')) ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('#communeForm').on('submit', function(e) {
    e.preventDefault();
    if (!$('#id_province').val()) { Swal.fire('Erreur', 'La province est requise', 'error'); return; }
    if (!$('#commune_name').val()) { Swal.fire('Erreur', 'Le nom de la commune est requis', 'error'); return; }
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) Swal.fire('Succès', response.message, 'success').then(() => { window.location.href = '<?= base_url("Location/communes") ?>'; });
        else Swal.fire('Erreur', response.message, 'error');
        submitBtn.prop('disabled', false).html('<?= isset($commune) ? "Mettre à jour" : "Enregistrer" ?>');
    }, 'json');
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>