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
                            <iconify-icon icon="solar:chat-round-like-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des témoignages
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/testimonials/add') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Ajouter un témoignage
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📝 Total témoignages</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Approuvés</span>
                                        <strong><?= number_format($stats->approuves ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-secondary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏳ En attente</span>
                                        <strong><?= number_format($stats->desapprouves ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⭐ Note moyenne</span>
                                        <strong><?= number_format($stats->note_moyenne ?? 0, 1); ?> / 5</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Répartition des notes -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-12">
                                <small class="text-muted">Répartition des notes :</small>
                                <div class="d-flex gap-3 mt-1 flex-wrap">
                                    <span class="badge bg-success">5★ (<?= $stats->note_5 ?? 0 ?>)</span>
                                    <span class="badge bg-info">4★ (<?= $stats->note_4 ?? 0 ?>)</span>
                                    <span class="badge bg-warning">3★ (<?= $stats->note_3 ?? 0 ?>)</span>
                                    <span class="badge bg-secondary">2★ (<?= $stats->note_2 ?? 0 ?>)</span>
                                    <span class="badge bg-danger">1★ (<?= $stats->note_1 ?? 0 ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="est_approuve" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['est_approuve'] ?? '') === '1' ? 'selected' : '' ?>>Approuvé</option>
                                    <option value="0" <?= ($filters['est_approuve'] ?? '') === '0' ? 'selected' : '' ?>>Non approuvé</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="note" class="form-select">
                                    <option value="">Toutes les notes</option>
                                    <option value="5" <?= ($filters['note'] ?? '') == '5' ? 'selected' : '' ?>>5 étoiles</option>
                                    <option value="4" <?= ($filters['note'] ?? '') == '4' ? 'selected' : '' ?>>4 étoiles</option>
                                    <option value="3" <?= ($filters['note'] ?? '') == '3' ? 'selected' : '' ?>>3 étoiles</option>
                                    <option value="2" <?= ($filters['note'] ?? '') == '2' ? 'selected' : '' ?>>2 étoiles</option>
                                    <option value="1" <?= ($filters['note'] ?? '') == '1' ? 'selected' : '' ?>>1 étoile</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom, prénom..." value="<?= htmlspecialchars($filters['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
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
                                        <th width="60">Photo</th>
                                        <th>Client</th>
                                        <th>Message</th>
                                        <th width="80">Note</th>
                                        <th width="80">Ordre</th>
                                        <th width="100">Statut</th>
                                        <th width="100">Date</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($testimonials)): ?>
                                        <?php foreach ($testimonials as $t): ?>
                                        <tr>
                                            <td>#<?= $t->id_testimonial ?></td>
                                            <td>
                                                <?php if (!empty($t->photo_url)): ?>
                                                    <img src="<?= base_url($t->photo_url); ?>" style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%;" alt="">
                                                <?php else: ?>
                                                    <div style="width: 45px; height: 45px; background: #6c757d; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                                        <iconify-icon icon="solar:user-bold" width="20"></iconify-icon>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <td>
                                                <strong><?= htmlspecialchars($t->prenom . ' ' . $t->nom, ENT_QUOTES, 'UTF-8'); ?></strong>
                                                <?php if (!empty($t->poste)): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($t->poste, ENT_QUOTES, 'UTF-8'); ?></small>
                                                <?php endif; ?>
                                            </div>
                                            <td>
                                                <div style="max-width: 300px;">
                                                    <?= htmlspecialchars(substr($t->message, 0, 80), ENT_QUOTES, 'UTF-8'); ?>
                                                    <?php if (strlen($t->message) > 80): ?>...<?php endif; ?>
                                                </div>
                                            </div>
                                            <td>
                                                <div class="text-nowrap">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <iconify-icon icon="solar:star-bold" class="<?= $i <= $t->note ? 'text-warning' : 'text-secondary'; ?>" width="16"></iconify-icon>
                                                    <?php endfor; ?>
                                                    <br><small>(<?= $t->note ?>/5)</small>
                                                </div>
                                            </div>
                                            <td>
                                                <input type="number" class="form-control form-control-sm order-input" 
                                                       data-id="<?= $t->id_testimonial; ?>" 
                                                       value="<?= $t->ordre_affichage; ?>" 
                                                       style="width: 70px;">
                                            </div>
                                            <td>
                                                <button class="btn btn-sm approve-status <?= $t->est_approuve == 1 ? 'btn-success' : 'btn-secondary'; ?>" 
                                                        data-id="<?= $t->id_testimonial; ?>">
                                                    <?= $t->est_approuve == 1 ? 'Approuvé' : 'En attente'; ?>
                                                </button>
                                            </div>
                                            <td><?= date('d/m/Y', strtotime($t->date_creation)); ?></div>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('admin/testimonials/edit/' . $t->id_testimonial); ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                        <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                                    </a>
                                                    <button class="btn btn-sm btn-danger delete-testimonial" data-id="<?= $t->id_testimonial; ?>" data-name="<?= htmlspecialchars($t->prenom . ' ' . $t->nom, ENT_QUOTES, 'UTF-8'); ?>" title="Supprimer">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                    </button>
                                                </div>
                                            </div>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:chat-round-like-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun témoignage trouvé</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Bouton mise à jour ordre -->
                        <?php if (!empty($testimonials)): ?>
                        <div class="mt-3">
                            <button id="updateOrderBtn" class="btn btn-warning">
                                <iconify-icon icon="solar:sort-bold"></iconify-icon>
                                Mettre à jour l'ordre
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($testimonials) && $total_pages > 1): ?>
                        <div class="card-footer border-top">
                            <nav>
                                <ul class="pagination justify-content-center mb-0">
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?= $i; ?>&est_approuve=<?= $filters['est_approuve'] ?? '' ?>&note=<?= $filters['note'] ?? '' ?>&search=<?= urlencode($filters['search'] ?? ''); ?>">
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
            url: '<?= base_url("admin/testimonials/updateOrder"); ?>',
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
    
    // Approve/Desapprove toggle
    $('.approve-status').click(function() {
        var $btn = $(this);
        var id = $btn.data('id');
        
        $.ajax({
            url: '<?= base_url("admin/testimonials/approve/"); ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.new_status == 1) {
                        $btn.removeClass('btn-secondary').addClass('btn-success').text('Approuvé');
                    } else {
                        $btn.removeClass('btn-success').addClass('btn-secondary').text('En attente');
                    }
                    Swal.fire('Succès', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                }
            }
        });
    });
    
    // Suppression témoignage
    $('.delete-testimonial').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: 'Confirmation',
            text: 'Supprimer le témoignage de "' + name + '" ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("admin/testimonials/delete/") ?>' + id,
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