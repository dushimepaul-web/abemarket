<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1"><?= isset($colline) ? 'Modifier la colline' : 'Ajouter une colline' ?></h4>
                        <a href="<?= base_url('collines') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="collineForm" method="post" action="<?= isset($colline) ? base_url('Location/modifier_colline/' . $colline->id_colline) : base_url('Location/ajouter_colline') ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Zone <span class="text-danger">*</span></label>
                                    <select name="id_zone" id="id_zone" class="form-select" required>
                                        <option value="">Sélectionner une zone</option>
                                        <?php foreach ($zones as $z): ?>
                                            <option value="<?= $z->id_zone ?>" 
                                                <?= isset($colline) && $colline->id_zone == $z->id_zone ? 'selected' : '' ?>
                                                data-quartier="<?= htmlspecialchars($z->quartier_name) ?>"
                                                data-commune="<?= htmlspecialchars($z->commune_name) ?>"
                                                data-province="<?= htmlspecialchars($z->province_name) ?>">
                                                <?= htmlspecialchars($z->zone_name) ?> (<?= htmlspecialchars($z->quartier_name) ?>, <?= htmlspecialchars($z->commune_name) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de la colline <span class="text-danger">*</span></label>
                                    <input type="text" name="colline_name" id="colline_name" class="form-control" required value="<?= isset($colline) ? htmlspecialchars($colline->colline_name) : '' ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" value="<?= isset($colline) ? $colline->latitude : '' ?>" placeholder="Ex: -3.38200000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" value="<?= isset($colline) ? $colline->longitude : '' ?>" placeholder="Ex: 29.36110000">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div id="map" style="height: 350px; border-radius: 8px;"></div>
                                <small class="text-muted">Cliquez sur la carte pour définir la position de la colline</small>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input type="checkbox" name="est_actif" id="est_actif" class="form-check-input" value="1" <?= isset($colline) && $colline->est_actif ? 'checked' : (isset($colline) ? '' : 'checked') ?>>
                                <label class="form-check-label">Actif</label>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($colline) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('collines') ?>" class="btn btn-secondary">Annuler</a>
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
const defaultLat = <?= isset($colline) && $colline->latitude ? $colline->latitude : '-3.382' ?>;
const defaultLng = <?= isset($colline) && $colline->longitude ? $colline->longitude : '29.361' ?>;

function initMap(lat = defaultLat, lng = defaultLng) {
    if (map) { map.remove(); }
    map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    const customIcon = L.icon({
        iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
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

$('#collineForm').on('submit', function(e) {
    e.preventDefault();
    if (!$('#id_zone').val()) { Swal.fire('Erreur', 'La zone est requise', 'error'); return; }
    if (!$('#colline_name').val()) { Swal.fire('Erreur', 'Le nom de la colline est requis', 'error'); return; }
    
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');
    
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => {
                window.location.href = '<?= base_url("collines") ?>';
            });
        } else {
            Swal.fire('Erreur', response.message, 'error');
            submitBtn.prop('disabled', false).html('<?= isset($colline) ? "Mettre à jour" : "Enregistrer" ?>');
        }
    }, 'json');
});

$(document).ready(function() { if (document.getElementById('map')) initMap(); });
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>