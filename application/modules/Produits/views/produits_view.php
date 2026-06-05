<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Détails du produit</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('Produits') ?>">Produits</a></li>
                            <li class="breadcrumb-item active">Détails</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche - Images -->
            <div class="col-xl-4 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <!-- Image principale -->
                        <div class="text-center mb-3">
                            <?php 
                            $main_image = null;
                            if(!empty($images)):
                                foreach($images as $img):
                                    if($img['est_principale'] == 1):
                                        $main_image = $img;
                                        break;
                                    endif;
                                endforeach;
                                if(!$main_image && !empty($images)):
                                    $main_image = $images[0];
                                endif;
                            endif;
                            
                            $image_exists = $main_image && file_exists(FCPATH . ltrim($main_image['url_image'], '/'));
                            ?>
                            <?php if($image_exists): ?>
                                <img src="<?= base_url($main_image['url_image']) ?>" alt="<?= htmlspecialchars($produit['nom_produit']) ?>" class="img-fluid rounded" id="mainImage" style="max-height: 400px; width: 100%; object-fit: contain;">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <iconify-icon icon="solar:box-bold-duotone" class="fs-64 text-primary"></iconify-icon>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Miniatures -->
                        <?php if(!empty($images) && count($images) > 1): ?>
                        <div class="row g-2 mt-3">
                            <?php foreach($images as $img): 
                                $thumb_path = !empty($img['url_miniature']) ? $img['url_miniature'] : $img['url_image'];
                                $thumb_exists = file_exists(FCPATH . ltrim($thumb_path, '/'));
                            ?>
                            <div class="col-3">
                                <?php if($thumb_exists): ?>
                                    <img src="<?= base_url($thumb_path) ?>" alt="miniature" class="img-fluid rounded cursor-pointer" style="height: 80px; width: 100%; object-fit: cover; cursor: pointer;" onclick="document.getElementById('mainImage').src = '<?= base_url($img['url_image']) ?>'">
                                <?php else: ?>
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 80px; cursor: pointer;" onclick="document.getElementById('mainImage').src = '<?= base_url($img['url_image']) ?>'">
                                        <i class="bx bx-image text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Informations -->
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <!-- En-tête -->
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="mb-1"><?= htmlspecialchars($produit['nom_produit']) ?></h2>
                                <p class="text-muted mb-2">
                                    <i class="bx bx-category"></i> <?= htmlspecialchars($produit['nom_categorie'] ?? 'Non catégorisé') ?>
                                    <?php if(!empty($produit['marque'])): ?>
                                        | <i class="bx bx-tag"></i> <?= htmlspecialchars($produit['marque']) ?>
                                    <?php endif; ?>
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="bx bx-link"></i> Slug: <code><?= htmlspecialchars($produit['slug_produit']) ?></code>
                                </p>
                            </div>
                            <div>
                                <?php if($produit['est_actif'] == 1): ?>
                                    <span class="badge bg-success fs-12 p-2">Actif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger fs-12 p-2">Inactif</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="mt-3">
                            <?php if(!empty($produit['prix_promo']) && $produit['prix_promo'] < $produit['prix_base']): ?>
                                <h3 class="text-muted text-decoration-line-through d-inline-block me-2"><?= number_format($produit['prix_base'], 0, ',', ' ') ?> BIF</h3>
                                <h2 class="text-primary d-inline-block"><?= number_format($produit['prix_promo'], 0, ',', ' ') ?> BIF</h2>
                                <?php 
                                $economie = $produit['prix_base'] - $produit['prix_promo'];
                                $pourcentage = ($economie / $produit['prix_base']) * 100;
                                ?>
                                <span class="badge bg-success ms-2">-<?= round($pourcentage) ?>%</span>
                            <?php else: ?>
                                <h2 class="text-primary"><?= number_format($produit['prix_base'], 0, ',', ' ') ?> BIF</h2>
                            <?php endif; ?>
                        </div>

                        <!-- Stock -->
                        <div class="mt-3">
                            <?php if($produit['quantite_actuelle'] <= 0): ?>
                                <div class="alert alert-danger mb-0 py-2">
                                    <i class="bx bx-error-circle"></i> Rupture de stock
                                </div>
                            <?php elseif($produit['quantite_actuelle'] <= $produit['seuil_stock_bas']): ?>
                                <div class="alert alert-warning mb-0 py-2">
                                    <i class="bx bx-info-circle"></i> Stock bas : <?= number_format($produit['quantite_actuelle']) ?> unités restantes
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success mb-0 py-2">
                                    <i class="bx bx-check-circle"></i> En stock : <?= number_format($produit['quantite_actuelle']) ?> unités
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Informations produit -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="40%">SKU</th>
                                            <td><code><?= htmlspecialchars($produit['sku']) ?></code></td>
                                        </tr>
                                        <tr>
                                            <th>Code produit</th>
                                            <td><?= htmlspecialchars($produit['code_produit']) ?></div>
                                        </tr>
                                        <tr>
                                            <th>Type de produit</th>
                                            <td><?= ucfirst($produit['type_produit']) ?></div>
                                        </tr>
                                        <tr>
                                            <th>Poids</th>
                                            <td><?= !empty($produit['poids_kg']) ? number_format($produit['poids_kg'], 3) . ' kg' : 'Non spécifié' ?></div>
                                        </tr>
                                        <?php if(!empty($produit['id_vendeur'])): ?>
                                        <tr>
                                            <th>Vendeur</th>
                                            <td><?= htmlspecialchars($produit['nom_boutique'] ?? 'Non spécifié') ?></div>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Note moyenne</th>
                                            <td>
                                                <?php 
                                                $note = round($produit['note_moyenne'] ?? 0);
                                                for($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bx bxs-star <?= $i <= $note ? 'text-warning' : 'text-muted' ?>"></i>
                                                <?php endfor; ?>
                                                (<?= number_format($produit['nombre_avis'] ?? 0) ?> avis)
                                             </div>
                                        </tr>
                                        <tr>
                                            <th>Nombre de ventes</th>
                                            <td><?= number_format($produit['nombre_ventes'] ?? 0) ?></div>
                                        </tr>
                                        <tr>
                                            <th>Nombre de vues</th>
                                            <td><?= number_format($produit['nombre_vues'] ?? 0) ?></div>
                                        </tr>
                                        <tr>
                                            <th>Date de création</th>
                                            <td><?= date('d/m/Y H:i', strtotime($produit['date_creation'])) ?></div>
                                        </tr>
                                        <tr>
                                            <th>Dernière modification</th>
                                            <td><?= date('d/m/Y H:i', strtotime($produit['date_modification'])) ?></div>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Description courte -->
                        <?php if(!empty($produit['description_courte'])): ?>
                        <div class="mt-3">
                            <h5 class="mb-2">Description courte</h5>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($produit['description_courte'])) ?></p>
                        </div>
                        <?php endif; ?>

                        <!-- Description complète -->
                        <?php if(!empty($produit['description'])): ?>
                        <div class="mt-3">
                            <h5 class="mb-2">Description complète</h5>
                            <div class="text-muted"><?= nl2br(htmlspecialchars($produit['description'])) ?></div>
                        </div>
                        <?php endif; ?>

                        <!-- Promotion -->
                        <?php if(!empty($produit['prix_promo']) && !empty($produit['date_debut_promo'])): ?>
                        <div class="mt-3">
                            <h5 class="mb-2">Information promotion</h5>
                            <div class="alert alert-info mb-0">
                                <i class="bx bx-gift"></i>
                                Promotion valable du <?= date('d/m/Y', strtotime($produit['date_debut_promo'])) ?> 
                                au <?= date('d/m/Y', strtotime($produit['date_fin_promo'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="mt-4 d-flex gap-2">
                            <a href="<?= base_url('Produits/edit/'.$produit['slug_produit']) ?>" class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i>Modifier
                            </a>
                            <a href="<?= base_url('Produits') ?>" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete('<?= $produit['slug_produit'] ?>', '<?= addslashes(htmlspecialchars($produit['nom_produit'])) ?>')">
                                <i class="bx bx-trash me-1"></i>Supprimer
                            </button>
                        </div>
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
// Fonction AJAX sans jQuery
function ajaxRequest(url, method, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    callback(response);
                } catch(e) {
                    console.error('Erreur JSON:', e);
                    callback({success: false, message: 'Erreur de réponse serveur'});
                }
            } else {
                callback({success: false, message: 'Erreur HTTP: ' + xhr.status});
            }
        }
    };
    xhr.send();
}

function confirmDelete(slug, name) {
    if (confirm('Êtes-vous sûr de vouloir supprimer le produit "' + name + '" ? Cette action est irréversible !')) {
        window.location.href = '<?= base_url("Produits/delete") ?>/' + slug;
    }
}

// Messages flash avec Toastr
<?php if($this->session->flashdata('success')): ?>
    toastr.success('<?= addslashes($this->session->flashdata('success')) ?>');
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    toastr.error('<?= addslashes($this->session->flashdata('error')) ?>');
<?php endif; ?>
</script>

<style>
.cursor-pointer {
    cursor: pointer;
    transition: opacity 0.2s ease;
}
.cursor-pointer:hover {
    opacity: 0.8;
}
.table th {
    background-color: #f8f9fa;
    width: 40%;
}
.table td {
    background-color: #ffffff;
}
.bg-soft-primary {
    background-color: rgba(85, 110, 230, 0.1);
}
.fs-12 {
    font-size: 12px;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>