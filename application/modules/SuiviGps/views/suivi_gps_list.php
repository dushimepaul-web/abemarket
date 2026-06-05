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
                            <iconify-icon icon="solar:map-bold-duotone" class="me-2"></iconify-icon>
                            Carte de suivi en direct
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('suivi-gps') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="map" style="height: 600px; border-radius: 10px;"></div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Légende :</strong>
                                <span class="badge bg-danger me-2">🔴 En livraison</span>
                                <span class="badge bg-success me-2">🟢 Disponible</span>
                                <span class="badge bg-secondary">⚪ Inactif</span>
                            </div>
                            <div class="col-md-6 text-end">
                                <button class="btn btn-sm btn-primary" id="rafraichir">
                                    <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                                    Rafraîchir
                                </button>
                                <span id="lastUpdate" class="text-muted ms-2"></span>
                            </div>
                        </div>
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
var map = L.map('map').setView([-3.382, 29.361], 12);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var markers = {};

// Icônes personnalisées
var iconLivraison = L.divIcon({
    className: 'custom-div-icon',
    html: '<div style="background-color: #dc3545; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>',
    iconSize: [12, 12],
    popupAnchor: [0, -6]
});

var iconDisponible = L.divIcon({
    className: 'custom-div-icon',
    html: '<div style="background-color: #198754; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>',
    iconSize: [12, 12],
    popupAnchor: [0, -6]
});

var iconInactif = L.divIcon({
    className: 'custom-div-icon',
    html: '<div style="background-color: #6c757d; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>',
    iconSize: [12, 12],
    popupAnchor: [0, -6]
});

// Charger les positions
function chargerPositions() {
    $.ajax({
        url: '<?= base_url("suivi-gps/positions_actives") ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Supprimer les anciens marqueurs
                for (var id in markers) {
                    map.removeLayer(markers[id]);
                }
                markers = {};
                
                response.data.forEach(function(t) {
                    var icon = t.est_disponible ? iconDisponible : (t.statut == 'actif' ? iconLivraison : iconInactif);
                    var popupContent = `
                        <div class="text-center">
                            <strong>${t.nom}</strong><br>
                            <span class="badge bg-secondary">${t.type_vehicule}</span><br>
                            <small>Dernière position: ${new Date(t.timestamp_gps).toLocaleTimeString()}</small><br>
                            <a href="https://www.google.com/maps?q=${t.latitude},${t.longitude}" target="_blank" class="btn btn-sm btn-primary mt-2">
                                Itinéraire
                            </a>
                        </div>
                    `;
                    
                    var marker = L.marker([t.latitude, t.longitude], {icon: icon})
                        .addTo(map)
                        .bindPopup(popupContent);
                    
                    markers[t.id_transporteur] = marker;
                });
                
                $('#lastUpdate').text('Dernière mise à jour: ' + new Date().toLocaleTimeString());
            }
        }
    });
}

// Rafraîchir toutes les 10 secondes
chargerPositions();
setInterval(chargerPositions, 10000);

$('#rafraichir').on('click', function() {
    chargerPositions();
});
</script>

<style>
.custom-div-icon {
    background: transparent;
    border: none;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>