<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
    <h4 class="card-title flex-grow-1"><?= $title ?></h4>
    <div class="d-flex gap-2">
        <a href="<?= base_url('article-commande/add') ?>" class="btn btn-sm btn-primary">
            <i class="bx bx-plus me-1"></i>Nouvel article
        </a>
        <a href="<?= base_url('ArticleCommande/exporter') ?>" class="btn btn-sm btn-secondary">
            <i class="bx bx-export me-1"></i>Exporter
        </a>
    </div>
</div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-primary bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:box-bold-duotone" class="fs-24 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->total_articles ?? 0) ?></h5>
                                        <small class="text-muted">Total articles</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-success bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:wallet-bold-duotone" class="fs-24 text-success"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->total_quantite ?? 0) ?></h5>
                                        <small class="text-muted">Quantité totale</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-info bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:chart-2-bold-duotone" class="fs-24 text-info"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->chiffre_affaires ?? 0, 0, ',', ' ') ?> FBu</h5>
                                        <small class="text-muted">Chiffre d'affaires</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-warning bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:hand-dollar-bold-duotone" class="fs-24 text-warning"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format($stats->revenus_vendeur ?? 0, 0, ',', ' ') ?> FBu</h5>
                                        <small class="text-muted">Revenus vendeur</small>
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
                                    <?php foreach ($statuts as $key => $s): ?>
                                        <option value="<?= $key ?>" <?= ($filters['statut_article'] ?? '') == $key ? 'selected' : '' ?>>
                                            <?= $s['label'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" value="<?= $filters['search'] ?? '' ?>" placeholder="Rechercher par commande ou produit...">
                            </div>
                            <div class="col-md-3">
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
                                        <th>N° Commande</th>
                                        <th>Produit</th>
                                        <th>Client</th>
                                        <th>Quantité</th>
                                        <th>Montant</th>
                                        <th>Commission</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($articles)): ?>
                                        <?php foreach ($articles as $a): ?>
                                            <tr>
                                                <td>#<?= $a->id_article ?></td>
                                                <td>
                                                    <strong><?= $a->numero_commande ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= date('d/m/Y', strtotime($a->date_creation)) ?></small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <?php if ($a->image_url): ?>
                                                            <img src="<?= base_url($a->image_url) ?>" class="rounded" width="40" height="40" style="object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                                <iconify-icon icon="solar:box-broken" class="fs-20 text-muted"></iconify-icon>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <strong><?= htmlspecialchars($a->nom_produit) ?></strong>
                                                            <?php if ($a->attributs_variante): ?>
                                                                <?php $attrs = json_decode($a->attributs_variante, true); ?>
                                                                <br>
                                                                <small class="text-muted">
                                                                    <?= isset($attrs['taille']) ? 'T: ' . $attrs['taille'] : '' ?>
                                                                    <?= isset($attrs['couleur']) ? ' | C: ' . $attrs['couleur'] : '' ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?= htmlspecialchars($a->prenom ?? '') ?> <?= htmlspecialchars($a->nom ?? '') ?>
                                                    <br>
                                                    <small class="text-muted"><?= htmlspecialchars($a->email ?? '-') ?></small>
                                                </td>
                                                <td><span class="badge bg-info"><?= $a->quantite ?></span></td>
                                                <td>
                                                    <strong><?= number_format($a->prix_total, 0, ',', ' ') ?> FBu</strong>
                                                    <br>
                                                    <small class="text-muted"><?= number_format($a->prix_unitaire, 0, ',', ' ') ?> FBu/unité</small>
                                                </td>
                                                <td>
                                                    <span class="text-warning"><?= number_format($a->montant_commission ?? 0, 0, ',', ' ') ?> FBu</span>
                                                    <br>
                                                    <small class="text-success">Revenu: <?= number_format($a->revenus_vendeur ?? 0, 0, ',', ' ') ?> FBu</small>
                                                </td>
                                                <td>
                                                    <?php $color = $statuts[$a->statut_article]['color'] ?? 'secondary'; ?>
                                                    <span class="badge bg-<?= $color ?> bg-opacity-10 text-<?= $color ?> px-3 py-2">
                                                        <?= $statuts[$a->statut_article]['label'] ?? $a->statut_article ?>
                                                    </span>
                                                    <?php if ($a->est_retourne): ?>
                                                        <br><small class="text-danger">Retourné</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
    <div class="d-flex gap-2">
        <a href="<?= base_url('article-commande/detail/' . $a->id_article) ?>" class="btn btn-light btn-sm" title="Voir">
            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
        </a>
        <a href="<?= base_url('article-commande/edit/' . $a->id_article) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
        </a>
        <?php if ($is_admin || $a->id_vendeur == $id_vendeur): ?>
            <button type="button" class="btn btn-soft-primary btn-sm change-statut" data-id="<?= $a->id_article ?>" data-statut="<?= $a->statut_article ?>" title="Changer statut">
                <iconify-icon icon="solar:refresh-bold-duotone" class="align-middle fs-18"></iconify-icon>
            </button>
        <?php endif; ?>
        <button type="button" class="btn btn-soft-danger btn-sm delete-article" data-id="<?= $a->id_article ?>" data-nom="<?= htmlspecialchars($a->nom_produit) ?>" title="Supprimer">
            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
        </button>
    </div>
</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:box-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucun article trouvé</h5>
                                                <p class="text-muted">Aucun article ne correspond à vos critères</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($articles)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Changer Statut -->
<div class="modal fade" id="changeStatutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le statut de l'article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changeStatutForm">
                <div class="modal-body">
                    <input type="hidden" name="id_article" id="statut_article_id">
                    <div class="mb-3">
                        <label class="form-label">Nouveau statut</label>
                        <select name="statut" id="nouveau_statut" class="form-select" required>
                            <?php foreach ($statuts as $key => $s): ?>
                                <option value="<?= $key ?>"><?= $s['label'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Commentaire (optionnel)</label>
                        <textarea name="commentaire" class="form-control" rows="3" placeholder="Ajouter un commentaire..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	// Supprimer un article
$('.delete-article').on('click', function() {
    const id = $(this).data('id');
    const nom = $(this).data('nom');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer l'article " + nom,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("ArticleCommande/delete/") ?>' + id, function(response) {
                if (response.success) {
                    Swal.fire('Supprimé!', response.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Erreur!', response.message, 'error');
                }
            }, 'json');
        }
    });
});
// Changer le statut
$('.change-statut').on('click', function() {
    const id = $(this).data('id');
    const statutActuel = $(this).data('statut');
    
    $('#statut_article_id').val(id);
    $('#nouveau_statut').val(statutActuel);
    $('#changeStatutModal').modal('show');
});

$('#changeStatutForm').on('submit', function(e) {
    e.preventDefault();
    
    $.post('<?= base_url("ArticleCommande/change_statut") ?>', $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Erreur', response.message, 'error');
        }
    }, 'json');
});
</script>

<style>
.btn-soft-primary { background-color: #cfe2ff; border-color: #cfe2ff; color: #0d6efd; }
.btn-soft-primary:hover { background-color: #b6d4fe; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>