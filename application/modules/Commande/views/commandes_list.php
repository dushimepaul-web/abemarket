<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-primary bg-opacity-10 p-3">
                                <iconify-icon icon="solar:cart-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($stats->total ?? 0) ?></h3>
                                <p class="text-muted mb-0">Total commandes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-warning bg-opacity-10 p-3">
                                <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-32 text-warning"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($stats->en_attente ?? 0) ?></h3>
                                <p class="text-muted mb-0">En attente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-success bg-opacity-10 p-3">
                                <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-32 text-success"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($stats->livre ?? 0) ?></h3>
                                <p class="text-muted mb-0">Livrées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-info bg-opacity-10 p-3">
                                <iconify-icon icon="solar:wallet-bold-duotone" class="fs-32 text-info"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($stats->chiffre_affaires ?? 0, 0, ',', ' ') ?> FBu</h3>
                                <p class="text-muted mb-0">Chiffre d'affaires</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title flex-grow-1">📋 Liste des commandes</h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('Commande/exporter') ?>" class="btn btn-sm btn-success">
                                <iconify-icon icon="solar:export-bold-duotone"></iconify-icon> Exporter
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="statut_commande" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_attente" <?= ($filters['statut_commande'] ?? '') == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="confirme" <?= ($filters['statut_commande'] ?? '') == 'confirme' ? 'selected' : '' ?>>Confirmée</option>
                                    <option value="en_preparation" <?= ($filters['statut_commande'] ?? '') == 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                                    <option value="expedie" <?= ($filters['statut_commande'] ?? '') == 'expedie' ? 'selected' : '' ?>>Expédiée</option>
                                    <option value="livre" <?= ($filters['statut_commande'] ?? '') == 'livre' ? 'selected' : '' ?>>Livrée</option>
                                    <option value="annule" <?= ($filters['statut_commande'] ?? '') == 'annule' ? 'selected' : '' ?>>Annulée</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>" placeholder="Date début">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>" placeholder="Date fin">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" value="<?= $filters['search'] ?? '' ?>" placeholder="N° commande, client...">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($commandes)): ?>
                                        <?php foreach ($commandes as $c): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($c->numero_commande) ?></strong></div>
                                                <td>
                                                    <?= htmlspecialchars($c->prenom . ' ' . $c->nom) ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($c->email) ?></small>
                                                </div>
                                                <td><?= date('d/m/Y H:i', strtotime($c->date_creation)) ?></div>
                                                <td><strong><?= number_format($c->montant_total, 0, ',', ' ') ?> FBu</strong></div>
                                                <td>
                                                    <?php
                                                    $badge_color = '';
                                                    switch($c->statut_commande) {
                                                        case 'en_attente': $badge_color = 'warning'; break;
                                                        case 'confirme': $badge_color = 'info'; break;
                                                        case 'en_preparation': $badge_color = 'primary'; break;
                                                        case 'expedie': $badge_color = 'secondary'; break;
                                                        case 'livre': $badge_color = 'success'; break;
                                                        case 'annule': $badge_color = 'danger'; break;
                                                        default: $badge_color = 'secondary';
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $badge_color ?>"><?= ucfirst(str_replace('_', ' ', $c->statut_commande)) ?></span>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('Commande/detail/' . $c->id_commande) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <?php if ($is_admin): ?>
                                                            <a href="<?= base_url('Commande/edit/' . $c->id_commande) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                                <iconify-icon icon="solar:pen-2-broken"></iconify-icon>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <iconify-icon icon="solar:cart-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucune commande trouvée</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </tr>
                        </div>
                    </div>
                    
                    <?php if (!empty($commandes)): ?>
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