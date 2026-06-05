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
                            <iconify-icon icon="solar:heart-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des listes de souhaits
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('liste-souhaits/exporter') ?>" class="btn btn-sm btn-success">
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
                                        <span>👥 Utilisateurs actifs</span>
                                        <strong><?= number_format($stats->utilisateurs_actifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>❤️ Total souhaits</span>
                                        <strong><?= number_format($stats->total_souhaits ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📦 Produits souhaités</span>
                                        <strong><?= number_format($stats->produits_souhaites ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📊 Moyenne par utilisateur</span>
                                        <strong><?= number_format($stats->moyenne_par_utilisateur ?? 0, 1) ?></strong>
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
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>" placeholder="Date début">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>" placeholder="Date fin">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('liste-souhaits/admin-list') ?>" class="btn btn-secondary w-100">Réinitialiser</a>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Utilisateur</th>
                                        <th>Email</th>
                                        <th>Produit</th>
                                        <th>Date d'ajout</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($souhaits)): ?>
                                        <?php foreach ($souhaits as $s): ?>
                                            <tr>
                                                <td>#<?= $s->id_souhait ?></div>
                                                <td>
                                                    <strong><?= htmlspecialchars($s->utilisateur_nom) ?></strong>
                                                    <br><small class="text-muted">ID: <?= $s->id_utilisateur ?></small>
                                                </div>
                                                <td><?= htmlspecialchars($s->utilisateur_email) ?></div>
                                                <td>
                                                    <a href="<?= base_url('produits/detail/' . $s->produit_sku) ?>">
                                                        <?= htmlspecialchars($s->produit_nom) ?>
                                                    </a>
                                                    <br><small class="text-muted">SKU: <?= $s->produit_sku ?></small>
                                                </div>
                                                <td><?= date('d/m/Y H:i:s', strtotime($s->date_ajout)) ?></div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('liste-souhaits/detail/' . $s->id_souhait) ?>" 
                                                           class="btn btn-sm btn-info" title="Voir détail">
                                                            <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-danger" 
                                                                onclick="supprimer(<?= $s->id_souhait ?>, '<?= addslashes($s->produit_nom) ?>')"
                                                                title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <iconify-icon icon="solar:heart-broken-bold-duotone" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucune donnée trouvée</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($souhaits)): ?>
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
function supprimer(id, produit) {
    if (confirm('Êtes-vous sûr de vouloir supprimer le produit "' + produit + '" de la liste de souhaits ?')) {
        window.location.href = '<?= base_url("liste-souhaits/supprimer/") ?>' + id;
    }
}
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>