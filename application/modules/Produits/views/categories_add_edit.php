<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1"><?= isset($categorie) ? 'Modifier la catégorie' : 'Ajouter une catégorie' ?></h4>
                        <a href="<?= base_url('categories') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                                    <input type="text" name="nom_categorie" class="form-control" required 
                                           value="<?= isset($categorie) ? htmlspecialchars($categorie->nom_categorie) : '' ?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Catégorie parente</label>
                                    <select name="id_parent" id="id_parent" class="form-select">
                                        <option value="">-- Aucune (catégorie principale - Niveau 0) --</option>
                                        <?php if (!empty($categories_parent)): ?>
                                            <?php foreach ($categories_parent as $parent): ?>
                                                <?php 
                                                // Ne pas inclure la catégorie elle-même (pour l'édition)
                                                if (isset($categorie) && $parent->id_categorie == $categorie->id_categorie) continue;
                                                
                                                // Empêcher de dépasser le niveau 3
                                                if ($parent->niveau >= 3) continue;
                                                
                                                // Calculer l'indentation
                                                $indent = '';
                                                for ($i = 0; $i < $parent->niveau; $i++) {
                                                    $indent .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                                                }
                                                
                                                // Icône selon le niveau
                                                $icon = '';
                                                if ($parent->niveau == 0) $icon = '📁 ';
                                                elseif ($parent->niveau == 1) $icon = '📂 ';
                                                elseif ($parent->niveau == 2) $icon = '📄 ';
                                                else $icon = '📌 ';
                                                ?>
                                                <option value="<?= $parent->id_categorie ?>" 
                                                        data-niveau="<?= $parent->niveau ?>"
                                                        <?= isset($categorie) && $categorie->id_parent == $parent->id_categorie ? 'selected' : '' ?>>
                                                    <?= $indent ?><?= $icon ?><?= htmlspecialchars($parent->nom_categorie) ?> 
                                                    (Niveau <?= $parent->niveau ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-muted">
                                        <span class="badge bg-info">📁 Niveau 0</span> = Catégorie principale
                                        <span class="badge bg-secondary ms-2">📂 Niveau 1</span> = Sous-catégorie
                                        <span class="badge bg-warning ms-2">📄 Niveau 2</span> = Sous-sous-catégorie
                                        <span class="badge bg-danger ms-2">⚠️ Niveau max: 3</span>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Icône (classe CSS)</label>
                                    <input type="text" name="icone" class="form-control" placeholder="bx-category"
                                           value="<?= isset($categorie) ? htmlspecialchars($categorie->icone) : '' ?>">
                                    <small class="text-muted">Ex: bx-category, bx-mobile, bx-laptop</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Image de la catégorie</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <small class="text-muted">Formats: JPG, PNG, GIF, WEBP. Max 2MB</small>
                                    <?php if (isset($categorie) && $categorie->url_image && file_exists(FCPATH . $categorie->url_image)): ?>
                                        <div class="mt-2">
                                            <img src="<?= base_url($categorie->url_image) ?>" alt="Image actuelle" style="height: 60px; width: auto; border-radius: 8px;">
                                            <small class="text-muted d-block">Image actuelle</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Niveau (automatique)</label>
                                    <input type="number" name="niveau" id="niveau_automatique" class="form-control" 
                                           min="0" max="3" readonly 
                                           value="<?= isset($categorie) ? $categorie->niveau : '0' ?>">
                                    <small class="text-muted text-info">Calculé automatiquement selon la catégorie parente</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ordre d'affichage</label>
                                    <input type="number" name="ordre_affichage" class="form-control" min="0"
                                           value="<?= isset($categorie) ? $categorie->ordre_affichage : '0' ?>">
                                    <small class="text-muted">Plus le chiffre est petit, plus la catégorie remonte</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Commission spécifique (%)</label>
                                    <input type="number" step="0.01" name="taux_commission_specifique" class="form-control" min="0" max="100"
                                           value="<?= isset($categorie) ? $categorie->taux_commission_specifique : '' ?>">
                                    <small class="text-muted">Laisser vide pour utiliser la commission par défaut</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="est_actif" class="form-check-input" value="1" 
                                               <?= isset($categorie) && $categorie->est_actif ? 'checked' : (isset($categorie) ? '' : 'checked') ?>>
                                        <label class="form-check-label">Catégorie active</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Description de la catégorie..."><?= isset($categorie) ? htmlspecialchars($categorie->description) : '' ?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i><?= isset($categorie) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('categories') ?>" class="btn btn-secondary">Annuler</a>
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
// Calcul automatique du niveau en fonction du parent sélectionné
function calculerNiveauAutomatique() {
    const selectedOption = $('#id_parent option:selected');
    const niveauParent = selectedOption.data('niveau');
    
    if (niveauParent !== undefined && niveauParent !== '') {
        const niveauEnfant = parseInt(niveauParent) + 1;
        $('#niveau_automatique').val(niveauEnfant);
        
        // Vérifier la limite maximale
        if (niveauEnfant > 3) {
            $('#niveau_automatique').addClass('is-invalid');
            Swal.fire({
                title: 'Attention !',
                text: 'Le niveau maximum (3) est atteint. Vous ne pouvez pas créer de sous-catégorie plus profonde.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        } else {
            $('#niveau_automatique').removeClass('is-invalid');
        }
    } else {
        $('#niveau_automatique').val(0);
        $('#niveau_automatique').removeClass('is-invalid');
    }
}

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

$(document).ready(function() {
    // Calculer le niveau au changement de parent
    $('#id_parent').on('change', function() {
        calculerNiveauAutomatique();
    });
    
    // Calculer le niveau au chargement de la page
    calculerNiveauAutomatique();
});
</script>

<style>
select option {
    font-family: monospace;
    padding: 5px;
}
select option[data-niveau="0"] { background-color: #e3f2fd; font-weight: bold; }
select option[data-niveau="1"] { background-color: #f3e5f5; }
select option[data-niveau="2"] { background-color: #fff3e0; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>