<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1"><?= isset($province) ? 'Modifier la province' : 'Ajouter une province' ?></h4>
                        <a href="<?= base_url('Adresse/Location/provinces') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="provinceForm" method="post" action="<?= isset($province) ? base_url('Adresse/Location/modifier/province/' . $province->id_province) : base_url('Adresse/Location/ajouter/province') ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de la province <span class="text-danger">*</span></label>
                                    <input type="text" name="province_name" id="province_name" class="form-control" required value="<?= isset($province) ? htmlspecialchars($province->province_name) : '' ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" placeholder="-3.382" value="<?= isset($province) ? $province->latitude : '' ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" placeholder="29.361" value="<?= isset($province) ? $province->longitude : '' ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div id="map" style="height: 400px; border-radius: 8px;"></div>
                                <small class="text-muted">Cliquez sur la carte pour définir la position de la province</small>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" <?= isset($province) && $province->est_actif ? 'checked' : (isset($province) ? '' : 'checked') ?>>
                                <label class="form-check-label">Actif</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($province) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('Adresse/Location/provinces') ?>" class="btn btn-secondary">Annuler</a>
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
const defaultLat = <?= isset($province) && $province->latitude ? $province->latitude : '-3.382' ?>;
const defaultLng = <?= isset($province) && $province->longitude ? $province->longitude : '29.361' ?>;

function initMap(lat = defaultLat, lng = defaultLng) {
    if (map) { map.remove(); }
    map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    const customIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
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

$('#provinceForm').on('submit', function(e) {
    e.preventDefault();
    if (!$('#province_name').val()) {
        Swal.fire('Erreur', 'Le nom de la province est requis', 'error');
        return;
    }
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');
    
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => {
                window.location.href = '<?= base_url("Location/provinces") ?>';
            });
        } else {
            Swal.fire('Erreur', response.message, 'error');
            submitBtn.prop('disabled', false).html('<?= isset($province) ? "Mettre à jour" : "Enregistrer" ?>');
        }
    }, 'json').fail(() => {
        Swal.fire('Erreur', 'Erreur de connexion', 'error');
        submitBtn.prop('disabled', false).html('<?= isset($province) ? "Mettre à jour" : "Enregistrer" ?>');
    });
});

$(document).ready(function() { if (document.getElementById('map')) initMap(); });
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>