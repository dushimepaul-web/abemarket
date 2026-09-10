<?php include VIEWPATH . 'includes/frontend/Header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
/* Namespace: scp = seller_complete_profile — Aucun conflit avec Bootstrap */
.scp-wrap{max-width:900px;margin:30px auto;padding:0 15px}
.scp-card{background:#fff;border-radius:20px;border:1px solid #e0e0e0;overflow:hidden;margin-bottom:30px}
.scp-card-hdr{background:linear-gradient(135deg,#ff6600,#ff8533);padding:30px;text-align:center;color:#fff}
.scp-card-hdr h1{font-size:28px;margin:0 0 10px;font-family:'Inter',sans-serif}
.scp-card-hdr p{opacity:.9;font-size:14px;margin:0;font-family:'Inter',sans-serif}
.scp-card-body{padding:30px}
.scp-section{font-size:18px;font-weight:600;color:#1a1a2e;margin:25px 0 15px;padding-bottom:10px;border-bottom:2px solid #ff6600;display:inline-block;font-family:'Inter',sans-serif}
.scp-fg{margin-bottom:20px}
.scp-fg label{display:block;font-weight:500;margin-bottom:8px;color:#333;font-size:14px;font-family:'Inter',sans-serif}
.scp-fg .scp-req{color:#ff6600}
.scp-input,.scp-select,.scp-ta{width:100%;padding:12px 15px;border:1px solid #e0e0e0;border-radius:10px;font-size:14px;transition:all .3s;font-family:'Inter',sans-serif;background:#fff;box-sizing:border-box}
.scp-input:focus,.scp-select:focus,.scp-ta:focus{outline:none;border-color:#ff6600;box-shadow:0 0 0 3px rgba(255,102,0,.1)}
.scp-ta{resize:vertical}
.scp-row{display:flex;flex-wrap:wrap;gap:20px;margin-bottom:0}
.scp-col-4{flex:1;min-width:calc(33.33% - 14px)}
.scp-col-6{flex:1;min-width:calc(50% - 10px)}
.scp-btn-submit{width:100%;background:linear-gradient(135deg,#ff6600,#ff8533);border:none;padding:15px;border-radius:10px;font-weight:600;font-size:16px;color:#fff;cursor:pointer;transition:opacity .2s;margin-top:20px;font-family:'Inter',sans-serif}
.scp-btn-submit:hover{opacity:.9}
.scp-btn-submit:disabled{opacity:.6;cursor:not-allowed}
.scp-info{background:#fff3cd;border-left:4px solid #ffc107;padding:15px;margin:20px 0;border-radius:8px;font-size:13px;color:#856404;font-family:'Inter',sans-serif}
.scp-info i{margin-right:8px}
.scp-map-wrap{margin:20px 0;border-radius:10px;overflow:hidden;border:1px solid #e0e0e0}
.scp-map{height:300px;width:100%}
.scp-loc-btn{background:#f0f0f0;border:1px solid #ddd;padding:10px 15px;border-radius:8px;cursor:pointer;font-size:14px;margin-bottom:15px;display:inline-flex;align-items:center;gap:8px;transition:all .3s;font-family:'Inter',sans-serif}
.scp-loc-btn:hover{background:#e0e0e0}
.scp-coords{background:#f8f9fa;padding:10px;border-radius:8px;font-size:12px;color:#666;margin-top:10px;text-align:center;font-family:'Inter',sans-serif}
.scp-muted{color:#999;font-size:12px;margin-top:4px;font-family:'Inter',sans-serif}
.select2-container--default .select2-selection--single{height:46px;padding:5px;border-radius:10px;border-color:#e0e0e0}
.select2-container--default .select2-selection--single .select2-selection__rendered{line-height:34px}
.select2-container--default .select2-selection--single .select2-selection__arrow{height:44px}
@media(max-width:768px){.scp-col-4,.scp-col-6{min-width:100%}.scp-card-body{padding:20px}}
</style>

<div class="scp-wrap">
    <div class="scp-card">
        <div class="scp-card-hdr">
            <h1><i class="fas fa-store"></i> Compléter mon profil vendeur</h1>
            <p>Bonjour <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>, complétez ces informations pour créer votre boutique</p>
        </div>
        <div class="scp-card-body">
            <form id="sellerCompleteForm" enctype="multipart/form-data">
                <div class="scp-info">
                    <i class="fas fa-info-circle"></i> Vos informations personnelles sont déjà renseignées à partir de votre compte
                </div>

                <div class="scp-row">
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Prénom</label>
                            <input type="text" class="scp-input" value="<?= htmlspecialchars($user['prenom']) ?>" disabled>
                        </div>
                    </div>
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Nom</label>
                            <input type="text" class="scp-input" value="<?= htmlspecialchars($user['nom']) ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="scp-row">
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Email</label>
                            <input type="email" class="scp-input" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        </div>
                    </div>
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Téléphone</label>
                            <input type="tel" class="scp-input" value="<?= htmlspecialchars($user['telephone']) ?>" disabled>
                        </div>
                    </div>
                </div>

                <h3 class="scp-section"><i class="fas fa-store"></i> Informations de la boutique</h3>

                <div class="scp-row">
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Nom de la boutique <span class="scp-req">*</span></label>
                            <input type="text" class="scp-input" name="nom_boutique" id="nom_boutique" required placeholder="Ex: Mode & Chic">
                        </div>
                    </div>
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>WhatsApp</label>
                            <input type="tel" class="scp-input" name="whatsapp" id="whatsapp" placeholder="Numéro WhatsApp" value="<?= htmlspecialchars($user['telephone']) ?>">
                        </div>
                    </div>
                </div>

                <div class="scp-fg">
                    <label>Description de la boutique</label>
                    <textarea class="scp-ta" name="description" rows="3" placeholder="Décrivez votre boutique, vos produits, etc..."></textarea>
                </div>

                <div class="scp-fg">
                    <label>Logo de la boutique</label>
                    <input type="file" class="scp-input" name="logo_boutique" accept="image/*">
                    <small class="scp-muted">Format: JPG, PNG, GIF. Taille max: 2MB</small>
                </div>

                <div class="scp-row">
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Type de vendeur</label>
                            <select class="scp-select" name="type_vendeur" id="type_vendeur">
                                <option value="particulier">Particulier</option>
                                <option value="entreprise">Entreprise</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="entreprise_fields" style="display:none;">
                    <div class="scp-row">
                        <div class="scp-col-4">
                            <div class="scp-fg">
                                <label>Nom de l'entreprise</label>
                                <input type="text" class="scp-input" name="nom_entreprise">
                            </div>
                        </div>
                        <div class="scp-col-4">
                            <div class="scp-fg">
                                <label>Numéro NIF</label>
                                <input type="text" class="scp-input" name="numero_nif">
                            </div>
                        </div>
                        <div class="scp-col-4">
                            <div class="scp-fg">
                                <label>Numéro RC</label>
                                <input type="text" class="scp-input" name="numero_rc">
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="scp-section"><i class="fas fa-map-marker-alt"></i> Localisation</h3>

                <div class="scp-row">
                    <div class="scp-col-4">
                        <div class="scp-fg">
                            <label>Province</label>
                            <select class="scp-select" name="id_province" id="id_province">
                                <option value="">Sélectionner</option>
                                <?php foreach($provinces as $province): ?>
                                    <option value="<?= $province['id_province'] ?>"><?= $province['province_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="scp-col-4">
                        <div class="scp-fg">
                            <label>Commune</label>
                            <select class="scp-select" name="id_commune" id="id_commune" disabled>
                                <option value="">Sélectionner d'abord une province</option>
                            </select>
                        </div>
                    </div>
                    <div class="scp-col-4">
                        <div class="scp-fg">
                            <label>Zone</label>
                            <select class="scp-select" name="id_zone" id="id_zone" disabled>
                                <option value="">Sélectionner d'abord une commune</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="scp-row">
                    <div class="scp-col-4">
                        <div class="scp-fg">
                            <label>Colline</label>
                            <select class="scp-select" name="id_colline" id="id_colline" disabled>
                                <option value="">Sélectionner d'abord une zone</option>
                            </select>
                        </div>
                    </div>
                    <div class="scp-col-4"></div>
                    <div class="scp-col-4"></div>
                </div>

                <div class="scp-row">
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Latitude</label>
                            <input type="text" class="scp-input" name="latitude" id="latitude" placeholder="Ex: -3.3822">
                        </div>
                    </div>
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Longitude</label>
                            <input type="text" class="scp-input" name="longitude" id="longitude" placeholder="Ex: 29.3611">
                        </div>
                    </div>
                </div>

                <div class="scp-fg">
                    <button type="button" class="scp-loc-btn" id="getLocationBtn">
                        <i class="fas fa-location-dot"></i> Utiliser ma position actuelle
                    </button>
                    <div class="scp-map-wrap">
                        <div id="locationMap" class="scp-map"></div>
                    </div>
                    <div class="scp-coords" id="coordinatesInfo">
                        <i class="fas fa-info-circle"></i> Cliquez sur la carte pour définir votre position
                    </div>
                </div>

                <h3 class="scp-section"><i class="fas fa-credit-card"></i> Configuration de paiement</h3>

                <div class="scp-row">
                    <div class="scp-col-6">
                        <div class="scp-fg">
                            <label>Méthode de paiement</label>
                            <select class="scp-select" name="methode_paiement" id="methode_paiement">
                                <option value="mobile_money">Mobile Money</option>
                                <option value="virement_bancaire">Virement bancaire</option>
                            </select>
                        </div>
                    </div>
                    <div class="scp-col-6" id="operateur_field">
                        <div class="scp-fg">
                            <label>Opérateur Mobile</label>
                            <select class="scp-select" name="operateur_mobile">
                                <option value="">Sélectionner</option>
                                <option value="BANCOBU">Bancobu</option>
                                <option value="LUMICASH">Lumicash</option>
                                <option value="ECOCASH">EcoCash</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="mobile_money_fields">
                    <div class="scp-row">
                        <div class="scp-col-6">
                            <div class="scp-fg">
                                <label>Numéro Mobile Money</label>
                                <input type="tel" class="scp-input" name="numero_mobile_money" placeholder="Ex: +257 XX XXX XXX">
                            </div>
                        </div>
                        <div class="scp-col-6">
                            <div class="scp-fg">
                                <label>Nom du titulaire</label>
                                <input type="text" class="scp-input" name="nom_abonne_mobile">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bank_fields" style="display:none;">
                    <div class="scp-row">
                        <div class="scp-col-6">
                            <div class="scp-fg">
                                <label>Nom de la banque</label>
                                <input type="text" class="scp-input" name="nom_banque">
                            </div>
                        </div>
                        <div class="scp-col-6">
                            <div class="scp-fg">
                                <label>Numéro de compte</label>
                                <input type="text" class="scp-input" name="numero_compte">
                            </div>
                        </div>
                    </div>
                    <div class="scp-fg">
                        <label>Nom du titulaire</label>
                        <input type="text" class="scp-input" name="nom_titulaire">
                    </div>
                </div>

                <button type="submit" class="scp-btn-submit" id="submitBtn">
                    <i class="fas fa-check-circle"></i> Créer ma boutique
                </button>
            </form>
        </div>
    </div>
</div>

<script>
var map;
var marker;

jQuery(document).ready(function($) {
    initMap(-3.3822, 29.3611);

    $('#type_vendeur').change(function() {
        $(this).val() === 'entreprise' ? $('#entreprise_fields').show() : $('#entreprise_fields').hide();
    });

    $('#methode_paiement').change(function() {
        if ($(this).val() === 'mobile_money') {
            $('#mobile_money_fields').show(); $('#bank_fields').hide(); $('#operateur_field').show();
        } else {
            $('#mobile_money_fields').hide(); $('#bank_fields').show(); $('#operateur_field').hide();
        }
    });

    $('#id_province').change(function() {
        var pid = $(this).val();
        if (pid) {
            $.ajax({url:'<?= base_url("User_dashboard/get_communes") ?>',type:'POST',data:{id_province:pid},dataType:'json',
                success:function(d){var s=$('#id_commune').empty().prop('disabled',false).append('<option value="">Sélectionner une commune</option>');$.each(d,function(i,v){s.append('<option value="'+v.id_commune+'">'+v.commune_name+'</option>')});$('#id_zone').empty().append('<option value="">Sélectionner d\'abord une commune</option>').prop('disabled',true);$('#id_colline').empty().append('<option value="">Sélectionner d\'abord une zone</option>').prop('disabled',true);}
            });
        } else {$('#id_commune').empty().append('<option value="">Sélectionner d\'abord une province</option>').prop('disabled',true);$('#id_zone').empty().append('<option value="">Sélectionner d\'abord une commune</option>').prop('disabled',true);$('#id_colline').empty().append('<option value="">Sélectionner d\'abord une zone</option>').prop('disabled',true);}
    });

    $('#id_commune').change(function() {
        var cid = $(this).val();
        if (cid) {
            $.ajax({url:'<?= base_url("User_dashboard/get_zones") ?>',type:'POST',data:{id_commune:cid},dataType:'json',
                success:function(d){var s=$('#id_zone').empty().prop('disabled',false).append('<option value="">Sélectionner une zone</option>');$.each(d,function(i,v){s.append('<option value="'+v.id_zone+'">'+v.zone_name+'</option>')});$('#id_colline').empty().append('<option value="">Sélectionner d\'abord une zone</option>').prop('disabled',true);}
            });
        } else {$('#id_zone').empty().append('<option value="">Sélectionner d\'abord une commune</option>').prop('disabled',true);$('#id_colline').empty().append('<option value="">Sélectionner d\'abord une zone</option>').prop('disabled',true);}
    });

    $('#id_zone').change(function() {
        var zid = $(this).val();
        if (zid) {
            $.ajax({url:'<?= base_url("User_dashboard/get_collines") ?>',type:'POST',data:{id_zone:zid},dataType:'json',
                success:function(d){var s=$('#id_colline').empty().prop('disabled',false).append('<option value="">Sélectionner une colline</option>');$.each(d,function(i,v){s.append('<option value="'+v.id_colline+'">'+v.colline_name+'</option>')});}
            });
        } else {$('#id_colline').empty().append('<option value="">Sélectionner d\'abord une zone</option>').prop('disabled',true);}
    });

    $('#getLocationBtn').click(function() {
        if (!navigator.geolocation) {Swal.fire({icon:'error',title:'Non supporté',text:'La géolocalisation n\'est pas supportée par votre navigateur'});return;}
        Swal.fire({title:'Localisation en cours...',text:'Veuillez patienter',allowOutsideClick:false,didOpen:function(){Swal.showLoading();}});
        navigator.geolocation.getCurrentPosition(function(p){
            Swal.close();var lat=p.coords.latitude,lng=p.coords.longitude;$('#latitude').val(lat);$('#longitude').val(lng);updateMapPosition(lat,lng);
            Swal.fire({icon:'success',title:'Position obtenue',text:'Votre position a été enregistrée sur la carte',timer:2000,showConfirmButton:false});
        },function(){Swal.close();Swal.fire({icon:'error',title:'Erreur',text:'Impossible d\'obtenir votre position. Vérifiez vos paramètres de localisation.'});});
    });

    $('#sellerCompleteForm').submit(function(e) {
        e.preventDefault();
        var fd=new FormData(this),btn=$('#submitBtn');
        btn.prop('disabled',true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
        $.ajax({url:'<?= base_url("User_dashboard/save_complete_profile") ?>',type:'POST',data:fd,processData:false,contentType:false,dataType:'json',
            success:function(r){
                if(r.success){Swal.fire({icon:'success',title:'Félicitations !',text:r.message,confirmButtonColor:'#ff6600',confirmButtonText:'OK'}).then(function(){window.location.href=r.redirect_url;});}
                else{Swal.fire({icon:'error',title:'Erreur',text:r.message,confirmButtonColor:'#ff6600'});btn.prop('disabled',false).html('<i class="fas fa-check-circle"></i> Créer ma boutique');}
            },
            error:function(xhr){
                var msg = 'Une erreur est survenue. Veuillez réessayer.';
                try { var d = JSON.parse(xhr.responseText); if (d.message) msg = d.message; } catch(e) {}
                if (xhr.status === 0) msg = 'Pas de connexion internet.';
                else if (xhr.status === 303) msg = 'Session expirée. Reconnectez-vous.';
                else if (xhr.status >= 400) msg = 'Erreur serveur (' + xhr.status + ').';
                Swal.fire({icon:'error',title:'Erreur',text:msg,confirmButtonColor:'#ff6600'});
                btn.prop('disabled',false).html('<i class="fas fa-check-circle"></i> Créer ma boutique');
            }
        });
    });
});

function initMap(lat,lng){
    if(map)map.remove();
    map=L.map('locationMap').setView([lat,lng],13);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',{attribution:'&copy; OpenStreetMap contributors'}).addTo(map);
    marker=L.marker([lat,lng],{draggable:true}).addTo(map);
    marker.on('dragend',function(e){var p=marker.getLatLng();$('#latitude').val(p.lat);$('#longitude').val(p.lng);$('#coordinatesInfo').html('<i class="fas fa-check-circle" style="color:#28a745;"></i> Position : Lat '+p.lat.toFixed(6)+', Lng '+p.lng.toFixed(6));});
    map.on('click',function(e){marker.setLatLng(e.latlng);$('#latitude').val(e.latlng.lat);$('#longitude').val(e.latlng.lng);$('#coordinatesInfo').html('<i class="fas fa-check-circle" style="color:#28a745;"></i> Position : Lat '+e.latlng.lat.toFixed(6)+', Lng '+e.latlng.lng.toFixed(6));});
}

function updateMapPosition(lat,lng){
    if(map&&marker){map.setView([lat,lng],15);marker.setLatLng([lat,lng]);}else{initMap(lat,lng);}
}

$('#latitude, #longitude').on('change',function(){
    var lat=parseFloat($('#latitude').val()),lng=parseFloat($('#longitude').val());
    if(!isNaN(lat)&&!isNaN(lng))updateMapPosition(lat,lng);
});
</script>

<?php include VIEWPATH . 'includes/frontend/Footer.php'; ?>
