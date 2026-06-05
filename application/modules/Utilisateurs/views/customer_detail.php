<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <div class="row">
            <!-- Profil Client -->
            <div class="col-lg-4">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="bg-primary profile-bg rounded-top p-5 position-relative mx-n3 mt-n3" style="height: 100px;">
                            <?php if($customer['avatar_url']): ?>
                                <img src="<?= base_url($customer['avatar_url']) ?>" alt="" class="avatar-lg border border-light border-3 rounded-circle position-absolute top-100 start-0 translate-middle ms-5">
                            <?php else: ?>
                                <div class="avatar-lg bg-light rounded-circle position-absolute top-100 start-0 translate-middle ms-5 d-flex align-items-center justify-content-center">
                                    <span class="fs-34 fw-bold text-primary"><?= strtoupper(substr($customer['prenom'], 0, 1)) . strtoupper(substr($customer['nom'], 0, 1)) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4 pt-3">
                            <h4 class="mb-1"><?= $customer['prenom'] . ' ' . $customer['nom'] ?> <i class="bx bxs-badge-check text-success align-middle"></i></h4>
                            <div class="mt-2">
                                <p class="fs-15 mb-1 mt-1"><span class="text-dark fw-semibold">Email : </span> <?= $customer['email'] ?></p>
                                <p class="fs-15 mb-0 mt-1"><span class="text-dark fw-semibold">Téléphone : </span> <?= $customer['telephone'] ?? 'Non renseigné' ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top gap-1 hstack">
                        <a href="<?= base_url('Customers/edit/'.$customer['id_utilisateur']) ?>" class="btn btn-primary w-100">Modifier</a>
                        <a href="#" class="btn btn-light w-100" onclick="window.print()">Imprimer</a>
                    </div>
                </div>

                <!-- Informations détaillées -->
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">Détails du client</h4>
                        </div>
                        <div>
                            <span class="badge <?= $customer['est_actif'] == 1 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> px-2 py-1">
                                <?= $customer['est_actif'] == 1 ? 'Actif' : 'Inactif' ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body py-2">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr><td class="px-0">ID Client</td><td class="text-dark fw-medium px-0">#<?= $customer['id_utilisateur'] ?></td></tr>
                                    <tr><td class="px-0">Email</td><td class="text-dark fw-medium px-0"><?= $customer['email'] ?></td></tr>
                                    <tr><td class="px-0">Téléphone</td><td class="text-dark fw-medium px-0"><?= $customer['telephone'] ?? '-' ?></td></tr>
                                    <tr><td class="px-0">Email vérifié</td><td class="text-dark fw-medium px-0"><?= $customer['email_verifie'] ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-warning">Non</span>' ?></td></tr>
                                    <tr><td class="px-0">Date d'inscription</td><td class="text-dark fw-medium px-0"><?= date('d/m/Y H:i', strtotime($customer['date_creation'])) ?></td></tr>
                                    <tr><td class="px-0">Dernière connexion</td><td class="text-dark fw-medium px-0"><?= $customer['derniere_connexion'] ? date('d/m/Y H:i', strtotime($customer['derniere_connexion'])) : 'Jamais' ?></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Adresses -->
                <?php if(!empty($addresses)): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Adresses de livraison</h4>
                    </div>
                    <div class="card-body">
                        <?php foreach($addresses as $address): ?>
                        <div class="d-flex p-2 rounded align-items-start gap-2 bg-light-subtle mb-2">
                            <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded-circle">
                                <iconify-icon icon="solar:map-point-bold" class="text-primary fs-3"></iconify-icon>
                            </div>
                            <div class="d-block flex-grow-1">
                                <p class="text-dark fw-medium mb-0"><?= ucfirst($address['type_adresse']) ?></p>
                                <p class="text-muted mb-0 fs-13"><?= $address['adresse_ligne'] ?? 'Adresse non renseignée' ?></p>
                                <p class="text-muted mb-0 fs-13">Tél: <?= $address['telephone'] ?? '-' ?></p>
                            </div>
                            <?php if($address['est_par_defaut']): ?>
                                <span class="badge bg-success">Par défaut</span>
                            <?php endif; ?>
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
                                        <h4 class="card-title mb-2">Commandes</h4>
                                        <p class="text-muted fw-medium fs-22 mb-0"><?= $total_orders ?? 0 ?></p>
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
                                        <h4 class="card-title mb-2">Dépenses totales</h4>
                                        <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($total_spent ?? 0, 0, ',', ' ') ?> BIF</p>
                                    </div>
                                    <div>
                                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                            <iconify-icon icon="solar:wallet-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
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
                                        <h4 class="card-title mb-2">Points fidélité</h4>
                                        <p class="text-muted fw-medium fs-22 mb-0">0</p>
                                    </div>
                                    <div>
                                        <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                            <iconify-icon icon="solar:cup-star-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historique des commandes -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Historique des commandes</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($orders)): ?>
                                        <?php foreach($orders as $order): ?>
                                        <tr>
                                            <td><a href="#" class="text-body">#<?= $order['numero_commande'] ?></a></td>
                                            <td><?= date('d/m/Y', strtotime($order['date_creation'])) ?></td>
                                            <td><?= number_format($order['montant_total'], 0, ',', ' ') ?> BIF</td>
                                            <td>
                                                <?php
                                                $status_class = '';
                                                switch($order['statut_commande']) {
                                                    case 'livre': $status_class = 'success'; break;
                                                    case 'en_attente': $status_class = 'warning'; break;
                                                    case 'confirme': $status_class = 'info'; break;
                                                    case 'expedie': $status_class = 'primary'; break;
                                                    case 'annule': $status_class = 'danger'; break;
                                                    default: $status_class = 'secondary';
                                                }
                                                ?>
                                                <span class="badge bg-<?= $status_class ?>-subtle text-<?= $status_class ?> py-1 px-2"><?= ucfirst($order['statut_commande']) ?></span>
                                             </td>
                                            <td>
                                                <a href="<?= base_url('Commande/details/'.$order['id_commande']) ?>" class="btn btn-light btn-sm">
                                                    <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                </a>
                                             </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Aucune commande trouvée</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if(count($orders) > 5): ?>
                    <div class="card-footer border-top">
                        <ul class="pagination pagination-rounded m-0 justify-content-end">
                            <li class="page-item"><a href="#" class="page-link"><i class="bx bx-left-arrow-alt"></i></a></li>
                            <li class="page-item active"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                            <li class="page-item"><a href="#" class="page-link">3</a></li>
                            <li class="page-item"><a href="#" class="page-link"><i class="bx bx-right-arrow-alt"></i></a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Derniers avis -->
                <?php if(!empty($reviews)): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Derniers avis</h4>
                    </div>
                    <div class="card-body">
                        <?php foreach($reviews as $review): ?>
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <div class="avatar bg-light d-flex align-items-center justify-content-center rounded-circle">
                                <i class="bx bx-star fs-30 text-warning"></i>
                            </div>
                            <div class="d-block flex-grow-1">
                                <h5 class="mb-1"><?= $review['titre'] ?? 'Avis' ?></h5>
                                <div class="mb-1">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bx bxs-star <?= $i <= $review['note'] ? 'text-warning' : 'text-muted' ?> fs-14"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="mb-0"><?= substr($review['commentaire'], 0, 100) ?>...</p>
                                <small class="text-muted">Produit: <?= $review['nom_produit'] ?> - <?= date('d/m/Y', strtotime($review['date_creation'])) ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="<?= base_url('Customers') ?>" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>

    </div>

    
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>