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
                            <iconify-icon icon="solar:history-bold-duotone" class="me-2"></iconify-icon>
                            Historique des statuts - Commande #<?= htmlspecialchars($commande->numero_commande) ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <?php if ($is_admin || $is_vendeur): ?>
                                <button class="btn btn-sm btn-primary" id="btnAjouterStatut">
                                    <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                    Ajouter un statut
                                </button>
                            <?php endif; ?>
                            <?php if ($is_admin): ?>
                                <a href="<?= base_url('historique-statut-commande/exporter/' . $commande->id_commande) ?>" class="btn btn-sm btn-success">
                                    <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                    Exporter CSV
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('commandes/detail/' . $commande->id_commande) ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Timeline -->
                        <div class="timeline">
                            <?php if (!empty($historique)): ?>
                                <?php foreach ($historique as $h): ?>
                                    <div class="timeline-item" data-id="<?= $h->id_historique ?>">
                                        <div class="timeline-badge <?= $h->statut == 'livre' ? 'bg-success' : ($h->statut == 'annule' ? 'bg-danger' : 'bg-primary') ?>">
                                            <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h6 class="timeline-title">
                                                    <?php
                                                    $statut_label = '';
                                                    switch($h->statut) {
                                                        case 'en_attente': $statut_label = 'En attente'; break;
                                                        case 'confirme': $statut_label = 'Confirmée'; break;
                                                        case 'en_preparation': $statut_label = 'En préparation'; break;
                                                        case 'expedie': $statut_label = 'Expédiée'; break;
                                                        case 'livre': $statut_label = 'Livrée'; break;
                                                        case 'annule': $statut_label = 'Annulée'; break;
                                                        default: $statut_label = $h->statut;
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $h->statut == 'livre' ? 'success' : ($h->statut == 'annule' ? 'danger' : 'primary') ?>">
                                                        <?= $statut_label ?>
                                                    </span>
                                                    <small class="text-muted ms-2">
                                                        <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                                                        <?= date('d/m/Y à H:i', strtotime($h->date_creation)) ?>
                                                    </small>
                                                </h6>
                                            </div>
                                            <div class="timeline-body">
                                                <?php if ($h->commentaire): ?>
                                                    <p><strong>Commentaire :</strong> <?= nl2br(htmlspecialchars($h->commentaire)) ?></p>
                                                <?php endif; ?>
                                                <p class="text-muted small mb-0">
                                                    <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                                                    Modifié par : 
                                                    <?php if ($h->prenom): ?>
                                                        <?= htmlspecialchars($h->prenom . ' ' . $h->nom) ?>
                                                    <?php else: ?>
                                                        Système
                                                    <?php endif; ?>
                                                </p>
                                                <?php if ($h->latitude && $h->longitude): ?>
                                                    <p class="text-muted small mb-0">
                                                        <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon>
                                                        Position : <?= $h->latitude ?>, <?= $h->longitude ?>
                                                        <a href="https://www.google.com/maps?q=<?= $h->latitude ?>,<?= $h->longitude ?>" target="_blank">
                                                            Voir sur la carte
                                                        </a>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($is_admin): ?>
                                                <div class="timeline-footer">
                                                    <button class="btn btn-sm btn-danger delete-historique" data-id="<?= $h->id_historique ?>">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <iconify-icon icon="solar:history-broken" class="fs-48 text-muted"></iconify-icon>
                                    <p class="mt-2">Aucun historique pour cette commande</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Statut -->
<div class="modal fade" id="statutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                    Ajouter un statut
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statutForm">
                <div class="modal-body">
                    <input type="hidden" name="id_commande" value="<?= $commande->id_commande ?>">
                    <div class="mb-3">
                        <label class="form-label">Statut <span class="text-danger">*</span></label>
                        <select name="statut" class="form-select" required>
                            <option value="en_attente">En attente</option>
                            <option value="confirme">Confirmée</option>
                            <option value="en_preparation">En préparation</option>
                            <option value="expedie">Expédiée</option>
                            <option value="livre">Livrée</option>
                            <option value="annule">Annulée</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commentaire</label>
                        <textarea name="commentaire" class="form-control" rows="3" placeholder="Informations supplémentaires..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Position GPS (optionnel)</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="latitude" class="form-control" placeholder="Latitude">
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="longitude" class="form-control" placeholder="Longitude">
                            </div>
                        </div>
                        <small class="text-muted">
                            <button type="button" class="btn btn-link btn-sm p-0" id="btnGetPosition">
                                <iconify-icon icon="solar:map-point-bold-duotone"></iconify-icon> Utiliser ma position
                            </button>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}
.timeline:before {
    content: '';
    position: absolute;
    left: 40px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}
.timeline-item {
    position: relative;
    margin-bottom: 30px;
    display: flex;
}
.timeline-badge {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    z-index: 1;
    flex-shrink: 0;
}
.timeline-badge iconify-icon {
    font-size: 20px;
}
.timeline-panel {
    flex: 1;
    margin-left: 20px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    position: relative;
}
.timeline-panel:before {
    content: '';
    position: absolute;
    left: -10px;
    top: 12px;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-right: 10px solid #e9ecef;
}
.timeline-panel:after {
    content: '';
    position: absolute;
    left: -8px;
    top: 12px;
    width: 0;
    height: 0;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-right: 10px solid white;
}
.timeline-heading {
    margin-bottom: 10px;
}
.timeline-title {
    margin: 0;
}
.timeline-footer {
    margin-top: 10px;
    text-align: right;
}
.bg-primary { background-color: #0d6efd !important; }
.bg-success { background-color: #198754 !important; }
.bg-danger { background-color: #dc3545 !important; }
</style>

<script>
$(document).ready(function() {
    // Ouvrir modal
    $('#btnAjouterStatut').on('click', function() {
        $('#statutModal').modal('show');
    });
    
    // Récupérer position GPS
    $('#btnGetPosition').on('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                $('input[name="latitude"]').val(position.coords.latitude);
                $('input[name="longitude"]').val(position.coords.longitude);
                Swal.fire('Succès', 'Position récupérée', 'success');
            }, function() {
                Swal.fire('Erreur', 'Impossible de récupérer la position', 'error');
            });
        } else {
            Swal.fire('Erreur', 'Géolocalisation non supportée', 'error');
        }
    });
    
    // Soumettre formulaire
    $('#statutForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url("historique-statut-commande/add") ?>',
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
            },
            error: function() {
                Swal.fire('Erreur', 'Erreur de communication', 'error');
            }
        });
    });
    
    // Supprimer historique
    $('.delete-historique').on('click', function() {
        const id = $(this).closest('.timeline-item').data('id');
        
        Swal.fire({
            title: 'Confirmation',
            text: 'Supprimer cet historique ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("historique-statut-commande/delete/") ?>' + id,
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