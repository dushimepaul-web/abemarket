<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:tag-bold-duotone" class="me-2"></iconify-icon>
                            Catégories FAQ
                        </h4>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                            Ajouter une catégorie
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Slug</th>
                                        <th>Description</th>
                                        <th>Ordre</th>
                                        <th>FAQ liées</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $cat): ?>
                                        <tr>
                                            <td><?= $cat->id_categorie_faq ?></td>
                                            <td><strong><?= htmlspecialchars($cat->nom, ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                            <td><?= $cat->slug ?></td>
                                            <td><?= htmlspecialchars(substr($cat->description ?? '', 0, 60), ENT_QUOTES, 'UTF-8'); ?>...</div>
                                            <td><?= $cat->ordre_affichage ?></td>
                                            <td><span class="badge bg-info"><?= $cat->total_faq ?> FAQ</span></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-sm btn-warning edit-category" 
                                                            data-id="<?= $cat->id_categorie_faq ?>"
                                                            data-nom="<?= htmlspecialchars($cat->nom, ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-description="<?= htmlspecialchars($cat->description ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                            data-ordre="<?= $cat->ordre_afficharge ?>">
                                                        <iconify-icon icon="solar:pen-bold"></iconify-icon>
                                                    </button>
                                                    <?php if ($cat->total_faq == 0): ?>
                                                    <button class="btn btn-sm btn-danger delete-category" data-id="<?= $cat->id_categorie_faq ?>" data-nom="<?= htmlspecialchars($cat->nom, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">Aucune catégorie trouvée</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Catégorie -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                    <input type="text" id="cat_nom" class="form-control" placeholder="Ex: Commandes, Livraison...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="cat_description" class="form-control" rows="3" placeholder="Description optionnelle"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ordre d'affichage</label>
                    <input type="number" id="cat_ordre" class="form-control" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveCategory">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Ajouter catégorie
$('#saveCategory').click(function() {
    var nom = $('#cat_nom').val();
    if (!nom) {
        Swal.fire('Erreur', 'Le nom est requis', 'error');
        return;
    }
    
    $.ajax({
        url: '<?= base_url("admin/faq/addCategory"); ?>',
        type: 'POST',
        data: {
            nom: nom,
            description: $('#cat_description').val(),
            ordre_affichage: $('#cat_ordre').val()
        },
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

// Supprimer catégorie
$('.delete-category').click(function() {
    var id = $(this).data('id');
    var nom = $(this).data('nom');
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Supprimer la catégorie "' + nom + '" ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("admin/faq/deleteCategory/") ?>' + id,
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
                }
            });
        }
    });
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>