<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<!-- ==================================================== -->
<!-- Start right Content here -->
<!-- ==================================================== -->
<div class="page-content">
     <!-- Start Container Fluid -->
     <div class="container-fluid">
          <!-- Start here.... -->
          <div class="row">
               <div class="col-xxl-5">
                    <div class="row">
                         <div class="col-12">
                              <div class="alert alert-primary text-truncate mb-3" role="alert">
                                   Bienvenue sur le tableau de bord d'ABEMARKET - Gérez votre plateforme e-commerce
                              </div>
                         </div>

                         <!-- Carte: Commandes totales -->
                         <div class="col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-soft-primary rounded">
                                                       <iconify-icon icon="solar:cart-5-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Commandes totales</p>
                                                  <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_orders ?? 0, 0, ',', ' '); ?></h3>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="card-footer py-2 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <span class="text-success"><i class="bx bxs-up-arrow fs-12"></i> 
                                                  <?php echo isset($orders_growth) ? number_format($orders_growth, 1) : '0'; ?>%</span>
                                                  <span class="text-muted ms-1 fs-12">Ce mois</span>
                                             </div>
                                             <a href="<?= base_url('Commandes') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Carte: Utilisateurs totaux -->
                         <div class="col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-soft-primary rounded">
                                                       <i class="bx bx-group avatar-title fs-24 text-primary"></i>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Utilisateurs</p>
                                                  <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_users ?? 0, 0, ',', ' '); ?></h3>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="card-footer py-2 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <span class="text-success"><i class="bx bxs-up-arrow fs-12"></i> 
                                                  <?php echo isset($users_growth) ? number_format($users_growth, 1) : '0'; ?>%</span>
                                                  <span class="text-muted ms-1 fs-12">Ce mois</span>
                                             </div>
                                             <a href="<?= base_url('Utilisateurs') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Carte: Produits -->
                         <div class="col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-soft-primary rounded">
                                                       <i class="bx bx-package avatar-title fs-24 text-primary"></i>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Produits</p>
                                                  <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_products ?? 0, 0, ',', ' '); ?></h3>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="card-footer py-2 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <span class="text-danger"><i class="bx bxs-down-arrow fs-12"></i> 
                                                  <?php echo isset($products_stock_bas) ? number_format($products_stock_bas, 0) : '0'; ?> en stock bas</span>
                                             </div>
                                             <a href="<?= base_url('Produits') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- Carte: Chiffre d'affaires -->
                         <div class="col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-soft-primary rounded">
                                                       <i class="bx bx-dollar-circle avatar-title text-primary fs-24"></i>
                                                  </div>
                                             </div>
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Chiffre d'affaires</p>
                                                  <h3 class="text-dark mt-1 mb-0"><?php echo number_format($total_revenue ?? 0, 0, ',', ' '); ?> BIF</h3>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="card-footer py-2 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <span class="text-success"><i class="bx bxs-up-arrow fs-12"></i> 
                                                  <?php echo isset($revenue_growth) ? number_format($revenue_growth, 1) : '0'; ?>%</span>
                                                  <span class="text-muted ms-1 fs-12">Ce mois</span>
                                             </div>
                                             <a href="<?= base_url('Rapports') ?>" class="text-reset fw-semibold fs-12">Voir plus</a>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="col-xxl-7">
                    <div class="card">
                         <div class="card-body">
                              <div class="d-flex justify-content-between align-items-center">
                                   <h4 class="card-title">Performance des ventes</h4>
                                   <div>
                                        <button type="button" class="btn btn-sm btn-outline-light" data-period="week">Semaine</button>
                                        <button type="button" class="btn btn-sm btn-outline-light" data-period="month">Mois</button>
                                        <button type="button" class="btn btn-sm btn-outline-light active" data-period="year">Année</button>
                                   </div>
                              </div>
                              <div dir="ltr">
                                   <div id="dash-performance-chart" class="apex-charts"></div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-lg-4">
                    <div class="card">
                         <div class="card-body">
                              <h5 class="card-title">Statut des commandes</h5>
                              <div id="orders-status-chart" class="apex-charts mb-2 mt-n2"></div>
                              <div class="row text-center">
                                   <div class="col-4">
                                        <p class="text-muted mb-2">En attente</p>
                                        <h3 class="text-dark mb-3"><?php echo $pending_orders ?? 0; ?></h3>
                                   </div>
                                   <div class="col-4">
                                        <p class="text-muted mb-2">En cours</p>
                                        <h3 class="text-dark mb-3"><?php echo $processing_orders ?? 0; ?></h3>
                                   </div>
                                   <div class="col-4">
                                        <p class="text-muted mb-2">Livrées</p>
                                        <h3 class="text-dark mb-3"><?php echo $delivered_orders ?? 0; ?></h3>
                                   </div>
                              </div>
                              <div class="text-center">
                                   <a href="<?= base_url('Commandes') ?>" class="btn btn-light shadow-none w-100">Voir les détails</a>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="col-lg-4">
                    <div class="card">
                         <div class="card-body">
                              <h5 class="card-title">Top catégories</h5>
                              <div id="top-categories-chart" class="apex-charts mb-2 mt-n2"></div>
                              <div class="table-responsive mt-3">
                                   <table class="table table-sm">
                                        <tbody>
                                             <?php if(!empty($top_categories)): ?>
                                                  <?php foreach($top_categories as $cat): ?>
                                                  <tr>
                                                       <td><?php echo $cat['nom_categorie']; ?></td>
                                                       <td class="text-end"><?php echo $cat['nombre_produits']; ?> produits</td>
                                                  </tr>
                                                  <?php endforeach; ?>
                                             <?php else: ?>
                                                  <tr><td colspan="2" class="text-center">Aucune catégorie</td></tr>
                                             <?php endif; ?>
                                        </tbody>
                                   </table>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="col-lg-4">
                    <div class="card card-height-100">
                         <div class="card-header d-flex align-items-center justify-content-between gap-2">
                              <h4 class="card-title flex-grow-1">Derniers utilisateurs</h4>
                              <a href="<?= base_url('Utilisateurs') ?>" class="btn btn-sm btn-soft-primary">Voir tout</a>
                         </div>
                         <div class="table-responsive">
                              <table class="table table-hover table-nowrap table-centered m-0">
                                   <thead class="bg-light bg-opacity-50">
                                        <tr>
                                             <th class="text-muted ps-3">Utilisateur</th>
                                             <th class="text-muted">Email</th>
                                             <th class="text-muted">Date</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php if(!empty($recent_users)): ?>
                                             <?php foreach($recent_users as $user): ?>
                                             <tr>
                                                  <td class="ps-3">
                                                       <div class="d-flex align-items-center gap-2">
                                                            <div class="avatar-xs">
                                                                 <div class="avatar-title bg-soft-primary rounded-circle text-primary fs-14">
                                                                      <?php echo strtoupper(substr($user['prenom'], 0, 1)); ?>
                                                                 </div>
                                                            </div>
                                                            <?php echo $user['prenom'] . ' ' . $user['nom']; ?>
                                                       </div>
                                                  </td>
                                                  <td><?php echo $user['email']; ?></td>
                                                  <td><?php echo date('d/m/Y', strtotime($user['date_creation'])); ?></td>
                                             </tr>
                                             <?php endforeach; ?>
                                        <?php else: ?>
                                             <tr><td colspan="3" class="text-center">Aucun utilisateur</td></tr>
                                        <?php endif; ?>
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col">
                    <div class="card">
                         <div class="card-body">
                              <div class="d-flex align-items-center justify-content-between">
                                   <h4 class="card-title">Dernières commandes</h4>
                                   <a href="<?= base_url('Commandes') ?>" class="btn btn-sm btn-soft-primary">
                                        <i class="bx bx-plus me-1"></i>Créer une commande
                                   </a>
                              </div>
                         </div>
                         <div class="table-responsive table-centered">
                              <table class="table mb-0">
                                   <thead class="bg-light bg-opacity-50">
                                        <tr>
                                             <th class="ps-3">N° Commande</th>
                                             <th>Date</th>
                                             <th>Client</th>
                                             <th>Email</th>
                                             <th>Total</th>
                                             <th>Statut</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        <?php if(!empty($recent_orders)): ?>
                                             <?php foreach($recent_orders as $order): ?>
                                             <tr>
                                                  <td class="ps-3">
                                                       <a href="<?= base_url('Commande/details/'.$order['id_commande']) ?>">#<?php echo $order['numero_commande']; ?></a>
                                                  </td>
                                                  <td><?php echo date('d/m/Y', strtotime($order['date_creation'])); ?></td>
                                                  <td><?php echo $order['prenom'] . ' ' . $order['nom']; ?></td>
                                                  <td><?php echo $order['email']; ?></td>
                                                  <td><?php echo number_format($order['montant_total'], 0, ',', ' '); ?> BIF</td>
                                                  <td>
                                                       <?php
                                                       $status_class = '';
                                                       switch($order['statut_commande']) {
                                                            case 'livre': $status_class = 'success'; break;
                                                            case 'en_attente': $status_class = 'warning'; break;
                                                            case 'confirme': $status_class = 'info'; break;
                                                            case 'expedie': $status_class = 'primary'; break;
                                                            case 'annule': $status_class = 'danger'; break;
                                                            default: $status_class = 'secondary';
                                                       }
                                                       ?>
                                                       <i class="bx bxs-circle text-<?php echo $status_class; ?> me-1"></i>
                                                       <?php echo ucfirst($order['statut_commande']); ?>
                                                  </td>
                                             </tr>
                                             <?php endforeach; ?>
                                        <?php else: ?>
                                             <tr><td colspan="6" class="text-center">Aucune commande trouvée</td></tr>
                                        <?php endif; ?>
                                   </tbody>
                              </table>
                         </div>
                         <div class="card-footer border-top">
                              <div class="row g-3">
                                   <div class="col-sm">
                                        <div class="text-muted">
                                             Affichage de <span class="fw-semibold"><?php echo count($recent_orders ?? []); ?></span>
                                             commandes récentes
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>

<script>
// Graphique de performance des ventes
var salesData = <?php echo json_encode($sales_chart_data ?? []); ?>;
var months = <?php echo json_encode($months_labels ?? []); ?>;

var options = {
    series: [{
        name: 'Ventes',
        data: salesData
    }],
    chart: {
        height: 350,
        type: 'area',
        toolbar: { show: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2 },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.3,
        }
    },
    xaxis: {
        categories: months,
        title: { text: 'Mois' }
    },
    yaxis: {
        title: { text: 'Montant (BIF)' },
        labels: {
            formatter: function(value) {
                return value.toLocaleString() + ' BIF';
            }
        }
    },
    tooltip: {
        y: {
            formatter: function(value) {
                return value.toLocaleString() + ' BIF';
            }
        }
    },
    colors: ['#556ee6']
};

var chart = new ApexCharts(document.querySelector("#dash-performance-chart"), options);
chart.render();

// Graphique des statuts de commandes
var ordersStatusData = [<?php echo $pending_orders ?? 0; ?>, <?php echo $processing_orders ?? 0; ?>, <?php echo $delivered_orders ?? 0; ?>];
var ordersStatusOptions = {
    series: ordersStatusData,
    chart: { type: 'donut', height: 250 },
    labels: ['En attente', 'En cours', 'Livrées'],
    colors: ['#f1b44c', '#50a5f1', '#34c38f'],
    legend: { position: 'bottom' },
    responsive: [{
        breakpoint: 480,
        options: { chart: { width: 200 }, legend: { position: 'bottom' } }
    }]
};

var ordersStatusChart = new ApexCharts(document.querySelector("#orders-status-chart"), ordersStatusOptions);
ordersStatusChart.render();

// Graphique des top catégories
var categoriesData = <?php echo json_encode($categories_chart_data ?? []); ?>;
var categoriesNames = <?php echo json_encode($categories_chart_names ?? []); ?>;

if(categoriesData.length > 0) {
    var categoriesOptions = {
        series: categoriesData,
        chart: { type: 'bar', height: 250, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 4, horizontal: true } },
        dataLabels: { enabled: false },
        xaxis: { categories: categoriesNames, title: { text: 'Nombre de produits' } },
        colors: ['#556ee6']
    };
    var categoriesChart = new ApexCharts(document.querySelector("#top-categories-chart"), categoriesOptions);
    categoriesChart.render();
} else {
    document.querySelector("#top-categories-chart").innerHTML = '<div class="text-center p-4">Aucune donnée disponible</div>';
}
</script>