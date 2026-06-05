<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<!-- ==================================================== -->
<!-- Start right Content here -->
<!-- ==================================================== -->
<div class="page-content">

    <!-- Start Container Fluid -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Détails de l'adresse</h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('Adresse/adresse_add_edit/' . $adresse->id_adresse) ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-edit me-1"></i>Modifier
                            </a>
                            <a href="<?= base_url('Adresse') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <?php if ($adresse->type_adresse == 'domicile'): ?>
                                            <iconify-icon icon="solar:home-2-bold-duotone" class="fs-48 text-primary"></iconify-icon>
                                        <?php elseif ($adresse->type_adresse == 'travail'): ?>
                                            <iconify-icon icon="solar:buildings-bold-duotone" class="fs-48 text-info"></iconify-icon>
                                        <?php else: ?>
                                            <iconify-icon icon="solar:map-point-bold-duotone" class="fs-48 text-warning"></iconify-icon>
                                        <?php endif; ?>
                                        <div>
                                            <h3 class="mb-0"><?= htmlspecialchars($adresse->nom_complet) ?></h3>
                                            <span class="badge bg-<?= $adresse->est_par_defaut ? 'success' : 'secondary' ?> mt-1">
                                                <?= $adresse->est_par_defaut ? '<i class="bx bx-check-circle me-1"></i>Adresse par défaut' : 'Adresse secondaire' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3"><iconify-icon icon="solar:user-id-bold-duotone" class="me-2"></iconify-icon> Informations du destinataire</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="120"><strong>Nom complet :</strong></td>
                                            <td><?= htmlspecialchars($adresse->nom_complet) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Téléphone :</strong></td>
                                            <td>
                                                <a href="tel:<?= htmlspecialchars($adresse->telephone) ?>">
                                                    <?= htmlspecialchars($adresse->telephone) ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type :</strong></td>
                                            <td>
                                                <span class="badge bg-light-subtle text-dark">
                                                    <?php 
                                                    $types = ['domicile' => '🏠 Domicile', 'travail' => '💼 Travail', 'autre' => '📍 Autre'];
                                                    echo $types[$adresse->type_adresse] ?? $adresse->type_adresse;
                                                    ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3"><iconify-icon icon="solar:map-point-bold-duotone" class="me-2"></iconify-icon> Localisation</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="120"><strong>Province :</strong></td>
                                            <td><?= htmlspecialchars($adresse->province_name ?? 'Non spécifiée') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Commune :</strong></td>
                                            <td><?= htmlspecialchars($adresse->commune_name ?? 'Non spécifiée') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Quartier :</strong></td>
                                            <td><?= htmlspecialchars($adresse->quartier_name ?? 'Non spécifié') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Adresse :</strong></td>
                                            <td><?= htmlspecialchars($adresse->adresse_ligne) ?></td>
                                        </tr>
                                        <?php if ($adresse->point_repere): ?>
                                        <tr>
                                            <td><strong>Point de repère :</strong></td>
                                            <td><?= htmlspecialchars($adresse->point_repere) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>

                                <?php if ($adresse->instructions_livraison): ?>
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3"><iconify-icon icon="solar:document-text-bold-duotone" class="me-2"></iconify-icon> Instructions de livraison</h5>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($adresse->instructions_livraison)) ?></p>
                                </div>
                                <?php endif; ?>

                                <div class="info-card p-3 bg-light rounded">
                                    <h5 class="mb-3"><iconify-icon icon="solar:calendar-bold-duotone" class="me-2"></iconify-icon> Informations système</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="120"><strong>Date création :</strong></td>
                                            <td><?= date('d/m/Y à H:i', strtotime($adresse->date_creation)) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>ID :</strong></td>
                                            <td>#<?= $adresse->id_adresse ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0"><iconify-icon icon="solar:map-point-wave-bold-duotone" class="me-2"></iconify-icon> Position sur la carte</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="map" style="height: 450px; border-radius: 8px;"></div>
                                    </div>
                                    <?php if ($adresse->latitude && $adresse->longitude): ?>
                                    <div class="card-footer">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <small class="text-muted">Latitude</small>
                                                <p class="mb-0 fw-medium"><?= $adresse->latitude ?></p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Longitude</small>
                                                <p class="mb-0 fw-medium"><?= $adresse->longitude ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Container Fluid -->

</div>
<!-- ==================================================== -->
<!-- End Page Content -->
<!-- ==================================================== -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;

    function initMap() {
        const lat = <?= isset($adresse) && $adresse->latitude ? $adresse->latitude : '-3.382' ?>;
        const lng = <?= isset($adresse) && $adresse->longitude ? $adresse->longitude : '29.361' ?>;

        map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        const customIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        marker = L.marker([parseFloat(lat), parseFloat(lng)], { icon: customIcon }).addTo(map);
        marker.bindPopup(`
            <strong><?= htmlspecialchars($adresse->nom_complet) ?></strong><br>
            <?= htmlspecialchars($adresse->adresse_ligne) ?><br>
            <?= htmlspecialchars($adresse->province_name ?? '') ?> <?= htmlspecialchars($adresse->commune_name ?? '') ?>
        `).openPopup();
    }

    $(document).ready(function() {
        if (document.getElementById('map')) {
            initMap();
        }
    });
</script>

<style>
    .info-card {
        transition: all 0.3s ease;
    }
    .info-card:hover {
        background-color: #e9ecef !important;
    }
    .table-borderless td, .table-borderless th {
        padding: 8px 0;
    }
    #map {
        z-index: 1;
    }
    .leaflet-container {
        z-index: 1;
    }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>