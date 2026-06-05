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
                            <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="me-2"></iconify-icon>
                            Gestion de l'équipe
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/team/add') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Ajouter un membre
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>👥 Total membres</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Actifs</span>
                                        <strong><?= number_format($stats->actifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-secondary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⛔ Inactifs</span>
                                        <strong><?= number_format($stats->inactifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="est_actif" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['est_actif'] ?? '') === '1' ? 'selected' : '' ?>>Actif</option>
                                    <option value="0" <?= ($filters['est_actif'] ?? '') === '0' ? 'selected' : '' ?>>Inactif</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, prénom ou poste..." value="<?= htmlspecialchars($filters['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <iconify-icon icon="solar:filter-bold"></iconify-icon>
                                    Filtrer
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th width="50">ID</th>
                                        <th width="80">Photo</th>
                                        <th>Nom complet</th>
                                        <th>Poste</th>
                                        <th width="80">Ordre</th>
                                        <th width="100">Statut</th>
                                        <th width="100">Date</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($members)): ?>
                                        <?php foreach ($members as $m): ?>
                                        <tr>
                                            <td>#<?= $m->id_member ?></td>
                                            <td>
                                                <?php if (!empty($m->photo_url)): ?>
                                                    <img src="<?= base_url($m->photo_url); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;" alt="">
                                                <?php else: ?>
                                                    <div style="width: 50px; height: 50px; background: #6c757d; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                                        <iconify-icon icon="solar:user-bold" width="24"></iconify-icon>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <td>
                                                <strong><?= htmlspecialchars($m->prenom . ' ' . $m->nom, ENT_QUOTES, 'UTF-8'); ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($m->poste, ENT_QUOTES, 'UTF-8'); ?></small>
                                            </div>
                                            <td><?= htmlspecialchars($m->poste, ENT_QUOTES, 'UTF-8'); ?></div>
                                            <td>
                                                <input type="number" class="form-control form-control-sm order-input" 
                                                       data-id="<?= $m->id_member; ?>" 
                                                       value="<?= $m->ordre_affichage; ?>" 
                                                       style="width: 70px;">
                                            </div>
                                            <td>
                                                <button class="btn btn-sm toggle-status <?= $m->est_actif == 1 ? 'btn-success' : 'btn-secondary'; ?>" 
                                                        data-id="<?= $m->id_member; ?>">
                                                    <?= $m->est_actif == 1 ? 'Actif' : 'Inactif'; ?>
                                                </button>
                                            </div>
                                            <td><?= date('d/m/Y', strtotime($m->date_creation)); ?></div>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('admin/team/edit/' . $m->id_member); ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                        <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                                    </a>
                                                    <button class="btn btn-sm btn-danger delete-member" data-id="<?= $m->id_member; ?>" data-name="<?= htmlspecialchars($m->prenom . ' ' . $m->nom, ENT_QUOTES, 'UTF-8'); ?>" title="Supprimer">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                    </button>
                                                </div>
                                            </div>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <iconify-icon icon="solar:users-group-rounded-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun membre trouvé</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Bouton mise à jour ordre -->
                        <?php if (!empty($members)): ?>
                        <div class="mt-3">
                            <button id="updateOrderBtn" class="btn btn-warning">
                                <iconify-icon icon="solar:sort-bold"></iconify-icon>
                                Mettre à jour l'ordre
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($members) && $total_pages > 1): ?>
                        <div class="card-footer border-top">
                            <nav>
                                <ul class="pagination justify-content-center mb-0">
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?= $i; ?>&est_actif=<?= $filters['est_actif'] ?? '' ?>&search=<?= urlencode($filters['search'] ?? ''); ?>">
                                            <?= $i; ?>
                                        </a>
                                    </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Mise à jour de l'ordre
    $('#updateOrderBtn').click(function() {
        var orders = [];
        $('.order-input').each(function() {
            orders.push({
                id: $(this).data('id'),
                ordre: $(this).val()
            });
        });
        
        $.ajax({
            url: '<?= base_url("admin/team/updateOrder"); ?>',
            type: 'POST',
            data: { orders: orders },
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
    });
    
    // Toggle statut
    $('.toggle-status').click(function() {
        var $btn = $(this);
        var id = $btn.data('id');
        
        $.ajax({
            url: '<?= base_url("admin/team/toggle/"); ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.new_status == 1) {
                        $btn.removeClass('btn-secondary').addClass('btn-success').text('Actif');
                    } else {
                        $btn.removeClass('btn-success').addClass('btn-secondary').text('Inactif');
                    }
                    Swal.fire('Succès', response.message, 'success');
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                }
            }
        });
    });
    
    // Suppression membre
    $('.delete-member').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: 'Confirmation',
            text: 'Supprimer le membre : "' + name + '" ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("admin/team/delete/") ?>' + id,
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
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>