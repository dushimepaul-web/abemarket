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
                            Détails du document - #<?= $document->id_document ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('documents-vendeur') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- En-tête avec statut -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <?php
                                $statut_color = '';
                                $statut_icon = '';
                                switch($document->statut_verification) {
                                    case 'en_attente':
                                        $statut_color = 'warning';
                                        $statut_icon = '⏳';
                                        break;
                                    case 'verifie':
                                        $statut_color = 'success';
                                        $statut_icon = '✅';
                                        break;
                                    case 'refuse':
                                        $statut_color = 'danger';
                                        $statut_icon = '❌';
                                        break;
                                }
                                ?>
                                <div class="alert alert-<?= $statut_color ?> d-flex align-items-center">
                                    <span class="fs-2 me-3"><?= $statut_icon ?></span>
                                    <div>
                                        <strong>Statut : <?= ucfirst(str_replace('_', ' ', $document->statut_verification)) ?></strong>
                                        <?php if ($document->statut_verification == 'verifie' && $document->date_verification): ?>
                                            <br>Vérifié le <?= date('d/m/Y à H:i', strtotime($document->date_verification)) ?>
                                        <?php endif; ?>
                                        <?php if ($document->statut_verification == 'refuse' && $document->motif_refus): ?>
                                            <br><strong>Motif :</strong> <?= htmlspecialchars($document->motif_refus) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Informations vendeur -->
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">🏪 Informations vendeur</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="150"><strong>Nom boutique :</strong></td>
                                                <td><strong><?= htmlspecialchars($document->vendeur_nom) ?></strong></div>
                                            </tr>
                                            <tr>
                                                <td><strong>ID Vendeur :</strong></td>
                                                <td>#<?= $document->id_vendeur ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Email :</strong></td>
                                                <td>
                                                    <?php 
                                                    $vendeur = $this->db->select('u.email')
                                                              ->from('vendeurs v')
                                                              ->join('utilisateurs u', 'u.id_utilisateur = v.id_utilisateur')
                                                              ->where('v.id_vendeur', $document->id_vendeur)
                                                              ->get()
                                                              ->row();
                                                    echo htmlspecialchars($vendeur->email ?? 'Non disponible');
                                                    ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Documents :</strong></td>
                                                <td>
                                                    <a href="<?= base_url('documents-vendeur?statut=all&id_vendeur=' . $document->id_vendeur) ?>" class="btn btn-sm btn-outline-primary">
                                                        Voir tous les documents
                                                    </a>
                                                </div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations document -->
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">📄 Informations document</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="150"><strong>Type :</strong></td>
                                                <td>
                                                    <?php
                                                    $types = [
                                                        'carte_identite' => 'Carte d\'identité nationale',
                                                        'passeport' => 'Passeport',
                                                        'licence_commerce' => 'Licence de commerce',
                                                        'attestation_fiscale' => 'Attestation fiscale',
                                                        'justificatif_domicile' => 'Justificatif de domicile'
                                                    ];
                                                    echo $types[$document->type_document] ?? $document->type_document;
                                                    ?>
                                                </div>
                                            </tr>
                                            <tr>
                                                <td><strong>Numéro :</strong></td>
                                                <td><?= htmlspecialchars($document->numero_document ?? '-') ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Date upload :</strong></td>
                                                <td><?= date('d/m/Y à H:i', strtotime($document->date_upload)) ?></div>
                                            </tr>
                                            <tr>
                                                <td><strong>Date expiration :</strong></td>
                                                <td>
                                                    <?php if ($document->date_expiration): ?>
                                                        <?= date('d/m/Y', strtotime($document->date_expiration)) ?>
                                                        <?php if (strtotime($document->date_expiration) < time()): ?>
                                                            <span class="badge bg-danger ms-2">Expiré</span>
                                                        <?php elseif (strtotime($document->date_expiration) - time() < 30 * 24 * 3600): ?>
                                                            <span class="badge bg-warning ms-2">Expire bientôt</span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Non spécifiée</span>
                                                    <?php endif; ?>
                                                </div>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Prévisualisation du document -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">📎 Prévisualisation du document</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <?php 
                                        $file_ext = pathinfo($document->fichier_document, PATHINFO_EXTENSION);
                                        $file_path = base_url($document->fichier_document);
                                        ?>
                                        
                                        <?php if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                            <img src="<?= $file_path ?>" alt="Document" class="img-fluid rounded shadow" style="max-height: 400px;">
                                            <div class="mt-3">
                                                <a href="<?= $file_path ?>" target="_blank" class="btn btn-primary">
                                                    <iconify-icon icon="solar:eye-bold-duotone"></iconify-icon> Ouvrir en grand
                                                </a>
                                            </div>
                                        <?php elseif (strtolower($file_ext) == 'pdf'): ?>
                                            <embed src="<?= $file_path ?>" type="application/pdf" width="100%" height="500px" class="rounded shadow">
                                            <div class="mt-3">
                                                <a href="<?= $file_path ?>" target="_blank" class="btn btn-primary">
                                                    <iconify-icon icon="solar:download-bold-duotone"></iconify-icon> Télécharger le PDF
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="py-5">
                                                <iconify-icon icon="solar:document-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aperçu non disponible</p>
                                                <a href="<?= base_url('documents-vendeur/download/' . $document->id_document) ?>" class="btn btn-primary">
                                                    <iconify-icon icon="solar:download-bold-duotone"></iconify-icon> Télécharger
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Admin -->
                        <?php if ($document->statut_verification == 'en_attente'): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-dark text-white">
                                        <h6 class="mb-0">⚙️ Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success verifier-doc" data-id="<?= $document->id_document ?>" data-statut="verifie">
                                                <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon> Approuver le document
                                            </button>
                                            <button class="btn btn-danger refuser-doc" data-id="<?= $document->id_document ?>">
                                                <iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon> Refuser le document
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Refus -->
<div class="modal fade" id="refusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Motif du refus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="refusForm">
                <div class="modal-body">
                    <input type="hidden" name="id_document" id="refus_id">
                    <input type="hidden" name="statut" value="refuse">
                    <div class="mb-3">
                        <label class="form-label">Motif du refus <span class="text-danger">*</span></label>
                        <textarea name="motif_refus" class="form-control" rows="4" required placeholder="Expliquez pourquoi ce document est refusé..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Approuver
    $('.verifier-doc').on('click', function() {
        const id = $(this).data('id');
        const statut = $(this).data('statut');
        
        Swal.fire({
            title: 'Confirmation',
            text: 'Voulez-vous approuver ce document ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("documents-vendeur/verifier/") ?>' + id,
                    type: 'POST',
                    data: {statut: statut},
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Succès', response.message, 'success').then(() => {
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
    
    // Refuser
    $('.refuser-doc').on('click', function() {
        const id = $(this).data('id');
        $('#refus_id').val(id);
        $('#refusModal').modal('show');
    });
    
    $('#refusForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#refus_id').val();
        
        $.ajax({
            url: '<?= base_url("documents-vendeur/verifier/") ?>' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                }
            }
        });
    });
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>