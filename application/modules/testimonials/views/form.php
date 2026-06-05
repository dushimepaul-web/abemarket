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
                            <iconify-icon icon="solar:chat-round-like-bold-duotone" class="me-2"></iconify-icon>
                            <?= $title; ?>
                        </h4>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                                            <input type="text" name="nom" class="form-control" 
                                                   value="<?= set_value('nom', $testimonial->nom ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                            <input type="text" name="prenom" class="form-control" 
                                                   value="<?= set_value('prenom', $testimonial->prenom ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Poste / Fonction</label>
                                        <input type="text" name="poste" class="form-control" 
                                               value="<?= set_value('poste', $testimonial->poste ?? ''); ?>" 
                                               placeholder="Ex: Gérant de boutique, Client, Vendeur...">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Témoignage <span class="text-danger">*</span></label>
                                        <textarea name="message" class="form-control" rows="6" required placeholder="Ce que le client dit..."><?= set_value('message', $testimonial->message ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">URL de la photo</label>
                                        <input type="text" name="photo_url" class="form-control" 
                                               value="<?= set_value('photo_url', $testimonial->photo_url ?? ''); ?>" 
                                               placeholder="/assets/frontend/images/user/photo.jpg">
                                        <small class="text-muted">Chemin relatif vers la photo du client (optionnel)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Note <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <label class="btn btn-outline-warning">
                                                <input type="radio" name="note" value="<?= $i; ?>" 
                                                       <?= set_radio('note', $i, (($testimonial->note ?? 5) == $i)); ?>>
                                                <?= $i; ?>★
                                            </label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Ordre d'affichage</label>
                                        <input type="number" name="ordre_affichage" class="form-control" 
                                               value="<?= set_value('ordre_affichage', $testimonial->ordre_affichage ?? 0); ?>">
                                        <small class="text-muted">Plus le chiffre est petit, plus le témoignage apparaît en haut.</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Statut</label>
                                        <select name="est_approuve" class="form-select">
                                            <option value="1" <?= set_select('est_approuve', '1', (($testimonial->est_approuve ?? 1) == 1)); ?>>Approuvé (visible sur le site)</option>
                                            <option value="0" <?= set_select('est_approuve', '0', (($testimonial->est_approuve ?? 1) == 0)); ?>>En attente (non visible)</option>
                                        </select>
                                    </div>
                                    
                                    <?php if (!empty($testimonial->photo_url)): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Aperçu de la photo</label>
                                        <div>
                                            <img src="<?= base_url($testimonial->photo_url); ?>" style="max-width: 100px; border-radius: 50%;" alt="">
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:save-bold"></iconify-icon>
                                Enregistrer
                            </button>
                            <a href="<?= base_url('admin/testimonials'); ?>" class="btn btn-secondary">
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