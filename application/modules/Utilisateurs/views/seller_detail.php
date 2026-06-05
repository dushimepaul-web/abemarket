<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <div class="row">
            <!-- Profil Vendeur -->
            <div class="col-lg-4">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="bg-primary profile-bg rounded-top p-5 position-relative mx-n3 mt-n3" style="height: 100px;">
                            <?php if($seller['logo_boutique']): ?>
                                <img src="<?= base_url($seller['logo_boutique']) ?>" alt="" class="avatar-lg border border-light border-3 rounded-circle position-absolute top-100 start-0 translate-middle ms-5" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="avatar-lg bg-light rounded-circle position-absolute top-100 start-0 translate-middle ms-5 d-flex align-items-center justify-content-center">
                                    <iconify-icon icon="solar:shop-bold-duotone" class="fs-34 text-primary"></iconify-icon>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4 pt-3 text-center">
                            <h4 class="mb-1"><?= $seller['nom_boutique'] ?></h4>
                            <p class="text-muted"><?= ucfirst($seller['type_vendeur']) ?></p>
                            <div class="mt-2">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="bx bxs-star <?= $i <= round($avg_rating ?? 0) ? 'text-warning' : 'text-muted' ?> fs-16"></i>
                                <?php endfor; ?>
                                <span class="text-muted ms-1">(<?= $total_reviews ?? 0 ?> avis)</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top gap-1 hstack">
                        <a href="<?= base_url('Sellers/edit/'.$seller['id_vendeur']) ?>" class="btn btn-primary w-100">Modifier</a>
                        <?php if($seller['statut'] == 'actif'): ?>
                            <a href="<?= base_url('Sellers/suspend/'.$seller['id_vendeur']) ?>" class="btn btn-warning w-100" onclick="return confirmSuspend(<?= $seller['id_vendeur'] ?>, '<?= addslashes($seller['nom_boutique']) ?>', 'suspend')">Suspendre</a>
                        <?php elseif($seller['statut'] == 'suspendu'): ?>
                            <a href="<?= base_url('Sellers/suspend/'.$seller['id_vendeur']) ?>" class="btn btn-success w-100">Réactiver</a>
                        <?php endif; ?>
                        <a href="#" class="btn btn-light w-100" onclick="window.print()">Imprimer</a>
                    </div>
                </div>

                <!-- Informations de la boutique -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informations de la boutique</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr><td class="px-0">ID Vendeur</td><td class="text-dark fw-medium px-0">#<?= $seller['id_vendeur'] ?></td></tr>
                                    <tr><td class="px-0">Nom boutique</td><td class="text-dark fw-medium px-0"><?= $seller['nom_boutique'] ?></td></tr>
                                    <tr><td class="px-0">Slug</td><td class="text-dark fw-medium px-0"><?= $seller['slug_boutique'] ?></td></tr>
                                    <tr><td class="px-0">Type</td><td class="text-dark fw-medium px-0"><?= ucfirst($seller['type_vendeur']) ?></td></tr>
                                    <?php if($seller['nom_entreprise']): ?>
                                    <tr><td class="px-0">Entreprise</td><td class="text-dark fw-medium px-0"><?= $seller['nom_entreprise'] ?></td></tr>
                                    <?php endif; ?>
                                    <?php if($seller['numero_nif']): ?>
                                    <tr><td class="px-0">NIF</td><td class="text-dark fw-medium px-0"><?= $seller['numero_nif'] ?></td></tr>
                                    <?php endif; ?>
                                    <?php if($seller['numero_rc']): ?>
                                    <tr><td class="px-0">Registre Commerce</td><td class="text-dark fw-medium px-0"><?= $seller['numero_rc'] ?></td></tr>
                                    <?php endif; ?>
                                    <tr><td class="px-0">Commission</td><td class="text-dark fw-medium px-0"><?= $seller['taux_commission'] ?>%</td></tr>
                                    <tr><td class="px-0">Délai paiement</td><td class="text-dark fw-medium px-0"><?= $seller['delai_paiement_jours'] ?> jours</td></tr>
                                    <tr><td class="px-0">Statut</td>
                                        <td class="text-dark fw-medium px-0">
                                            <?php if($seller['statut'] == 'actif'): ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php elseif($seller['statut'] == 'suspendu'): ?>
                                                <span class="badge bg-warning">Suspendu</span>
                                            <?php elseif($seller['statut'] == 'banni'): ?>
                                                <span class="badge bg-danger">Banni</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">En attente</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr><td class="px-0">Approbation</td>
                                        <td class="text-dark fw-medium px-0">
                                            <?php if($seller['est_approuve'] == 1): ?>
                                                <span class="badge bg-success">Approuvé</span>
                                                <?php if($seller['date_approbation']): ?>
                                                    <small class="d-block text-muted">le <?= date('d/m/Y H:i', strtotime($seller['date_approbation'])) ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-warning">En attente</span>
                                                <a href="<?= base_url('Sellers/approve/'.$seller['id_vendeur']) ?>" class="btn btn-sm btn-success mt-1">Approuver</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Localisation</h4>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($seller['province_name']) || !empty($seller['commune_name']) || !empty($seller['quartier_name'])): ?>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-map fs-20 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">
                                        <?php if(!empty($seller['province_name'])): ?>
                                            <?= $seller['province_name'] ?>
                                            <?php if(!empty($seller['commune_name'])): ?> → <?= $seller['commune_name'] ?><?php endif; ?>
                                            <?php if(!empty($seller['quartier_name'])): ?> → <?= $seller['quartier_name'] ?><?php endif; ?>
                                        <?php else: ?>
                                            Non renseignée
                                        <?php endif; ?>
                                    </h6>
                                    <small class="text-muted">Adresse géographique</small>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if(!empty($seller['latitude']) && !empty($seller['longitude'])): ?>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-current-location fs-20 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?= $seller['latitude'] ?>, <?= $seller['longitude'] ?></h6>
                                    <small class="text-muted">Coordonnées GPS</small>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div id="sellerMap" style="height: 200px; border-radius: 8px;"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contact -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Contact</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                <i class="bx bx-user fs-20 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?= $seller['prenom'] . ' ' . $seller['nom'] ?></h6>
                                <small class="text-muted">Nom du responsable</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                <i class="bx bx-envelope fs-20 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?= $seller['email'] ?></h6>
                                <small class="text-muted">Email</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                <i class="bx bx-phone fs-20 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?= $seller['telephone'] ?? '-' ?></h6>
                                <small class="text-muted">Téléphone</small>
                            </div>
                        </div>
                        <?php if($seller['whatsapp']): ?>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                <i class="bx bxl-whatsapp fs-20 text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?= $seller['whatsapp'] ?></h6>
                                <small class="text-muted">WhatsApp</small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Configuration Paiement -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Configuration paiement</h4>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($payment_config)): ?>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-credit-card fs-20 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?= ucfirst($payment_config['methode_principale']) ?></h6>
                                    <small class="text-muted">Méthode principale</small>
                                </div>
                            </div>
                            <?php if($payment_config['methode_principale'] == 'mobile_money'): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                        <i class="bx bx-mobile-alt fs-20 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= $payment_config['numero_mobile_money'] ?? '-' ?></h6>
                                        <small class="text-muted">Numéro Mobile Money</small>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                        <i class="bx bx-building fs-20 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?= $payment_config['nom_banque'] ?? '-' ?></h6>
                                        <small class="text-muted">Banque</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0 text-center">Configuration de paiement non renseignée</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Documents -->
                <?php if(!empty($documents)): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Documents</h4>
                    </div>
                    <div class="card-body">
                        <?php foreach($documents as $doc): ?>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar-sm bg-light rounded d-flex align-items-center justify-content-center">
                                    <i class="bx bx-file fs-20 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0"><?= str_replace('_', ' ', ucfirst($doc['type_document'])) ?></h6>
                                            <small class="text-muted"><?= date('d/m/Y', strtotime($doc['date_upload'])) ?></small>
                                        </div>
                                        <div>
                                            <?php if($doc['statut_verification'] == 'verifie'): ?>
                                                <span class="badge bg-success">Vérifié</span>
                                            <?php elseif($doc['statut_verification'] == 'refuse'): ?>
                                                <span class="badge bg-danger">Refusé</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">En attente</span>
                                            <?php endif; ?>
                                            <a href="<?= base_url($doc['fichier_document']) ?>" class="btn btn-sm btn-light" target="_blank">
                                                <i class="bx bx-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Statistiques et Commandes -->
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="card-title mb-2">Produits</h4>
                                        <p class="text-muted fw-medium fs-22 mb-0"><?= $total_products ?? 0 ?></p>
                                    </div>
                                    <div>
                                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                            <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="card-title mb-2">Commandes</h4>
                                        <p class="text-muted fw-medium fs-22 mb-0"><?= $total_orders ?? 0 ?></p>
                                    </div>
                                    <div>
                                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                            <iconify-icon icon="solar:bag-smile-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="card-title mb-2">Clients uniques</h4>
                                        <p class="text-muted fw-medium fs-22 mb-0"><?= $total_customers ?? 0 ?></p>
                                    </div>
                                    <div>
                                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                            <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solde -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Solde du vendeur</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <p class="text-muted mb-1">Disponible</p>
                                <h3 class="text-success"><?= number_format($solde['solde_disponible'] ?? 0, 0, ',', ' ') ?> BIF</h3>
                            </div>
                            <div class="col-4">
                                <p class="text-muted mb-1">En attente</p>
                                <h3 class="text-warning"><?= number_format($solde['solde_en_attente'] ?? 0, 0, ',', ' ') ?> BIF</h3>
                            </div>
                            <div class="col-4">
                                <p class="text-muted mb-1">Total gagné</p>
                                <h3 class="text-primary"><?= number_format($solde['total_gagne'] ?? 0, 0, ',', ' ') ?> BIF</h3>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="mb-0">
                                    Commission totale: <strong><?= number_format($total_commission ?? 0, 0, ',', ' ') ?> BIF</strong>
                                    | Revenu net: <strong><?= number_format($net_revenue ?? 0, 0, ',', ' ') ?> BIF</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historique des paiements -->
                <?php if(!empty($payments_history)): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Historique des paiements</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Période</th><th>Commandes</th><th>Montant net</th><th>Statut</th><th>Date paiement</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($payments_history as $payment): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($payment['date_debut_periode'])) ?> - <?= date('d/m/Y', strtotime($payment['date_fin_periode'])) ?></td>
                                            <td><?= $payment['nombre_commandes'] ?></td>
                                            <td><?= number_format($payment['montant_net'], 0, ',', ' ') ?> BIF</td>
                                            <td>
                                                <?php if($payment['statut'] == 'paye'): ?>
                                                    <span class="badge bg-success">Payé</span>
                                                <?php elseif($payment['statut'] == 'en_attente'): ?>
                                                    <span class="badge bg-warning">En attente</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?= ucfirst($payment['statut']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $payment['date_paiement'] ? date('d/m/Y', strtotime($payment['date_paiement'])) : '-' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Derniers produits -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Derniers produits</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Produit</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Statut</th><th>Date</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($products)): ?>
                                        <?php foreach($products as $product): ?>
                                        <tr>
                                            <td><?= $product['nom_produit'] ?></td>
                                            <td><?= $product['nom_categorie'] ?? '-' ?></td>
                                            <td><?= number_format($product['prix_base'], 0, ',', ' ') ?> BIF</td>
                                            <td><?= $product['quantite_actuelle'] ?></td>
                                            <td>
                                                <?php if($product['statut'] == 'actif'): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?= ucfirst($product['statut']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($product['date_creation'])) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center">Aucun produit</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Distribution des notes -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Distribution des notes</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <h2 class="display-4"><?= number_format($avg_rating ?? 0, 1) ?></h2>
                                <div>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bx bxs-star <?= $i <= round($avg_rating ?? 0) ? 'text-warning' : 'text-muted' ?> fs-20"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-muted">Basé sur <?= $total_reviews ?? 0 ?> avis</p>
                            </div>
                            <div class="col-md-6">
                                <?php for($star = 5; $star >= 1; $star--): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="me-2"><?= $star ?> <i class="bx bxs-star text-warning"></i></span>
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                 style="width: <?= ($total_reviews > 0 ? ($rating_distribution[$star] / $total_reviews) * 100 : 0) ?>%"></div>
                                        </div>
                                        <span class="ms-2 text-muted"><?= $rating_distribution[$star] ?? 0 ?></span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dernières commandes -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Dernières commandes</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>N° Commande</th><th>Client</th><th>Produit</th><th>Quantité</th><th>Total</th><th>Commission</th><th>Statut</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($orders)): ?>
                                        <?php foreach($orders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['numero_commande'] ?></td>
                                            <td><?= $order['prenom'] . ' ' . $order['nom'] ?></td>
                                            <td><?= $order['nom_produit'] ?></td>
                                            <td><?= $order['quantite'] ?></td>
                                            <td><?= number_format($order['prix_total'], 0, ',', ' ') ?> BIF</td>
                                            <td><?= number_format($order['montant_commission'], 0, ',', ' ') ?> BIF</td>
                                            <td>
                                                <?php
                                                $status_class = match($order['statut_article']) {
                                                    'livre' => 'success',
                                                    'en_attente' => 'warning',
                                                    'expedie' => 'primary',
                                                    'annule' => 'danger',
                                                    default => 'secondary'
                                                };
                                                ?>
                                                <span class="badge bg-<?= $status_class ?>"><?= ucfirst($order['statut_article']) ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="7" class="text-center">Aucune commande</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="<?= base_url('Sellers') ?>" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>

    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <script>document.write(new Date().getFullYear())</script> &copy; ABEMARKET - Plateforme e-commerce
                </div>
            </div>
        </div>
    </footer>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
// Carte pour la localisation
<?php if(!empty($seller['latitude']) && !empty($seller['longitude'])): ?>
    var map = L.map('sellerMap').setView([<?= $seller['latitude'] ?>, <?= $seller['longitude'] ?>], 15);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    L.marker([<?= $seller['latitude'] ?>, <?= $seller['longitude'] ?>])
        .addTo(map)
        .bindPopup('<strong><?= addslashes($seller['nom_boutique']) ?></strong><br><?= addslashes($seller['province_name'] ?? '') ?>');
<?php endif; ?>

// Confirmation de suspension
function confirmSuspend(id, name, action) {
    const actionText = action === 'suspend' ? 'suspendre' : 'réactiver';
    const actionIcon = action === 'suspend' ? 'warning' : 'info';
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez " + actionText + " le vendeur '" + name + "'.",
        icon: actionIcon,
        showCancelButton: true,
        confirmButtonColor: action === 'suspend' ? '#d33' : '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, ' + actionText,
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Sellers/suspend") ?>/' + id;
        }
    });
    return false;
}

// Impression
window.onload = function() {
    if(window.location.hash === '#print') {
        window.print();
    }
}
</script>

<style>
.hstack {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.profile-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
#sellerMap {
    height: 200px;
    border-radius: 8px;
}
@media print {
    .sidebar, .top-header, .footer, .card-footer, .btn, .dropdown-menu {
        display: none !important;
    }
    .page-content {
        margin: 0 !important;
        padding: 0 !important;
    }
    .col-lg-4, .col-lg-8 {
        width: 100% !important;
    }
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>