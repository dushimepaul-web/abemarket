<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1"><?= isset($quartier) ? 'Modifier le quartier' : 'Ajouter un quartier' ?></h4>
                        <a href="<?= base_url('Adresse/Location/quartiers' . (isset($commune_id) ? '/' . $commune_id : '')) ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="quartierForm" method="post" action="<?= isset($quartier) ? base_url('Adresse/Location/modifier/quartier/' . $quartier->id_quartier) : base_url('Adresse/Location/ajouter/quartier') ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Province <span class="text-danger">*</span></label>
                                    <select name="id_province" id="id_province" class="form-select" required>
                                        <option value="">Sélectionner une province</option>
                                        <?php foreach ($provinces as $prov): ?>
                                            <option value="<?= $prov->id_province ?>" data-province-id="<?= $prov->id_province ?>" <?= isset($selected_province) && $selected_province == $prov->id_province ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($prov->province_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Commune <span class="text-danger">*</span></label>
                                    <select name="id_commune" id="id_commune" class="form-select" required <?= isset($communes) && !empty($communes) ? '' : 'disabled' ?>>
                                        <option value="">Sélectionner d'abord une province</option>
                                        <?php if (isset($communes) && !empty($communes)): ?>
                                            <?php foreach ($communes as $com): ?>
                                                <option value="<?= $com->id_commune ?>" <?= isset($quartier) && $quartier->id_commune == $com->id_commune ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($com->commune_name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom du quartier <span class="text-danger">*</span></label>
                                    <input type="text" name="quartier_name" id="quartier_name" class="form-control" required value="<?= isset($quartier) ? htmlspecialchars($quartier->quartier_name) : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Zone</label>
                                    <input type="text" name="zone" id="zone" class="form-control" value="<?= isset($quartier) ? htmlspecialchars($quartier->zone) : '' ?>" placeholder="Ex: Zone Nord, Zone Industrielle...">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" value="<?= isset($quartier) ? $quartier->latitude : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" value="<?= isset($quartier) ? $quartier->longitude : '' ?>">
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" <?= isset($quartier) && $quartier->est_actif ? 'checked' : (isset($quartier) ? '' : 'checked') ?>>
                                <label class="form-check-label">Actif</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i><?= isset($quartier) ? 'Mettre à jour' : 'Enregistrer' ?></button>
                                <a href="<?= base_url('Adresse/Location/quartiers' . (isset($commune_id) ? '/' . $commune_id : '')) ?>" class="btn btn-secondary">Annuler</a>
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
$('#id_province').on('change', function() {
    const provinceId = $(this).val();
    if (provinceId) {
        $.get('<?= base_url("Location/get_communes_by_province/") ?>' + provinceId, function(data) {
            let options = '<option value="">Sélectionner une commune</option>';
            data.forEach(function(commune) { options += `<option value="${commune.id_commune}">${commune.commune_name}</option>`; });
            $('#id_commune').html(options).prop('disabled', false);
        }, 'json');
    } else {
        $('#id_commune').html('<option value="">Sélectionner d\'abord une province</option>').prop('disabled', true);
    }
});

$('#quartierForm').on('submit', function(e) {
    e.preventDefault();
    if (!$('#id_province').val()) { Swal.fire('Erreur', 'La province est requise', 'error'); return; }
    if (!$('#id_commune').val()) { Swal.fire('Erreur', 'La commune est requise', 'error'); return; }
    if (!$('#quartier_name').val()) { Swal.fire('Erreur', 'Le nom du quartier est requis', 'error'); return; }
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) Swal.fire('Succès', response.message, 'success').then(() => { window.location.href = '<?= base_url("Location/quartiers") ?>'; });
        else Swal.fire('Erreur', response.message, 'error');
    }, 'json');
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>