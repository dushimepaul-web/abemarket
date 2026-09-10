<?php include VIEWPATH . 'includes/frontend/Header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .container {
        max-width: 1200px;
        width: 100%;
        margin: 0 auto;
    }
    .card {
        background: white;
    }
    .card-header {
        background: linear-gradient(135deg, #0a66c2 0%, #004182 100%);
        color: white;
        text-align: center;
        padding: 30px;
    }
    .card-header h2 {
        font-size: 32px;
        margin-bottom: 10px;
    }
    .card-body {
        padding: 40px;
    }
    .text-center {
        text-align: center;
    }
    .mb-4 {
        margin-bottom: 20px;
    }
    .mt-2 {
        margin-top: 10px;
    }
    .mt-4 {
        margin-top: 20px;
    }
    .avatar-xl {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #f0f0f0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
    }
    .col-md-6 {
        width: calc(50% - 15px);
        min-width: 280px;
    }
    .col-12 {
        width: 100%;
    }
    .role-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        height: 100%;
        border: 1px solid #e0e0e0;
        border-radius: 16px;
        background: white;
    }
    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .role-card.selected {
        border: 2px solid #0a66c2;
        box-shadow: 0 5px 20px rgba(10,102,194,0.2);
    }
    .role-card .card-body {
        padding: 30px;
        text-align: center;
    }
    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-light { background: #f5f5f5; }
    .text-primary { color: #667eea; }
    .text-success { color: #11998e; }
    .text-info { color: #4facfe; }
    .text-muted { color: #6c757d; }
    .list-unstyled {
        list-style: none;
        padding-left: 0;
        text-align: left;
    }
    .list-unstyled li {
        margin-bottom: 8px;
        font-size: 14px;
    }
    .btn {
        padding: 12px 24px;
        border-radius: 30px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        font-size: 14px;
    }
    .btn-outline-primary {
        background: transparent;
        border: 2px solid #667eea;
        color: #667eea;
    }
    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
    }
    .btn-outline-success {
        background: transparent;
        border: 2px solid #11998e;
        color: #11998e;
    }
    .btn-outline-success:hover {
        background: #11998e;
        color: white;
    }
    .btn-outline-info {
        background: transparent;
        border: 2px solid #4facfe;
        color: #4facfe;
    }
    .btn-outline-info:hover {
        background: #4facfe;
        color: white;
    }
    .w-100 {
        width: 100%;
    }
    .bg-light {
        background: #f8f9fa;
    }
    .rounded-4 {
        border-radius: 16px;
    }
    .py-5 {
        padding-top: 40px;
        padding-bottom: 40px;
    }
    .gap-2 {
        gap: 8px;
    }
    .fw-bold {
        font-weight: 700;
    }
    .fs-3x {
        font-size: 48px;
    }
    .fa-3x {
        font-size: 40px;
    }
    .opacity-75 {
        opacity: 0.75;
    }
    @media (max-width: 768px) {
        .col-md-6 {
            width: 100%;
        }
        .card-body {
            padding: 20px;
        }
    }
</style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="avatar-xl bg-light d-inline-flex align-items-center justify-content-center mb-3">
                    <?php if(!empty($user['avatar_url'])): ?>
                        <img src="<?= base_url($user['avatar_url']) ?>" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <i class="fas fa-user fa-3x text-primary"></i>
                    <?php endif; ?>
                </div>
                <h4>Comment souhaitez-vous utiliser ABEMARKET ?</h4>
                <p class="text-muted">Choisissez votre type de compte pour commencer l'aventure</p>
            </div>

            <div class="row g-4 mt-2">
                <!-- Option Client -->
                <div class="col-md-6">
                    <div class="role-card" data-role="client">
                        <div class="card-body">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-shopping-bag fa-3x text-white"></i>
                            </div>
                            <h4 class="fw-bold">Je suis Client</h4>
                            <p class="text-muted">Achetez des produits, découvrez des offres et profitez des meilleurs prix.</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-check-circle text-success me-2"></i> Achetez en toute sécurité</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Suivez vos commandes</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Profitez des promotions</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Payez à la livraison</li>
                            </ul>
                            <button class="btn btn-outline-primary w-100 mt-3 select-role" data-role="client">
                                <i class="fas fa-shopping-cart me-2"></i>Continuer comme Client
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Option Vendeur -->
                <div class="col-md-6">
                    <div class="role-card" data-role="vendeur">
                        <div class="card-body">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-store fa-3x text-white"></i>
                            </div>
                            <h4 class="fw-bold">Je suis Vendeur</h4>
                            <p class="text-muted">Créez votre boutique en ligne, vendez vos produits et développez votre activité.</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-check-circle text-success me-2"></i> Créez votre boutique</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Gérez vos produits</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Accédez à vos statistiques</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i> Recevez vos paiements</li>
                            </ul>
                            <button class="btn btn-outline-success w-100 mt-3 select-role" data-role="vendeur">
                                <i class="fas fa-store me-2"></i>Continuer comme Vendeur
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Option Les deux -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="bg-light rounded-4 p-4 text-center">
                        <div class="icon-circle bg-info d-inline-flex mb-3">
                            <i class="fas fa-exchange-alt fa-2x text-white"></i>
                        </div>
                        <h5>Je veux être Client ET Vendeur</h5>
                        <p class="text-muted">Vous pouvez avoir les deux rôles ! Commencez comme client, puis créez votre boutique plus tard.</p>
                        <button class="btn btn-outline-info px-4" id="bothRolesBtn">
                            <i class="fas fa-sync-alt me-2"></i>Continuer comme Client
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dans Header.php, ajoutez avant le </head> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Utiliser jQuery sans conflit
(function($) {
    $(document).ready(function() {
        let selectedRole = null;
        
        // Sélection de la carte
        $('.role-card').on('click', function() {
            $('.role-card').removeClass('selected');
            $(this).addClass('selected');
            selectedRole = $(this).data('role');
        });
        
        // Bouton de sélection
        $('.select-role').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const role = $(this).data('role');
            selectedRole = role;
            submitRole(role);
        });
        
        // Bouton "Les deux"
        $('#bothRolesBtn').on('click', function(e) {
            e.preventDefault();
            submitRole('client');
        });
        
        function submitRole(role) {
            // Désactiver tous les boutons pendant le traitement
            $('.select-role, #bothRolesBtn').prop('disabled', true);
            
            Swal.fire({
                title: 'Confirmation',
                text: role === 'client' ? 'Vous allez être redirigé vers votre espace client.' : 'Vous allez être redirigé vers la création de votre boutique.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0a66c2',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Continuer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("auth/save_profile_choice") ?>',
                        method: 'POST',
                        data: { profile_type: role },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                window.location.href = response.redirect_url;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: response.message || 'Une erreur est survenue',
                                    confirmButtonColor: '#ff6600'
                                });
                                // Réactiver les boutons
                                $('.select-role, #bothRolesBtn').prop('disabled', false);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur AJAX:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: 'Impossible de communiquer avec le serveur. Vérifiez votre connexion.',
                                confirmButtonColor: '#ff6600'
                            });
                            // Réactiver les boutons
                            $('.select-role, #bothRolesBtn').prop('disabled', false);
                        }
                    });
                } else {
                    // Réactiver les boutons si l'utilisateur annule
                    $('.select-role, #bothRolesBtn').prop('disabled', false);
                }
            });
        }
    });
})(jQuery);
</script>


<?php include VIEWPATH . 'includes/frontend/Footer.php'; ?>