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
                            <iconify-icon icon="solar:star-bold-duotone" class="me-2"></iconify-icon>
                            Évaluations de ma boutique
                        </h4>
                    </div>
                    
                    <!-- Statistiques boutique -->
                    <div class="card-body border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="me-4">
                                        <div class="display-1 fw-bold text-warning"><?= number_format($stats->note_moyenne ?? 0, 1) ?></div>
                                        <div class="text-muted">/5</div>
                                    </div>
                                    <div>
                                        <div class="text-warning fs-18">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <iconify-icon icon="<?= $i <= round($stats->note_moyenne ?? 0) ? 'solar:star-bold' : 'solar:star-linear' ?>"></iconify-icon>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="text-muted">Basé sur <?= number_format($stats->total_evaluations ?? 0) ?> évaluation(s)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <div class="fs-20 fw-bold"><?= number_format($stats->total_evaluations ?? 0) ?></div>
                                            <div class="text-muted small">Total</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <div class="fs-20 fw-bold"><?= number_format($stats->note_communication_moyenne ?? 0, 1) ?></div>
                                            <div class="text-muted small">Communication</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border rounded p-2">
                                            <div class="fs-20 fw-bold"><?= number_format($stats->note_livraison_moyenne ?? 0, 1) ?></div>
                                            <div class="text-muted small">Livraison</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="est_approuve" class="form-select">
                                    <option value="">Tous statuts</option>
                                    <option value="1" <?= ($filters['est_approuve'] ?? '') == '1' ? 'selected' : '' ?>>Approuvées</option>
                                    <option value="0" <?= ($filters['est_approuve'] ?? '') == '0' ? 'selected' : '' ?>>En attente</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="note_min" class="form-select">
                                    <option value="">Note minimale</option>
                                    <option value="5">5 étoiles</option>
                                    <option value="4">4+ étoiles</option>
                                    <option value="3">3+ étoiles</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <?php if (empty($evaluations)): ?>
                            <div class="text-center py-5">
                                <iconify-icon icon="solar:star-broken" class="fs-48 text-muted"></iconify-icon>
                                <p class="mt-2">Aucune évaluation pour le moment</p>
                            </div>
                        <?php else: ?>
                            <div class="row g-4">
                                <?php foreach ($evaluations as $e): ?>
                                    <div class="col-md-6">
                                        <div class="card border shadow-none h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <div class="fw-bold"><?= htmlspecialchars($e->client_nom) ?></div>
                                                        <small class="text-muted">Commande: <?= $e->commande_numero ?></small>
                                                    </div>
                                                    <div class="text-warning">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <iconify-icon icon="<?= $i <= $e->note_globale ? 'solar:star-bold' : 'solar:star-linear' ?>" class="fs-18"></iconify-icon>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                
                                                <?php if ($e->note_communication || $e->note_livraison): ?>
                                                    <div class="row mb-3">
                                                        <?php if ($e->note_communication): ?>
                                                            <div class="col-6">
                                                                <small class="text-muted">Communication</small>
                                                                <div class="text-warning">
                                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                                        <iconify-icon icon="<?= $i <= $e->note_communication ? 'solar:star-bold' : 'solar:star-linear' ?>" class="fs-12"></iconify-icon>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($e->note_livraison): ?>
                                                            <div class="col-6">
                                                                <small class="text-muted">Livraison</small>
                                                                <div class="text-warning">
                                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                                        <iconify-icon icon="<?= $i <= $e->note_livraison ? 'solar:star-bold' : 'solar:star-linear' ?>" class="fs-12"></iconify-icon>
                                                                    <?php endfor; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($e->commentaire): ?>
                                                    <p class="mb-0">"<?= htmlspecialchars($e->commentaire) ?>"</p>
                                                <?php endif; ?>
                                                
                                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                                    <small class="text-muted"><?= date('d/m/Y', strtotime($e->date_creation)) ?></small>
                                                    <?php if (!$e->est_approuve): ?>
                                                        <span class="badge bg-warning">En attente de modération</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Publié</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="mt-4">
                                <?= $this->pagination->create_links() ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>