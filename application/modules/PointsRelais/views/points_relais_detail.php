<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:map-point-bold-duotone" class="me-2"></iconify-icon>
                            Détails du point relais
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('points-relais/edit/' . $point->id_point) ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:pen-2-broken"></iconify-icon> Modifier
                            </a>
                            <a href="<?= base_url('points-relais') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">📋 Informations générales</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Nom :</strong></td><td><strong><?= htmlspecialchars($point->nom) ?></strong></div></tr>
                                            <tr><td><strong>Type :</strong></div>
                                                <tr>
                                                    <?php
                                                    switch($point->type) {
                                                        case 'boutique_partenaire': echo '🏪 Boutique partenaire'; break;
                                                        case 'kiosque': echo '📰 Kiosque'; break;
                                                        case 'bureau_poste': echo '🏤 Bureau de poste'; break;
                                                        default: echo $point->type;
                                                    }
                                                    ?>
                                                </div>
                                            </tr>
                                            <tr><td><strong>Adresse :</strong></div><td><?= nl2br(htmlspecialchars($point->adresse)) ?></div></tr>
                                            <?php if ($commune): ?>
                                            <tr><td><strong>Commune :</strong></div><td><?= htmlspecialchars($commune->commune_name) ?></div></tr>
                                            <?php endif; ?>
                                            <?php if ($quartier): ?>
                                            <tr><td><strong>Quartier :</strong></div><td><?= htmlspecialchars($quartier->quartier_name) ?></div></tr>
                                            <?php endif; ?>
                                            <?php if ($point->telephone): ?>
                                            <tr><td><strong>Téléphone :</strong></div><td><a href="tel:<?= $point->telephone ?>"><?= htmlspecialchars($point->telephone) ?></a></div></tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">📊 Informations logistiques</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Capacité max :</strong></div><td><?= number_format($point->capacite_max) ?> colis</div></tr>
                                            <tr><td><strong>Statut :</strong></div>
                                                <td>
                                                    <?php if ($point->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?else: ?>
                                                        <span class="badge bg-secondary">Inactif</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                            <tr><td><strong>Date création :</strong></div><td><?= date('d/m/Y H:i', strtotime($point->date_creation)) ?></div></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">📍 Position GPS</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="map" style="height: 250px; border-radius: 10px;"></div>
                                        <div class="mt-2">
                                            <small>Latitude: <?= $point->latitude ?></small><br>
                                            <small>Longitude: <?= $point->longitude ?></small>
                                        </div>
                                        <a href="https://www.google.com/maps?q=<?= $point->latitude ?>,<?= $point->longitude ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                            <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon> Voir sur Google Maps
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">⏰ Horaires d'ouverture</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($point->horaires_array)): ?>
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr><th>Jour</th><th>Ouverture</th><th>Fermeture</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($point->horaires_array as $jour => $horaire): ?>
                                                        <tr>
                                                            <td><strong><?= $jour ?></strong></div>
                                                            <td><?= $horaire['ouverture'] ?? '-' ?></div>
                                                            <td><?= $horaire['fermeture'] ?? '-' ?></div>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <p class="text-muted text-center py-3">Aucun horaire renseigné</p>
                                        <?php endif; ?>
                                    </div>
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
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<script>
var map = L.map('map').setView([<?= $point->latitude ?>, <?= $point->longitude ?>], 15);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
L.marker([<?= $point->latitude ?>, <?= $point->longitude ?>])
    .addTo(map)
    .bindPopup('<strong><?= htmlspecialchars($point->nom) ?></strong><br><?= htmlspecialchars($point->adresse) ?>')
    .openPopup();
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>