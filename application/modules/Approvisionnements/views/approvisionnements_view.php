<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Détails de l'approvisionnement #<?= $appro->id_appro ?></h4>
                        <a href="<?= base_url('approvisionnements') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Informations produit</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="150"><strong>Produit :</strong></td>
                                                <td><?= htmlspecialchars($appro->nom_produit) ?> (<?= htmlspecialchars($appro->sku) ?>)</div>
                                            </tr>
                                            <tr>
                                                <td><strong>Variante :</strong></td>
                                                <td>                                                    <?php if ($appro->variante_sku): ?>
                                                        <span class="badge bg-info"><?= htmlspecialchars($appro->variante_sku) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Standard</span>
                                                    <?php endif; ?>
                                                 </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Prix de vente :</strong></td>
                                                <td><?= number_format($appro->prix_base ?? 0, 0, ',', ' ') ?> FBu</div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Informations approvisionnement</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="150"><strong>Date :</strong></div>
                                                <td><?= date('d/m/Y', strtotime($appro->date_appro)) ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Quantité commandée :</strong></div>
                                                <td><?= number_format($appro->quantite_initiale) ?> unités</div>
                                            </tr>
                                            <tr>
                                                <td><strong>Quantité reçue :</strong></div>
                                                <td><?= number_format($appro->quantite_recue) ?> unités</div>
                                            </tr>
                                            <tr>
                                                <td><strong>Stock après :</strong></div>
                                                <td><?= number_format($appro->quantite_apres) ?> unités</div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Informations financières</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="150"><strong>Prix unitaire :</strong></div>
                                                <td><?= number_format($appro->prix_achat_unitaire, 0, ',', ' ') ?> FBu</div>
                                            </tr>
                                            <tr>
                                                <td><strong>Coût total :</strong></div>
                                                <td><strong class="text-primary"><?= number_format($appro->cout_total, 0, ',', ' ') ?> FBu</strong></div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Informations fournisseur</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="150"><strong>Fournisseur :</strong></div>
                                                <td><?= htmlspecialchars($appro->fournisseur ?? '-') ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Référence bon :</strong></div>
                                                <td><?= htmlspecialchars($appro->reference_bon ?? '-') ?></div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($appro->note): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Note</h6>
                                        <p class="mb-0"><?= nl2br(htmlspecialchars($appro->note)) ?></p>
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

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>