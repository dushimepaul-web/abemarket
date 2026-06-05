<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Ajouter un vendeur</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('Sellers') ?>">Vendeurs</a></li>
                            <li class="breadcrumb-item active">Ajouter</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informations du vendeur</h4>
                        <p class="text-muted mb-0">Sélectionnez un utilisateur existant et complétez les informations de sa boutique</p>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('Sellers/add') ?>" method="POST" enctype="multipart/form-data" id="sellerForm">
                            <!-- Sélection de l'utilisateur -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card bg-light border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="bx bx-user-plus me-2"></i>Étape 1 : Sélectionner un utilisateur</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Rechercher un utilisateur <span class="text-danger">*</span></label>
                                                <select class="form-select" name="id_utilisateur" id="user_search" style="width: 100%;" required>
                                                    <option value="">Rechercher par nom, prénom, email ou téléphone...</option>
                                                </select>
                                                <div class="form-text">
                                                    <i class="bx bx-info-circle"></i> Seuls les utilisateurs qui ne sont pas déjà vendeurs sont affichés
                                                </div>
                                            </div>
                                            
                                            <!-- Informations utilisateur pré-remplies -->
                                            <div id="user_info" class="mt-3" style="display: none;">
                                                <div class="alert alert-info">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <strong><i class="bx bx-user"></i> Nom complet :</strong>
                                                            <span id="display_nom_complet"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong><i class="bx bx-envelope"></i> Email :</strong>
                                                            <span id="display_email"></span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong><i class="bx bx-phone"></i> Téléphone :</strong>
                                                            <span id="display_telephone"></span>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-12">
                                                            <strong><i class="bx bx-calendar"></i> Inscrit le :</strong>
                                                            <span id="display_date_creation"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="user_prenom" id="user_prenom">
                                                <input type="hidden" name="user_nom" id="user_nom">
                                                <input type="hidden" name="user_email" id="user_email">
                                                <input type="hidden" name="user_telephone" id="user_telephone">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de la boutique -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="bx bx-store me-2"></i>Étape 2 : Informations de la boutique</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nom de la boutique <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="nom_boutique" id="nom_boutique" required>
                                                        <div class="form-text">Ce nom sera visible par les clients</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Slug généré</label>
                                                        <input type="text" class="form-control" id="slug_preview" readonly disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Logo de la boutique</label>
                                                        <input type="file" class="form-control" name="logo_boutique" accept="image/*">
                                                        <div class="form-text">Logo recommandé: 200x200px</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Taux de commission (%)</label>
                                                        <input type="number" class="form-control" name="taux_commission" value="10" step="0.5" min="0" max="50">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description de la boutique</label>
                                                <textarea class="form-control" name="description" rows="3" placeholder="Décrivez votre boutique..."></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Type de vendeur</label>
                                                    <select class="form-select" name="type_vendeur" id="type_vendeur">
                                                        <option value="particulier">Particulier</option>
                                                        <option value="entreprise">Entreprise</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">WhatsApp</label>
                                                    <input type="tel" class="form-control" name="whatsapp" id="whatsapp" placeholder="Numéro WhatsApp">
                                                </div>
                                            </div>
                                            
                                            <!-- Champs entreprise (cachés par défaut) -->
                                            <div id="entreprise_fields" style="display: none;">
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <h6 class="mb-3">Informations de l'entreprise</h6>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Nom de l'entreprise</label>
                                                        <input type="text" class="form-control" name="nom_entreprise">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Numéro NIF</label>
                                                        <input type="text" class="form-control" name="numero_nif">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Numéro RC</label>
                                                        <input type="text" class="form-control" name="numero_rc">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Localisation -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="bx bx-map me-2"></i>Localisation</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Province</label>
                                                    <select class="form-select" name="id_province" id="id_province">
                                                        <option value="">Sélectionner une province</option>
                                                        <?php if(isset($provinces) && !empty($provinces)): ?>
                                                            <?php foreach($provinces as $province): ?>
                                                                <option value="<?= $province['id_province'] ?>"><?= $province['province_name'] ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Commune</label>
                                                    <select class="form-select" name="id_commune" id="id_commune" disabled>
                                                        <option value="">Sélectionner d'abord une province</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Quartier</label>
                                                    <select class="form-select" name="id_quartier" id="id_quartier" disabled>
                                                        <option value="">Sélectionner d'abord une commune</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Latitude</label>
                                                    <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Ex: -3.3822">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Longitude</label>
                                                    <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Ex: 29.3611">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="getCurrentLocation()">
                                                    <i class="bx bx-current-location me-1"></i>Utiliser ma position actuelle
                                                </button>
                                            </div>
                                            <div id="locationMap" style="height: 300px; border-radius: 8px; display: none;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Configuration de paiement -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="bx bx-credit-card me-2"></i>Configuration de paiement</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Méthode de paiement principale</label>
                                                    <select class="form-select" name="methode_paiement" id="methode_paiement">
                                                        <option value="mobile_money">Mobile Money</option>
                                                        <option value="virement_bancaire">Virement bancaire</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3" id="operateur_field">
                                                    <label class="form-label">Opérateur Mobile</label>
                                                    <select class="form-select" name="operateur_mobile">
                                                        <option value="">Sélectionner</option>
                                                        <option value="BANCOBU">Bancobu</option>
                                                        <option value="LUMICASH">Lumicash</option>
                                                        <option value="ECOCASH">EcoCash</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" id="mobile_money_fields">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Numéro Mobile Money</label>
                                                    <input type="tel" class="form-control" name="numero_mobile_money" placeholder="Ex: +257 XX XXX XXX">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Nom du titulaire</label>
                                                    <input type="text" class="form-control" name="nom_abonne_mobile">
                                                </div>
                                            </div>
                                            <div class="row" id="bank_fields" style="display: none;">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Nom de la banque</label>
                                                    <input type="text" class="form-control" name="nom_banque">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Numéro de compte</label>
                                                    <input type="text" class="form-control" name="numero_compte">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Nom du titulaire</label>
                                                    <input type="text" class="form-control" name="nom_titulaire">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="bx bx-file me-2"></i>Documents d'identification</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="documents_container">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Type de document</label>
                                                    <select class="form-select" name="doc_type[]">
                                                        <option value="carte_identite">Carte d'identité</option>
                                                        <option value="passeport">Passeport</option>
                                                        <option value="licence_commerce">Licence de commerce</option>
                                                        <option value="attestation_fiscale">Attestation fiscale</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Fichier document</label>
                                                    <input type="file" class="form-control" name="doc_file[]" accept="image/*,.pdf">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addDocumentField()">
                                                    <i class="bx bx-plus me-1"></i>Ajouter un document
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Paramètres du compte vendeur -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="bx bx-slider me-2"></i>Paramètres du compte vendeur</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Statut vendeur</label>
                                                    <select class="form-select" name="statut">
                                                        <option value="actif">Actif</option>
                                                        <option value="en_attente">En attente</option>
                                                        <option value="suspendu">Suspendu</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Approbation boutique</label>
                                                    <select class="form-select" name="est_approuve">
                                                        <option value="0">En attente</option>
                                                        <option value="1">Approuvé</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Délai paiement (jours)</label>
                                                    <input type="number" class="form-control" name="delai_paiement_jours" value="7" min="0" max="60">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                        <i class="bx bx-save me-1"></i>Créer le vendeur
                                    </button>
                                    <a href="<?= base_url('Sellers') ?>" class="btn btn-secondary btn-lg">
                                        <i class="bx bx-x me-1"></i>Annuler
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Select2 CSS et JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
let map, marker;

// Initialisation Select2 pour la recherche d'utilisateur
$(document).ready(function() {
    $('#user_search').select2({
        theme: 'bootstrap-5',
        placeholder: 'Rechercher un utilisateur...',
        allowClear: true,
        ajax: {
            url: '<?= base_url("Sellers/search_users") ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    search: params.term,
                    page: params.page || 1
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: data.total_count > (params.page * 10)
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        templateResult: formatUserResult,
        templateSelection: formatUserSelection
    });
    
    // Événement quand un utilisateur est sélectionné
    $('#user_search').on('select2:select', function(e) {
        const user = e.params.data;
        if (user && user.id) {
            loadUserDetails(user.id);
            document.getElementById('submitBtn').disabled = false;
        }
    });
    
    $('#user_search').on('select2:clear', function() {
        document.getElementById('user_info').style.display = 'none';
        document.getElementById('submitBtn').disabled = true;
    });
});

// Formatage des résultats dans la liste déroulante
function formatUserResult(user) {
    if (user.loading) return user.text;
    
    let avatarHtml = '';
    if (user.avatar_url) {
        avatarHtml = `<img src="<?= base_url('') ?>${user.avatar_url}" class="rounded-circle me-2" width="32" height="32">`;
    } else {
        avatarHtml = `<div class="avatar-xs rounded-circle bg-light d-inline-flex align-items-center justify-content-center me-2" style="width:32px;height:32px">
                        <i class="bx bx-user fs-16"></i>
                      </div>`;
    }
    
    let html = `<div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        ${avatarHtml}
                    </div>
                    <div class="flex-grow-1">
                        <strong>${user.prenom} ${user.nom}</strong><br>
                        <small class="text-muted">${user.email} | ${user.telephone || 'Pas de téléphone'}</small>
                    </div>
                </div>`;
    return $(html);
}

// Formatage de la sélection
function formatUserSelection(user) {
    return user.prenom && user.nom ? `${user.prenom} ${user.nom} (${user.email})` : user.text;
}

// Charger les détails complets de l'utilisateur
function loadUserDetails(userId) {
    $.ajax({
        url: '<?= base_url("Sellers/get_user_details") ?>',
        type: 'POST',
        data: { id_utilisateur: userId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const user = response.user;
                document.getElementById('display_nom_complet').innerHTML = `${user.prenom} ${user.nom}`;
                document.getElementById('display_email').innerHTML = user.email;
                document.getElementById('display_telephone').innerHTML = user.telephone || 'Non renseigné';
                document.getElementById('display_date_creation').innerHTML = user.date_creation;
                
                document.getElementById('user_prenom').value = user.prenom;
                document.getElementById('user_nom').value = user.nom;
                document.getElementById('user_email').value = user.email;
                document.getElementById('user_telephone').value = user.telephone;
                
                // Pré-remplir le WhatsApp si vide
                if (!document.getElementById('whatsapp').value && user.telephone) {
                    document.getElementById('whatsapp').value = user.telephone;
                }
                
                document.getElementById('user_info').style.display = 'block';
            }
        }
    });
}

// Génération automatique du slug
document.getElementById('nom_boutique').addEventListener('keyup', function() {
    let slug = this.value.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('slug_preview').value = slug;
});

// Toggle champs entreprise
document.getElementById('type_vendeur').addEventListener('change', function() {
    const entrepriseFields = document.getElementById('entreprise_fields');
    if (this.value === 'entreprise') {
        entrepriseFields.style.display = 'block';
    } else {
        entrepriseFields.style.display = 'none';
    }
});

// Toggle champs paiement
document.getElementById('methode_paiement').addEventListener('change', function() {
    const mobileFields = document.getElementById('mobile_money_fields');
    const bankFields = document.getElementById('bank_fields');
    const operateurField = document.getElementById('operateur_field');
    
    if (this.value === 'mobile_money') {
        mobileFields.style.display = 'flex';
        bankFields.style.display = 'none';
        operateurField.style.display = 'block';
    } else {
        mobileFields.style.display = 'none';
        bankFields.style.display = 'flex';
        operateurField.style.display = 'none';
    }
});

// Chargement des communes par province
document.getElementById('id_province').addEventListener('change', function() {
    const provinceId = this.value;
    const communeSelect = document.getElementById('id_commune');
    const quartierSelect = document.getElementById('id_quartier');
    
    communeSelect.disabled = true;
    communeSelect.innerHTML = '<option value="">Chargement...</option>';
    quartierSelect.disabled = true;
    quartierSelect.innerHTML = '<option value="">Sélectionner d\'abord une commune</option>';
    
    if (provinceId) {
        fetch('<?= base_url("Sellers/get_communes") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_province=' + provinceId
        })
        .then(response => response.json())
        .then(data => {
            communeSelect.disabled = false;
            communeSelect.innerHTML = '<option value="">Sélectionner une commune</option>';
            data.forEach(commune => {
                communeSelect.innerHTML += `<option value="${commune.id_commune}">${commune.commune_name}</option>`;
            });
        });
    } else {
        communeSelect.disabled = true;
        communeSelect.innerHTML = '<option value="">Sélectionner d\'abord une province</option>';
    }
});

// Chargement des quartiers par commune
document.getElementById('id_commune').addEventListener('change', function() {
    const communeId = this.value;
    const quartierSelect = document.getElementById('id_quartier');
    
    quartierSelect.disabled = true;
    quartierSelect.innerHTML = '<option value="">Chargement...</option>';
    
    if (communeId) {
        fetch('<?= base_url("Sellers/get_quartiers") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id_commune=' + communeId
        })
        .then(response => response.json())
        .then(data => {
            quartierSelect.disabled = false;
            quartierSelect.innerHTML = '<option value="">Sélectionner un quartier</option>';
            data.forEach(quartier => {
                quartierSelect.innerHTML += `<option value="${quartier.id_quartier}">${quartier.quartier_name}</option>`;
            });
        });
    } else {
        quartierSelect.disabled = true;
        quartierSelect.innerHTML = '<option value="">Sélectionner d\'abord une commune</option>';
    }
});

// Obtenir la position actuelle
function getCurrentLocation() {
    if (navigator.geolocation) {
        Swal.fire({
            title: 'Localisation en cours',
            text: 'Veuillez patienter...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        navigator.geolocation.getCurrentPosition(function(position) {
            Swal.close();
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            showMap(position.coords.latitude, position.coords.longitude);
            Swal.fire({
                icon: 'success',
                title: 'Position obtenue',
                text: `Latitude: ${position.coords.latitude}, Longitude: ${position.coords.longitude}`,
                timer: 2000
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
            text: 'La géolocalisation n\'est pas supportée par votre navigateur.'
        });
    }
}

// Afficher la carte
function showMap(lat, lng) {
    const mapContainer = document.getElementById('locationMap');
    mapContainer.style.display = 'block';
    
    if (map) {
        map.remove();
    }
    
    map = L.map('locationMap').setView([lat, lng], 15);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
    
    marker.on('dragend', function(e) {
        const pos = marker.getLatLng();
        document.getElementById('latitude').value = pos.lat;
        document.getElementById('longitude').value = pos.lng;
    });
    
    map.on('click', function(e) {
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng, { draggable: true }).addTo(map);
            marker.on('dragend', function(ev) {
                const pos = marker.getLatLng();
                document.getElementById('latitude').value = pos.lat;
                document.getElementById('longitude').value = pos.lng;
            });
        }
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });
}

// Ajouter un champ document
function addDocumentField() {
    const container = document.getElementById('documents_container');
    const newRow = document.createElement('div');
    newRow.className = 'row mt-2';
    newRow.innerHTML = `
        <div class="col-md-6 mb-3">
            <select class="form-select" name="doc_type[]">
                <option value="carte_identite">Carte d'identité</option>
                <option value="passeport">Passeport</option>
                <option value="licence_commerce">Licence de commerce</option>
                <option value="attestation_fiscale">Attestation fiscale</option>
                <option value="justificatif_domicile">Justificatif de domicile</option>
            </select>
        </div>
        <div class="col-md-5 mb-3">
            <input type="file" class="form-control" name="doc_file[]" accept="image/*,.pdf">
        </div>
        <div class="col-md-1 mb-3">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">
                <i class="bx bx-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.card {
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.08);
}
#locationMap {
    z-index: 1;
}
.form-text {
    font-size: 0.75rem;
}
.select2-container--bootstrap-5 .select2-selection {
    min-height: 38px;
}
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
}
.select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
.avatar-xs {
    width: 32px;
    height: 32px;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>