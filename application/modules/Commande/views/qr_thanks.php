<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merci - Confirmation de livraison</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .check-icon { font-size: 80px; color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Livraison confirmée</h4>
                    </div>
                    <div class="card-body">
                        <iconify-icon icon="solar:check-circle-bold-duotone" class="check-icon"></iconify-icon>
                        <h3 class="mt-3">Merci !</h3>
                        <p>Votre commande a été confirmée avec succès.</p>
                        <p class="text-muted">Vous recevrez une confirmation par email.</p>
                        <a href="<?= base_url() ?>" class="btn btn-success">Retour à l'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</body>
</html>