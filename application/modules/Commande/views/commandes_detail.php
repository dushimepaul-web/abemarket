<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-1 flex-wrap">
                        <h4 class="card-title flex-grow-1">Commande #<?= $commande->numero_commande ?></h4>
                        <div class="d-flex gap-2">
                            <?php if ($is_admin): ?>
                                <a href="<?= base_url('Commande/edit/' . $commande->id_commande) ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-edit me-1"></i>Modifier
                                </a>
                                <a href="<?= base_url('qr/generate/' . $commande->id_commande) ?>" class="btn btn-primary btn-sm">
                                    <iconify-icon icon="solar:qr-code-bold-duotone" class="me-1"></iconify-icon>
                                    Générer QR code
                                </a>
                                <a href="<?= base_url('qr/view/' . $commande->id_commande) ?>" class="btn btn-info btn-sm">
                                    <iconify-icon icon="solar:eye-broken" class="me-1"></iconify-icon>
                                    Voir QR code
                                </a>
                            <?php endif; ?>
                            <a href="<?= base_url('Commande') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- En-tête de commande -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded bg-primary bg-opacity-10 p-3">
                                        <iconify-icon icon="solar:receipt-bold-duotone" class="fs-32 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h3 class="mb-1"><?= $commande->numero_commande ?></h3>
                                        <p class="text-muted mb-0">
                                            Passée le <?= date('d/m/Y à H:i', strtotime($commande->date_creation)) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="mb-2">
                                    <?php
                                    // Configuration des statuts avec sécurité
                                    $statut_config = [
                                        'en_attente' => ['label' => 'En attente', 'color' => 'warning', 'icon' => 'clock-circle'],
                                        'confirme' => ['label' => 'Confirmée', 'color' => 'info', 'icon' => 'check-circle'],
                                        'en_preparation' => ['label' => 'En préparation', 'color' => 'primary', 'icon' => 'box'],
                                        'expedie' => ['label' => 'Expédiée', 'color' => 'secondary', 'icon' => 'delivery'],
                                        'en_livraison' => ['label' => 'En livraison', 'color' => 'info', 'icon' => 'map-point'],
                                        'livre' => ['label' => 'Livrée', 'color' => 'success', 'icon' => 'home'],
                                        'annule' => ['label' => 'Annulée', 'color' => 'danger', 'icon' => 'close-circle'],
                                        'retourne' => ['label' => 'Retournée', 'color' => 'dark', 'icon' => 'refresh']
                                    ];
                                    
                                    $statut_key = $commande->statut_commande;
                                    $config = isset($statut_config[$statut_key]) ? $statut_config[$statut_key] : [
                                        'label' => ucfirst(str_replace('_', ' ', $statut_key)),
                                        'color' => 'secondary',
                                        'icon' => 'info-circle'
                                    ];
                                    ?>
                                    <span class="badge bg-<?= $config['color'] ?> bg-opacity-10 text-<?= $config['color'] ?> px-3 py-2 fs-14">
                                        <iconify-icon icon="solar:<?= $config['icon'] ?>-bold-duotone" class="me-1"></iconify-icon>
                                        <?= $config['label'] ?>
                                    </span>
                                </div>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <iconify-icon icon="solar:refresh-bold-duotone" class="me-1"></iconify-icon>
                                        Changer statut
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php foreach ($statut_config as $key => $s): ?>
                                            <a class="dropdown-item change-statut" href="#" data-statut="<?= $key ?>">
                                                <iconify-icon icon="solar:<?= $s['icon'] ?>-bold-duotone" class="me-2"></iconify-icon>
                                                <?= $s['label'] ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informations client et livraison -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3">
                                        <iconify-icon icon="solar:user-id-bold-duotone" class="me-2"></iconify-icon>
                                        Informations client
                                    </h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="100"><strong>Nom :</strong></td>
                                            <td><?= htmlspecialchars($commande->prenom ?? '') ?> <?= htmlspecialchars($commande->nom ?? '') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email :</strong></td>
                                            <td><?= htmlspecialchars($commande->email ?? '-') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Téléphone :</strong></td>
                                            <td><?= htmlspecialchars($commande->telephone ?? '-') ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3">
                                        <iconify-icon icon="solar:card-bold-duotone" class="me-2"></iconify-icon>
                                        Paiement & Livraison
                                    </h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="120"><strong>Mode :</strong></td>
                                            <td><?= htmlspecialchars($commande->mode_paiement ?? '-') ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Statut paiement :</strong></td>
                                            <td>
                                                <span class="badge bg-<?= $commande->statut_paiement == 'paye' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst($commande->statut_paiement ?? 'en_attente') ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php if ($commande->numero_suivi): ?>
                                        <tr>
                                            <td><strong>N° suivi :</strong></td>
                                            <td><code><?= htmlspecialchars($commande->numero_suivi) ?></code></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="info-card p-3 bg-light rounded mb-3">
                                    <h5 class="mb-3">
                                        <iconify-icon icon="solar:box-bold-duotone" class="me-2"></iconify-icon>
                                        Résumé
                                    </h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="120"><strong>Sous-total :</strong></td>
                                            <td><?= number_format($commande->sous_total, 0, ',', ' ') ?> FBu</div>
                                        </tr>
                                        <tr>
                                            <td><strong>Frais livraison :</strong></td>
                                            <td><?= number_format($commande->frais_livraison, 0, ',', ' ') ?> FBu</div>
                                        </tr>
                                        <?php if ($commande->montant_reduction > 0): ?>
                                        <tr>
                                            <td><strong>Réduction :</strong></td>
                                            <td class="text-danger">-<?= number_format($commande->montant_reduction, 0, ',', ' ') ?> FBu</div>
                                        </tr>
                                        <?php endif; ?>
                                        <tr class="border-top">
                                            <td><strong>Total :</strong></td>
                                            <td><strong class="text-primary fs-5"><?= number_format($commande->montant_total, 0, ',', ' ') ?> FBu</strong></div>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Articles commandés -->
                        <div class="mt-4">
                            <h5 class="mb-3">
                                <iconify-icon icon="solar:box-bold-duotone" class="me-2"></iconify-icon>
                                Articles commandés
                            </h5>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Produit</th>
                                            <th>Référence</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($articles as $article): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($article->nom_produit) ?></strong>
                                                <?php if ($article->attributs_variante): ?>
                                                    <?php $attrs = json_decode($article->attributs_variante, true); ?>
                                                    <br><small class="text-muted">
                                                        <?= isset($attrs['taille']) ? 'Taille: ' . $attrs['taille'] : '' ?>
                                                        <?= isset($attrs['couleur']) ? ' | Couleur: ' . $attrs['couleur'] : '' ?>
                                                    </small>
                                                <?php endif; ?>
                                             </div>
                                            <td><code><?= htmlspecialchars($article->sku ?? '-') ?></code></div>
                                            <td><?= number_format($article->prix_unitaire, 0, ',', ' ') ?> FBu</div>
                                            <td><?= $article->quantite ?></div>
                                            <td><strong><?= number_format($article->prix_total, 0, ',', ' ') ?> FBu</strong></div>
                                            <td>
                                                <?php
                                                $status_labels = [
                                                    'en_attente' => 'warning',
                                                    'prepare' => 'info',
                                                    'expedie' => 'primary',
                                                    'livre' => 'success',
                                                    'retourne' => 'danger',
                                                    'annule' => 'secondary'
                                                ];
                                                $status_color = $status_labels[$article->statut_article] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $status_color ?>"><?= ucfirst(str_replace('_', ' ', $article->statut_article)) ?></span>
                                             </div>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Historique des statuts -->
                        <?php if (!empty($historique)): ?>
                        <div class="mt-4">
                            <h5 class="mb-3">
                                <iconify-icon icon="solar:clock-circle-bold-duotone" class="me-2"></iconify-icon>
                                Historique des statuts
                            </h5>
                            <div class="timeline">
                                <?php foreach ($historique as $h): ?>
                                <div class="d-flex gap-3 mb-3">
                                    <div class="flex-shrink-0">
                                        <?php
                                        $h_config = isset($statut_config[$h->statut]) ? $statut_config[$h->statut] : [
                                            'color' => 'secondary',
                                            'icon' => 'info-circle'
                                        ];
                                        ?>
                                        <div class="rounded-circle bg-<?= $h_config['color'] ?> bg-opacity-10 p-2">
                                            <iconify-icon icon="solar:<?= $h_config['icon'] ?>-bold-duotone" class="fs-20"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <strong><?= isset($statut_config[$h->statut]) ? $statut_config[$h->statut]['label'] : ucfirst($h->statut) ?></strong>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($h->date_creation)) ?></small>
                                        </div>
                                        <?php if ($h->commentaire): ?>
                                            <p class="mb-0 text-muted"><?= htmlspecialchars($h->commentaire) ?></p>
                                        <?php endif; ?>
                                        <small class="text-muted">Par <?= htmlspecialchars($h->prenom ?? 'Système') ?> <?= htmlspecialchars($h->nom ?? '') ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($commande->note_client): ?>
                        <div class="mt-4">
                            <div class="alert alert-light">
                                <strong><iconify-icon icon="solar:document-text-bold-duotone" class="me-1"></iconify-icon> Note du client :</strong>
                                <p class="mb-0 mt-1"><?= nl2br(htmlspecialchars($commande->note_client)) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Changer le statut
$('.change-statut').on('click', function(e) {
    e.preventDefault();
    const newStatut = $(this).data('statut');
    const label = $(this).text().trim();
    
    Swal.fire({
        title: 'Changer le statut',
        text: `Passer la commande en "${label}" ?`,
        icon: 'question',
        input: 'textarea',
        inputPlaceholder: 'Commentaire (optionnel)',
        showCancelButton: true,
        confirmButtonText: 'Confirmer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("Commande/change_statut") ?>',
                type: 'POST',
                data: {
                    id_commande: <?= $commande->id_commande ?>,
                    statut: newStatut,
                    commentaire: result.value
                },
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
                error: function() {
                    Swal.fire('Erreur', 'Erreur de communication', 'error');
                }
            });
        }
    });
});
</script>

<style>
.info-card { transition: all 0.3s ease; }
.info-card:hover { background-color: #e9ecef !important; }
.table-borderless td, .table-borderless th { padding: 6px 0; }
.timeline { position: relative; padding-left: 30px; }
.timeline::before { content: ''; position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background: #e9ecef; }
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>