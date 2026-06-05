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
                            Détails du mode de paiement - <?= htmlspecialchars($mode->code) ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('mode-payement/edit/' . $mode->id_mode_payement) ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:pen-2-broken"></iconify-icon> Modifier
                            </a>
                            <a href="<?= base_url('mode-payement') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- En-tête avec statut -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-<?= $mode->est_actif ? 'success' : 'secondary' ?> d-flex align-items-center">
                                    <span class="fs-2 me-3"><?= $mode->est_actif ? '✅' : '⛔' ?></span>
                                    <div>
                                        <strong>Statut : <?= $mode->est_actif ? 'Actif' : 'Inactif' ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Logo -->
                            <div class="col-md-3 text-center">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <?php if ($mode->logo_url && file_exists(FCPATH . $mode->logo_url)): ?>
                                            <img src="<?= base_url($mode->logo_url) ?>" alt="Logo" class="img-fluid" style="max-height: 150px;">
                                        <?php else: ?>
                                            <div class="py-4">
                                                <iconify-icon icon="solar:card-bold-duotone" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun logo</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations -->
                            <div class="col-md-9">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">📋 Informations générales</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="180"><strong>Code :</strong></td>
                                                <td><strong class="text-primary fs-4"><?= htmlspecialchars($mode->code) ?></strong></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Description :</strong></td>
                                                <td><?= htmlspecialchars($mode->description) ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Type :</strong></td>
                                                <td>
                                                    <?php
                                                    $type_icon = '';
                                                    switch($mode->type) {
                                                        case 'mobile_money': $type_icon = '📱 Mobile Money'; break;
                                                        case 'carte_bancaire': $type_icon = '💳 Carte bancaire'; break;
                                                        case 'virement': $type_icon = '🏦 Virement'; break;
                                                        case 'especes_livraison': $type_icon = '💰 Espèces à la livraison'; break;
                                                    }
                                                    echo $type_icon;
                                                    ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Ordre d'affichage :</strong></td>
                                                <td><?= $mode->ordre_affichage ?></div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Frais -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">💰 Frais de transaction</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="180"><strong>Frais fixe :</strong></td>
                                                <td>
                                                    <?php if ($mode->frais_fixe > 0): ?>
                                                        <?= number_format($mode->frais_fixe, 0, ',', ' ') ?> FBu
                                                    <?php else: ?>
                                                        <span class="text-muted">0 FBu</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Frais en pourcentage :</strong></td>
                                                <td>
                                                    <?php if ($mode->frais_pourcentage > 0): ?>
                                                        <?= $mode->frais_pourcentage ?>%
                                                    <?php else: ?>
                                                        <span class="text-muted">0%</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-success">Exemple (100 000 FBu) :</strong></td>
                                                <td>
                                                    <?php
                                                    $montant = 100000;
                                                    $frais = $mode->frais_fixe + ($montant * $mode->frais_pourcentage / 100);
                                                    ?>
                                                    Frais: <?= number_format($frais, 0, ',', ' ') ?> FBu
                                                    <br>Total à payer: <?= number_format($montant + $frais, 0, ',', ' ') ?> FBu
                                                </div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">📝 Instructions</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($mode->instructions): ?>
                                            <?= nl2br(htmlspecialchars($mode->instructions)) ?>
                                        <?php else: ?>
                                            <p class="text-muted">Aucune instruction</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="d-flex gap-2 mt-3">
                            <a href="<?= base_url('mode-payement/edit/' . $mode->id_mode_payement) ?>" class="btn btn-primary">
                                <iconify-icon icon="solar:pen-2-broken"></iconify-icon> Modifier
                            </a>
                            <?php if ($mode->est_actif): ?>
                                <button class="btn btn-warning toggle-status" data-id="<?= $mode->id_mode_payement ?>" data-status="1" data-code="<?= htmlspecialchars($mode->code) ?>">
                                    <iconify-icon icon="solar:eye-closed-bold-duotone"></iconify-icon> Désactiver
                                </button>
                            <?php else: ?>
                                <button class="btn btn-success toggle-status" data-id="<?= $mode->id_mode_payement ?>" data-status="0" data-code="<?= htmlspecialchars($mode->code) ?>">
                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon> Activer
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-danger delete-mode" data-id="<?= $mode->id_mode_payement ?>" data-code="<?= htmlspecialchars($mode->code) ?>">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
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
    
    $('.delete-mode').on('click', function() {
        const id = $(this).data('id');
        const code = $(this).data('code');
        
        Swal.fire({
            title: 'Confirmation',
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