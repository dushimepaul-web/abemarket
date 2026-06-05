<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Gestion des Profils</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Profils</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md bg-soft-primary rounded">
                                <i class="bx bx-group avatar-title fs-24 text-primary"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <p class="text-muted mb-1">Total Profils</p>
                                <h3 class="mb-0"><?= isset($total_profils) ? $total_profils : 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md bg-soft-success rounded">
                                <i class="bx bx-user-check avatar-title fs-24 text-success"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <p class="text-muted mb-1">Utilisateurs</p>
                                <h3 class="mb-0"><?= isset($total_utilisateurs) ? $total_utilisateurs : 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <a href="<?= base_url('Profils/add') ?>" class="btn btn-primary">
                                    <i class="bx bx-plus me-1"></i>Nouveau Profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire d'ajout/modification -->
        <?php if(isset($profil) || $this->uri->segment(2) == 'add'): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?= isset($profil) ? 'Modifier le profil' : 'Ajouter un nouveau profil' ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= isset($profil) ? base_url('Profils/edit/'.$profil['id_profil']) : base_url('Profils/add') ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="description" name="description" 
                                               value="<?= isset($profil) ? $profil['description'] : '' ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="est_actif" class="form-label">Statut</label>
                                        <select class="form-select" id="est_actif" name="est_actif">
                                            <option value="1" <?= (isset($profil) && $profil['est_actif'] == 1) ? 'selected' : '' ?>>Actif</option>
                                            <option value="0" <?= (isset($profil) && $profil['est_actif'] == 0) ? 'selected' : '' ?>>Inactif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Permissions</label>
                                <div class="row">
                                    <?php
                                    $all_permissions = [
                                        'viewDashboard' => 'Voir le tableau de bord',
                                        'manageUtilisateurs' => 'Gérer les utilisateurs',
                                        'manageProfils' => 'Gérer les profils',
                                        'manageProduits' => 'Gérer les produits',
                                        'manageCommandes' => 'Gérer les commandes',
                                        'manageVendeurs' => 'Gérer les vendeurs',
                                        'manageCategories' => 'Gérer les catégories',
                                        'manageCoupons' => 'Gérer les coupons',
                                        'managePaiements' => 'Gérer les paiements',
                                        'manageLivraisons' => 'Gérer les livraisons',
                                        'manageLitiges' => 'Gérer les litiges',
                                        'manageTransporteurs' => 'Gérer les transporteurs',
                                        'viewRapports' => 'Voir les rapports',
                                        'validateKyc' => 'Valider KYC',
                                        'manageAvis' => 'Gérer les avis',
                                        'manageMesProduits' => 'Gérer mes produits',
                                        'viewMesCommandes' => 'Voir mes commandes',
                                        'manageMesApprovisionnements' => 'Gérer mes approvisionnements',
                                        'viewMesSoldes' => 'Voir mes soldes',
                                        'manageMaBoutique' => 'Gérer ma boutique',
                                        'viewProduits' => 'Voir les produits',
                                        'manageMonPanier' => 'Gérer mon panier',
                                        'manageMesAvis' => 'Gérer mes avis',
                                        'viewMesNotifications' => 'Voir mes notifications',
                                        'viewMesLivraisons' => 'Voir mes livraisons',
                                        'updateStatutLivraison' => 'Mettre à jour statut livraison',
                                        'viewCarteGps' => 'Voir la carte GPS',
                                        'manageVirements' => 'Gérer les virements',
                                        'manageSoldesVendeurs' => 'Gérer les soldes vendeurs',
                                        'viewUtilisateurs' => 'Voir les utilisateurs',
                                        'manageRetours' => 'Gérer les retours'
                                    ];
                                    
                                    $current_permissions = [];
                                    if (isset($permissions_list)) {
                                        $current_permissions = $permissions_list;
                                    } elseif (isset($profil) && isset($profil['permissions'])) {
                                        $current_permissions = json_decode($profil['permissions'], true);
                                        if (!is_array($current_permissions)) {
                                            $current_permissions = [];
                                        }
                                    }
                                    ?>
                                    
                                    <?php foreach($all_permissions as $key => $label): ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" 
                                                   value="<?= $key ?>" id="perm_<?= $key ?>"
                                                   <?= (is_array($current_permissions) && in_array($key, $current_permissions)) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="perm_<?= $key ?>">
                                                <?= $label ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i><?= isset($profil) ? 'Mettre à jour' : 'Créer' ?>
                                </button>
                                <a href="<?= base_url('Profils') ?>" class="btn btn-secondary">
                                    <i class="bx bx-x me-1"></i>Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Liste des profils -->
        <?php 
        $segment2 = $this->uri->segment(2);
        if(!isset($profil) && $segment2 != 'add' && $segment2 != 'edit' && $segment2 != 'view'): 
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste des profils</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th>ID</th>
                                        <th>Description</th>
                                        <th>Utilisateurs</th>
                                        <th>Statut</th>
                                        <th>Date de création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($profils)): ?>
                                        <?php foreach($profils as $profil_item): ?>
                                        <tr>
                                            <td><?= $profil_item['id_profil'] ?></td>
                                            <td>
                                                <span class="fw-semibold"><?= ucfirst($profil_item['description']) ?></span>
                                                <?php if(in_array($profil_item['description'], ['super_admin', 'admin'])): ?>
                                                    <span class="badge bg-danger ms-1">Système</span>
                                                <?php endif; ?>
                                             </td>
                                             <td>
                                                <?php 
                                                $count = 0;
                                                if(isset($user_count_by_profil) && isset($user_count_by_profil[$profil_item['id_profil']])) {
                                                    $count = $user_count_by_profil[$profil_item['id_profil']];
                                                }
                                                echo $count;
                                                ?>
                                             </td>
                                             <td>
                                                <?php if($profil_item['est_actif'] == 1): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactif</span>
                                                <?php endif; ?>
                                             </td>
                                             <td><?= date('d/m/Y H:i', strtotime($profil_item['date_creation'])) ?></td>
                                             <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('Profils/view/'.$profil_item['id_profil']) ?>" class="btn btn-sm btn-info" title="Voir">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="<?= base_url('Profils/edit/'.$profil_item['id_profil']) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <?php if(!in_array($profil_item['description'], ['super_admin', 'admin'])): ?>
                                                        <button type="button" class="btn btn-sm btn-danger" title="Supprimer"
                                                                onclick="confirmDelete(<?= $profil_item['id_profil'] ?>, '<?= $profil_item['description'] ?>')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                             </td>
                                         </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun profil trouvé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Vue détaillée d'un profil -->
        <?php if($this->uri->segment(2) == 'view' && isset($profil)): ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informations du profil</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">ID</th>
                                <td><?= $profil['id_profil'] ?></td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td><?= ucfirst($profil['description']) ?></td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>
                                    <?php if($profil['est_actif'] == 1): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactif</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Nombre d'utilisateurs</th>
                                <td><?= isset($total_users) ? $total_users : 0 ?></td>
                            </tr>
                            <tr>
                                <th>Date de création</th>
                                <td><?= date('d/m/Y H:i:s', strtotime($profil['date_creation'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Permissions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if(!empty($permissions) && is_array($permissions)): ?>
                                <?php foreach($permissions as $perm): ?>
                                <div class="col-md-6 mb-2">
                                    <i class="bx bx-check-circle text-success me-1"></i>
                                    <?= str_replace(['manage', 'view', 'update', 'validate'], '', $perm) ?>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Aucune permission définie</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Utilisateurs avec ce profil</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom complet</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Statut</th>
                                        <th>Date d'inscription</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($users) && is_array($users)): ?>
                                        <?php foreach($users as $user): ?>
                                        <tr>
                                            <td><?= $user['id_utilisateur'] ?></td>
                                            <td><?= $user['prenom'] . ' ' . $user['nom'] ?></td>
                                            <td><?= $user['email'] ?></td>
                                            <td><?= $user['telephone'] ?? '-' ?></td>
                                            <td>
                                                <?php if($user['est_actif'] == 1): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($user['date_creation'])) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Aucun utilisateur avec ce profil</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer le profil '" + name + "'. Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Profils/delete") ?>/' + id;
        }
    })
}
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>