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
                            Détails du paiement - <?= htmlspecialchars($paiement->vendeur_nom) ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('paiements-vendeurs') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- En-tête avec statut -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <?php
                                $statut_color = '';
                                $statut_icon = '';
                                switch($paiement->statut) {
                                    case 'en_attente':
                                        $statut_color = 'warning';
                                        $statut_icon = '⏳';
                                        break;
                                    case 'en_cours':
                                        $statut_color = 'info';
                                        $statut_icon = '🔄';
                                        break;
                                    case 'paye':
                                        $statut_color = 'success';
                                        $statut_icon = '✅';
                                        break;
                                    case 'echoue':
                                        $statut_color = 'danger';
                                        $statut_icon = '❌';
                                        break;
                                }
                                ?>
                                <div class="alert alert-<?= $statut_color ?> d-flex align-items-center">
                                    <span class="fs-2 me-3"><?= $statut_icon ?></span>
                                    <div>
                                        <strong>Statut : <?= ucfirst(str_replace('_', ' ', $paiement->statut)) ?></strong>
                                        <?php if ($paiement->statut == 'paye' && $paiement->date_paiement): ?>
                                            <br>Payé le <?= date('d/m/Y à H:i', strtotime($paiement->date_paiement)) ?>
                                            <?php if ($paiement->reference_transaction): ?>
                                                - Réf: <code><?= htmlspecialchars($paiement->reference_transaction) ?></code>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations principales -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">📋 Informations générales</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="150"><strong>Vendeur :</strong></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($paiement->vendeur_nom) ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($paiement->vendeur_email ?? '-') ?></small>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Période :</strong></td>
                                                <td>
                                                    Du <?= date('d/m/Y', strtotime($paiement->date_debut_periode)) ?><br>
                                                    Au <?= date('d/m/Y', strtotime($paiement->date_fin_periode)) ?>
                                                </div>
                                            </table>
                                            <tr>
                                                <td><strong>Date de création :</strong></td>
                                                <td><?= date('d/m/Y à H:i', strtotime($paiement->date_creation)) ?></div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">💰 Informations financières</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="150"><strong>Nombre commandes :</strong></td>
                                                <td><strong class="fs-4"><?= number_format($paiement->nombre_commandes) ?></strong></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Total revenus :</strong></td>
                                                <td><?= number_format($paiement->total_revenus, 0, ',', ' ') ?> FBu</div>
                                            </tr>
                                            <tr>
                                                <td><strong>Total commissions :</strong></td>
                                                <td><?= number_format($paiement->total_commissions, 0, ',', ' ') ?> FBu</div>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-success">Montant net à payer :</strong></td>
                                                <td><strong class="text-success fs-3"><?= number_format($paiement->montant_net, 0, ',', ' ') ?> FBu</strong></div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <?php if ($paiement->statut == 'en_attente' || $paiement->statut == 'en_cours'): ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-dark text-white">
                                        <h6 class="mb-0">⚙️ Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success payer-btn" data-id="<?= $paiement->id_paiement ?>" data-montant="<?= number_format($paiement->montant_net, 0, ',', ' ') ?>" data-vendeur="<?= htmlspecialchars($paiement->vendeur_nom) ?>">
                                                <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon> Marquer comme payé
                                            </button>
                                            <button class="btn btn-info en-cours-btn" data-id="<?= $paiement->id_paiement ?>">
                                                <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon> Marquer comme en cours
                                            </button>
                                            <button class="btn btn-danger echoue-btn" data-id="<?= $paiement->id_paiement ?>">
                                                <iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon> Marquer comme échoué
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Liste des commandes -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">📦 Commandes concernées</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>N° Commande</th>
                                                        <th>Date</th>
                                                        <th>Montant</th>
                                                        <th>Commission</th>
                                                        <th>Revenu vendeur</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($commandes)): ?>
                                                        <?php foreach ($commandes as $c): ?>
                                                            <tr>
                                                                <td><strong><?= htmlspecialchars($c->numero_commande) ?></strong></div>
                                                                <td><?= date('d/m/Y', strtotime($c->date_creation)) ?></div>
                                                                <td><?= number_format($c->prix_total, 0, ',', ' ') ?> FBu</div>
                                                                <td><?= number_format($c->montant_commission, 0, ',', ' ') ?> FBu</div>
                                                                <td><strong class="text-success"><?= number_format($c->revenus_vendeur, 0, ',', ' ') ?> FBu</strong></div>
                                                                <td>
                                                                    <span class="badge bg-success">Livrée</span>
                                                                </div>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center py-3">
                                                                <span class="text-muted">Aucune commande trouvée pour cette période</span>
                                                            </div>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                                <tfoot class="bg-light">
                                                    <tr>
                                                        <td colspan="2" class="text-end"><strong>Totaux :</strong></td>
                                                        <td><strong><?= number_format($paiement->total_revenus, 0, ',', ' ') ?> FBu</strong></td>
                                                        <td><strong><?= number_format($paiement->total_commissions, 0, ',', ' ') ?> FBu</strong></td>
                                                        <td><strong class="text-success"><?= number_format($paiement->montant_net, 0, ',', ' ') ?> FBu</strong></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Paiement -->
<div class="modal fade" id="paiementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:wallet-bold-duotone"></iconify-icon>
                    Confirmer le paiement
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
                        <label class="form-label">Référence transaction <span class="text-danger">*</span></label>
                        <input type="text" name="reference_transaction" id="reference_transaction" class="form-control" required placeholder="Ex: VIREMENT_001, MPESA_123...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Confirmer le paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Payer
    $('.payer-btn').on('click', function() {
        const id = $(this).data('id');
        const vendeur = $(this).data('vendeur');
        const montant = $(this).data('montant');
        
        $('#paiement_id').val(id);
        $('#paiement_vendeur').val(vendeur);
        $('#paiement_montant').val(montant + ' FBu');
        $('#reference_transaction').val('');
        $('#paiementModal').modal('show');
    });
    
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
    
    // Marquer comme en cours
    $('.en-cours-btn').on('click', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmation',
            text: 'Marquer ce paiement comme "en cours" ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("paiements-vendeurs/en_cours/") ?>' + id,
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
    
    // Marquer comme échoué
    $('.echoue-btn').on('click', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Confirmation',
            text: 'Marquer ce paiement comme "échoué" ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("paiements-vendeurs/echoue/") ?>' + id,
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
.bg-light {
    background-color: #f8f9fa !important;
}
.table-sm td, .table-sm th {
    padding: 0.75rem;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>