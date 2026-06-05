<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:question-circle-bold-duotone" class="me-2"></iconify-icon>
                            <?= $title; ?>
                        </h4>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Question <span class="text-danger">*</span></label>
                                        <input type="text" name="question" class="form-control" 
                                               value="<?= set_value('question', $faq->question ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Réponse <span class="text-danger">*</span></label>
                                        <textarea name="reponse" class="form-control" rows="8" required><?= set_value('reponse', $faq->reponse ?? ''); ?></textarea>
                                        <small class="text-muted">Vous pouvez utiliser du HTML pour formater la réponse.</small>
                                    </div>
                                  </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Ordre d'affichage</label>
                                        <input type="number" name="ordre_affichage" class="form-control" value="<?= set_value('ordre_affichage', $faq->ordre_affichage ?? 0); ?>">
                                        <small class="text-muted">Plus le chiffre est petit, plus la FAQ apparaît en haut.</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Statut</label>
                                        <select name="est_actif" class="form-select">
                                            <option value="1" <?= set_select('est_actif', '1', (($faq->est_actif ?? 1) == 1)); ?>>Actif</option>
                                            <option value="0" <?= set_select('est_actif', '0', (($faq->est_actif ?? 1) == 0)); ?>>Inactif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:save-bold"></iconify-icon>
                                Enregistrer
                            </button>
                            <a href="<?= base_url('admin/faq'); ?>" class="btn btn-secondary">
                                <iconify-icon icon="solar:arrow-left-bold"></iconify-icon>
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>