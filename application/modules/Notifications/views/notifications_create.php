<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="me-2"></iconify-icon>
                            Créer une notification
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Destinataire <span class="text-danger">*</span></label>
                                <select name="id_utilisateur" class="form-select" required>
                                    <option value="all">📢 Tous les utilisateurs</option>
                                    <?php foreach ($utilisateurs as $u): ?>
                                        <option value="<?= $u->id_utilisateur ?>">
                                            <?= htmlspecialchars($u->prenom . ' ' . $u->nom) ?> (<?= htmlspecialchars($u->email) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Canal de notification <span class="text-danger">*</span></label>
                                    <select name="type_canal" class="form-select" required>
                                        <option value="in_app">📱 In-app (notification dans l'application)</option>
                                        <option value="email">📧 Email</option>
                                        <option value="sms">📱 SMS</option>
                                        <option value="push">🔔 Push notification</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                    <select name="categorie" class="form-select" required>
                                        <option value="commande">📦 Commande</option>
                                        <option value="paiement">💰 Paiement</option>
                                        <option value="livraison">🚚 Livraison</option>
                                        <option value="securite">🔒 Sécurité</option>
                                        <option value="systeme">⚙️ Système</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" name="titre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" rows="5" required></textarea>
                            </div>
                            <div class="alert alert-info">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                                <strong>Informations :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>📱 Les notifications in-app sont visibles dans l'application</li>
                                    <li>📧 Les emails sont envoyés à l'adresse email de l'utilisateur</li>
                                    <li>📱 Les SMS sont envoyés au numéro de téléphone (si disponible)</li>
                                </ul>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <iconify-icon icon="solar:send-bold-duotone" class="me-1"></iconify-icon>
                                    Envoyer la notification
                                </button>
                                <a href="<?= base_url('notifications') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>