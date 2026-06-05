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
                            <iconify-icon icon="solar:wallet-bold-duotone" class="me-2"></iconify-icon>
                            Soldes des vendeurs
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('soldes-vendeurs/exporter') ?>" class="btn btn-sm btn-success">
                                <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                Exporter CSV
                            </a>
                            <button type="button" class="btn btn-sm btn-warning" onclick="recalculerTous()">
                                <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                                Recalculer tous
                            </button>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>💰 Solde disponible total</span>
                                        <strong><?= number_format($stats->total_solde_disponible ?? 0, 0, ',', ' ') ?> FBu</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏳ Solde en attente total</span>
                                        <strong><?= number_format($stats->total_solde_attente ?? 0, 0, ',', ' ') ?> FBu</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📈 Total gagné</span>
                                        <strong><?= number_format($stats->total_gagne ?? 0, 0, ',', ' ') ?> FBu</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>🏪 Vendeurs actifs</span>
                                        <strong><?= number_format($stats->total_vendeurs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <select name="id_vendeur" class="form-select select2">
                                    <option value="">Tous les vendeurs</option>
                                    <?php foreach ($vendeurs as $v): ?>
                                        <option value="<?= $v->id_vendeur ?>" <?= ($filters['id_vendeur'] ?? '') == $v->id_vendeur ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($v->nom_boutique) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="solde_min" class="form-control" placeholder="Solde min" value="<?= $filters['solde_min'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="solde_max" class="form-control" placeholder="Solde max" value="<?= $filters['solde_max'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('soldes-vendeurs') ?>" class="btn btn-secondary w-100">Réinitialiser</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Vendeur</th>
                                        <th>Solde disponible</th>
                                        <th>Solde en attente</th>
                                        <th>Total gagné</th>
                                        <th>Total retiré</th>
                                        <th>Dernier paiement</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($soldes)): ?>
                                        <?php foreach ($soldes as $s): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($s->nom_boutique) ?></strong>
                                                    <br><small class="text-muted">ID: <?= $s->id_vendeur ?></small>
                                                    <?php if ($s->vendeur_statut == 'suspendu'): ?>
                                                        <span class="badge bg-danger">Suspendu</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <span class="fw-bold text-success">
                                                        <?= number_format($s->solde_disponible, 0, ',', ' ') ?> FBu
                                                    </span>
                                                </div>
                                                <td>
                                                    <span class="text-warning">
                                                        <?= number_format($s->solde_en_attente, 0, ',', ' ') ?> FBu
                                                    </span>
                                                </div>
                                                <td><?= number_format($s->total_gagne, 0, ',', ' ') ?> FBu</div>
                                                <td><?= number_format($s->total_retire, 0, ',', ' ') ?> FBu</div>
                                                <td>
                                                    <?php if ($s->dernier_paiement): ?>
                                                        <?= date('d/m/Y', strtotime($s->dernier_paiement)) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('soldes-vendeurs/detail/' . $s->id_solde) ?>" 
                                                           class="btn btn-sm btn-info" title="Voir détails">
                                                            <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-warning" 
                                                                onclick="recalculer(<?= $s->id_vendeur ?>)"
                                                                title="Recalculer">
                                                            <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <iconify-icon icon="solar:wallet-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun solde trouvé</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($soldes)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
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

function recalculerTous() {
    if (confirm('Recalculer tous les soldes ? Cette opération peut prendre du temps.')) {
        window.location.href = '<?= base_url("soldes-vendeurs/recalculer_tous") ?>';
    }
}
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>