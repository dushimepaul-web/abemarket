<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
     <div class="container-fluid">
          <div class="row">
               <div class="col-12">
                    <div class="alert alert-primary text-truncate mb-3" role="alert">
                         Tableau de bord Client - Suivez vos achats et commandes
                    </div>
               </div>

               <div class="col-md-3">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:bag-smile-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Mes Commandes</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_orders ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('Commandes') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-3">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:cart-3-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Mon Panier</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($cart_count ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('List') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-3">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:heart-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Ma Wishlist</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($wishlist_count ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('liste-souhaits') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-3">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:map-point-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Mes Adresses</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($address_count ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('Adresse') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
