<!-- admin/about/list.php -->

<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>
<div class="page-content">
    <div class="container-fluid">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?? 'Gestion de la page "À propos"'; ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
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
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Section</th>
                                    <th>Titre</th>
                                    <th>Contenu (aperçu)</th>
                                    <th>Image</th>
                                    <th>Ordre</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($contents)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Aucun contenu trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($contents as $content): ?>
                                    <tr>
                                        <td><?= $content['id_about']; ?></td>
                                        <td>
                                            <span class="badge badge-info"><?= htmlspecialchars($content['section_key'], ENT_QUOTES, 'UTF-8'); ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($content['title'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td>
                                            <?php 
                                            $preview = strip_tags($content['content'] ?? '');
                                            echo htmlspecialchars(substr($preview, 0, 80), ENT_QUOTES, 'UTF-8');
                                            echo strlen($preview) > 80 ? '...' : '';
                                            ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($content['image_url'])): ?>
                                                <img src="<?= base_url($content['image_url']); ?>" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                            <?php else: ?>
                                                <span class="text-muted">Aucune</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm order-input" 
                                                   data-id="<?= $content['id_about']; ?>" 
                                                   value="<?= $content['ordre_affichage']; ?>" 
                                                   style="width: 70px;">
                                        </td>
                                        <td>
                                            <button class="btn btn-sm toggle-status <?= $content['est_actif'] == 1 ? 'btn-success' : 'btn-secondary'; ?>" 
                                                    data-id="<?= $content['id_about']; ?>">
                                                <?= $content['est_actif'] == 1 ? 'Actif' : 'Inactif'; ?>
                                            </button>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/about/edit/' . $content['id_about']); ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Modifier
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <button id="updateOrderBtn" class="btn btn-warning">Mettre à jour l'ordre</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            url: '<?= base_url("admin/about/updateOrder"); ?>',
            type: 'POST',
            data: { orders: orders },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    });
    
    // Toggle statut
    $('.toggle-status').click(function() {
        var $btn = $(this);
        var id = $btn.data('id');
        
        $.ajax({
            url: '<?= base_url("admin/about/toggle/"); ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.new_status == 1) {
                        $btn.removeClass('btn-secondary').addClass('btn-success').text('Actif');
                    } else {
                        $btn.removeClass('btn-success').addClass('btn-secondary').text('Inactif');
                    }
                } else {
                    alert(response.message);
                }
            }
        });
    });
});
</script>