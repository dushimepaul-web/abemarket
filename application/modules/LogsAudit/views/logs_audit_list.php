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
                            <iconify-icon icon="solar:document-text-bold-duotone" class="me-2"></iconify-icon>
                            Journal d'audit système
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('logs-audit/statistiques') ?>" class="btn btn-sm btn-info">
                                <iconify-icon icon="solar:chart-bold-duotone"></iconify-icon>
                                Statistiques
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                    Exporter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= base_url('logs-audit/exporter?format=csv&' . http_build_query($filters)) ?>">CSV</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('logs-audit/exporter?format=json&' . http_build_query($filters)) ?>">JSON</a></li>
                                </ul>
                            </div>
                            <?php if (isset($is_super_admin) && $is_super_admin): ?>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cleanModal">
                                    <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                    Nettoyer
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Statistiques rapides -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📝 Total logs</span>
                                        <strong><?= number_format($stats->total_logs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>👥 Utilisateurs actifs</span>
                                        <strong><?= number_format($stats->utilisateurs_actifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>🏷️ Types d'actions</span>
                                        <strong><?= number_format($stats->types_actions ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📊 Tables modifiées</span>
                                        <strong><?= number_format($stats->tables_modifiees ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="id_utilisateur" class="form-select select2">
                                    <option value="">Tous les utilisateurs</option>
                                    <?php foreach ($utilisateurs as $u): ?>
                                        <option value="<?= $u->id_utilisateur ?>" <?= ($filters['id_utilisateur'] ?? '') == $u->id_utilisateur ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($u->prenom . ' ' . $u->nom . ' (' . $u->email . ')') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="type_action" class="form-select">
                                    <option value="">Toutes actions</option>
                                    <?php foreach ($types_action as $type): ?>
                                        <option value="<?= $type ?>" <?= ($filters['type_action'] ?? '') == $type ? 'selected' : '' ?>>
                                            <?= $type ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="table_cible" class="form-select">
                                    <option value="">Toutes tables</option>
                                    <?php foreach ($tables_cibles as $table): ?>
                                        <option value="<?= $table ?>" <?= ($filters['table_cible'] ?? '') == $table ? 'selected' : '' ?>>
                                            <?= $table ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>" placeholder="Date début">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>" placeholder="Date fin">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Utilisateur</th>
                                        <th>Action</th>
                                        <th>Table cible</th>
                                        <th>ID cible</th>
                                        <th>Adresse IP</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($logs)): ?>
                                        <?php foreach ($logs as $log): ?>
                                            <tr>
                                                <td>#<?= $log->id_log ?></td>
                                                <td>
                                                    <?php if ($log->utilisateur_nom): ?>
                                                        <strong><?= htmlspecialchars($log->utilisateur_nom) ?></strong>
                                                        <br><small class="text-muted"><?= $log->utilisateur_email ?></small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Système</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <span class="badge bg-<?= get_action_color($log->type_action) ?>">
                                                        <?= $log->type_action ?>
                                                    </span>
                                                </div>
                                                <td><?= $log->table_cible ?: '<span class="text-muted">-</span>' ?></div>
                                                <td><?= $log->id_cible ?: '<span class="text-muted">-</span>' ?></div>
                                                <td><code><?= $log->adresse_ip ?: '-' ?></code></div>
                                                <td><?= date('d/m/Y H:i:s', strtotime($log->date_creation)) ?></div>
                                                <td>
                                                    <a href="<?= base_url('logs-audit/detail/' . $log->id_log) ?>" 
                                                       class="btn btn-sm btn-info" title="Voir détails">
                                                        <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                                    </a>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <iconify-icon icon="solar:document-text-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun log trouvé</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($logs)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nettoyer -->
<div class="modal fade" id="cleanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('logs-audit/nettoyer') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Nettoyer les logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Supprimer les logs plus vieux que (jours)</label>
                        <input type="number" name="jours" class="form-control" value="90" min="1" max="365">
                        <small class="text-muted">Cette action est irréversible</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>

<?php
// Fonction helper pour les couleurs des actions
if (!function_exists('get_action_color')) {
    function get_action_color($action) {
        $colors = [
            'connexion' => 'success',
            'deconnexion' => 'secondary',
            'creation' => 'primary',
            'modification' => 'warning',
            'suppression' => 'danger',
            'export' => 'info',
            'import' => 'info',
            'validation' => 'success',
            'rejet' => 'danger'
        ];
        
        foreach ($colors as $key => $color) {
            if (strpos(strtolower($action), $key) !== false) {
                return $color;
            }
        }
        return 'secondary';
    }
}
?>