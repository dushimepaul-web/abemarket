<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Liste des Clients</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Clients</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">                                
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="mb-0">Total Clients</h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($total_customers ?? 0) ?></p>            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">                                
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="mb-0">Commandes</h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($total_orders ?? 0) ?></p>            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">                                
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                <iconify-icon icon="solar:bill-list-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="mb-0">Chiffre d'affaires</h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($total_revenue ?? 0, 0, ',', ' ') ?> BIF</p>            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">                                
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                <iconify-icon icon="solar:user-check-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="mb-0">Clients Actifs</h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($active_customers ?? 0) ?></p>            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des clients -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Tous les clients</h4>
                        </div>
                        <div>
                            <a href="<?= base_url('Customers/export') ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-download me-1"></i>Exporter
                            </a>
                        </div>
                    </div>
                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>Client</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Commandes</th>
                                        <th>Dépenses totales</th>
                                        <th>Statut</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($customers)): ?>
                                        <?php foreach($customers as $customer): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if($customer['avatar_url']): ?>
                                                        <img src="<?= base_url($customer['avatar_url']) ?>" class="avatar-sm rounded-circle" alt="avatar">
                                                    <?php else: ?>
                                                        <div class="avatar-sm bg-soft-primary rounded-circle d-flex align-items-center justify-content-center">
                                                            <span class="text-primary fs-16 fw-bold"><?= strtoupper(substr($customer['prenom'], 0, 1)) ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?= $customer['prenom'] . ' ' . $customer['nom'] ?></h6>
                                                        <span class="text-muted fs-12">ID: #<?= $customer['id_utilisateur'] ?></span>
                                                    </div>
                                                </div>
                                             </div>
                                            <td><?= $customer['email'] ?></td>
                                            <td><?= $customer['telephone'] ?? '-' ?></td>
                                            <td>
                                                <?php
                                                $order_count = $this->db->where('id_utilisateur', $customer['id_utilisateur'])->count_all_results('commandes');
                                                echo $order_count;
                                                ?>
                                             </div>
                                            <td>
                                                <?php
                                                $total_spent = $this->db->select_sum('montant_total')
                                                    ->where('id_utilisateur', $customer['id_utilisateur'])
                                                    ->where('statut_commande', 'livre')
                                                    ->get('commandes')
                                                    ->row()->montant_total ?? 0;
                                                echo number_format($total_spent, 0, ',', ' ') . ' BIF';
                                                ?>
                                             </div>
                                            <td>
                                                <?php if($customer['est_actif'] == 1): ?>
                                                    <span class="badge bg-success-subtle text-success py-1 px-2">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger-subtle text-danger py-1 px-2">Inactif</span>
                                                <?php endif; ?>
                                             </div>
                                            <td><?= date('d/m/Y', strtotime($customer['date_creation'])) ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('Customers/view/'.$customer['id_utilisateur']) ?>" class="btn btn-light btn-sm" title="Voir">
                                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="<?= base_url('Utilisateurs/edit/'.$customer['id_utilisateur']) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <a href="<?= base_url('Customers/toggle_status/'.$customer['id_utilisateur']) ?>" class="btn btn-soft-<?= $customer['est_actif'] == 1 ? 'warning' : 'success' ?> btn-sm" title="<?= $customer['est_actif'] == 1 ? 'Désactiver' : 'Activer' ?>">
                                                        <iconify-icon icon="solar:<?= $customer['est_actif'] == 1 ? 'user-block-rounded-broken' : 'user-check-rounded-broken' ?>" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    <button type="button" class="btn btn-soft-danger btn-sm" title="Supprimer"
                                                            onclick="confirmDelete(<?= $customer['id_utilisateur'] ?>, '<?= $customer['prenom'] . ' ' . $customer['nom'] ?>')">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </div>
                                             </div>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun client trouvé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">Précédent</a></li>
                                <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">Suivant</a></li>
                            </ul>
                        </nav>
                    </div>
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

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer le client '" + name + "'. Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Customers/delete") ?>/' + id;
        }
    });
}
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>