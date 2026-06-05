<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1"><?= isset($zone) ? 'Modifier la zone de livraison' : 'Ajouter une zone de livraison' ?></h4>
                        <a href="<?= base_url('zone-livraison') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="zoneForm" method="post" action="<?= base_url('zone-livraison/save') ?>">
                            <input type="hidden" name="id_zone_liv" value="<?= isset($zone) ? $zone->id_zone_liv : '' ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de la zone <span class="text-danger">*</span></label>
                                    <input type="text" name="nom_zone" id="nom_zone" class="form-control" required value="<?= isset($zone) ? htmlspecialchars($zone->nom_zone) : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Province</label>
                                    <select name="id_province" id="id_province" class="form-select">
                                        <option value="">Toutes les provinces</option>
                                        <?php foreach ($provinces as $p): ?>
                                            <option value="<?= $p->id_province ?>" <?= isset($zone) && $zone->id_province == $p->id_province ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($p->province_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Laissez vide pour toutes les provinces</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Communes concernées</label>
                                    <select name="communes_ids[]" id="communes_ids" class="form-select select2" multiple>
                                        <option value="">Sélectionner des communes</option>
                                    </select>
                                    <small class="text-muted">Sélectionnez une province d'abord. Laissez vide pour toutes les communes</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Quartiers concernés</label>
                                    <select name="quartiers_ids[]" id="quartiers_ids" class="form-select select2" multiple disabled>
                                        <option value="">Sélectionner des quartiers</option>
                                    </select>
                                    <small class="text-muted">Sélectionnez des communes d'abord</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Coût de base (FBu) <span class="text-danger">*</span></label>
                                    <input type="number" step="1" name="cout_base" id="cout_base" class="form-control" required value="<?= isset($zone) ? $zone->cout_base : '' ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Seuil livraison gratuite (FBu)</label>
                                    <input type="number" step="1" name="seuil_livraison_gratuite" id="seuil_livraison_gratuite" class="form-control" value="<?= isset($zone) ? $zone->seuil_livraison_gratuite : '' ?>">
                                    <small class="text-muted">Au-dessus de ce montant, livraison gratuite</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Coût par kg supplémentaire (FBu)</label>
                                    <input type="number" step="1" name="cout_par_kg" id="cout_par_kg" class="form-control" value="<?= isset($zone) ? $zone->cout_par_kg : '' ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Délai minimum (jours)</label>
                                    <input type="number" name="delai_min_jours" id="delai_min_jours" class="form-control" value="<?= isset($zone) ? $zone->delai_min_jours : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Délai maximum (jours)</label>
                                    <input type="number" name="delai_max_jours" id="delai_max_jours" class="form-control" value="<?= isset($zone) ? $zone->delai_max_jours : '' ?>">
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" <?= isset($zone) && $zone->est_actif ? 'checked' : (isset($zone) ? '' : 'checked') ?>>
                                <label class="form-check-label">Zone active</label>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($zone) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('zone-livraison') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialiser Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Sélectionner...',
        allowClear: true
    });
    
    // Charger les communes par province
    $('#id_province').on('change', function() {
        const provinceId = $(this).val();
        const communesSelect = $('#communes_ids');
        const quartiersSelect = $('#quartiers_ids');
        
        if (provinceId) {
            $.post('<?= base_url("zone-livraison/get_communes") ?>', {id_province: provinceId}, function(data) {
                let options = '';
                if (data.length > 0) {
                    data.forEach(function(commune) {
                        const selected = <?= isset($zone) && !empty($zone->communes_ids) ? 'true' : 'false' ?>;
                        const isSelected = selected && <?= isset($zone) ? '$.inArray("' . '" + commune.id_commune + "' . '", ' . json_encode($zone->communes_ids) . ') !== -1' : 'false' ?>;
                        options += `<option value="${commune.id_commune}" ${isSelected ? 'selected' : ''}>${commune.commune_name}</option>`;
                    });
                }
                communesSelect.html(options).prop('disabled', false);
                communesSelect.trigger('change');
            }, 'json');
        } else {
            communesSelect.html('<option value="">Sélectionner des communes</option>').prop('disabled', true);
            quartiersSelect.html('<option value="">Sélectionner des quartiers</option>').prop('disabled', true);
        }
    });
    
    // Charger les quartiers par communes sélectionnées
    $('#communes_ids').on('change', function() {
        const communesIds = $(this).val();
        const quartiersSelect = $('#quartiers_ids');
        
        if (communesIds && communesIds.length > 0) {
            // Charger les quartiers pour chaque commune
            let allQuartiers = [];
            let requests = communesIds.map(function(communeId) {
                return $.post('<?= base_url("zone-livraison/get_quartiers") ?>', {id_commune: communeId});
            });
            
            $.when.apply($, requests).done(function() {
                let options = '';
                let quartiersSet = new Set();
                
                for (let i = 0; i < arguments.length; i++) {
                    const data = arguments[i][0];
                    if (data.length > 0) {
                        data.forEach(function(quartier) {
                            if (!quartiersSet.has(quartier.id_quartier)) {
                                quartiersSet.add(quartier.id_quartier);
                                const selected = <?= isset($zone) && !empty($zone->quartiers_ids) ? 'true' : 'false' ?>;
                                const isSelected = selected && <?= isset($zone) ? '$.inArray("' . '" + quartier.id_quartier + "' . '", ' . json_encode($zone->quartiers_ids) . ') !== -1' : 'false' ?>;
                                options += `<option value="${quartier.id_quartier}" ${isSelected ? 'selected' : ''}>${quartier.quartier_name}</option>`;
                            }
                        });
                    }
                }
                
                if (options) {
                    quartiersSelect.html(options).prop('disabled', false);
                } else {
                    quartiersSelect.html('<option value="">Aucun quartier disponible</option>').prop('disabled', true);
                }
                quartiersSelect.trigger('change');
            });
        } else {
            quartiersSelect.html('<option value="">Sélectionner des quartiers</option>').prop('disabled', true);
        }
    });
    
    // Déclencher le chargement initial si en mode édition
    <?php if (isset($zone) && $zone->id_province): ?>
        $('#id_province').trigger('change');
        setTimeout(() => {
            $('#communes_ids').trigger('change');
        }, 500);
    <?php endif; ?>
});

// Validation du formulaire
$('#zoneForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!$('#nom_zone').val()) {
        Swal.fire('Erreur', 'Le nom de la zone est requis', 'error');
        return;
    }
    if (!$('#cout_base').val() || parseFloat($('#cout_base').val()) < 0) {
        Swal.fire('Erreur', 'Le coût de base est requis et doit être supérieur ou égal à 0', 'error');
        return;
    }
    
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');
    
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => {
                window.location.href = '<?= base_url("zone-livraison") ?>';
            });
        } else {
            Swal.fire('Erreur', response.message, 'error');
            submitBtn.prop('disabled', false).html('<?= isset($zone) ? "Mettre à jour" : "Enregistrer" ?>');
        }
    }, 'json').fail(function() {
        Swal.fire('Erreur', 'Erreur de connexion au serveur', 'error');
        submitBtn.prop('disabled', false).html('<?= isset($zone) ? "Mettre à jour" : "Enregistrer" ?>');
    });
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>