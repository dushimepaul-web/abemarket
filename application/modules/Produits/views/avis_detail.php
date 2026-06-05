<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Détails de l'avis - <?= htmlspecialchars($avis->nom_produit) ?></h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('avis-produits') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Informations produit</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="120"><strong>Produit :</strong></td>
                                                <td><?= htmlspecialchars($avis->nom_produit) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>SKU :</strong></td>
                                                <td><code><?= htmlspecialchars($avis->sku) ?></code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>ID Produit :</strong></td>
                                                <td>#<?= $avis->id_produit ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Informations client</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="120"><strong>Nom :</strong></td>
                                                <td><?= htmlspecialchars($avis->prenom . ' ' . $avis->nom) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email :</strong></td>
                                                <td><?= htmlspecialchars($avis->email ?? '-') ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>ID Client :</strong></td>
                                                <td>#<?= $avis->id_utilisateur ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Informations avis</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="120"><strong>Date :</strong></td>
                                                <td><?= date('d/m/Y à H:i', strtotime($avis->date_creation)) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Note :</strong></td>
                                                <td>
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <iconify-icon icon="solar:star-<?= $i <= $avis->note ? 'bold' : 'linear' ?>-duotone" class="fs-18 text-warning"></iconify-icon>
                                                    <?php endfor; ?>
                                                    <span class="ms-2">(<?= $avis->note ?>/5)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Statut :</strong></td>
                                                <td>
                                                    <?php if ($avis->est_approuve == 1): ?>
                                                        <span class="badge bg-success">Approuvé</span>
                                                    <?php elseif ($avis->est_approuve == 0): ?>
                                                        <span class="badge bg-warning">En attente</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Rejeté</span>
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
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">📝 Titre de l'avis</h6>
                                        <p class="p-3 bg-light rounded"><?= htmlspecialchars($avis->titre ?? 'Aucun titre') ?></p>
                                    </div>
                                </div>
                                
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">💬 Commentaire</h6>
                                        <div class="p-3 bg-light rounded">
                                            <?= nl2br(htmlspecialchars($avis->commentaire ?? 'Aucun commentaire')) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if (!empty($avis->urls_medias)): ?>
                                    <?php $medias = json_decode($avis->urls_medias, true); ?>
                                    <?php if (!empty($medias)): ?>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h6 class="card-title text-muted mb-3">📎 Médias joints</h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <?php foreach ($medias as $media): ?>
                                                        <a href="<?= base_url($media) ?>" target="_blank" class="btn btn-outline-primary">
                                                            <iconify-icon icon="solar:gallery-bold-duotone"></iconify-icon> Voir le média
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if (!empty($avis->reponse_vendeur)): ?>
                                    <div class="card mb-3 border-success">
                                        <div class="card-header bg-success bg-opacity-10">
                                            <h6 class="card-title mb-0">💬 Réponse du vendeur</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="p-3 bg-light rounded">
                                                <?= nl2br(htmlspecialchars($avis->reponse_vendeur)) ?>
                                            </div>
                                            <small class="text-muted mt-2 d-block">
                                                Répondu le <?= date('d/m/Y à H:i', strtotime($avis->date_reponse_vendeur)) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php if ($is_admin || $is_vendeur): ?>
                                        <div class="card mb-3">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="card-title mb-0">✏️ Répondre à cet avis</h6>
                                            </div>
                                            <div class="card-body">
                                                <form id="reponseForm" method="post">
                                                    <input type="hidden" name="id_avis" value="<?= $avis->id_avis ?>">
                                                    <div class="mb-3">
                                                        <textarea name="reponse" id="reponse_texte" class="form-control" rows="4" placeholder="Votre réponse..."></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary" id="btnEnvoyerReponse">
                                                        <iconify-icon icon="solar:send-bold-duotone"></iconify-icon> Envoyer la réponse
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action pour l'admin -->
                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <?php if ($is_admin): ?>
                                <!-- Bouton Approuver - visible si l'avis n'est pas déjà approuvé -->
                                <?php if ($avis->est_approuve != 1): ?>
                                    <button type="button" class="btn btn-success" id="btnApprouver" data-id="<?= $avis->id_avis ?>">
                                        <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon> Approuver
                                    </button>
                                <?php endif; ?>
                                
                                <!-- Bouton Rejeter - visible si l'avis n'est pas déjà rejeté -->
                                <?php if ($avis->est_approuve != 0 && $avis->est_approuve != -1): ?>
                                    <button type="button" class="btn btn-danger" id="btnRejeter" data-id="<?= $avis->id_avis ?>">
                                        <iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon> Rejeter
                                    </button>
                                <?php endif; ?>
                                
                                <!-- Bouton Désapprouver - visible si l'avis est approuvé -->
                                <?php if ($avis->est_approuve == 1): ?>
                                    <button type="button" class="btn btn-warning" id="btnDesapprouver" data-id="<?= $avis->id_avis ?>">
                                        <iconify-icon icon="solar:refresh-bold-duotone"></iconify-icon> Désapprouver
                                    </button>
                                <?php endif; ?>
                                
                                <!-- Bouton Remettre en attente - visible si l'avis est rejeté -->
                                <?php if ($avis->est_approuve == -1): ?>
                                    <button type="button" class="btn btn-info" id="btnEnAttente" data-id="<?= $avis->id_avis ?>">
                                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon> Remettre en attente
                                    </button>
                                <?php endif; ?>
                                
                                <!-- Bouton Supprimer - toujours visible -->
                                <button type="button" class="btn btn-danger" id="btnSupprimer" data-id="<?= $avis->id_avis ?>" data-produit="<?= htmlspecialchars($avis->nom_produit) ?>">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon> Supprimer
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if ($is_admin): ?>
// Approuver
$('#btnApprouver').on('click', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous approuver cet avis ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("avis-produit/approuver/") ?>' + id,
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
                },
                error: function(xhr) {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});

// Rejeter
$('#btnRejeter').on('click', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous rejeter cet avis ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, rejeter',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("avis-produit/rejeter/") ?>' + id,
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
                },
                error: function(xhr) {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});

// Désapprouver (remettre en attente)
$('#btnDesapprouver').on('click', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous désapprouver cet avis ? Il sera remis en attente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("avis-produit/desapprouver/") ?>' + id,
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
                },
                error: function(xhr) {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});

// Remettre en attente
$('#btnEnAttente').on('click', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous remettre cet avis en attente ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("avis-produit/enattente/") ?>' + id,
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
                },
                error: function(xhr) {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});

// Supprimer
$('#btnSupprimer').on('click', function() {
    const id = $(this).data('id');
    const produit = $(this).data('produit');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer l'avis pour " + produit,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("avis-produit/delete/") ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Supprimé!', response.message, 'success').then(() => {
                            window.location.href = '<?= base_url("avis-produits") ?>';
                        });
                    } else {
                        Swal.fire('Erreur!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});
<?php endif; ?>

// Répondre à l'avis
$('#reponseForm').on('submit', function(e) {
    e.preventDefault();
    
    const reponse = $('#reponse_texte').val();
    const idAvis = $('input[name="id_avis"]').val();
    
    if (!reponse.trim()) {
        Swal.fire('Erreur', 'La réponse ne peut pas être vide', 'error');
        return;
    }
    
    const btn = $('#btnEnvoyerReponse');
    const originalHtml = btn.html();
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Envoi en cours...');
    
    $.ajax({
        url: '<?= base_url("avis-produit/repondre") ?>',
        type: 'POST',
        data: {
            id_avis: idAvis,
            reponse: reponse
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire('Succès', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Erreur', response.message, 'error');
                btn.prop('disabled', false).html(originalHtml);
            }
        },
        error: function(xhr) {
            console.log('Erreur:', xhr.responseText);
            Swal.fire('Erreur', 'Erreur de communication avec le serveur', 'error');
            btn.prop('disabled', false).html(originalHtml);
        }
    });
});
</script>

<style>
.bg-light {
    background-color: #f8f9fa !important;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>