<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:document-bold-duotone" class="me-2"></iconify-icon>
                            <?= isset($document) ? 'Modifier le document' : 'Ajouter un document' ?>
                        </h4>
                        <a href="<?= base_url('documents-vendeur/mes-documents') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type de document <span class="text-danger">*</span></label>
                                    <select name="type_document" class="form-select" required>
                                        <option value="">Sélectionner</option>
                                        <?php foreach ($types_document as $key => $label): ?>
                                            <option value="<?= $key ?>" <?= isset($document) && $document->type_document == $key ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Numéro de document</label>
                                    <input type="text" name="numero_document" class="form-control" 
                                           value="<?= isset($document) ? htmlspecialchars($document->numero_document) : '' ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fichier document <?= !isset($document) ? '<span class="text-danger">*</span>' : '' ?></label>
                                    <input type="file" name="fichier_document" class="form-control" accept=".pdf,.jpg,.jpeg,.png" <?= !isset($document) ? 'required' : '' ?>>
                                    <small class="text-muted">Formats acceptés: PDF, JPG, PNG. Max 5MB</small>
                                    <?php if (isset($document) && $document->fichier_document): ?>
                                        <div class="mt-2">
                                            <a href="<?= base_url('documents-vendeur/download/' . $document->id_document) ?>" class="btn btn-sm btn-info" target="_blank">
                                                <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon> Voir document actuel
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date d'expiration</label>
                                    <input type="date" name="date_expiration" class="form-control" 
                                           value="<?= isset($document) && $document->date_expiration ? date('Y-m-d', strtotime($document->date_expiration)) : '' ?>">
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                                Les documents seront vérifiés par notre équipe. Assurez-vous qu'ils sont lisibles et à jour.
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i><?= isset($document) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('documents-vendeur/mes-documents') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>