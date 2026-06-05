<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .btn-confirm { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px 30px; font-weight: bold; }
        .btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102,126,234,0.4); }
        #camera-preview { width: 100%; max-width: 400px; margin: 0 auto; border-radius: 15px; overflow: hidden; }
        #photo-canvas { display: none; }
        .info-card { background: #f8f9fa; border-radius: 15px; padding: 15px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Confirmation de livraison</h4>
                    </div>
                    <div class="card-body">
                        <!-- Informations commande -->
                        <div class="info-card">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Commande N°</small>
                                    <h6 class="mb-0"><?= $commande->numero_commande ?></h6>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Montant</small>
                                    <h6 class="mb-0 text-primary"><?= number_format($commande->montant_total, 0, ',', ' ') ?> FBu</h6>
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-card">
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-muted">Client</small>
                                    <h6 class="mb-0"><?= htmlspecialchars($commande->prenom . ' ' . $commande->nom) ?></h6>
                                    <small class="text-muted">Tél: <?= htmlspecialchars($commande->telephone ?? 'Non renseigné') ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Zone de capture photo -->
                        <div class="text-center mb-3">
                            <div id="camera-preview">
                                <video id="video" width="100%" height="auto" autoplay playsinline style="border-radius: 15px;"></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                            </div>
                            <div id="photo-preview" style="display: none;">
                                <img id="photo-img" class="img-fluid rounded" style="max-width: 100%;">
                            </div>
                            <div class="mt-3">
                                <button type="button" id="capture-btn" class="btn btn-primary">
                                    <iconify-icon icon="solar:camera-bold-duotone" class="me-1"></iconify-icon>
                                    Prendre une photo
                                </button>
                                <button type="button" id="retake-btn" class="btn btn-secondary" style="display: none;">
                                    <iconify-icon icon="solar:refresh-bold-duotone" class="me-1"></iconify-icon>
                                    Reprendre
                                </button>
                            </div>
                        </div>
                        
                        <!-- Position GPS -->
                        <div class="info-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <iconify-icon icon="solar:map-point-bold-duotone" class="me-1"></iconify-icon>
                                    Position GPS
                                </span>
                                <span id="gps-status" class="text-muted">En attente...</span>
                            </div>
                            <div id="gps-coords" class="small text-muted mt-1"></div>
                        </div>
                        
                        <!-- Bouton confirmation -->
                        <button type="button" id="confirm-btn" class="btn btn-success btn-lg w-100 btn-confirm" disabled>
                            <iconify-icon icon="solar:check-circle-bold-duotone" class="me-2 fs-20"></iconify-icon>
                            Confirmer la livraison
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="confirmForm" style="display: none;">
        <input type="hidden" name="token" value="<?= $qr->token ?>">
        <input type="hidden" name="latitude" id="input-latitude">
        <input type="hidden" name="longitude" id="input-longitude">
        <input type="hidden" name="id_transporteur" id="input-transporteur">
        <input type="file" name="photo" id="photo-file" accept="image/*">
    </form>

    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script>
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let photoImg = document.getElementById('photo-img');
        let stream = null;
        let photoData = null;
        let latitude = null;
        let longitude = null;
        
        // Initialiser la caméra
        async function initCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                await video.play();
                $('#capture-btn').prop('disabled', false);
            } catch (err) {
                console.error('Erreur caméra:', err);
                $('#camera-preview').html('<div class="alert alert-warning">Impossible d\'accéder à la caméra. Veuillez vérifier vos permissions.</div>');
            }
        }
        
        // Prendre une photo
        $('#capture-btn').on('click', function() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            photoData = canvas.toDataURL('image/jpeg', 0.8);
            photoImg.src = photoData;
            
            // Convertir en Blob pour upload
            fetch(photoData)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], 'livraison.jpg', { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('photo-file').files = dataTransfer.files;
                });
            
            $('#camera-preview').hide();
            $('#photo-preview').show();
            $('#capture-btn').hide();
            $('#retake-btn').show();
            
            verifierConditions();
        });
        
        // Reprendre une photo
        $('#retake-btn').on('click', function() {
            $('#photo-preview').hide();
            $('#camera-preview').show();
            $('#capture-btn').show();
            $('#retake-btn').hide();
            photoData = null;
            document.getElementById('photo-file').value = '';
            verifierConditions();
        });
        
        // Obtenir la position GPS
        function getLocation() {
            $('#gps-status').html('<span class="spinner-border spinner-border-sm me-1"></span> Recherche...');
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        latitude = position.coords.latitude;
                        longitude = position.coords.longitude;
                        $('#input-latitude').val(latitude);
                        $('#input-longitude').val(longitude);
                        $('#gps-status').html('<span class="text-success">✓ Position obtenue</span>');
                        $('#gps-coords').html(`${latitude.toFixed(6)}, ${longitude.toFixed(6)}`);
                        verifierConditions();
                    },
                    function(error) {
                        console.error('Erreur GPS:', error);
                        $('#gps-status').html('<span class="text-danger">✗ Impossible d\'obtenir la position</span>');
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                $('#gps-status').html('<span class="text-danger">✗ GPS non supporté</span>');
            }
        }
        
        // Vérifier toutes les conditions
        function verifierConditions() {
            const hasPhoto = photoData !== null;
            const hasGps = latitude !== null && longitude !== null;
            
            if (hasPhoto && hasGps) {
                $('#confirm-btn').prop('disabled', false);
            } else {
                $('#confirm-btn').prop('disabled', true);
            }
        }
        
        // Confirmer la livraison
        $('#confirm-btn').on('click', function() {
            const formData = new FormData(document.getElementById('confirmForm'));
            
            Swal.fire({
                title: 'Confirmation',
                text: 'Voulez-vous confirmer la livraison de cette commande ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#confirm-btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Confirmation...');
                    
                    $.ajax({
                        url: '<?= base_url("qr/confirm") ?>',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Succès', response.message, 'success').then(() => {
                                    window.location.href = '<?= base_url("qr/merci") ?>';
                                });
                            } else {
                                Swal.fire('Erreur', response.message, 'error');
                                $('#confirm-btn').prop('disabled', false).html('<iconify-icon icon="solar:check-circle-bold-duotone" class="me-2 fs-20"></iconify-icon> Confirmer la livraison');
                            }
                        },
                        error: function() {
                            Swal.fire('Erreur', 'Erreur de connexion', 'error');
                            $('#confirm-btn').prop('disabled', false).html('<iconify-icon icon="solar:check-circle-bold-duotone" class="me-2 fs-20"></iconify-icon> Confirmer la livraison');
                        }
                    });
                }
            });
        });
        
        // Initialisation
        $(document).ready(function() {
            initCamera();
            getLocation();
            
            // Récupérer l'ID du transporteur depuis la session
            $.get('<?= base_url("api/get_transporteur_id") ?>', function(response) {
                if (response.id) {
                    $('#input-transporteur').val(response.id);
                }
            }, 'json');
        });
    </script>
</body>
</html>