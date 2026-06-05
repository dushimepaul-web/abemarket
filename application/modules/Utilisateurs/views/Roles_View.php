<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Gestion des Rôles & Utilisateurs</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Rôles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md bg-soft-primary rounded">
                                <i class="bx bx-group avatar-title fs-24 text-primary"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <p class="text-muted mb-1">Total Utilisateurs</p>
                                <h3 class="mb-0"><?= $total_utilisateurs ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md bg-soft-success rounded">
                                <i class="bx bx-shield-alt avatar-title fs-24 text-success"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <p class="text-muted mb-1">Profils Disponibles</p>
                                <h3 class="mb-0"><?= $total_profils ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfilModal">
                                    <i class="bx bx-plus me-1"></i>Nouveau Profil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des utilisateurs -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Gestion des utilisateurs</h4>
                        <p class="text-muted mb-0">Gérez les utilisateurs, assignez leurs rôles et modifiez leurs informations</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0" id="usersTable">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom complet</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Statut</th>
                                        <th>Rôles actuels</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($utilisateurs)): ?>
                                        <?php foreach($utilisateurs as $user): ?>
                                        <tr>
                                            <td><?= $user['id_utilisateur'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-soft-primary rounded-circle text-primary fs-14">
                                                            <?= strtoupper(substr($user['prenom'], 0, 1)) ?>
                                                        </div>
                                                    </div>
                                                    <?= $user['prenom'] . ' ' . $user['nom'] ?>
                                                </div>
                                             </div>
                                            <td><?= $user['email'] ?></td>
                                            <td><?= $user['telephone'] ?? '-' ?></td>
                                            <td>
                                                <?php if($user['est_actif'] == 1): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactif</span>
                                                <?php endif; ?>
                                             </div>
                                            <td>
                                                <?php 
                                                if(isset($user_roles[$user['id_utilisateur']]) && !empty($user_roles[$user['id_utilisateur']])):
                                                    foreach($user_roles[$user['id_utilisateur']] as $pid):
                                                        foreach($profils as $p):
                                                            if($p['id_profil'] == $pid):
                                                                $badge_class = ($p['description'] == 'super_admin') ? 'danger' : (($p['description'] == 'admin') ? 'warning' : 'info');
                                                                echo '<span class="badge bg-'.$badge_class.' me-1">'.ucfirst($p['description']).'</span>';
                                                            endif;
                                                        endforeach;
                                                    endforeach;
                                                else:
                                                    echo '<span class="text-muted">Aucun rôle</span>';
                                                endif;
                                                ?>
                                             </div>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-info" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewUserModal"
                                                            data-user-id="<?= $user['id_utilisateur'] ?>"
                                                            data-user-name="<?= $user['prenom'] . ' ' . $user['nom'] ?>"
                                                            data-user-email="<?= $user['email'] ?>"
                                                            data-user-phone="<?= $user['telephone'] ?? 'Non renseigné' ?>"
                                                            data-user-avatar="<?= $user['avatar_url'] ?? '' ?>"
                                                            data-user-status="<?= $user['est_actif'] ?>"
                                                            data-user-email-verified="<?= $user['email_verifie'] ?>"
                                                            data-user-phone-verified="<?= $user['telephone_verifie'] ?>"
                                                            data-user-created="<?= date('d/m/Y H:i', strtotime($user['date_creation'])) ?>"
                                                            data-user-last-login="<?= $user['derniere_connexion'] ? date('d/m/Y H:i', strtotime($user['derniere_connexion'])) : 'Jamais' ?>">
                                                        <i class="bx bx-show me-1"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary assign-role-btn" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#assignRoleModal"
                                                            data-user-id="<?= $user['id_utilisateur'] ?>"
                                                            data-user-name="<?= $user['prenom'] . ' ' . $user['nom'] ?>">
                                                        <i class="bx bx-user-plus me-1"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning edit-user-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editUserModal"
                                                            data-user-id="<?= $user['id_utilisateur'] ?>"
                                                            data-user-prenom="<?= $user['prenom'] ?>"
                                                            data-user-nom="<?= $user['nom'] ?>"
                                                            data-user-email="<?= $user['email'] ?>"
                                                            data-user-phone="<?= $user['telephone'] ?? '' ?>"
                                                            data-user-status="<?= $user['est_actif'] ?>">
                                                        <i class="bx bx-edit me-1"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger delete-user-btn"
                                                            data-user-id="<?= $user['id_utilisateur'] ?>"
                                                            data-user-name="<?= $user['prenom'] . ' ' . $user['nom'] ?>">
                                                        <i class="bx bx-trash me-1"></i>
                                                    </button>
                                                </div>
                                             </div>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Aucun utilisateur trouvé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Détails Utilisateur -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">
                    <i class="bx bx-user-circle me-2"></i>Détails de l'utilisateur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <img id="userAvatar" src="<?= base_url('assets/images/users/avatar-default.jpg') ?>" 
                                 class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <h5 id="userName" class="mb-1"></h5>
                        <span id="userStatus" class="badge"></span>
                    </div>
                    <div class="col-md-8">
                        <h6 class="mb-3"><i class="bx bx-info-circle me-2"></i>Informations personnelles</h6>
                        <table class="table table-bordered">
                            <tr><th style="width: 40%;">ID Utilisateur</th><td id="userId"></td></tr>
                            <tr><th>Nom complet</th><td id="userFullName"></td></tr>
                            <tr><th>Email</th><td id="userEmail"></td></tr>
                            <tr><th>Téléphone</th><td id="userPhone"></td></tr>
                            <tr><th>Email vérifié</th><td id="userEmailVerified"></td></tr>
                            <tr><th>Téléphone vérifié</th><td id="userPhoneVerified"></td></tr>
                            <tr><th>Date d'inscription</th><td id="userCreated"></td></tr>
                            <tr><th>Dernière connexion</th><td id="userLastLogin"></td></tr>
                        </table>
                        <h6 class="mb-3 mt-3"><i class="bx bx-shield-alt me-2"></i>Rôles assignés</h6>
                        <div id="userRoles" class="d-flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Éditer Utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">
                    <i class="bx bx-edit me-2"></i>Modifier l'utilisateur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('Roles/modifier_utilisateur') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prénom</label>
                                <input type="text" class="form-control" name="prenom" id="edit_prenom" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" name="nom" id="edit_nom" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="tel" class="form-control" name="telephone" id="edit_telephone">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select class="form-select" name="est_actif" id="edit_est_actif">
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                        <input type="password" class="form-control" name="new_password" placeholder="Nouveau mot de passe">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'assignation des rôles -->
<div class="modal fade" id="assignRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assigner des rôles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('Roles/assigner') ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="modal_user_id">
                    <div class="mb-3">
                        <label class="form-label">Utilisateur :</label>
                        <p class="fw-semibold" id="modal_user_name"></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sélectionner les rôles :</label>
                        <div class="row" id="profils_checkboxes">
                            <?php if(!empty($profils)): ?>
                                <?php foreach($profils as $profil): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="profil_ids[]" value="<?= $profil['id_profil'] ?>" 
                                               id="profil_<?= $profil['id_profil'] ?>">
                                        <label class="form-check-label" for="profil_<?= $profil['id_profil'] ?>">
                                            <?= ucfirst($profil['description']) ?>
                                            <?php if($profil['description'] == 'super_admin'): ?>
                                                <span class="badge bg-danger">Admin total</span>
                                            <?php elseif($profil['description'] == 'admin'): ?>
                                                <span class="badge bg-warning">Admin</span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ajouter Profil -->
<div class="modal fade" id="addProfilModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un nouveau profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('Roles/ajouter_profil') ?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="description" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select" name="est_actif">
                                    <option value="1">Actif</option>
                                    <option value="0">Inactif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="row">
                            <?php
                            $all_permissions = [
                                'viewDashboard' => 'Voir le tableau de bord',
                                'manageUtilisateurs' => 'Gérer les utilisateurs',
                                'manageProfils' => 'Gérer les profils',
                                'manageProduits' => 'Gérer les produits',
                                'manageCommandes' => 'Gérer les commandes',
                                'manageVendeurs' => 'Gérer les vendeurs',
                                'manageCategories' => 'Gérer les catégories',
                                'manageCoupons' => 'Gérer les coupons',
                                'managePaiements' => 'Gérer les paiements',
                                'manageLivraisons' => 'Gérer les livraisons',
                                'manageLitiges' => 'Gérer les litiges',
                                'manageTransporteurs' => 'Gérer les transporteurs',
                                'viewRapports' => 'Voir les rapports',
                                'validateKyc' => 'Valider KYC'
                            ];
                            ?>
                            <?php foreach($all_permissions as $key => $label): ?>
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="<?= $key ?>" id="perm_<?= $key ?>">
                                    <label class="form-check-label" for="perm_<?= $key ?>"><?= $label ?></label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal d'assignation des rôles
    const assignModal = document.getElementById('assignRoleModal');
    if (assignModal) {
        assignModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            
            assignModal.querySelector('#modal_user_id').value = userId;
            assignModal.querySelector('#modal_user_name').textContent = userName;
            
            fetch('<?= base_url("Roles/get_user_roles") ?>/' + userId)
                .then(response => response.json())
                .then(data => {
                    const checkboxes = assignModal.querySelectorAll('input[name="profil_ids[]"]');
                    checkboxes.forEach(checkbox => checkbox.checked = false);
                    data.forEach(roleId => {
                        const checkbox = assignModal.querySelector(`input[name="profil_ids[]"][value="${roleId}"]`);
                        if (checkbox) checkbox.checked = true;
                    });
                });
        });
    }
    
    // Modal détails utilisateur
    const viewModal = document.getElementById('viewUserModal');
    if (viewModal) {
        viewModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            document.getElementById('userId').textContent = button.getAttribute('data-user-id');
            document.getElementById('userName').textContent = button.getAttribute('data-user-name');
            document.getElementById('userFullName').textContent = button.getAttribute('data-user-name');
            document.getElementById('userEmail').textContent = button.getAttribute('data-user-email');
            document.getElementById('userPhone').textContent = button.getAttribute('data-user-phone');
            document.getElementById('userCreated').textContent = button.getAttribute('data-user-created');
            document.getElementById('userLastLogin').textContent = button.getAttribute('data-user-last-login');
            
            const statusBadge = document.getElementById('userStatus');
            if (button.getAttribute('data-user-status') == 1) {
                statusBadge.textContent = 'Actif';
                statusBadge.className = 'badge bg-success';
            } else {
                statusBadge.textContent = 'Inactif';
                statusBadge.className = 'badge bg-danger';
            }
            
            const emailVerified = button.getAttribute('data-user-email-verified');
            document.getElementById('userEmailVerified').innerHTML = emailVerified == 1 ? 
                '<span class="badge bg-success"><i class="bx bx-check-circle"></i> Vérifié</span>' : 
                '<span class="badge bg-warning"><i class="bx bx-x-circle"></i> Non vérifié</span>';
            
            const phoneVerified = button.getAttribute('data-user-phone-verified');
            document.getElementById('userPhoneVerified').innerHTML = phoneVerified == 1 ? 
                '<span class="badge bg-success"><i class="bx bx-check-circle"></i> Vérifié</span>' : 
                '<span class="badge bg-warning"><i class="bx bx-x-circle"></i> Non vérifié</span>';
            
            const avatar = button.getAttribute('data-user-avatar');
            const avatarImg = document.getElementById('userAvatar');
            avatarImg.src = avatar && avatar !== '' ? '<?= base_url() ?>' + avatar : '<?= base_url("assets/images/users/avatar-default.jpg") ?>';
            
            const userId = button.getAttribute('data-user-id');
            fetch('<?= base_url("Roles/get_user_roles") ?>/' + userId)
                .then(response => response.json())
                .then(data => {
                    const rolesContainer = document.getElementById('userRoles');
                    rolesContainer.innerHTML = '';
                    const profils = <?= json_encode($profils) ?>;
                    if (data.length > 0) {
                        data.forEach(roleId => {
                            const profil = profils.find(p => p.id_profil == roleId);
                            if (profil) {
                                let badgeClass = 'info';
                                if (profil.description == 'super_admin') badgeClass = 'danger';
                                else if (profil.description == 'admin') badgeClass = 'warning';
                                else if (profil.description == 'vendeur') badgeClass = 'primary';
                                else if (profil.description == 'client') badgeClass = 'success';
                                rolesContainer.innerHTML += `<span class="badge bg-${badgeClass} p-2">${profil.description}</span>`;
                            }
                        });
                    } else {
                        rolesContainer.innerHTML = '<span class="text-muted">Aucun rôle assigné</span>';
                    }
                });
        });
    }
    
    // Modal édition utilisateur
    const editModal = document.getElementById('editUserModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('edit_user_id').value = button.getAttribute('data-user-id');
            document.getElementById('edit_prenom').value = button.getAttribute('data-user-prenom');
            document.getElementById('edit_nom').value = button.getAttribute('data-user-nom');
            document.getElementById('edit_email').value = button.getAttribute('data-user-email');
            document.getElementById('edit_telephone').value = button.getAttribute('data-user-phone');
            document.getElementById('edit_est_actif').value = button.getAttribute('data-user-status');
        });
    }
});

// Supprimer un utilisateur
function deleteUser(userId, userName) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer l'utilisateur '" + userName + "'. Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Roles/supprimer_utilisateur") ?>/' + userId;
        }
    });
}

// Attacher les événements de suppression
document.querySelectorAll('.delete-user-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const userId = this.getAttribute('data-user-id');
        const userName = this.getAttribute('data-user-name');
        deleteUser(userId, userName);
    });
});
</script>

<style>
.table td {
    vertical-align: middle;
}
.badge {
    font-size: 11px;
    padding: 5px 8px;
}
.btn-sm {
    padding: 5px 10px;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>