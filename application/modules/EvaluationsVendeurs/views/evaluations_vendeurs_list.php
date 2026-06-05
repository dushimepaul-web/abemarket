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
                            <iconify-icon icon="solar:star-bold-duotone" class="me-2"></iconify-icon>
                            Évaluations des vendeurs
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('evaluations-vendeurs/exporter') ?>" class="btn btn-sm btn-success">
                                <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                Exporter CSV
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⭐ Note moyenne</span>
                                        <strong><?= number_format($stats->note_moyenne ?? 0, 1) ?>/5</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📝 Total évaluations</span>
                                        <strong><?= number_format($stats->total_evaluations ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Approuvées</span>
                                        <strong><?= number_format($stats->approuvees ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏳ En attente</span>
                                        <strong><?= number_format($stats->en_attente ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
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
                                <select name="est_approuve" class="form-select">
                                    <option value="">Tous statuts</option>
                                    <option value="1" <?= ($filters['est_approuve'] ?? '') == '1' ? 'selected' : '' ?>>Approuvées</option>
                                    <option value="0" <?= ($filters['est_approuve'] ?? '') == '0' ? 'selected' : '' ?>>En attente</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="note_min" class="form-select">
                                    <option value="">Note minimale</option>
                                    <option value="5" <?= ($filters['note_min'] ?? '') == '5' ? 'selected' : '' ?>>5 étoiles</option>
                                    <option value="4" <?= ($filters['note_min'] ?? '') == '4' ? 'selected' : '' ?>>4+ étoiles</option>
                                    <option value="3" <?= ($filters['note_min'] ?? '') == '3' ? 'selected' : '' ?>>3+ étoiles</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Vendeur</th>
                                        <th>Client</th>
                                        <th>Commande</th>
                                        <th>Notes</th>
                                        <th>Commentaire</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($evaluations)): ?>
                                        <?php foreach ($evaluations as $e): ?>
                                            <tr>
                                                <td>#<?= $e->id_evaluation ?></div>
                                                <td>
                                                    <strong><?= htmlspecialchars($e->vendeur_nom) ?></strong>
                                                    <br><small class="text-muted">ID: <?= $e->id_vendeur ?></small>
                                                </div>
                                                <td><?= htmlspecialchars($e->client_nom) ?></div>
                                                <td><?= $e->commande_numero ?></div>
                                                <td>
                                                    <div class="text-nowrap">
                                                        <span class="fw-bold">⭐ <?= $e->note_globale ?>/5</span>
                                                        <?php if ($e->note_communication): ?>
                                                            <br><small>💬 Comm: <?= $e->note_communication ?>/5</small>
                                                        <?php endif; ?>
                                                        <?php if ($e->note_livraison): ?>
                                                            <br><small>🚚 Liv: <?= $e->note_livraison ?>/5</small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <td>
                                                    <?php if ($e->commentaire): ?>
                                                        <span title="<?= htmlspecialchars($e->commentaire) ?>">
                                                            <?= strlen($e->commentaire) > 50 ? substr($e->commentaire, 0, 50) . '...' : htmlspecialchars($e->commentaire) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <?php if ($e->est_approuve): ?>
                                                        <span class="badge bg-success">Approuvé</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">En attente</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td><?= date('d/m/Y', strtotime($e->date_creation)) ?></div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('evaluations-vendeurs/detail/' . $e->id_evaluation) ?>" 
                                                           class="btn btn-sm btn-info" title="Voir détail">
                                                            <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                                        </a>
                                                        <?php if (!$e->est_approuve): ?>
                                                            <a href="<?= base_url('evaluations-vendeurs/approuver/' . $e->id_evaluation) ?>" 
                                                               class="btn btn-sm btn-success" title="Approuver">
                                                                <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('evaluations-vendeurs/supprimer/' . $e->id_evaluation) ?>" 
                                                           class="btn btn-sm btn-danger" title="Supprimer"
                                                           onclick="return confirm('Supprimer cette évaluation ?')">
                                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                                        </a>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:star-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucune évaluation trouvée</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($evaluations)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>