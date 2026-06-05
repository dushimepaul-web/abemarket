<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <iconify-icon icon="solar:chart-2-bold-duotone" class="me-2"></iconify-icon>
                            Statistiques des paiements
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Statistiques par mode de paiement -->
                        <div class="mb-4">
                            <h5>📊 Par mode de paiement</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Mode de paiement</th>
                                            <th>Nombre transactions</th>
                                            <th>Montant total</th>
                                            <th>%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total_montant = 0;
                                        foreach ($stats_par_mode as $s) {
                                            $total_montant += $s->montant_total;
                                        }
                                        foreach ($stats_par_mode as $s): 
                                            $pourcent = $total_montant > 0 ? ($s->montant_total / $total_montant) * 100 : 0;
                                        ?>
                                            <tr>
                                                <td><?= $s->mode_paiement ?></td>
                                                <td><?= number_format($s->total) ?></td>
                                                <td><strong><?= number_format($s->montant_total, 0, ',', ' ') ?> FBu</strong></td>
                                                <td><?= round($pourcent, 1) ?>%</div>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td><strong><?= array_sum(array_column($stats_par_mode, 'total')) ?></strong></div>
                                            <td><strong><?= number_format($total_montant, 0, ',', ' ') ?> FBu</strong></div>
                                            <td><strong>100%</strong></div>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Statistiques par type -->
                        <div class="mb-4">
                            <h5>💰 Par type de transaction</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Type</th>
                                            <th>Nombre</th>
                                            <th>Montant total</th>
                                            <th>Montant moyen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stats_par_type as $s): ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    switch($s->type_transaction) {
                                                        case 'paiement': echo '💰 Paiement'; break;
                                                        case 'remboursement': echo '↩️ Remboursement'; break;
                                                        case 'virement_vendeur': echo '🏦 Virement vendeur'; break;
                                                    }
                                                    ?>
                                                </div>
                                                <td><?= number_format($s->total) ?></div>
                                                <td><strong><?= number_format($s->montant_total, 0, ',', ' ') ?> FBu</strong></div>
                                                <td><?= number_format($s->montant_moyen, 0, ',', ' ') ?> FBu</div>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Évolution mensuelle -->
                        <div class="mb-4">
                            <h5>📈 Évolution mensuelle (<?= date('Y') ?>)</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Mois</th>
                                            <th>Nombre transactions</th>
                                            <th>Paiements</th>
                                            <th>Remboursements</th>
                                            <th>Montant total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                                        foreach ($stats_mensuelles as $s): 
                                            $mois = $months[$s->mois - 1];
                                        ?>
                                            <tr>
                                                <td><?= $mois ?></td>
                                                <td><?= number_format($s->total) ?></td>
                                                <td><?= number_format($s->paiements, 0, ',', ' ') ?> FBu</div>
                                                <td><?= number_format($s->remboursements, 0, ',', ' ') ?> FBu</div>
                                                <td><strong><?= number_format($s->montant_total, 0, ',', ' ') ?> FBu</strong></div>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>