<?php include VIEWPATH . 'includes/frontend/Header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
   
    .container {
        max-width: 900px;
        margin: 0 auto;
    }
    .card {
        background: white;
        border-radius: 20px;
        margin-bottom: 30px;
        border: 1px solid #e0e0e0;
    }
    .card-header {
        background: linear-gradient(135deg, #ff6600, #ff8533);
        padding: 30px;
        text-align: center;
        color: white;
        border-radius: 20px 20px 0 0;
    }
    .card-header h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }
    .card-header p {
        opacity: 0.9;
        font-size: 14px;
    }
    .card-body {
        padding: 30px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ff6600;
        display: inline-block;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #333;
        font-size: 14px;
    }
    .form-group label .required {
        color: #ff6600;
    }
    .form-control, .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        font-family: 'Inter', sans-serif;
        background: white;
    }
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #ff6600;
        box-shadow: 0 0 0 3px rgba(255,102,0,0.1);
    }
    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .col-md-6 {
        flex: 1;
        min-width: calc(50% - 10px);
    }
    .col-md-4 {
        flex: 1;
        min-width: calc(33.33% - 14px);
    }
    .col-12 {
        width: 100%;
    }
    .btn-submit {
        width: 100%;
        background: linear-gradient(135deg, #ff6600, #ff8533);
        border: none;
        padding: 15px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        color: white;
        cursor: pointer;
        transition: opacity 0.2s;
        margin-top: 20px;
    }
    .btn-submit:hover {
        opacity: 0.9;
    }
    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .info-text {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        margin: 20px 0;
        border-radius: 8px;
        font-size: 13px;
        color: #856404;
    }
    .info-text i {
        margin-right: 8px;
    }
    .map-container {
        margin: 20px 0;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }
    #locationMap {
        height: 300px;
        width: 100%;
    }
    .current-location-btn {
        background: #f0f0f0;
        border: 1px solid #ddd;
        padding: 10px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        margin-bottom: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    .current-location-btn:hover {
        background: #e0e0e0;
    }
    .coordinates-info {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        font-size: 12px;
        color: #666;
        margin-top: 10px;
        text-align: center;
    }
    @media (max-width: 768px) {
        .col-md-6, .col-md-4 {
            min-width: 100%;
        }
        .card-body {
            padding: 20px;
        }
    }
    .select2-container--default .select2-selection--single {
        height: 46px;
        padding: 5px;
        border-radius: 10px;
        border-color: #e0e0e0;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 34px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 44px;
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h1><i class="fas fa-store"></i> Compléter mon profil vendeur</h1>
            <p>Bonjour <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>, complétez ces informations pour créer votre boutique</p>
        </div>
        <div class="card-body">
            <form id="sellerCompleteForm" enctype="multipart/form-data">
                <!-- Informations personnelles (pré-remplies) -->
                <div class="info-text">
                    <i class="fas fa-info-circle"></i> Vos informations personnelles sont déjà renseignées à partir de votre compte
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" disabled>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="tel" class="form-control" value="<?= htmlspecialchars($user['telephone']) ?>" disabled>
                        </div>
                    </div>
                </div>
                
                <!-- Informations de la boutique -->
                <h3 class="section-title"><i class="fas fa-store"></i> Informations de la boutique</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nom de la boutique <span class="required">*</span></label>
                            <input type="text" class="form-control" name="nom_boutique" id="nom_boutique" required placeholder="Ex: Mode & Chic">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>WhatsApp</label>
                            <input type="tel" class="form-control" name="whatsapp" id="whatsapp" placeholder="Numéro WhatsApp" value="<?= htmlspecialchars($user['telephone']) ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description de la boutique</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Décrivez votre boutique, vos produits, etc..."></textarea>
                </div>
                
                <div class="form-group">
                    <label>Logo de la boutique</label>
                    <input type="file" class="form-control" name="logo_boutique" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, GIF. Taille max: 2MB</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type de vendeur</label>
                            <select class="form-select" name="type_vendeur" id="type_vendeur">
                                <option value="particulier">Particulier</option>
                                <option value="entreprise">Entreprise</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Champs entreprise -->
                <div id="entreprise_fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom de l'entreprise</label>
                                <input type="text" class="form-control" name="nom_entreprise">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Numéro NIF</label>
                                <input type="text" class="form-control" name="numero_nif">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Numéro RC</label>
                                <input type="text" class="form-control" name="numero_rc">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Localisation -->
                <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Localisation</h3>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Province</label>
                            <select class="form-select" name="id_province" id="id_province">
                                <option value="">Sélectionner</option>
                                <?php foreach($provinces as $province): ?>
                                    <option value="<?= $province['id_province'] ?>"><?= $province['province_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Commune</label>
                            <select class="form-select" name="id_commune" id="id_commune" disabled>
                                <option value="">Sélectionner d'abord une province</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Quartier</label>
                            <select class="form-select" name="id_quartier" id="id_quartier" disabled>
                                <option value="">Sélectionner d'abord une commune</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Latitude</label>
                            <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Ex: -3.3822">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Ex: 29.3611">
                        </div>
                    </div>
                </div>
                
                <!-- Carte interactive -->
                <div class="form-group">
                    <button type="button" class="current-location-btn" id="getLocationBtn">
                        <i class="fas fa-location-dot"></i> Utiliser ma position actuelle
                    </button>
                    <div class="map-container">
                        <div id="locationMap"></div>
                    </div>
                    <div class="coordinates-info" id="coordinatesInfo">
                        <i class="fas fa-info-circle"></i> Cliquez sur la carte pour définir votre position
                    </div>
                </div>
                
                <!-- Configuration de paiement -->
                <h3 class="section-title"><i class="fas fa-credit-card"></i> Configuration de paiement</h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Méthode de paiement</label>
                            <select class="form-select" name="methode_paiement" id="methode_paiement">
                                <option value="mobile_money">Mobile Money</option>
                                <option value="virement_bancaire">Virement bancaire</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6" id="operateur_field">
                        <div class="form-group">
                            <label>Opérateur Mobile</label>
                            <select class="form-select" name="operateur_mobile">
                                <option value="">Sélectionner</option>
                                <option value="BANCOBU">Bancobu</option>
                                <option value="LUMICASH">Lumicash</option>
                                <option value="ECOCASH">EcoCash</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div id="mobile_money_fields">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Numéro Mobile Money</label>
                                <input type="tel" class="form-control" name="numero_mobile_money" placeholder="Ex: +257 XX XXX XXX">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom du titulaire</label>
                                <input type="text" class="form-control" name="nom_abonne_mobile">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="bank_fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom de la banque</label>
                                <input type="text" class="form-control" name="nom_banque">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Numéro de compte</label>
                                <input type="text" class="form-control" name="numero_compte">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nom du titulaire</label>
                        <input type="text" class="form-control" name="nom_titulaire">
                    </div>
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-check-circle"></i> Créer ma boutique
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let map;
let marker;

$(document).ready(function() {
    // Initialiser la carte avec des coordonnées par défaut (Bujumbura)
    initMap(-3.3822, 29.3611);
    
    // Toggle champs entreprise
    $('#type_vendeur').change(function() {
        if ($(this).val() === 'entreprise') {
            $('#entreprise_fields').show();
        } else {
            $('#entreprise_fields').hide();
        }
    });
    
    // Toggle champs paiement
    $('#methode_paiement').change(function() {
        if ($(this).val() === 'mobile_money') {
            $('#mobile_money_fields').show();
            $('#bank_fields').hide();
            $('#operateur_field').show();
        } else {
            $('#mobile_money_fields').hide();
            $('#bank_fields').show();
            $('#operateur_field').hide();
        }
    });
    
    // Chargement des communes
    $('#id_province').change(function() {
        var provinceId = $(this).val();
        if (provinceId) {
            $.ajax({
                url: '<?= base_url("User_dashboard/get_communes") ?>',
                type: 'POST',
                data: { id_province: provinceId },
                dataType: 'json',
                success: function(data) {
                    var communeSelect = $('#id_commune');
                    communeSelect.empty();
                    communeSelect.append('<option value="">Sélectionner une commune</option>');
                    communeSelect.prop('disabled', false);
                    $.each(data, function(i, commune) {
                        communeSelect.append('<option value="' + commune.id_commune + '">' + commune.commune_name + '</option>');
                    });
                    $('#id_quartier').empty().append('<option value="">Sélectionner d\'abord une commune</option>').prop('disabled', true);
                }
            });
        } else {
            $('#id_commune').empty().append('<option value="">Sélectionner d\'abord une province</option>').prop('disabled', true);
            $('#id_quartier').empty().append('<option value="">Sélectionner d\'abord une commune</option>').prop('disabled', true);
        }
    });
    
    // Chargement des quartiers
    $('#id_commune').change(function() {
        var communeId = $(this).val();
        if (communeId) {
            $.ajax({
                url: '<?= base_url("User_dashboard/get_quartiers") ?>',
                type: 'POST',
                data: { id_commune: communeId },
                dataType: 'json',
                success: function(data) {
                    var quartierSelect = $('#id_quartier');
                    quartierSelect.empty();
                    quartierSelect.append('<option value="">Sélectionner un quartier</option>');
                    quartierSelect.prop('disabled', false);
                    $.each(data, function(i, quartier) {
                        quartierSelect.append('<option value="' + quartier.id_quartier + '">' + quartier.quartier_name + '</option>');
                    });
                }
            });
        } else {
            $('#id_quartier').empty().append('<option value="">Sélectionner d\'abord une commune</option>').prop('disabled', true);
        }
    });
    
    // Bouton pour utiliser la position actuelle
    $('#getLocationBtn').click(function() {
        if (navigator.geolocation) {
            Swal.fire({
                title: 'Localisation en cours...',
                text: 'Veuillez patienter',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            navigator.geolocation.getCurrentPosition(function(position) {
                Swal.close();
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                $('#latitude').val(lat);
                $('#longitude').val(lng);
                updateMapPosition(lat, lng);
                Swal.fire({
                    icon: 'success',
                    title: 'Position obtenue',
                    text: 'Votre position a été enregistrée sur la carte',
                    timer: 2000,
                    showConfirmButton: false
                });
            }, function(error) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible d\'obtenir votre position. Vérifiez vos paramètres de localisation.'
                });
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Non supporté',
                text: 'La géolocalisation n\'est pas supportée par votre navigateur'
            });
        }
    });
    
    // Soumission du formulaire
    $('#sellerCompleteForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var submitBtn = $('#submitBtn');
        
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
        
        $.ajax({
            url: '<?= base_url("User_dashboard/save_complete_profile") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Félicitations !',
                        text: response.message,
                        confirmButtonColor: '#ff6600',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = response.redirect_url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.message,
                        confirmButtonColor: '#ff6600'
                    });
                    submitBtn.prop('disabled', false);
                    submitBtn.html('<i class="fas fa-check-circle"></i> Créer ma boutique');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue. Veuillez réessayer.',
                    confirmButtonColor: '#ff6600'
                });
                submitBtn.prop('disabled', false);
                submitBtn.html('<i class="fas fa-check-circle"></i> Créer ma boutique');
            }
        });
    });
});

// Initialiser la carte
function initMap(lat, lng) {
    if (map) {
        map.remove();
    }
    
    map = L.map('locationMap').setView([lat, lng], 13);
    
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    
    marker.on('dragend', function(e) {
        var pos = marker.getLatLng();
        $('#latitude').val(pos.lat);
        $('#longitude').val(pos.lng);
        $('#coordinatesInfo').html('<i class="fas fa-check-circle" style="color:#28a745;"></i> Position enregistrée : Lat ' + pos.lat.toFixed(6) + ', Lng ' + pos.lng.toFixed(6));
    });
    
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        marker.setLatLng(e.latlng);
        $('#latitude').val(lat);
        $('#longitude').val(lng);
        $('#coordinatesInfo').html('<i class="fas fa-check-circle" style="color:#28a745;"></i> Position enregistrée : Lat ' + lat.toFixed(6) + ', Lng ' + lng.toFixed(6));
    });
}

// Mettre à jour la position sur la carte
function updateMapPosition(lat, lng) {
    if (map && marker) {
        map.setView([lat, lng], 15);
        marker.setLatLng([lat, lng]);
    } else {
        initMap(lat, lng);
    }
}

// Mettre à jour la carte quand les champs latitude/longitude changent
$('#latitude, #longitude').on('change', function() {
    var lat = parseFloat($('#latitude').val());
    var lng = parseFloat($('#longitude').val());
    if (!isNaN(lat) && !isNaN(lng)) {
        updateMapPosition(lat, lng);
    }
});
</script>

<?php include VIEWPATH . 'includes/frontend/Footer.php'; ?>