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
                            <iconify-icon icon="solar:heart-bold-duotone" class="me-2"></iconify-icon>
                            Détail du souhait #<?= $souhait->id_souhait ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('liste-souhaits/admin-list') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" onclick="supprimer(<?= $souhait->id_souhait ?>)">
                                <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                Supprimer
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Informations utilisateur -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:user-bold-duotone" class="me-2"></iconify-icon>Informations utilisateur</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="35%"><strong>Nom complet :</strong></td>
                                                <td><?= htmlspecialchars($souhait->utilisateur_nom) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email :</strong></td>
                                                <td><?= htmlspecialchars($souhait->utilisateur_email) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Téléphone :</strong></td>
                                                <td><?= $souhait->utilisateur_telephone ?? '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>ID Utilisateur :</strong></td>
                                                <td><?= $souhait->id_utilisateur ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations produit -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:box-bold-duotone" class="me-2"></iconify-icon>Informations produit</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="35%"><strong>Nom :</strong></td>
                                                <td>
                                                    <a href="<?= base_url('produits/detail/' . $souhait->produit_sku) ?>">
                                                        <?= htmlspecialchars($souhait->produit_nom) ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>SKU :</strong></td>
                                                <td><?= $souhait->produit_sku ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Catégorie :</strong></td>
                                                <td><?= $souhait->nom_categorie ?? '-' ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Prix de base :</strong></td>
                                                <td><?= number_format($souhait->prix_base, 0, ',', ' ') ?> FBu</td>
                                            </tr>
                                            <?php if ($souhait->prix_promo): ?>
                                            <tr>
                                                <td><strong>Prix promotion :</strong></td>
                                                <td><?= number_format($souhait->prix_promo, 0, ',', ' ') ?> FBu</td>
                                            </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date d'ajout -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:calendar-bold-duotone" class="me-2"></iconify-icon>Date d'ajout</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center p-3">
                                            <div class="fs-24 text-primary">
                                                <iconify-icon icon="solar:clock-circle-bold-duotone"></iconify-icon>
                                            </div>
                                            <strong>Ajouté le :</strong>
                                            <p class="mb-0"><?= date('d/m/Y à H:i:s', strtotime($souhait->date_ajout)) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function supprimer(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce souhait ?')) {
        window.location.href = '<?= base_url("liste-souhaits/supprimer/") ?>' + id;
    }
}
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>