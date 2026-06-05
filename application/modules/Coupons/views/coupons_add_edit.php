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
                            <iconify-icon icon="solar:ticket-bold-duotone" class="me-2"></iconify-icon>
                            <?= isset($coupon) ? 'Modifier le coupon' : 'Ajouter un coupon' ?>
                        </h4>
                        <a href="<?= base_url('coupons') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Code du coupon <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control text-uppercase" required 
                                           value="<?= isset($coupon) ? htmlspecialchars($coupon->code) : '' ?>"
                                           placeholder="EX: PROMO20">
                                    <small class="text-muted">Code unique, lettres et chiffres sans espaces</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" name="description" class="form-control" 
                                           value="<?= isset($coupon) ? htmlspecialchars($coupon->description) : '' ?>"
                                           placeholder="Description du coupon">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Type de réduction <span class="text-danger">*</span></label>
                                    <select name="type_reduction" id="type_reduction" class="form-select" required>
                                        <option value="pourcentage" <?= isset($coupon) && $coupon->type_reduction == 'pourcentage' ? 'selected' : '' ?>>Pourcentage (%)</option>
                                        <option value="montant_fixe" <?= isset($coupon) && $coupon->type_reduction == 'montant_fixe' ? 'selected' : '' ?>>Montant fixe (FBu)</option>
                                        <option value="livraison_gratuite" <?= isset($coupon) && $coupon->type_reduction == 'livraison_gratuite' ? 'selected' : '' ?>>Livraison gratuite</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" id="valeur_label">Valeur de réduction <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="valeur_reduction" id="valeur_reduction" class="form-control" required 
                                           value="<?= isset($coupon) ? $coupon->valeur_reduction : '' ?>">
                                    <small id="valeur_small" class="text-muted"></small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Montant minimum d'achat</label>
                                    <input type="number" step="1" name="montant_min_achat" class="form-control" 
                                           value="<?= isset($coupon) ? $coupon->montant_min_achat : '' ?>"
                                           placeholder="Laisser vide = aucun minimum">
                                    <small class="text-muted">Montant minimum pour utiliser le coupon</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Montant maximum de réduction</label>
                                    <input type="number" step="1" name="montant_max_reduction" class="form-control" 
                                           value="<?= isset($coupon) ? $coupon->montant_max_reduction : '' ?>"
                                           placeholder="Laisser vide = sans limite">
                                    <small class="text-muted">Uniquement pour les réductions en pourcentage</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Limite d'utilisation totale</label>
                                    <input type="number" name="limite_utilisation" class="form-control" 
                                           value="<?= isset($coupon) ? $coupon->limite_utilisation : '' ?>"
                                           placeholder="Laisser vide = illimité">
                                    <small class="text-muted">Nombre total de fois que ce coupon peut être utilisé</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Limite par utilisateur</label>
                                    <input type="number" name="limite_par_utilisateur" class="form-control" 
                                           value="<?= isset($coupon) ? $coupon->limite_par_utilisateur : '1' ?>">
                                    <small class="text-muted">Nombre de fois qu'un même client peut l'utiliser</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="date_debut" class="form-control" required 
                                           value="<?= isset($coupon) ? date('Y-m-d\TH:i', strtotime($coupon->date_debut)) : date('Y-m-d\TH:i') ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de fin <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="date_fin" class="form-control" required 
                                           value="<?= isset($coupon) ? date('Y-m-d\TH:i', strtotime($coupon->date_fin)) : date('Y-m-d\TH:i', strtotime('+1 month')) ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Applicable à</label>
                                    <select name="applicable_a" id="applicable_a" class="form-select">
                                        <option value="tout" <?= isset($coupon) && $coupon->applicable_a == 'tout' ? 'selected' : '' ?>>Tous les produits</option>
                                        <option value="categories" <?= isset($coupon) && $coupon->applicable_a == 'categories' ? 'selected' : '' ?>>Catégories spécifiques</option>
                                        <option value="produits" <?= isset($coupon) && $coupon->applicable_a == 'produits' ? 'selected' : '' ?>>Produits spécifiques</option>
                                        <option value="vendeurs" <?= isset($coupon) && $coupon->applicable_a == 'vendeurs' ? 'selected' : '' ?>>Vendeurs spécifiques</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="est_actif" class="form-check-input" value="1" 
                                               <?= isset($coupon) && $coupon->est_actif ? 'checked' : (isset($coupon) ? '' : 'checked') ?>>
                                        <label class="form-check-label">Coupon actif</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Section pour les éléments applicables (cachée par défaut) -->
                            <div id="applicables_container" class="mb-3" style="display: none;">
                                <div class="border p-3 rounded bg-light">
                                    <label class="form-label fw-bold">Éléments applicables</label>
                                    <div id="applicables_list"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="btnAjouterApplicable">
                                        <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Ajouter
                                    </button>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-3">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2 fs-18"></iconify-icon>
                                <strong>Informations :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>📝 Le code sera automatiquement converti en majuscules</li>
                                    <li>⏰ Les dates sont à définir selon le fuseau horaire du serveur</li>
                                    <li>💰 Pour les réductions en pourcentage, le montant max de réduction est optionnel</li>
                                    <li>🔄 Les limites d'utilisation aident à contrôler la promotion</li>
                                </ul>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i><?= isset($coupon) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('coupons') ?>" class="btn btn-secondary">Annuler</a>
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
// Changer le label en fonction du type de réduction
$('#type_reduction').on('change', function() {
    const type = $(this).val();
    if (type === 'pourcentage') {
        $('#valeur_label').html('Valeur de réduction (%) <span class="text-danger">*</span>');
        $('#valeur_small').html('Ex: 20 pour 20% de réduction');
        $('#valeur_reduction').attr('step', '0.01');
        $('#valeur_reduction').attr('max', '100');
    } else if (type === 'montant_fixe') {
        $('#valeur_label').html('Valeur de réduction (FBu) <span class="text-danger">*</span>');
        $('#valeur_small').html('Ex: 5000 pour 5000 FBu de réduction');
        $('#valeur_reduction').attr('step', '1');
        $('#valeur_reduction').removeAttr('max');
    } else {
        $('#valeur_label').html('Valeur (livraison gratuite)');
        $('#valeur_small').html('La livraison sera gratuite');
        $('#valeur_reduction').val(0);
    }
});
$('#type_reduction').trigger('change');

// Gérer l'affichage de la section "applicable à"
$('#applicable_a').on('change', function() {
    const val = $(this).val();
    if (val === 'tout') {
        $('#applicables_container').hide();
    } else {
        $('#applicables_container').show();
        chargerElementsApplicables(val);
    }
});
$('#applicable_a').trigger('change');

// Charger les éléments applicables
function chargerElementsApplicables(type) {
    let url = '';
    let title = '';
    if (type === 'categories') {
        url = '<?= base_url("coupons/get_categories") ?>';
        title = 'Catégories';
    } else if (type === 'produits') {
        url = '<?= base_url("coupons/get_produits") ?>';
        title = 'Produits';
    } else if (type === 'vendeurs') {
        url = '<?= base_url("coupons/get_vendeurs") ?>';
        title = 'Vendeurs';
    }
    
    if (url) {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let html = `<label class="form-label">Sélectionner les ${title.toLowerCase()}</label>`;
                html += '<select id="applicables_select" class="form-select" multiple size="5">';
                data.forEach(function(item) {
                    html += `<option value="${item.id}">${item.nom}</option>`;
                });
                html += '</select>';
                html += '<div class="mt-2" id="applicables_selected"></div>';
                $('#applicables_list').html(html);
                
                // Charger les valeurs existantes
                <?php if (isset($coupon) && $coupon->ids_applicables): ?>
                    const ids = <?= json_encode(json_decode($coupon->ids_applicables ?? '[]')) ?>;
                    ids.forEach(function(id) {
                        $('#applicables_select option[value="' + id + '"]').attr('selected', 'selected');
                    });
                    $('#btnAjouterApplicable').hide();
                <?php else: ?>
                    $('#btnAjouterApplicable').show();
                <?php endif; ?>
            }
        });
    }
}

// Ajouter les sélections
$('#btnAjouterApplicable').on('click', function() {
    const selected = $('#applicables_select').val();
    if (selected && selected.length > 0) {
        let html = '<input type="hidden" name="ids_applicables" value=\'' + JSON.stringify(selected) + '\'>';
        html += '<div class="mt-2">';
        html += '<strong>Sélectionnés:</strong><br>';
        selected.forEach(function(id) {
            html += '<span class="badge bg-primary me-1 mb-1">' + $('#applicables_select option[value="' + id + '"]').text() + '</span>';
        });
        html += '</div>';
        $('#applicables_selected').html(html);
        $('#btnAjouterApplicable').hide();
    } else {
        Swal.fire('Attention', 'Veuillez sélectionner au moins un élément', 'warning');
    }
});

// Validation du formulaire
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<style>
select[multiple] {
    min-height: 150px;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>