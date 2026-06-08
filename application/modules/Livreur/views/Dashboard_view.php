<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
     <div class="container-fluid">
          <div class="row">
               <div class="col-12">
                    <div class="alert alert-primary text-truncate mb-3" role="alert">
                         Tableau de bord Livreur - Gérez vos livraisons
                    </div>
               </div>

               <div class="col-md-4">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:delivery-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Livraisons en cours</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($ongoing_deliveries ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('Commandes') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-4">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:check-circle-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Livraisons effectuées</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($completed_deliveries ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('Commandes') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-4">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:bell-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Notifications</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($notifications ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('Notifications') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
