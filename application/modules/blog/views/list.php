<!-- admin/blog/posts/list.php -->
 <?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php';?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= $title ?? 'Articles de blog'; ?></h3>
                        <div class="card-tools">
                            <a href="<?= base_url('admin/blog/posts/add'); ?>" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> Ajouter un article
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <?= $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Filtres -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="btn-group">
                                    <a href="<?= base_url('admin/blog/posts'); ?>" class="btn btn-sm <?= !$current_status ? 'btn-primary' : 'btn-default'; ?>">Tous</a>
                                    <a href="<?= base_url('admin/blog/posts?status=publie'); ?>" class="btn btn-sm <?= $current_status == 'publie' ? 'btn-primary' : 'btn-default'; ?>">Publiés</a>
                                    <a href="<?= base_url('admin/blog/posts?status=brouillon'); ?>" class="btn btn-sm <?= $current_status == 'brouillon' ? 'btn-primary' : 'btn-default'; ?>">Brouillons</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        <th>Auteur</th>
                                        <th>Vues</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($posts)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center">Aucun article trouvé</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($posts as $post): ?>
                                        <tr>
                                            <td><?= $post['id_post']; ?></td>
                                            <td>
                                                <?php if (!empty($post['featured_image'])): ?>
                                                    <img src="<?= base_url($post['featured_image']); ?>" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('blog/' . $post['slug']); ?>" target="_blank">
                                                    <?= htmlspecialchars(substr($post['title'], 0, 50), ENT_QUOTES, 'UTF-8'); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge badge-info"><?= htmlspecialchars($post['categorie_nom'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($post['auteur_nom'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= $post['views']; ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($post['date_creation'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm toggle-status <?= $post['status'] == 'publie' ? 'btn-success' : 'btn-warning'; ?>" 
                                                        data-id="<?= $post['id_post']; ?>">
                                                    <?= $post['status'] == 'publie' ? 'Publié' : 'Brouillon'; ?>
                                                </button>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/blog/posts/edit/' . $post['id_post']); ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit">MODIFIER</i>
                                                </a>
                                                <button class="btn btn-sm btn-danger delete-post" data-id="<?= $post['id_post']; ?>" data-title="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <i class="fas fa-trash">DELETE</i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <div class="mt-3">
                            <nav>
                                <ul class="pagination">
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?= $i; ?>&status=<?= $current_status; ?>">
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
</div>

<script>
$(document).ready(function() {
    // Toggle statut
    $('.toggle-status').click(function() {
        var $btn = $(this);
        var id = $btn.data('id');
        
        $.ajax({
            url: '<?= base_url("admin/blog/posts/toggle/"); ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.new_status == 'publie') {
                        $btn.removeClass('btn-warning').addClass('btn-success').text('Publié');
                    } else {
                        $btn.removeClass('btn-success').addClass('btn-warning').text('Brouillon');
                    }
                } else {
                    alert(response.message);
                }
            }
        });
    });
    
    // Suppression
    $('.delete-post').click(function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        
        if (confirm('Êtes-vous sûr de vouloir supprimer l\'article "' + title + '" ?')) {
            window.location.href = '<?= base_url("admin/blog/posts/delete/"); ?>' + id;
        }
    });
});
</script>