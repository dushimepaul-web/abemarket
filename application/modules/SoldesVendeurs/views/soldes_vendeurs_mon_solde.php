<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:wallet-bold-duotone" class="me-2"></iconify-icon>
                            Mon solde
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        <!-- Informations boutique -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <?php if ($vendeur->logo_boutique): ?>
                                        <img src="<?= base_url($vendeur->logo_boutique) ?>" alt="Logo" class="rounded-circle me-3" width="60" height="60">
                                    <?php else: ?>
                                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                            <iconify-icon icon="solar:shop-bold-duotone" class="text-white fs-30"></iconify-icon>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h4 class="mb-0"><?= htmlspecialchars($vendeur->nom_boutique) ?></h4>
                                        <p class="mb-0 text-muted">Bienvenue dans votre espace financier</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cartes des soldes -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="card bg-success bg-gradient text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-white-50">Solde disponible</h6>
                                                <h2 class="mb-0"><?= number_format($solde->solde_disponible ?? 0, 0, ',', ' ') ?> <small>FBu</small></h2>
                                            </div>
                                            <iconify-icon icon="solar:wallet-bold" class="fs-48"></iconify-icon>
                                        </div>
                                        <small class="text-white-50">Montant pouvant être retiré</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-warning bg-gradient text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-white-50">Solde en attente</h6>
                                                <h2 class="mb-0"><?= number_format($solde->solde_en_attente ?? 0, 0, ',', ' ') ?> <small>FBu</small></h2>
                                            </div>
                                            <iconify-icon icon="solar:clock-circle-bold" class="fs-48"></iconify-icon>
                                        </div>
                                        <small class="text-white-50">En cours de validation</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info bg-gradient text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 text-white-50">Total gagné</h6>
                                                <h2 class="mb-0"><?= number_format($solde->total_gagne ?? 0, 0, ',', ' ') ?> <small>FBu</small></h2>
                                            </div>
                                            <iconify-icon icon="solar:chart-2-bold" class="fs-48"></iconify-icon>
                                        </div>
                                        <small class="text-white-50">Depuis le début</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Historique des paiements -->
                        <div class="card border shadow-none">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <iconify-icon icon="solar:history-bold-duotone" class="me-2"></iconify-icon>
                                    Historique des paiements
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($historique_paiements)): ?>
                                    <div class="text-center py-4">
                                        <iconify-icon icon="solar:history-broken" class="fs-48 text-muted"></iconify-icon>
                                        <p class="mt-2">Aucun paiement effectué pour le moment</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Période</th>
                                                    <th>Commandes</th>
                                                    <th>Revenus</th>
                                                    <th>Commissions</th>
                                                    <th>Montant net</th>
                                                    <th>Statut</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($historique_paiements as $p): ?>
                                                    <tr>
                                                        <td><?= date('d/m/Y', strtotime($p->date_debut_periode)) ?> - <?= date('d/m/Y', strtotime($p->date_fin_periode)) ?></div>
                                                        <td><?= $p->nombre_commandes ?></div>
                                                        <td><?= number_format($p->total_revenus, 0, ',', ' ') ?> FBu</div>
                                                        <td><?= number_format($p->total_commissions, 0, ',', ' ') ?> FBu</div>
                                                        <td>
                                                            <strong class="text-success">
                                                                <?= number_format($p->montant_net, 0, ',', ' ') ?> FBu
                                                            </strong>
                                                        </div>
                                                        <td>
                                                            <?php if ($p->statut == 'paye'): ?>
                                                                <span class="badge bg-success">Payé</span>
                                                            <?php elseif ($p->statut == 'en_attente'): ?>
                                                                <span class="badge bg-warning">En attente</span>
                                                            <?php elseif ($p->statut == 'en_cours'): ?>
                                                                <span class="badge bg-info">En cours</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger">Échoué</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <td><?= date('d/m/Y', strtotime($p->date_creation)) ?></div>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Informations paiement -->
                        <div class="card border shadow-none mt-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <iconify-icon icon="solar:card-bold-duotone" class="me-2"></iconify-icon>
                                    Informations de paiement
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                                    Les paiements sont effectués automatiquement chaque mois pour les soldes supérieurs à 50 000 FBu.
                                    <a href="<?= base_url('config-paiement-vendeur') ?>" class="alert-link">Configurez vos informations de paiement</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>