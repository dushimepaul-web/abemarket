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
                            <iconify-icon icon="solar:heart-bold-duotone" class="me-2"></iconify-icon>
                            Ma liste de souhaits
                            <span class="badge bg-primary ms-2"><?= $total_souhaits ?> produit(s)</span>
                        </h4>
                        <div class="d-flex gap-2">
                            <?php if ($total_souhaits > 0): ?>
                                <button type="button" class="btn btn-sm btn-danger" onclick="viderListe()">
                                    <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                    Vider la liste
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if (empty($souhaits)): ?>
                            <div class="text-center py-5">
                                <iconify-icon icon="solar:heart-broken-bold-duotone" class="fs-48 text-muted"></iconify-icon>
                                <h5 class="mt-3">Votre liste de souhaits est vide</h5>
                                <p class="text-muted">Ajoutez vos produits préférés et revenez plus tard !</p>
                                <a href="<?= base_url('produits') ?>" class="btn btn-primary">
                                    <iconify-icon icon="solar:shop-bold-duotone"></iconify-icon>
                                    Découvrir les produits
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row g-4">
                                <?php foreach ($souhaits as $s): ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="card product-card h-100">
                                            <?php if ($s->prix_promo && $s->prix_promo < $s->prix_base): ?>
                                                <div class="badge bg-danger position-absolute top-0 start-0 m-2">
                                                    -<?= round((($s->prix_base - $s->prix_promo) / $s->prix_base) * 100) ?>%
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="position-relative">
                                                <img src="<?= base_url('uploads/produits/default.jpg') ?>" 
                                                     class="card-img-top" 
                                                     alt="<?= htmlspecialchars($s->nom_produit) ?>"
                                                     style="height: 200px; object-fit: cover;">
                                                
                                                <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 remove-wishlist"
                                                        data-id="<?= $s->id_souhait ?>"
                                                        title="Retirer de la liste">
                                                    <iconify-icon icon="solar:trash-bin-trash-bold-duotone"></iconify-icon>
                                                </button>
                                            </div>
                                            
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <a href="<?= base_url('produits/detail/' . $s->slug_produit) ?>" class="text-dark text-decoration-none">
                                                        <?= htmlspecialchars($s->nom_produit) ?>
                                                    </a>
                                                </h6>
                                                
                                                <div class="mb-2">
                                                    <?php if ($s->prix_promo && $s->prix_promo < $s->prix_base): ?>
                                                        <span class="text-muted text-decoration-line-through"><?= number_format($s->prix_base, 0, ',', ' ') ?> FBu</span>
                                                        <span class="text-danger fw-bold ms-2"><?= number_format($s->prix_promo, 0, ',', ' ') ?> FBu</span>
                                                    <?php else: ?>
                                                        <span class="fw-bold"><?= number_format($s->prix_base, 0, ',', ' ') ?> FBu</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-warning">
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <iconify-icon icon="<?= $i <= round($s->note_moyenne) ? 'solar:star-bold' : 'solar:star-linear' ?>" 
                                                                          class="fs-12"></iconify-icon>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <a href="<?= base_url('panier/ajouter/' . $s->id_produit) ?>" 
                                                       class="btn btn-sm btn-primary add-to-cart"
                                                       data-id="<?= $s->id_produit ?>">
                                                        <iconify-icon icon="solar:cart-large-minimalistic-bold-duotone"></iconify-icon>
                                                        Ajouter
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Supprimer un produit de la liste
$('.remove-wishlist').on('click', function() {
    var id_souhait = $(this).data('id');
    var $btn = $(this);
    
    $.ajax({
        url: '<?= base_url("liste-souhaits/supprimer_api") ?>',
        type: 'POST',
        data: { id_souhait: id_souhait },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $btn.closest('.col-md-3').fadeOut(300, function() {
                    $(this).remove();
                    location.reload();
                });
            } else {
                alert('Erreur lors de la suppression');
            }
        }
    });
});

// Vider la liste
function viderListe() {
    if (confirm('Êtes-vous sûr de vouloir vider toute votre liste de souhaits ?')) {
        window.location.href = '<?= base_url("liste-souhaits/vider") ?>';
    }
}
</script>

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>