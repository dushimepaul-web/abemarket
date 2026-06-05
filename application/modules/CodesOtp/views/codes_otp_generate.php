<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:key-bold-duotone" class="me-2"></iconify-icon>
                            Générer un code OTP
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Utilisateur <span class="text-danger">*</span></label>
                                <select name="id_utilisateur" class="form-select" required>
                                    <option value="">-- Sélectionner un utilisateur --</option>
                                    <?php foreach ($utilisateurs as $u): ?>
                                        <option value="<?= $u->id_utilisateur ?>">
                                            <?= htmlspecialchars($u->prenom . ' ' . $u->nom) ?> (<?= htmlspecialchars($u->email) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Type d'OTP <span class="text-danger">*</span></label>
                                <select name="type_otp" class="form-select" required>
                                    <option value="connexion">🔑 Connexion</option>
                                    <option value="verification_telephone">📱 Vérification téléphone</option>
                                    <option value="verification_email">📧 Vérification email</option>
                                    <option value="reinitialisation_mdp">🔐 Réinitialisation mot de passe</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Téléphone (optionnel)</label>
                                <input type="text" name="telephone" class="form-control" placeholder="+257 XX XX XX XX">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email (optionnel)</label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com">
                            </div>
                            
                            <div class="alert alert-info">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                                <strong>Informations :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>📱 Le code sera envoyé par SMS si le téléphone est renseigné</li>
                                    <li>📧 Le code sera envoyé par email si l'email est renseigné</li>
                                    <li>⏰ Le code expirera après 15 minutes</li>
                                    <li>🔢 Code à 6 chiffres généré aléatoirement</li>
                                </ul>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:key-bold-duotone" class="me-1"></iconify-icon>
                                    Générer le code
                                </button>
                                <a href="<?= base_url('codes-otp') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>