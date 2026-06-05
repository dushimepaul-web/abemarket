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
        <a href="<?= base_url('Commande/litige/add') ?>" class="btn btn-sm btn-primary">
            <i class="bx bx-plus me-1"></i>Nouveau litige
        </a>
        <a href="<?= base_url('Commande/LitigeCommande/exporter') ?>" class="btn btn-sm btn-secondary">
            <i class="bx bx-export me-1"></i>Exporter
        </a>
    </div>
</div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-primary bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:flag-bold-duotone" class="fs-24 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= array_sum($stats) ?></h5>
                                        <small class="text-muted">Total litiges</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-warning bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-24 text-warning"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= $stats['ouvert'] ?? 0 ?></h5>
                                        <small class="text-muted">Ouverts</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-info bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:chat-round-bold-duotone" class="fs-24 text-info"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= $stats['en_mediation'] ?? 0 ?></h5>
                                        <small class="text-muted">En médiation</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-success bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-24 text-success"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= ($stats['resolu_acheteur'] ?? 0) + ($stats['resolu_vendeur'] ?? 0) ?></h5>
                                        <small class="text-muted">Résolus</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-secondary bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:lock-bold-duotone" class="fs-24 text-secondary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= $stats['ferme'] ?? 0 ?></h5>
                                        <small class="text-muted">Fermés</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex align-items-center gap-2 p-2 rounded bg-light">
                                    <div class="rounded bg-danger bg-opacity-10 p-2">
                                        <iconify-icon icon="solar:hand-dollar-bold-duotone" class="fs-24 text-danger"></iconify-icon>
                                    </div>
                                    <div>
                                        <h5 class="mb-0"><?= number_format(array_sum($raisons_stats), 0) ?></h5>
                                        <small class="text-muted">Raisons diverses</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <select name="statut" class="form-select">
                                    <option value="">Tous statuts</option>
                                    <?php foreach ($statuts as $key => $s): ?>
                                        <option value="<?= $key ?>" <?= ($filters['statut'] ?? '') == $key ? 'selected' : '' ?>>
                                            <?= $s['label'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="raison" class="form-select">
                                    <option value="">Toutes raisons</option>
                                    <?php foreach ($raisons as $key => $r): ?>
                                        <option value="<?= $key ?>" <?= ($filters['raison'] ?? '') == $key ? 'selected' : '' ?>>
                                            <?= $r['label'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="type_plaignant" class="form-select">
                                    <option value="">Tous plaignants</option>
                                    <option value="acheteur" <?= ($filters['type_plaignant'] ?? '') == 'acheteur' ? 'selected' : '' ?>>Acheteur</option>
                                    <option value="vendeur" <?= ($filters['type_plaignant'] ?? '') == 'vendeur' ? 'selected' : '' ?>>Vendeur</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" value="<?= $filters['search'] ?? '' ?>" placeholder="Rechercher par commande ou client...">
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
                                        <th>N° Commande</th>
                                        <th>Plaignant</th>
                                        <th>Défendeur</th>
                                        <th>Raison</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($litiges)): ?>
                                        <?php foreach ($litiges as $l): ?>
                                            <tr>
                                                <td>#<?= $l->id_litige ?></td>
                                                <td><strong><?= $l->numero_commande ?></strong></td>
                                                <td>
                                                    <?= htmlspecialchars($l->plaignant_prenom ?? '') ?> <?= htmlspecialchars($l->plaignant_nom ?? '') ?>
                                                    <br>
                                                    <small class="text-muted"><?= $l->type_plaignant == 'acheteur' ? '👤 Client' : '🏪 Vendeur' ?></small>
                                                </td>
                                                <td>
                                                    <?= htmlspecialchars($l->defendeur_prenom ?? '') ?> <?= htmlspecialchars($l->defendeur_nom ?? '') ?>
                                                    <?php if ($l->vendeur_nom): ?>
                                                        <br><small class="text-muted">🏪 <?= htmlspecialchars($l->vendeur_nom) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php $r = $raisons[$l->raison] ?? ['label' => $l->raison, 'color' => 'secondary']; ?>
                                                    <span class="badge bg-<?= $r['color'] ?> bg-opacity-10 text-<?= $r['color'] ?>">
                                                        <iconify-icon icon="solar:<?= $r['icon'] ?>-bold-duotone" class="me-1"></iconify-icon>
                                                        <?= $r['label'] ?>
                                                    </span>
                                                </td>
                                                <td><strong><?= number_format($l->montant_total, 0, ',', ' ') ?> FBu</strong></td>
                                                <td>
                                                    <?php $s = $statuts[$l->statut] ?? ['label' => $l->statut, 'color' => 'secondary']; ?>
                                                    <span class="badge bg-<?= $s['color'] ?> bg-opacity-10 text-<?= $s['color'] ?> px-3 py-2">
                                                        <iconify-icon icon="solar:<?= $s['icon'] ?>-bold-duotone" class="me-1"></iconify-icon>
                                                        <?= $s['label'] ?>
                                                    </span>
                                                    <?php if ($l->mediateur_id): ?>
                                                        <br><small class="text-muted">Médiateur: <?= htmlspecialchars($l->med_prenom ?? '') ?> <?= htmlspecialchars($l->med_nom ?? '') ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($l->date_creation)) ?></td>
                                               <td>
    <div class="d-flex gap-2">
        <a href="<?= base_url('litige/detail/' . $l->id_litige) ?>" class="btn btn-light btn-sm" title="Voir">
            <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
        </a>
        <a href="<?= base_url('litige/edit/' . $l->id_litige) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
        </a>
        <button type="button" class="btn btn-soft-danger btn-sm delete-litige" data-id="<?= $l->id_litige ?>" data-numero="<?= $l->numero_commande ?>" title="Supprimer">
            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
        </button>
    </div>
</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:flag-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucun litige trouvé</h5>
                                                <p class="text-muted">Aucun litige ne correspond à vos critères</p>
                                                <a href="<?= base_url('LitigeCommande/creer') ?>" class="btn btn-primary mt-2">Créer un litige</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($litiges)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-soft-primary { background-color: #cfe2ff; border-color: #cfe2ff; color: #0d6efd; }
.btn-soft-primary:hover { background-color: #b6d4fe; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<script type="text/javascript">
	// Supprimer un litige
$('.delete-litige').on('click', function() {
    const id = $(this).data('id');
    const numero = $(this).data('numero');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer le litige de la commande " + numero,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= base_url("LitigeCommande/delete/") ?>' + id, function(response) {
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
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>