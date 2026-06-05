<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Détails de la zone - <?= htmlspecialchars($zone->nom_zone) ?></h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('zone-livraison/edit/' . $zone->id_zone_liv) ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-edit me-1"></i>Modifier
                            </a>
                            <a href="<?= base_url('zone-livraison') ?>" class="btn btn-sm btn-secondary">
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
                                        <tr><td width="180"><strong>ID :</strong></td><td>#<?= $zone->id_zone_liv ?></td></tr>
                                        <tr><td><strong>Nom :</strong></td><td><?= htmlspecialchars($zone->nom_zone) ?></td></tr>
                                        <tr><td><strong>Province :</strong></td><td><?= htmlspecialchars($zone->province_name ?? 'Toutes les provinces') ?></td></tr>
                                        <tr><td><strong>Statut :</strong></td>
                                            <td><?= $zone->est_actif ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-danger">Inactif</span>' ?></td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3"><iconify-icon icon="solar:wallet-bold-duotone" class="me-2"></iconify-icon> Tarifs</h5>
                                    <table class="table table-borderless">
                                        <tr><td width="180"><strong>Coût de base :</strong></td><td><strong class="text-primary"><?= number_format($zone->cout_base, 0, ',', ' ') ?> FBu</strong></td></tr>
                                        <?php if ($zone->seuil_livraison_gratuite): ?>
                                        <tr><td><strong>Seuil livraison gratuite :</strong></td><td class="text-success">≥ <?= number_format($zone->seuil_livraison_gratuite, 0, ',', ' ') ?> FBu</td></tr>
                                        <?php endif; ?>
                                        <?php if ($zone->cout_par_kg): ?>
                                        <tr><td><strong>Coût par kg supplémentaire :</strong></td><td><?= number_format($zone->cout_par_kg, 0, ',', ' ') ?> FBu/kg</td></tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3"><iconify-icon icon="solar:calendar-bold-duotone" class="me-2"></iconify-icon> Délais de livraison</h5>
                                    <table class="table table-borderless">
                                        <?php if ($zone->delai_min_jours && $zone->delai_max_jours): ?>
                                        <tr><td width="180"><strong>Délai estimé :</strong></td><td><?= $zone->delai_min_jours ?> - <?= $zone->delai_max_jours ?> jours ouvrés</td></tr>
                                        <?php else: ?>
                                        <tr><td><strong>Délai estimé :</strong></td><td>Non spécifié</td></tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                                
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3"><iconify-icon icon="solar:map-point-bold-duotone" class="me-2"></iconify-icon> Zones couvertes</h5>
                                    
                                    <?php if (!empty($zone->communes)): ?>
                                    <div class="mb-3">
                                        <strong>Communes (<?= count($zone->communes) ?>) :</strong>
                                        <div class="mt-2">
                                            <?php foreach ($zone->communes as $c): ?>
                                                <span class="badge bg-info me-1 mb-1"><?= htmlspecialchars($c->commune_name) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($zone->quartiers)): ?>
                                    <div>
                                        <strong>Quartiers (<?= count($zone->quartiers) ?>) :</strong>
                                        <div class="mt-2">
                                            <?php foreach ($zone->quartiers as $q): ?>
                                                <span class="badge bg-secondary me-1 mb-1"><?= htmlspecialchars($q->quartier_name) ?> (<?= htmlspecialchars($q->commune_name) ?>)</span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (empty($zone->communes) && empty($zone->quartiers)): ?>
                                    <p class="text-muted mb-0">Toutes les localités de la province (ou tout le pays si aucune province sélectionnée)</p>
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

<style>
.info-card { transition: all 0.3s ease; }
.info-card:hover { background-color: #e9ecef !important; }
.table-borderless td, .table-borderless th { padding: 8px 0; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>