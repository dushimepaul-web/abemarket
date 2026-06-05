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
                            <iconify-icon icon="solar:question-circle-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des FAQ
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('admin/faq/add') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Ajouter une FAQ
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📋 Total FAQ</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Actives</span>
                                        <strong><?= number_format($stats->actifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-secondary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⛔ Inactives</span>
                                        <strong><?= number_format($stats->inactifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
            

                    <div class="card-body">
                           

        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="bg-light">
                    <tr>
                        <th width="50">ID</th>
                        <th>Question</th>
                        <th width="80">Ordre</th>
                        <th width="100">Statut</th>
                        <th width="100">Date</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($faqs)): ?>
                        <?php foreach ($faqs as $f): ?>
                        <tr>
                            <td>#<?= $f->id_faq ?></td>
                            <td>
                                <strong><?= htmlspecialchars(substr($f->question, 0, 80), ENT_QUOTES, 'UTF-8'); ?></strong>
                                <?php if (strlen($f->question) > 80): ?>...<?php endif; ?>
                                <br>
                                <small class="text-muted"><?= htmlspecialchars(substr(strip_tags($f->reponse), 0, 60), ENT_QUOTES, 'UTF-8'); ?>...</small>
                            </div>
                            <td>
                                <input type="number" class="form-control form-control-sm order-input" 
                                    data-id="<?= $f->id_faq; ?>" 
                                    value="<?= $f->ordre_affichage; ?>" 
                                    style="width: 70px;">
                            </div>
                            <td>
                                <button class="btn btn-sm toggle-status <?= $f->est_actif == 1 ? 'btn-success' : 'btn-secondary'; ?>" 
                                        data-id="<?= $f->id_faq; ?>">
                                    <?= $f->est_actif == 1 ? 'Actif' : 'Inactif'; ?>
                                </button>
                            </div>
                            <td><?= date('d/m/Y', strtotime($f->date_creation)); ?></div>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('admin/faq/edit/' . $f->id_faq); ?>" class="btn btn-sm btn-primary" title="Modifier">
                                        <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                    </a>
                                    <button class="btn btn-sm btn-danger delete-faq" data-id="<?= $f->id_faq; ?>" data-question="<?= htmlspecialchars($f->question, ENT_QUOTES, 'UTF-8'); ?>" title="Supprimer">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <iconify-icon icon="solar:question-circle-broken" class="fs-48 text-muted"></iconify-icon>
                                <p class="mt-2">Aucune FAQ trouvée</p>
                            </div>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
                                </div>
                                
                                <!-- Bouton mise à jour ordre -->
                                <?php if (!empty($faqs)): ?>
                                <div class="mt-3">
                                    <button id="updateOrderBtn" class="btn btn-warning">
                                        <iconify-icon icon="solar:sort-bold"></iconify-icon>
                                        Mettre à jour l'ordre
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($faqs) && $total_pages > 1): ?>
                                <div class="card-footer border-top">
                                    <nav>
                                        <ul class="pagination justify-content-center mb-0">
                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
                                                <a class="page-link" href="?page=<?= $i; ?>&categorie=<?= $filters['categorie'] ?? '' ?>&est_actif=<?= $filters['est_actif'] ?? '' ?>&search=<?= urlencode($filters['search'] ?? ''); ?>">
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
                    url: '<?= base_url("admin/faq/updateOrder"); ?>',
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
                    url: '<?= base_url("admin/faq/toggle/"); ?>' + id,
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
            
            // Suppression FAQ
            $('.delete-faq').click(function() {
                var id = $(this).data('id');
                var question = $(this).data('question');
                
                Swal.fire({
                    title: 'Confirmation',
                    text: 'Supprimer la FAQ : "' + question + '" ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= base_url("admin/faq/delete/") ?>' + id,
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