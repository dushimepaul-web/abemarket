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
                            <iconify-icon icon="solar:bell-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des notifications
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('notifications/create') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Nouvelle notification
                            </a>
                            <a href="<?= base_url('notifications/exporter') ?>" class="btn btn-sm btn-success">
                                <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                Exporter CSV
                            </a>
                            <button class="btn btn-sm btn-danger" id="btnSupprimerToutes">
                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                Tout supprimer
                            </button>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📊 Total notifications</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Lues</span>
                                        <strong><?= number_format($stats->lues ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏳ Non lues</span>
                                        <strong><?= number_format($stats->non_lues ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>👥 Utilisateurs concernés</span>
                                        <strong><?= number_format($stats->utilisateurs_concernes ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <select name="categorie" class="form-select">
                                    <option value="">Toutes les catégories</option>
                                    <option value="commande" <?= ($filters['categorie'] ?? '') == 'commande' ? 'selected' : '' ?>>Commande</option>
                                    <option value="paiement" <?= ($filters['categorie'] ?? '') == 'paiement' ? 'selected' : '' ?>>Paiement</option>
                                    <option value="livraison" <?= ($filters['categorie'] ?? '') == 'livraison' ? 'selected' : '' ?>>Livraison</option>
                                    <option value="securite" <?= ($filters['categorie'] ?? '') == 'securite' ? 'selected' : '' ?>>Sécurité</option>
                                    <option value="systeme" <?= ($filters['categorie'] ?? '') == 'systeme' ? 'selected' : '' ?>>Système</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="est_lue" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['est_lue'] ?? '') === '1' ? 'selected' : '' ?>>Lues</option>
                                    <option value="0" <?= ($filters['est_lue'] ?? '') === '0' ? 'selected' : '' ?>>Non lues</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= $filters['search'] ?? '' ?>">
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
                                        <th width="50">ID</th>
                                        <th>Utilisateur</th>
                                        <th width="100">Catégorie</th>
                                        <th>Titre</th>
                                        <th>Message</th>
                                        <th width="100">Statut</th>
                                        <th width="120">Date</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($notifications)): ?>
                                        <?php foreach ($notifications as $n): ?>
                                            <tr>
                                                <td>#<?= $n->id_notification ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($n->utilisateur_nom) ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= htmlspecialchars($n->email) ?></small>
                                                 </div>
                                                <td>
                                                    <?php
                                                    $cat_icon = '';
                                                    switch($n->categorie) {
                                                        case 'commande': $cat_icon = '📦'; break;
                                                        case 'paiement': $cat_icon = '💰'; break;
                                                        case 'livraison': $cat_icon = '🚚'; break;
                                                        case 'securite': $cat_icon = '🔒'; break;
                                                        case 'systeme': $cat_icon = '⚙️'; break;
                                                        default: $cat_icon = '📢';
                                                    }
                                                    ?>
                                                    <span class="badge bg-secondary"><?= $cat_icon ?> <?= ucfirst($n->categorie) ?></span>
                                                 </div>
                                                <td>
                                                    <strong><?= htmlspecialchars($n->titre) ?></strong>
                                                 </div>
                                                <td>
                                                    <small class="text-muted"><?= htmlspecialchars(substr($n->message, 0, 80)) ?>...</small>
                                                 </div>
                                                <td>
                                                    <?php if ($n->est_lue): ?>
                                                        <span class="badge bg-success">✅ Lue</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">⏳ Non lue</span>
                                                    <?php endif; ?>
                                                 </div>
                                                <td><?= date('d/m/Y H:i', strtotime($n->date_creation)) ?> </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('notifications/detail/' . $n->id_notification) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <?php if (!$n->est_lue): ?>
                                                            <button class="btn btn-sm btn-success marquer-lue" data-id="<?= $n->id_notification ?>" title="Marquer comme lue">
                                                                <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button class="btn btn-sm btn-danger delete-notif" data-id="<?= $n->id_notification ?>" title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <iconify-icon icon="solar:bell-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucune notification trouvée</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($notifications)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Marquer comme lue
$('.marquer-lue').on('click', function() {
    const id = $(this).data('id');
    
    $.ajax({
        url: '<?= base_url("notifications/marquer_lue/") ?>' + id,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire('Succès', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Erreur', response.message, 'error');
            }
        }
    });
});

// Supprimer une notification
$('.delete-notif').on('click', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Supprimer cette notification ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("notifications/delete/") ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Supprimé', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur', response.message, 'error');
                    }
                }
            });
        }
    });
});

// Supprimer toutes les notifications
$('#btnSupprimerToutes').on('click', function() {
    Swal.fire({
        title: 'Confirmation',
        text: 'Supprimer TOUTES les notifications ? Cette action est irréversible !',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, tout supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("notifications/supprimer_toutes") ?>',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Succès', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur', response.message, 'error');
                    }
                }
            });
        }
    });
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>