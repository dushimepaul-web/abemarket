<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">
        <div class="row mb-3">
            <div class="col-12">
                <a href="<?= base_url('ProduitsVendeur') ?>" class="text-decoration-none"><i class="ri-arrow-left-line"></i> Retour</a>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h4>Images — <?= htmlspecialchars($produit->nom_produit) ?></h4>
                    <div>
                        <a href="<?= base_url('ProduitsVendeur/edit/' . $produit->slug_produit) ?>" class="btn btn-outline-primary btn-sm"><i class="ri-edit-line"></i> Modifier</a>
                        <a href="<?= base_url('ProduitsVendeur/variantes/' . $produit->slug_produit) ?>" class="btn btn-outline-warning btn-sm"><i class="ri-stack-line"></i> Variantes</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Images (<?= count($images) ?>)</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <div class="input-group">
                            <input type="file" class="form-control" id="imageInput" multiple accept="image/*">
                            <button type="submit" class="btn btn-primary"><i class="ri-upload-cloud-line"></i> Upload</button>
                        </div>
                    </form>
                </div>

                <?php if (empty($images)): ?>
                    <div class="text-center py-4">
                        <i class="ri-image-line" style="font-size:48px; color:#ccc;"></i>
                        <p class="text-muted mt-2">Aucune image. Ajoutez des images pour votre produit.</p>
                    </div>
                <?php else: ?>
                    <div class="row" id="imageGallery">
                        <?php foreach ($images as $img): ?>
                            <div class="col-md-3 col-6 mb-3" id="img-<?= $img->id_image ?>">
                                <div class="card h-100">
                                    <img src="<?= base_url($img->url_image) ?>" class="card-img-top" style="height:150px; object-fit:cover;">
                                    <div class="card-body p-2 text-center">
                                        <?php if (!empty($img->est_principale)): ?>
                                            <span class="badge bg-primary">Principale</span>
                                        <?php else: ?>
                                            <button class="btn btn-outline-primary btn-sm btn-set-main" data-id="<?= $img->id_image ?>">
                                                <i class="ri-star-line"></i> Définir principale
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn btn-outline-danger btn-sm btn-delete-img mt-1" data-id="<?= $img->id_image ?>">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const files = document.getElementById('imageInput').files;
    if (!files.length) return alert('Sélectionnez des images');

    const formData = new FormData();
    for (let f of files) formData.append('images[]', f);

    try {
        const resp = await fetch('<?= base_url("ProduitsVendeur/ajax_upload_image/" . $produit->id_produit) ?>', {
            method: 'POST',
            body: formData
        });
        const data = await resp.json();
        if (data.success) location.reload();
        else alert(data.error || 'Erreur upload');
    } catch(err) { alert('Erreur réseau'); }
});

document.addEventListener('click', async function(e) {
    if (e.target.closest('.btn-delete-img')) {
        const id = e.target.closest('.btn-delete-img').dataset.id;
        if (!confirm('Supprimer cette image ?')) return;
        const resp = await fetch('<?= base_url("ProduitsVendeur/delete_image/") ?>' + id, { method: 'POST' });
        const data = await resp.json();
        if (data.success) document.getElementById('img-' + id)?.remove();
        else alert(data.error || 'Erreur');
    }
    if (e.target.closest('.btn-set-main')) {
        const id = e.target.closest('.btn-set-main').dataset.id;
        const resp = await fetch('<?= base_url("ProduitsVendeur/set_main_image/") ?>' + id, { method: 'POST' });
        const data = await resp.json();
        if (data.success) location.reload();
        else alert(data.error || 'Erreur');
    }
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
