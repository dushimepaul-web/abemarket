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
                            Gestion des transactions de paiement
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('transactions-paiement/statistiques') ?>" class="btn btn-sm btn-info">
                                <iconify-icon icon="solar:chart-2-bold-duotone"></iconify-icon>
                                Statistiques
                            </a>
                            <a href="<?= base_url('transactions-paiement/exporter') ?>" class="btn btn-sm btn-success">
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
                                        <span>📊 Total transactions</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Confirmées</span>
                                        <strong><?= number_format($stats->confirmees ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏳ En attente</span>
                                        <strong><?= number_format($stats->en_attente ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>💰 Montant total</span>
                                        <strong><?= number_format($stats->total_net ?? 0, 0, ',', ' ') ?> FBu</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <select name="statut" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="initie" <?= ($filters['statut'] ?? '') == 'initie' ? 'selected' : '' ?>>Initié</option>
                                    <option value="en_attente" <?= ($filters['statut'] ?? '') == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="confirme" <?= ($filters['statut'] ?? '') == 'confirme' ? 'selected' : '' ?>>Confirmé</option>
                                    <option value="echoue" <?= ($filters['statut'] ?? '') == 'echoue' ? 'selected' : '' ?>>Échoué</option>
                                    <option value="annule" <?= ($filters['statut'] ?? '') == 'annule' ? 'selected' : '' ?>>Annulé</option>
                                    <option value="rembourse" <?= ($filters['statut'] ?? '') == 'rembourse' ? 'selected' : '' ?>>Remboursé</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="type_transaction" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="paiement" <?= ($filters['type_transaction'] ?? '') == 'paiement' ? 'selected' : '' ?>>Paiement</option>
                                    <option value="remboursement" <?= ($filters['type_transaction'] ?? '') == 'remboursement' ? 'selected' : '' ?>>Remboursement</option>
                                    <option value="virement_vendeur" <?= ($filters['type_transaction'] ?? '') == 'virement_vendeur' ? 'selected' : '' ?>>Virement vendeur</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" placeholder="Réf, client..." value="<?= $filters['search'] ?? '' ?>">
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
                                        <th>Référence</th>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Type</th>
                                        <th>Montant</th>
                                        <th>Frais</th>
                                        <th>Net</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($transactions)): ?>
                                        <?php foreach ($transactions as $t): ?>
                                            <tr>
                                                <td><strong><?= $t->reference_interne ?></strong></div>
                                                <td>
                                                    <?php if ($t->numero_commande): ?>
                                                        <?= $t->numero_commande ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <?= htmlspecialchars($t->client_nom) ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($t->email) ?></small>
                                                </div>
                                                <td>
                                                    <?php
                                                    $type_icon = '';
                                                    switch($t->type_transaction) {
                                                        case 'paiement': $type_icon = '💰'; break;
                                                        case 'remboursement': $type_icon = '↩️'; break;
                                                        case 'virement_vendeur': $type_icon = '🏦'; break;
                                                        default: $type_icon = '💳';
                                                    }
                                                    echo $type_icon . ' ' . ucfirst($t->type_transaction);
                                                    ?>
                                                </div>
                                                <td><strong><?= number_format($t->montant, 0, ',', ' ') ?> FBu</strong></div>
                                                <td><?= number_format($t->frais, 0, ',', ' ') ?> FBu</div>
                                                <td><strong class="text-primary"><?= number_format($t->montant_net, 0, ',', ' ') ?> FBu</strong></div>
                                                <td>
                                                    <?php
                                                    $badge_color = '';
                                                    switch($t->statut) {
                                                        case 'confirme': $badge_color = 'success'; break;
                                                        case 'en_attente': $badge_color = 'warning'; break;
                                                        case 'echoue': $badge_color = 'danger'; break;
                                                        case 'annule': $badge_color = 'secondary'; break;
                                                        case 'rembourse': $badge_color = 'info'; break;
                                                        default: $badge_color = 'secondary';
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $badge_color ?>"><?= ucfirst($t->statut) ?></span>
                                                </div>
                                                <td><?= date('d/m/Y H:i', strtotime($t->date_creation)) ?></div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('transactions-paiement/detail/' . $t->id_transaction) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <?php if ($is_admin && ($t->statut == 'en_attente' || $t->statut == 'initie')): ?>
                                                            <button class="btn btn-sm btn-primary update-statut" data-id="<?= $t->id_transaction ?>" data-statut="confirme" title="Confirmer">
                                                                <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger update-statut" data-id="<?= $t->id_transaction ?>" data-statut="echoue" title="Échouer">
                                                                <iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($is_admin): ?>
                                                            <button class="btn btn-sm btn-danger delete-transaction" data-id="<?= $t->id_transaction ?>" title="Supprimer">
                                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <iconify-icon icon="solar:card-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucune transaction trouvée</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($transactions)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mettre à jour statut -->
<div class="modal fade" id="updateStatutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Mettre à jour le statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStatutForm">
                <div class="modal-body">
                    <input type="hidden" name="id_transaction" id="update_id">
                    <input type="hidden" name="statut" id="update_statut">
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message_statut" class="form-control" rows="3" placeholder="Information supplémentaire..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Référence opérateur</label>
                        <input type="text" name="reference_operateur" class="form-control" placeholder="Référence du paiement">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentId = null;
let currentStatut = null;

$('.update-statut').on('click', function() {
    currentId = $(this).data('id');
    currentStatut = $(this).data('statut');
    $('#update_id').val(currentId);
    $('#update_statut').val(currentStatut);
    $('#updateStatutModal').modal('show');
});

$('#updateStatutForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?= base_url("transactions-paiement/update_statut/") ?>' + currentId,
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

$('.delete-transaction').on('click', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Supprimer cette transaction ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("transactions-paiement/delete/") ?>' + id,
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