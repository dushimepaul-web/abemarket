<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title mb-0">
                            <iconify-icon icon="solar:qr-code-bold-duotone" class="me-2"></iconify-icon>
                            QR Code de livraison
                        </h4>
                        <div class="d-flex gap-2">
                            <?php if ($is_admin): ?>
                                <button type="button" class="btn btn-warning btn-sm" id="regenerateBtn">
                                    <iconify-icon icon="solar:refresh-bold-duotone" class="me-1"></iconify-icon>
                                    Régénérer
                                </button>
                            <?php endif; ?>
                            <a href="<?= base_url('Commande/detail/' . $commande->id_commande) ?>" class="btn btn-secondary btn-sm">
                                <iconify-icon icon="solar:arrow-left-bold-duotone" class="me-1"></iconify-icon>
                                Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <!-- Informations commande -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-card p-3 bg-light rounded">
                                    <h6 class="mb-2 text-primary">
                                        <iconify-icon icon="solar:receipt-bold-duotone" class="me-1"></iconify-icon>
                                        Commande #<?= $commande->numero_commande ?>
                                    </h6>
                                    <p class="mb-1">
                                        <iconify-icon icon="solar:user-id-bold-duotone" class="me-1 fs-14"></iconify-icon>
                                        Client: <strong><?= htmlspecialchars($commande->prenom . ' ' . $commande->nom) ?></strong>
                                    </p>
                                    <p class="mb-0">
                                        <iconify-icon icon="solar:wallet-bold-duotone" class="me-1 fs-14"></iconify-icon>
                                        Montant: <strong class="text-success"><?= number_format($commande->montant_total, 0, ',', ' ') ?> FBu</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card p-3 bg-light rounded">
                                    <h6 class="mb-2 text-info">
                                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="me-1"></iconify-icon>
                                        Statut QR code
                                    </h6>
                                    <?php if ($qr && $qr->est_utilise): ?>
                                        <p class="mb-0 text-success">
                                            <iconify-icon icon="solar:check-circle-bold-duotone" class="me-1"></iconify-icon>
                                            Utilisé le <?= date('d/m/Y H:i', strtotime($qr->date_utilisation)) ?>
                                        </p>
                                    <?php elseif ($qr && strtotime($qr->date_expiration) < time()): ?>
                                        <p class="mb-0 text-danger">
                                            <iconify-icon icon="solar:close-circle-bold-duotone" class="me-1"></iconify-icon>
                                            Expiré le <?= date('d/m/Y H:i', strtotime($qr->date_expiration)) ?>
                                        </p>
                                    <?php elseif ($qr): ?>
                                        <p class="mb-0 text-warning">
                                            <iconify-icon icon="solar:clock-circle-bold-duotone" class="me-1"></iconify-icon>
                                            Valide jusqu'au <?= date('d/m/Y H:i', strtotime($qr->date_expiration)) ?>
                                        </p>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted">
                                            <iconify-icon icon="solar:info-circle-bold-duotone" class="me-1"></iconify-icon>
                                            Aucun QR code généré
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QR Code -->
                        <div class="qr-container mb-4">
                            <?php if ($qr && !empty($qr->qr_image_url) && file_exists(FCPATH . $qr->qr_image_url) && !$qr->est_utilise && strtotime($qr->date_expiration) > time()): ?>
                                <div class="qr-code-wrapper">
                                    <img src="<?= base_url($qr->qr_image_url) ?>" alt="QR Code" class="img-fluid qr-image">
                                </div>
                                <div class="mt-3 d-flex gap-2 justify-content-center flex-wrap">
                                    <a href="<?= base_url($qr->qr_image_url) ?>" download="qr_<?= $commande->numero_commande ?>.png" class="btn btn-primary">
                                        <iconify-icon icon="solar:download-bold-duotone" class="me-1"></iconify-icon>
                                        Télécharger
                                    </a>
                                    <button class="btn btn-secondary" onclick="window.print()">
                                        <iconify-icon icon="solar:printer-bold-duotone" class="me-1"></iconify-icon>
                                        Imprimer
                                    </button>
                                    <button class="btn btn-info" id="copyTokenBtn">
                                        <iconify-icon icon="solar:copy-bold-duotone" class="me-1"></iconify-icon>
                                        Copier le token
                                    </button>
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">Token: <code id="tokenCode"><?= $qr->token ?></code></small>
                                </div>
                            <?php elseif ($qr && $qr->est_utilise): ?>
                                <div class="alert alert-success">
                                    <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-48 mb-2 d-block"></iconify-icon>
                                    <h5>✓ QR code déjà utilisé</h5>
                                    <p>Ce QR code a été scanné le <strong><?= date('d/m/Y H:i', strtotime($qr->date_utilisation)) ?></strong></p>
                                    <?php if ($qr->latitude_scan && $qr->longitude_scan): ?>
                                        <p class="small">
                                            📍 Position: <?= $qr->latitude_scan ?>, <?= $qr->longitude_scan ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($is_admin): ?>
                                        <button type="button" class="btn btn-warning mt-2" id="regenerateBtn">
                                            <iconify-icon icon="solar:refresh-bold-duotone" class="me-1"></iconify-icon>
                                            Générer un nouveau QR code
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($qr && strtotime($qr->date_expiration) < time()): ?>
                                <div class="alert alert-danger">
                                    <iconify-icon icon="solar:calendar-remove-bold-duotone" class="fs-48 mb-2 d-block"></iconify-icon>
                                    <h5>⏰ QR code expiré</h5>
                                    <p>Ce QR code a expiré le <strong><?= date('d/m/Y H:i', strtotime($qr->date_expiration)) ?></strong></p>
                                    <?php if ($is_admin): ?>
                                        <button type="button" class="btn btn-warning mt-2" id="regenerateBtn">
                                            <iconify-icon icon="solar:refresh-bold-duotone" class="me-1"></iconify-icon>
                                            Générer un nouveau QR code
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-48 mb-2 d-block"></iconify-icon>
                                    <h5>📱 Aucun QR code</h5>
                                    <p>Aucun QR code n'a été généré pour cette commande</p>
                                    <?php if ($is_admin): ?>
                                        <button type="button" class="btn btn-primary mt-2" id="generateBtn">
                                            <iconify-icon icon="solar:qr-code-bold-duotone" class="me-1"></iconify-icon>
                                            Générer QR code
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Instructions pour le livreur -->
                        <?php if ($is_livreur && $qr && !$qr->est_utilise && strtotime($qr->date_expiration) > time()): ?>
                            <div class="alert alert-info mt-4 text-start">
                                <h6 class="mb-2">
                                    <iconify-icon icon="solar:info-circle-bold-duotone" class="me-1"></iconify-icon>
                                    Instructions pour le livreur
                                </h6>
                                <ol class="mb-0 ps-3">
                                    <li>Scannez le QR code avec l'application de livraison</li>
                                    <li>Prenez une photo de la livraison</li>
                                    <li>Vérifiez que la position GPS est correcte</li>
                                    <li>Confirmez la livraison</li>
                                </ol>
                            </div>
                        <?php endif; ?>
                        
                        <!-- URL de vérification -->
                        <?php if ($qr && !$qr->est_utilise && strtotime($qr->date_expiration) > time()): ?>
                            <div class="alert alert-secondary mt-3 text-start">
                                <h6 class="mb-2">
                                    <iconify-icon icon="solar:link-bold-duotone" class="me-1"></iconify-icon>
                                    Lien de vérification
                                </h6>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" id="verifyUrl" value="<?= base_url('qr/verify/' . $qr->token) ?>" readonly>
                                    <button class="btn btn-outline-primary btn-sm" type="button" id="copyUrlBtn">
                                        <iconify-icon icon="solar:copy-bold-duotone"></iconify-icon> Copier
                                    </button>
                                </div>
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
$(document).ready(function() {
    <?php if ($is_admin): ?>
    // Générer un nouveau QR code
    $('#generateBtn, #regenerateBtn').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Chargement...');
        
        $.ajax({
            url: '<?= base_url("qr/regenerate/" . $commande->id_commande) ?>',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                Swal.fire('Erreur', 'Erreur de communication', 'error');
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
    <?php endif; ?>
    
    // Copier le token
    $('#copyTokenBtn').on('click', function() {
        const token = $('#tokenCode').text();
        navigator.clipboard.writeText(token).then(function() {
            Swal.fire('Copié !', 'Token copié dans le presse-papier', 'success');
        });
    });
    
    // Copier l'URL
    $('#copyUrlBtn').on('click', function() {
        const url = $('#verifyUrl').val();
        navigator.clipboard.writeText(url).then(function() {
            Swal.fire('Copié !', 'URL copiée dans le presse-papier', 'success');
        });
    });
});
</script>

<style>
.info-card { transition: all 0.3s ease; }
.info-card:hover { background-color: #e9ecef !important; transform: translateY(-2px); }
.qr-container { background: white; padding: 20px; border-radius: 15px; display: inline-block; width: 100%; }
.qr-code-wrapper { background: white; padding: 20px; border-radius: 10px; display: inline-block; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
.qr-image { max-width: 250px; height: auto; }
.alert { border-radius: 12px; }

@media print {
    .sidebar, .topbar, .card-header, .btn, .footer, .info-card, .alert-secondary, .alert-info { display: none !important; }
    .card { border: none !important; box-shadow: none !important; margin: 0 !important; }
    .qr-container { display: block !important; padding: 0 !important; }
    .qr-code-wrapper { box-shadow: none !important; padding: 0 !important; }
    body { background: white; padding: 0; margin: 0; }
    .page-content { margin: 0; padding: 0; }
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>