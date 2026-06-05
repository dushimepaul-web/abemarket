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
                            <iconify-icon icon="solar:wallet-bold-duotone" class="me-2"></iconify-icon>
                            Paiements vendeurs
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('paiements-vendeurs/generer') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Générer paiements
                            </a>
                            <a href="<?= base_url('paiements-vendeurs/exporter') ?>" class="btn btn-sm btn-success">
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
                                    <div class="d-flex justify-content-between">
                                        <span>📊 Total paiements</span>
                                        <strong><?= number_format($stats->total_paiements ?? 0) ?></strong>
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
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Payés</span>
                                        <strong><?= number_format($stats->payes ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>💰 Total à payer</span>
                                        <strong><?= number_format($stats->total_a_payer ?? 0, 0, ',', ' ') ?> FBu</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="id_vendeur" class="form-select">
                                    <option value="">Tous les vendeurs</option>
                                    <?php foreach ($vendeurs as $v): ?>
                                        <option value="<?= $v->id_vendeur ?>" <?= ($filters['id_vendeur'] ?? '') == $v->id_vendeur ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($v->nom_boutique) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="statut" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_attente" <?= ($filters['statut'] ?? '') == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="en_cours" <?= ($filters['statut'] ?? '') == 'en_cours' ? 'selected' : '' ?>>En cours</option>
                                    <option value="paye" <?= ($filters['statut'] ?? '') == 'paye' ? 'selected' : '' ?>>Payé</option>
                                    <option value="echoue" <?= ($filters['statut'] ?? '') == 'echoue' ? 'selected' : '' ?>>Échoué</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>" placeholder="Début">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>" placeholder="Fin">
                            </div>
                            <div class="col-md-3">
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
                                        <th>Vendeur</th>
                                        <th>Période</th>
                                        <th>Commandes</th>
                                        <th>Revenus</th>
                                        <th>Commissions</th>
                                        <th>Montant net</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($paiements)): ?>
                                        <?php foreach ($paiements as $p): ?>
                                            <tr>
                                                <td>#<?= $p->id_paiement ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($p->vendeur_nom) ?></strong>
                                                </div>
                                                <td>
                                                    <?= date('d/m/Y', strtotime($p->date_debut_periode)) ?><br>
                                                    <small class="text-muted">au <?= date('d/m/Y', strtotime($p->date_fin_periode)) ?></small>
                                                </div>
                                                <td><?= number_format($p->nombre_commandes) ?></div>
                                                <td><?= number_format($p->total_revenus, 0, ',', ' ') ?> FBu</div>
                                                <td><?= number_format($p->total_commissions, 0, ',', ' ') ?> FBu</div>
                                                <td>
                                                    <strong class="text-success"><?= number_format($p->montant_net, 0, ',', ' ') ?> FBu</strong>
                                                </div>
                                                <td>
                                                    <?php
                                                    $badge_class = '';
                                                    switch($p->statut) {
                                                        case 'en_attente': $badge_class = 'warning'; break;
                                                        case 'en_cours': $badge_class = 'info'; break;
                                                        case 'paye': $badge_class = 'success'; break;
                                                        case 'echoue': $badge_class = 'danger'; break;
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $badge_class ?>">
                                                        <?= ucfirst(str_replace('_', ' ', $p->statut)) ?>
                                                    </span>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('paiements-vendeurs/detail/' . $p->id_paiement) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <?php if ($p->statut == 'en_attente'): ?>
                                                            <button class="btn btn-sm btn-primary payer-btn" data-id="<?= $p->id_paiement ?>" data-montant="<?= number_format($p->montant_net, 0, ',', ' ') ?>" data-vendeur="<?= htmlspecialchars($p->vendeur_nom) ?>">
                                                                <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon> Payer
                                                            </button>
                                                        <?php endif; ?>
                                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $p->id_paiement ?>" data-vendeur="<?= htmlspecialchars($p->vendeur_nom) ?>">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:wallet-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun paiement trouvé</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($paiements)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Paiement -->
<div class="modal fade" id="paiementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon>
                    Paiement vendeur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paiementForm">
                <div class="modal-body">
                    <input type="hidden" name="id_paiement" id="paiement_id">
                    <div class="mb-3">
                        <label class="form-label">Vendeur</label>
                        <input type="text" id="paiement_vendeur" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant à payer</label>
                        <input type="text" id="paiement_montant" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Référence transaction</label>
                        <input type="text" name="reference_transaction" class="form-control" required placeholder="Ex: VIREMENT_001, MPESA_123...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Ouvrir modal de paiement
    $('.payer-btn').on('click', function() {
        const id = $(this).data('id');
        const vendeur = $(this).data('vendeur');
        const montant = $(this).data('montant');
        
        $('#paiement_id').val(id);
        $('#paiement_vendeur').val(vendeur);
        $('#paiement_montant').val(montant + ' FBu');
        $('#paiementModal').modal('show');
    });
    
    // Soumettre paiement
    $('#paiementForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#paiement_id').val();
        
        $.ajax({
            url: '<?= base_url("paiements-vendeurs/payer/") ?>' + id,
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
    
    // Supprimer
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        const vendeur = $(this).data('vendeur');
        
        Swal.fire({
            title: 'Confirmation',
            text: `Supprimer le paiement pour ${vendeur} ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("paiements-vendeurs/delete/") ?>' + id,
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