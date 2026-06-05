<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1"><?= isset($variante) ? 'Modifier la variante' : 'Ajouter une variante' ?> - <?= htmlspecialchars($produit->nom_produit) ?></h4>
                        <a href="<?= base_url('VarianteProduit/index/' . $produit->slug_produit) ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="varianteForm" method="post" action="<?= base_url('VarianteProduit/save') ?>">
                            <input type="hidden" name="slug_produit" value="<?= $produit->slug_produit ?>">
                            <input type="hidden" name="id_variante" value="<?= isset($variante) ? $variante->id_variante : '' ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SKU <span class="text-danger">*</span></label>
                                    <input type="text" name="sku" id="sku" class="form-control" required value="<?= isset($variante) ? htmlspecialchars($variante->sku) : '' ?>">
                                    <small class="text-muted" id="sku_error"></small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prix <span class="text-danger">*</span></label>
                                    <input type="number" step="1" name="prix" id="prix" class="form-control" required value="<?= isset($variante) ? $variante->prix : $produit->prix_base ?>">
                                    <small class="text-muted">Prix en Francs Burundais (FBu)</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quantité initiale</label>
                                    <input type="number" name="quantite_actuelle" id="quantite_actuelle" class="form-control" value="<?= isset($variante) ? $variante->quantite_actuelle : '0' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" <?= isset($variante) && $variante->est_actif ? 'checked' : (isset($variante) ? '' : 'checked') ?>>
                                        <label class="form-check-label">Variante active</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Attributs de la variante</label>
                                <div id="attributs-container">
                                    <?php if (isset($variante) && !empty($variante->attributs)): ?>
                                        <?php foreach ($variante->attributs as $type => $valeur): ?>
                                        <div class="row mb-2 attribut-row">
                                            <div class="col-md-5">
                                                <select name="attribut_type[]" class="form-select">
                                                    <option value="">Sélectionner</option>
                                                    <?php foreach ($types_attributs as $key => $label): ?>
                                                        <option value="<?= $key ?>" <?= $type == $key ? 'selected' : '' ?>><?= $label ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="attribut_valeur[]" class="form-control" placeholder="Valeur" value="<?= htmlspecialchars($valeur) ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-attribut">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                </button>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="row mb-2 attribut-row">
                                            <div class="col-md-5">
                                                <select name="attribut_type[]" class="form-select">
                                                    <option value="">Sélectionner</option>
                                                    <?php foreach ($types_attributs as $key => $label): ?>
                                                        <option value="<?= $key ?>"><?= $label ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="attribut_valeur[]" class="form-control" placeholder="Valeur">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger btn-sm remove-attribut">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-attribut">
                                    <i class="bx bx-plus me-1"></i>Ajouter un attribut
                                </button>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($variante) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('VarianteProduit/index/' . $produit->slug_produit) ?>" class="btn btn-secondary">Annuler</a>
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
// Vérifier SKU unique
$('#sku').on('blur', function() {
    const sku = $(this).val();
    const exclude_id = $('input[name="id_variante"]').val();
    
    if (sku) {
        $.post('<?= base_url("VarianteProduit/check_sku") ?>', {sku: sku, exclude_id: exclude_id}, function(response) {
            if (response.exists) {
                $('#sku_error').html('<span class="text-danger">Ce SKU existe déjà</span>');
                $('#submitBtn').prop('disabled', true);
            } else {
                $('#sku_error').html('<span class="text-success">SKU disponible</span>');
                $('#submitBtn').prop('disabled', false);
            }
        }, 'json');
    }
});

// Ajouter un attribut
$('#add-attribut').on('click', function() {
    const html = `
        <div class="row mb-2 attribut-row">
            <div class="col-md-5">
                <select name="attribut_type[]" class="form-select">
                    <option value="">Sélectionner</option>
                    <?php foreach ($types_attributs as $key => $label): ?>
                        <option value="<?= $key ?>"><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="attribut_valeur[]" class="form-control" placeholder="Valeur">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-attribut">
                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                </button>
            </div>
        </div>
    `;
    $('#attributs-container').append(html);
});

// Supprimer un attribut
$(document).on('click', '.remove-attribut', function() {
    $(this).closest('.attribut-row').remove();
});

// Validation du formulaire
$('#varianteForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!$('#sku').val()) {
        Swal.fire('Erreur', 'Le SKU est requis', 'error');
        return;
    }
    if (!$('#prix').val() || parseFloat($('#prix').val()) <= 0) {
        Swal.fire('Erreur', 'Le prix est requis et doit être supérieur à 0', 'error');
        return;
    }
    
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');
    
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => {
                window.location.href = '<?= base_url("VarianteProduit/index/" . $produit->slug_produit) ?>';
            });
        } else {
            Swal.fire('Erreur', response.message, 'error');
            submitBtn.prop('disabled', false).html('<?= isset($variante) ? "Mettre à jour" : "Enregistrer" ?>');
        }
    }, 'json').fail(function() {
        Swal.fire('Erreur', 'Erreur de connexion au serveur', 'error');
        submitBtn.prop('disabled', false).html('<?= isset($variante) ? "Mettre à jour" : "Enregistrer" ?>');
    });
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>