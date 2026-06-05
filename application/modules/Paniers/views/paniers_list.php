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
                            <iconify-icon icon="solar:cart-bold-duotone" class="me-2"></iconify-icon>
                            Gestion des paniers
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('paniers/exporter') ?>" class="btn btn-sm btn-success">
                                <iconify-icon icon="solar:export-bold-duotone"></iconify-icon>
                                Exporter CSV
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>👥 Utilisateurs actifs</span>
                                        <strong><?= number_format($stats->utilisateurs_actifs ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>📦 Articles dans paniers</span>
                                        <strong><?= number_format($stats->total_articles ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>🔢 Quantité totale</span>
                                        <strong><?= number_format($stats->total_quantite ?? 0) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between">
                                        <span>💰 Valeur totale</span>
                                        <strong><?= number_format($stats->valeur_totale ?? 0, 0, ',', ' ') ?> FBu</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Client, produit..." value="<?= $filters['search'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date_debut" class="form-control" value="<?= $filters['date_debut'] ?? '' ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date_fin" class="form-control" value="<?= $filters['date_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
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
                                        <th>Client</th>
                                        <th>Produit</th>
                                        <th>Variante</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Total</th>
                                        <th>Date ajout</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($paniers)): ?>
                                        <?php foreach ($paniers as $p): 
                                            $prix = $p->prix_promo && $p->prix_promo < $p->prix_base ? $p->prix_promo : $p->prix_base;
                                            $total = $prix * $p->quantite;
                                        ?>
                                            <tr>
                                                <td>#<?= $p->id_panier ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($p->prenom . ' ' . $p->nom) ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($p->email) ?></small>
                                                </div>
                                                <td>
                                                    <strong><?= htmlspecialchars($p->nom_produit) ?></strong>
                                                    <br><small class="text-muted">SKU: <?= htmlspecialchars($p->sku) ?></small>
                                                </div>
                                                <td>
                                                    <?php if ($p->variante_sku): ?>
                                                        <span class="badge bg-info"><?= htmlspecialchars($p->variante_sku) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Standard</span>
                                                    <?php endif; ?>
                                                </div>
                                                <td><?= $p->quantite ?></div>
                                                <td><?= number_format($prix, 0, ',', ' ') ?> FBu</div>
                                                <td><strong class="text-primary"><?= number_format($total, 0, ',', ' ') ?> FBu</strong></div>
                                                <td><?= date('d/m/Y H:i', strtotime($p->date_ajout)) ?></div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('paniers/detail/' . $p->id_utilisateur) ?>" class="btn btn-sm btn-info" title="Voir panier client">
                                                            <iconify-icon icon="solar:eye-broken"></iconify-icon>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger delete-article" data-id="<?= $p->id_panier ?>" data-produit="<?= htmlspecialchars($p->nom_produit) ?>" title="Supprimer l'article">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <iconify-icon icon="solar:cart-broken" class="fs-48 text-muted"></iconify-icon>
                                                <p class="mt-2">Aucun article dans les paniers</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <?php if (!empty($paniers)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Supprimer un article du panier
$('.delete-article').on('click', function() {
    const id = $(this).data('id');
    const produit = $(this).data('produit');
    
    Swal.fire({
        title: 'Confirmation',
        text: `Supprimer "${produit}" du panier ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("paniers/delete_article/") ?>' + id,
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
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>