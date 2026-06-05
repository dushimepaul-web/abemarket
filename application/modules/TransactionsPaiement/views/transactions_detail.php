<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:card-bold-duotone" class="me-2"></iconify-icon>
                            Détails de la transaction
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('transactions-paiement') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">📋 Informations transaction</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Référence :</strong></td><td><strong><?= $transaction->reference_interne ?></strong></div></tr>
                                            <tr><td><strong>Type :</strong></div>
                                                <td>
                                                    <?php
                                                    switch($transaction->type_transaction) {
                                                        case 'paiement': echo '💰 Paiement'; break;
                                                        case 'remboursement': echo '↩️ Remboursement'; break;
                                                        case 'virement_vendeur': echo '🏦 Virement vendeur'; break;
                                                    }
                                                    ?>
                                                </div>
                                            </tr>
                                            <tr><td><strong>Statut :</strong></div>
                                                <td>
                                                    <span class="badge bg-<?= 
                                                        $transaction->statut == 'confirme' ? 'success' : 
                                                        ($transaction->statut == 'en_attente' ? 'warning' : 
                                                        ($transaction->statut == 'echoue' ? 'danger' : 'secondary')) ?>">
                                                        <?= ucfirst($transaction->statut) ?>
                                                    </span>
                                                </div>
                                            </tr>
                                            <tr><td><strong>Date création :</strong></div><td><?= date('d/m/Y H:i:s', strtotime($transaction->date_creation)) ?></div></tr>
                                            <?php if ($transaction->date_confirmation): ?>
                                            <tr><td><strong>Date confirmation :</strong></div><td><?= date('d/m/Y H:i:s', strtotime($transaction->date_confirmation)) ?></div></tr>
                                            <?php endif; ?>
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
                                            <tr><td width="150"><strong>Montant :</strong></div><td><?= number_format($transaction->montant, 0, ',', ' ') ?> FBu</div></tr>
                                            <tr><td><strong>Frais :</strong></div><td><?= number_format($transaction->frais, 0, ',', ' ') ?> FBu</div></tr>
                                            <tr class="border-top">
                                                <td><strong class="text-primary">Net :</strong></div>
                                                <td><strong class="text-primary fs-5"><?= number_format($transaction->montant_net, 0, ',', ' ') ?> FBu</strong></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Devise :</strong></div><td><?= $transaction->devise ?? 'BIF' ?></div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">👤 Informations payeur</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Nom :</strong></div><td><?= htmlspecialchars($transaction->prenom . ' ' . $transaction->nom) ?></div></tr>
                                            <tr><td><strong>Email :</strong></div><td><?= htmlspecialchars($transaction->email) ?></div></tr>
                                            <?php if ($transaction->telephone_payeur): ?>
                                            <tr><td><strong>Téléphone :</strong></div><td><?= htmlspecialchars($transaction->telephone_payeur) ?></div></tr>
                                            <?php endif; ?>
                                            <?php if ($transaction->nom_payeur): ?>
                                            <tr><td><strong>Nom payeur :</strong></div><td><?= htmlspecialchars($transaction->nom_payeur) ?></div></tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">📝 Informations opérateur</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Mode :</strong></div><td><?= $transaction->mode_paiement ?? '-' ?></div></tr>
                                            <?php if ($transaction->reference_operateur): ?>
                                            <tr><td><strong>Réf. opérateur :</strong></div><td><code><?= $transaction->reference_operateur ?></code></div></tr>
                                            <?php endif; ?>
                                            <?php if ($transaction->message_statut): ?>
                                            <tr><td><strong>Message :</strong></div><td><?= $transaction->message_statut ?></div></tr>
                                            <?php endif; ?>
                                            <?php if ($transaction->adresse_ip): ?>
                                            <tr><td><strong>IP :</strong></div><td><?= $transaction->adresse_ip ?></div></tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($commande): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-dark text-white">
                                        <h6 class="mb-0">📦 Commande associée</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>N° commande :</strong></div><td><strong><?= $commande->numero_commande ?></strong> <a href="<?= base_url('Commande/detail/' . $commande->id_commande) ?>">Voir détails</a></div></tr>
                                            <tr><td><strong>Montant total :</strong></div><td><?= number_format($commande->montant_total, 0, ',', ' ') ?> FBu</div></tr>
                                            <tr><td><strong>Statut commande :</strong></div><td><?= ucfirst($commande->statut_commande) ?></div></td>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($is_admin && ($transaction->statut == 'en_attente' || $transaction->statut == 'initie')): ?>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-success update-statut" data-id="<?= $transaction->id_transaction ?>" data-statut="confirme">
                                <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon> Confirmer
                            </button>
                            <button class="btn btn-danger update-statut" data-id="<?= $transaction->id_transaction ?>" data-statut="echoue">
                                <iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon> Échouer
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('.update-statut').on('click', function() {
    const id = $(this).data('id');
    const statut = $(this).data('statut');
    
    Swal.fire({
        title: 'Confirmation',
        text: `Passer cette transaction en "${statut}" ?`,
        icon: 'question',
        input: 'textarea',
        inputPlaceholder: 'Message (optionnel)',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("transactions-paiement/update_statut/") ?>' + id,
                type: 'POST',
                data: {
                    statut: statut,
                    message_statut: result.value
                },
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