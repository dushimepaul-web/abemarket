<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Détails de la commune</h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('Adresse/Location/quartiers/' . $commune->id_commune) ?>" class="btn btn-sm btn-info">
                                <i class="bx bx-list-ul me-1"></i>Voir les quartiers
                            </a>
                            <a href="<?= base_url('Adresse/Location/commune_add_edit/' . $commune->id_commune) ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-edit me-1"></i>Modifier
                            </a>
                            <a href="<?= base_url('Adresse/Location/communes') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3">Informations générales</h5>
                                    <table class="table table-borderless">
                                        <tr><td width="150"><strong>ID :</strong></td><td>#<?= $commune->id_commune ?></td></tr>
                                        <tr><td><strong>Nom :</strong></td><td><?= htmlspecialchars($commune->commune_name) ?></td></tr>
                                        <tr><td><strong>Province :</strong></td><td><?= htmlspecialchars($commune->province_name) ?></td></tr>
                                        <tr><td><strong>Statut :</strong></td><td><?= $commune->est_actif ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-danger">Inactif</span>' ?></td></tr>
                                    </table>
                                </div>
                                <div class="info-card p-3 bg-light rounded">
                                    <h5 class="mb-3">Coordonnées GPS</h5>
                                    <table class="table table-borderless">
                                        <tr><td width="150"><strong>Latitude :</strong></td><td><?= $commune->latitude ?? 'Non définie' ?></td></tr>
                                        <tr><td><strong>Longitude :</strong></td><td><?= $commune->longitude ?? 'Non définie' ?></td></tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card"><div class="card-header"><h5 class="mb-0">Position sur la carte</h5></div>
                                <div class="card-body p-0"><div id="map" style="height: 400px;"></div></div></div>
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
const lat = <?= $commune->latitude ?: ($commune->province_latitude ?? '-3.382') ?>;
const lng = <?= $commune->longitude ?: ($commune->province_longitude ?? '29.361') ?>;
const map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map).bindPopup('<?= htmlspecialchars($commune->commune_name) ?>').openPopup();
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>