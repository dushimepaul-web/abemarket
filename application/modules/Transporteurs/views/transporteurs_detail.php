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
                            <iconify-icon icon="solar:truck-bold-duotone" class="me-2"></iconify-icon>
                            Détails du transporteur
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('transporteurs/edit/' . $transporteur->id_transporteur) ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:pen-2-broken"></iconify-icon> Modifier
                            </a>
                            <a href="<?= base_url('transporteurs') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <?php if ($transporteur->photo_url && file_exists(FCPATH . $transporteur->photo_url)): ?>
                                    <img src="<?= base_url($transporteur->photo_url) ?>" alt="Photo" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                                        <iconify-icon icon="solar:user-circle-bold-duotone" class="fs-80 text-muted"></iconify-icon>
                                    </div>
                                <?php endif; ?>
                                <h4 class="mt-3"><?= htmlspecialchars($transporteur->nom) ?></h4>
                                <span class="badge bg-<?= $transporteur->statut == 'actif' ? 'success' : 'secondary' ?>"><?= ucfirst($transporteur->statut) ?></span>
                                <?php if ($transporteur->est_disponible): ?>
                                    <span class="badge bg-success">🟢 Disponible</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">🔴 Occupé</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">📋 Informations générales</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Type :</strong></td><td><?= ucfirst($transporteur->type) ?></td></tr>
                                            <tr><td><strong>Téléphone :</strong></td><td><a href="tel:<?= $transporteur->telephone ?>"><?= htmlspecialchars($transporteur->telephone) ?></a></td></tr>
                                            <?php if ($transporteur->whatsapp): ?>
                                            <tr><td><strong>WhatsApp :</strong></td><td><a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $transporteur->whatsapp) ?>" target="_blank"><?= htmlspecialchars($transporteur->whatsapp) ?></a></td></tr>
                                            <?php endif; ?>
                                            <tr><td><strong>Véhicule :</strong></td><td><?= ucfirst($transporteur->type_vehicule) ?></td></tr>
                                            <?php if ($transporteur->plaque): ?>
                                            <tr><td><strong>Plaque :</strong></td><td><code><?= htmlspecialchars($transporteur->plaque) ?></code></td></tr>
                                            <?php endif; ?>
                                            <?php if ($transporteur->id_utilisateur): ?>
                                            <tr><td><strong>Utilisateur associé :</strong></td><td>#<?= $transporteur->id_utilisateur ?></td></tr>
                                            <?php endif; ?>
                                            <tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">📊 Statistiques</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Livraisons totales :</strong></td><td><strong><?= number_format($transporteur->livraisons_totales) ?></strong></td></tr>
                                            <tr><td><strong>Livraisons réussies :</strong></td><td><strong class="text-success"><?= number_format($transporteur->livraisons_reussies) ?></strong></td></tr>
                                            <tr><td><strong>Taux de réussite :</strong></td><td>
                                                <?php $taux = $transporteur->livraisons_totales > 0 ? ($transporteur->livraisons_reussies / $transporteur->livraisons_totales) * 100 : 0; ?>
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-success" style="width: <?= $taux ?>%"></div>
                                                </div>
                                                <small><?= round($taux, 1) ?>%</small>
                                            </td></tr>
                                            <tr><td><strong>Note moyenne :</strong></td><td>
                                                <div class="text-warning">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <iconify-icon icon="solar:star-<?= $i <= round($transporteur->note_moyenne) ? 'bold' : 'linear' ?>-duotone" class="fs-20"></iconify-icon>
                                                    <?php endfor; ?>
                                                    <span class="ms-2">(<?= number_format($transporteur->note_moyenne, 1) ?>/5)</span>
                                                </div>
                                            </td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">📍 Position actuelle</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($transporteur->latitude_actuelle && $transporteur->longitude_actuelle): ?>
                                            <div id="map" style="height: 200px; border-radius: 10px;"></div>
                                            <div class="mt-2">
                                                <small>Latitude: <?= $transporteur->latitude_actuelle ?></small><br>
                                                <small>Longitude: <?= $transporteur->longitude_actuelle ?></small><br>
                                                <small>Dernière mise à jour: <?= $transporteur->derniere_position ? date('d/m/Y H:i', strtotime($transporteur->derniere_position)) : 'Jamais' ?></small>
                                            </div>
                                            <a href="https://www.google.com/maps?q=<?= $transporteur->latitude_actuelle ?>,<?= $transporteur->longitude_actuelle ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon> Voir sur Google Maps
                                            </a>
                                        <?php else: ?>
                                            <div class="text-center py-4">
                                                <iconify-icon icon="solar:map-point-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Position non disponible</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">📅 Informations système</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Date création :</strong></td><td><?= date('d/m/Y H:i', strtotime($transporteur->date_creation)) ?></td></tr>
                                        </table>
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

<?php if ($transporteur->latitude_actuelle && $transporteur->longitude_actuelle): ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
<script>
    var map = L.map('map').setView([<?= $transporteur->latitude_actuelle ?>, <?= $transporteur->longitude_actuelle ?>], 13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    L.marker([<?= $transporteur->latitude_actuelle ?>, <?= $transporteur->longitude_actuelle ?>])
        .addTo(map)
        .bindPopup('<strong><?= htmlspecialchars($transporteur->nom) ?></strong><br><?= ucfirst($transporteur->type_vehicule) ?>')
        .openPopup();
</script>
<?php endif; ?>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>