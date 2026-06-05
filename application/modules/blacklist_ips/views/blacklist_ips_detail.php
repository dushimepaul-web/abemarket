<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<div class="row">
<div class="col-xl-12">

<div class="card">

<div class="card-header">

<h4>Détail IP blacklistée</h4>

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>ID</th>
<td><?= $ip->id_blacklist ?></td>

</tr>

<tr>

<th>Adresse IP</th>
<td>
<strong><?= $ip->adresse_ip ?></strong>
</td>

</tr>

<tr>

<th>Raison</th>
<td><?= $ip->raison ?></td>

</tr>

<tr>

<th>Date fin</th>
<td>

<?php if($ip->date_fin): ?>

<?= date('d/m/Y H:i',
strtotime($ip->date_fin)) ?>

<?php else: ?>

<span class="badge bg-danger">
Permanent
</span>

<?php endif; ?>

</td>

</tr>

<tr>

<th>Date création</th>

<td>

<?= date('d/m/Y H:i',
strtotime($ip->date_creation)) ?>

</td>

</tr>

</table>

<a href="<?= base_url('Blacklist_ips/edit/'.$ip->id_blacklist) ?>"
class="btn btn-warning">

Modifier

</a>

<a href="<?= base_url('Blacklist_ips') ?>"
class="btn btn-secondary">

Retour

</a>

</div>

</div>

</div>
</div>

</div>
</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>