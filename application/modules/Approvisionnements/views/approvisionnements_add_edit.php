<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Nouvel approvisionnement</h4>
                        <a href="<?= base_url('approvisionnements') ?>" class="btn btn-sm btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>Retour
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Produit <span class="text-danger">*</span></label>
                                    <select name="id_produit" id="id_produit" class="form-select" required>
                                        <option value="">-- Sélectionner un produit --</option>
                                        <?php foreach ($produits as $p): ?>
                                            <option value="<?= $p->id_produit ?>"><?= htmlspecialchars($p->nom_produit) ?> (<?= htmlspecialchars($p->sku) ?>) - Stock: <?= $p->quantite_actuelle ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Variante</label>
                                    <select name="id_variante" id="id_variante" class="form-select">
                                        <option value="">-- Produit standard (sans variante) --</option>
                                    </select>
                                    <small class="text-muted">Sélectionnez une variante si le produit en a</small>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stock actuel</label>
                                    <input type="text" id="stock_actuel" class="form-control" readonly value="0">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Quantité à ajouter <span class="text-danger">*</span></label>
                                    <input type="number" name="quantite" id="quantite" class="form-control" required min="1" value="1">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stock après</label>
                                    <input type="text" id="stock_apres" class="form-control" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Prix d'achat unitaire (FBu) <span class="text-danger">*</span></label>
                                    <input type="number" name="prix_achat_unitaire" id="prix_achat_unitaire" class="form-control" required min="1" step="1">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Coût total</label>
                                    <input type="text" id="cout_total" class="form-control" readonly>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date d'approvisionnement <span class="text-danger">*</span></label>
                                    <input type="date" name="date_appro" class="form-control" required value="<?= $date_appro ?>">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fournisseur</label>
                                    <input type="text" name="fournisseur" class="form-control" placeholder="Nom du fournisseur">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Référence bon de commande</label>
                                    <input type="text" name="reference_bon" class="form-control" placeholder="Référence">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="Informations supplémentaires..."></textarea>
                            </div>
                            
                            <div class="alert alert-info">
                                <iconify-icon icon="solar:info-circle-bold-duotone" class="me-2"></iconify-icon>
                                <strong>Ce que va faire cette opération :</strong>
                                <ul class="mb-0 mt-2">
                                    <li>📝 Enregistrer l'historique dans <strong>approvisionnements</strong></li>
                                    <li>➕ Ajouter <strong id="info_quantite">X</strong> unités au stock actuel</li>
                                    <li>📊 Mettre à jour <strong>produits.quantite_actuelle</strong> ou <strong>variantes_produit.quantite_actuelle</strong></li>
                                </ul>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i>Enregistrer l'approvisionnement
                                </button>
                                <a href="<?= base_url('approvisionnements') ?>" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Récupérer les variantes
$('#id_produit').on('change', function() {
    const id_produit = $(this).val();
    
    if (id_produit) {
        $.ajax({
            url: '<?= base_url("approvisionnements/get_variantes") ?>',
            type: 'POST',
            data: {id_produit: id_produit},
            dataType: 'json',
            success: function(variantes) {
                let options = '<option value="">-- Produit standard (sans variante) --</option>';
                variantes.forEach(function(v) {
                    let attributs = '';
                    if (v.attributs) {
                        attributs = Object.values(v.attributs).join(' - ');
                    }
                    options += `<option value="${v.id_variante}" data-stock="${v.quantite_actuelle}">${v.sku} - ${attributs} (Stock: ${v.quantite_actuelle})</option>`;
                });
                $('#id_variante').html(options);
                $('#id_variante').trigger('change');
            }
        });
    }
});

// Récupérer le stock actuel
function getStockActuel() {
    const id_produit = $('#id_produit').val();
    const id_variante = $('#id_variante').val();
    
    if (id_produit) {
        $.ajax({
            url: '<?= base_url("approvisionnements/get_stock_actuel") ?>',
            type: 'POST',
            data: {id_produit: id_produit, id_variante: id_variante},
            dataType: 'json',
            success: function(data) {
                $('#stock_actuel').val(data.stock);
                calculerStockApres();
            }
        });
    }
}

// Calculer le stock après
function calculerStockApres() {
    const stockActuel = parseInt($('#stock_actuel').val()) || 0;
    const quantite = parseInt($('#quantite').val()) || 0;
    $('#stock_apres').val(stockActuel + quantite);
    $('#info_quantite').text(quantite);
}

// Calculer le coût total
function calculerCoutTotal() {
    const quantite = parseInt($('#quantite').val()) || 0;
    const prix = parseInt($('#prix_achat_unitaire').val()) || 0;
    const total = quantite * prix;
    $('#cout_total').val(total.toLocaleString('fr-FR') + ' FBu');
}

// Événements
$('#id_produit').on('change', getStockActuel);
$('#id_variante').on('change', getStockActuel);
$('#quantite').on('input', function() {
    calculerStockApres();
    calculerCoutTotal();
});
$('#prix_achat_unitaire').on('input', calculerCoutTotal);

// Initialisation
calculerStockApres();

// Validation du formulaire
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