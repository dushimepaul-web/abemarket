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
                            <iconify-icon icon="solar:ticket-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des coupons promotionnels
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('coupons/add') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Nouveau coupon
                            </a>
                            <a href="<?= base_url('coupons/export') ?>" class="btn btn-sm btn-success">
                                <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                Exporter
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>📊 Total coupons</span>
                                        <strong class="fs-4"><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>✅ Coupons actifs</span>
                                        <strong class="fs-4"><?= number_format($stats->actifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-secondary mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>⛔ Coupons inactifs</span>
                                        <strong class="fs-4"><?= number_format($stats->inactifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>🔄 Total utilisations</span>
                                        <strong class="fs-4"><?= number_format($stats->total_utilisations ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Statut</label>
                                <select name="est_actif" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['est_actif'] ?? '') === '1' ? 'selected' : '' ?>>Actifs</option>
                                    <option value="0" <?= ($filters['est_actif'] ?? '') === '0' ? 'selected' : '' ?>>Inactifs</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Type de réduction</label>
                                <select name="type_reduction" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="pourcentage" <?= ($filters['type_reduction'] ?? '') === 'pourcentage' ? 'selected' : '' ?>>Pourcentage (%)</option>
                                    <option value="montant_fixe" <?= ($filters['type_reduction'] ?? '') === 'montant_fixe' ? 'selected' : '' ?>>Montant fixe (FBu)</option>
                                    <option value="livraison_gratuite" <?= ($filters['type_reduction'] ?? '') === 'livraison_gratuite' ? 'selected' : '' ?>>Livraison gratuite</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Rechercher</label>
                                <input type="text" name="search" class="form-control" placeholder="Code ou description..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <iconify-icon icon="solar:filter-bold-duotone"></iconify-icon> Filtrer
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50">ID</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Réduction</th>
                                        <th>Min. achat</th>
                                        <th>Utilisations</th>
                                        <th>Validité</th>
                                        <th width="80">Statut</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($coupons)): ?>
                                        <?php foreach ($coupons as $c): 
                                            $est_valide = ($c->est_actif == 1 && (!$c->date_fin || strtotime($c->date_fin) > time()));
                                            $est_expire = ($c->est_actif == 1 && $c->date_fin && strtotime($c->date_fin) < time());
                                            $est_a_venir = ($c->date_debut && strtotime($c->date_debut) > time());
                                        ?>
                                            <tr>
                                                <td>#<?= $c->id_coupon ?></td>
                                                <td>
                                                    <span class="fw-bold text-primary"><?= htmlspecialchars($c->code) ?></span>
                                                    <button class="btn btn-sm btn-link text-secondary p-0 ms-1 copy-code" data-code="<?= $c->code ?>" title="Copier">
                                                        <iconify-icon icon="solar:copy-bold-duotone" width="14"></iconify-icon>
                                                    </button>
                                                </div>
                                                <td>
                                                    <?= htmlspecialchars($c->description ?? '<span class="text-muted">-</span>') ?>
                                                </div>
                                                <td>
                                                    <?php
                                                    $type_badge = '';
                                                    $type_icon = '';
                                                    switch($c->type_reduction) {
                                                        case 'pourcentage':
                                                            $type_badge = 'info';
                                                            $type_icon = '💰';
                                                            $valeur_affichee = $c->valeur_reduction . '%';
                                                            break;
                                                        case 'montant_fixe':
                                                            $type_badge = 'success';
                                                            $type_icon = '💵';
                                                            $valeur_affichee = number_format($c->valeur_reduction, 0, ',', ' ') . ' FBu';
                                                            break;
                                                        case 'livraison_gratuite':
                                                            $type_badge = 'warning';
                                                            $type_icon = '🚚';
                                                            $valeur_affichee = 'Livraison offerte';
                                                            break;
                                                        default:
                                                            $type_badge = 'secondary';
                                                            $type_icon = '🏷️';
                                                            $valeur_affichee = '-';
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $type_badge ?>">
                                                        <?= $type_icon ?> <?= $valeur_affichee ?>
                                                    </span>
                                                    <?php if ($c->type_reduction == 'pourcentage' && $c->montant_max_reduction): ?>
                                                        <br><small class="text-muted">Max: <?= number_format($c->montant_max_reduction, 0, ',', ' ') ?> FBu</small>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <?php if ($c->montant_min_achat): ?>
                                                        <?= number_format($c->montant_min_achat, 0, ',', ' ') ?> FBu
                                                    <?php else: ?>
                                                        <span class="text-muted">Aucun</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <strong class="text-primary"><?= number_format($c->nombre_utilisations) ?></strong>
                                                        <?php if ($c->limite_utilisation): ?>
                                                            <small class="text-muted">/ <?= number_format($c->limite_utilisation) ?> max</small>
                                                            <div class="progress mt-1" style="height: 3px; width: 80px;">
                                                                <div class="progress-bar bg-primary" style="width: <?= ($c->nombre_utilisations / $c->limite_utilisation) * 100 ?>%"></div>
                                                            </div>
                                                        <?php else: ?>
                                                            <small class="text-muted">Illimité</small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <small class="text-nowrap">📅 Du: <?= date('d/m/Y', strtotime($c->date_debut)) ?></small>
                                                        <small class="text-nowrap">⏱️ Au: <?= date('d/m/Y', strtotime($c->date_fin)) ?></small>
                                                        <?php if ($est_a_venir && $c->est_actif): ?>
                                                            <span class="badge bg-info mt-1">À venir</span>
                                                        <?php elseif ($est_expire): ?>
                                                            <span class="badge bg-danger mt-1">Expiré</span>
                                                        <?php elseif ($est_valide && $c->est_actif): ?>
                                                            <span class="badge bg-success mt-1">Valide</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <td>
                                                    <?php if ($c->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactif</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <a href="<?= base_url('coupons/detail/' . $c->id_coupon) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('coupons/edit/' . $c->id_coupon) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-warning toggle-status" 
                                                                data-id="<?= $c->id_coupon ?>" 
                                                                data-status="<?= $c->est_actif ?>"
                                                                data-code="<?= htmlspecialchars($c->code) ?>"
                                                                title="<?= $c->est_actif ? 'Désactiver' : 'Activer' ?>">
                                                            <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger delete-coupon" 
                                                                data-id="<?= $c->id_coupon ?>" 
                                                                data-code="<?= htmlspecialchars($c->code) ?>"
                                                                title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:ticket-broken" class="fs-48 text-muted"></iconify-icon>
                                                <h5 class="mt-3">Aucun coupon trouvé</h5>
                                                <p class="text-muted">Cliquez sur "Nouveau coupon" pour commencer</p>
                                                <a href="<?= base_url('coupons/add') ?>" class="btn btn-sm btn-primary mt-2">
                                                    <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Créer un coupon
                                                </a>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            <table>
                        </div>
                    </div>
                    
                    <?php if (!empty($coupons) && isset($this->pagination)): ?>
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
$(document).ready(function() {
    // Copier le code
    $('.copy-code').on('click', function(e) {
        e.preventDefault();
        const code = $(this).data('code');
        navigator.clipboard.writeText(code).then(function() {
            Swal.fire({
                title: 'Copié !',
                text: 'Code ' + code + ' copié dans le presse-papier',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        });
    });

    // Changer le statut
    $('.toggle-status').on('click', function() {
        const id = $(this).data('id');
        const code = $(this).data('code');
        const statusActuel = $(this).data('status');
        const action = statusActuel == 1 ? 'désactiver' : 'activer';
        
        Swal.fire({
            title: 'Confirmation',
            text: `Voulez-vous ${action} le coupon "${code}" ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("coupons/toggle_status/") ?>' + id,
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
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Erreur de communication', 'error');
                    }
                });
            }
        });
    });

    // Supprimer
    $('.delete-coupon').on('click', function() {
        const id = $(this).data('id');
        const code = $(this).data('code');
        
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: `Vous allez supprimer le coupon "${code}". Cette action est irréversible !`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("coupons/delete/") ?>' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Supprimé!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Erreur!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Erreur', 'Erreur de communication', 'error');
                    }
                });
            }
        });
    });
});
</script>

<style>
.progress {
    background-color: #e9ecef;
    border-radius: 10px;
}
.progress-bar {
    border-radius: 10px;
}
.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,0.02);
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>