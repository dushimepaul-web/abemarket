<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Gestion des images - <?= htmlspecialchars($produit->nom_produit) ?></h4>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <i class="bx bx-upload me-1"></i>Uploader une image
                            </button>
                            <a href="<?= base_url('Produits') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour produits
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-info">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2 fs-18"></iconify-icon>
                            La première image est l'image principale. Glissez-déposez les images pour les réorganiser.
                        </div>
                        
                        <div class="row" id="images-container">
                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $img): ?>
                                <div class="col-md-3 col-sm-4 col-6 mb-3" data-id="<?= $img->id_image ?>">
                                    <div class="card image-card">
                                        <img src="<?= base_url($img->url_miniature ?: $img->url_image) ?>" class="card-img-top" alt="<?= htmlspecialchars($img->texte_alt) ?>" style="height: 150px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="form-check">
                                                    <input type="radio" name="image_principale" class="form-check-input principale" value="<?= $img->id_image ?>" <?= $img->est_principale ? 'checked' : '' ?>>
                                                    <label class="form-check-label small">Principale</label>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary edit-image" data-id="<?= $img->id_image ?>" data-alt="<?= htmlspecialchars($img->texte_alt) ?>" data-ordre="<?= $img->ordre_affichage ?>" data-principale="<?= $img->est_principale ?>">
                                                        <iconify-icon icon="solar:pen-2-broken"></iconify-icon>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger delete-image" data-id="<?= $img->id_image ?>">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-1">Ordre: <?= $img->ordre_affichage ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12 text-center py-5">
                                    <iconify-icon icon="solar:gallery-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                    <h5>Aucune image</h5>
                                    <p class="text-muted">Cliquez sur "Uploader une image" pour ajouter des images</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Uploader une image</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_produit" value="<?= $produit->id_produit ?>">
                    <div class="mb-3">
                        <label class="form-label">Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" id="image_file" class="form-control" accept="image/*" required>
                        <small class="text-muted">Formats: JPG, PNG, GIF, WEBP. Max 5MB</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Texte alternatif (SEO)</label>
                        <input type="text" name="texte_alt" id="texte_alt" class="form-control" placeholder="Description de l'image">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="ordre_affichage" id="ordre_affichage" class="form-control" value="0">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="est_principale" id="est_principale" class="form-check-input" value="1">
                        <label class="form-check-label">Définir comme image principale</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Uploader</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Éditer -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Modifier l'image</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" name="id_image" id="edit_id_image">
                    <div class="mb-3">
                        <label class="form-label">Texte alternatif (SEO)</label>
                        <input type="text" name="texte_alt" id="edit_texte_alt" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="ordre_affichage" id="edit_ordre_affichage" class="form-control">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="est_principale" id="edit_est_principale" class="form-check-input" value="1">
                        <label class="form-check-label">Définir comme image principale</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Sortable pour le glisser-déposer
const container = document.getElementById('images-container');
if (container) {
    new Sortable(container, {
        animation: 150,
        onEnd: function() {
            const orders = {};
            document.querySelectorAll('#images-container .col-md-3').forEach((el, index) => {
                const id = el.dataset.id;
                if (id) orders[id] = index;
            });
            
            $.post('<?= base_url("ImageProduit/reordonner") ?>', {orders: orders}, function(response) {
                if (response.success) {
                    document.querySelectorAll('#images-container .col-md-3').forEach((el, index) => {
                        const orderSpan = el.querySelector('.text-muted');
                        if (orderSpan) orderSpan.innerHTML = 'Ordre: ' + index;
                    });
                    Swal.fire('Succès', 'Ordre mis à jour', 'success');
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                }
            }, 'json').fail(function() {
                Swal.fire('Erreur', 'Erreur de communication', 'error');
            });
        }
    });
}




// Upload - Version corrigée
$('#uploadForm').on('submit', function(e) {
    e.preventDefault();
    
    // Vérifier qu'un fichier est sélectionné
    var fileInput = document.getElementById('image_file');
    if (fileInput.files.length === 0) {
        Swal.fire('Erreur', 'Veuillez sélectionner une image', 'error');
        return;
    }
    
    const formData = new FormData(this);
    
    // Afficher un indicateur de chargement
    Swal.fire({
        title: 'Upload en cours...',
        text: 'Veuillez patienter',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: '<?= base_url("ImageProduit/upload/" . $produit->id_produit) ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
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
        error: function(xhr, status, error) {
            console.log('Erreur AJAX:', error);
            console.log('Réponse:', xhr.responseText);
            Swal.fire('Erreur', 'Erreur lors de l\'upload: ' + error, 'error');
        }
    });
});



// Éditer
$(document).on('click', '.edit-image', function() {
    const id = $(this).data('id');
    const alt = $(this).data('alt');
    const ordre = $(this).data('ordre');
    const principale = $(this).data('principale');
    
    $('#edit_id_image').val(id);
    $('#edit_texte_alt').val(alt);
    $('#edit_ordre_affichage').val(ordre);
    $('#edit_est_principale').prop('checked', principale == 1);
    $('#editModal').modal('show');
});

$('#editForm').on('submit', function(e) {
    e.preventDefault();
    const id = $('#edit_id_image').val();
    
    $.post('<?= base_url("ImageProduit/update/") ?>' + id, $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Erreur', response.message, 'error');
        }
    }, 'json');
});

// Définir principale
$(document).on('change', '.principale', function() {
    const id = $(this).val();
    if ($(this).is(':checked')) {
        $.post('<?= base_url("ImageProduit/definir_principale/") ?>' + id, function(response) {
            if (response.success) {
                location.reload();
            } else {
                Swal.fire('Erreur', response.message, 'error');
            }
        }, 'json');
    }
});

// Supprimer
$(document).on('click', '.delete-image', function() {
    const id = $(this).data('id');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("ImageProduit/delete/") ?>' + id, function(response) {
                if (response.success) {
                    Swal.fire('Supprimé!', response.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                }
            }, 'json');
        }
    });
});
</script>

<style>
.image-card { transition: transform 0.2s; }
.image-card:hover { transform: scale(1.02); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>