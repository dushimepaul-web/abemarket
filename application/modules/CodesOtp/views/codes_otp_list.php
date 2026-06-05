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
                            <iconify-icon icon="solar:key-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des codes OTP
                        </h4>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-warning" id="btnNettoyer">
                                <iconify-icon icon="solar:brush-bold-duotone"></iconify-icon>
                                Nettoyer les expirés
                            </button>
                            <a href="<?= base_url('codes-otp/generate') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Générer un code
                            </a>
                            <a href="<?= base_url('codes-otp/exporter') ?>" class="btn btn-sm btn-success">
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
                                        <span>📊 Total codes</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Utilisés</span>
                                        <strong><?= number_format($stats->utilises ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏳ Actifs</span>
                                        <strong><?= number_format($stats->actifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-danger mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏰ Expirés</span>
                                        <strong><?= number_format($stats->expires ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <select name="type_otp" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="connexion" <?= ($filters['type_otp'] ?? '') == 'connexion' ? 'selected' : '' ?>>Connexion</option>
                                    <option value="verification_telephone" <?= ($filters['type_otp'] ?? '') == 'verification_telephone' ? 'selected' : '' ?>>Vérification téléphone</option>
                                    <option value="verification_email" <?= ($filters['type_otp'] ?? '') == 'verification_email' ? 'selected' : '' ?>>Vérification email</option>
                                    <option value="reinitialisation_mdp" <?= ($filters['type_otp'] ?? '') == 'reinitialisation_mdp' ? 'selected' : '' ?>>Réinitialisation mot de passe</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="utilise" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['utilise'] ?? '') === '1' ? 'selected' : '' ?>>Utilisé</option>
                                    <option value="0" <?= ($filters['utilise'] ?? '') === '0' ? 'selected' : '' ?>>Non utilisé</option>
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
                                        <th>ID</th>
                                        <th>Utilisateur</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Tentatives</th>
                                        <th>Statut</th>
                                        <th>Expiration</th>
                                        <th>Date création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($codes)): ?>
                                        <?php foreach ($codes as $c): 
                                            $est_expire = strtotime($c->date_expiration) < time();
                                            $est_actif = !$c->utilise && !$est_expire;
                                        ?>
                                            <tr>
                                                <td>#<?= $c->id_otp ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($c->utilisateur_nom) ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($c->user_email) ?></small>
                                                </div>
                                                <td>
                                                    <code class="fs-5"><?= $c->code ?></code>
                                                </div>
                                                <td>
                                                    <?php
                                                    $type_label = '';
                                                    switch($c->type_otp) {
                                                        case 'connexion': $type_label = '🔑 Connexion'; break;
                                                        case 'verification_telephone': $type_label = '📱 Vérif. téléphone'; break;
                                                        case 'verification_email': $type_label = '📧 Vérif. email'; break;
                                                        case 'reinitialisation_mdp': $type_label = '🔐 Réinit. MDP'; break;
                                                        default: $type_label = $c->type_otp;
                                                    }
                                                    ?>
                                                    <?= $type_label ?>
                                                </div>
                                                <td>
                                                    <span class="badge bg-<?= $c->tentatives >= 3 ? 'danger' : 'secondary' ?>">
                                                        <?= $c->tentatives ?>/3
                                                    </span>
                                                </div>
                                                <td>
                                                    <?php if ($c->utilise): ?>
                                                        <span class="badge bg-success">✅ Utilisé</span>
                                                    <?php elseif ($est_expire): ?>
                                                        <span class="badge bg-danger">⏰ Expiré</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">🟢 Actif</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <?= date('d/m/Y H:i', strtotime($c->date_expiration)) ?>
                                                    <?php if ($est_actif): ?>
                                                        <br><small class="text-success">Valide</small>
                                                    <?php endif; ?>
                                                </div>
                                                <td><?= date('d/m/Y H:i', strtotime($c->date_creation)) ?></div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('codes-otp/detail/' . $c->id_otp) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger delete-otp" data-id="<?= $c->id_otp ?>" title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:key-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun code OTP trouvé</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($codes)): ?>
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
// Supprimer
$('.delete-otp').on('click', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Supprimer ce code OTP ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("codes-otp/delete/") ?>' + id,
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

// Nettoyer les expirés
$('#btnNettoyer').on('click', function() {
    Swal.fire({
        title: 'Confirmation',
        text: 'Supprimer tous les codes OTP expirés ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("codes-otp/nettoyer") ?>',
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