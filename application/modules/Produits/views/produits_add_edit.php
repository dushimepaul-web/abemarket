<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <div class="row">
            <!-- Aperçu du produit -->
            <div class="col-xl-3 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <?php 
                            $main_image = null;
                            if(isset($produit) && !empty($images)):
                                foreach($images as $img):
                                    if($img['est_principale'] == 1):
                                        $main_image = $img['url_image'];
                                        break;
                                    endif;
                                endforeach;
                                if(!$main_image && !empty($images)):
                                    $main_image = $images[0]['url_image'];
                                endif;
                            endif;
                            
                            $image_exists = $main_image && file_exists(FCPATH . ltrim($main_image, '/'));
                            ?>
                            <?php if($image_exists): ?>
                                <img src="<?= base_url($main_image) ?>" alt="" class="img-fluid rounded bg-light" style="height: 200px; width: 100%; object-fit: cover;" id="main_image_preview">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <iconify-icon icon="solar:box-bold-duotone" class="fs-64 text-primary"></iconify-icon>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-3">
                            <h4 class="mb-1" id="product_name_preview"><?= isset($produit) ? htmlspecialchars($produit['nom_produit']) : 'Nouveau produit' ?></h4>
                            <p class="text-muted">SKU: <?= isset($produit) ? htmlspecialchars($produit['sku']) : 'A générer' ?></p>
                            <?php if(isset($produit)): ?>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between">
                                    <span>Prix:</span>
                                    <span class="fw-semibold" id="price_preview"><?= number_format($produit['prix_base'], 0, ',', ' ') ?> BIF</span>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <span>Stock:</span>
                                    <span class="fw-semibold"><?= $produit['quantite_actuelle'] ?></span>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <span>Note:</span>
                                    <span class="fw-semibold"><?= $produit['note_moyenne'] ?? 0 ?> ★</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer bg-light-subtle">
                        <div class="row g-2">
                            <div class="col-lg-6">
                                <button type="submit" form="productForm" class="btn btn-primary w-100">
                                    <i class="bx bx-save me-1"></i><?= isset($produit) ? 'Mettre à jour' : 'Créer' ?>
                                </button>
                            </div>
                            <div class="col-lg-6">
                                <a href="<?= base_url('Produits') ?>" class="btn btn-outline-secondary w-100">Annuler</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images existantes -->
                <?php if(isset($produit) && !empty($images)): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">Images existantes</h4>
                    </div>
                    <div class="card-body">
                        <div class="row" id="existing_images_container">
                            <?php foreach($images as $img): 
                                $thumb_path = !empty($img['url_miniature']) ? $img['url_miniature'] : $img['url_image'];
                                $full_thumb_path = FCPATH . ltrim($thumb_path, '/');
                                $thumb_exists = file_exists($full_thumb_path);
                            ?>
                            <div class="col-md-4 mb-2 image-item" data-image-id="<?= $img['id_image'] ?>">
                                <div class="position-relative">
                                    <?php if($thumb_exists): ?>
                                        <img src="<?= base_url($thumb_path) ?>" class="img-fluid rounded" style="height: 100px; width: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <i class="bx bx-image fs-1 text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="position-absolute top-0 end-0 m-1">
                                        <?php if($img['est_principale'] == 1): ?>
                                            <span class="badge bg-success">Principale</span>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-primary set-main-btn" data-image-id="<?= $img['id_image'] ?>" title="Définir comme principale">
                                                <i class="bx bx-star"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-danger delete-image-btn" data-image-id="<?= $img['id_image'] ?>">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Formulaire principal -->
            <div class="col-xl-9 col-lg-8">
                <form action="<?= isset($produit) ? base_url('Produits/edit/'.$produit['slug_produit']) : base_url('Produits/add') ?>" method="POST" enctype="multipart/form-data" id="productForm">
                    
                    <!-- Upload d'images -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Images du produit</h4>
                        </div>
                        <div class="card-body">
                            <div class="fallback">
                                <input name="images[]" type="file" id="image_input" style="display: none;" accept="image/jpeg,image/png,image/gif,image/webp" multiple onchange="previewImages(this)">
                            </div>
                            <div class="dz-message needsclick" onclick="document.getElementById('image_input').click();">
                                <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                <h3 class="mt-4">Déposez vos images ici, ou <span class="text-primary">cliquez pour parcourir</span></h3>
                                <span class="text-muted fs-13">PNG, JPG, GIF, WEBP sont autorisés (Max 2MB)</span>
                            </div>
                            <div id="image_preview" class="mt-3 row"></div>
                        </div>
                    </div>

                    <!-- Informations produit -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informations produit</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label class="form-label">Nom du produit <span class="text-danger">*</span></label>
                                        <input type="text" name="nom_produit" class="form-control" value="<?= isset($produit) ? htmlspecialchars($produit['nom_produit']) : '' ?>" required onkeyup="updatePreview()">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Marque</label>
                                        <input type="text" name="marque" class="form-control" value="<?= isset($produit) ? htmlspecialchars($produit['marque']) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Catégorie</label>
                                        <select name="id_categorie" class="form-select">
                                            <option value="">-- Sélectionner --</option>
                                            <?php foreach($categories as $cat): ?>
                                                <option value="<?= $cat['id_categorie'] ?>" <?= (isset($produit) && $produit['id_categorie'] == $cat['id_categorie']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cat['nom_categorie']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Vendeur</label>
                                        <select name="id_vendeur" class="form-select">
                                            <option value="">-- Sélectionner --</option>
                                            <?php foreach($vendeurs as $vend): ?>
                                                <option value="<?= $vend['id_vendeur'] ?>" <?= (isset($produit) && $produit['id_vendeur'] == $vend['id_vendeur']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($vend['nom_boutique'] . ' (' . $vend['prenom'] . ' ' . $vend['nom'] . ')') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description courte</label>
                                <textarea name="description_courte" class="form-control" rows="2"><?= isset($produit) ? htmlspecialchars($produit['description_courte']) : '' ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description complète</label>
                                <textarea name="description" class="form-control" rows="4"><?= isset($produit) ? htmlspecialchars($produit['description']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Prix et stock -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Prix et stock</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Prix de base <span class="text-danger">*</span></label>
                                        <input type="number" name="prix_base" class="form-control" value="<?= isset($produit) ? $produit['prix_base'] : '' ?>" required onkeyup="updatePreview()">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Prix promo</label>
                                        <input type="number" name="prix_promo" class="form-control" value="<?= isset($produit) ? $produit['prix_promo'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Quantité en stock</label>
                                        <input type="number" name="quantite_actuelle" class="form-control" value="<?= isset($produit) ? $produit['quantite_actuelle'] : '0' ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label class="form-label">Seuil stock bas</label>
                                        <input type="number" name="seuil_stock_bas" class="form-control" value="<?= isset($produit) ? $produit['seuil_stock_bas'] : '5' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Poids (kg)</label>
                                        <input type="number" name="poids_kg" class="form-control" step="0.001" value="<?= isset($produit) ? $produit['poids_kg'] : '' ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Date début promo</label>
                                        <input type="date" name="date_debut_promo" class="form-control" value="<?= isset($produit) && $produit['date_debut_promo'] ? date('Y-m-d', strtotime($produit['date_debut_promo'])) : '' ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Date fin promo</label>
                                        <input type="date" name="date_fin_promo" class="form-control" value="<?= isset($produit) && $produit['date_fin_promo'] ? date('Y-m-d', strtotime($produit['date_fin_promo'])) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paramètres -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Paramètres</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Type de produit</label>
                                        <select name="type_produit" class="form-select">
                                            <option value="simple" <?= (isset($produit) && $produit['type_produit'] == 'simple') ? 'selected' : '' ?>>Simple</option>
                                            <option value="variable" <?= (isset($produit) && $produit['type_produit'] == 'variable') ? 'selected' : '' ?>>Variable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Statut</label>
                                        <select name="statut" class="form-select">
                                            <option value="brouillon" <?= (isset($produit) && $produit['statut'] == 'brouillon') ? 'selected' : '' ?>>Brouillon</option>
                                            <option value="actif" <?= (isset($produit) && $produit['statut'] == 'actif') ? 'selected' : '' ?>>Actif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label">Visibilité</label>
                                        <select name="est_actif" class="form-select">
                                            <option value="1" <?= (isset($produit) && $produit['est_actif'] == 1) ? 'selected' : '' ?>>Visible</option>
                                            <option value="0" <?= (isset($produit) && $produit['est_actif'] == 0) ? 'selected' : '' ?>>Caché</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-light mb-3 rounded">
                        <div class="row justify-content-end g-2">
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($produit) ? 'Mettre à jour' : 'Créer' ?>
                                </button>
                            </div>
                            <div class="col-lg-2">
                                <a href="<?= base_url('Produits') ?>" class="btn btn-outline-secondary w-100">Annuler</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <script>document.write(new Date().getFullYear())</script> &copy; ABEMARKET - Plateforme e-commerce
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
// Fonction AJAX sans jQuery
function ajaxRequest(url, method, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    callback(response);
                } catch(e) {
                    console.error('Erreur JSON:', e);
                    callback({success: false, message: 'Erreur de réponse serveur'});
                }
            } else {
                callback({success: false, message: 'Erreur HTTP: ' + xhr.status});
            }
        }
    };
    xhr.send();
}

// Prévisualisation des images
function previewImages(input) {
    const preview = document.getElementById('image_preview');
    const existingCount = document.querySelectorAll('#image_preview .col-md-3').length;
    
    if (input.files && input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            if (!file.type.match('image.*')) {
                alert('Seules les images sont autorisées');
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) {
                alert('L\'image ' + file.name + ' dépasse 2MB');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 col-sm-4 col-6 mt-2';
                col.innerHTML = `
                    <div class="position-relative border rounded p-1">
                        <img src="${e.target.result}" class="img-fluid rounded" style="height: 120px; width: 100%; object-fit: cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle" 
                                style="width: 25px; height: 25px; padding: 0; line-height: 1;"
                                onclick="this.closest('.col-md-3').remove()">
                            <i class="bx bx-x" style="font-size: 16px;"></i>
                        </button>
                        ${(existingCount === 0 && index === 0) ? '<span class="badge bg-primary position-absolute bottom-0 start-0 m-1">Principale</span>' : ''}
                    </div>
                `;
                preview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }
}

function updatePreview() {
    const productName = document.querySelector('input[name="nom_produit"]');
    const productPrice = document.querySelector('input[name="prix_base"]');
    const previewName = document.getElementById('product_name_preview');
    const previewPrice = document.getElementById('price_preview');
    
    if(productName && previewName) {
        previewName.textContent = productName.value || 'Nouveau produit';
    }
    if(productPrice && previewPrice && productPrice.value) {
        previewPrice.textContent = new Intl.NumberFormat('fr-FR').format(productPrice.value) + ' BIF';
    }
}

// Supprimer une image
function deleteImage(imageId) {
    if(confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        ajaxRequest('<?= base_url("Produits/delete_image") ?>/' + imageId, 'POST', function(response) {
            if(response.success) {
                var imageElement = document.querySelector('.image-item[data-image-id="' + imageId + '"]');
                if(imageElement) {
                    imageElement.style.opacity = '0';
                    setTimeout(function() {
                        imageElement.remove();
                    }, 300);
                }
                toastr.success('Image supprimée avec succès');
                
                if(response.redirect_slug) {
                    setTimeout(function() {
                        window.location.href = '<?= base_url("Produits/edit/") ?>' + response.redirect_slug;
                    }, 1000);
                }
            } else {
                toastr.error('Erreur lors de la suppression');
            }
        });
    }
}

// Définir l'image principale
function setMainImage(imageId) {
    ajaxRequest('<?= base_url("Produits/set_main_image") ?>/' + imageId, 'POST', function(response) {
        if(response.success) {
            toastr.success('Image principale mise à jour');
            location.reload();
        } else {
            toastr.error('Erreur lors de la définition de l\'image principale');
        }
    });
}

// Attacher les événements après le chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Événements pour les boutons de suppression
    var deleteButtons = document.querySelectorAll('.delete-image-btn');
    deleteButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var imageId = this.getAttribute('data-image-id');
            deleteImage(imageId);
        });
    });
    
    // Événements pour les boutons "définir comme principale"
    var setMainButtons = document.querySelectorAll('.set-main-btn');
    setMainButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var imageId = this.getAttribute('data-image-id');
            setMainImage(imageId);
        });
    });
    
    // Empêcher la soumission multiple
    var form = document.getElementById('productForm');
    var submitBtn = document.getElementById('submitBtn');
    
    if(form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Chargement...';
        });
    }
});

// Messages flash avec Toastr
<?php if($this->session->flashdata('success')): ?>
    toastr.success('<?= addslashes($this->session->flashdata('success')) ?>');
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    toastr.error('<?= addslashes($this->session->flashdata('error')) ?>');
<?php endif; ?>
</script>

<style>
.dz-message {
    text-align: center;
    padding: 30px;
    border: 2px dashed #ddd;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s;
}
.dz-message:hover {
    border-color: #556ee6;
    background-color: #f8f9fa;
}
.image-item {
    transition: opacity 0.3s ease;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>