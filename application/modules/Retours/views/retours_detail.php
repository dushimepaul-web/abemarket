<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:refresh-bold-duotone" class="me-2"></iconify-icon>
                            Détails de la demande #<?= $retour->id_retour ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('retours') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">📋 Informations demande</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr><td width="150"><strong>ID Demande :</strong></td><td>#<?= $retour->id_retour ?></td></tr>
                                            <tr><td><strong>Commande :</strong></td><td><strong><?= $retour->numero_commande ?></strong> (<a href="<?= base_url('Commande/detail/' . $retour->id_commande) ?>">Voir commande</a>)</div></tr>
                                            <tr><td><strong>Client :</strong></td><td><?= htmlspecialchars($retour->prenom . ' ' . $retour->nom) ?><br><small class="text-muted"><?= htmlspecialchars($retour->email) ?></small></div></tr>
                                            <tr><td><strong>Type :</strong></td><td>
                                                <?php
                                                $type_label = '';
                                                switch($retour->type) {
                                                    case 'retour_produit': $type_label = 'Retour produit'; break;
                                                    case 'remboursement_partiel': $type_label = 'Remboursement partiel'; break;
                                                    case 'remboursement_total': $type_label = 'Remboursement total'; break;
                                                }
                                                echo $type_label;
                                                ?>
                                            </div></tr>
                                            <tr><td><strong>Motif :</strong></td><td>
                                                <?php
                                                $motif_label = '';
                                                switch($retour->motif) {
                                                    case 'produit_defectueux': $motif_label = 'Produit défectueux'; break;
                                                    case 'non_conforme': $motif_label = 'Non conforme'; break;
                                                    case 'erreur_livraison': $motif_label = 'Erreur livraison'; break;
                                                    case 'changement_avis': $motif_label = 'Changement d\'avis'; break;
                                                    case 'produit_endommage': $motif_label = 'Produit endommagé'; break;
                                                    default: $motif_label = $retour->motif;
                                                }
                                                echo $motif_label;
                                                ?>
                                            </div></tr>
                                            <tr><td><strong>Date demande :</strong></td><td><?= date('d/m/Y H:i', strtotime($retour->date_creation)) ?></td></tr>
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
                                            <tr><td width="150"><strong>Montant demandé :</strong></td><td><strong class="text-primary"><?= number_format($retour->montant_demande, 0, ',', ' ') ?> FBu</strong></div></tr>
                                            <?php if ($retour->montant_approuve): ?>
                                            <tr><td><strong>Montant approuvé :</strong></td><td><strong class="text-success"><?= number_format($retour->montant_approuve, 0, ',', ' ') ?> FBu</strong></div></tr>
                                            <?php endif; ?>
                                            <?php if ($retour->methode_remboursement): ?>
                                            <tr><td><strong>Méthode remboursement :</strong></td><td><?= $retour->methode_remboursement == 'mobile_money' ? '📱 Mobile Money' : '🏦 Virement bancaire' ?></div></tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">📝 Description</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><?= nl2br(htmlspecialchars($retour->description ?? 'Aucune description')) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($retour->photos_urls): 
                            $photos = json_decode($retour->photos_urls, true);
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">📎 Photos jointes</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php foreach ($photos as $photo): ?>
                                                <div class="col-md-3 mb-2">
                                                    <a href="<?= base_url($photo) ?>" target="_blank">
                                                        <img src="<?= base_url($photo) ?>" alt="Photo" class="img-fluid rounded" style="height: 120px; object-fit: cover;">
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($article): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">📦 Article concerné</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Produit:</strong> <?= htmlspecialchars($article->nom_produit ?? '-') ?></p>
                                        <p><strong>Quantité:</strong> <?= $article->quantite ?? '-' ?></p>
                                        <p><strong>Prix unitaire:</strong> <?= number_format($article->prix_unitaire ?? 0, 0, ',', ' ') ?> FBu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($is_admin && $retour->statut == 'demande'): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-dark text-white">
                                        <h6 class="mb-0">⚙️ Traiter la demande</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="traiterForm">
                                            <input type="hidden" name="id_retour" value="<?= $retour->id_retour ?>">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Statut</label>
                                                    <select name="statut" class="form-select" required>
                                                        <option value="approuve">✅ Approuver</option>
                                                        <option value="refuse">❌ Refuser</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3" id="montant_container">
                                                    <label class="form-label">Montant approuvé (FBu)</label>
                                                    <input type="number" name="montant_approuve" class="form-control" value="<?= $retour->montant_demande ?>">
                                                </div>
                                                <div class="col-md-4 mb-3" id="methode_container">
                                                    <label class="form-label">Méthode remboursement</label>
                                                    <select name="methode_remboursement" class="form-select">
                                                        <option value="mobile_money">📱 Mobile Money</option>
                                                        <option value="virement">🏦 Virement bancaire</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 mb-3" id="motif_container" style="display: none;">
                                                    <label class="form-label">Motif du refus</label>
                                                    <textarea name="motif_refus" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Enregistrer la décision</button>
                                        </form>
                                    </div>
                                </div>
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
    $('select[name="statut"]').on('change', function() {
        if ($(this).val() == 'refuse') {
            $('#montant_container').hide();
            $('#methode_container').hide();
            $('#motif_container').show();
        } else {
            $('#montant_container').show();
            $('#methode_container').show();
            $('#motif_container').hide();
        }
    });
    
    $('#traiterForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url("retours/traiter/" . $retour->id_retour) ?>',
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
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>