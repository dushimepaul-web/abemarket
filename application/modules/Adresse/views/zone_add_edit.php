<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1"><?= isset($zone) ? 'Modifier la zone' : 'Ajouter une zone' ?></h4>
                        <a href="<?= base_url('zones') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="zoneForm" method="post" action="<?= isset($zone) ? base_url('Location/modifier_zone/' . $zone->id_zone) : base_url('Location/ajouter_zone') ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quartier <span class="text-danger">*</span></label>
                                    <select name="id_quartier" id="id_quartier" class="form-select" required>
                                        <option value="">Sélectionner un quartier</option>
                                        <?php foreach ($quartiers as $q): ?>
                                            <option value="<?= $q->id_quartier ?>" 
                                                <?= isset($zone) && $zone->id_quartier == $q->id_quartier ? 'selected' : '' ?>
                                                data-commune="<?= htmlspecialchars($q->commune_name) ?>"
                                                data-province="<?= htmlspecialchars($q->province_name) ?>">
                                                <?= htmlspecialchars($q->quartier_name) ?> (<?= htmlspecialchars($q->commune_name) ?>, <?= htmlspecialchars($q->province_name) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de la zone <span class="text-danger">*</span></label>
                                    <input type="text" name="zone_name" id="zone_name" class="form-control" required value="<?= isset($zone) ? htmlspecialchars($zone->zone_name) : '' ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" value="<?= isset($zone) ? $zone->latitude : '' ?>" placeholder="Ex: -3.38200000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" value="<?= isset($zone) ? $zone->longitude : '' ?>" placeholder="Ex: 29.36110000">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div id="map" style="height: 350px; border-radius: 8px;"></div>
                                <small class="text-muted">Cliquez sur la carte pour définir la position de la zone</small>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" <?= isset($zone) && $zone->est_actif ? 'checked' : (isset($zone) ? '' : 'checked') ?>>
                                <label class="form-check-label">Actif</label>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($zone) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('zones') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let map, marker;
const defaultLat = <?= isset($zone) && $zone->latitude ? $zone->latitude : '-3.382' ?>;
const defaultLng = <?= isset($zone) && $zone->longitude ? $zone->longitude : '29.361' ?>;

function initMap(lat = defaultLat, lng = defaultLng) {
    if (map) { map.remove(); }
    map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    const customIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41]
    });
    
    marker = L.marker([parseFloat(lat), parseFloat(lng)], { draggable: true, icon: customIcon }).addTo(map);
    marker.on('dragend', function(e) {
        const pos = marker.getLatLng();
        $('#latitude').val(pos.lat.toFixed(8));
        $('#longitude').val(pos.lng.toFixed(8));
    });
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        $('#latitude').val(e.latlng.lat.toFixed(8));
        $('#longitude').val(e.latlng.lng.toFixed(8));
    });
}

$('#zoneForm').on('submit', function(e) {
    e.preventDefault();
    if (!$('#id_quartier').val()) { Swal.fire('Erreur', 'Le quartier est requis', 'error'); return; }
    if (!$('#zone_name').val()) { Swal.fire('Erreur', 'Le nom de la zone est requis', 'error'); return; }
    
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');
    
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => {
                window.location.href = '<?= base_url("zones") ?>';
            });
        } else {
            Swal.fire('Erreur', response.message, 'error');
            submitBtn.prop('disabled', false).html('<?= isset($zone) ? "Mettre à jour" : "Enregistrer" ?>');
        }
    }, 'json');
});

$(document).ready(function() { if (document.getElementById('map')) initMap(); });
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>