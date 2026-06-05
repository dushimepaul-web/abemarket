<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Gestion des Utilisateurs</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard') ?>">Tableau de bord</a></li>
                            <li class="breadcrumb-item active">Utilisateurs</li>
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
                                <i class="bx bx-user-check avatar-title fs-24 text-success"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <p class="text-muted mb-1">Utilisateurs Actifs</p>
                                <h3 class="mb-0"><?= $total_actifs ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-md bg-soft-danger rounded">
                                <i class="bx bx-user-x avatar-title fs-24 text-danger"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <p class="text-muted mb-1">Utilisateurs Inactifs</p>
                                <h3 class="mb-0"><?= $total_inactifs ?? 0 ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton Ajouter -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="text-end">
                    <a href="<?= base_url('Utilisateurs/add') ?>" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>Ajouter un utilisateur
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulaire d'ajout/modification -->
        <?php if(isset($utilisateur) || $this->uri->segment(2) == 'add'): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?= isset($utilisateur) ? 'Modifier l\'utilisateur' : 'Ajouter un nouvel utilisateur' ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= isset($utilisateur) ? base_url('Utilisateurs/edit/'.$utilisateur['id_utilisateur']) : base_url('Utilisateurs/add') ?>" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="prenom" value="<?= isset($utilisateur) ? $utilisateur['prenom'] : '' ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nom" value="<?= isset($utilisateur) ? $utilisateur['nom'] : '' ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="<?= isset($utilisateur) ? $utilisateur['email'] : '' ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control" name="telephone" value="<?= isset($utilisateur) ? $utilisateur['telephone'] : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><?= isset($utilisateur) ? 'Nouveau mot de passe' : 'Mot de passe' ?> <?= !isset($utilisateur) ? '<span class="text-danger">*</span>' : '' ?></label>
                                        <input type="password" class="form-control" name="new_password" <?= !isset($utilisateur) ? 'required' : '' ?>>
                                        <?php if(isset($utilisateur)): ?>
                                            <small class="text-muted">Laisser vide pour ne pas changer</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Avatar</label>
                                        <input type="file" class="form-control" name="avatar" accept="image/*">
                                        <?php if(isset($utilisateur) && $utilisateur['avatar_url']): ?>
                                            <div class="mt-2">
                                                <img src="<?= base_url($utilisateur['avatar_url']) ?>" class="avatar-md rounded-circle" alt="avatar">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Statut</label>
                                        <select class="form-select" name="est_actif">
                                            <option value="1" <?= (isset($utilisateur) && $utilisateur['est_actif'] == 1) ? 'selected' : '' ?>>Actif</option>
                                            <option value="0" <?= (isset($utilisateur) && $utilisateur['est_actif'] == 0) ? 'selected' : '' ?>>Inactif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Email vérifié</label>
                                        <select class="form-select" name="email_verifie">
                                            <option value="1" <?= (isset($utilisateur) && $utilisateur['email_verifie'] == 1) ? 'selected' : '' ?>>Oui</option>
                                            <option value="0" <?= (isset($utilisateur) && $utilisateur['email_verifie'] == 0) ? 'selected' : '' ?>>Non</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Téléphone vérifié</label>
                                        <select class="form-select" name="telephone_verifie">
                                            <option value="1" <?= (isset($utilisateur) && $utilisateur['telephone_verifie'] == 1) ? 'selected' : '' ?>>Oui</option>
                                            <option value="0" <?= (isset($utilisateur) && $utilisateur['telephone_verifie'] == 0) ? 'selected' : '' ?>>Non</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rôles</label>
                                <div class="row">
                                    <?php if(!empty($profils)): ?>
                                        <?php foreach($profils as $profil): ?>
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="profil_ids[]" value="<?= $profil['id_profil'] ?>" 
                                                       id="profil_<?= $profil['id_profil'] ?>"
                                                       <?= (isset($user_roles) && in_array($profil['id_profil'], $user_roles)) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="profil_<?= $profil['id_profil'] ?>">
                                                    <?= ucfirst($profil['description']) ?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i><?= isset($utilisateur) ? 'Mettre à jour' : 'Créer' ?>
                                </button>
                                <a href="<?= base_url('Utilisateurs') ?>" class="btn btn-secondary">
                                    <i class="bx bx-x me-1"></i>Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Liste des utilisateurs -->
        <?php if(!isset($utilisateur) && $this->uri->segment(2) != 'add' && $this->uri->segment(2) != 'edit' && $this->uri->segment(2) != 'view'): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste des utilisateurs</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-centered mb-0" id="usersTable">
                                <thead class="bg-light bg-opacity-50">
                                    <tr>
                                        <th>ID</th>
                                        <th>Avatar</th>
                                        <th>Nom complet</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Rôles</th>
                                        <th>Statut</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($utilisateurs)): ?>
                                        <?php foreach($utilisateurs as $user): ?>
                                        <tr>
                                            <td><?= $user['id_utilisateur'] ?></td>
                                            <td>
                                                <?php if($user['avatar_url']): ?>
                                                    <img src="<?= base_url($user['avatar_url']) ?>" class="avatar-xs rounded-circle" alt="avatar">
                                                <?php else: ?>
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title bg-soft-primary rounded-circle text-primary fs-14">
                                                            <?= strtoupper(substr($user['prenom'], 0, 1)) ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                             </div>
                                            <td><?= $user['prenom'] . ' ' . $user['nom'] ?></td>
                                            <td><?= $user['email'] ?></td>
                                            <td><?= $user['telephone'] ?? '-' ?></div>
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
                                                <?php if($user['est_actif'] == 1): ?>
                                                    <span class="badge bg-success">Actif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactif</span>
                                                <?php endif; ?>
                                             </div>
                                            <td><?= date('d/m/Y', strtotime($user['date_creation'])) ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('Utilisateurs/view/'.$user['id_utilisateur']) ?>" class="btn btn-sm btn-info" title="Voir">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="<?= base_url('Utilisateurs/edit/'.$user['id_utilisateur']) ?>" class="btn btn-sm btn-primary" title="Modifier">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url('Utilisateurs/toggle_status/'.$user['id_utilisateur']) ?>" class="btn btn-sm btn-<?= $user['est_actif'] == 1 ? 'warning' : 'success' ?>" title="<?= $user['est_actif'] == 1 ? 'Désactiver' : 'Activer' ?>">
                                                        <i class="bx bx-<?= $user['est_actif'] == 1 ? 'hide' : 'show' ?>"></i>
                                                    </a>
                                                    <?php if($user['id_utilisateur'] != $this->session->userdata('id_utilisateur')): ?>
                                                        <button type="button" class="btn btn-sm btn-danger" title="Supprimer"
                                                                onclick="confirmDelete(<?= $user['id_utilisateur'] ?>, '<?= $user['prenom'] . ' ' . $user['nom'] ?>')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                             </div>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">Aucun utilisateur trouvé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Vue détaillée d'un utilisateur -->
        <?php if($this->uri->segment(2) == 'view' && isset($utilisateur)): ?>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if($utilisateur['avatar_url']): ?>
                            <img src="<?= base_url($utilisateur['avatar_url']) ?>" class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-soft-primary rounded-circle text-primary fs-34">
                                    <?= strtoupper(substr($utilisateur['prenom'], 0, 1)) . strtoupper(substr($utilisateur['nom'], 0, 1)) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <h4 class="mt-3"><?= $utilisateur['prenom'] . ' ' . $utilisateur['nom'] ?></h4>
                        <p class="text-muted">
                            <?php if($utilisateur['est_actif'] == 1): ?>
                                <span class="badge bg-success">Actif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactif</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informations personnelles</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr><th>ID</th><td><?= $utilisateur['id_utilisateur'] ?></td></tr>
                            <tr><th>Email</th><td><?= $utilisateur['email'] ?></td></tr>
                            <tr><th>Téléphone</th><td><?= $utilisateur['telephone'] ?? '-' ?></td></tr>
                            <tr><th>Email vérifié</th><td><?= $utilisateur['email_verifie'] ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>' ?></td></tr>
                            <tr><th>Téléphone vérifié</th><td><?= $utilisateur['telephone_verifie'] ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>' ?></td></tr>
                            <tr><th>Date d'inscription</th><td><?= date('d/m/Y H:i', strtotime($utilisateur['date_creation'])) ?></td></tr>
                            <tr><th>Dernière connexion</th><td><?= $utilisateur['derniere_connexion'] ? date('d/m/Y H:i', strtotime($utilisateur['derniere_connexion'])) : 'Jamais' ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rôles assignés</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <?php if(!empty($roles)): ?>
                                <?php foreach($roles as $role): ?>
                                    <span class="badge bg-info p-2"><?= ucfirst($role['description']) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Aucun rôle assigné</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Adresses</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Type</th><th>Adresse</th><th>Téléphone</th><th>Par défaut</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($adresses)): ?>
                                        <?php foreach($adresses as $adresse): ?>
                                        <tr>
                                            <td><?= $adresse['type_adresse'] ?></td>
                                            <td><?= $adresse['adresse_ligne'] ?? '-' ?></td>
                                            <td><?= $adresse['telephone'] ?? '-' ?></td>
                                            <td><?= $adresse['est_par_defaut'] ? '<span class="badge bg-success">Oui</span>' : 'Non' ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center">Aucune adresse enregistrée</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Dernières commandes</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>N° Commande</th><th>Date</th><th>Total</th><th>Statut</th></tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($commandes)): ?>
                                        <?php foreach($commandes as $commande): ?>
                                        <tr>
                                            <td>#<?= $commande['numero_commande'] ?></td>
                                            <td><?= date('d/m/Y', strtotime($commande['date_creation'])) ?></td>
                                            <td><?= number_format($commande['montant_total'], 0, ',', ' ') ?> BIF</td>
                                            <td><?= ucfirst($commande['statut_commande']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center">Aucune commande</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <a href="<?= base_url('Utilisateurs') ?>" class="btn btn-secondary">
                    <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                </a>
                <a href="<?= base_url('Utilisateurs/edit/'.$utilisateur['id_utilisateur']) ?>" class="btn btn-primary">
                    <i class="bx bx-edit me-1"></i>Modifier
                </a>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Vous allez supprimer l'utilisateur '" + name + "'. Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("Utilisateurs/delete") ?>/' + id;
        }
    });
}
</script>

<style>
.table td {
    vertical-align: middle;
}
.badge {
    font-size: 11px;
    padding: 5px 8px;
}
.avatar-xl {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>