<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:document-bold-duotone" class="me-2"></iconify-icon>
                            Mes documents
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('documents-vendeur/add') ?>" class="btn btn-sm btn-primary">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
                                Ajouter un document
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-info">
                            <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                            <strong>Informations :</strong> Les documents sont nécessaires pour la vérification de votre compte vendeur. 
                            Ils seront examinés par notre équipe.
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Numéro</th>
                                        <th>Document</th>
                                        <th>Date upload</th>
                                        <th>Date expiration</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($documents)): ?>
                                        <?php foreach ($documents as $d): ?>
                                            <tr>
                                                <td>#<?= $d->id_document ?></td>
                                                <td>
                                                    <?php
                                                    $types = $this->DocumentsVendeur_model->get_types_document();
                                                    echo $types[$d->type_document] ?? $d->type_document;
                                                    ?>
                                                </div>
                                                <td><?= htmlspecialchars($d->numero_document ?? '-') ?></div>
                                                <td>
                                                    <a href="<?= base_url('documents-vendeur/download/' . $d->id_document) ?>" class="btn btn-sm btn-info" target="_blank">
                                                        <iconify-icon icon="solar:download-bold-duotone"></iconify-icon> Télécharger
                                                    </a>
                                                </div>
                                                <td><?= date('d/m/Y', strtotime($d->date_upload)) ?></div>
                                                <td>
                                                    <?php if ($d->date_expiration): ?>
                                                        <?= date('d/m/Y', strtotime($d->date_expiration)) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <?php
                                                    $badge_class = '';
                                                    $badge_text = '';
                                                    switch($d->statut_verification) {
                                                        case 'en_attente':
                                                            $badge_class = 'warning';
                                                            $badge_text = '⏳ En attente';
                                                            break;
                                                        case 'verifie':
                                                            $badge_class = 'success';
                                                            $badge_text = '✅ Vérifié';
                                                            break;
                                                        case 'refuse':
                                                            $badge_class = 'danger';
                                                            $badge_text = '❌ Refusé';
                                                            if ($d->motif_refus) {
                                                                $badge_text .= '<br><small class="text-muted">' . htmlspecialchars($d->motif_refus) . '</small>';
                                                            }
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $badge_class ?>"><?= $badge_text ?></span>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('documents-vendeur/edit/' . $d->id_document) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken"></iconify-icon>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger delete-doc" 
                                                                data-id="<?= $d->id_document ?>"
                                                                data-type="<?= $types[$d->type_document] ?>"
                                                                title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <iconify-icon icon="solar:document-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun document trouvé</p>
                                                <a href="<?= base_url('documents-vendeur/add') ?>" class="btn btn-sm btn-primary">
                                                    Ajouter votre premier document
                                                </a>
                                            </div>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.delete-doc').on('click', function() {
        const id = $(this).data('id');
        const type = $(this).data('type');
        
        Swal.fire({
            title: 'Confirmation',
            text: `Supprimer le document "${type}" ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("documents-vendeur/delete/") ?>' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Supprimé', response.message, 'success').then(() => {
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
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>