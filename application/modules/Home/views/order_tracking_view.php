<?php include VIEWPATH . 'includes/frontend/Header.php'; ?>

<style type="text/css">
	/* Styles pour le suivi de commande */
.tracking-order-number {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.tracking-order-number h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.tracking-order-number h3 span:first-child {
    color: #ff6b35;
}

.tracking-order-number .badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    margin-left: 10px;
}

.tacking-left-box,
.delivery-details,
.order-details,
.payment-details {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.order-title {
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.order-title h4 {
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0;
}

.tracking-order-box {
    display: flex;
    align-items: center;
    gap: 15px;
}

.order-images {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    overflow: hidden;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.order-images img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tracking-product-details h4 {
    font-size: 1rem;
    margin-bottom: 8px;
}

.order-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.order-list li {
    font-size: 0.8rem;
    color: #6c757d;
}

.order-list li span {
    color: #333;
    font-weight: 500;
}

.price {
    color: #ff6b35;
    font-weight: 600;
    margin: 0;
}

.del-price {
    font-size: 0.8rem;
    color: #999;
    margin-left: 5px;
}

/* Timeline */
.delivery-timeline {
    list-style: none;
    padding: 0;
    margin: 0;
}

.delivery-timeline li {
    position: relative;
    padding-left: 30px;
    padding-bottom: 20px;
    border-left: 2px solid #e0e0e0;
    margin-left: 10px;
}

.delivery-timeline li:last-child {
    border-left-color: transparent;
    padding-bottom: 0;
}

.delivery-timeline li::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #ddd;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #ddd;
}

.delivery-timeline li.complete::before {
    background: #28a745;
    box-shadow: 0 0 0 2px #28a745;
}

.delivery-timeline li h5 {
    font-size: 0.95rem;
    margin-bottom: 5px;
}

.delivery-timeline li h5 span {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: normal;
}

/* Order details */
.order-details-box {
    list-style: none;
    padding: 0;
    margin: 0 0 20px 0;
}

.order-details-box li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.order-details-box li i {
    font-size: 1.2rem;
    color: #ff6b35;
    width: 24px;
}

.order-details-box li h5 {
    font-size: 0.9rem;
    margin: 0;
    font-weight: normal;
}

.address-list {
    list-style: none;
    padding: 0;
    margin: 20px 0 0 0;
}

.address-box {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-bottom: 15px;
}

.address-box h5 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 8px;
}

.address-box p {
    font-size: 0.85rem;
    margin-bottom: 5px;
    color: #6c757d;
}

/* Payment summary */
.payment-summary {
    list-style: none;
    padding: 0;
    margin: 0;
}

.payment-summary li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.payment-summary li h4 {
    font-size: 0.9rem;
    margin: 0;
    font-weight: normal;
}

.payment-summary li h4 span {
    font-size: 0.8rem;
    color: #6c757d;
}

.payment-summary li h5 {
    font-size: 0.9rem;
    margin: 0;
    font-weight: 600;
    color: #333;
}

.total-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    margin-top: 15px;
    border-top: 2px solid #ff6b35;
}

.total-price p {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    color: #333;
}

.total-price span {
    font-size: 1.3rem;
    font-weight: bold;
    color: #ff6b35;
}

.proceed-btn {
    background: linear-gradient(135deg, #ff6b35, #ff9a44);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
    width: 100%;
    transition: all 0.3s ease;
}

.proceed-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,107,53,0.3);
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
}

.btn-danger:hover {
    background: #c82333;
}
</style>
<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Suivi de commande</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?php echo base_url(); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Commande</li>
                    <li class="breadcrumb-item active">Suivi de commande</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Order Tracking Section Start -->
<section class="tracking-section section-t-space">
    <div class="custom-container">
        
        <?php if (!$commande && !$erreur): ?>
        <!-- Formulaire de recherche -->
        <div class="tracking-order-number text-center p-5">
            <h3>Suivre ma commande</h3>
            <div class="row justify-content-center mt-4">
                <div class="col-md-6">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" 
                                   name="numero_commande" 
                                   placeholder="Entrez votre numéro de commande ex: CMD-20260423-0001" 
                                   required>
                            <button class="btn proceed-btn" type="submit">
                                <i class="ri-search-line"></i> Suivre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($erreur): ?>
        <div class="alert alert-danger">
            <i class="ri-error-warning-line"></i> <?php echo htmlspecialchars($erreur); ?>
        </div>
        <?php endif; ?>
        
        <?php if ($commande): ?>
        
        <!-- En-tête commande -->
        <div class="tracking-order-number">
            <h3>
                Commande <span>#<?php echo htmlspecialchars($commande['numero_commande']); ?></span> 
                <span class="badge badge-<?php echo $statuts_labels[$commande['statut_commande']]['color'] ?? 'secondary'; ?>">
                    <?php echo $statuts_labels[$commande['statut_commande']]['label'] ?? $commande['statut_commande']; ?>
                </span>
            </h3>
            <h4><?php echo date('d F Y \à H:i', strtotime($commande['date_creation'])); ?></h4>
        </div>
        
        <div class="row g-md-4 g-3 tracking-row">
            <!-- Colonne de gauche -->
            <div class="col-xl-8 col-lg-7">
                <div class="row g-md-4 g-3">
                    <!-- Détails produits -->
                    <div class="col-12">
                        <div class="tacking-left-box">
                            <div class="order-title border-0">
                                <h4>Détails des produits</h4>
                            </div>
                            <div class="order-tracking-table">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Prix</th>
                                                <th>Quantité</th>
                                                <th>Sous-total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($articles as $article): ?>
                                            <tr>
                                                <td>
                                                    <div class="tracking-order-box">
                                                        <a href="<?php echo base_url('product/' . $article['id_produit']); ?>" class="order-images">
                                                            <img src="<?php echo base_url($article['image_url'] ?? 'assets/images/product/default.png'); ?>" 
                                                                 alt="<?php echo htmlspecialchars($article['nom_produit']); ?>"
                                                                 class="img-fluid order-images">
                                                        </a>
                                                        <div class="tracking-product-details">
                                                            <a href="<?php echo base_url('product/' . $article['id_produit']); ?>">
                                                                <h4><?php echo htmlspecialchars($article['nom_produit']); ?></h4>
                                                            </a>
                                                            <?php if ($article['attributs_variante']): 
                                                                $attributs = json_decode($article['attributs_variante'], true);
                                                                if ($attributs): ?>
                                                                <ul class="order-list">
                                                                    <?php foreach ($attributs as $key => $value): ?>
                                                                    <li>
                                                                        <?php echo ucfirst($key); ?>: <span><?php echo htmlspecialchars($value); ?></span>
                                                                    </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php endif; endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h5 class="price">
                                                        <?php echo number_format($article['prix_unitaire'], 0, ',', ' '); ?> BIF
                                                        <?php if ($article['prix_unitaire'] < $article['prix_base'] ?? 0): ?>
                                                        <del class="del-price"><?php echo number_format($article['prix_base'], 0, ',', ' '); ?> BIF</del>
                                                        <?php endif; ?>
                                                    </h5>
                                                </td>
                                                <td><?php echo $article['quantite']; ?></td>
                                                <td><?php echo number_format($article['prix_total'], 0, ',', ' '); ?> BIF</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Timeline livraison -->
                    <div class="col-12">
                        <div class="delivery-details">
                            <div class="order-title">
                                <h4>Détails de livraison</h4>
                            </div>
                            <ul class="delivery-timeline">
                                <?php 
                                $timeline_steps = [
                                    'order_placed' => ['label' => 'Commande placée', 'key' => 'date_creation'],
                                    'confirmed' => ['label' => 'Commandé confirmée', 'key' => null],
                                    'packed' => ['label' => 'Emballé dans l\'entrepôt', 'key' => null],
                                    'shipped' => ['label' => 'Expédiée', 'key' => 'date_expedition'],
                                    'delivered' => ['label' => 'Livrée', 'key' => 'date_livraison_reelle']
                                ];
                                
                                $step_index = 0;
                                $total_steps = count($timeline_steps);
                                $current_status = $commande['statut_commande'];
                                $status_order = ['en_attente', 'confirme', 'en_preparation', 'expedie', 'en_livraison', 'livre'];
                                $current_step_index = array_search($current_status, $status_order);
                                ?>
                                
                                <?php if ($commande['date_livraison_prevue']): ?>
                                <li>
                                    <h5>Livraison prévue <span>entre <?php echo date('d M', strtotime($commande['date_livraison_prevue'])); ?></span></h5>
                                </li>
                                <?php endif; ?>
                                
                                <?php if ($commande['date_livraison_reelle']): ?>
                                <li class="complete">
                                    <h5>Livrée <span>le <?php echo date('d M \à H:i', strtotime($commande['date_livraison_reelle'])); ?></span></h5>
                                </li>
                                <?php endif; ?>
                                
                                <?php if ($commande['date_expedition']): ?>
                                <li class="complete">
                                    <h5>Expédiée <span>le <?php echo date('d M', strtotime($commande['date_expedition'])); ?></span></h5>
                                </li>
                                <?php endif; ?>
                                
                                <li class="<?php echo $current_step_index >= 2 ? 'complete' : ''; ?>">
                                    <h5>Préparée <span><?php echo $current_step_index >= 2 ? 'Terminé' : 'En cours'; ?></span></h5>
                                </li>
                                
                                <li class="<?php echo $current_step_index >= 1 ? 'complete' : ''; ?>">
                                    <h5>Confirmée <span>le <?php echo date('d M', strtotime($commande['date_creation'])); ?></span></h5>
                                </li>
                                
                                <li class="complete">
                                    <h5>Commande placée <span>le <?php echo date('d M \à H:i', strtotime($commande['date_creation'])); ?></span></h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne de droite -->
            <div class="col-xl-4 col-lg-5">
                <div class="tracking-right-box">
                    <div class="row g-sm-4 g-3">
                        <!-- Informations client -->
                        <div class="col-12">
                            <div class="order-details">
                                <div class="order-title">
                                    <h4>Informations</h4>
                                </div>
                                
                                <ul class="order-details-box">
                                    <li>
                                        <i class="ri-mail-line"></i>
                                        <h5><?php echo htmlspecialchars($commande['email'] ?? 'Non renseigné'); ?></h5>
                                    </li>
                                    <li>
                                        <i class="ri-calendar-line"></i>
                                        <h5><?php echo date('d M Y \à H:i', strtotime($commande['date_creation'])); ?></h5>
                                    </li>
                                    <li>
                                        <i class="ri-hashtag"></i>
                                        <h5><?php echo htmlspecialchars($commande['numero_commande']); ?></h5>
                                    </li>
                                </ul>
                                
                                <button class="btn proceed-btn" onclick="window.print();">
                                    <i class="ri-file-pdf-line"></i> Télécharger facture
                                </button>
                                
                                <?php if ($adresse): ?>
                                <ul class="address-list">
                                    <li class="address-box">
                                        <h5>Adresse de livraison :</h5>
                                        <p><?php echo htmlspecialchars($adresse['adresse_ligne'] ?? ''); ?></p>
                                        <p><?php echo htmlspecialchars($adresse['commune_name'] ?? ''); ?>, <?php echo htmlspecialchars($adresse['province_name'] ?? ''); ?></p>
                                        <p>Tél: <?php echo htmlspecialchars($adresse['telephone'] ?? $commande['telephone']); ?></p>
                                    </li>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Résumé paiement -->
                        <div class="col-12">
                            <div class="payment-details">
                                <div class="order-title">
                                    <h4>Résumé paiement</h4>
                                </div>
                                <ul class="payment-summary">
                                    <li>
                                        <h4>Sous-total <span>(<?php echo $nombre_articles ?? count($articles); ?> articles)</span></h4>
                                        <h5><?php echo number_format($sous_total ?? $commande['sous_total'], 0, ',', ' '); ?> BIF</h5>
                                    </li>
                                    <li>
                                        <h4>Livraison <span><?php echo number_format($frais_livraison ?? $commande['frais_livraison'], 0, ',', ' '); ?> BIF</span></h4>
                                    </li>
                                    <?php if ($commande['montant_reduction'] > 0): ?>
                                    <li>
                                        <h4>Réduction <span>-<?php echo number_format($commande['montant_reduction'], 0, ',', ' '); ?> BIF</span></h4>
                                    </li>
                                    <?php endif; ?>
                                    <li>
                                        <h4>Paiement <span><?php echo htmlspecialchars($commande['mode_paiement_desc'] ?? 'Non spécifié'); ?></span></h4>
                                    </li>
                                </ul>
                                <div class="total-price">
                                    <p>Total payé par le client</p>
                                    <span><?php echo number_format($montant_total ?? $commande['montant_total'], 0, ',', ' '); ?> BIF</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QR Code si disponible -->
                        <?php if ($qr_code && !$qr_code['est_utilise'] && $commande['statut_commande'] != 'livre'): ?>
                        <div class="col-12">
                            <div class="payment-details text-center">
                                <div class="order-title">
                                    <h4>QR Code livraison</h4>
                                </div>
                                <div id="qrcode" class="mb-3"></div>
                                <p class="text-muted small">Présentez ce code au livreur</p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Actions -->
                        <?php if (in_array($commande['statut_commande'], ['en_attente', 'confirme'])): ?>
                        <div class="col-12">
                            <button class="btn btn-danger w-100" onclick="cancelOrder(<?php echo $commande['id_commande']; ?>)">
                                <i class="ri-close-circle-line"></i> Annuler la commande
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
        
    </div>
</section>
<!-- Order Tracking Section End -->

<!-- Loading Spinner -->
<div id="loading" class="loading-overlay">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
// Annuler la commande
function cancelOrder(orderId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
        $('#loading').fadeIn();
        
        $.ajax({
            url: '<?php echo base_url("index.php/ordertracking/updateOrderStatus"); ?>',
            method: 'POST',
            data: {
                id_commande: orderId,
                statut: 'annule',
                commentaire: 'Annulée par le client'
            },
            success: function(response) {
                $('#loading').fadeOut();
                if (response.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + response.message);
                }
            },
            error: function() {
                $('#loading').fadeOut();
                alert('Une erreur est survenue');
            }
        });
    }
}

// Générer QR code
<?php if ($qr_code && !$qr_code['qr_image_url'] && isset($commande)): ?>
new QRCode(document.getElementById("qrcode"), {
    text: "<?php echo $qr_code['token']; ?>",
    width: 150,
    height: 150
});
<?php endif; ?>

// Styles additionnels
$('head').append(`
<style>
.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.badge-warning { background: #ffc107; color: #000; }
.badge-info { background: #17a2b8; color: #fff; }
.badge-primary { background: #007bff; color: #fff; }
.badge-success { background: #28a745; color: #fff; }
.badge-danger { background: #dc3545; color: #fff; }
.badge-dark { background: #343a40; color: #fff; }

.delivery-timeline li.complete h5::before {
    background: #28a745;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
}
.btn-danger:hover {
    background: #c82333;
}
</style>
`);
</script>

<?php include VIEWPATH . 'includes/frontend/Footer.php'; ?>