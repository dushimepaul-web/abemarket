<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ma Boutique</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('ma-boutique/update') ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom de la boutique *</label>
                                    <input type="text" class="form-control" name="nom_boutique" value="<?= $vendeur['nom_boutique'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type de vendeur</label>
                                    <select class="form-control" name="type_vendeur">
                                        <option value="particulier" <?= $vendeur['type_vendeur'] == 'particulier' ? 'selected' : '' ?>>Particulier</option>
                                        <option value="entreprise" <?= $vendeur['type_vendeur'] == 'entreprise' ? 'selected' : '' ?>>Entreprise</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" name="telephone" value="<?= $vendeur['telephone'] ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">WhatsApp</label>
                                    <input type="text" class="form-control" name="whatsapp" value="<?= $vendeur['whatsapp'] ?>">
                                </div>
                                <div class="col-md-6 mb-3" id="entreprise_fields" style="<?= $vendeur['type_vendeur'] == 'entreprise' ? '' : 'display:none' ?>">
                                    <label class="form-label">Nom de l'entreprise</label>
                                    <input type="text" class="form-control" name="nom_entreprise" value="<?= $vendeur['nom_entreprise'] ?>">
                                </div>
                                <div class="col-md-3 mb-3" id="nif_field" style="<?= $vendeur['type_vendeur'] == 'entreprise' ? '' : 'display:none' ?>">
                                    <label class="form-label">NIF</label>
                                    <input type="text" class="form-control" name="numero_nif" value="<?= $vendeur['numero_nif'] ?>">
                                </div>
                                <div class="col-md-3 mb-3" id="rc_field" style="<?= $vendeur['type_vendeur'] == 'entreprise' ? '' : 'display:none' ?>">
                                    <label class="form-label">RC</label>
                                    <input type="text" class="form-control" name="numero_rc" value="<?= $vendeur['numero_rc'] ?>">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"><?= $vendeur['description'] ?></textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Province</label>
                                    <select class="form-control" name="id_province" id="id_province">
                                        <option value="">Sélectionner</option>
                                        <?php foreach ($provinces as $p): ?>
                                        <option value="<?= $p['id_province'] ?>" <?= $vendeur['id_province'] == $p['id_province'] ? 'selected' : '' ?>><?= $p['province_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Commune</label>
                                    <select class="form-control" name="id_commune" id="id_commune">
                                        <option value="">Sélectionner d'abord la province</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Quartier</label>
                                    <input type="text" class="form-control" name="id_quartier" value="<?= $vendeur['id_quartier'] ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Logo de la boutique</label>
                                    <input type="file" class="form-control" name="logo" accept="image/*">
                                    <?php if ($vendeur['logo_boutique']): ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url($vendeur['logo_boutique']) ?>" alt="Logo" style="max-width: 120px; max-height: 120px;" class="rounded border">
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Configuration de paiement</h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('ma-boutique/update_paiement') ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Méthode principale</label>
                                    <select class="form-control" name="methode_principale">
                                        <option value="mobile_money" <?= ($config_paiement['methode_principale'] ?? '') == 'mobile_money' ? 'selected' : '' ?>>Mobile Money</option>
                                        <option value="virement_bancaire" <?= ($config_paiement['methode_principale'] ?? '') == 'virement_bancaire' ? 'selected' : '' ?>>Virement bancaire</option>
                                    </select>
                                </div>
                            </div>
                            <div id="mobile_money_fields" style="<?= ($config_paiement['methode_principale'] ?? 'mobile_money') == 'mobile_money' ? '' : 'display:none' ?>">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Opérateur</label>
                                        <select class="form-control" name="operateur_mobile">
                                            <option value="">Sélectionner</option>
                                            <option value="lumicash" <?= ($config_paiement['operateur_mobile'] ?? '') == 'lumicash' ? 'selected' : '' ?>>Lumicash</option>
                                            <option value="ecomoney" <?= ($config_paiement['operateur_mobile'] ?? '') == 'ecomoney' ? 'selected' : '' ?>>EcoMoney</option>
                                            <option value="m_pesa" <?= ($config_paiement['operateur_mobile'] ?? '') == 'm_pesa' ? 'selected' : '' ?>>M-Pesa</option>
                                            <option value="airtel_money" <?= ($config_paiement['operateur_mobile'] ?? '') == 'airtel_money' ? 'selected' : '' ?>>Airtel Money</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Numéro Mobile Money</label>
                                        <input type="text" class="form-control" name="numero_mobile_money" value="<?= $config_paiement['numero_mobile_money'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nom de l'abonné</label>
                                        <input type="text" class="form-control" name="nom_abonne_mobile" value="<?= $config_paiement['nom_abonne_mobile'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div id="bank_fields" style="<?= ($config_paiement['methode_principale'] ?? '') == 'virement_bancaire' ? '' : 'display:none' ?>">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nom du titulaire</label>
                                        <input type="text" class="form-control" name="nom_titulaire" value="<?= $config_paiement['nom_titulaire'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Numéro de compte</label>
                                        <input type="text" class="form-control" name="numero_compte" value="<?= $config_paiement['numero_compte'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Banque</label>
                                        <input type="text" class="form-control" name="nom_banque" value="<?= $config_paiement['nom_banque'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
</div>

<script>
$(document).ready(function() {
    $('#id_province').change(function() {
        var id_province = $(this).val();
        if (id_province) {
            $.post('<?= base_url("ma-boutique/get_communes") ?>', {id_province: id_province}, function(data) {
                $('#id_commune').html(data);
                <?php if ($vendeur['id_commune']): ?>
                $('#id_commune').val('<?= $vendeur['id_commune'] ?>');
                <?php endif; ?>
            });
        }
    });

    <?php if ($vendeur['id_province']): ?>
    $('#id_province').trigger('change');
    <?php endif; ?>

    $('select[name="type_vendeur"]').change(function() {
        if ($(this).val() === 'entreprise') {
            $('#entreprise_fields, #nif_field, #rc_field').show();
        } else {
            $('#entreprise_fields, #nif_field, #rc_field').hide();
        }
    });

    $('select[name="methode_principale"]').change(function() {
        if ($(this).val() === 'mobile_money') {
            $('#mobile_money_fields').show();
            $('#bank_fields').hide();
        } else {
            $('#mobile_money_fields').hide();
            $('#bank_fields').show();
        }
    });
});
</script>

<?php if ($msg = $this->session->flashdata('success')): ?>
<script>
Swal.fire({ title: 'Succès!', text: '<?= $msg ?>', icon: 'success', confirmButtonText: 'OK' });
</script>
<?php endif; ?>
