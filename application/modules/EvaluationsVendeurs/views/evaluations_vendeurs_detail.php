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
                            <iconify-icon icon="solar:star-bold-duotone" class="me-2"></iconify-icon>
                            Détail de l'évaluation #<?= $evaluation->id_evaluation ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('evaluations-vendeurs') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                            <?php if (!$evaluation->est_approuve && $this->is_admin()): ?>
                                <a href="<?= base_url('evaluations-vendeurs/approuver/' . $evaluation->id_evaluation) ?>" class="btn btn-sm btn-success">
                                    <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                    Approuver
                                </a>
                            <?php endif; ?>
                            <?php if ($this->is_admin()): ?>
                                <a href="<?= base_url('evaluations-vendeurs/supprimer/' . $evaluation->id_evaluation) ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Supprimer cette évaluation ?')">
                                    <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                    Supprimer
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Vendeur -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:shop-bold-duotone" class="me-2"></iconify-icon>Vendeur</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <?php if ($evaluation->logo_boutique): ?>
                                                <img src="<?= base_url($evaluation->logo_boutique) ?>" alt="Logo" class="rounded-circle me-3" width="60" height="60">
                                            <?php else: ?>
                                                <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                                    <iconify-icon icon="solar:shop-bold-duotone" class="text-white fs-30"></iconify-icon>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <h5 class="mb-0"><?= htmlspecialchars($evaluation->vendeur_nom) ?></h5>
                                                <small class="text-muted">ID Vendeur: <?= $evaluation->id_vendeur ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Client -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:user-bold-duotone" class="me-2"></iconify-icon>Client</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="35%"><strong>Nom :</strong></td>
                                                <td><?= htmlspecialchars($evaluation->client_nom) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email :</strong></td>
                                                <td><?= $evaluation->client_email ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Téléphone :</strong></td>
                                                <td><?= $evaluation->client_telephone ?? '-' ?></div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Commande -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:box-bold-duotone" class="me-2"></iconify-icon>Commande</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="35%"><strong>Numéro :</strong></td>
                                                <td><?= $evaluation->commande_numero ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Montant :</strong></td>
                                                <td><?= number_format($evaluation->commande_montant, 0, ',', ' ') ?> FBu</div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Notes -->
                            <div class="col-md-6">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:star-bold-duotone" class="me-2"></iconify-icon>Évaluation</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="border rounded p-2">
                                                    <div class="display-6 fw-bold text-warning"><?= $evaluation->note_globale ?>/5</div>
                                                    <div class="text-muted small">Globale</div>
                                                </div>
                                            </div>
                                            <?php if ($evaluation->note_communication): ?>
                                                <div class="col-4">
                                                    <div class="border rounded p-2">
                                                        <div class="display-6 fw-bold text-warning"><?= $evaluation->note_communication ?>/5</div>
                                                        <div class="text-muted small">Communication</div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($evaluation->note_livraison): ?>
                                                <div class="col-4">
                                                    <div class="border rounded p-2">
                                                        <div class="display-6 fw-bold text-warning"><?= $evaluation->note_livraison ?>/5</div>
                                                        <div class="text-muted small">Livraison</div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Commentaire -->
                        <?php if ($evaluation->commentaire): ?>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><iconify-icon icon="solar:chat-square-text-bold-duotone" class="me-2"></iconify-icon>Commentaire</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0">"<?= nl2br(htmlspecialchars($evaluation->commentaire)) ?>"</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Date -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:calendar-bold-duotone" class="me-2"></iconify-icon>Informations</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Date de création :</strong><br>
                                                <?= date('d/m/Y à H:i:s', strtotime($evaluation->date_creation)) ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Statut :</strong><br>
                                                <?php if ($evaluation->est_approuve): ?>
                                                    <span class="badge bg-success">Approuvé</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">En attente de modération</span>
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
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>