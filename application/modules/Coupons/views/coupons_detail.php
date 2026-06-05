<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:ticket-bold-duotone" class="me-2"></iconify-icon>
                            Détails du coupon : <strong><?= htmlspecialchars($coupon->code) ?></strong>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('coupons/edit/' . $coupon->id_coupon) ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:pen-2-broken"></iconify-icon> Modifier
                            </a>
                            <a href="<?= base_url('coupons') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- En-tête avec statut -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <?php 
                                $est_valide = ($coupon->est_actif && (!$coupon->date_fin || strtotime($coupon->date_fin) > time()));
                                $statut_color = $est_valide ? 'success' : ($coupon->est_actif ? 'warning' : 'secondary');
                                $statut_texte = $est_valide ? 'Actif et valide' : ($coupon->est_actif ? 'Actif mais expiré' : 'Inactif');
                                ?>
                                <div class="alert alert-<?= $statut_color ?> d-flex align-items-center">
                                    <iconify-icon icon="solar:info-circle-bold-duotone" class="fs-24 me-3"></iconify-icon>
                                    <div>
                                        <strong>Statut du coupon : <?= $statut_texte ?></strong>
                                        <?php if (!$est_valide && $coupon->est_actif): ?>
                                            <br>Ce coupon a expiré le <?= date('d/m/Y', strtotime($coupon->date_fin)) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Informations générales -->
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">📋 Informations générales</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="180"><strong>Code :</strong></td>
                                                <td>
                                                    <span class="badge bg-dark fs-14 p-2"><?= htmlspecialchars($coupon->code) ?></span>
                                                    <button class="btn btn-sm btn-outline-secondary ms-2" id="btnCopierCode" data-code="<?= $coupon->code ?>">
                                                        <iconify-icon icon="solar:copy-bold-duotone"></iconify-icon> Copier
                                                    </button>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Description :</strong></td>
                                                <td><?= htmlspecialchars($coupon->description ?? 'Aucune description') ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Date de création :</strong></td>
                                                <td><?= date('d/m/Y à H:i', strtotime($coupon->date_creation)) ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Dernière modification :</strong></td>
                                                <td><?= date('d/m/Y à H:i', strtotime($coupon->date_modification ?? $coupon->date_creation)) ?></div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations sur la réduction -->
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">💰 Informations sur la réduction</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="180"><strong>Type de réduction :</strong></td>
                                                <td>
                                                    <?php
                                                    $type_icon = '';
                                                    $type_text = '';
                                                    switch($coupon->type_reduction) {
                                                        case 'pourcentage':
                                                            $type_icon = '💰';
                                                            $type_text = 'Pourcentage (%)';
                                                            break;
                                                        case 'montant_fixe':
                                                            $type_icon = '💵';
                                                            $type_text = 'Montant fixe (FBu)';
                                                            break;
                                                        case 'livraison_gratuite':
                                                            $type_icon = '🚚';
                                                            $type_text = 'Livraison gratuite';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge bg-info"><?= $type_icon ?> <?= $type_text ?></span>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Valeur :</strong></td>
                                                <td>
                                                    <?php if ($coupon->type_reduction == 'pourcentage'): ?>
                                                        <strong class="text-primary fs-4"><?= $coupon->valeur_reduction ?>%</strong>
                                                        <?php if ($coupon->montant_max_reduction): ?>
                                                            <br><small class="text-muted">Max: <?= number_format($coupon->montant_max_reduction, 0, ',', ' ') ?> FBu</small>
                                                        <?php endif; ?>
                                                    <?php elseif ($coupon->type_reduction == 'montant_fixe'): ?>
                                                        <strong class="text-primary fs-4"><?= number_format($coupon->valeur_reduction, 0, ',', ' ') ?> FBu</strong>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Livraison offerte</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Montant minimum :</strong></td>
                                                <td>
                                                    <?php if ($coupon->montant_min_achat): ?>
                                                        <?= number_format($coupon->montant_min_achat, 0, ',', ' ') ?> FBu
                                                    <?php else: ?>
                                                        <span class="text-muted">Aucun minimum</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Application :</strong></td>
                                                <td>
                                                    <?php
                                                    $app_text = '';
                                                    switch($coupon->applicable_a) {
                                                        case 'tout': $app_text = 'Tous les produits'; break;
                                                        case 'categories': $app_text = 'Catégories spécifiques'; break;
                                                        case 'produits': $app_text = 'Produits spécifiques'; break;
                                                        case 'vendeurs': $app_text = 'Vendeurs spécifiques'; break;
                                                    }
                                                    ?>
                                                    <?= $app_text ?>
                                                    <?php if ($coupon->ids_applicables): 
                                                        $ids = json_decode($coupon->ids_applicables, true);
                                                        if (!empty($ids)): ?>
                                                            <br><small class="text-muted"><?= count($ids) ?> élément(s) ciblé(s)</small>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Période de validité -->
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">⏰ Période de validité</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="180"><strong>Date de début :</strong></td>
                                                <td>
                                                    <?= date('d/m/Y à H:i', strtotime($coupon->date_debut)) ?>
                                                    <?php if (strtotime($coupon->date_debut) > time()): ?>
                                                        <span class="badge bg-info ms-2">À venir</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Date de fin :</strong></td>
                                                <td>
                                                    <?= date('d/m/Y à H:i', strtotime($coupon->date_fin)) ?>
                                                    <?php if (strtotime($coupon->date_fin) < time()): ?>
                                                        <span class="badge bg-danger ms-2">Expiré</span>
                                                    <?php elseif (strtotime($coupon->date_fin) - time() < 7 * 24 * 3600): ?>
                                                        <span class="badge bg-warning ms-2">Expire bientôt</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Jours restants :</strong></td>
                                                <td>
                                                    <?php
                                                    $jours_restants = ceil((strtotime($coupon->date_fin) - time()) / (24 * 3600));
                                                    if ($jours_restants > 0):
                                                    ?>
                                                        <strong class="text-success"><?= $jours_restants ?> jour(s)</strong>
                                                    <?php else: ?>
                                                        <span class="text-danger">Expiré</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistiques d'utilisation -->
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">📊 Statistiques d'utilisation</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="180"><strong>Nombre d'utilisations :</strong></td>
                                                <td>
                                                    <strong class="fs-4"><?= number_format($coupon->nombre_utilisations) ?></strong>
                                                    <?php if ($coupon->limite_utilisation): ?>
                                                        / <?= number_format($coupon->limite_utilisation) ?>
                                                        <div class="progress mt-2" style="height: 8px;">
                                                            <div class="progress-bar bg-primary" style="width: <?= ($coupon->nombre_utilisations / $coupon->limite_utilisation) * 100 ?>%"></div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Limite par utilisateur :</strong></td>
                                                <td><?= $coupon->limite_par_utilisateur ?> fois maximum</div>
                                            </tr>
                                            <tr>
                                                <td><strong>Taux d'utilisation :</strong></td>
                                                <td>
                                                    <?php if ($coupon->limite_utilisation): ?>
                                                        <?= round(($coupon->nombre_utilisations / $coupon->limite_utilisation) * 100, 1) ?>%
                                                    <?php else: ?>
                                                        Illimité
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Éléments applicables (si spécifiques) -->
                        <?php if ($coupon->applicable_a != 'tout' && $coupon->ids_applicables): 
                            $ids = json_decode($coupon->ids_applicables, true);
                            if (!empty($ids)):
                        ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">🎯 Éléments applicables</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php
                                            $type = $coupon->applicable_a;
                                            if ($type == 'categories') {
                                                $items = $this->db->where_in('id_categorie', $ids)->get('categories')->result();
                                                foreach ($items as $item): ?>
                                                    <div class="col-md-3 mb-2">
                                                        <span class="badge bg-primary p-2 w-100">📁 <?= htmlspecialchars($item->nom_categorie) ?></span>
                                                    </div>
                                                <?php endforeach;
                                            } elseif ($type == 'produits') {
                                                $items = $this->db->where_in('id_produit', $ids)->get('produits')->result();
                                                foreach ($items as $item): ?>
                                                    <div class="col-md-3 mb-2">
                                                        <span class="badge bg-info p-2 w-100">📦 <?= htmlspecialchars($item->nom_produit) ?></span>
                                                    </div>
                                                <?php endforeach;
                                            } elseif ($type == 'vendeurs') {
                                                $items = $this->db->where_in('id_vendeur', $ids)->get('vendeurs')->result();
                                                foreach ($items as $item): ?>
                                                    <div class="col-md-3 mb-2">
                                                        <span class="badge bg-warning p-2 w-100">🏪 <?= htmlspecialchars($item->nom_boutique) ?></span>
                                                    </div>
                                                <?php endforeach;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; endif; ?>

                        <!-- Exemple d'utilisation -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-dark text-white">
                                        <h6 class="mb-0">📝 Exemple d'utilisation</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-secondary">
                                            <strong>🔗 URL d'application :</strong>
                                            <code><?= base_url('panier/appliquer-coupon?code=' . $coupon->code) ?></code>
                                        </div>
                                        <div class="alert alert-success">
                                            <strong>✅ Exemple de calcul :</strong><br>
                                            <?php
                                            $montant_test = 50000;
                                            $reduction = 0;
                                            if ($coupon->type_reduction == 'pourcentage') {
                                                $reduction = ($montant_test * $coupon->valeur_reduction) / 100;
                                                if ($coupon->montant_max_reduction && $reduction > $coupon->montant_max_reduction) {
                                                    $reduction = $coupon->montant_max_reduction;
                                                }
                                            } elseif ($coupon->type_reduction == 'montant_fixe') {
                                                $reduction = $coupon->valeur_reduction;
                                                if ($reduction > $montant_test) {
                                                    $reduction = $montant_test;
                                                }
                                            } else {
                                                $reduction = 0;
                                            }
                                            ?>
                                            Pour un panier de <strong><?= number_format($montant_test, 0, ',', ' ') ?> FBu</strong>, 
                                            la réduction serait de <strong class="text-success"><?= number_format($reduction, 0, ',', ' ') ?> FBu</strong>.<br>
                                            Total après réduction : <strong><?= number_format($montant_test - $reduction, 0, ',', ' ') ?> FBu</strong>
                                            <?php if ($coupon->type_reduction == 'livraison_gratuite'): ?>
                                                <br>➕ La livraison est offerte !
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2 mt-3">
                            <a href="<?= base_url('coupons/edit/' . $coupon->id_coupon) ?>" class="btn btn-primary">
                                <iconify-icon icon="solar:pen-2-broken"></iconify-icon> Modifier le coupon
                            </a>
                            <button type="button" class="btn btn-danger" id="btnSupprimer" data-id="<?= $coupon->id_coupon ?>" data-code="<?= htmlspecialchars($coupon->code) ?>">
                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon> Supprimer
                            </button>
                            <?php if ($coupon->est_actif): ?>
                                <button type="button" class="btn btn-warning" id="btnDesactiver" data-id="<?= $coupon->id_coupon ?>">
                                    <iconify-icon icon="solar:eye-closed-bold-duotone"></iconify-icon> Désactiver
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-success" id="btnActiver" data-id="<?= $coupon->id_coupon ?>">
                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon> Activer
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
// Copier le code
$('#btnCopierCode').on('click', function() {
    const code = $(this).data('code');
    navigator.clipboard.writeText(code).then(function() {
        Swal.fire('Copié !', 'Code ' + code + ' copié dans le presse-papier', 'success');
    });
});

// Supprimer
$('#btnSupprimer').on('click', function() {
    const id = $(this).data('id');
    const code = $(this).data('code');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: `Vous allez supprimer le coupon "${code}". Cette action est irréversible !`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("coupons/delete/") ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Supprimé!', response.message, 'success').then(() => {
                            window.location.href = '<?= base_url("coupons") ?>';
                        });
                    } else {
                        Swal.fire('Erreur!', response.message, 'error');
                    }
                }
            });
        }
    });
});

// Désactiver
$('#btnDesactiver').on('click', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous désactiver ce coupon ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("coupons/toggle_status/") ?>' + id,
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

// Activer
$('#btnActiver').on('click', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous activer ce coupon ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("coupons/toggle_status/") ?>' + id,
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
</script>

<style>
.bg-dark.bg-opacity-10 {
    background-color: rgba(0,0,0,0.1);
}
.fs-14 {
    font-size: 14px;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>