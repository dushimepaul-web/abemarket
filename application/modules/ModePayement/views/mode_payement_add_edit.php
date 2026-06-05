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
                            <iconify-icon icon="solar:card-bold-duotone" class="me-2"></iconify-icon>
                            <?= isset($mode) ? 'Modifier le mode de paiement' : 'Ajouter un mode de paiement' ?>
                        </h4>
                        <a href="<?= base_url('mode-payement') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control text-uppercase" required 
                                           value="<?= isset($mode) ? htmlspecialchars($mode->code) : '' ?>"
                                           placeholder="EX: BANCOBU, LUMICASH...">
                                    <small class="text-muted">Code unique en majuscules</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <input type="text" name="description" class="form-control" required 
                                           value="<?= isset($mode) ? htmlspecialchars($mode->description) : '' ?>"
                                           placeholder="Description du mode de paiement">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Sélectionner</option>
                                        <option value="mobile_money" <?= isset($mode) && $mode->type == 'mobile_money' ? 'selected' : '' ?>>📱 Mobile Money</option>
                                        <option value="carte_bancaire" <?= isset($mode) && $mode->type == 'carte_bancaire' ? 'selected' : '' ?>>💳 Carte bancaire</option>
                                        <option value="virement" <?= isset($mode) && $mode->type == 'virement' ? 'selected' : '' ?>>🏦 Virement</option>
                                        <option value="especes_livraison" <?= isset($mode) && $mode->type == 'especes_livraison' ? 'selected' : '' ?>>💰 Espèces à la livraison</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Logo</label>
                                    <input type="file" name="logo_url" class="form-control" accept="image/*">
                                    <small class="text-muted">Formats: JPG, PNG, GIF, WEBP</small>
                                    <?php if (isset($mode) && $mode->logo_url && file_exists(FCPATH . $mode->logo_url)): ?>
                                        <div class="mt-2">
                                            <img src="<?= base_url($mode->logo_url) ?>" alt="Logo" style="height: 50px;">
                                            <small class="text-muted d-block">Logo actuel</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Frais fixe (FBu)</label>
                                    <input type="number" step="1" name="frais_fixe" class="form-control" 
                                           value="<?= isset($mode) ? $mode->frais_fixe : 0 ?>">
                                    <small class="text-muted">Frais fixes par transaction</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Frais en pourcentage (%)</label>
                                    <input type="number" step="0.01" name="frais_pourcentage" class="form-control" 
                                           value="<?= isset($mode) ? $mode->frais_pourcentage : 0 ?>">
                                    <small class="text-muted">Ex: 1.50 pour 1.5%</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ordre d'affichage</label>
                                    <input type="number" name="ordre_affichage" class="form-control" 
                                           value="<?= isset($mode) ? $mode->ordre_affichage : 0 ?>">
                                    <small class="text-muted">Plus le chiffre est petit, plus il remonte</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Instructions de paiement</label>
                                <textarea name="instructions" class="form-control" rows="4" 
                                          placeholder="Instructions pour le client..."><?= isset($mode) ? htmlspecialchars($mode->instructions) : '' ?></textarea>
                                <small class="text-muted">Informations pour aider le client à effectuer le paiement</small>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="est_actif" class="form-check-input" value="1" 
                                           <?= isset($mode) && $mode->est_actif ? 'checked' : (isset($mode) ? '' : 'checked') ?>>
                                    <label class="form-check-label">Mode de paiement actif</label>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2 fs-18"></iconify-icon>
                                <strong>Informations :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>💳 Les frais sont additionnés (frais fixe + pourcentage)</li>
                                    <li>📱 Le code sera automatiquement converti en majuscules</li>
                                    <li>🖼️ Le logo est optionnel mais recommandé pour l'affichage</li>
                                </ul>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i><?= isset($mode) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('mode-payement') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>