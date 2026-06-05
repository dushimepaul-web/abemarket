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
        Gestion des documents vendeurs
    </h4>
    <div class="d-flex gap-2">
        <a href="<?= base_url('documents-vendeur/add') ?>" class="btn btn-sm btn-primary">
            <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon>
            Ajouter un document
        </a>
    </div>
</div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📄 Total documents</span>
                                        <strong><?= number_format($stats->total ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>⏳ En attente</span>
                                        <strong><?= number_format($stats->en_attente ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>✅ Vérifiés</span>
                                        <strong><?= number_format($stats->verifies ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-danger mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>❌ Refusés</span>
                                        <strong><?= number_format($stats->refuses ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="statut" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_attente" <?= ($filters['statut_verification'] ?? '') == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="verifie" <?= ($filters['statut_verification'] ?? '') == 'verifie' ? 'selected' : '' ?>>Vérifiés</option>
                                    <option value="refuse" <?= ($filters['statut_verification'] ?? '') == 'refuse' ? 'selected' : '' ?>>Refusés</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="id_vendeur" class="form-select">
                                    <option value="">Tous les vendeurs</option>
                                    <?php foreach ($vendeurs as $v): ?>
                                        <option value="<?= $v->id_vendeur ?>" <?= ($filters['id_vendeur'] ?? '') == $v->id_vendeur ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($v->nom_boutique) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="type_document" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="carte_identite" <?= ($filters['type_document'] ?? '') == 'carte_identite' ? 'selected' : '' ?>>Carte d'identité</option>
                                    <option value="passeport" <?= ($filters['type_document'] ?? '') == 'passeport' ? 'selected' : '' ?>>Passeport</option>
                                    <option value="licence_commerce" <?= ($filters['type_document'] ?? '') == 'licence_commerce' ? 'selected' : '' ?>>Licence de commerce</option>
                                    <option value="attestation_fiscale" <?= ($filters['type_document'] ?? '') == 'attestation_fiscale' ? 'selected' : '' ?>>Attestation fiscale</option>
                                    <option value="justificatif_domicile" <?= ($filters['type_document'] ?? '') == 'justificatif_domicile' ? 'selected' : '' ?>>Justificatif de domicile</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Vendeur</th>
                                        <th>Type</th>
                                        <th>Numéro</th>
                                        <th>Document</th>
                                        <th>Date upload</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($documents)): ?>
                                        <?php foreach ($documents as $d): ?>
                                            <tr>
                                                <td>#<?= $d->id_document ?></td>
                                                <td><strong><?= htmlspecialchars($d->vendeur_nom) ?></strong></div>
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
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?= $badge_class ?>"><?= $badge_text ?></span>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-success verifier-btn" 
                                                                data-id="<?= $d->id_document ?>"
                                                                data-statut="verifie"
                                                                data-vendeur="<?= htmlspecialchars($d->vendeur_nom) ?>"
                                                                title="Vérifier">
                                                            <iconify-icon icon="solar:check-circle-bold-duotone"></iconify-icon>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger verifier-btn" 
                                                                data-id="<?= $d->id_document ?>"
                                                                data-statut="refuse"
                                                                data-vendeur="<?= htmlspecialchars($d->vendeur_nom) ?>"
                                                                title="Refuser">
                                                            <iconify-icon icon="solar:close-circle-bold-duotone"></iconify-icon>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-btn" 
                                                                data-id="<?= $d->id_document ?>"
                                                                data-vendeur="<?= htmlspecialchars($d->vendeur_nom) ?>"
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
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($documents)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
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
    let currentId = null;
    let currentStatut = null;
    
    // Vérifier/Refuser
    $('.verifier-btn').on('click', function() {
        const id = $(this).data('id');
        const statut = $(this).data('statut');
        const vendeur = $(this).data('vendeur');
        
        if (statut === 'refuse') {
            $('#refus_id').val(id);
            $('#refusModal').modal('show');
        } else {
            Swal.fire({
                title: 'Confirmation',
                text: `Voulez-vous vérifier ce document pour ${vendeur} ?`,
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
        }
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
    
    // Supprimer
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        const vendeur = $(this).data('vendeur');
        
        Swal.fire({
            title: 'Confirmation',
            text: `Supprimer ce document pour ${vendeur} ?`,
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