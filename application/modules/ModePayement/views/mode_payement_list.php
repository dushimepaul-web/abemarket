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
                            <iconify-icon icon="solar:card-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des modes de paiement
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('mode-payement/add') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Ajouter un mode de paiement
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>💰 Total</span>
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
                                <div class="alert alert-secondary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⛔ Inactifs</span>
                                        <strong><?= number_format($stats->inactifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📱 Mobile Money</span>
                                        <strong><?= number_format($stats->mobile_money ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="est_actif" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['est_actif'] ?? '') === '1' ? 'selected' : '' ?>>Actifs</option>
                                    <option value="0" <?= ($filters['est_actif'] ?? '') === '0' ? 'selected' : '' ?>>Inactifs</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="type" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="mobile_money" <?= ($filters['type'] ?? '') === 'mobile_money' ? 'selected' : '' ?>>Mobile Money</option>
                                    <option value="carte_bancaire" <?= ($filters['type'] ?? '') === 'carte_bancaire' ? 'selected' : '' ?>>Carte bancaire</option>
                                    <option value="virement" <?= ($filters['type'] ?? '') === 'virement' ? 'selected' : '' ?>>Virement</option>
                                    <option value="especes_livraison" <?= ($filters['type'] ?? '') === 'especes_livraison' ? 'selected' : '' ?>>Espèces à la livraison</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
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
                                        <th>ID</th>
                                        <th>Logo</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Frais</th>
                                        <th>Ordre</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($modes)): ?>
                                        <?php foreach ($modes as $m): ?>
                                            <tr>
                                                <td>#<?= $m->id_mode_payement ?></td>
                                                <td>
                                                    <?php if ($m->logo_url && file_exists(FCPATH . $m->logo_url)): ?>
                                                        <img src="<?= base_url($m->logo_url) ?>" alt="<?= htmlspecialchars($m->code) ?>" style="width: 40px; height: 40px; object-fit: contain;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <iconify-icon icon="solar:card-bold-duotone" class="fs-24 text-muted"></iconify-icon>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <td><strong class="text-primary"><?= htmlspecialchars($m->code) ?></strong></div>
                                                <td><?= htmlspecialchars($m->description) ?></div>
                                                <td>
                                                    <?php
                                                    $type_label = '';
                                                    $type_icon = '';
                                                    switch($m->type) {
                                                        case 'mobile_money':
                                                            $type_label = 'Mobile Money';
                                                            $type_icon = '📱';
                                                            break;
                                                        case 'carte_bancaire':
                                                            $type_label = 'Carte bancaire';
                                                            $type_icon = '💳';
                                                            break;
                                                        case 'virement':
                                                            $type_label = 'Virement';
                                                            $type_icon = '🏦';
                                                            break;
                                                        case 'especes_livraison':
                                                            $type_label = 'Espèces à la livraison';
                                                            $type_icon = '💰';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge bg-info"><?= $type_icon ?> <?= $type_label ?></span>
                                                </div>
                                                <td>
                                                    <?php if ($m->frais_fixe > 0): ?>
                                                        <?= number_format($m->frais_fixe, 0, ',', ' ') ?> FBu fixe<br>
                                                    <?php endif; ?>
                                                    <?php if ($m->frais_pourcentage > 0): ?>
                                                        + <?= $m->frais_pourcentage ?>%
                                                    <?php endif; ?>
                                                    <?php if ($m->frais_fixe == 0 && $m->frais_pourcentage == 0): ?>
                                                        <span class="text-muted">Gratuit</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td><?= $m->ordre_affichage ?></div>
                                                <td>
                                                    <span class="badge bg-<?= $m->est_actif ? 'success' : 'secondary' ?>">
                                                        <?= $m->est_actif ? 'Actif' : 'Inactif' ?>
                                                    </span>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('mode-payement/detail/' . $m->id_mode_payement) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <a href="<?= base_url('mode-payement/edit/' . $m->id_mode_payement) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken"></iconify-icon>
                                                        </a>
                                                        <button class="btn btn-sm btn-warning toggle-status" 
                                                                data-id="<?= $m->id_mode_payement ?>" 
                                                                data-status="<?= $m->est_actif ?>"
                                                                data-code="<?= htmlspecialchars($m->code) ?>">
                                                            <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-mode" 
                                                                data-id="<?= $m->id_mode_payement ?>" 
                                                                data-code="<?= htmlspecialchars($m->code) ?>">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:card-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun mode de paiement trouvé</p>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($modes)): ?>
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
    // Changer le statut
    $('.toggle-status').on('click', function() {
        const id = $(this).data('id');
        const code = $(this).data('code');
        const statusActuel = $(this).data('status');
        const action = statusActuel == 1 ? 'désactiver' : 'activer';
        
        Swal.fire({
            title: 'Confirmation',
            text: `Voulez-vous ${action} le mode de paiement "${code}" ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("mode-payement/toggle_status/") ?>' + id,
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
    $('.delete-mode').on('click', function() {
        const id = $(this).data('id');
        const code = $(this).data('code');
        
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: `Supprimer le mode de paiement "${code}" ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("mode-payement/delete/") ?>' + id,
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
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>