<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Gestion des avis produits</h4>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-primary bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:chat-square-like-bold-duotone" class="fs-24 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->total_avis ?? 0) ?></h5>
                                        <small class="text-muted">Total avis</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-success bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-24 text-success"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->approuves ?? 0) ?></h5>
                                        <small class="text-muted">Approuvés</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-warning bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-24 text-warning"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->en_attente ?? 0) ?></h5>
                                        <small class="text-muted">En attente</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-info bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:star-bold-duotone" class="fs-24 text-info"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->note_moyenne ?? 0, 1) ?> / 5</h5>
                                        <small class="text-muted">Note moyenne</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Répartition des notes -->
                    <div class="card-body border-bottom">
                        <h6 class="mb-3">Répartition des notes</h6>
                        <div class="row">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                            <div class="col-md-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-warning">
                                        <?php for ($j = 1; $j <= 5; $j++): ?>
                                            <iconify-icon icon="solar:star-<?= $j <= $i ? 'bold' : 'linear' ?>-duotone" class="fs-14"></iconify-icon>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height: 8px;">
                                            <?php 
                                            $total = array_sum($repartition);
                                            $pourcent = $total > 0 ? ($repartition[$i] / $total) * 100 : 0;
                                            ?>
                                            <div class="progress-bar bg-warning" style="width: <?= $pourcent ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="text-muted small"><?= $repartition[$i] ?></div>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <select name="statut" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" <?= ($filters['est_approuve'] ?? '') === '1' ? 'selected' : '' ?>>Approuvés</option>
                                    <option value="0" <?= ($filters['est_approuve'] ?? '') === '0' ? 'selected' : '' ?>>En attente</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="note" class="form-select">
                                    <option value="">Toutes les notes</option>
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?= $i ?>" <?= ($filters['note'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?> étoiles</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" value="<?= $filters['search'] ?? '' ?>" placeholder="Rechercher par produit, client, titre...">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Produit</th>
                                        <th>Client</th>
                                        <th>Note</th>
                                        <th>Avis</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($avis)): ?>
                                        <?php foreach ($avis as $a): ?>
                                            <tr>
                                                <td>#<?= $a->id_avis ?></td>
                                                <td>
                                                    <div>
                                                        <strong><?= htmlspecialchars($a->nom_produit) ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?= htmlspecialchars($a->sku) ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?= htmlspecialchars($a->prenom ?? '') ?> <?= htmlspecialchars($a->nom ?? '') ?>
                                                    <br>
                                                    <small class="text-muted"><?= htmlspecialchars($a->email ?? '-') ?></small>
                                                </td>
                                                <td>
                                                    <div class="text-warning">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <iconify-icon icon="solar:star-<?= $i <= $a->note ? 'bold' : 'linear' ?>-duotone" class="fs-16"></iconify-icon>
                                                        <?php endfor; ?>
                                                        <span class="text-muted ms-1">(<?= $a->note ?>/5)</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <?php if ($a->titre): ?>
                                                            <strong><?= htmlspecialchars($a->titre) ?></strong><br>
                                                        <?php endif; ?>
                                                        <small class="text-muted"><?= htmlspecialchars(substr($a->commentaire ?? '', 0, 80)) ?>...</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($a->est_approuve): ?>
                                                        <span class="badge bg-success">Approuvé</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">En attente</span>
                                                    <?php endif; ?>
                                                    <?php if ($a->reponse_vendeur): ?>
                                                        <br><small class="text-info">Répondu</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($a->date_creation)) ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('avis-produit/detail/' . $a->id_avis) ?>" class="btn btn-light btn-sm" title="Voir">
                                                            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                        <?php if (!$a->est_approuve): ?>
                                                            <button type="button" class="btn btn-soft-success btn-sm approuver" data-id="<?= $a->id_avis ?>" title="Approuver">
                                                                <iconify-icon icon="solar:check-circle-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                            </button>
                                                            <button type="button" class="btn btn-soft-danger btn-sm rejeter" data-id="<?= $a->id_avis ?>" title="Rejeter">
                                                                <iconify-icon icon="solar:close-circle-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                            </button>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-soft-primary btn-sm repondre" data-id="<?= $a->id_avis ?>" data-produit="<?= htmlspecialchars($a->nom_produit) ?>" data-client="<?= htmlspecialchars($a->prenom . ' ' . $a->nom) ?>" title="Répondre">
                                                            <iconify-icon icon="solar:chat-round-like-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-soft-danger btn-sm delete-avis" data-id="<?= $a->id_avis ?>" data-produit="<?= htmlspecialchars($a->nom_produit) ?>" title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <iconify-icon icon="solar:chat-square-like-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucun avis trouvé</h5>
                                                <p class="text-muted">Aucun avis ne correspond à vos critères</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($avis)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Répondre -->
<div class="modal fade" id="repondreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Répondre à l'avis</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="repondreForm">
                <div class="modal-body">
                    <input type="hidden" name="id_avis" id="reponse_id_avis">
                    <div class="mb-3">
                        <label class="form-label">Produit</label>
                        <input type="text" id="reponse_produit" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <input type="text" id="reponse_client" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Votre réponse <span class="text-danger">*</span></label>
                        <textarea name="reponse" id="reponse_texte" class="form-control" rows="4" required placeholder="Merci pour votre avis..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Approuver
$('.approuver').on('click', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous approuver cet avis ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("avis-produit/approuver/") ?>' + id, function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                }
            }, 'json');
        }
    });
});

// Rejeter
$('.rejeter').on('click', function() {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous rejeter cet avis ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("avis-produit/rejeter/") ?>' + id, function(response) {
                if (response.success) {
                    Swal.fire('Succès', response.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur', response.message, 'error');
                }
            }, 'json');
        }
    });
});

// Répondre
$('.repondre').on('click', function() {
    const id = $(this).data('id');
    const produit = $(this).data('produit');
    const client = $(this).data('client');
    
    $('#reponse_id_avis').val(id);
    $('#reponse_produit').val(produit);
    $('#reponse_client').val(client);
    $('#repondreModal').modal('show');
});

$('#repondreForm').on('submit', function(e) {
    e.preventDefault();
    if (!$('#reponse_texte').val()) {
        Swal.fire('Erreur', 'La réponse ne peut pas être vide', 'error');
        return;
    }
    
    $.post('<?= base_url("avis-produit/repondre") ?>', $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Erreur', response.message, 'error');
        }
    }, 'json');
});

// Supprimer
$('.delete-avis').on('click', function() {
    const id = $(this).data('id');
    const produit = $(this).data('produit');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer l'avis pour " + produit,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("avis-produit/delete/") ?>' + id, function(response) {
                if (response.success) {
                    Swal.fire('Supprimé!', response.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                }
            }, 'json');
        }
    });
});
</script>

<style>
.btn-soft-success { background-color: #d1e7dd; border-color: #d1e7dd; color: #198754; }
.btn-soft-success:hover { background-color: #b8e0c4; }
.btn-soft-primary { background-color: #cfe2ff; border-color: #cfe2ff; color: #0d6efd; }
.btn-soft-primary:hover { background-color: #b6d4fe; }
.btn-soft-danger { background-color: #f8d7da; border-color: #f8d7da; color: #dc3545; }
.btn-soft-danger:hover { background-color: #f5c2c7; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>