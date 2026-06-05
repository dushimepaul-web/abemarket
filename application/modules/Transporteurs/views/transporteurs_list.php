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
                            <iconify-icon icon="solar:truck-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des transporteurs
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('transporteurs/add') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Ajouter un transporteur
                            </a>
                            <a href="<?= base_url('transporteurs/exporter') ?>" class="btn btn-sm btn-success">
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
                                        <span>🚚 Total transporteurs</span>
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
                                        <span>🟢 Disponibles</span>
                                        <strong><?= number_format($stats->disponibles ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⭐ Note moyenne</span>
                                        <strong><?= number_format($stats->note_moyenne ?? 0, 1) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="statut" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="actif" <?= ($filters['statut'] ?? '') == 'actif' ? 'selected' : '' ?>>Actif</option>
                                    <option value="inactif" <?= ($filters['statut'] ?? '') == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                                    <option value="suspendu" <?= ($filters['statut'] ?? '') == 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="type_vehicule" class="form-select">
                                    <option value="">Tous les véhicules</option>
                                    <option value="moto" <?= ($filters['type_vehicule'] ?? '') == 'moto' ? 'selected' : '' ?>>Moto</option>
                                    <option value="voiture" <?= ($filters['type_vehicule'] ?? '') == 'voiture' ? 'selected' : '' ?>>Voiture</option>
                                    <option value="camionnette" <?= ($filters['type_vehicule'] ?? '') == 'camionnette' ? 'selected' : '' ?>>Camionnette</option>
                                    <option value="velo" <?= ($filters['type_vehicule'] ?? '') == 'velo' ? 'selected' : '' ?>>Vélo</option>
                                    <option value="pied" <?= ($filters['type_vehicule'] ?? '') == 'pied' ? 'selected' : '' ?>>À pied</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="est_disponible" class="form-select">
                                    <option value="">Disponibilité</option>
                                    <option value="1" <?= ($filters['est_disponible'] ?? '') === '1' ? 'selected' : '' ?>>Disponible</option>
                                    <option value="0" <?= ($filters['est_disponible'] ?? '') === '0' ? 'selected' : '' ?>>Non disponible</option>
                                </select>
                            </div>
                            <div class="col-md-4">
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
                                        <th>Photo</th>
                                        <th>Nom</th>
                                        <th>Contact</th>
                                        <th>Véhicule</th>
                                        <th>Statut</th>
                                        <th>Dispo</th>
                                        <th>Livraisons</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transporteurs)): ?>
                                        <?php foreach ($transporteurs as $t): ?>
                                            <tr>
                                                <td>#<?= $t->id_transporteur ?></td>
                                                <td>
                                                    <?php if ($t->photo_url && file_exists(FCPATH . $t->photo_url)): ?>
                                                        <img src="<?= base_url($t->photo_url) ?>" alt="<?= htmlspecialchars($t->nom) ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <iconify-icon icon="solar:user-bold-duotone" class="fs-24 text-muted"></iconify-icon>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <strong><?= htmlspecialchars($t->nom) ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($t->type) ?></small>
                                                </div>
                                                <td>
                                                    <iconify-icon icon="solar:phone-bold-duotone"></iconify-icon> <?= htmlspecialchars($t->telephone) ?>
                                                    <?php if ($t->whatsapp): ?>
                                                        <br><iconify-icon icon="solar:whatsapp-bold-duotone" class="text-success"></iconify-icon> <?= htmlspecialchars($t->whatsapp) ?>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <?php
                                                    $vehicule_icons = [
                                                        'moto' => '🛵',
                                                        'voiture' => '🚗',
                                                        'camionnette' => '🚚',
                                                        'velo' => '🚲',
                                                        'pied' => '🚶'
                                                    ];
                                                    $icon = $vehicule_icons[$t->type_vehicule] ?? '🚚';
                                                    ?>
                                                    <?= $icon ?> <?= ucfirst($t->type_vehicule) ?>
                                                    <?php if ($t->plaque): ?>
                                                        <br><small class="text-muted"><?= htmlspecialchars($t->plaque) ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <span class="badge bg-<?= $t->statut == 'actif' ? 'success' : ($t->statut == 'suspendu' ? 'danger' : 'secondary') ?>">
                                                        <?= ucfirst($t->statut) ?>
                                                    </span>
                                                </div>
                                                <td>
                                                    <?php if ($t->est_disponible): ?>
                                                        <span class="badge bg-success">🟢 Disponible</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">🔴 Occupé</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <strong><?= $t->livraisons_totales ?></strong>
                                                    <br><small class="text-muted">Réussies: <?= $t->livraisons_reussies ?></small>
                                                </div>
                                                <td>
                                                    <div class="text-warning">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <iconify-icon icon="solar:star-<?= $i <= round($t->note_moyenne) ? 'bold' : 'linear' ?>-duotone" class="fs-14"></iconify-icon>
                                                        <?php endfor; ?>
                                                        <span class="text-muted">(<?= number_format($t->note_moyenne, 1) ?>)</span>
                                                    </div>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('transporteurs/detail/' . $t->id_transporteur) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('transporteurs/edit/' . $t->id_transporteur) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken"></iconify-icon>
                                                        </a>
                                                        <?php if ($t->est_disponible): ?>
                                                            <button class="btn btn-sm btn-warning toggle-dispo" data-id="<?= $t->id_transporteur ?>" title="Marquer occupé">
                                                                <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                                                            </button>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-success toggle-dispo" data-id="<?= $t->id_transporteur ?>" title="Marquer disponible">
                                                                <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button class="btn btn-sm btn-danger delete-transporteur" data-id="<?= $t->id_transporteur ?>" data-nom="<?= htmlspecialchars($t->nom) ?>" title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <iconify-icon icon="solar:truck-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun transporteur trouvé</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($transporteurs)): ?>
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
// Changer disponibilité
$('.toggle-dispo').on('click', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Changer la disponibilité de ce transporteur ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("transporteurs/toggle_disponible/") ?>' + id,
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
$('.delete-transporteur').on('click', function() {
    const id = $(this).data('id');
    const nom = $(this).data('nom');
    
    Swal.fire({
        title: 'Confirmation',
        text: `Supprimer le transporteur "${nom}" ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("transporteurs/delete/") ?>' + id,
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