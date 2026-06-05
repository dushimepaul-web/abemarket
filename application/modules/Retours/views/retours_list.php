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
                            <iconify-icon icon="solar:refresh-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des retours et remboursements
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('retours/exporter') ?>" class="btn btn-sm btn-success">
                                <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                Exporter CSV
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📊 Total demandes</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏳ En attente</span>
                                        <strong><?= number_format($stats->demandes ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Approuvés</span>
                                        <strong><?= number_format($stats->approuves ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>💰 Montant total</span>
                                        <strong><?= number_format($stats->total_demande ?? 0, 0, ',', ' ') ?> FBu</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="statut" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="demande" <?= ($filters['statut'] ?? '') == 'demande' ? 'selected' : '' ?>>En attente</option>
                                    <option value="en_cours" <?= ($filters['statut'] ?? '') == 'en_cours' ? 'selected' : '' ?>>En cours</option>
                                    <option value="approuve" <?= ($filters['statut'] ?? '') == 'approuve' ? 'selected' : '' ?>>Approuvé</option>
                                    <option value="refuse" <?= ($filters['statut'] ?? '') == 'refuse' ? 'selected' : '' ?>>Refusé</option>
                                    <option value="rembourse" <?= ($filters['statut'] ?? '') == 'rembourse' ? 'selected' : '' ?>>Remboursé</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="motif" class="form-select">
                                    <option value="">Tous les motifs</option>
                                    <option value="produit_defectueux" <?= ($filters['motif'] ?? '') == 'produit_defectueux' ? 'selected' : '' ?>>Produit défectueux</option>
                                    <option value="non_conforme" <?= ($filters['motif'] ?? '') == 'non_conforme' ? 'selected' : '' ?>>Non conforme</option>
                                    <option value="erreur_livraison" <?= ($filters['motif'] ?? '') == 'erreur_livraison' ? 'selected' : '' ?>>Erreur livraison</option>
                                    <option value="changement_avis" <?= ($filters['motif'] ?? '') == 'changement_avis' ? 'selected' : '' ?>>Changement d'avis</option>
                                    <option value="produit_endommage" <?= ($filters['motif'] ?? '') == 'produit_endommage' ? 'selected' : '' ?>>Produit endommagé</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Commande</th>
                                        <th>Client</th>
                                        <th>Type</th>
                                        <th>Motif</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Date demande</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($retours)): ?>
                                        <?php foreach ($retours as $r): ?>
                                            <tr>
                                                <td>#<?= $r->id_retour ?></td>
                                                <td>
                                                    <strong><?= $r->numero_commande ?></strong>
                                                </div>
                                                <td>
                                                    <?= htmlspecialchars($r->client_nom) ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($r->email) ?></small>
                                                </div>
                                                <td>
                                                    <?php
                                                    $type_label = '';
                                                    switch($r->type) {
                                                        case 'retour_produit': $type_label = 'Retour produit'; break;
                                                        case 'remboursement_partiel': $type_label = 'Remboursement partiel'; break;
                                                        case 'remboursement_total': $type_label = 'Remboursement total'; break;
                                                        default: $type_label = $r->type;
                                                    }
                                                    ?>
                                                    <?= $type_label ?>
                                                </div>
                                                <td>
                                                    <?php
                                                    $motif_label = '';
                                                    switch($r->motif) {
                                                        case 'produit_defectueux': $motif_label = 'Produit défectueux'; break;
                                                        case 'non_conforme': $motif_label = 'Non conforme'; break;
                                                        case 'erreur_livraison': $motif_label = 'Erreur livraison'; break;
                                                        case 'changement_avis': $motif_label = 'Changement d\'avis'; break;
                                                        case 'produit_endommage': $motif_label = 'Produit endommagé'; break;
                                                        default: $motif_label = $r->motif;
                                                    }
                                                    ?>
                                                    <?= $motif_label ?>
                                                </div>
                                                <td><strong><?= number_format($r->montant_demande, 0, ',', ' ') ?> FBu</strong></div>
                                                <td>
                                                    <?php
                                                    $badge_color = '';
                                                    $badge_text = '';
                                                    switch($r->statut) {
                                                        case 'demande': $badge_color = 'warning'; $badge_text = '⏳ En attente'; break;
                                                        case 'en_cours': $badge_color = 'info'; $badge_text = '🔄 En cours'; break;
                                                        case 'approuve': $badge_color = 'success'; $badge_text = '✅ Approuvé'; break;
                                                        case 'refuse': $badge_color = 'danger'; $badge_text = '❌ Refusé'; break;
                                                        case 'rembourse': $badge_color = 'success'; $badge_text = '💰 Remboursé'; break;
                                                        default: $badge_color = 'secondary'; $badge_text = $r->statut;
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $badge_color ?>"><?= $badge_text ?></span>
                                                </div>
                                                <td><?= date('d/m/Y', strtotime($r->date_creation)) ?></div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('retours/detail/' . $r->id_retour) ?>" class="btn btn-sm btn-info" title="Détails">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <?php if ($r->statut == 'demande'): ?>
                                                            <a href="<?= base_url('retours/traiter/' . $r->id_retour) ?>" class="btn btn-sm btn-primary" title="Traiter">
                                                                <iconify-icon icon="solar:pen-2-broken"></iconify-icon>
                                                            </a>
                                                        <?php endif; ?>
                                                        <button class="btn btn-sm btn-danger delete-retour" data-id="<?= $r->id_retour ?>" title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:refresh-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucune demande de retour trouvée</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($retours)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$('.delete-retour').on('click', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Supprimer cette demande de retour ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("retours/delete/") ?>' + id,
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

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>