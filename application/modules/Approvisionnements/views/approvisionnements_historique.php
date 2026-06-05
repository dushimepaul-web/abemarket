<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">📜 Historique des approvisionnements</h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('approvisionnements') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="card-body border-bottom">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="id_produit" class="form-select">
                                    <option value="">Tous les produits</option>
                                    <?php foreach ($produits as $p): ?>
                                        <option value="<?= $p->id_produit ?>" <?= ($this->input->get('id_produit') == $p->id_produit) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p->nom_produit) ?> (<?= $p->sku ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_debut" class="form-control" value="<?= $this->input->get('date_debut') ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_fin" class="form-control" value="<?= $this->input->get('date_fin') ?>">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="fournisseur" class="form-control" placeholder="Fournisseur" value="<?= $this->input->get('fournisseur') ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <iconify-icon icon="solar:filter-bold-duotone"></iconify-icon> Filtrer
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="alert alert-primary mb-0">
                                    <iconify-icon icon="solar:box-bold-duotone" class="fs-20 me-2"></iconify-icon>
                                    <strong><?= number_format($stats->total_appro ?? 0) ?></strong>
                                    <small>Total approvisionnements</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-success mb-0">
                                    <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="fs-20 me-2"></iconify-icon>
                                    <strong><?= number_format($stats->total_quantite ?? 0) ?></strong>
                                    <small>Unités reçues</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-warning mb-0">
                                    <iconify-icon icon="solar:wallet-bold-duotone" class="fs-20 me-2"></iconify-icon>
                                    <strong><?= number_format($stats->total_cout ?? 0, 0, ',', ' ') ?> FBu</strong>
                                    <small>Coût total</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert alert-info mb-0">
                                    <iconify-icon icon="solar:shop-bold-duotone" class="fs-20 me-2"></iconify-icon>
                                    <strong><?= number_format($stats->nb_fournisseurs ?? 0) ?></strong>
                                    <small>Fournisseurs</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="bg-light-subtle">
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Produit</th>
                                        <th>Variante</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Coût total</th>
                                        <th>Fournisseur</th>
                                        <th>Référence</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($approvisionnements)): ?>
                                        <?php foreach ($approvisionnements as $a): ?>
                                            <tr>
                                                <td>#<?= $a->id_appro ?></td>
                                                <td><?= date('d/m/Y', strtotime($a->date_appro)) ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($a->nom_produit) ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?= htmlspecialchars($a->sku) ?></small>
                                                 </div>
                                                <td>
                                                    <?php if ($a->variante_sku): ?>
                                                        <span class="badge bg-info"><?= htmlspecialchars($a->variante_sku) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Standard</span>
                                                    <?php endif; ?>
                                                 </div>
                                                <td>
                                                    <strong class="text-success">+ <?= number_format($a->quantite_recue) ?></strong>
                                                    <br>
                                                    <small class="text-muted">Commandé: <?= number_format($a->quantite_initiale) ?></small>
                                                 </div>
                                                <td><?= number_format($a->prix_achat_unitaire, 0, ',', ' ') ?> FBu</div>
                                                <td><strong class="text-primary"><?= number_format($a->cout_total, 0, ',', ' ') ?> FBu</strong></div>
                                                <td>
                                                    <?php if ($a->fournisseur): ?>
                                                        <?= htmlspecialchars($a->fournisseur) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                 </div>
                                                <td>
                                                    <?php if ($a->reference_bon): ?>
                                                        <small><?= htmlspecialchars($a->reference_bon) ?></small>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                 </div>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-soft-warning btn-sm edit-appro" 
                                                                data-id="<?= $a->id_appro ?>"
                                                                data-produit="<?= htmlspecialchars($a->nom_produit) ?>"
                                                                data-quantite="<?= $a->quantite_recue ?>"
                                                                data-prix="<?= $a->prix_achat_unitaire ?>"
                                                                data-fournisseur="<?= htmlspecialchars($a->fournisseur) ?>"
                                                                data-reference="<?= htmlspecialchars($a->reference_bon) ?>"
                                                                data-note="<?= htmlspecialchars($a->note) ?>"
                                                                title="Modifier">
                                                            <iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                        <button type="button" class="btn btn-soft-danger btn-sm delete-appro" 
                                                                data-id="<?= $a->id_appro ?>" 
                                                                data-produit="<?= htmlspecialchars($a->nom_produit) ?>"
                                                                data-quantite="<?= $a->quantite_recue ?>"
                                                                data-variante="<?= $a->id_variante ?>"
                                                                data-idproduit="<?= $a->id_produit ?>"
                                                                title="Supprimer">
                                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <iconify-icon icon="solar:document-broken" class="fs-48 text-muted mb-3 d-block"></iconify-icon>
                                                <h5>Aucun approvisionnement trouvé</h5>
                                                <p class="text-muted">Aucun historique pour la période sélectionnée</p>
                                            </div>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($approvisionnements)): ?>
                        <div class="card-footer border-top">
                            <?= $this->pagination->create_links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <iconify-icon icon="solar:pen-2-broken" class="me-1"></iconify-icon>
                    Modifier l'approvisionnement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" name="id_appro" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Produit</label>
                        <input type="text" id="edit_produit" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantité reçue <span class="text-danger">*</span></label>
                        <input type="number" name="quantite_recue" id="edit_quantite" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prix d'achat unitaire (FBu) <span class="text-danger">*</span></label>
                        <input type="number" name="prix_achat_unitaire" id="edit_prix" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fournisseur</label>
                        <input type="text" name="fournisseur" id="edit_fournisseur" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Référence bon de commande</label>
                        <input type="text" name="reference_bon" id="edit_reference" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" id="edit_note" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="alert alert-info">
                        <iconify-icon icon="solar:info-circle-bold-duotone" class="me-1"></iconify-icon>
                        La modification va recalculer le stock automatiquement.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <iconify-icon icon="solar:pen-2-broken"></iconify-icon> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Modifier
$('.edit-appro').on('click', function() {
    const id = $(this).data('id');
    const produit = $(this).data('produit');
    const quantite = $(this).data('quantite');
    const prix = $(this).data('prix');
    const fournisseur = $(this).data('fournisseur');
    const reference = $(this).data('reference');
    const note = $(this).data('note');
    
    $('#edit_id').val(id);
    $('#edit_produit').val(produit);
    $('#edit_quantite').val(quantite);
    $('#edit_prix').val(prix);
    $('#edit_fournisseur').val(fournisseur || '');
    $('#edit_reference').val(reference || '');
    $('#edit_note').val(note || '');
    
    $('#editModal').modal('show');
});

$('#editForm').on('submit', function(e) {
    e.preventDefault();
    
    const id = $('#edit_id').val();
    const quantite = $('#edit_quantite').val();
    const prix = $('#edit_prix').val();
    
    if (!quantite || quantite <= 0) {
        Swal.fire('Erreur', 'Quantité invalide', 'error');
        return;
    }
    if (!prix || prix <= 0) {
        Swal.fire('Erreur', 'Prix invalide', 'error');
        return;
    }
    
    Swal.fire({
        title: 'Confirmation',
        text: 'Voulez-vous modifier cet approvisionnement ? Le stock sera recalculé.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("approvisionnements/update") ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Succès', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});

// Supprimer
$('.delete-appro').on('click', function() {
    const id = $(this).data('id');
    const produit = $(this).data('produit');
    const quantite = $(this).data('quantite');
    const variante = $(this).data('variante');
    const idProduit = $(this).data('idproduit');
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        html: `Vous allez supprimer l'approvisionnement pour <strong>${produit}</strong>.<br>
               Quantité: <strong>${quantite}</strong> unités.<br>
               Le stock sera automatiquement réduit.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("approvisionnements/delete/") ?>' + id,
                type: 'POST',
                data: {
                    id_appro: id,
                    id_produit: idProduit,
                    id_variante: variante,
                    quantite: quantite
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Supprimé!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Erreur!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});
</script>

<style>
.btn-soft-warning { background-color: #fff3cd; border-color: #fff3cd; color: #ffc107; }
.btn-soft-warning:hover { background-color: #ffecb5; }
.btn-soft-danger { background-color: #f8d7da; border-color: #f8d7da; color: #dc3545; }
.btn-soft-danger:hover { background-color: #f5c2c7; }
.bg-light-subtle { background-color: #f8f9fa; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>