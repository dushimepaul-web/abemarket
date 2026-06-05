<?php include VIEWPATH . 'includes/frontend/Header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <iconify-icon icon="solar:bell-bold-duotone" class="me-2"></iconify-icon>
                        Mes notifications
                        <span class="badge bg-light text-dark ms-2"><?= $stats->non_lues ?> non lues</span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <button class="btn btn-sm btn-success" id="marquerToutesLues">
                                <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                Tout marquer comme lu
                            </button>
                            <button class="btn btn-sm btn-danger" id="supprimerToutes">
                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                Supprimer toutes
                            </button>
                        </div>
                        <div>
                            <span class="badge bg-primary">📊 Total: <?= $stats->total ?></span>
                            <span class="badge bg-success ms-1">✅ Lues: <?= $stats->lues ?></span>
                            <span class="badge bg-warning ms-1">⏳ Non lues: <?= $stats->non_lues ?></span>
                        </div>
                    </div>
                    
                    <div class="list-group">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $n): ?>
                                <a href="<?= base_url('notifications/detail/' . $n->id_notification) ?>" class="list-group-item list-group-item-action <?= !$n->est_lue ? 'bg-light border-start border-primary border-4' : '' ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php
                                            $cat_icon = '';
                                            switch($n->categorie) {
                                                case 'commande': $cat_icon = '📦'; break;
                                                case 'paiement': $cat_icon = '💰'; break;
                                                case 'livraison': $cat_icon = '🚚'; break;
                                                case 'securite': $cat_icon = '🔒'; break;
                                                case 'systeme': $cat_icon = '⚙️'; break;
                                                default: $cat_icon = '📢';
                                            }
                                            ?>
                                            <strong><?= $cat_icon ?> <?= htmlspecialchars($n->titre) ?></strong>
                                            <?php if (!$n->est_lue): ?>
                                                <span class="badge bg-primary ms-2">Nouveau</span>
                                            <?php endif; ?>
                                            <div class="small text-muted mt-1"><?= htmlspecialchars(substr($n->message, 0, 100)) ?>...</div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($n->date_creation)) ?></small>
                                            <br>
                                            <?php if (!$n->est_lue): ?>
                                                <span class="badge bg-warning mt-1">Non lue</span>
                                            <?php else: ?>
                                                <span class="badge bg-success mt-1">Lue</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <iconify-icon icon="solar:bell-broken" class="fs-48 text-muted"></iconify-icon>
                                <p class="mt-2">Aucune notification</p>
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
// Marquer toutes comme lues
$('#marquerToutesLues').on('click', function() {
    $.ajax({
        url: '<?= base_url("notifications/marquer_toutes_lues") ?>',
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

// Supprimer toutes
$('#supprimerToutes').on('click', function() {
    Swal.fire({
        title: 'Confirmation',
        text: 'Supprimer toutes vos notifications ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("notifications/supprimer_toutes") ?>',
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

<?php include VIEWPATH . 'includes/frontend/Footer.php'; ?>