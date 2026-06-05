<!-- admin/blog/posts/form.php -->

 <?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php';?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= $title; ?></h3>
                        <div class="card-tools">
                            <a href="<?= base_url('admin/blog/posts'); ?>" class="btn btn-sm btn-default">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Titre <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="<?= set_value('title', $post['title'] ?? ''); ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Contenu <span class="text-danger">*</span></label>
                                        <textarea name="content" class="form-control" rows="15"><?= set_value('content', $post['content'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Catégorie <span class="text-danger">*</span></label>
                                        <select name="id_categorie" class="form-control" required>
                                            <option value="">-- Sélectionner --</option>
                                            <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id_categorie']; ?>" <?= set_select('id_categorie', $cat['id_categorie'], (($post['id_categorie'] ?? '') == $cat['id_categorie'])); ?>>
                                                <?= htmlspecialchars($cat['nom'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Image à la une</label>
                                        <input type="text" name="featured_image" class="form-control" placeholder="/uploads/blog/image.jpg" value="<?= set_value('featured_image', $post['featured_image'] ?? ''); ?>">
                                        <small class="text-muted">Chemin relatif vers l'image</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Résumé</label>
                                        <textarea name="excerpt" class="form-control" rows="3" placeholder="Courte description de l'article"><?= set_value('excerpt', $post['excerpt'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Tags</label>
                                        <input type="text" name="tags" class="form-control" placeholder="tag1, tag2, tag3" value="<?= set_value('tags', $post['tags'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Date de publication</label>
                                        <input type="datetime-local" name="date_publication" class="form-control" value="<?= set_value('date_publication', isset($post['date_publication']) ? date('Y-m-d\TH:i', strtotime($post['date_publication'])) : ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Statut</label>
                                        <select name="status" class="form-control">
                                            <option value="brouillon" <?= set_select('status', 'brouillon', (($post['status'] ?? '') == 'brouillon')); ?>>Brouillon</option>
                                            <option value="publie" <?= set_select('status', 'publie', (($post['status'] ?? '') == 'publie')); ?>>Publié</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="allow_comments" value="1" <?= set_checkbox('allow_comments', '1', (($post['allow_comments'] ?? 1) == 1)); ?>>
                                                Autoriser les commentaires
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="<?= base_url('admin/blog/posts'); ?>" class="btn btn-default">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CKEditor pour l'édition du contenu -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>