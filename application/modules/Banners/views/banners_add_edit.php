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
                            <i class="bx bx-image-alt me-2"></i>
                            <?= isset($banner) ? 'Modifier la bannière' : 'Ajouter une bannière' ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <?php if (isset($banner)): ?>
                            <button type="button" class="btn btn-sm btn-info" onclick="previewBanner()">
                                <i class="bx bx-show me-1"></i>Aperçu
                            </button>
                            <?php endif; ?>
                            <a href="<?= base_url('banners') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bx bx-error-circle me-2"></i><?= $this->session->flashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate id="bannerForm">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Titre <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" required 
                                           value="<?= isset($banner) ? htmlspecialchars($banner->title, ENT_QUOTES, 'UTF-8') : '' ?>"
                                           maxlength="200">
                                    <div class="invalid-feedback">Veuillez saisir un titre</div>
                                    <small class="text-muted">Maximum 200 caractères</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Position <span class="text-danger">*</span></label>
                                    <select name="position" class="form-select" required id="positionSelect">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="home_main" <?= isset($banner) && $banner->position == 'home_main' ? 'selected' : '' ?>>
                                            🏠 Accueil principal (Slider)
                                        </option>
                                        <option value="home_bottom" <?= isset($banner) && $banner->position == 'home_bottom' ? 'selected' : '' ?>>
                                            📌 Accueil bas
                                        </option>
                                        <option value="home_top_right" <?= isset($banner) && $banner->position == 'home_top_right' ? 'selected' : '' ?>>
                                            📌 Accueil haut droite
                                        </option>
                                        <option value="category" <?= isset($banner) && $banner->position == 'category' ? 'selected' : '' ?>>
                                            📁 Catégorie
                                        </option>
                                        <option value="product" <?= isset($banner) && $banner->position == 'product' ? 'selected' : '' ?>>
                                            📦 Produit
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">Veuillez sélectionner une position</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Sous-titre</label>
                                <input type="text" name="subtitle" class="form-control" placeholder="Optionnel"
                                       value="<?= isset($banner) ? htmlspecialchars($banner->subtitle, ENT_QUOTES, 'UTF-8') : '' ?>"
                                       maxlength="500">
                                <small class="text-muted">Maximum 500 caractères</small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Lien (URL)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-link"></i></span>
                                        <input type="url" name="link" class="form-control" placeholder="https://..."
                                               value="<?= isset($banner) ? htmlspecialchars($banner->link, ENT_QUOTES, 'UTF-8') : '' ?>">
                                    </div>
                                    <small class="text-muted">Laisser vide pour ne pas ajouter de lien</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ordre d'affichage</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-sort"></i></span>
                                        <input type="number" name="ordre_affichage" class="form-control" min="0" max="999"
                                               value="<?= isset($banner) ? (int)$banner->ordre_affichage : '0' ?>">
                                    </div>
                                    <small class="text-muted">Plus le chiffre est petit, plus la bannière remonte</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de début</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                        <input type="datetime-local" name="date_debut" class="form-control"
                                               value="<?= isset($banner) && $banner->date_debut ? date('Y-m-d\TH:i', strtotime($banner->date_debut)) : '' ?>">
                                    </div>
                                    <small class="text-muted">Laisser vide pour activation immédiate</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de fin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-calendar-x"></i></span>
                                        <input type="datetime-local" name="date_fin" class="form-control"
                                               value="<?= isset($banner) && $banner->date_fin ? date('Y-m-d\TH:i', strtotime($banner->date_fin)) : '' ?>">
                                    </div>
                                    <small class="text-muted">Laisser vide pour pas de date d'expiration</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Image de la bannière <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" 
                                           id="imageInput" <?= isset($banner) ? '' : 'required' ?>>
                                    <button type="button" class="btn btn-outline-secondary" id="clearImageBtn" style="display: none;">
                                        <i class="bx bx-trash"></i> Supprimer
                                    </button>
                                </div>
                                <small class="text-muted">Formats acceptés: JPG, PNG, GIF, WEBP. Taille recommandée: 1920x600px (max 5MB)</small>
                                
                                <!-- Image actuelle -->
                                <?php if (isset($banner) && !empty($banner->image) && file_exists(FCPATH . $banner->image)): ?>
                                    <div class="mt-3 p-2 bg-light rounded d-inline-block" id="currentImage">
                                        <img src="<?= base_url($banner->image) ?>" alt="Image actuelle" style="height: 80px; width: auto; border-radius: 8px;">
                                        <div class="small text-muted mt-1">Image actuelle</div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Zone d'aperçu pour nouvelle image -->
                                <div id="previewContainer" class="mt-3" style="display: none;">
                                    <div class="p-2 bg-light rounded d-inline-block position-relative">
                                        <img id="imagePreview" src="#" alt="Aperçu" style="height: 80px; width: auto; border-radius: 8px;">
                                        <div class="small text-muted mt-1">Nouvelle image</div>
                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" 
                                                onclick="clearPreview()" style="padding: 2px 6px;">
                                            <i class="bx bx-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="est_actif" class="form-check-input" value="1" id="est_actif"
                                               <?= isset($banner) && $banner->est_actif ? 'checked' : (isset($banner) ? '' : 'checked') ?>>
                                        <label class="form-check-label" for="est_actif">
                                            <i class="bx bx-check-circle text-success me-1"></i>Bannière active
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($banner) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('banners') ?>" class="btn btn-secondary">
                                    <i class="bx bx-x me-1"></i>Annuler
                                </a>
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
// Validation du formulaire Bootstrap
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

// Aperçu de l'image avant upload
const imageInput = document.getElementById('imageInput');
const previewContainer = document.getElementById('previewContainer');
const imagePreview = document.getElementById('imagePreview');
const currentImage = document.getElementById('currentImage');
const clearImageBtn = document.getElementById('clearImageBtn');

if (imageInput) {
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Vérifier la taille (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Fichier trop volumineux',
                    text: 'L\'image ne doit pas dépasser 5MB',
                    confirmButtonColor: '#ff6600'
                });
                imageInput.value = '';
                return;
            }
            
            // Vérifier le type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format non supporté',
                    text: 'Utilisez JPG, PNG, GIF ou WEBP',
                    confirmButtonColor: '#ff6600'
                });
                imageInput.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block';
                if (clearImageBtn) clearImageBtn.style.display = 'inline-block';
                // Cacher l'image actuelle si elle existe
                if (currentImage) currentImage.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            clearPreview();
        }
    });
}

function clearPreview() {
    previewContainer.style.display = 'none';
    imagePreview.src = '#';
    imageInput.value = '';
    if (clearImageBtn) clearImageBtn.style.display = 'none';
    if (currentImage) currentImage.style.display = 'block';
}

// Confirmation avant de quitter si des modifications non enregistrées
let formModified = false;
document.querySelectorAll('#bannerForm input, #bannerForm select, #bannerForm textarea').forEach(field => {
    field.addEventListener('change', () => formModified = true);
});

window.addEventListener('beforeunload', (e) => {
    if (formModified) {
        e.preventDefault();
        e.returnValue = '';
    }
});

document.getElementById('bannerForm').addEventListener('submit', () => formModified = false);

// Aperçu de la bannière (pour la modification)
function previewBanner() {
    const title = document.querySelector('input[name="title"]').value || 'Aperçu';
    let imageUrl = '';
    
    // Vérifier si une nouvelle image a été sélectionnée
    if (imageInput.files && imageInput.files[0]) {
        imageUrl = URL.createObjectURL(imageInput.files[0]);
    } else {
        <?php if (isset($banner) && !empty($banner->image)): ?>
            imageUrl = '<?= base_url($banner->image) ?>';
        <?php else: ?>
            imageUrl = '';
        <?php endif; ?>
    }
    
    if (!imageUrl) {
        Swal.fire({
            icon: 'warning',
            title: 'Aucune image',
            text: 'Veuillez sélectionner une image',
            confirmButtonColor: '#ff6600'
        });
        return;
    }
    
    Swal.fire({
        title: title,
        imageUrl: imageUrl,
        imageWidth: '100%',
        imageHeight: 'auto',
        imageAlt: title,
        confirmButtonColor: '#ff6600',
        confirmButtonText: 'Fermer'
    });
}

// Validation des dates
const dateDebut = document.querySelector('input[name="date_debut"]');
const dateFin = document.querySelector('input[name="date_fin"]');

if (dateDebut && dateFin) {
    dateFin.addEventListener('change', function() {
        if (dateDebut.value && this.value && this.value < dateDebut.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Date invalide',
                text: 'La date de fin ne peut pas être antérieure à la date de début',
                confirmButtonColor: '#ff6600'
            });
            this.value = '';
        }
    });
    
    dateDebut.addEventListener('change', function() {
        if (dateFin.value && this.value && dateFin.value < this.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Date invalide',
                text: 'La date de début ne peut pas être postérieure à la date de fin',
                confirmButtonColor: '#ff6600'
            });
            dateFin.value = '';
        }
    });
}

// Position description
document.getElementById('positionSelect')?.addEventListener('change', function() {
    const position = this.value;
    let description = '';
    let tailleRecommandee = '';
    
    switch(position) {
        case 'home_main':
            description = 'Slider principal de la page d\'accueil';
            tailleRecommandee = '1920 x 600px';
            break;
        case 'home_bottom':
            description = 'Bannière en bas de la page d\'accueil';
            tailleRecommandee = '300 x 200px';
            break;
        case 'home_top_right':
            description = 'Bannière en haut à droite de la page d\'accueil';
            tailleRecommandee = '300 x 250px';
            break;
        case 'category':
            description = 'Bannière dans les pages catégories';
            tailleRecommandee = '1200 x 400px';
            break;
        case 'product':
            description = 'Bannière dans les pages produits';
            tailleRecommandee = '800 x 400px';
            break;
        default:
            description = '';
    }
    
    if (description) {
        const existingHelp = document.querySelector('.position-help');
        if (existingHelp) existingHelp.remove();
        
        const helpText = document.createElement('small');
        helpText.className = 'text-muted d-block mt-1 position-help';
        helpText.innerHTML = `<i class="bx bx-info-circle me-1"></i>${description} - Taille recommandée: ${tailleRecommandee}`;
        this.parentNode.appendChild(helpText);
    }
});
</script>

<style>
/* Style pour l'aperçu de l'image */
.image-preview {
    max-width: 200px;
    transition: all 0.3s ease;
}
.image-preview:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Style pour le switch */
.form-switch .form-check-input:checked {
    background-color: #ff6600;
    border-color: #ff6600;
}

/* Style pour les champs requis */
.form-label .text-danger {
    font-size: 14px;
}

/* Position absolute pour le bouton de suppression */
.position-absolute {
    position: absolute;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>