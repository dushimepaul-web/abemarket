<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<!-- ==================================================== -->
<!-- Start right Content here -->
<!-- ==================================================== -->
<div class="page-content">

    <!-- Start Container Fluid -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1">
                        <h4 class="card-title flex-grow-1">
                            <?= isset($adresse) ? 'Modifier l\'adresse' : 'Ajouter une adresse' ?>
                        </h4>
                        <a href="<?= base_url('Adresse') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="addressForm" method="post" action="<?= isset($adresse) ? base_url('Adresse/modifier/' . $adresse->id_adresse) : base_url('Adresse/ajouter') ?>">
                            <input type="hidden" name="id_adresse" id="id_adresse" value="<?= isset($adresse) ? $adresse->id_adresse : '' ?>">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type d'adresse <span class="text-danger">*</span></label>
                                    <select name="type_adresse" id="type_adresse" class="form-select" required>
                                        <option value="domicile" <?= isset($adresse) && $adresse->type_adresse == 'domicile' ? 'selected' : '' ?>>🏠 Domicile</option>
                                        <option value="travail" <?= isset($adresse) && $adresse->type_adresse == 'travail' ? 'selected' : '' ?>>💼 Travail</option>
                                        <option value="autre" <?= isset($adresse) && $adresse->type_adresse == 'autre' ? 'selected' : '' ?>>📍 Autre</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" name="nom_complet" id="nom_complet" class="form-control" required value="<?= isset($adresse) ? htmlspecialchars($adresse->nom_complet) : '' ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" name="telephone" id="telephone" class="form-control" required value="<?= isset($adresse) ? htmlspecialchars($adresse->telephone) : '' ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Province <span class="text-danger">*</span></label>
                                    <select name="id_province" id="id_province" class="form-select" required>
                                        <option value="">Sélectionner une province</option>
                                        <?php if (!empty($provinces)): ?>
                                            <?php foreach ($provinces as $province): ?>
                                                <option value="<?= $province->id_province ?>" <?= isset($adresse) && $adresse->id_province == $province->id_province ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($province->province_name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Commune <span class="text-danger">*</span></label>
                                    <select name="id_commune" id="id_commune" class="form-select" required <?= isset($adresse) && $adresse->id_commune ? '' : 'disabled' ?>>
                                        <option value="">Sélectionner d'abord une province</option>
                                        <?php if (isset($adresse) && !empty($communes)): ?>
                                            <?php foreach ($communes as $commune): ?>
                                                <option value="<?= $commune->id_commune ?>" <?= $adresse->id_commune == $commune->id_commune ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($commune->commune_name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Quartier</label>
                                    <select name="id_quartier" id="id_quartier" class="form-select" <?= isset($adresse) && $adresse->id_quartier ? '' : 'disabled' ?>>
                                        <option value="">Sélectionner un quartier</option>
                                        <?php if (isset($adresse) && !empty($quartiers)): ?>
                                            <?php foreach ($quartiers as $quartier): ?>
                                                <option value="<?= $quartier->id_quartier ?>" <?= $adresse->id_quartier == $quartier->id_quartier ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($quartier->quartier_name) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Adresse (rue, avenue, numéro) <span class="text-danger">*</span></label>
                                <input type="text" name="adresse_ligne" id="adresse_ligne" class="form-control" required value="<?= isset($adresse) ? htmlspecialchars($adresse->adresse_ligne) : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Point de repère</label>
                                <input type="text" name="point_repere" id="point_repere" class="form-control" placeholder="Ex: À côté de l'église, près du marché..." value="<?= isset($adresse) ? htmlspecialchars($adresse->point_repere) : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Instructions de livraison</label>
                                <textarea name="instructions_livraison" id="instructions_livraison" class="form-control" rows="2" placeholder="Informations supplémentaires pour le livreur..."><?= isset($adresse) ? htmlspecialchars($adresse->instructions_livraison) : '' ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Cliquez sur la carte" readonly value="<?= isset($adresse) ? $adresse->latitude : '' ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Cliquez sur la carte" readonly value="<?= isset($adresse) ? $adresse->longitude : '' ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div id="map" style="height: 400px; border-radius: 8px;"></div>
                                <small class="text-muted">Cliquez sur la carte pour définir votre position</small>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" name="est_par_defaut" id="est_par_defaut" class="form-check-input" value="1" <?= isset($adresse) && $adresse->est_par_defaut ? 'checked' : '' ?>>
                                <label class="form-check-label">Définir comme adresse par défaut</label>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($adresse) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('Adresse') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Container Fluid -->

</div>
<!-- ==================================================== -->
<!-- End Page Content -->
<!-- ==================================================== -->

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let map, marker;
    const defaultLat = <?= isset($adresse) && $adresse->latitude ? $adresse->latitude : '-3.382' ?>;
    const defaultLng = <?= isset($adresse) && $adresse->longitude ? $adresse->longitude : '29.361' ?>;

    // Initialiser la carte Leaflet
    function initMap(lat = defaultLat, lng = defaultLng) {
        if (map) {
            map.remove();
        }

        map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        const customIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        marker = L.marker([parseFloat(lat), parseFloat(lng)], { draggable: true, icon: customIcon }).addTo(map);

        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            document.getElementById('latitude').value = pos.lat.toFixed(8);
            document.getElementById('longitude').value = pos.lng.toFixed(8);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat.toFixed(8);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(8);
        });
    }

    // Charger les communes par province
    $('#id_province').on('change', function() {
        const provinceId = $(this).val();
        if (provinceId) {
            $.get('<?= base_url("Adresse/get_communes/") ?>' + provinceId, function(data) {
                let options = '<option value="">Sélectionner une commune</option>';
                if (data.length > 0) {
                    data.forEach(function(commune) {
                        options += `<option value="${commune.id_commune}">${commune.commune_name}</option>`;
                    });
                } else {
                    options = '<option value="">Aucune commune disponible</option>';
                }
                $('#id_commune').html(options);
                $('#id_commune').prop('disabled', false);
                $('#id_quartier').html('<option value="">Sélectionner d\'abord une commune</option>');
                $('#id_quartier').prop('disabled', true);
            }, 'json').fail(function() {
                $('#id_commune').html('<option value="">Erreur de chargement</option>');
            });
        } else {
            $('#id_commune').html('<option value="">Sélectionner d\'abord une province</option>');
            $('#id_commune').prop('disabled', true);
            $('#id_quartier').html('<option value="">Sélectionner d\'abord une commune</option>');
            $('#id_quartier').prop('disabled', true);
        }
    });

    // Charger les quartiers par commune
    $('#id_commune').on('change', function() {
        const communeId = $(this).val();
        if (communeId) {
            $.get('<?= base_url("Adresse/get_quartiers/") ?>' + communeId, function(data) {
                let options = '<option value="">Sélectionner un quartier</option>';
                if (data.length > 0) {
                    data.forEach(function(quartier) {
                        options += `<option value="${quartier.id_quartier}">${quartier.quartier_name}</option>`;
                    });
                }
                $('#id_quartier').html(options);
                $('#id_quartier').prop('disabled', false);
            }, 'json').fail(function() {
                $('#id_quartier').html('<option value="">Erreur de chargement</option>');
            });
        } else {
            $('#id_quartier').html('<option value="">Sélectionner d\'abord une commune</option>');
            $('#id_quartier').prop('disabled', true);
        }
    });

    // Soumission du formulaire
    $('#addressForm').on('submit', function(e) {
        e.preventDefault();

        // Validation
        if (!$('#type_adresse').val()) {
            Swal.fire('Erreur', 'Le type d\'adresse est requis', 'error');
            return;
        }
        if (!$('#nom_complet').val()) {
            Swal.fire('Erreur', 'Le nom complet est requis', 'error');
            return;
        }
        if (!$('#telephone').val()) {
            Swal.fire('Erreur', 'Le téléphone est requis', 'error');
            return;
        }
        if (!$('#id_province').val()) {
            Swal.fire('Erreur', 'La province est requise', 'error');
            return;
        }
        if (!$('#id_commune').val()) {
            Swal.fire('Erreur', 'La commune est requise', 'error');
            return;
        }
        if (!$('#adresse_ligne').val()) {
            Swal.fire('Erreur', 'L\'adresse est requise', 'error');
            return;
        }

        const formData = $(this).serialize();
        const submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');

        $.post($(this).attr('action'), formData, function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '<?= base_url("Adresse") ?>';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: response.message || response.errors || 'Une erreur est survenue'
                });
                submitBtn.prop('disabled', false).html('<?= isset($adresse) ? "Mettre à jour" : "Enregistrer" ?>');
            }
        }, 'json').fail(function(xhr) {
            let errorMsg = 'Erreur de connexion au serveur';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            Swal.fire('Erreur', errorMsg, 'error');
            submitBtn.prop('disabled', false).html('<?= isset($adresse) ? "Mettre à jour" : "Enregistrer" ?>');
        });
    });

    // Initialiser la carte
    $(document).ready(function() {
        if (document.getElementById('map')) {
            initMap();
        }
    });
</script>

<style>
    #map {
        z-index: 1;
    }
    .leaflet-container {
        z-index: 1;
    }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>