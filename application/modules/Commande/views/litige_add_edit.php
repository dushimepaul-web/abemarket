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
                            <?= isset($litige) ? 'Modifier le litige #' . $litige->id_litige : 'Nouveau litige' ?>
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('Commande/LitigeCommande') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="litigeForm" method="post" action="<?= isset($litige) ? base_url('Commande/LitigeCommande/update/' . $litige->id_litige) : base_url('Commande/LitigeCommande/save') ?>">
                            
                            <!-- Informations commande -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:receipt-bold-duotone" class="me-2"></iconify-icon>
                                        Commande concernée
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">N° Commande <span class="text-danger">*</span></label>
                                    <select name="id_commande" id="id_commande" class="form-select" <?= isset($litige) ? 'disabled' : '' ?> required>
                                        <option value="">Sélectionner une commande</option>
                                        <?php if (!empty($commandes)): ?>
                                            <?php foreach ($commandes as $c): ?>
                                                <option value="<?= $c->id_commande ?>" 
                                                    <?= isset($litige) && $litige->id_commande == $c->id_commande ? 'selected' : '' ?>
                                                    data-client="<?= htmlspecialchars(($c->prenom ?? '') . ' ' . ($c->nom ?? '')) ?>"
                                                    data-client-id="<?= $c->id_utilisateur ?>"
                                                    data-montant="<?= $c->montant_total ?>"
                                                    data-vendeur-id="<?= $this->db->select('id_vendeur')->from('vendeurs')->where('id_utilisateur', $c->id_utilisateur)->get()->row()->id_vendeur ?? '' ?>">
                                                    <?= $c->numero_commande ?> - <?= htmlspecialchars(($c->prenom ?? '') . ' ' . ($c->nom ?? '')) ?> - <?= number_format($c->montant_total, 0, ',', ' ') ?> FBu
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (isset($litige)): ?>
                                        <input type="hidden" name="id_commande" value="<?= $litige->id_commande ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Client</label>
                                    <input type="text" id="client_info" class="form-control bg-light" readonly placeholder="Sélectionner une commande">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Montant commande</label>
                                    <input type="text" id="commande_montant" class="form-control bg-light" readonly placeholder="Sélectionner une commande">
                                </div>
                            </div>
                            
                            <!-- Parties prenantes -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="me-2"></iconify-icon>
                                        Parties prenantes
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type de plaignant <span class="text-danger">*</span></label>
                                    <select name="type_plaignant" id="type_plaignant" class="form-select" required>
                                        <option value="">Sélectionner</option>
                                        <option value="acheteur" <?= isset($litige) && $litige->type_plaignant == 'acheteur' ? 'selected' : '' ?>>Acheteur (Client)</option>
                                        <option value="vendeur" <?= isset($litige) && $litige->type_plaignant == 'vendeur' ? 'selected' : '' ?>>Vendeur</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Plaignant <span class="text-danger">*</span></label>
                                    <select name="id_plaignant" id="id_plaignant" class="form-select" required>
                                        <option value="">Sélectionner le plaignant</option>
                                        <?php if (isset($clients) && !empty($clients)): ?>
                                            <optgroup label="Clients">
                                                <?php foreach ($clients as $c): ?>
                                                    <option value="<?= $c->id_utilisateur ?>" <?= isset($litige) && $litige->id_plaignant == $c->id_utilisateur ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($c->prenom . ' ' . $c->nom) ?> (Client)
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                        <?php if (isset($vendeurs_list) && !empty($vendeurs_list)): ?>
                                            <optgroup label="Vendeurs">
                                                <?php foreach ($vendeurs_list as $v): ?>
                                                    <option value="<?= $v->id_utilisateur ?>" <?= isset($litige) && $litige->id_plaignant == $v->id_utilisateur ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($v->prenom . ' ' . $v->nom) ?> (Vendeur: <?= htmlspecialchars($v->nom_boutique) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </optgroup>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Défendeur <span class="text-danger">*</span></label>
                                    <select name="id_defendeur" id="id_defendeur" class="form-select" required>
                                        <option value="">Sélectionner le défendeur</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Raison et description -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:chat-round-bold-duotone" class="me-2"></iconify-icon>
                                        Détails du litige
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Raison <span class="text-danger">*</span></label>
                                    <select name="raison" id="raison" class="form-select" required>
                                        <option value="">Sélectionner une raison</option>
                                        <?php foreach ($raisons as $key => $r): ?>
                                            <option value="<?= $key ?>" <?= isset($litige) && $litige->raison == $key ? 'selected' : '' ?>>
                                                <iconify-icon icon="solar:<?= $r['icon'] ?>-bold-duotone" class="me-1"></iconify-icon>
                                                <?= $r['label'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date création</label>
                                    <input type="datetime-local" name="date_creation" id="date_creation" class="form-control bg-light" readonly value="<?= isset($litige) ? date('Y-m-d\TH:i', strtotime($litige->date_creation)) : date('Y-m-d\TH:i') ?>">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control" rows="5" required placeholder="Décrivez le litige en détail..."><?= isset($litige) ? htmlspecialchars($litige->description) : '' ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Pièces jointes -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:attachment-bold-duotone" class="me-2"></iconify-icon>
                                        Pièces jointes
                                    </h5>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">URL des pièces jointes (JSON)</label>
                                    <textarea name="pieces_jointes" id="pieces_jointes" class="form-control" rows="3" placeholder='["url1.jpg", "url2.pdf"]'><?= isset($litige) ? htmlspecialchars($litige->pieces_jointes) : '' ?></textarea>
                                    <small class="text-muted">Format JSON: ["chemin/fichier1.jpg", "chemin/fichier2.png"]</small>
                                </div>
                            </div>
                            
                            <!-- Médiation -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:chat-square-like-bold-duotone" class="me-2"></iconify-icon>
                                        Médiation
                                    </h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select name="statut" id="statut" class="form-select" required>
                                        <?php foreach ($statuts as $key => $s): ?>
                                            <option value="<?= $key ?>" <?= isset($litige) && $litige->statut == $key ? 'selected' : '' ?>>
                                                <?= $s['label'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Médiateur</label>
                                    <select name="mediateur_id" id="mediateur_id" class="form-select">
                                        <option value="">Non assigné</option>
                                        <?php if (!empty($mediateurs)): ?>
                                            <?php foreach ($mediateurs as $m): ?>
                                                <option value="<?= $m->id_utilisateur ?>" <?= isset($litige) && $litige->mediateur_id == $m->id_utilisateur ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($m->prenom . ' ' . $m->nom) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Décision et remboursement -->
                            <div class="row mb-4" id="decisionSection" style="display: none;">
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">
                                        <iconify-icon icon="solar:scale-bold-duotone" class="me-2"></iconify-icon>
                                        Décision et remboursement
                                    </h5>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Décision</label>
                                    <textarea name="decision" id="decision" class="form-control" rows="3" placeholder="Décision du médiateur..."><?= isset($litige) ? htmlspecialchars($litige->decision) : '' ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Montant remboursé (FBu)</label>
                                    <input type="number" step="1" name="montant_rembourse" id="montant_rembourse" class="form-control" value="<?= isset($litige) ? $litige->montant_rembourse : '' ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date résolution</label>
                                    <input type="datetime-local" name="date_resolution" id="date_resolution" class="form-control" value="<?= isset($litige) && $litige->date_resolution ? date('Y-m-d\TH:i', strtotime($litige->date_resolution)) : '' ?>">
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bx bx-save me-1"></i><?= isset($litige) ? 'Mettre à jour' : 'Créer le litige' ?>
                                </button>
                                <a href="<?= base_url('LitigeCommande') ?>" class="btn btn-secondary">Annuler</a>
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
// Charger les informations de la commande
$('#id_commande').on('change', function() {
    const selected = $(this).find('option:selected');
    const client = selected.data('client');
    const clientId = selected.data('client-id');
    const montant = selected.data('montant');
    const vendeurId = selected.data('vendeur-id');
    
    $('#client_info').val(client || '');
    $('#commande_montant').val(montant ? montant.toLocaleString('fr-FR') + ' FBu' : '');
    
    // Mettre à jour les options du défendeur en fonction du type de plaignant
    updateDefendeurOptions(clientId, vendeurId);
});

// Mettre à jour les options du défendeur
function updateDefendeurOptions(clientId, vendeurId) {
    const typePlaignant = $('#type_plaignant').val();
    let defendeurOptions = '<option value="">Sélectionner le défendeur</option>';
    
    if (typePlaignant === 'acheteur') {
        // Si le plaignant est acheteur, le défendeur est le vendeur
        if (vendeurId) {
            defendeurOptions += `<option value="<?= $vendeurs_users ?>" selected>Vendeur (ID: ${vendeurId})</option>`;
        }
        $('#id_defendeur').html(defendeurOptions);
    } else if (typePlaignant === 'vendeur') {
        // Si le plaignant est vendeur, le défendeur est l'acheteur
        if (clientId) {
            defendeurOptions += `<option value="${clientId}" selected>Client (ID: ${clientId})</option>`;
        }
        $('#id_defendeur').html(defendeurOptions);
    }
}

// Mettre à jour les options du plaignant en fonction du type
$('#type_plaignant').on('change', function() {
    const type = $(this).val();
    const commandeSelected = $('#id_commande').find('option:selected');
    const clientId = commandeSelected.data('client-id');
    const vendeurId = commandeSelected.data('vendeur-id');
    
    let plaignantOptions = '<option value="">Sélectionner le plaignant</option>';
    
    if (type === 'acheteur') {
        // Afficher uniquement les clients
        <?php if (isset($clients) && !empty($clients)): ?>
            plaignantOptions += '<optgroup label="Clients">';
            <?php foreach ($clients as $c): ?>
                plaignantOptions += `<option value="<?= $c->id_utilisateur ?>" ${clientId == <?= $c->id_utilisateur ?> ? 'selected' : ''}>
                    <?= addslashes($c->prenom . ' ' . $c->nom) ?> (Client)
                </option>`;
            <?php endforeach; ?>
            plaignantOptions += '</optgroup>';
        <?php endif; ?>
    } else if (type === 'vendeur') {
        // Afficher uniquement les vendeurs
        <?php if (isset($vendeurs_list) && !empty($vendeurs_list)): ?>
            plaignantOptions += '<optgroup label="Vendeurs">';
            <?php foreach ($vendeurs_list as $v): ?>
                plaignantOptions += `<option value="<?= $v->id_utilisateur ?>" ${vendeurId == <?= $v->id_vendeur ?> ? 'selected' : ''}>
                    <?= addslashes($v->prenom . ' ' . $v->nom) ?> (Vendeur: <?= addslashes($v->nom_boutique) ?>)
                </option>`;
            <?php endforeach; ?>
            plaignantOptions += '</optgroup>';
        <?php endif; ?>
    }
    
    $('#id_plaignant').html(plaignantOptions);
    
    // Mettre à jour le défendeur
    updateDefendeurOptions(clientId, vendeurId);
});

// Afficher/masquer la section décision selon le statut
$('#statut').on('change', function() {
    const statut = $(this).val();
    if (statut === 'resolu_acheteur' || statut === 'resolu_vendeur' || statut === 'ferme') {
        $('#decisionSection').show();
        if (statut === 'resolu_acheteur' || statut === 'resolu_vendeur') {
            $('#date_resolution').val(new Date().toISOString().slice(0, 16));
        }
    } else {
        $('#decisionSection').hide();
    }
});

// Initialiser
$(document).ready(function() {
    // Afficher la section décision si nécessaire
    if ($('#statut').val() === 'resolu_acheteur' || $('#statut').val() === 'resolu_vendeur' || $('#statut').val() === 'ferme') {
        $('#decisionSection').show();
    }
    
    // Initialiser le défendeur si commande sélectionnée
    if ($('#id_commande').val()) {
        const selected = $('#id_commande').find('option:selected');
        const clientId = selected.data('client-id');
        const vendeurId = selected.data('vendeur-id');
        updateDefendeurOptions(clientId, vendeurId);
    }
});

// Validation du formulaire
$('#litigeForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!$('#id_commande').val()) {
        Swal.fire('Erreur', 'La commande est requise', 'error');
        return;
    }
    if (!$('#type_plaignant').val()) {
        Swal.fire('Erreur', 'Le type de plaignant est requis', 'error');
        return;
    }
    if (!$('#id_plaignant').val()) {
        Swal.fire('Erreur', 'Le plaignant est requis', 'error');
        return;
    }
    if (!$('#id_defendeur').val()) {
        Swal.fire('Erreur', 'Le défendeur est requis', 'error');
        return;
    }
    if (!$('#raison').val()) {
        Swal.fire('Erreur', 'La raison est requise', 'error');
        return;
    }
    if (!$('#description').val()) {
        Swal.fire('Erreur', 'La description est requise', 'error');
        return;
    }
    
    // Valider le format JSON des pièces jointes si fourni
    const piecesJointes = $('#pieces_jointes').val();
    if (piecesJointes && piecesJointes.trim()) {
        try {
            JSON.parse(piecesJointes);
        } catch (e) {
            Swal.fire('Erreur', 'Le format des pièces jointes est invalide. Utilisez un format JSON valide.', 'error');
            return;
        }
    }
    
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...');
    
    $.post($(this).attr('action'), $(this).serialize(), function(response) {
        if (response.success) {
            Swal.fire('Succès', response.message, 'success').then(() => {
                window.location.href = '<?= base_url("LitigeCommande/detail/") ?>' + response.id;
            });
        } else {
            Swal.fire('Erreur', response.message, 'error');
            submitBtn.prop('disabled', false).html('<?= isset($litige) ? "Mettre à jour" : "Créer le litige" ?>');
        }
    }, 'json').fail(function() {
        Swal.fire('Erreur', 'Erreur de connexion au serveur', 'error');
        submitBtn.prop('disabled', false).html('<?= isset($litige) ? "Mettre à jour" : "Créer le litige" ?>');
    });
});
</script>

<style>
select optgroup { font-weight: bold; font-style: normal; }
select optgroup option { padding-left: 20px; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>