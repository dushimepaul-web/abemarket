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
                            <iconify-icon icon="solar:map-point-bold-duotone" class="me-2"></iconify-icon>
                            <?= isset($point) ? 'Modifier le point relais' : 'Ajouter un point relais' ?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" class="form-control" required 
                                           value="<?= isset($point) ? htmlspecialchars($point->nom) : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="boutique_partenaire" <?= isset($point) && $point->type == 'boutique_partenaire' ? 'selected' : '' ?>>🏪 Boutique partenaire</option>
                                        <option value="kiosque" <?= isset($point) && $point->type == 'kiosque' ? 'selected' : '' ?>>📰 Kiosque</option>
                                        <option value="bureau_poste" <?= isset($point) && $point->type == 'bureau_poste' ? 'selected' : '' ?>>🏤 Bureau de poste</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                <textarea name="adresse" class="form-control" rows="2" required><?= isset($point) ? htmlspecialchars($point->adresse) : '' ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Commune</label>
                                    <select name="id_commune" id="id_commune" class="form-select">
                                        <option value="">-- Sélectionner --</option>
                                        <?php foreach ($communes as $c): ?>
                                            <option value="<?= $c->id_commune ?>" <?= isset($point) && $point->id_commune == $c->id_commune ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c->commune_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quartier</label>
                                    <select name="id_quartier" id="id_quartier" class="form-select">
                                        <option value="">-- Sélectionner --</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="telephone" class="form-control" 
                                           value="<?= isset($point) ? htmlspecialchars($point->telephone) : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Capacité maximale</label>
                                    <input type="number" name="capacite_max" class="form-control" min="1" 
                                           value="<?= isset($point) ? $point->capacite_max : '50' ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude <span class="text-danger">*</span></label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" required 
                                           value="<?= isset($point) ? $point->latitude : '' ?>">
                                    <small class="text-muted">Ex: -3.3820000</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude <span class="text-danger">*</span></label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" required 
                                           value="<?= isset($point) ? $point->longitude : '' ?>">
                                    <small class="text-muted">Ex: 29.3611000</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div id="map" style="height: 250px; border-radius: 10px;"></div>
                                    <button type="button" class="btn btn-sm btn-secondary mt-2" id="getPositionBtn">
                                        <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon> Utiliser ma position
                                    </button>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Horaires d'ouverture</label>
                                    <div id="horaires-container">
                                        <?php 
                                        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                                        $horaires = isset($point->horaires_array) ? $point->horaires_array : [];
                                        foreach ($jours as $jour):
                                        ?>
                                        <div class="row mb-2">
                                            <div class="col-4">
                                                <strong><?= $jour ?></strong>
                                            </div>
                                            <div class="col-4">
                                                <input type="text" name="horaires[<?= $jour ?>][ouverture]" class="form-control form-control-sm" placeholder="09:00" 
                                                       value="<?= isset($horaires[$jour]['ouverture']) ? $horaires[$jour]['ouverture'] : '' ?>">
                                            </div>
                                            <div class="col-4">
                                                <input type="text" name="horaires[<?= $jour ?>][fermeture]" class="form-control form-control-sm" placeholder="18:00" 
                                                       value="<?= isset($horaires[$jour]['fermeture']) ? $horaires[$jour]['fermeture'] : '' ?>">
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="est_actif" class="form-check-input" value="1" 
                                           <?= isset($point) && $point->est_actif ? 'checked' : (isset($point) ? '' : 'checked') ?>>
                                    <label class="form-check-label">Point relais actif</label>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                                <strong>Informations :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>📍 Les coordonnées GPS sont utilisées pour la recherche de points relais proches</li>
                                    <li>📦 La capacité maximale indique le nombre de colis que peut recevoir ce point</li>
                                    <li>⏰ Les horaires sont optionnels mais recommandés pour les clients</li>
                                </ul>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i><?= isset($point) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('points-relais') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Initialiser la carte
var map = L.map('map').setView([-3.382, 29.361], 13);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var marker = null;

// Ajouter un marqueur à la position
function addMarker(lat, lng) {
    if (marker) {
        map.removeLayer(marker);
    }
    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);
}

// Récupérer la position actuelle
$('#getPositionBtn').on('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            addMarker(lat, lng);
        }, function() {
            alert('Impossible de récupérer la position');
        });
    } else {
        alert('Géolocalisation non supportée');
    }
});

// Si déjà des coordonnées
var lat = $('#latitude').val();
var lng = $('#longitude').val();
if (lat && lng) {
    addMarker(lat, lng);
}

// Charger les quartiers par commune
$('#id_commune').on('change', function() {
    var id_commune = $(this).val();
    if (id_commune) {
        $.ajax({
            url: '<?= base_url("points-relais/get_quartiers") ?>',
            type: 'POST',
            data: {id_commune: id_commune},
            dataType: 'json',
            success: function(data) {
                var options = '<option value="">-- Sélectionner --</option>';
                data.forEach(function(q) {
                    options += `<option value="${q.id_quartier}">${q.quartier_name}</option>`;
                });
                $('#id_quartier').html(options);
            }
        });
    } else {
        $('#id_quartier').html('<option value="">-- Sélectionner --</option>');
    }
});

// Déclencher le chargement initial
$('#id_commune').trigger('change');

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

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>