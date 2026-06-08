<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
     <div class="container-fluid">
          <div class="row">
               <div class="col-12">
                    <div class="alert alert-primary text-truncate mb-3" role="alert">
                         Tableau de bord Finance - Suivi des transactions et paiements
                    </div>
               </div>

               <div class="col-md-4">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:card-transfer-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Transactions</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_transactions ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('TransactionsPaiement') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-4">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:wallet-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Paiements Vendeurs</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($pending_payments ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('PaiementsVendeurs') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>

               <div class="col-md-4">
                    <div class="card overflow-hidden">
                         <div class="card-body">
                              <div class="row">
                                   <div class="col-6">
                                        <div class="avatar-md bg-soft-primary rounded">
                                             <iconify-icon icon="solar:card-send-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                        </div>
                                   </div>
                                   <div class="col-6 text-end">
                                        <p class="text-muted mb-0 text-truncate">Virements</p>
                                        <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_transfers ?? 0, 0, ',', ' '); ?></h3>
                                   </div>
                              </div>
                         </div>
                         <div class="card-footer py-2 bg-light bg-opacity-50">
                              <a href="<?= base_url('PaiementsVendeurs') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>
