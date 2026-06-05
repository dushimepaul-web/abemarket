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
                            <iconify-icon icon="solar:add-circle-bold-duotone" class="me-2"></iconify-icon>
                            Générer les paiements vendeurs
                        </h4>
                        <a href="<?= base_url('paiements-vendeurs') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" name="date_debut" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de fin <span class="text-danger">*</span></label>
                                    <input type="date" name="date_fin" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vendeur (optionnel)</label>
                                    <select name="id_vendeur" class="form-select">
                                        <option value="">Tous les vendeurs</option>
                                        <?php foreach ($vendeurs as $v): ?>
                                            <option value="<?= $v->id_vendeur ?>"><?= htmlspecialchars($v->nom_boutique) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Laissez vide pour générer pour tous les vendeurs</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <iconify-icon icon="solar:add-circle-bold-duotone"></iconify-icon> Générer les paiements
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-3">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2 fs-18"></iconify-icon>
                                <strong>Informations :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>📊 Les paiements sont calculés à partir des commandes livrées</li>
                                    <li>💰 Le montant net = Revenus - Commissions - Remboursements</li>
                                    <li>📅 Seules les commandes entre les dates sélectionnées sont prises en compte</li>
                                    <li>🔁 Les doublons sont évités : un paiement ne sera pas généré deux fois</li>
                                </ul>
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