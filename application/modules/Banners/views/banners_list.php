<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">

        <!-- Statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-primary bg-opacity-10 p-3">
                                <iconify-icon icon="solar:gallery-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($total_banners ?? 0) ?></h3>
                                <p class="text-muted mb-0">Total bannières</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-success bg-opacity-10 p-3">
                                <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-32 text-success"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($total_active ?? 0) ?></h3>
                                <p class="text-muted mb-0">Bannières actives</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-info bg-opacity-10 p-3">
                                <iconify-icon icon="solar:home-2-bold-duotone" class="fs-32 text-info"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($count_by_position['home_main'] ?? 0) ?></h3>
                                <p class="text-muted mb-0">Accueil principal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-warning bg-opacity-10 p-3">
                                <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-32 text-warning"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($total_programmees ?? 0) ?></h3>
                                <p class="text-muted mb-0">Planifiées</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bannières expirant bientôt (alerte) -->
        <?php if (!empty($expiring_banners)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><i class="bx bx-time me-2"></i> Attention !</strong> 
                    <?= count($expiring_banners) ?> bannière(s) expire(nt) dans les 7 prochains jours :
                    <?php foreach($expiring_banners as $exp): ?>
                        <span class="badge bg-warning text-dark ms-2"><?= htmlspecialchars($exp->title) ?></span>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Liste des bannières -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Gestion des bannières</h4>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bx bx-filter me-1"></i>Filtrer
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= base_url('banners') ?>">Toutes</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('banners/by-position/home_main') ?>">🏠 Accueil principal</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('banners/by-position/home_bottom') ?>">📌 Accueil bas</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('banners/by-position/home_top_right') ?>">📌 Accueil haut droite</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('banners/by-position/category') ?>">📁 Catégorie</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('banners/by-position/product') ?>">📦 Produit</a></li>
                                </ul>
                            </div>
                            <a href="<?= base_url('banners/stats') ?>" class="btn btn-sm btn-info">
                                <i class="bx bx-stats me-1"></i>Stats
                            </a>
                            <a href="<?= base_url('banners/add') ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus me-1"></i>Ajouter
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Titre</th>
                                        <th>Position</th>
                                        <th>Ordre</th>
                                        <th>Période</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($banners)): ?>
                                        <?php foreach ($banners as $b): ?>
                                            <?php 
                                            // Vérifier si la bannière est expirée
                                            $is_expired = ($b->date_fin && strtotime($b->date_fin) < time());
                                            // Vérifier si la bannière est programmée
                                            $is_scheduled = ($b->date_debut && strtotime($b->date_debut) > time());
                                            ?>
                                            <tr class="<?= $is_expired ? 'bg-light text-muted' : '' ?>">
                                                <td>#<?= $b->id_banner ?></td>
                                                <td>
                                                    <?php if ($b->image && file_exists(FCPATH . $b->image)): ?>
                                                        <img src="<?= base_url($b->image) ?>" alt="<?= htmlspecialchars($b->title) ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                                            <i class="bx bx-image fs-20 text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($b->title) ?></strong>
                                                    <?php if ($b->subtitle): ?>
                                                        <br><small class="text-muted"><?= htmlspecialchars(substr($b->subtitle, 0, 40)) ?>...</small>
                                                    <?php endif; ?>
                                                    <?php if ($is_expired): ?>
                                                        <br><span class="badge bg-danger mt-1">Expirée</span>
                                                    <?php endif; ?>
                                                    <?php if ($is_scheduled): ?>
                                                        <br><span class="badge bg-info mt-1">Programmée</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $position_label = '';
                                                    $position_icon = '';
                                                    switch($b->position) {
                                                        case 'home_main': 
                                                            $position_label = '<span class="badge bg-primary"><i class="bx bx-home me-1"></i>Accueil principal</span>'; 
                                                            break;
                                                        case 'home_top_right': 
                                                            $position_label = '<span class="badge bg-info"><i class="bx bx-home-alt me-1"></i>Accueil haut droite</span>'; 
                                                            break;
                                                        case 'home_bottom': 
                                                            $position_label = '<span class="badge bg-success"><i class="bx bx-home-smile me-1"></i>Accueil bas</span>'; 
                                                            break;
                                                        case 'category': 
                                                            $position_label = '<span class="badge bg-warning"><i class="bx bx-folder me-1"></i>Catégorie</span>'; 
                                                            break;
                                                        case 'product': 
                                                            $position_label = '<span class="badge bg-secondary"><i class="bx bx-package me-1"></i>Produit</span>'; 
                                                            break;
                                                        default: 
                                                            $position_label = '<span class="badge bg-dark">' . $b->position . '</span>';
                                                    }
                                                    echo $position_label;
                                                    ?>
                                                </td>
                                                <td><?= $b->ordre_affichage ?></td>
                                                <td>
                                                    <?php if ($b->date_debut || $b->date_fin): ?>
                                                        <small>
                                                            <?php if ($b->date_debut): ?>
                                                                <i class="bx bx-calendar me-1"></i>Déb: <?= date('d/m/Y', strtotime($b->date_debut)) ?><br>
                                                            <?php endif; ?>
                                                            <?php if ($b->date_fin): ?>
                                                                <i class="bx bx-calendar-x me-1"></i>Fin: <?= date('d/m/Y', strtotime($b->date_fin)) ?>
                                                            <?php endif; ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-muted"><i class="bx bx-infinity me-1"></i>Permanent</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($b->est_actif && !$is_expired && !$is_scheduled): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php elseif ($b->est_actif && $is_scheduled): ?>
                                                        <span class="badge bg-info">Programmé</span>
                                                    <?php elseif ($b->est_actif && $is_expired): ?>
                                                        <span class="badge bg-danger">Expiré</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('banners/edit/' . $b->id_banner) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-info btn-sm" 
                                                                onclick="previewBanner('<?= base_url($b->image) ?>', '<?= htmlspecialchars($b->title) ?>')" 
                                                                title="Aperçu">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-soft-success btn-sm duplicate-banner" 
                                                                data-id="<?= $b->id_banner ?>" 
                                                                data-title="<?= htmlspecialchars($b->title) ?>"
                                                                title="Dupliquer">
                                                            <iconify-icon icon="solar:copy-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-soft-warning btn-sm toggle-status" 
                                                                data-id="<?= $b->id_banner ?>" 
                                                                data-status="<?= $b->est_actif ?>" 
                                                                data-title="<?= htmlspecialchars($b->title) ?>"
                                                                title="<?= $b->est_actif ? 'Désactiver' : 'Activer' ?>">
                                                            <iconify-icon icon="solar:refresh-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-soft-danger btn-sm delete-banner" 
                                                                data-id="<?= $b->id_banner ?>" 
                                                                data-title="<?= htmlspecialchars($b->title) ?>"
                                                                title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <iconify-icon icon="solar:gallery-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucune bannière</h5>
                                                <p class="text-muted">Cliquez sur "Ajouter une bannière" pour commencer</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($banners) && isset($this->pagination)): ?>
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
// Aperçu de la bannière
function previewBanner(imageUrl, title) {
    Swal.fire({
        title: title,
        imageUrl: imageUrl,
        imageWidth: '100%',
        imageHeight: 'auto',
        imageAlt: title,
        confirmButtonColor: '#ff6600',
        confirmButtonText: 'Fermer'
    });
}

// Dupliquer une bannière
$('.duplicate-banner').on('click', function() {
    const id = $(this).data('id');
    const title = $(this).data('title');
    
    Swal.fire({
        title: 'Dupliquer la bannière',
        text: `Voulez-vous dupliquer "${title}" ?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, dupliquer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("banners/duplicate/") ?>' + id,
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
                },
                error: function() {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});

// Changer le statut
$('.toggle-status').on('click', function() {
    const id = $(this).data('id');
    const title = $(this).data('title');
    const statusActuel = $(this).data('status');
    const action = statusActuel == 1 ? 'désactiver' : 'activer';
    
    Swal.fire({
        title: 'Confirmation',
        text: `Voulez-vous ${action} la bannière "${title}" ?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("banners/toggle_status/") ?>' + id,
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
                },
                error: function() {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});

// Supprimer
$('.delete-banner').on('click', function() {
    const id = $(this).data('id');
    const title = $(this).data('title');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: `Vous allez supprimer la bannière "${title}". Cette action est irréversible !`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("banners/delete/") ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Supprimé!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});
</script>

<style>
.btn-soft-primary { background-color: #cfe2ff; border-color: #cfe2ff; color: #0d6efd; }
.btn-soft-primary:hover { background-color: #b6d4fe; color: #0d6efd; }
.btn-soft-warning { background-color: #fff3cd; border-color: #fff3cd; color: #ffc107; }
.btn-soft-warning:hover { background-color: #ffecb5; color: #ffc107; }
.btn-soft-danger { background-color: #f8d7da; border-color: #f8d7da; color: #dc3545; }
.btn-soft-danger:hover { background-color: #f5c2c7; color: #dc3545; }
.btn-soft-info { background-color: #cff4fc; border-color: #cff4fc; color: #0dcaf0; }
.btn-soft-info:hover { background-color: #b6effb; color: #0dcaf0; }
.btn-soft-success { background-color: #d1e7dd; border-color: #d1e7dd; color: #198754; }
.btn-soft-success:hover { background-color: #badbcc; color: #198754; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>