<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">

        <!-- ============================================ -->
        <!-- SECTION STATISTIQUES DES PRODUITS -->
        <!-- ============================================ -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-primary bg-opacity-10 p-3">
                                <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-primary"></iconify-icon>
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
                            <div class="rounded bg-danger bg-opacity-10 p-3">
                                <iconify-icon icon="solar:close-circle-bold-duotone" class="fs-32 text-danger"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($produits_inactifs ?? 0) ?></h3>
                                <p class="text-muted mb-0">Produits inactifs</p>
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
        </div>

        <!-- Deuxième ligne de statistiques -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-info bg-opacity-10 p-3">
                                <iconify-icon icon="solar:chart-2-bold-duotone" class="fs-32 text-info"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($total_produits - $produits_actifs - $produits_inactifs ?? 0) ?></h3>
                                <p class="text-muted mb-0">En attente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded bg-success bg-opacity-10 p-3">
                                <iconify-icon icon="solar:wallet-bold-duotone" class="fs-32 text-success"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="mb-0"><?= number_format($total_produits - $rupture_stock ?? 0) ?></h3>
                                <p class="text-muted mb-0">Produits disponibles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">Liste des produits</h4>

                        <a href="<?= base_url('Produits/add') ?>" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i>Ajouter un produit
                        </a>

                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                Ce mois
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item">Exporter</a>
                                <a href="#" class="dropdown-item">Importer</a>
                            </div>
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
                                <input type="text" name="search" class="form-control" value="<?= $this->input->get('search') ?>" placeholder="Rechercher...">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                            </div>
                        </form>
                    </div>

                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th style="width: 20px;">
                                            <div class="form-check ms-1">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                                <label class="form-check-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th>Produit</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Catégorie</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($produits)): ?>
                                        <?php foreach($produits as $prod): 
                                            $image_url = isset($prod['image_url']) ? $prod['image_url'] : null;
                                            $image_exists = $image_url && file_exists(FCPATH . ltrim($image_url, '/'));
                                        ?>
                                        <tr data-produit-id="<?= $prod['id_produit'] ?>" data-slug="<?= $prod['slug_produit'] ?>">
                                            <td>
                                                <div class="form-check ms-1">
                                                    <input type="checkbox" class="form-check-input product-check" value="<?= $prod['id_produit'] ?>">
                                                    <label class="form-check-label"></label>
                                                </div>
                                             </div>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                        <?php if($image_exists): ?>
                                                            <img src="<?= base_url($image_url) ?>" alt="<?= htmlspecialchars($prod['nom_produit']) ?>" class="avatar-md" style="object-fit: cover; width: 48px; height: 48px; border-radius: 8px;">
                                                        <?php else: ?>
                                                            <iconify-icon icon="solar:box-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <a href="<?= base_url('Produits/view/'.$prod['slug_produit']) ?>" class="text-dark fw-medium fs-15"><?= htmlspecialchars($prod['nom_produit']) ?></a>
                                                        <p class="text-muted mb-0 mt-1 fs-13">
                                                            <span>SKU : </span><?= htmlspecialchars($prod['sku']) ?>
                                                        </p>
                                                        <?php if($prod['type_produit'] == 'variable'): ?>
                                                            <span class="badge bg-info mt-1">Variable</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                             </div>
                                            <td>
                                                <?php if(!empty($prod['prix_promo']) && $prod['prix_promo'] > 0 && $prod['prix_promo'] < $prod['prix_base']): ?>
                                                    <span class="text-muted text-decoration-line-through fs-12"><?= number_format($prod['prix_base'], 0, ',', ' ') ?> BIF</span><br>
                                                    <span class="fw-semibold text-success"><?= number_format($prod['prix_promo'], 0, ',', ' ') ?> BIF</span>
                                                <?php else: ?>
                                                    <span class="fw-semibold"><?= number_format($prod['prix_base'], 0, ',', ' ') ?> BIF</span>
                                                <?php endif; ?>
                                             </div>
                                            <td>
                                                <?php if($prod['quantite_actuelle'] <= 0): ?>
                                                    <p class="mb-1 text-danger"><span class="text-dark fw-medium">0</span> en stock</p>
                                                    <p class="mb-0 text-muted">Rupture</p>
                                                <?php elseif($prod['quantite_actuelle'] <= $prod['seuil_stock_bas']): ?>
                                                    <p class="mb-1 text-warning"><span class="text-dark fw-medium"><?= $prod['quantite_actuelle'] ?></span> en stock</p>
                                                    <p class="mb-0 text-muted">Stock bas</p>
                                                <?php else: ?>
                                                    <p class="mb-1"><span class="text-dark fw-medium"><?= $prod['quantite_actuelle'] ?></span> en stock</p>
                                                    <p class="mb-0 text-muted"><?= number_format($prod['nombre_ventes'] ?? 0) ?> vendus</p>
                                                <?php endif; ?>
                                             </div>
                                            <td><?= htmlspecialchars($prod['nom_categorie'] ?? 'Non catégorisé') ?> </div>
                                            <td>
                                                <span class="badge p-1 bg-light text-dark fs-12 me-1">
                                                    <i class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 
                                                    <?= number_format($prod['note_moyenne'] ?? 0, 1) ?>
                                                </span>
                                                <?= number_format($prod['nombre_avis'] ?? 0) ?> avis
                                             </div>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('Produits/view/'.$prod['slug_produit']) ?>" class="btn btn-light btn-sm" title="Voir">
                                                        <iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    
                                                    <a href="<?= base_url('Produits/images/'.$prod['slug_produit']) ?>" class="btn btn-soft-info btn-sm" title="Gérer les images">
                                                        <iconify-icon icon="solar:gallery-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    
                                                    <a href="<?= base_url('Produits/variantes/'.$prod['slug_produit']) ?>" class="btn btn-soft-warning btn-sm" title="Gérer les variantes">
                                                        <iconify-icon icon="solar:box-bold-duotone" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    
                                                    <a href="<?= base_url('Produits/edit/'.$prod['slug_produit']) ?>" class="btn btn-soft-primary btn-sm" title="Modifier">
                                                        <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </a>
                                                    
                                                    <button type="button" class="btn btn-soft-danger btn-sm delete-product" title="Supprimer" data-slug="<?= $prod['slug_produit'] ?>" data-name="<?= addslashes(htmlspecialchars($prod['nom_produit'])) ?>">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                    </button>
                                                </div>
                                             </div>
                                         </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <iconify-icon icon="solar:box-remove-bold-duotone" class="fs-48"></iconify-icon>
                                                    <p class="mt-2">Aucun produit trouvé</p>
                                                    <a href="<?= base_url('Produits/add') ?>" class="btn btn-sm btn-primary mt-2">
                                                        <i class="bx bx-plus me-1"></i>Ajouter un produit
                                                    </a>
                                                </div>
                                             </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">Précédent</a></li>
                                <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">Suivant</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <script>document.write(new Date().getFullYear())</script> &copy; ABEMARKET - Plateforme e-commerce
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
// Sélectionner tous les produits
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-check');
    checkboxes.forEach(cb => cb.checked = this.checked);
});

// Fonction pour supprimer avec confirmation
function confirmDelete(slug, name) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer le produit " + name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Produits/delete") ?>/' + slug;
        }
    });
}

// Événements pour les boutons de suppression
document.addEventListener('DOMContentLoaded', function() {
    var deleteButtons = document.querySelectorAll('.delete-product');
    deleteButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var slug = this.getAttribute('data-slug');
            var name = this.getAttribute('data-name');
            confirmDelete(slug, name);
        });
    });
    
    // Ajouter un champ de recherche
    var cardHeader = document.querySelector('.card-header');
    if (cardHeader) {
        var searchDiv = document.createElement('div');
        searchDiv.className = 'ms-auto me-3';
        searchDiv.style.width = '250px';
        searchDiv.innerHTML = `
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bx bx-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un produit...">
            </div>
        `;
        cardHeader.insertBefore(searchDiv, cardHeader.querySelector('.dropdown'));
        
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            var rows = document.querySelectorAll('tbody tr');
            var visibleCount = 0;
            
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                if (text.indexOf(searchTerm) > -1) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            var noResultMsg = document.getElementById('noResultMsg');
            if (visibleCount === 0 && rows.length > 0) {
                if (!noResultMsg) {
                    var tbody = document.querySelector('tbody');
                    var msgRow = document.createElement('tr');
                    msgRow.id = 'noResultMsg';
                    msgRow.innerHTML = '<td colspan="7" class="text-center py-4"><div class="text-muted"><iconify-icon icon="solar:box-remove-bold-duotone" class="fs-48"></iconify-icon><p class="mt-2">Aucun produit ne correspond à votre recherche</p></div></td>';
                    tbody.appendChild(msgRow);
                }
            } else if (noResultMsg) {
                noResultMsg.remove();
            }
        });
    }
});

// Messages flash avec SweetAlert
<?php if($this->session->flashdata('success')): ?>
    Swal.fire('Succès', '<?= addslashes($this->session->flashdata('success')) ?>', 'success');
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    Swal.fire('Erreur', '<?= addslashes($this->session->flashdata('error')) ?>', 'error');
<?php endif; ?>
</script>

<style>
.avatar-md {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
}
.bg-light-subtle {
    background-color: #f8f9fa;
}
.table tbody tr {
    transition: background-color 0.2s ease;
}
.table tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>