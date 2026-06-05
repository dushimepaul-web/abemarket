<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Statistiques des approvisionnements -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-primary bg-opacity-10 p-3">
                                <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($stats_appro->total_appro ?? 0) ?></h3>
                                <p class="text-muted mb-0">Total approvisionnements</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-success bg-opacity-10 p-3">
                                <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="fs-32 text-success"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($stats_appro->total_quantite ?? 0) ?></h3>
                                <p class="text-muted mb-0">Unités reçues</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-warning bg-opacity-10 p-3">
                                <iconify-icon icon="solar:wallet-bold-duotone" class="fs-32 text-warning"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($stats_appro->total_cout ?? 0, 0, ',', ' ') ?> FBu</h3>
                                <p class="text-muted mb-0">Coût total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-info bg-opacity-10 p-3">
                                <iconify-icon icon="solar:shop-bold-duotone" class="fs-32 text-info"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($stats_appro->nb_fournisseurs ?? 0) ?></h3>
                                <p class="text-muted mb-0">Fournisseurs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques des produits (stock) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-dark bg-opacity-10 p-3">
                                <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-dark"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($total_produits ?? 0) ?></h3>
                                <p class="text-muted mb-0">Total produits</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-success bg-opacity-10 p-3">
                                <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-32 text-success"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($produits_actifs ?? 0) ?></h3>
                                <p class="text-muted mb-0">Produits actifs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-warning bg-opacity-10 p-3">
                                <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="fs-32 text-warning"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($produits_stock_bas ?? 0) ?></h3>
                                <p class="text-muted mb-0">Stock bas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-danger bg-opacity-10 p-3">
                                <iconify-icon icon="solar:alert-circle-bold-duotone" class="fs-32 text-danger"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($rupture_stock ?? 0) ?></h3>
                                <p class="text-muted mb-0">Rupture de stock</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des produits avec gestion de stock -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">📦 Gestion des stocks & approvisionnements</h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('approvisionnements/historique') ?>" class="btn btn-sm btn-info">
                                <i class="bx bx-history me-1"></i>Historique
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="id_categorie" class="form-select">
                                    <option value="">Toutes les catégories</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat->id_categorie ?>" <?= ($this->input->get('id_categorie') == $cat->id_categorie) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat->nom_categorie) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="statut_stock" class="form-select">
                                    <option value="">Tous les stocks</option>
                                    <option value="en_stock" <?= ($this->input->get('statut_stock') == 'en_stock') ? 'selected' : '' ?>>En stock</option>
                                    <option value="stock_bas" <?= ($this->input->get('statut_stock') == 'stock_bas') ? 'selected' : '' ?>>Stock bas</option>
                                    <option value="rupture_stock" <?= ($this->input->get('statut_stock') == 'rupture_stock') ? 'selected' : '' ?>>Rupture</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" value="<?= $this->input->get('search') ?>" placeholder="Rechercher un produit...">
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
                                        <th>Image</th>
                                        <th>Produit</th>
                                        <th>SKU</th>
                                        <th>Stock actuel</th>
                                        <th>Statut</th>
                                        <th>Prix vente</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($produits)): ?>
                                        <?php foreach ($produits as $p): 
                                            $image_url = isset($p['image_url']) ? $p['image_url'] : null;
                                            $image_exists = $image_url && file_exists(FCPATH . ltrim($image_url, '/'));
                                            
                                            // Déterminer la classe CSS pour le statut
                                            $stock_class = '';
                                            $stock_text = '';
                                            if ($p['quantite_actuelle'] <= 0) {
                                                $stock_class = 'bg-danger';
                                                $stock_text = 'Rupture';
                                            } elseif ($p['quantite_actuelle'] <= $p['seuil_stock_bas']) {
                                                $stock_class = 'bg-warning';
                                                $stock_text = 'Stock bas';
                                            } else {
                                                $stock_class = 'bg-success';
                                                $stock_text = 'En stock';
                                            }
                                        ?>
                                            <tr>
                                                <td>
                                                    <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                        <?php if ($image_exists): ?>
                                                            <img src="<?= base_url($image_url) ?>" alt="<?= htmlspecialchars($p['nom_produit']) ?>" class="avatar-md" style="object-fit: cover; width: 48px; height: 48px; border-radius: 8px;">
                                                        <?php else: ?>
                                                            <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <td>
                                                    <div>
                                                        <a href="<?= base_url('produits/view/'.$p['slug_produit']) ?>" class="text-dark fw-medium fs-15"><?= htmlspecialchars($p['nom_produit']) ?></a>
                                                        <p class="text-muted mb-0 mt-1 fs-13">
                                                            <span>Catégorie : </span><?= htmlspecialchars($p['nom_categorie'] ?? 'Non catégorisé') ?>
                                                        </p>
                                                        <?php if($p['type_produit'] == 'variable'): ?>
                                                            <span class="badge bg-info mt-1">Variable</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <td><code><?= htmlspecialchars($p['sku']) ?></code></div>
                                                <td>
                                                    <h5 class="mb-0"><?= number_format($p['quantite_actuelle']) ?></h5>
                                                    <small class="text-muted">Seuil: <?= $p['seuil_stock_bas'] ?></small>
                                                </div>
                                                <td>
                                                    <span class="badge <?= $stock_class ?> p-2"><?= $stock_text ?></span>
                                                </div>
                                                <td>
                                                    <strong><?= number_format($p['prix_base'], 0, ',', ' ') ?> FBu</strong>
                                                    <?php if($p['prix_promo'] && $p['prix_promo'] < $p['prix_base']): ?>
                                                        <br><small class="text-danger">Promo: <?= number_format($p['prix_promo'], 0, ',', ' ') ?> FBu</small>
                                                    <?php endif; ?>
                                                </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-soft-success btn-sm ajouter-stock" 
                                                                data-slug="<?= $p['slug_produit'] ?>"
                                                                data-produit="<?= htmlspecialchars($p['nom_produit']) ?>"
                                                                data-sku="<?= htmlspecialchars($p['sku']) ?>"
                                                                data-type="<?= $p['type_produit'] ?>"
                                                                title="Ajouter du stock">
                                                            <iconify-icon icon="solar:add-circle-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                            Stock
                                                        </button>
                                                        <?php if($p['type_produit'] == 'variable'): ?>
                                                            <button type="button" class="btn btn-soft-info btn-sm gerer-variantes" 
                                                                    data-slug="<?= $p['slug_produit'] ?>"
                                                                    data-produit="<?= htmlspecialchars($p['nom_produit']) ?>"
                                                                    title="Gérer les variantes">
                                                                <iconify-icon icon="solar:box-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                                Variantes
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <iconify-icon icon="solar:box-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucun produit trouvé</h5>
                                                <p class="text-muted">Aucun produit ne correspond à vos critères</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($produits)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter du stock -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:add-circle-bold-duotone" class="me-1"></iconify-icon>
                    Ajouter du stock
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockForm">
                <div class="modal-body">
                    <input type="hidden" name="slug_produit" id="stock_slug">
                    <div class="mb-3">
                        <label class="form-label">Produit</label>
                        <input type="text" id="stock_produit" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" id="stock_sku" class="form-control bg-light" readonly>
                    </div>
                    
                    <!-- Pour les produits variables, afficher les variantes -->
                    <div class="mb-3" id="variante_container" style="display: none;">
                        <label class="form-label">Variante</label>
                        <select name="id_variante" id="stock_variante" class="form-select">
                            <option value="">-- Sélectionner une variante --</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Quantité à ajouter <span class="text-danger">*</span></label>
                        <input type="number" name="quantite" id="stock_quantite" class="form-control" required min="1" value="1">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Prix d'achat unitaire (FBu) <span class="text-danger">*</span></label>
                        <input type="number" name="prix_achat_unitaire" id="stock_prix" class="form-control" required min="1" step="1">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fournisseur</label>
                        <input type="text" name="fournisseur" id="stock_fournisseur" class="form-control" placeholder="Nom du fournisseur">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Référence bon de commande</label>
                        <input type="text" name="reference_bon" id="stock_reference" class="form-control" placeholder="Référence">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="stock_note" class="form-control" rows="2" placeholder="Informations supplémentaires..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <iconify-icon icon="solar:info-circle-bold-duotone" class="me-1"></iconify-icon>
                        Cette opération va <strong>ajouter</strong> la quantité au stock actuel et enregistrer l'historique.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Ajouter au stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Gérer les variantes -->
<div class="modal fade" id="variantesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:box-bold-duotone" class="me-1"></iconify-icon>
                    Gérer les variantes
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="variantesContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Chargement des variantes...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Ajouter du stock (produit simple ou variable)
$('.ajouter-stock').on('click', function() {
    const slug = $(this).data('slug');
    const produit = $(this).data('produit');
    const sku = $(this).data('sku');
    const type = $(this).data('type');
    
    $('#stock_slug').val(slug);
    $('#stock_produit').val(produit);
    $('#stock_sku').val(sku);
    $('#stock_quantite').val(1);
    $('#stock_prix').val('');
    $('#stock_fournisseur').val('');
    $('#stock_reference').val('');
    $('#stock_note').val('');
    
    if (type === 'variable') {
        $('#variante_container').show();
        chargerVariantes(slug);
    } else {
        $('#variante_container').hide();
        $('#stock_variante').val('');
    }
    
    $('#stockModal').modal('show');
});

// Charger les variantes d'un produit
function chargerVariantes(slug) {
    $.ajax({
        url: '<?= site_url("approvisionnements/get_variantes_by_slug") ?>',
        type: 'POST',
        data: {slug: slug},
        dataType: 'json',
        timeout: 10000,
        success: function(variantes) {
            let options = '<option value="">-- Sélectionner une variante --</option>';
            if (variantes && variantes.length > 0) {
                variantes.forEach(function(v) {
                    let attributs = '';
                    if (v.attributs && typeof v.attributs === 'object') {
                        attributs = Object.values(v.attributs).join(' - ');
                    }
                    options += `<option value="${v.id_variante}" data-stock="${v.quantite_actuelle}">${escapeHtml(v.sku)} - ${escapeHtml(attributs)} (Stock: ${v.quantite_actuelle})</option>`;
                });
            } else {
                options += '<option value="">Aucune variante disponible</option>';
            }
            $('#stock_variante').html(options);
        },
        error: function(xhr, status, error) {
            console.error('Erreur chargement variantes:', status, error);
            $('#stock_variante').html('<option value="">Erreur de chargement</option>');
        }
    });
}

// Gérer les variantes
$('.gerer-variantes').on('click', function() {
    const slug = $(this).data('slug');
    const produit = $(this).data('produit');
    
    $('#variantesModal').modal('show');
    $('#variantesContent').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Chargement des variantes...</p></div>');
    
    $.ajax({
        url: '<?= site_url("approvisionnements/get_variantes_with_stock") ?>',
        type: 'POST',
        data: {slug: slug},
        dataType: 'json',
        timeout: 10000,
        success: function(response) {
            console.log('Réponse reçue:', response);
            
            // Vérifier la structure de la réponse
            let variantes = [];
            if (response.variantes && Array.isArray(response.variantes)) {
                variantes = response.variantes;
            } else if (Array.isArray(response)) {
                variantes = response;
            } else if (response.success === false) {
                $('#variantesContent').html('<div class="text-center py-4 text-danger"><iconify-icon icon="solar:box-broken" class="fs-48"></iconify-icon><p class="mt-2">' + (response.message || 'Erreur de chargement') + '</p></div>');
                return;
            }
            
            if (variantes.length === 0) {
                $('#variantesContent').html('<div class="text-center py-4"><iconify-icon icon="solar:box-broken" class="fs-48 text-muted"></iconify-icon><p class="mt-2">Aucune variante trouvée pour ce produit</p></div>');
                return;
            }
            
            let html = `
                <h6 class="mb-3">Produit: <strong>${escapeHtml(produit)}</strong></h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th>SKU</th>
                                <th>Attributs</th>
                                <th>Stock actuel</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            variantes.forEach(function(v) {
                let attributs = '';
                if (v.attributs && typeof v.attributs === 'object') {
                    attributs = Object.values(v.attributs).join(' - ');
                } else if (typeof v.attributs === 'string') {
                    attributs = v.attributs;
                }
                
                html += `
                    <tr>
                        <td><code>${escapeHtml(v.sku)}</code></td>
                        <td>${escapeHtml(attributs)}</div>
                        <td>
                            <strong class="fs-5">${v.quantite_actuelle}</strong>
                            <br><small class="text-muted">Seuil: ${v.seuil_stock_bas || 5}</small>
                        </div>
                        <td>
                            <button class="btn btn-sm btn-success ajouter-stock-variante" 
                                    data-id="${v.id_variante}"
                                    data-sku="${escapeHtml(v.sku)}"
                                    data-produit="${escapeHtml(produit)}">
                                <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Ajouter
                            </button>
                        </div>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
            
            $('#variantesContent').html(html);
            
            // Réattacher les événements
            $('.ajouter-stock-variante').off('click').on('click', function() {
                const idVariante = $(this).data('id');
                const sku = $(this).data('sku');
                const produit = $(this).data('produit');
                
                $('#variantesModal').modal('hide');
                
                Swal.fire({
                    title: 'Ajouter du stock',
                    html: `
                        <div class="text-start">
                            <p><strong>Produit:</strong> ${escapeHtml(produit)}</p>
                            <p><strong>Variante:</strong> ${escapeHtml(sku)}</p>
                            <div class="mb-3">
                                <label class="form-label">Quantité à ajouter</label>
                                <input type="number" id="quantite_input" class="form-control" min="1" value="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix d'achat unitaire (FBu)</label>
                                <input type="number" id="prix_input" class="form-control" min="1" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fournisseur</label>
                                <input type="text" id="fournisseur_input" class="form-control" placeholder="Nom du fournisseur">
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Ajouter',
                    cancelButtonText: 'Annuler',
                    preConfirm: () => {
                        const quantite = document.getElementById('quantite_input').value;
                        const prix = document.getElementById('prix_input').value;
                        const fournisseur = document.getElementById('fournisseur_input').value;
                        
                        if (!quantite || parseInt(quantite) <= 0) {
                            Swal.showValidationMessage('Quantité invalide');
                            return false;
                        }
                        if (!prix || parseInt(prix) <= 0) {
                            Swal.showValidationMessage('Prix d\'achat invalide');
                            return false;
                        }
                        
                        return {quantite: parseInt(quantite), prix: parseFloat(prix), fournisseur: fournisseur};
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btn = $('.ajouter-stock-variante');
                        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>');
                        
                        $.ajax({
                            url: '<?= site_url("approvisionnements/ajouter_stock_variante") ?>',
                            type: 'POST',
                            data: {
                                id_variante: idVariante,
                                quantite: result.value.quantite,
                                prix_achat_unitaire: result.value.prix,
                                fournisseur: result.value.fournisseur
                            },
                            dataType: 'json',
                            timeout: 10000,
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Succès', response.message, 'success').then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Erreur', response.message, 'error');
                                    btn.prop('disabled', false).html('<iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Ajouter');
                                }
                            },
                            error: function(xhr) {
                                console.error('Erreur:', xhr.responseText);
                                Swal.fire('Erreur', 'Erreur de communication', 'error');
                                btn.prop('disabled', false).html('<iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Ajouter');
                            }
                        });
                    }
                });
            });
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX:', status, error);
            console.error('Réponse:', xhr.responseText);
            $('#variantesContent').html('<div class="text-center py-4 text-danger"><iconify-icon icon="solar:box-broken" class="fs-48"></iconify-icon><p class="mt-2">Erreur de chargement: ' + status + '</p><p class="small">Vérifiez la console pour plus de détails</p></div>');
        }
    });
});

// Soumettre l'ajout de stock
$('#stockForm').on('submit', function(e) {
    e.preventDefault();
    
    const quantite = $('#stock_quantite').val();
    const prix = $('#stock_prix').val();
    const produit = $('#stock_produit').val();
    const slug = $('#stock_slug').val();
    const idVariante = $('#stock_variante').val();
    
    if (!quantite || parseInt(quantite) <= 0) {
        Swal.fire('Erreur', 'Quantité invalide', 'error');
        return;
    }
    
    if (!prix || parseFloat(prix) <= 0) {
        Swal.fire('Erreur', 'Prix d\'achat invalide', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Confirmation',
        text: `Voulez-vous ajouter ${quantite} unité(s) au stock de ${produit} ?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, ajouter',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            const btn = $('#stockForm button[type="submit"]');
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Ajout en cours...');
            
            $.ajax({
                url: '<?= site_url("approvisionnements/ajouter_stock_produit") ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                timeout: 10000,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Succès', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur', response.message, 'error');
                        btn.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', status, error);
                    console.error('Réponse:', xhr.responseText);
                    
                    let errorMsg = 'Erreur de communication avec le serveur';
                    if (xhr.status === 404) {
                        errorMsg = 'URL non trouvée (404)';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Erreur serveur (500)';
                    }
                    
                    Swal.fire('Erreur', errorMsg, 'error');
                    btn.prop('disabled', false).html(originalHtml);
                }
            });
        }
    });
});

// Fonction utilitaire pour échapper le HTML
function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
</script>

<style>
.avatar-md {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
}
.btn-soft-success { background-color: #d1e7dd; border-color: #d1e7dd; color: #198754; }
.btn-soft-success:hover { background-color: #b8e0c4; }
.btn-soft-info { background-color: #cff4fc; border-color: #cff4fc; color: #0dcaf0; }
.btn-soft-info:hover { background-color: #b6effb; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>