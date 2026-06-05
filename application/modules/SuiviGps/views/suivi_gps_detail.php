<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:map-point-bold-duotone" class="me-2"></iconify-icon>
                            Détail du suivi GPS
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('suivi-gps') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                            <a href="<?= base_url('suivi-gps/edit/' . ($suivi->id_suivi ?? '')) ?>" class="btn btn-sm btn-warning">
                                <iconify-icon icon="solar:pen-bold-duotone"></iconify-icon>
                                Modifier
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmerSuppression(<?= $suivi->id_suivi ?? 'null' ?>)">
                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                Supprimer
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Informations générales -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-gradient p-3 rounded-circle me-3">
                                            <iconify-icon icon="solar:map-point-bold-duotone" class="text-white fs-24"></iconify-icon>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">Point GPS #<?= $suivi->id_suivi ?></h5>
                                            <small class="text-muted">Enregistré le <?= date('d/m/Y H:i:s', strtotime($suivi->date_creation)) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-4">
                            <!-- Informations commande -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:box-bold-duotone" class="me-2"></iconify-icon>Informations commande</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Numéro commande :</strong></td>
                                                <td>
                                                    <?php if ($commande): ?>
                                                        <a href="<?= base_url('commandes/detail/' . $commande->id_commande) ?>">
                                                            <?= $commande->numero_commande ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Client :</strong></td>
                                                <td><?= $commande ? htmlspecialchars($commande->prenom . ' ' . $commande->nom) : '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Statut commande :</strong></td>
                                                <td>
                                                    <?php if ($commande): ?>
                                                        <span class="badge bg-<?= $commande->statut_commande == 'livre' ? 'success' : ($commande->statut_commande == 'annule' ? 'danger' : 'warning') ?>">
                                                            <?= strtoupper($commande->statut_commande) ?>
                                                        </span>
                                                    <?php else: ?> - <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations transporteur -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:truck-bold-duotone" class="me-2"></iconify-icon>Informations transporteur</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="40%"><strong>Nom :</strong></td>
                                                <td><?= htmlspecialchars($transporteur->nom ?? '-') ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Téléphone :</strong></td>
                                                <td><?= $transporteur->telephone ?? '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Véhicule :</strong></td>
                                                <td><?= $transporteur->type_vehicule ?? '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Disponible :</strong></td>
                                                <td>
                                                    <?php if (isset($transporteur->est_disponible)): ?>
                                                        <span class="badge bg-<?= $transporteur->est_disponible ? 'success' : 'secondary' ?>">
                                                            <?= $transporteur->est_disponible ? 'Oui' : 'Non' ?>
                                                        </span>
                                                    <?php else: ?> - <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Coordonnées GPS -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:gps-bold-duotone" class="me-2"></iconify-icon>Coordonnées GPS</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center p-3 bg-light rounded">
                                                    <div class="fs-24 text-primary">
                                                        <iconify-icon icon="solar:alt-arrow-up-bold-duotone"></iconify-icon>
                                                    </div>
                                                    <strong>Latitude</strong><br>
                                                    <code><?= $suivi->latitude ?></code>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 bg-light rounded">
                                                    <div class="fs-24 text-primary">
                                                        <iconify-icon icon="solar:alt-arrow-right-bold-duotone"></iconify-icon>
                                                    </div>
                                                    <strong>Longitude</strong><br>
                                                    <code><?= $suivi->longitude ?></code>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 bg-light rounded">
                                                    <div class="fs-24 text-primary">
                                                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                                                    </div>
                                                    <strong>Date/Heure</strong><br>
                                                    <small><?= date('d/m/Y H:i:s', strtotime($suivi->timestamp_gps)) ?></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center p-3 bg-light rounded">
                                                    <div class="fs-24 text-primary">
                                                        <iconify-icon icon="solar:speedometer-bold-duotone"></iconify-icon>
                                                    </div>
                                                    <strong>Vitesse</strong><br>
                                                    <?= $suivi->vitesse_kmh ? number_format($suivi->vitesse_kmh, 1) . ' km/h' : '-' ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Carte -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:map-bold-duotone" class="me-2"></iconify-icon>Visualisation sur la carte</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="detailMap" style="height: 450px; border-radius: 10px;"></div>
                                        <div class="mt-3 text-center">
                                            <a href="https://www.google.com/maps?q=<?= $suivi->latitude ?>,<?= $suivi->longitude ?>" target="_blank" class="btn btn-sm btn-primary">
                                                <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                                                Ouvrir dans Google Maps
                                            </a>
                                            <a href="https://www.openstreetmap.org/?mlat=<?= $suivi->latitude ?>&mlon=<?= $suivi->longitude ?>#map=15/<?= $suivi->latitude ?>/<?= $suivi->longitude ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                <iconify-icon icon="solar:map-bold-duotone"></iconify-icon>
                                                Ouvrir dans OpenStreetMap
                                            </a>
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
</div>

<!-- Scripts pour la carte -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Initialiser la carte de détail
var detailMap = L.map('detailMap').setView([<?= $suivi->latitude ?>, <?= $suivi->longitude ?>], 15);
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(detailMap);

// Ajouter un marqueur
var detailMarker = L.marker([<?= $suivi->latitude ?>, <?= $suivi->longitude ?>]).addTo(detailMap);
detailMarker.bindPopup(`
    <div class="text-center">
        <strong>Position GPS</strong><br>
        Latitude: <?= $suivi->latitude ?><br>
        Longitude: <?= $suivi->longitude ?><br>
        Date: <?= date('d/m/Y H:i:s', strtotime($suivi->timestamp_gps)) ?>
    </div>
`).openPopup();

// Confirmation de suppression
function confirmerSuppression(id) {
    if (id && confirm('Êtes-vous sûr de vouloir supprimer ce point GPS ? Cette action est irréversible.')) {
        window.location.href = '<?= base_url("suivi-gps/delete/") ?>' + id;
    } else if (!id) {
        alert('ID non valide');
    }
}
</script>

<style>
.info-box {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>