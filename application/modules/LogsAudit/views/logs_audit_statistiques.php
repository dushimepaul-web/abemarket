<?php include VIEWPATH . 'includes/backend/Header.php'; ?>
<?php include VIEWPATH . 'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH . 'includes/backend/Topheader.php'; ?>

<div class="page-content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="card-title flex-grow-1">
                            <iconify-icon icon="solar:chart-bold-duotone" class="me-2"></iconify-icon>
                            Statistiques des logs
                        </h4>
                        <div class="d-flex gap-2">
                            <a href="<?= base_url('logs-audit') ?>" class="btn btn-sm btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Total logs -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-primary text-center">
                                    <h3 class="mb-0"><?= number_format($total_logs) ?></h3>
                                    <p class="mb-0">Total des logs enregistrés</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-4">
                            <!-- Par type d'action -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:chart-2-bold-duotone" class="me-2"></iconify-icon>Par type d'action</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="bg-light">
                                                    <tr><th>Action</th><th>Nombre</th><th>%</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $total = array_sum(array_column($stats_par_action, 'total'));
                                                    foreach ($stats_par_action as $s): 
                                                        $pourcent = $total > 0 ? round($s->total / $total * 100, 1) : 0;
                                                    ?>
                                                        <tr>
                                                            <td><?= $s->type_action ?></td>
                                                            <td><?= number_format($s->total) ?></td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar" style="width: <?= $pourcent ?>%"><?= $pourcent ?>%</div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Par table cible -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:database-bold-duotone" class="me-2"></iconify-icon>Par table cible</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="bg-light">
                                                    <tr><th>Table</th><th>Nombre</th><th>%</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $total = array_sum(array_column($stats_par_table, 'total'));
                                                    foreach ($stats_par_table as $s): 
                                                        $pourcent = $total > 0 ? round($s->total / $total * 100, 1) : 0;
                                                    ?>
                                                        <tr>
                                                            <td><?= $s->table_cible ?></td>
                                                            <td><?= number_format($s->total) ?></td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar" style="width: <?= $pourcent ?>%"><?= $pourcent ?>%</div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-4 mt-2">
                            <!-- Par jour (derniers 30 jours) -->
                            <div class="col-md-6">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:calendar-bold-duotone" class="me-2"></iconify-icon>Activité par jour (30 derniers jours)</h6>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="dailyChart" height="200"></canvas>
                                        <div class="table-responsive mt-3">
                                            <table class="table table-sm">
                                                <thead class="bg-light">
                                                    <tr><th>Date</th><th>Logs</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($stats_par_jour as $s): ?>
                                                        <tr>
                                                            <td><?= date('d/m/Y', strtotime($s->date)) ?></td>
                                                            <td><?= number_format($s->total) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Top utilisateurs -->
                            <div class="col-md-6">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><iconify-icon icon="solar:users-group-rounded-bold-duotone" class="me-2"></iconify-icon>Top 10 utilisateurs les plus actifs</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="bg-light">
                                                    <tr><th>Utilisateur</th><th>Nombre d'actions</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($stats_par_utilisateur as $s): ?>
                                                        <tr>
                                                            <td><?= $s->nom ?: 'Système' ?></td>
                                                            <td><?= number_format($s->total) ?></td>
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
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique journalier
var ctx = document.getElementById('dailyChart').getContext('2d');
var dailyData = <?= json_encode($stats_par_jour) ?>;

new Chart(ctx, {
    type: 'line',
    data: {
        labels: dailyData.map(d => d.date),
        datasets: [{
            label: 'Nombre de logs',
            data: dailyData.map(d => d.total),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});
</script>

<?php include VIEWPATH . 'includes/backend/Footer.php'; ?>