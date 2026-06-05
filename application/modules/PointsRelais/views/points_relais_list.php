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
                            <iconify-icon icon="solar:map-point-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des points relais
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('points-relais/add') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Ajouter un point relais
                            </a>
                            <a href="<?= base_url('points-relais/exporter') ?>" class="btn btn-sm btn-success">
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
                                        <span>📍 Total points</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Actifs</span>
                                        <strong><?= number_format($stats->actifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>🏪 Boutiques</span>
                                        <strong><?= number_format($stats->boutiques ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📦 Capacité totale</span>
                                        <strong><?= number_format($stats->capacite_totale ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="type" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="boutique_partenaire" <?= ($filters['type'] ?? '') == 'boutique_partenaire' ? 'selected' : '' ?>>🏪 Boutique partenaire</option>
                                    <option value="kiosque" <?= ($filters['type'] ?? '') == 'kiosque' ? 'selected' : '' ?>>📰 Kiosque</option>
                                    <option value="bureau_poste" <?= ($filters['type'] ?? '') == 'bureau_poste' ? 'selected' : '' ?>>🏤 Bureau de poste</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="est_actif" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['est_actif'] ?? '') === '1' ? 'selected' : '' ?>>Actif</option>
                                    <option value="0" <?= ($filters['est_actif'] ?? '') === '0' ? 'selected' : '' ?>>Inactif</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="id_commune" class="form-select" id="filter_commune">
                                    <option value="">Toutes les communes</option>
                                    <?php foreach ($communes as $c): ?>
                                        <option value="<?= $c->id_commune ?>" <?= ($filters['id_commune'] ?? '') == $c->id_commune ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($c->commune_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= $filters['search'] ?? '' ?>">
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Adresse</th>
                                        <th>Commune</th>
                                        <th>Téléphone</th>
                                        <th>Capacité</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($points)): ?>
                                        <?php foreach ($points as $p): ?>
                                            <tr>
                                                <td>#<?= $p->id_point ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($p->nom) ?></strong>
                                                </div>
                                                <td>
                                                    <?php
                                                    $type_icon = '';
                                                    switch($p->type) {
                                                        case 'boutique_partenaire': $type_icon = '🏪 Boutique'; break;
                                                        case 'kiosque': $type_icon = '📰 Kiosque'; break;
                                                        case 'bureau_poste': $type_icon = '🏤 Poste'; break;
                                                        default: $type_icon = '📍';
                                                    }
                                                    echo $type_icon;
                                                    ?>
                                                </div>
                                                <td>
                                                    <small><?= htmlspecialchars(substr($p->adresse, 0, 50)) ?>...</small>
                                                </div>
                                                <td>
                                                    <?php if ($p->commune_name): ?>
                                                        <?= htmlspecialchars($p->commune_name) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <?php if ($p->telephone): ?>
                                                        <a href="tel:<?= $p->telephone ?>"><?= htmlspecialchars($p->telephone) ?></a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td><?= $p->capacite_max ?> </div>
                                                <td>
                                                    <?php if ($p->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactif</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('points-relais/detail/' . $p->id_point) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('points-relais/edit/' . $p->id_point) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken"></iconify-icon>
                                                        </a>
                                                        <button class="btn btn-sm btn-warning toggle-status" data-id="<?= $p->id_point ?>" data-status="<?= $p->est_actif ?>" data-nom="<?= htmlspecialchars($p->nom) ?>" title="<?= $p->est_actif ? 'Désactiver' : 'Activer' ?>">
                                                            <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-point" data-id="<?= $p->id_point ?>" data-nom="<?= htmlspecialchars($p->nom) ?>" title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:map-point-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun point relais trouvé</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($points)): ?>
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
// Changer le statut
$('.toggle-status').on('click', function() {
    const id = $(this).data('id');
    const nom = $(this).data('nom');
    const statusActuel = $(this).data('status');
    const action = statusActuel == 1 ? 'désactiver' : 'activer';
    
    Swal.fire({
        title: 'Confirmation',
        text: `Voulez-vous ${action} le point relais "${nom}" ?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("points-relais/toggle_status/") ?>' + id,
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

// Supprimer
$('.delete-point').on('click', function() {
    const id = $(this).data('id');
    const nom = $(this).data('nom');
    
    Swal.fire({
        title: 'Confirmation',
        text: `Supprimer le point relais "${nom}" ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("points-relais/delete/") ?>' + id,
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
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>