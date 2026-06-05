<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:truck-bold-duotone" class="me-2"></iconify-icon>
                            <?= isset($transporteur) ? 'Modifier le transporteur' : 'Ajouter un transporteur' ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" class="form-control" required 
                                           value="<?= isset($transporteur) ? htmlspecialchars($transporteur->nom) : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select">
                                        <option value="interne" <?= isset($transporteur) && $transporteur->type == 'interne' ? 'selected' : '' ?>>Interne</option>
                                        <option value="partenaire" <?= isset($transporteur) && $transporteur->type == 'partenaire' ? 'selected' : '' ?>>Partenaire</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" name="telephone" class="form-control" required 
                                           value="<?= isset($transporteur) ? htmlspecialchars($transporteur->telephone) : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">WhatsApp</label>
                                    <input type="text" name="whatsapp" class="form-control" 
                                           value="<?= isset($transporteur) ? htmlspecialchars($transporteur->whatsapp) : '' ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type de véhicule <span class="text-danger">*</span></label>
                                    <select name="type_vehicule" class="form-select" required>
                                        <option value="moto" <?= isset($transporteur) && $transporteur->type_vehicule == 'moto' ? 'selected' : '' ?>>🛵 Moto</option>
                                        <option value="voiture" <?= isset($transporteur) && $transporteur->type_vehicule == 'voiture' ? 'selected' : '' ?>>🚗 Voiture</option>
                                        <option value="camionnette" <?= isset($transporteur) && $transporteur->type_vehicule == 'camionnette' ? 'selected' : '' ?>>🚚 Camionnette</option>
                                        <option value="velo" <?= isset($transporteur) && $transporteur->type_vehicule == 'velo' ? 'selected' : '' ?>>🚲 Vélo</option>
                                        <option value="pied" <?= isset($transporteur) && $transporteur->type_vehicule == 'pied' ? 'selected' : '' ?>>🚶 À pied</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Plaque d'immatriculation</label>
                                    <input type="text" name="plaque" class="form-control" 
                                           value="<?= isset($transporteur) ? htmlspecialchars($transporteur->plaque) : '' ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Photo</label>
                                    <input type="file" name="photo_url" class="form-control" accept="image/*">
                                    <small class="text-muted">JPG, PNG, GIF, WEBP. Max 2MB</small>
                                    <?php if (isset($transporteur) && $transporteur->photo_url && file_exists(FCPATH . $transporteur->photo_url)): ?>
                                        <div class="mt-2">
                                            <img src="<?= base_url($transporteur->photo_url) ?>" alt="Photo" style="height: 50px;">
                                            <small class="text-muted d-block">Photo actuelle</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Statut</label>
                                    <select name="statut" class="form-select">
                                        <option value="actif" <?= isset($transporteur) && $transporteur->statut == 'actif' ? 'selected' : '' ?>>Actif</option>
                                        <option value="inactif" <?= isset($transporteur) && $transporteur->statut == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                                        <option value="suspendu" <?= isset($transporteur) && $transporteur->statut == 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="est_disponible" class="form-check-input" value="1" 
                                               <?= isset($transporteur) && $transporteur->est_disponible ? 'checked' : (isset($transporteur) ? '' : 'checked') ?>>
                                        <label class="form-check-label">Transporteur disponible</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Utilisateur associé</label>
                                    <select name="id_utilisateur" class="form-select">
                                        <option value="">-- Non associé --</option>
                                        <?php foreach ($utilisateurs as $u): ?>
                                            <option value="<?= $u->id_utilisateur ?>" <?= isset($transporteur) && $transporteur->id_utilisateur == $u->id_utilisateur ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($u->prenom . ' ' . $u->nom) ?> (<?= htmlspecialchars($u->email) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                                <strong>Informations :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>📍 La position GPS peut être mise à jour depuis l'application mobile</li>
                                    <li>⭐ La note moyenne se calcule automatiquement</li>
                                    <li>📊 Les statistiques de livraison sont automatiques</li>
                                </ul>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i><?= isset($transporteur) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('transporteurs') ?>" class="btn btn-secondary">Annuler</a>
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