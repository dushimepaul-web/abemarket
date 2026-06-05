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
                            <iconify-icon icon="solar:key-bold-duotone" class="me-2"></iconify-icon>
                            Détails du code OTP #<?= $code->id_otp ?>
                        </h4>
                        <a href="<?= base_url('codes-otp') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">📋 Informations générales</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>ID :</strong></td><td>#<?= $code->id_otp ?></td></tr>
                                            <tr><td><strong>Code :</strong></td><td class="fs-2 fw-bold text-primary"><?= $code->code ?></td></tr>
                                            <tr><td><strong>Type :</strong></td>
                                                <td>
                                                    <?php
                                                    switch($code->type_otp) {
                                                        case 'connexion': echo '🔑 Connexion'; break;
                                                        case 'verification_telephone': echo '📱 Vérification téléphone'; break;
                                                        case 'verification_email': echo '📧 Vérification email'; break;
                                                        case 'reinitialisation_mdp': echo '🔐 Réinitialisation mot de passe'; break;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr><td><strong>Tentatives :</strong></td><td><?= $code->tentatives ?>/3 <?= $code->tentatives >= 3 ? '<span class="badge bg-danger">Compte bloqué</span>' : '' ?></td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">⏰ Dates & Statut</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Date création :</strong></td><td><?= date('d/m/Y H:i:s', strtotime($code->date_creation)) ?></td></tr>
                                            <tr><td><strong>Date expiration :</strong></td>
                                                <td class="<?= strtotime($code->date_expiration) < time() ? 'text-danger' : 'text-success' ?>">
                                                    <?= date('d/m/Y H:i:s', strtotime($code->date_expiration)) ?>
                                                </td>
                                            </tr>
                                            <tr><td><strong>Statut :</strong></td>
                                                <td>
                                                    <?php if ($code->utilise): ?>
                                                        <span class="badge bg-success">✅ Utilisé</span>
                                                    <?php elseif (strtotime($code->date_expiration) < time()): ?>
                                                        <span class="badge bg-danger">⏰ Expiré</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">🟢 Actif</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr><td><strong>Temps restant :</strong></td>
                                                <td>
                                                    <?php if (!$code->utilise && strtotime($code->date_expiration) > time()): 
                                                        $diff = strtotime($code->date_expiration) - time();
                                                        $minutes = floor($diff / 60);
                                                        $secondes = $diff % 60;
                                                        echo $minutes . ' minute(s) et ' . $secondes . ' seconde(s)';
                                                    else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">👤 Utilisateur</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>Nom :</strong></td><td><?= htmlspecialchars($code->prenom . ' ' . $code->nom) ?></td></tr>
                                            <tr><td><strong>Email :</strong></td><td><?= htmlspecialchars($code->user_email) ?></td></tr>
                                            <?php if ($code->telephone): ?>
                                            <tr><td><strong>Téléphone :</strong></td><td><?= htmlspecialchars($code->telephone) ?></td></tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-danger delete-otp" data-id="<?= $code->id_otp ?>">
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
                            window.location.href = '<?= base_url("codes-otp") ?>';
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