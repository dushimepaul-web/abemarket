<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-xxl">

        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Liste des Vendeurs</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Vendeurs</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Total Vendeurs</h4>
                                <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($total_sellers ?? 0) ?></p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:shop-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Vendeurs Actifs</h4>
                                <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($active_sellers ?? 0) ?></p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:user-check-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">En Attente</h4>
                                <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($pending_sellers ?? 0) ?></p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="card-title mb-2">Chiffre d'affaires</h4>
                                <p class="text-muted fw-medium fs-22 mb-0"><?= number_format($total_sales ?? 0, 0, ',', ' ') ?> BIF</p>
                            </div>
                            <div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:bill-list-bold-duotone" class="fs-32 text-primary avatar-title"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select" id="filter_status">
                                    <option value="">Tous</option>
                                    <option value="actif">Actif</option>
                                    <option value="en_attente">En attente</option>
                                    <option value="suspendu">Suspendu</option>
                                    <option value="banni">Banni</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Type vendeur</label>
                                <select class="form-select" id="filter_type">
                                    <option value="">Tous</option>
                                    <option value="particulier">Particulier</option>
                                    <option value="entreprise">Entreprise</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Province</label>
                                <select class="form-select" id="filter_province">
                                    <option value="">Toutes</option>
                                    <?php if(isset($provinces) && !empty($provinces)): ?>
                                        <?php foreach($provinces as $province): ?>
                                            <option value="<?= $province['id_province'] ?>"><?= $province['province_name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-primary w-100" onclick="applyFilters()">
                                    <i class="bx bx-search me-1"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton Ajouter -->
        <div class="row mb-3">
            <div class="col-12 text-end">
                <a href="<?= base_url('Sellers/add') ?>" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i>Ajouter un vendeur
                </a>
            </div>
        </div>

        <!-- Liste des vendeurs en cartes -->
        <div class="row" id="sellers-container">
            <?php if(!empty($sellers)): ?>
                <?php foreach($sellers as $seller): ?>
                <div class="col-xl-3 col-md-6 seller-card" 
                     data-status="<?= $seller['statut'] ?? 'en_attente' ?>"
                     data-type="<?= $seller['type_vendeur'] ?>"
                     data-province="<?= $seller['id_province'] ?? '' ?>">
                    <div class="card">
                        <div class="card-body">
                            <div class="position-relative bg-light p-2 rounded text-center">
                                <!-- Badge de statut -->
                                <div class="position-absolute top-0 start-0 m-2">
                                    <?php if($seller['statut'] == 'actif'): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php elseif($seller['statut'] == 'en_attente'): ?>
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    <?php elseif($seller['statut'] == 'suspendu'): ?>
                                        <span class="badge bg-danger">Suspendu</span>
                                    <?php elseif($seller['statut'] == 'banni'): ?>
                                        <span class="badge bg-dark">Banni</span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Logo -->
                                <?php if($seller['logo_boutique']): ?>
                                    <img src="<?= base_url($seller['logo_boutique']) ?>" alt="<?= $seller['nom_boutique'] ?>" class="avatar-xxl" style="height: 100px; object-fit: contain;">
                                <?php else: ?>
                                    <div class="avatar-xxl d-flex align-items-center justify-content-center mx-auto">
                                        <div class="avatar-lg bg-soft-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <iconify-icon icon="solar:shop-bold-duotone" class="fs-34 text-primary"></iconify-icon>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Menu dropdown -->
                                <div class="position-absolute top-0 end-0 m-1">
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <iconify-icon icon="iconamoon:menu-kebab-vertical-circle-duotone" class="fs-20 align-middle text-muted"></iconify-icon>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="<?= base_url('Sellers/view/'.$seller['id_vendeur']) ?>" class="dropdown-item">
                                                <i class="bx bx-show me-1"></i>Voir détails
                                            </a>
                                            <a href="<?= base_url('Sellers/edit/'.$seller['id_vendeur']) ?>" class="dropdown-item">
                                                <i class="bx bx-edit me-1"></i>Modifier
                                            </a>
                                            <?php if($seller['est_approuve'] == 0): ?>
                                                <a href="<?= base_url('Sellers/approve/'.$seller['id_vendeur']) ?>" class="dropdown-item">
                                                    <i class="bx bx-check-circle me-1"></i>Approuver
                                                </a>
                                            <?php endif; ?>
                                            <?php if($seller['statut'] == 'actif'): ?>
                                                <a href="<?= base_url('Sellers/suspend/'.$seller['id_vendeur']) ?>" class="dropdown-item text-warning">
                                                    <i class="bx bx-pause-circle me-1"></i>Suspendre
                                                </a>
                                            <?php elseif($seller['statut'] == 'suspendu'): ?>
                                                <a href="<?= base_url('Sellers/suspend/'.$seller['id_vendeur']) ?>" class="dropdown-item text-success">
                                                    <i class="bx bx-play-circle me-1"></i>Réactiver
                                                </a>
                                            <?php endif; ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="#" class="dropdown-item text-danger" onclick="confirmDelete(<?= $seller['id_vendeur'] ?>, '<?= addslashes($seller['nom_boutique']) ?>')">
                                                <i class="bx bx-trash me-1"></i>Supprimer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap justify-content-between my-3">
                                <div>
                                    <h4 class="mb-1"><?= $seller['nom_boutique'] ?>
                                        <span class="text-muted fs-13 ms-1">(<?= ucfirst($seller['type_vendeur']) ?>)</span>
                                    </h4>
                                    <div>
                                        <span class="fs-14 text-muted"><?= $seller['prenom'] . ' ' . $seller['nom'] ?></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0">
                                        <span class="badge bg-light text-dark fs-12 me-1">
                                            <i class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 
                                            <?= number_format($seller['note_moyenne'] ?? 0, 1) ?>
                                        </span>
                                        <?= number_format($seller['nombre_avis'] ?? 0) ?>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Informations de contact -->
                            <div class="mb-2">
                                <p class="d-flex align-items-center gap-2 mb-1">
                                    <iconify-icon icon="solar:letter-bold-duotone" class="fs-18 text-primary"></iconify-icon>
                                    <small><?= $seller['email'] ?></small>
                                </p>
                                <p class="d-flex align-items-center gap-2 mb-1">
                                    <iconify-icon icon="solar:outgoing-call-rounded-bold-duotone" class="fs-20 text-primary"></iconify-icon>
                                    <small><?= $seller['telephone'] ?? 'Non renseigné' ?></small>
                                </p>
                                <?php if(!empty($seller['whatsapp'])): ?>
                                <p class="d-flex align-items-center gap-2 mb-1">
                                    <iconify-icon icon="logos:whatsapp-icon" class="fs-18"></iconify-icon>
                                    <small><?= $seller['whatsapp'] ?></small>
                                </p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Localisation -->
                            <?php if(!empty($seller['province_name']) || !empty($seller['commune_name']) || !empty($seller['quartier_name'])): ?>
                            <div class="border-top pt-2 mt-2 mb-2">
                                <p class="d-flex align-items-center gap-2 mb-1">
                                    <iconify-icon icon="solar:map-point-bold-duotone" class="fs-18 text-primary"></iconify-icon>
                                    <small>
                                        <?php if(!empty($seller['province_name'])): ?>
                                            <?= $seller['province_name'] ?>
                                            <?php if(!empty($seller['commune_name'])): ?> > <?= $seller['commune_name'] ?><?php endif; ?>
                                            <?php if(!empty($seller['quartier_name'])): ?> > <?= $seller['quartier_name'] ?><?php endif; ?>
                                        <?php else: ?>
                                            Localisation non renseignée
                                        <?php endif; ?>
                                    </small>
                                </p>
                                <?php if(!empty($seller['latitude']) && !empty($seller['longitude'])): ?>
                                <p class="d-flex align-items-center gap-2 mb-0">
                                    <iconify-icon icon="solar:gps-bold-duotone" class="fs-18 text-primary"></iconify-icon>
                                    <small class="text-muted">GPS: <?= $seller['latitude'] ?>, <?= $seller['longitude'] ?></small>
                                </p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Commission -->
                            <div class="d-flex align-items-center justify-content-between mt-3 mb-1">
                                <p class="mb-0 fs-15 fw-medium text-dark">Commission</p>
                                <div>
                                    <p class="mb-0 fs-15 fw-medium text-dark"><?= $seller['taux_commission'] ?>%</p>
                                </div>
                            </div>
                            <div class="progress progress-soft progress-md">
                                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: <?= min(100, $seller['taux_commission'] * 5) ?>%" 
                                     aria-valuenow="<?= $seller['taux_commission'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            
                            <!-- Statistiques -->
                            <div class="p-2 pb-0 mx-n3 mt-2">
                                <div class="row text-center g-2">
                                    <div class="col-lg-4 col-4 border-end">
                                        <h5 class="mb-1"><?= $seller['total_products'] ?? 0 ?></h5>
                                        <p class="text-muted mb-0">Produits</p>
                                    </div>
                                    <div class="col-lg-4 col-4 border-end">
                                        <h5 class="mb-1"><?= $seller['total_orders'] ?? 0 ?></h5>
                                        <p class="text-muted mb-0">Commandes</p>
                                    </div>
                                    <div class="col-lg-4 col-4">
                                        <h5 class="mb-1"><?= number_format(($seller['total_revenue'] ?? 0) / 1000, 0) ?>k</h5>
                                        <p class="text-muted mb-0">CA (BIF)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-top gap-1 hstack">
                            <a href="<?= base_url('Sellers/view/'.$seller['id_vendeur']) ?>" class="btn btn-primary w-100">
                                <i class="bx bx-show me-1"></i>Voir profil
                            </a>
                            <a href="<?= base_url('Sellers/edit/'.$seller['id_vendeur']) ?>" class="btn btn-light w-100">
                                <i class="bx bx-edit me-1"></i>Modifier
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <iconify-icon icon="solar:shop-bold-duotone" class="fs-48 text-muted mb-3"></iconify-icon>
                            <p class="text-muted mb-0">Aucun vendeur trouvé</p>
                            <a href="<?= base_url('Sellers/add') ?>" class="btn btn-primary mt-3">
                                <i class="bx bx-plus me-1"></i>Ajouter un vendeur
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if(isset($pagination) && !empty($pagination)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                <?= $pagination ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
// Filtrage des vendeurs
function applyFilters() {
    const status = document.getElementById('filter_status').value;
    const type = document.getElementById('filter_type').value;
    const province = document.getElementById('filter_province').value;
    
    const cards = document.querySelectorAll('.seller-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        let show = true;
        
        if (status && card.dataset.status !== status) {
            show = false;
        }
        
        if (type && card.dataset.type !== type) {
            show = false;
        }
        
        if (province && card.dataset.province !== province) {
            show = false;
        }
        
        if (show) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Afficher un message si aucun résultat
    let noResultMsg = document.getElementById('no-result-message');
    if (visibleCount === 0) {
        if (!noResultMsg) {
            noResultMsg = document.createElement('div');
            noResultMsg.id = 'no-result-message';
            noResultMsg.className = 'col-12';
            noResultMsg.innerHTML = '<div class="card"><div class="card-body text-center"><p class="text-muted mb-0">Aucun vendeur ne correspond aux critères de recherche.</p></div></div>';
            document.getElementById('sellers-container').appendChild(noResultMsg);
        }
        noResultMsg.style.display = '';
    } else if (noResultMsg) {
        noResultMsg.style.display = 'none';
    }
}

// Réinitialiser les filtres
function resetFilters() {
    document.getElementById('filter_status').value = '';
    document.getElementById('filter_type').value = '';
    document.getElementById('filter_province').value = '';
    applyFilters();
}

// Confirmation de suppression
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        html: "Vous allez supprimer le vendeur <strong>'" + name + "'</strong>.<br>Cette action est irréversible et supprimera également tous ses produits, commandes et données associées !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Sellers/delete") ?>/' + id;
        }
    });
}

// Confirmation de suspension/réactivation
function confirmSuspend(id, name, action) {
    const actionText = action === 'suspend' ? 'suspendre' : 'réactiver';
    const actionIcon = action === 'suspend' ? 'warning' : 'info';
    const confirmText = action === 'suspend' ? 'Oui, suspendre' : 'Oui, réactiver';
    
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez " + actionText + " le vendeur '" + name + "'.",
        icon: actionIcon,
        showCancelButton: true,
        confirmButtonColor: action === 'suspend' ? '#d33' : '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmText,
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Sellers/suspend") ?>/' + id;
        }
    });
}

// Confirmation de bannissement
function confirmBan(id, name) {
    Swal.fire({
        title: 'Bannir le vendeur',
        html: `
            <p>Vous allez bannir le vendeur <strong>'${name}'</strong>.</p>
            <div class="mt-3">
                <label class="form-label">Motif du bannissement :</label>
                <textarea id="ban_reason" class="form-control" rows="3" placeholder="Expliquez la raison du bannissement..."></textarea>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, bannir',
        cancelButtonText: 'Annuler',
        preConfirm: () => {
            const reason = document.getElementById('ban_reason').value;
            if (!reason) {
                Swal.showValidationMessage('Veuillez saisir un motif de bannissement');
                return false;
            }
            return { reason: reason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Envoyer avec AJAX ou formulaire
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url("Sellers/ban") ?>/' + id;
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'motif_ban';
            input.value = result.value.reason;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Export des données
function exportSellers() {
    window.location.href = '<?= base_url("Sellers/export") ?>?' + new URLSearchParams({
        status: document.getElementById('filter_status').value,
        type: document.getElementById('filter_type').value,
        province: document.getElementById('filter_province').value
    }).toString();
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
.avatar-xxl {
    width: 100px;
    height: 100px;
    object-fit: contain;
}
.hstack {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-footer .btn {
    padding: 0.375rem 0.75rem;
}
.progress-soft {
    background-color: rgba(220, 53, 69, 0.1);
}
.progress-md {
    height: 8px;
}
.seller-card {
    transition: transform 0.2s ease;
}
.seller-card:hover {
    transform: translateY(-5px);
}
.badge i, .badge iconify-icon {
    vertical-align: middle;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>