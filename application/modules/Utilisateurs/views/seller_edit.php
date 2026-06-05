<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Modifier le vendeur</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('Sellers') ?>">Vendeurs</a></li>
                            <li class="breadcrumb-item active">Modifier</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Modifier : <?= $seller['nom_boutique'] ?></h4>
                        <p class="text-muted mb-0">Modifiez les informations du vendeur ci-dessous</p>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('Sellers/edit/'.$seller['id_vendeur']) ?>" method="POST" enctype="multipart/form-data" id="sellerForm">
                            <!-- Informations de l'utilisateur (non modifiables) -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card bg-light border-info">
                                        <div class="card-header bg-info text-white">
                                            <h5 class="mb-0"><i class="bx bx-user-check me-2"></i>Informations de l'utilisateur</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong><i class="bx bx-user"></i> Nom complet :</strong><br>
                                                    <?= htmlspecialchars($seller['prenom'] . ' ' . $seller['nom']) ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong><i class="bx bx-envelope"></i> Email :</strong><br>
                                                    <?= htmlspecialchars($seller['email']) ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong><i class="bx bx-phone"></i> Téléphone :</strong><br>
                                                    <?= htmlspecialchars($seller['telephone'] ?? 'Non renseigné') ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong><i class="bx bx-calendar"></i> Inscrit le :</strong><br>
                                                    <?= date('d/m/Y H:i', strtotime($seller['user_date'])) ?>
                                                </div>
                                            </div>
                                            <?php if($seller['avatar_url']): ?>
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <strong><i class="bx bx-image"></i> Avatar :</strong><br>
                                                    <img src="<?= base_url($seller['avatar_url']) ?>" class="avatar-md rounded-circle mt-1">
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de la boutique -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="bx bx-store me-2"></i>Informations de la boutique</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nom de la boutique <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="nom_boutique" id="nom_boutique" value="<?= htmlspecialchars($seller['nom_boutique']) ?>" required>
                                                        <div class="form-text">Ce nom sera visible par les clients</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Slug</label>
                                                        <input type="text" class="form-control" id="slug_preview" value="<?= htmlspecialchars($seller['slug_boutique']) ?>" readonly disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Logo actuel</label>
                                                        <?php if($seller['logo_boutique']): ?>
                                                            <div class="mb-2">
                                                                <img src="<?= base_url($seller['logo_boutique']) ?>" class="avatar-lg rounded" style="width: 80px; height: 80px; object-fit: contain;">
                                                            </div>
                                                        <?php endif; ?>
                                                        <input type="file" class="form-control" name="logo_boutique" accept="image/*">
                                                        <div class="form-text">Logo recommandé: 200x200px</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Taux de commission (%)</label>
                                                        <input type="number" class="form-control" name="taux_commission" value="<?= $seller['taux_commission'] ?>" step="0.5" min="0" max="50">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description de la boutique</label>
                                                <textarea class="form-control" name="description" rows="3" placeholder="Décrivez votre boutique..."><?= htmlspecialchars($seller['description'] ?? '') ?></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label">Type de vendeur</label>
                                                    <select class="form-select" name="type_vendeur" id="type_vendeur">
                                                        <option value="particulier" <?= ($seller['type_vendeur'] ?? 'particulier') == 'particulier' ? 'selected' : '' ?>>Particulier</option>
                                                        <option value="entreprise" <?= ($seller['type_vendeur'] ?? '') == 'entreprise' ? 'selected' : '' ?>>Entreprise</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">WhatsApp</label>
                                                    <input type="tel" class="form-control" name="whatsapp" id="whatsapp" value="<?= htmlspecialchars($seller['whatsapp'] ?? '') ?>" placeholder="Numéro WhatsApp">
                                                </div>
                                            </div>
                                            
                                            <!-- Champs entreprise -->
                                            <div id="entreprise_fields" style="display: <?= ($seller['type_vendeur'] ?? '') == 'entreprise' ? 'block' : 'none' ?>;">
                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <h6 class="mb-3">Informations de l'entreprise</h6>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Nom de l'entreprise</label>
                                                        <input type="text" class="form-control" name="nom_entreprise" value="<?= htmlspecialchars($seller['nom_entreprise'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Numéro NIF</label>
                                                        <input type="text" class="form-control" name="numero_nif" value="<?= htmlspecialchars($seller['numero_nif'] ?? '') ?>">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">Numéro RC</label>
                                                        <input type="text" class="form-control" name="numero_rc" value="<?= htmlspecialchars($seller['numero_rc'] ?? '') ?>">
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
                                                                <option value="<?= $province['id_province'] ?>" <?= ($seller['id_province'] ?? '') == $province['id_province'] ? 'selected' : '' ?>>
                                                                    <?= $province['province_name'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Commune</label>
                                                    <select class="form-select" name="id_commune" id="id_commune">
                                                        <option value="">Sélectionner d'abord une province</option>
                                                        <?php if(isset($communes) && !empty($communes)): ?>
                                                            <?php foreach($communes as $commune): ?>
                                                                <option value="<?= $commune['id_commune'] ?>" <?= ($seller['id_commune'] ?? '') == $commune['id_commune'] ? 'selected' : '' ?>>
                                                                    <?= $commune['commune_name'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Quartier</label>
                                                    <select class="form-select" name="id_quartier" id="id_quartier">
                                                        <option value="">Sélectionner d'abord une commune</option>
                                                        <?php if(isset($quartiers) && !empty($quartiers)): ?>
                                                            <?php foreach($quartiers as $quartier): ?>
                                                                <option value="<?= $quartier['id_quartier'] ?>" <?= ($seller['id_quartier'] ?? '') == $quartier['id_quartier'] ? 'selected' : '' ?>>
                                                                    <?= $quartier['quartier_name'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Latitude</label>
                                                    <input type="text" class="form-control" name="latitude" id="latitude" value="<?= $seller['latitude'] ?? '' ?>" placeholder="Ex: -3.3822">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Longitude</label>
                                                    <input type="text" class="form-control" name="longitude" id="longitude" value="<?= $seller['longitude'] ?? '' ?>" placeholder="Ex: 29.3611">
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
                                            <?php if(isset($payment_config) && !empty($payment_config)): ?>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Méthode de paiement principale</label>
                                                        <select class="form-select" name="methode_paiement" id="methode_paiement">
                                                            <option value="mobile_money" <?= ($payment_config['methode_principale'] ?? '') == 'mobile_money' ? 'selected' : '' ?>>Mobile Money</option>
                                                            <option value="virement_bancaire" <?= ($payment_config['methode_principale'] ?? '') == 'virement_bancaire' ? 'selected' : '' ?>>Virement bancaire</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3" id="operateur_field" style="display: <?= ($payment_config['methode_principale'] ?? 'mobile_money') == 'mobile_money' ? 'block' : 'none' ?>;">
                                                        <label class="form-label">Opérateur Mobile</label>
                                                        <select class="form-select" name="operateur_mobile">
                                                            <option value="">Sélectionner</option>
                                                            <option value="BANCOBU" <?= ($payment_config['operateur_mobile'] ?? '') == 'BANCOBU' ? 'selected' : '' ?>>Bancobu</option>
                                                            <option value="LUMICASH" <?= ($payment_config['operateur_mobile'] ?? '') == 'LUMICASH' ? 'selected' : '' ?>>Lumicash</option>
                                                            <option value="ECOCASH" <?= ($payment_config['operateur_mobile'] ?? '') == 'ECOCASH' ? 'selected' : '' ?>>EcoCash</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row" id="mobile_money_fields" style="display: <?= ($payment_config['methode_principale'] ?? 'mobile_money') == 'mobile_money' ? 'flex' : 'none' ?>;">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Numéro Mobile Money</label>
                                                        <input type="tel" class="form-control" name="numero_mobile_money" value="<?= $payment_config['numero_mobile_money'] ?? '' ?>" placeholder="Ex: +257 XX XXX XXX">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nom du titulaire</label>
                                                        <input type="text" class="form-control" name="nom_abonne_mobile" value="<?= $payment_config['nom_abonne_mobile'] ?? '' ?>">
                                                    </div>
                                                </div>
                                                <div class="row" id="bank_fields" style="display: <?= ($payment_config['methode_principale'] ?? '') == 'virement_bancaire' ? 'flex' : 'none' ?>;">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nom de la banque</label>
                                                        <input type="text" class="form-control" name="nom_banque" value="<?= $payment_config['nom_banque'] ?? '' ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Numéro de compte</label>
                                                        <input type="text" class="form-control" name="numero_compte" value="<?= $payment_config['numero_compte'] ?? '' ?>">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Nom du titulaire</label>
                                                        <input type="text" class="form-control" name="nom_titulaire" value="<?= $payment_config['nom_titulaire'] ?? '' ?>">
                                                    </div>
                                                </div>
                                            <?php else: ?>
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
                                            <?php endif; ?>
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
                                            <?php if(isset($documents) && !empty($documents)): ?>
                                                <div class="table-responsive mb-3">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr><th>Type</th><th>Fichier</th><th>Statut</th><th>Date</th><th>Action</th></tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($documents as $doc): ?>
                                                                <tr>
                                                                    <td><?= str_replace('_', ' ', ucfirst($doc['type_document'])) ?></td>
                                                                    <td><a href="<?= base_url($doc['fichier_document']) ?>" target="_blank">Voir</a></td>
                                                                    <td>
                                                                        <?php if($doc['statut_verification'] == 'verifie'): ?>
                                                                            <span class="badge bg-success">Vérifié</span>
                                                                        <?php elseif($doc['statut_verification'] == 'refuse'): ?>
                                                                            <span class="badge bg-danger">Refusé</span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-warning">En attente</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?= date('d/m/Y', strtotime($doc['date_upload'])) ?></td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteDocument(<?= $doc['id_document'] ?>)">
                                                                            <i class="bx bx-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
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

                            <!-- Paramètres du compte -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0"><i class="bx bx-slider me-2"></i>Paramètres du compte</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Nouveau mot de passe</label>
                                                    <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Laisser vide pour ne pas changer">
                                                    <div class="form-text">Minimum 6 caractères</div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Confirmer mot de passe</label>
                                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirmez le nouveau mot de passe">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Délai paiement (jours)</label>
                                                    <input type="number" class="form-control" name="delai_paiement_jours" value="<?= $seller['delai_paiement_jours'] ?? 7 ?>" min="0" max="60">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Statut vendeur</label>
                                                    <select class="form-select" name="statut">
                                                        <option value="actif" <?= ($seller['statut'] ?? '') == 'actif' ? 'selected' : '' ?>>Actif</option>
                                                        <option value="en_attente" <?= ($seller['statut'] ?? '') == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                                        <option value="suspendu" <?= ($seller['statut'] ?? '') == 'suspendu' ? 'selected' : '' ?>>Suspendu</option>
                                                        <option value="banni" <?= ($seller['statut'] ?? '') == 'banni' ? 'selected' : '' ?>>Banni</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Approbation boutique</label>
                                                    <select class="form-select" name="est_approuve">
                                                        <option value="0" <?= ($seller['est_approuve'] ?? 0) == 0 ? 'selected' : '' ?>>En attente</option>
                                                        <option value="1" <?= ($seller['est_approuve'] ?? 0) == 1 ? 'selected' : '' ?>>Approuvé</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Date d'approbation</label>
                                                    <input type="text" class="form-control" value="<?= !empty($seller['date_approbation']) ? date('d/m/Y H:i', strtotime($seller['date_approbation'])) : 'Non approuvé' ?>" disabled readonly>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">Commission (%)</label>
                                                    <input type="number" class="form-control" name="taux_commission" value="<?= $seller['taux_commission'] ?? 10 ?>" step="0.5" min="0" max="50">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bx bx-save me-1"></i>Enregistrer les modifications
                                    </button>
                                    <a href="<?= base_url('Sellers/view/'.$seller['id_vendeur']) ?>" class="btn btn-secondary btn-lg">
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
let map, marker;

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
                const selected = (commune.id_commune == <?= $seller['id_commune'] ?? 0 ?>) ? 'selected' : '';
                communeSelect.innerHTML += `<option value="${commune.id_commune}" ${selected}>${commune.commune_name}</option>`;
            });
            if (communeSelect.value) {
                communeSelect.dispatchEvent(new Event('change'));
            }
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
                const selected = (quartier.id_quartier == <?= $seller['id_quartier'] ?? 0 ?>) ? 'selected' : '';
                quartierSelect.innerHTML += `<option value="${quartier.id_quartier}" ${selected}>${quartier.quartier_name}</option>`;
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
                text: 'Impossible d\'obtenir votre position.'
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Non supporté',
            text: 'La géolocalisation n\'est pas supportée.'
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

// Supprimer un document
function deleteDocument(docId) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Ce document sera supprimé définitivement !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Sellers/delete_document") ?>/' + docId;
        }
    });
}

// Validation du formulaire
document.getElementById('sellerForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== '' && newPassword !== confirmPassword) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Les mots de passe ne correspondent pas.'
        });
        return false;
    }
    
    if (newPassword !== '' && newPassword.length < 6) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Le mot de passe doit contenir au moins 6 caractères.'
        });
        return false;
    }
});

// Initialiser la carte si des coordonnées existent
<?php if(!empty($seller['latitude']) && !empty($seller['longitude'])): ?>
    document.addEventListener('DOMContentLoaded', function() {
        showMap(<?= $seller['latitude'] ?>, <?= $seller['longitude'] ?>);
    });
<?php endif; ?>

// Initialiser les select dépendants
document.addEventListener('DOMContentLoaded', function() {
    <?php if(!empty($seller['id_province'])): ?>
        document.getElementById('id_province').dispatchEvent(new Event('change'));
    <?php endif; ?>
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
.avatar-md {
    width: 80px;
    height: 80px;
    object-fit: cover;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>