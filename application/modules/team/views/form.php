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
                            <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="me-2"></iconify-icon>
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
                                                   value="<?= set_value('nom', $member->nom ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                            <input type="text" name="prenom" class="form-control" 
                                                   value="<?= set_value('prenom', $member->prenom ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Poste <span class="text-danger">*</span></label>
                                        <input type="text" name="poste" class="form-control" 
                                               value="<?= set_value('poste', $member->poste ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Biographie</label>
                                        <textarea name="bio" class="form-control" rows="6" placeholder="Présentation du membre..."><?= set_value('bio', $member->bio ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">URL de la photo</label>
                                        <input type="text" name="photo_url" class="form-control" 
                                               value="<?= set_value('photo_url', $member->photo_url ?? ''); ?>" 
                                               placeholder="/assets/frontend/images/user/photo.jpg">
                                        <small class="text-muted">Chemin relatif vers l'image du membre</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Ordre d'affichage</label>
                                        <input type="number" name="ordre_affichage" class="form-control" 
                                               value="<?= set_value('ordre_affichage', $member->ordre_affichage ?? 0); ?>">
                                        <small class="text-muted">Plus le chiffre est petit, plus le membre apparaît en haut.</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Statut</label>
                                        <select name="est_actif" class="form-select">
                                            <option value="1" <?= set_select('est_actif', '1', (($member->est_actif ?? 1) == 1)); ?>>Actif</option>
                                            <option value="0" <?= set_select('est_actif', '0', (($member->est_actif ?? 1) == 0)); ?>>Inactif</option>
                                        </select>
                                    </div>
                                    
                                    <div class="border-top pt-3 mt-3">
                                        <h6 class="mb-3">Réseaux sociaux</h6>
                                        
                                        <div class="mb-2">
                                            <label class="form-label">
                                                <iconify-icon icon="ri:facebook-fill" class="text-primary"></iconify-icon> Facebook
                                            </label>
                                            <input type="text" name="facebook_url" class="form-control" 
                                                   value="<?= set_value('facebook_url', $member->facebook_url ?? ''); ?>" 
                                                   placeholder="https://facebook.com/...">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label">
                                                <iconify-icon icon="ri:instagram-fill" class="text-danger"></iconify-icon> Instagram
                                            </label>
                                            <input type="text" name="instagram_url" class="form-control" 
                                                   value="<?= set_value('instagram_url', $member->instagram_url ?? ''); ?>" 
                                                   placeholder="https://instagram.com/...">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label">
                                                <iconify-icon icon="ri:linkedin-fill" class="text-info"></iconify-icon> LinkedIn
                                            </label>
                                            <input type="text" name="linkedin_url" class="form-control" 
                                                   value="<?= set_value('linkedin_url', $member->linkedin_url ?? ''); ?>" 
                                                   placeholder="https://linkedin.com/in/...">
                                        </div>
                                        
                                        <div class="mb-2">
                                            <label class="form-label">
                                                <iconify-icon icon="ri:twitter-x-fill"></iconify-icon> Twitter/X
                                            </label>
                                            <input type="text" name="twitter_url" class="form-control" 
                                                   value="<?= set_value('twitter_url', $member->twitter_url ?? ''); ?>" 
                                                   placeholder="https://twitter.com/...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <iconify-icon icon="solar:save-bold"></iconify-icon>
                                Enregistrer
                            </button>
                            <a href="<?= base_url('admin/team'); ?>" class="btn btn-secondary">
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