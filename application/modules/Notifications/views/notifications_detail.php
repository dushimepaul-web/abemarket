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
                            <iconify-icon icon="solar:bell-bold-duotone" class="me-2"></iconify-icon>
                            Détails de la notification
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= $is_admin ? base_url('notifications') : base_url('notifications/mes_notifications') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Envoyée à</small>
                            <h6><?= htmlspecialchars($notification->prenom . ' ' . $notification->nom) ?> (<?= htmlspecialchars($notification->email) ?>)</h6>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">Catégorie</small>
                                <div>
                                    <?php
                                    $cat_icon = '';
                                    switch($notification->categorie) {
                                        case 'commande': $cat_icon = '📦 Commande'; break;
                                        case 'paiement': $cat_icon = '💰 Paiement'; break;
                                        case 'livraison': $cat_icon = '🚚 Livraison'; break;
                                        case 'securite': $cat_icon = '🔒 Sécurité'; break;
                                        case 'systeme': $cat_icon = '⚙️ Système'; break;
                                        default: $cat_icon = '📢 ' . ucfirst($notification->categorie);
                                    }
                                    echo $cat_icon;
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Canal</small>
                                <div><?= ucfirst($notification->type_canal) ?></div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Statut</small>
                                <div>
                                    <?php if ($notification->est_lue): ?>
                                        <span class="badge bg-success">✅ Lue le <?= date('d/m/Y H:i', strtotime($notification->date_lecture)) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">⏳ Non lue</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Date d'envoi</small>
                            <div><?= date('d/m/Y à H:i', strtotime($notification->date_creation)) ?></div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Titre</small>
                            <h4 class="mt-1"><?= htmlspecialchars($notification->titre) ?></h4>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Message</small>
                            <div class="p-3 bg-light rounded mt-1">
                                <?= nl2br(htmlspecialchars($notification->message)) ?>
                            </div>
                        </div>
                        
                        <?php if (!$notification->est_lue && !$is_admin): ?>
                            <button class="btn btn-success" id="marquerLue">
                                <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                Marquer comme lue
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if (!$notification->est_lue && !$is_admin): ?>
$('#marquerLue').on('click', function() {
    $.ajax({
        url: '<?= base_url("notifications/marquer_lue/") . $notification->id_notification ?>',
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
});
<?php endif; ?>
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>