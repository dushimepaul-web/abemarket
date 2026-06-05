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
                            <iconify-icon icon="solar:login-bold-duotone" class="me-2"></iconify-icon>
                            Historique des tentatives de connexion
                        </h4>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-warning" id="btnNettoyerBlacklist">
                                <iconify-icon icon="solar:brush-bold-duotone"></iconify-icon>
                                Nettoyer IPs expirées
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" id="btnVider">
                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                Vider l'historique
                            </button>
                            <a href="<?= base_url('tentatives-connexion/exporter') ?>" class="btn btn-sm btn-success">
                                <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                Exporter CSV
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" id="btnSupprimerSelection">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                Supprimer sélection
                            </button>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Total tentatives</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Connexions réussies</span>
                                        <strong><?= number_format($stats->succes ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-danger mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Échecs</span>
                                        <strong><?= number_format($stats->echec ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Aujourd'hui</span>
                                        <strong><?= number_format($stats->aujourdhui ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top IPs les plus actives -->
                    <div class="card-body border-bottom">
                        <h6 class="mb-3">🔍 IPs les plus actives</h6>
                        <div class="row">
                            <?php foreach ($top_ips as $ip): ?>
                            <div class="col-md-2 mb-2">
                                <div class="border rounded p-2 <?= $ip->echec > 0 ? 'bg-danger bg-opacity-10' : '' ?>">
                                    <code><?= $ip->adresse_ip ?></code>
                                    <div class="small mt-1">
                                        <span class="text-success">✓ <?= $ip->succes ?></span> / 
                                        <span class="text-danger">✗ <?= $ip->echec ?></span>
                                        <span class="badge bg-secondary"><?= $ip->nombre ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- IPs Blacklistées -->
                    <div class="card-body border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">🚫 IPs blacklistées</h6>
                            <button class="btn btn-sm btn-outline-danger" id="btnAjouterBlacklist">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Ajouter IP
                            </button>
                        </div>
                        <div class="row">
                            <?php if (!empty($ips_blacklist)): ?>
                                <?php foreach ($ips_blacklist as $ip): ?>
                                <div class="col-md-2 mb-2">
                                    <div class="border rounded p-2 bg-light">
                                        <code><?= $ip->adresse_ip ?></code>
                                        <small class="d-block text-muted"><?= htmlspecialchars(substr($ip->raison, 0, 30)) ?></small>
                                        <?php if ($ip->date_fin): ?>
                                            <?php if (strtotime($ip->date_fin) > time()): ?>
                                                <small class="text-warning">Expire: <?= date('d/m/Y', strtotime($ip->date_fin)) ?></small>
                                            <?php else: ?>
                                                <small class="text-danger">Expirée</small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <small class="text-danger">Permanent</small>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-link text-danger p-0 retirer-blacklist" data-ip="<?= $ip->adresse_ip ?>">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <p class="text-muted">Aucune IP blacklistée</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <select name="reussie" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['reussie'] ?? '') === '1' ? 'selected' : '' ?>>Succès</option>
                                    <option value="0" <?= ($filters['reussie'] ?? '') === '0' ? 'selected' : '' ?>>Échec</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="ip" class="form-control" placeholder="Adresse IP" value="<?= $filters['ip'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" placeholder="Email, IP..." value="<?= $filters['search'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Liste des tentatives -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>ID</th>
                                        <th>Utilisateur</th>
                                        <th>Email tenté</th>
                                        <th>IP</th>
                                        <th>Statut</th>
                                        <th>Motif</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($tentatives)): ?>
                                        <?php foreach ($tentatives as $t): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="tentative-checkbox" value="<?= $t->id_tentative ?>">
                                                </div>
                                                <td>#<?= $t->id_tentative ?></td>
                                                <td>
                                                    <?php if ($t->id_utilisateur): ?>
                                                        <strong><?= htmlspecialchars($t->prenom . ' ' . $t->nom) ?></strong>
                                                        <br><small class="text-muted"><?= htmlspecialchars($t->utilisateur_email) ?></small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Visiteur</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <?php if ($t->email_tente): ?>
                                                        <code><?= htmlspecialchars($t->email_tente) ?></code>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td><code><?= $t->adresse_ip ?></code></div>
                                                <td>
                                                    <?php if ($t->reussie): ?>
                                                        <span class="badge bg-success">✅ Succès</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">❌ Échec</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td><?= htmlspecialchars($t->motif_echec ?? '-') ?></div>
                                                <td><?= date('d/m/Y H:i:s', strtotime($t->date_tentative)) ?></div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-dark blacklister-ip" 
                                                                data-ip="<?= $t->adresse_ip ?>" 
                                                                data-email="<?= htmlspecialchars($t->email_tente) ?>"
                                                                title="Blacklister cette IP">
                                                            <iconify-icon icon="solar:shield-warning-bold-duotone"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete-tentative" 
                                                                data-id="<?= $t->id_tentative ?>" 
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
                                                <iconify-icon icon="solar:login-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucune tentative de connexion trouvée</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($tentatives)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Blacklister IP -->
<div class="modal fade" id="blacklistModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:shield-warning-bold-duotone"></iconify-icon>
                    Blacklister une adresse IP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="blacklistForm">
                <div class="modal-body">
                    <input type="hidden" name="ip" id="blacklist_ip">
                    <div class="mb-3">
                        <label class="form-label">Adresse IP</label>
                        <input type="text" id="blacklist_ip_display" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durée</label>
                        <select name="duree" class="form-select">
                            <option value="permanent">Permanent</option>
                            <option value="1day">1 jour</option>
                            <option value="1week">1 semaine</option>
                            <option value="1month">1 mois</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Raison</label>
                        <textarea name="raison" class="form-control" rows="3" placeholder="Motif du blacklistage..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Blacklister</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Sélectionner tout
    $('#selectAll').on('change', function() {
        $('.tentative-checkbox').prop('checked', this.checked);
    });

    // Blacklister IP
    $('.blacklister-ip').on('click', function() {
        const ip = $(this).data('ip');
        const email = $(this).data('email');
        
        $('#blacklist_ip').val(ip);
        $('#blacklist_ip_display').val(ip + (email ? ' (' + email + ')' : ''));
        $('#blacklistModal').modal('show');
    });

    $('#blacklistForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url("tentatives-connexion/blacklister_ip") ?>',
            type: 'POST',
            data: $(this).serialize(),
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

    // Retirer de la blacklist
    $('.retirer-blacklist').on('click', function() {
        const ip = $(this).data('ip');
        
        Swal.fire({
            title: 'Confirmation',
            text: `Retirer l'IP ${ip} de la blacklist ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("tentatives-connexion/retirer_blacklist") ?>',
                    type: 'POST',
                    data: {ip: ip},
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

    // Supprimer une tentative
    $('.delete-tentative').on('click', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmation',
            text: 'Supprimer cette tentative ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("tentatives-connexion/delete/") ?>' + id,
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

    // Supprimer la sélection
    $('#btnSupprimerSelection').on('click', function() {
        const ids = [];
        $('.tentative-checkbox:checked').each(function() {
            ids.push($(this).val());
        });
        
        if (ids.length === 0) {
            Swal.fire('Aucune sélection', 'Veuillez sélectionner des tentatives', 'warning');
            return;
        }
        
        Swal.fire({
            title: 'Confirmation',
            text: `Supprimer ${ids.length} tentative(s) ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("tentatives-connexion/delete_multiple") ?>',
                    type: 'POST',
                    data: {ids: ids},
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

    // Ajouter IP à la blacklist (bouton en haut)
    $('#btnAjouterBlacklist').on('click', function() {
        $('#blacklist_ip').val('');
        $('#blacklist_ip_display').val('');
        $('#blacklistModal').modal('show');
    });

    // Nettoyer les IPs expirées
    $('#btnNettoyerBlacklist').on('click', function() {
        Swal.fire({
            title: 'Confirmation',
            text: 'Supprimer toutes les IPs expirées de la blacklist ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("tentatives-connexion/nettoyer_expirees") ?>',
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

    // Vider l'historique
    $('#btnVider').on('click', function() {
        Swal.fire({
            title: 'Vider l\'historique',
            text: 'Supprimer toutes les tentatives ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui, tout supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("tentatives-connexion/vider") ?>',
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
});
</script>

<style>
.border-danger {
    border-color: #dc3545 !important;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>