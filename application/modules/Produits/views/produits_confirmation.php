<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - Produit Créé</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <style>
        .confirmation-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        
        .confirmation-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            padding: 50px 40px;
            text-align: center;
        }
        
        .success-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
            animation: bounce 0.6s;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .confirmation-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .confirmation-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .product-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .product-info-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .product-detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }
        
        .product-detail:last-child {
            border-bottom: none;
        }
        
        .product-detail-label {
            color: #666;
            font-weight: 500;
        }
        
        .product-detail-value {
            color: #333;
            font-weight: 600;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-primary, .btn-secondary {
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #d0d0d0;
            transform: translateY(-2px);
        }
        
        .steps {
            margin-top: 40px;
            text-align: left;
            background: #f0f7ff;
            padding: 20px;
            border-radius: 8px;
        }
        
        .steps-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }
        
        .step-item {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .step-icon {
            min-width: 24px;
            height: 24px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
        }
        
        .step-text {
            color: #555;
            padding-top: 2px;
        }
        
        .image-preview {
            margin-top: 20px;
        }
        
        .image-preview-title {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .image-gallery {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .image-thumb {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            overflow: hidden;
            border: 2px solid #e0e0e0;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-card">
            <!-- Icône de succès -->
            <div class="success-icon">✓</div>
            
            <!-- Titre de confirmation -->
            <h1 class="confirmation-title">Produit créé avec succès!</h1>
            
            <!-- Message -->
            <p class="confirmation-text">
                Votre produit <strong><?= htmlspecialchars($product_name) ?></strong> a été enregistré avec succès dans votre boutique.
            </p>
            
            <!-- Informations du produit -->
            <div class="product-info">
                <div class="product-info-title">Détails du produit</div>
                
                <div class="product-detail">
                    <span class="product-detail-label">ID Produit:</span>
                    <span class="product-detail-value">#<?= $product_id ?></span>
                </div>
                
                <div class="product-detail">
                    <span class="product-detail-label">Nom:</span>
                    <span class="product-detail-value"><?= htmlspecialchars($product_name) ?></span>
                </div>
                
                <?php if (!empty($produit)): ?>
                    <div class="product-detail">
                        <span class="product-detail-label">Prix:</span>
                        <span class="product-detail-value"><?= number_format($produit['prix_base'], 0, ',', ' ') ?> BIF</span>
                    </div>
                    
                    <div class="product-detail">
                        <span class="product-detail-label">Stock:</span>
                        <span class="product-detail-value"><?= $produit['quantite_actuelle'] ?> unités</span>
                    </div>
                    
                    <div class="product-detail">
                        <span class="product-detail-label">Statut:</span>
                        <span class="product-detail-value">
                            <?php if ($produit['statut'] === 'actif'): ?>
                                <span class="badge badge-success">Actif</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Brouillon</span>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Aperçu des images -->
            <?php if (!empty($images)): ?>
                <div class="image-preview">
                    <div class="image-preview-title">Images du produit (<?= count($images) ?>)</div>
                    <div class="image-gallery">
                        <?php foreach (array_slice($images, 0, 4) as $img): ?>
                            <img src="<?= base_url($img['url_miniature'] ?: $img['url_image']) ?>" alt="<?= htmlspecialchars($img['texte_alt'] ?? '') ?>" class="image-thumb">
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Étapes suivantes -->
            <div class="steps">
                <div class="steps-title">Prochaines étapes</div>
                
                <div class="step-item">
                    <div class="step-icon">1</div>
                    <div class="step-text">Votre produit est en <strong>brouillon</strong>. Activez-le pour que les clients puissent le voir.</div>
                </div>
                
                <div class="step-item">
                    <div class="step-icon">2</div>
                    <div class="step-text">Vous pouvez ajouter des <strong>variantes</strong> (tailles, couleurs) si nécessaire.</div>
                </div>
                
                <div class="step-item">
                    <div class="step-icon">3</div>
                    <div class="step-text">Consultez votre <strong>tableau de bord vendeur</strong> pour gérer vos produits.</div>
                </div>
            </div>
            
            <!-- Boutons d'action -->
            <div class="button-group">
                <?php if ($is_vendor): ?>
                    <a href="<?= base_url('MaBoutique') ?>" class="btn-primary">Retourner au Dashboard</a>
                    <a href="<?= base_url('Produits/add') ?>" class="btn-secondary">Ajouter un autre produit</a>
                <?php else: ?>
                    <a href="<?= base_url('Produits') ?>" class="btn-primary">Voir tous les produits</a>
                    <a href="<?= base_url('Produits/add') ?>" class="btn-secondary">Ajouter un autre produit</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
</body>
</html>
