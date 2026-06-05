<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Détails de la zone</h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('collines/' . $zone->id_zone) ?>" class="btn btn-sm btn-info">
                                <i class="bx bx-list-ul me-1"></i>Voir les collines
                            </a>
                            <a href="<?= base_url('zone/edit/' . $zone->id_zone) ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-edit me-1"></i>Modifier
                            </a>
                            <a href="<?= base_url('zones') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3"><iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon> Informations générales</h5>
                                    <table class="table table-borderless">
                                        <tr><td width="150"><strong>ID :</strong></td><td>#<?= $zone->id_zone ?></td></tr>
                                        <tr><td><strong>Nom :</strong></td><td><?= htmlspecialchars($zone->zone_name) ?></td></tr>
                                        <tr><td><strong>Quartier :</strong></td><td><?= htmlspecialchars($zone->quartier_name) ?></td></tr>
                                        <tr><td><strong>Commune :</strong></td><td><?= htmlspecialchars($zone->commune_name) ?></td></tr>
                                        <tr><td><strong>Province :</strong></td><td><?= htmlspecialchars($zone->province_name) ?></td></tr>
                                        <tr><td><strong>Statut :</strong></td><td><?= $zone->est_actif ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-danger">Inactif</span>' ?></td></tr>
                                    </table>
                                </div>
                                <div class="info-card p-3 bg-light rounded">
                                    <h5 class="mb-3"><iconify-icon icon="solar:map-point-bold-duotone" class="me-2"></iconify-icon> Coordonnées GPS</h5>
                                    <table class="table table-borderless">
                                        <tr><td width="150"><strong>Latitude :</strong></td><td><?= $zone->latitude ?? 'Non définie' ?></td></tr>
                                        <tr><td><strong>Longitude :</strong></td><td><?= $zone->longitude ?? 'Non définie' ?></td></tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header"><h5 class="mb-0">Position sur la carte</h5></div>
                                    <div class="card-body p-0"><div id="map" style="height: 400px;"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const lat = <?= $zone->latitude ?: '-3.382' ?>;
const lng = <?= $zone->longitude ?: '29.361' ?>;
const map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map).bindPopup('Zone: <?= htmlspecialchars($zone->zone_name) ?>').openPopup();
</script>

<style>
.info-card { transition: all 0.3s ease; }
.info-card:hover { background-color: #e9ecef !important; }
.table-borderless td, .table-borderless th { padding: 8px 0; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>