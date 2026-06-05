<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:pen-2-broken" class="me-2"></iconify-icon>
                            Modifier la commande #<?= $commande->numero_commande ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('Commande/detail/' . $commande->id_commande) ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Statut de la commande</label>
                                    <select name="statut_commande" class="form-select">
                                        <option value="en_attente" <?= $commande->statut_commande == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                        <option value="confirme" <?= $commande->statut_commande == 'confirme' ? 'selected' : '' ?>>Confirmée</option>
                                        <option value="en_preparation" <?= $commande->statut_commande == 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                                        <option value="expedie" <?= $commande->statut_commande == 'expedie' ? 'selected' : '' ?>>Expédiée</option>
                                        <option value="livre" <?= $commande->statut_commande == 'livre' ? 'selected' : '' ?>>Livrée</option>
                                        <option value="annule" <?= $commande->statut_commande == 'annule' ? 'selected' : '' ?>>Annulée</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Statut du paiement</label>
                                    <select name="statut_paiement" class="form-select">
                                        <option value="en_attente" <?= $commande->statut_paiement == 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                        <option value="en_cours" <?= $commande->statut_paiement == 'en_cours' ? 'selected' : '' ?>>En cours</option>
                                        <option value="paye" <?= $commande->statut_paiement == 'paye' ? 'selected' : '' ?>>Payé</option>
                                        <option value="echoue" <?= $commande->statut_paiement == 'echoue' ? 'selected' : '' ?>>Échoué</option>
                                        <option value="rembourse" <?= $commande->statut_paiement == 'rembourse' ? 'selected' : '' ?>>Remboursé</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Numéro de suivi</label>
                                    <input type="text" name="numero_suivi" class="form-control" value="<?= htmlspecialchars($commande->numero_suivi ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date d'expédition</label>
                                    <input type="datetime-local" name="date_expedition" class="form-control" 
                                           value="<?= $commande->date_expedition ? date('Y-m-d\TH:i', strtotime($commande->date_expedition)) : '' ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Note interne</label>
                                <textarea name="note_interne" class="form-control" rows="3" placeholder="Note interne pour l'équipe..."><?= htmlspecialchars($commande->note_interne ?? '') ?></textarea>
                            </div>
                            
                            <div class="alert alert-info">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2 fs-18"></iconify-icon>
                                <strong>Informations :</strong> Les modifications seront enregistrées dans l'historique de la commande.
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>Enregistrer les modifications
                                </button>
                                <a href="<?= base_url('Commande/detail/' . $commande->id_commande) ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>