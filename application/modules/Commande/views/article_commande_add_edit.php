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
                            <?= isset($article) ? 'Modifier l\'article #' . $article->id_article : 'Nouvel article commandé' ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('ArticleCommande') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="articleForm" method="post" action="<?= isset($article) ? base_url('ArticleCommande/update/' . $article->id_article) : base_url('ArticleCommande/save') ?>">
                            
                            <!-- Informations commande -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:receipt-bold-duotone" class="me-2"></iconify-icon>
                                        Informations commande
                                    </h5>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">N° Commande <span class="text-danger">*</span></label>
                                    <select name="id_commande" id="id_commande" class="form-select" <?= isset($article) ? 'disabled' : '' ?> required>
                                        <option value="">Sélectionner une commande</option>
                                        <?php if (!empty($commandes)): ?>
                                            <?php foreach ($commandes as $c): ?>
                                                <option value="<?= $c->id_commande ?>" 
                                                    <?= isset($article) && $article->id_commande == $c->id_commande ? 'selected' : '' ?>
                                                    data-client="<?= htmlspecialchars(($c->prenom ?? '') . ' ' . ($c->nom ?? '')) ?>"
                                                    data-montant="<?= $c->montant_total ?>">
                                                    <?= $c->numero_commande ?> - <?= htmlspecialchars(($c->prenom ?? '') . ' ' . ($c->nom ?? '')) ?> - <?= number_format($c->montant_total, 0, ',', ' ') ?> FBu
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (isset($article)): ?>
                                        <input type="hidden" name="id_commande" value="<?= $article->id_commande ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Client</label>
                                    <input type="text" id="client_info" class="form-control bg-light" readonly placeholder="Sélectionner une commande">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Montant commande</label>
                                    <input type="text" id="commande_montant" class="form-control bg-light" readonly placeholder="Sélectionner une commande">
                                </div>
                            </div>
                            
                            <!-- Informations produit -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:box-bold-duotone" class="me-2"></iconify-icon>
                                        Informations produit
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Produit <span class="text-danger">*</span></label>
                                    <select name="id_produit" id="id_produit" class="form-select" required>
                                        <option value="">Sélectionner un produit</option>
                                        <?php if (!empty($produits)): ?>
                                            <?php foreach ($produits as $p): ?>
                                                <option value="<?= $p->id_produit ?>" 
                                                    <?= isset($article) && $article->id_produit == $p->id_produit ? 'selected' : '' ?>
                                                    data-prix="<?= $p->prix_base ?>"
                                                    data-nom="<?= htmlspecialchars($p->nom_produit) ?>"
                                                    data-sku="<?= $p->sku ?>">
                                                    <?= htmlspecialchars($p->nom_produit) ?> (<?= $p->sku ?>) - <?= number_format($p->prix_base, 0, ',', ' ') ?> FBu
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Variante</label>
                                    <select name="id_variante" id="id_variante" class="form-select" <?= isset($article) && !$article->id_variante ? 'disabled' : '' ?>>
                                        <option value="">Aucune variante</option>
                                        <?php if (isset($variantes) && !empty($variantes)): ?>
                                            <?php foreach ($variantes as $v): ?>
                                                <?php $attrs = json_decode($v->attributs_variante, true); ?>
                                                <option value="<?= $v->id_variante ?>" 
                                                    <?= isset($article) && $article->id_variante == $v->id_variante ? 'selected' : '' ?>
                                                    data-prix="<?= $v->prix ?>"
                                                    data-sku="<?= $v->sku ?>">
                                                    <?= isset($attrs['taille']) ? 'Taille: ' . $attrs['taille'] : '' ?>
                                                    <?= isset($attrs['couleur']) ? ($attrs['taille'] ? ' | ' : '') . 'Couleur: ' . $attrs['couleur'] : '' ?>
                                                    (<?= $v->sku ?>) - <?= number_format($v->prix ?? 0, 0, ',', ' ') ?> FBu
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Informations vendeur -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:shop-bold-duotone" class="me-2"></iconify-icon>
                                        Informations vendeur
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vendeur <span class="text-danger">*</span></label>
                                    <select name="id_vendeur" id="id_vendeur" class="form-select" required>
                                        <option value="">Sélectionner un vendeur</option>
                                        <?php if (!empty($vendeurs)): ?>
                                            <?php foreach ($vendeurs as $v): ?>
                                                <option value="<?= $v->id_vendeur ?>" 
                                                    <?= isset($article) && $article->id_vendeur == $v->id_vendeur ? 'selected' : '' ?>
                                                    data-taux="<?= $v->taux_commission ?>">
                                                    <?= htmlspecialchars($v->nom_boutique) ?> (Taux: <?= $v->taux_commission ?>%)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Taux commission (%)</label>
                                    <input type="number" step="0.01" name="taux_commission" id="taux_commission" class="form-control bg-light" readonly>
                                </div>
                            </div>
                            
                            <!-- Prix et quantités -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:chart-2-bold-duotone" class="me-2"></iconify-icon>
                                        Prix et quantités
                                    </h5>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Prix unitaire (FBu) <span class="text-danger">*</span></label>
                                    <input type="number" step="1" name="prix_unitaire" id="prix_unitaire" class="form-control" required value="<?= isset($article) ? $article->prix_unitaire : '' ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <input type="number" name="quantite" id="quantite" class="form-control" required min="1" value="<?= isset($article) ? $article->quantite : '1' ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Prix total (FBu)</label>
                                    <input type="text" id="prix_total" class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Montant commission (FBu)</label>
                                    <input type="text" id="montant_commission" class="form-control bg-light" readonly>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Revenus vendeur (FBu)</label>
                                    <input type="text" id="revenus_vendeur" class="form-control bg-light" readonly>
                                </div>
                            </div>
                            
                            <!-- Statut -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:status-bold-duotone" class="me-2"></iconify-icon>
                                        Statut
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Statut article <span class="text-danger">*</span></label>
                                    <select name="statut_article" id="statut_article" class="form-select" required>
                                        <?php foreach ($statuts as $key => $s): ?>
                                            <option value="<?= $key ?>" <?= isset($article) && $article->statut_article == $key ? 'selected' : '' ?>>
                                                <?= $s['label'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-check-label mt-4">
                                        <input type="checkbox" name="est_retourne" id="est_retourne" class="form-check-input" value="1" <?= isset($article) && $article->est_retourne ? 'checked' : '' ?>>
                                        Article retourné
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Retour -->
                            <div class="row mb-4" id="retourSection" style="display: none;">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:refresh-bold-duotone" class="me-2"></iconify-icon>
                                        Informations retour
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Motif retour</label>
                                    <select name="motif_retour" id="motif_retour" class="form-select">
                                        <option value="">Sélectionner un motif</option>
                                        <?php foreach ($motifs_retour as $key => $m): ?>
                                            <option value="<?= $key ?>" <?= isset($article) && $article->motif_retour == $key ? 'selected' : '' ?>>
                                                <?= $m['label'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date demande retour</label>
                                    <input type="datetime-local" name="date_demande_retour" id="date_demande_retour" class="form-control" value="<?= isset($article) && $article->date_demande_retour ? date('Y-m-d\TH:i', strtotime($article->date_demande_retour)) : '' ?>">
                                </div>
                            </div>
                            
                            <!-- Avis -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:chat-square-like-bold-duotone" class="me-2"></iconify-icon>
                                        Avis
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-check-label">
                                        <input type="checkbox" name="avis_laisse" id="avis_laisse" class="form-check-input" value="1" <?= isset($article) && $article->avis_laisse ? 'checked' : '' ?>>
                                        Avis laissé par le client
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($article) ? 'Mettre à jour' : 'Enregistrer' ?>
                                </button>
                                <a href="<?= base_url('ArticleCommande') ?>" class="btn btn-secondary">Annuler</a>
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
// Calcul automatique des montants
function calculerMontants() {
    let prix = parseFloat($('#prix_unitaire').val()) || 0;
    let qte = parseInt($('#quantite').val()) || 0;
    let taux = parseFloat($('#taux_commission').val()) || 0;
    
    let total = prix * qte;
    let commission = (total * taux) / 100;
    let revenus = total - commission;
    
    $('#prix_total').val(total.toLocaleString('fr-FR') + ' FBu');
    $('#montant_commission').val(commission.toLocaleString('fr-FR') + ' FBu');
    $('#revenus_vendeur').val(revenus.toLocaleString('fr-FR') + ' FBu');
}

// Charger les informations de la commande
$('#id_commande').on('change', function() {
    const selected = $(this).find('option:selected');
    const client = selected.data('client');
    const montant = selected.data('montant');
    
    $('#client_info').val(client || '');
    $('#commande_montant').val(montant ? montant.toLocaleString('fr-FR') + ' FBu' : '');
});

// Charger les variantes du produit
$('#id_produit').on('change', function() {
    const produitId = $(this).val();
    const selected = $(this).find('option:selected');
    const prixBase = selected.data('prix');
    
    if (prixBase) {
        $('#prix_unitaire').val(prixBase);
        calculerMontants();
    }
    
    if (produitId) {
        $.post('<?= base_url("ArticleCommande/get_variantes") ?>', {id_produit: produitId}, function(data) {
            let options = '<option value="">Aucune variante</option>';
            if (data.length > 0) {
                data.forEach(function(v) {
                    let attrs = JSON.parse(v.attributs_variante);
                    let label = '';
                    if (attrs.taille) label += 'Taille: ' + attrs.taille;
                    if (attrs.couleur) label += (label ? ' | ' : '') + 'Couleur: ' + attrs.couleur;
                    options += `<option value="${v.id_variante}" data-prix="${v.prix}" data-sku="${v.sku}">${label} (${v.sku}) - ${v.prix ? v.prix.toLocaleString('fr-FR') + ' FBu' : ''}</option>`;
                });
            }
            $('#id_variante').html(options).prop('disabled', false);
        }, 'json');
    } else {
        $('#id_variante').html('<option value="">Aucune variante</option>').prop('disabled', true);
    }
});

// Charger le prix de la variante
$('#id_variante').on('change', function() {
    const selected = $(this).find('option:selected');
    const prixVariante = selected.data('prix');
    
    if (prixVariante) {
        $('#prix_unitaire').val(prixVariante);
        calculerMontants();
    }
});

// Charger le taux de commission du vendeur
$('#id_vendeur').on('change', function() {
    const selected = $(this).find('option:selected');
    const taux = selected.data('taux');
    
    if (taux) {
        $('#taux_commission').val(taux);
        calculerMontants();
    }
});

// Recalculer quand prix ou quantité change
$('#prix_unitaire, #quantite').on('input', function() {
    calculerMontants();
});

// Afficher/masquer la section retour
$('#est_retourne').on('change', function() {
    if ($(this).is(':checked')) {
        $('#retourSection').show();
        $('#statut_article').val('retourne');
    } else {
        $('#retourSection').hide();
    }
});

// Initialiser les calculs
$(document).ready(function() {
    calculerMontants();
    
    <?php if (isset($article)): ?>
        if ($('#est_retourne').is(':checked')) {
            $('#retourSection').show();
        }
        
        // Charger les variantes si produit sélectionné
        if ($('#id_produit').val()) {
            $('#id_produit').trigger('change');
            setTimeout(() => {
                if ($('#id_variante').val()) {
                    $('#id_variante').trigger('change');
                }
            }, 500);
        }
    <?php endif; ?>
});

// Validation du formulaire
$('#articleForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!$('#id_commande').val()) {
        Swal.fire('Erreur', 'La commande est requise', 'error');
        return;
    }
    if (!$('#id_produit').val()) {
        Swal.fire('Erreur', 'Le produit est requis', 'error');
        return;
    }
    if (!$('#id_vendeur').val()) {
        Swal.fire('Erreur', 'Le vendeur est requis', 'error');
        return;
    }
    if (!$('#prix_unitaire').val() || parseFloat($('#prix_unitaire').val()) <= 0) {
        Swal.fire('Erreur', 'Le prix unitaire est requis et doit être supérieur à 0', 'error');
        return;
    }
    if (!$('#quantite').val() || parseInt($('#quantite').val()) <= 0) {
        Swal.fire('Erreur', 'La quantité est requise et doit être supérieure à 0', 'error');
        return;
    }
    
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');
    
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => {
                window.location.href = '<?= base_url("ArticleCommande/detail/") ?>' + response.id;
            });
        } else {
            Swal.fire('Erreur', response.message, 'error');
            submitBtn.prop('disabled', false).html('<?= isset($article) ? "Mettre à jour" : "Enregistrer" ?>');
        }
    }, 'json').fail(function() {
        Swal.fire('Erreur', 'Erreur de connexion au serveur', 'error');
        submitBtn.prop('disabled', false).html('<?= isset($article) ? "Mettre à jour" : "Enregistrer" ?>');
    });
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>