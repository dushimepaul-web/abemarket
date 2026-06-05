<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Paramètres du site</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Paramètres</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <!-- Bouton Ajouter -->
        <div class="row mb-3">
            <div class="col-12 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addParamModal">
                    <i class="bx bx-plus me-1"></i>Ajouter un paramètre
                </button>
            </div>
        </div>

        <form action="<?= base_url('Settings/update') ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <!-- Informations générales -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Informations générales</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Nom du site</label>
                                <input type="text" name="site_name" class="form-control" 
                                       value="<?= $settings['site_name']['value'] ?? '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email de contact</label>
                                <input type="email" name="site_email" class="form-control" 
                                       value="<?= $settings['site_email']['value'] ?? '' ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="site_phone" class="form-control" 
                                       value="<?= $settings['site_phone']['value'] ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <textarea name="site_address" class="form-control" rows="3"><?= $settings['site_address']['value'] ?? '' ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pays</label>
                                <input type="text" name="site_country" class="form-control" 
                                       value="<?= $settings['site_country']['value'] ?? 'Burundi' ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logo et Favicon -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Logo et favicon</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Logo du site</label>
                                <input type="file" name="site_logo" class="form-control" accept="image/*">
                                <?php if(isset($settings['site_logo']['value']) && $settings['site_logo']['value']): ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url('attachments/Settings/' . $settings['site_logo']['value']) ?>" 
                                             class="avatar-lg rounded" alt="logo">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Favicon</label>
                                <input type="file" name="site_favicon" class="form-control" accept="image/*">
                                <?php if(isset($settings['site_favicon']['value']) && $settings['site_favicon']['value']): ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url('attachments/Settings/' . $settings['site_favicon']['value']) ?>" 
                                             class="avatar-sm rounded" alt="favicon">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Réseaux sociaux -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Réseaux sociaux</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bxl-facebook"></i></span>
                                    <input type="url" name="site_facebook" class="form-control" 
                                           value="<?= $settings['site_facebook']['value'] ?? '' ?>" placeholder="https://facebook.com/...">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bxl-instagram"></i></span>
                                    <input type="url" name="site_instagram" class="form-control" 
                                           value="<?= $settings['site_instagram']['value'] ?? '' ?>" placeholder="https://instagram.com/...">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">YouTube</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bxl-youtube"></i></span>
                                    <input type="url" name="site_youtube" class="form-control" 
                                           value="<?= $settings['site_youtube']['value'] ?? '' ?>" placeholder="https://youtube.com/...">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">LinkedIn</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bxl-linkedin"></i></span>
                                    <input type="url" name="site_linkedln" class="form-control" 
                                           value="<?= $settings['site_linkedln']['value'] ?? '' ?>" placeholder="https://linkedin.com/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coordonnées GPS -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Coordonnées GPS</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-control" 
                                       value="<?= $settings['latitude']['value'] ?? '-3.3835' ?>" placeholder="-3.3835">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-control" 
                                       value="<?= $settings['longitude']['value'] ?? '29.3719' ?>" placeholder="29.3719">
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Image Popup Newsletter -->
<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Image Popup Newsletter</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Image de la popup</label>
                <input type="file" name="popup_image" class="form-control" accept="image/*">
                <?php if(isset($settings['popup_image']['value']) && $settings['popup_image']['value']): ?>
                    <div class="mt-2">
                        <img src="<?= base_url($settings['popup_image']['value']) ?>" 
                             class="img-fluid rounded" style="max-height: 150px;" alt="popup image">
                        <p class="text-muted small mt-1">Image actuelle : <?= basename($settings['popup_image']['value']) ?></p>
                    </div>
                <?php else: ?>
                    <div class="mt-2">
                        <img src="<?= base_url('assets/images/promo.jpg') ?>" 
                             class="img-fluid rounded" style="max-height: 150px;" alt="popup par défaut">
                        <p class="text-muted small mt-1">Image par défaut</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

                <!-- Configuration email -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Configuration email</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bx bx-info-circle"></i> 
                                Ces paramètres sont utilisés pour l'envoi d'emails depuis la plateforme.
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mot de passe email (16 caractères)</label>
                                        <input type="password" name="password_email16caractere" class="form-control" 
                                               value="<?= $settings['password_email16caractere']['value'] ?? '' ?>">
                                        <small class="text-muted">Mot de passe pour l'envoi d'emails</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paramètres personnalisés -->
                <?php 
                $custom_settings = [];
                foreach($settings as $key => $setting):
                    if(!in_array($key, ['site_name', 'site_email', 'site_phone', 'site_address', 'site_country', 
                                        'site_youtube', 'site_instagram', 'site_facebook', 'site_linkedln', 
                                        'longitude', 'latitude', 'site_logo', 'site_favicon', 'password_email16caractere'])):
                        $custom_settings[] = ['key' => $key, 'setting' => $setting];
                    endif;
                endforeach;
                ?>
                
                <?php if(!empty($custom_settings)): ?>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Paramètres personnalisés</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="customSettingsTable">
                                    <thead>
                                        <tr>
                                            <th>Clé</th>
                                            <th>Valeur</th>
                                            <th>Catégorie</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($custom_settings as $item): ?>
                                        <tr>
                                            <td><code><?= $item['key'] ?></code></td>
                                            <td>
                                                <?php if($item['setting']['is_file']): ?>
                                                    <a href="<?= base_url('attachments/Settings/' . $item['setting']['value']) ?>" target="_blank">
                                                        Voir le fichier
                                                    </a>
                                                <?php else: ?>
                                                    <span class="setting-value" data-id="<?= $item['setting']['id'] ?>"><?= $item['setting']['value'] ?></span>
                                                <?php endif; ?>
                                             </div>
                                            <td><?= $item['setting']['title'] ?? 'Général' ?></div>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <?php if(!$item['setting']['is_file']): ?>
                                                        <button type="button" class="btn btn-sm btn-primary edit-setting" 
                                                                data-id="<?= $item['setting']['id'] ?>" 
                                                                data-value="<?= htmlspecialchars($item['setting']['value']) ?>"
                                                                data-key="<?= $item['key'] ?>">
                                                            <i class="bx bx-edit"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-danger delete-setting" 
                                                            data-id="<?= $item['setting']['id'] ?>" 
                                                            data-key="<?= $item['key'] ?>">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </div>
                                             </div>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Boutons d'action -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>Enregistrer les modifications
                                </button>
                                <a href="<?= base_url('Dashboard') ?>" class="btn btn-secondary">
                                    <i class="bx bx-x me-1"></i>Annuler
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    
</div>

<!-- Modal Ajouter un paramètre -->
<div class="modal fade" id="addParamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-plus-circle me-2"></i>Ajouter un paramètre
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addParamForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Clé du paramètre <span class="text-danger">*</span></label>
                        <input type="text" name="key" class="form-control" id="param_key" 
                               placeholder="ex: api_key, site_twitter, etc." required>
                        <small class="text-muted">Identifiant unique (sans espaces)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie</label>
                        <select name="category" class="form-select" id="param_category">
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat ?>"><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type de valeur</label>
                        <select name="is_file" class="form-select" id="param_type">
                            <option value="0">Texte</option>
                            <option value="1">Fichier (image/document)</option>
                        </select>
                    </div>
                    <div class="mb-3" id="text_value_div">
                        <label class="form-label">Valeur</label>
                        <textarea name="value" class="form-control" id="param_value" rows="3" placeholder="Valeur du paramètre"></textarea>
                    </div>
                    <div class="mb-3" id="file_value_div" style="display: none;">
                        <label class="form-label">Fichier</label>
                        <input type="file" name="file" class="form-control" id="param_file" accept="image/*,application/pdf">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveParamBtn">
                    <i class="bx bx-save me-1"></i>Ajouter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier un paramètre -->
<div class="modal fade" id="editParamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-edit me-2"></i>Modifier le paramètre
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Clé</label>
                    <input type="text" class="form-control" id="edit_key" readonly disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Valeur</label>
                    <textarea class="form-control" id="edit_value" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateParamBtn">
                    <i class="bx bx-save me-1"></i>Mettre à jour
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Gestion du type de valeur (texte/fichier)
document.getElementById('param_type').addEventListener('change', function() {
    if (this.value == '1') {
        document.getElementById('text_value_div').style.display = 'none';
        document.getElementById('file_value_div').style.display = 'block';
    } else {
        document.getElementById('text_value_div').style.display = 'block';
        document.getElementById('file_value_div').style.display = 'none';
    }
});

// Ajouter un paramètre
document.getElementById('saveParamBtn').addEventListener('click', function() {
    var formData = new FormData(document.getElementById('addParamForm'));
    
    fetch('<?= base_url("Settings/ajouter_parametre") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Succès!', data.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Erreur!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Erreur!', 'Une erreur est survenue', 'error');
    });
});

// Modifier un paramètre
document.querySelectorAll('.edit-setting').forEach(btn => {
    btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var value = this.dataset.value;
        var key = this.dataset.key;
        
        document.getElementById('edit_key').value = key;
        document.getElementById('edit_value').value = value;
        
        var modal = new bootstrap.Modal(document.getElementById('editParamModal'));
        modal.show();
        
        document.getElementById('updateParamBtn').onclick = function() {
            var newValue = document.getElementById('edit_value').value;
            
            fetch('<?= base_url("Settings/modifier_parametre") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id + '&value=' + encodeURIComponent(newValue)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Succès!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur!', data.message, 'error');
                }
            });
        };
    });
});

// Supprimer un paramètre
document.querySelectorAll('.delete-setting').forEach(btn => {
    btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var key = this.dataset.key;
        
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous allez supprimer le paramètre '" + key + "'",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('<?= base_url("Settings/supprimer_parametre") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Succès!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur!', data.message, 'error');
                    }
                });
            }
        });
    });
});
</script>

<style>
.avatar-lg {
    width: 120px;
    height: 120px;
    object-fit: contain;
}
.avatar-sm {
    width: 32px;
    height: 32px;
    object-fit: contain;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>