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
                            <iconify-icon icon="solar:wallet-bold-duotone" class="me-2"></iconify-icon>
                            Détail du solde - <?= htmlspecialchars($vendeur->nom_boutique) ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('soldes-vendeurs') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                            <button type="button" class="btn btn-sm btn-warning" onclick="recalculer(<?= $vendeur->id_vendeur ?>)">
                                <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                                Recalculer
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Informations vendeur -->
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
                                        <p class="mb-0 text-muted">
                                            <?= $vendeur->type_vendeur == 'entreprise' ? $vendeur->nom_entreprise : 'Vendeur particulier' ?>
                                            <?php if ($vendeur->numero_nif): ?> | NIF: <?= $vendeur->numero_nif ?><?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cartes des soldes -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="card bg-success bg-gradient text-white">
                                    <div class="card-body">
                                        <h6 class="mb-0 text-white-50">Solde disponible</h6>
                                        <h3 class="mb-0"><?= number_format($solde->solde_disponible ?? 0, 0, ',', ' ') ?> FBu</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning bg-gradient text-white">
                                    <div class="card-body">
                                        <h6 class="mb-0 text-white-50">Solde en attente</h6>
                                        <h3 class="mb-0"><?= number_format($solde->solde_en_attente ?? 0, 0, ',', ' ') ?> FBu</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-primary bg-gradient text-white">
                                    <div class="card-body">
                                        <h6 class="mb-0 text-white-50">Total gagné</h6>
                                        <h3 class="mb-0"><?= number_format($solde->total_gagne ?? 0, 0, ',', ' ') ?> FBu</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-secondary bg-gradient text-white">
                                    <div class="card-body">
                                        <h6 class="mb-0 text-white-50">Total retiré</h6>
                                        <h3 class="mb-0"><?= number_format($solde->total_retire ?? 0, 0, ',', ' ') ?> FBu</h3>
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
                                        <p class="text-muted">Aucun paiement effectué</p>
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Période</th>
                                                    <th>Commandes</th>
                                                    <th>Revenus</th>
                                                    <th>Commissions</th>
                                                    <th>Montant net</th>
                                                    <th>Statut</th>
                                                    <th>Référence</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($historique_paiements as $p): ?>
                                                    <tr>
                                                        <td>#<?= $p->id_paiement ?></div>
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
                                                            <?php else: ?>
                                                                <span class="badge bg-danger"><?= $p->statut ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <td><?= $p->reference_transaction ?? '-' ?></div>
                                                        <td><?= date('d/m/Y', strtotime($p->date_creation)) ?></div>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
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

<script>
function recalculer(id_vendeur) {
    if (confirm('Recalculer le solde de ce vendeur ?')) {
        window.location.href = '<?= base_url("soldes-vendeurs/recalculer/") ?>' + id_vendeur;
    }
}
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>