<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:map-point-add-bold-duotone" class="me-2"></iconify-icon>
                            <?= isset($suivi) ? 'Modifier la position GPS' : 'Ajouter une position GPS' ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('suivi-gps') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                    
                    <form action="<?= base_url('suivi-gps/save') ?>" method="post" id="formSuiviGps">
                        <div class="card-body">
                            <input type="hidden" name="id_suivi" value="<?= isset($suivi) ? $suivi->id_suivi : '' ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Commande <span class="text-danger">*</span></label>
                                        <select name="id_commande" class="form-select select2" required>
                                            <option value="">Sélectionner une commande</option>
                                            <?php foreach ($commandes as $commande): ?>
                                                <option value="<?= $commande->id_commande ?>" 
                                                    <?= (isset($suivi) && $suivi->id_commande == $commande->id_commande) ? 'selected' : '' ?>>
                                                    <?= $commande->numero_commande ?> - <?= $commande->nom_client ?? 'Client #'.$commande->id_utilisateur ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Transporteur <span class="text-danger">*</span></label>
                                        <select name="id_transporteur" class="form-select select2" required>
                                            <option value="">Sélectionner un transporteur</option>
                                            <?php foreach ($transporteurs as $transporteur): ?>
                                                <option value="<?= $transporteur->id_transporteur ?>" 
                                                    <?= (isset($suivi) && $suivi->id_transporteur == $transporteur->id_transporteur) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($transporteur->nom) ?> (<?= $transporteur->type_vehicule ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Latitude <span class="text-danger">*</span></label>
                                        <input type="number" step="any" name="latitude" class="form-control" 
                                               value="<?= isset($suivi) ? $suivi->latitude : '' ?>" 
                                               placeholder="-3.38200000" required>
                                        <small class="text-muted">Exemple: -3.38200000</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Longitude <span class="text-danger">*</span></label>
                                        <input type="number" step="any" name="longitude" class="form-control" 
                                               value="<?= isset($suivi) ? $suivi->longitude : '' ?>" 
                                               placeholder="29.36110000" required>
                                        <small class="text-muted">Exemple: 29.36110000</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Vitesse (km/h)</label>
                                        <input type="number" step="0.01" name="vitesse_kmh" class="form-control" 
                                               value="<?= isset($suivi) ? $suivi->vitesse_kmh : '' ?>" 
                                               placeholder="0.00">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Date/Heure GPS</label>
                                        <input type="datetime-local" name="timestamp_gps" class="form-control" 
                                               value="<?= isset($suivi) ? date('Y-m-d\TH:i', strtotime($suivi->timestamp_gps)) : date('Y-m-d\TH:i') ?>">
                                        <small class="text-muted">Laissez vide pour utiliser l'heure actuelle</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mini carte pour sélectionner la position -->
                            <div class="mb-3">
                                <label class="form-label">Sélectionner sur la carte</label>
                                <div id="positionMap" style="height: 400px; border-radius: 10px;"></div>
                                <small class="text-muted">Cliquez sur la carte pour définir les coordonnées</small>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= base_url('suivi-gps') ?>" class="btn btn-secondary">Annuler</a>
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:save-bold-duotone"></iconify-icon>
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Initialiser la carte pour la sélection
var positionMap = L.map('positionMap').setView([-3.382, 29.361], 12);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(positionMap);

var marker;
var latInput = document.querySelector('input[name="latitude"]');
var lngInput = document.querySelector('input[name="longitude"]');

// Ajouter un marqueur à la position actuelle
function updateMarker(lat, lng) {
    if (marker) {
        positionMap.removeLayer(marker);
    }
    marker = L.marker([lat, lng]).addTo(positionMap);
    positionMap.setView([lat, lng], 15);
}

// Si des coordonnées existent déjà
if (latInput.value && lngInput.value) {
    updateMarker(parseFloat(latInput.value), parseFloat(lngInput.value));
}

// Clic sur la carte
positionMap.on('click', function(e) {
    var lat = e.latlng.lat.toFixed(8);
    var lng = e.latlng.lng.toFixed(8);
    latInput.value = lat;
    lngInput.value = lng;
    updateMarker(parseFloat(lat), parseFloat(lng));
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>