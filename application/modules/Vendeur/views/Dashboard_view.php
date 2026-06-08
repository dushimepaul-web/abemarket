<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
     <div class="container-fluid">
          <div class="row">
               <div class="col-12">
                    <div class="alert alert-primary text-truncate mb-3" role="alert">
                         Tableau de bord Vendeur - Gérez votre boutique et vos ventes
                    </div>
               </div>

               <div class="col-md-3">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:t-shirt-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Mes Produits</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_products ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('Produits') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
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
                               <a href="<?= base_url('Commande') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-3">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:dollar-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Mes Revenus</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_revenue ?? 0, 0, ',', ' '); ?> BIF</h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                               <a href="<?= base_url('soldes-vendeurs') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-3">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:shop-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Ma Boutique</p>
                                        <h3 class="text-dark mt-1 mb-0">Boutique</h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                               <a href="<?= base_url('Profile') ?>" class="text-reset fw-semibold fs-12">Gérer ma boutique</a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
