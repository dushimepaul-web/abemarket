<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<div class="row">
<div class="col-xl-12">

<div class="card">

<div class="card-header d-flex justify-content-between">

<h4 class="card-title">
Liste des IP blacklistées
</h4>

<a href="<?= base_url('Blacklist_ips/ajouter') ?>"
   class="btn btn-primary">

Ajouter IP

</a>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover table-bordered">

<thead class="table-light">

<tr>

<th>ID</th>
<th>Adresse IP</th>
<th>Raison</th>
<th>Date fin</th>
<th>Date création</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php if(!empty($ips)): ?>

<?php foreach($ips as $ip): ?>

<tr>

<td><?= $ip->id_blacklist ?></td>

<td>
<strong>
<?= $ip->adresse_ip ?>
</strong>
</td>

<td>
<?= $ip->raison ?>
</td>

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

<td>

<?= date('d/m/Y H:i',
strtotime($ip->date_creation)) ?>

</td>

<td>

<div class="d-flex gap-2">

<a href="<?= base_url('Blacklist_ips/detail/'.$ip->id_blacklist) ?>"
   class="btn btn-sm btn-info">

Voir

</a>

<a href="<?= base_url('Blacklist_ips/edit/'.$ip->id_blacklist) ?>"
   class="btn btn-sm btn-warning">

Modifier

</a>

<a href="<?= base_url('Blacklist_ips/delete/'.$ip->id_blacklist) ?>"
   class="btn btn-sm btn-danger"
   onclick="return confirm('Supprimer cette IP ?')">

Supprimer

</a>

</div>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="6"
class="text-center">

Aucune IP blacklistée

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>
</div>

</div>
</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>