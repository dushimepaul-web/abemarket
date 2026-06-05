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
                            <iconify-icon icon="solar:document-text-bold-duotone" class="me-2"></iconify-icon>
                            Détail du log #<?= $log->id_log ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('logs-audit') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Informations générales -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>Informations générales</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="35%"><strong>ID Log :</strong></td>
                                                <td>#<?= $log->id_log ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Type d'action :</strong></td>
                                                <td>
                                                    <span class="badge bg-<?= $this->get_action_color($log->type_action) ?>">
                                                        <?= $log->type_action ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Table cible :</strong></td>
                                                <td><?= $log->table_cible ?: '<span class="text-muted">-</span>' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>ID cible :</strong></td>
                                                <td><?= $log->id_cible ?: '<span class="text-muted">-</span>' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Adresse IP :</strong></td>
                                                <td><code><?= $log->adresse_ip ?: '-' ?></code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Date :</strong></td>
                                                <td><?= date('d/m/Y à H:i:s', strtotime($log->date_creation)) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations utilisateur -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:user-bold-duotone" class="me-2"></iconify-icon>Utilisateur</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($log->utilisateur_nom): ?>
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <td width="35%"><strong>Nom :</strong></td>
                                                    <td><?= htmlspecialchars($log->utilisateur_nom) ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email :</strong></td>
                                                    <td><?= $log->utilisateur_email ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>ID Utilisateur :</strong></td>
                                                    <td><?= $log->id_utilisateur ?></td>
                                                </tr>
                                            </table>
                                        <?php else: ?>
                                            <p class="text-muted mb-0">Action système (aucun utilisateur connecté)</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Données modifiées -->
                        <?php if ($log->valeurs_avant || $log->valeurs_apres): ?>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><iconify-icon icon="solar:code-bold-duotone" class="me-2"></iconify-icon>Données modifiées</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <?php if ($log->valeurs_avant): ?>
                                                    <div class="col-md-6">
                                                        <h6 class="text-danger">Avant modification</h6>
                                                        <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow: auto;"><code><?= json_encode(json_decode($log->valeurs_avant), JSON_PRETTY_PRINT) ?></code></pre>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if ($log->valeurs_apres): ?>
                                                    <div class="col-md-6">
                                                        <h6 class="text-success">Après modification</h6>
                                                        <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow: auto;"><code><?= json_encode(json_decode($log->valeurs_apres), JSON_PRETTY_PRINT) ?></code></pre>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
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

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>

<?php
function get_action_color($action) {
    $colors = [
        'connexion' => 'success',
        'deconnexion' => 'secondary',
        'creation' => 'primary',
        'modification' => 'warning',
        'suppression' => 'danger',
        'export' => 'info'
    ];
    
    foreach ($colors as $key => $color) {
        if (strpos(strtolower($action), $key) !== false) {
            return $color;
        }
    }
    return 'secondary';
}
?>