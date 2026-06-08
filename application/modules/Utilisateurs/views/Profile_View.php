<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>


          <!-- Page Content Start -->
          <div class="page-content">
               <div class="container-xxl">

                    <div class="row">
                         <div class="col-xl-9 col-lg-8">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <div class="bg-primary profile-bg rounded-top position-relative mx-n3 mt-n3" style="height: 120px;">
                                             <img src="<?= base_url($avatar_url ?? 'assets/images/users/avatar-default.jpg') ?>" alt="" class="avatar-xl border border-light border-3 rounded-circle position-absolute top-100 start-0 translate-middle ms-5">
                                        </div>
                                        <div class="mt-5 d-flex flex-wrap align-items-center justify-content-between">
                                             <div>
                                                  <h4 class="mb-1"><?= $prenom . ' ' . $nom ?> 
                                                       <i class='bx bxs-badge-check text-success align-middle'></i>
                                                  </h4>
                                                  <p class="mb-0"><?= $role ?? 'Utilisateur' ?></p>
                                             </div>
                                             <div class="d-flex align-items-center gap-2 my-2 my-lg-0">
                                                  <a href="#!" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                                       <i class='bx bx-edit'></i> Modifier
                                                  </a>
                                                  <a href="#!" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                                       <i class="bx bx-lock"></i> Mot de passe
                                                  </a>
                                             </div>
                                        </div>
                                        <div class="row mt-3 gy-2">
                                             <div class="col-lg-3 col-6">
                                                  <div class="d-flex align-items-center gap-2 border-end">
                                                       <div><iconify-icon icon="solar:clock-circle-bold-duotone" class="fs-28 text-primary"></iconify-icon></div>
                                                       <div>
                                                            <h5 class="mb-1"><?= date('d/m/Y', strtotime($date_creation ?? date('Y-m-d'))) ?></h5>
                                                            <p class="mb-0">Date d'inscription</p>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="col-lg-3 col-6">
                                                  <div class="d-flex align-items-center gap-2 border-end">
                                                       <div><iconify-icon icon="solar:calendar-bold-duotone" class="fs-28 text-primary"></iconify-icon></div>
                                                       <div>
                                                            <h5 class="mb-1"><?= $derniere_connexion ? date('d/m/Y', strtotime($derniere_connexion)) : 'Jamais' ?></h5>
                                                            <p class="mb-0">Dernière connexion</p>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="col-lg-3 col-6">
                                                  <div class="d-flex align-items-center gap-2">
                                                       <div><iconify-icon icon="solar:shield-check-bold-duotone" class="fs-28 text-primary"></iconify-icon></div>
                                                       <div>
                                                            <h5 class="mb-1"><?= $email_verifie == 1 ? 'Vérifié' : 'Non vérifié' ?></h5>
                                                            <p class="mb-0">Email</p>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="col-lg-3 col-6">
                                                  <div class="d-flex align-items-center gap-2">
                                                       <div><iconify-icon icon="solar:phone-bold-duotone" class="fs-28 text-primary"></iconify-icon></div>
                                                       <div>
                                                            <h5 class="mb-1"><?= $telephone_verifie == 1 ? 'Vérifié' : 'Non vérifié' ?></h5>
                                                            <p class="mb-0">Téléphone</p>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-xl-3 col-lg-4">
                              <div class="card">
                                   <div class="card-header">
                                        <h4 class="card-title">Informations personnelles</h4>
                                   </div>
                                   <div class="card-body">
                                        <div class="">
                                             <div class="d-flex align-items-center gap-2 mb-2">
                                                  <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                                       <iconify-icon icon="solar:user-bold-duotone" class="fs-20 text-secondary"></iconify-icon>
                                                  </div>
                                                  <p class="mb-0 fs-14"><?= $role ?? 'Utilisateur' ?></p>
                                             </div>
                                             <div class="d-flex align-items-center gap-2 mb-2">
                                                  <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                                       <iconify-icon icon="solar:letter-bold-duotone" class="fs-20 text-secondary"></iconify-icon>
                                                  </div>
                                                  <p class="mb-0 fs-14">Email: <span class="text-dark fw-semibold"><?= $email ?></span></p>
                                             </div>
                                             <div class="d-flex align-items-center gap-2 mb-2">
                                                  <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                                       <iconify-icon icon="solar:phone-bold-duotone" class="fs-20 text-secondary"></iconify-icon>
                                                  </div>
                                                  <p class="mb-0 fs-14">Téléphone: <span class="text-dark fw-semibold"><?= $telephone ?? 'Non renseigné' ?></span></p>
                                             </div>
                                             <div class="d-flex align-items-center gap-2 mb-2">
                                                  <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                                       <iconify-icon icon="solar:global-bold-duotone" class="fs-20 text-secondary"></iconify-icon>
                                                  </div>
                                                  <p class="mb-0 fs-14">Langue: <span class="text-dark fw-semibold">Français</span></p>
                                             </div>
                                             <div class="d-flex align-items-center gap-2">
                                                  <div class="avatar-sm bg-light d-flex align-items-center justify-content-center rounded">
                                                       <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-20 text-secondary"></iconify-icon>
                                                  </div>
                                                  <p class="mb-0 fs-14">Statut: 
                                                       <span class="badge <?= $est_actif == 1 ? 'bg-success' : 'bg-danger' ?>">
                                                            <?= $est_actif == 1 ? 'Actif' : 'Inactif' ?>
                                                       </span>
                                                  </p>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <div class="col-xl-12">
                              <div class="card">
                                   <div class="card-header">
                                        <h4 class="card-title">À propos</h4>
                                   </div>
                                   <div class="card-body">
                                        <p>Bienvenue sur votre espace personnel ABEMARKET. Vous êtes connecté en tant que <strong><?= $role ?></strong>.</p>
                                        <p>Votre compte a été créé le <strong><?= date('d/m/Y à H:i', strtotime($date_creation)) ?></strong>.</p>
                                        <?php if($derniere_connexion): ?>
                                        <p>Dernière connexion : <strong><?= date('d/m/Y à H:i', strtotime($derniere_connexion)) ?></strong></p>
                                        <?php endif; ?>
                                   </div>
                              </div>
                         </div>
                    </div>

               </div>

               <?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
          </div>
     </div>

     <!-- Modal Modifier Profil -->
     <div class="modal fade" id="editProfileModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title">Modifier mon profil</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('Profile/update') ?>" method="POST" enctype="multipart/form-data">
                         <div class="modal-body">
                              <div class="mb-3 text-center">
                                   <img src="<?= base_url($avatar_url ?? 'assets/images/users/avatar-default.jpg') ?>" alt="" class="avatar-xl rounded-circle border mb-2" id="avatarPreview" style="width:100px;height:100px;object-fit:cover">
                                   <br>
                                   <label class="btn btn-outline-primary btn-sm">
                                        <i class="bx bx-camera"></i> Changer photo
                                        <input type="file" name="avatar" accept="image/*" hidden class="avatar-input">
                                   </label>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Prénom</label>
                                   <input type="text" class="form-control" name="prenom" value="<?= $prenom ?>" required>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Nom</label>
                                   <input type="text" class="form-control" name="nom" value="<?= $nom ?>" required>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Email</label>
                                   <input type="email" class="form-control" name="email" value="<?= $email ?>" required>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Téléphone</label>
                                   <input type="tel" class="form-control" name="telephone" value="<?= $telephone ?>">
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

     <!-- Modal Changer Mot de Passe -->
     <div class="modal fade" id="changePasswordModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                    <div class="modal-header">
                         <h5 class="modal-title">Changer mon mot de passe</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?= base_url('Profile/changepassword') ?>" method="POST">
                         <div class="modal-body">
                              <div class="mb-3">
                                   <label class="form-label">Mot de passe actuel</label>
                                   <input type="password" class="form-control" name="current_password" required>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Nouveau mot de passe</label>
                                   <input type="password" class="form-control" name="new_password" required>
                              </div>
                              <div class="mb-3">
                                   <label class="form-label">Confirmer le mot de passe</label>
                                   <input type="password" class="form-control" name="confirm_password" required>
                              </div>
                         </div>
                         <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                              <button type="submit" class="btn btn-primary">Changer</button>
                         </div>
                    </form>
               </div>
          </div>
     </div>

  

      <script>
      $(document).on('change', '.avatar-input', function() {
          var file = this.files[0];
          if (file) {
              var reader = new FileReader();
              reader.onload = function(e) {
                  $('#avatarPreview').attr('src', e.target.result);
              };
              reader.readAsDataURL(file);
          }
      });
      </script>

      <?php if ($msg = $this->session->flashdata('success')): ?>
      <script>
           Swal.fire({ title: 'Succès!', text: '<?= $msg ?>', icon: 'success', confirmButtonText: 'OK' });
      </script>
      <?php endif; ?>

      <?php if ($msg = $this->session->flashdata('error')): ?>
      <script>
           Swal.fire({ title: 'Erreur!', text: '<?= $msg ?>', icon: 'error', confirmButtonText: 'OK' });
      </script>
      <?php endif; ?>

