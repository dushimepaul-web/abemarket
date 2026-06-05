<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">

        <!-- ============================================ -->
        <!-- SECTION STATISTIQUES DES CATÉGORIES -->
        <!-- ============================================ -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-primary bg-opacity-10 p-3">
                                <iconify-icon icon="solar:folder-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($total_categories ?? count($categories)) ?></h3>
                                <p class="text-muted mb-0">Total catégories</p>
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
                                <iconify-icon icon="solar:folder-open-bold-duotone" class="fs-32 text-success"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0">
                                    <?php 
                                    $actives = 0;
                                    foreach($categories as $cat) {
                                        if($cat->est_actif == 1) $actives++;
                                    }
                                    echo number_format($actives);
                                    ?>
                                </h3>
                                <p class="text-muted mb-0">Catégories actives</p>
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
                                <iconify-icon icon="solar:folder-with-files-bold-duotone" class="fs-32 text-info"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0">
                                    <?php 
                                    $sous_categories = 0;
                                    foreach($categories as $cat) {
                                        if($cat->niveau > 0) $sous_categories++;
                                    }
                                    echo number_format($sous_categories);
                                    ?>
                                </h3>
                                <p class="text-muted mb-0">Sous-catégories</p>
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
                                <iconify-icon icon="solar:folder-path-connect-bold-duotone" class="fs-32 text-warning"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0">
                                    <?php 
                                    $principales = 0;
                                    foreach($categories as $cat) {
                                        if($cat->niveau == 0) $principales++;
                                    }
                                    echo number_format($principales);
                                    ?>
                                </h3>
                                <p class="text-muted mb-0">Catégories principales</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deuxième ligne de statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-danger bg-opacity-10 p-3">
                                <iconify-icon icon="solar:folder-remove-bold-duotone" class="fs-32 text-danger"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0">
                                    <?php 
                                    $inactives = 0;
                                    foreach($categories as $cat) {
                                        if($cat->est_actif == 0) $inactives++;
                                    }
                                    echo number_format($inactives);
                                    ?>
                                </h3>
                                <p class="text-muted mb-0">Catégories inactives</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-secondary bg-opacity-10 p-3">
                                <iconify-icon icon="solar:tree-bold-duotone" class="fs-32 text-secondary"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0">
                                    <?php 
                                    $niveaux_max = 0;
                                    foreach($categories as $cat) {
                                        if($cat->niveau > $niveaux_max) $niveaux_max = $cat->niveau;
                                    }
                                    echo number_format($niveaux_max + 1);
                                    ?>
                                </h3>
                                <p class="text-muted mb-0">Niveaux hiérarchiques</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-purple bg-opacity-10 p-3">
                                <iconify-icon icon="solar:chart-2-bold-duotone" class="fs-32 text-purple"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0">
                                    <?php 
                                    $avec_commission = 0;
                                    foreach($categories as $cat) {
                                        if($cat->taux_commission_specifique > 0) $avec_commission++;
                                    }
                                    echo number_format($avec_commission);
                                    ?>
                                </h3>
                                <p class="text-muted mb-0">Avec commission spéciale</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des catégories -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Gestion des catégories</h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('categories/add') ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-plus me-1"></i>Ajouter une catégorie
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
                                        <th>Nom</th>
                                        <th>Slug</th>
                                        <th>Parent</th>
                                        <th>Niveau</th>
                                        <th>Commission</th>
                                        <th>Statut</th>
                                        <th>Ordre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $cat): ?>
                                            <tr>
                                                <td>#<?= $cat->id_categorie ?></td>
                                                <td>
                                                    <?php if ($cat->url_image && file_exists(FCPATH . $cat->url_image)): ?>
                                                        <img src="<?= base_url($cat->url_image) ?>" alt="<?= htmlspecialchars($cat->nom_categorie) ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="<?= htmlspecialchars($cat->icone ?: 'bx-category') ?> fs-20 text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                 </div>
                                                <td>
                                                    <strong><?= htmlspecialchars($cat->nom_categorie) ?></strong>
                                                    <?php if ($cat->description): ?>
                                                        <br><small class="text-muted"><?= htmlspecialchars(substr($cat->description, 0, 50)) ?>...</small>
                                                    <?php endif; ?>
                                                 </div>
                                                <td><code><?= htmlspecialchars($cat->slug_categorie) ?></code></td>
                                                <td>
                                                    <?php if ($cat->parent_nom): ?>
                                                        <span class="badge bg-info"><?= htmlspecialchars($cat->parent_nom) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Principale</span>
                                                    <?php endif; ?>
                                                 </div>
                                                <td>
                                                    <?php
                                                    $niveau_badge = '';
                                                    if ($cat->niveau == 0) $niveau_badge = 'primary';
                                                    elseif ($cat->niveau == 1) $niveau_badge = 'info';
                                                    elseif ($cat->niveau == 2) $niveau_badge = 'warning';
                                                    else $niveau_badge = 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?= $niveau_badge ?>">Niveau <?= $cat->niveau ?></span>
                                                 </div>
                                                <td>
                                                    <?php if ($cat->taux_commission_specifique): ?>
                                                        <strong class="text-success"><?= $cat->taux_commission_specifique ?>%</strong>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                 </div>
                                                <td>
                                                    <?php if ($cat->est_actif): ?>
                                                        <span class="badge bg-success">Actif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactif</span>
                                                    <?php endif; ?>
                                                 </div>
                                                <td><?= $cat->ordre_affichage ?> </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('categories/edit/' . $cat->slug_categorie) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <button type="button" class="btn btn-soft-warning btn-sm toggle-status" 
                                                                data-slug="<?= $cat->slug_categorie ?>" 
                                                                data-status="<?= $cat->est_actif ?>" 
                                                                data-nom="<?= htmlspecialchars($cat->nom_categorie) ?>"
                                                                title="<?= $cat->est_actif ? 'Désactiver' : 'Activer' ?>">
                                                            <iconify-icon icon="solar:refresh-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-soft-danger btn-sm delete-categorie" 
                                                                data-slug="<?= $cat->slug_categorie ?>" 
                                                                data-nom="<?= htmlspecialchars($cat->nom_categorie) ?>"
                                                                title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <iconify-icon icon="solar:folder-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucune catégorie</h5>
                                                <p class="text-muted">Cliquez sur "Ajouter une catégorie" pour commencer</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($categories)): ?>
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
// Changer le statut
$('.toggle-status').on('click', function() {
    const slug = $(this).data('slug');
    const nom = $(this).data('nom');
    const statusActuel = $(this).data('status');
    const action = statusActuel == 1 ? 'désactiver' : 'activer';
    
    Swal.fire({
        title: 'Confirmation',
        text: `Voulez-vous ${action} la catégorie "${nom}" ?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("categories/toggle_status/") ?>' + slug,
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
$('.delete-categorie').on('click', function() {
    const slug = $(this).data('slug');
    const nom = $(this).data('nom');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: `Vous allez supprimer la catégorie "${nom}". Cette action est irréversible !`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("categories/delete/") ?>' + slug,
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
.btn-soft-primary:hover { background-color: #b6d4fe; }
.btn-soft-warning { background-color: #fff3cd; border-color: #fff3cd; color: #ffc107; }
.btn-soft-warning:hover { background-color: #ffecb5; }
.btn-soft-danger { background-color: #f8d7da; border-color: #f8d7da; color: #dc3545; }
.btn-soft-danger:hover { background-color: #f5c2c7; }
.bg-light-subtle { background-color: #f8f9fa; }
.bg-purple { background-color: #6f42c1; }
.bg-purple.bg-opacity-10 { background-color: rgba(111, 66, 193, 0.1); }
.text-purple { color: #6f42c1; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>