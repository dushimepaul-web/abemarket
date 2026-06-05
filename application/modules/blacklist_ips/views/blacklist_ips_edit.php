<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<div class="row">
<div class="col-xl-12">

<div class="card">

<div class="card-header">

<h4>Modifier IP blacklist</h4>

</div>

<div class="card-body">

<form method="post"
action="<?= base_url('Blacklist_ips/update/'.$ip->id_blacklist) ?>">

<div class="mb-3">

<label>Adresse IP</label>

<input type="text"
name="adresse_ip"
class="form-control"
value="<?= $ip->adresse_ip ?>"
required>

</div>

<div class="mb-3">

<label>Raison</label>

<input type="text"
name="raison"
class="form-control"
value="<?= $ip->raison ?>">

</div>

<div class="mb-3">

<label>Date fin blocage</label>

<input type="datetime-local"
name="date_fin"
class="form-control"
value="<?= $ip->date_fin ? date('Y-m-d\TH:i', strtotime($ip->date_fin)) : '' ?>">

</div>

<button class="btn btn-primary">

Mettre à jour

</button>

<a href="<?= base_url('Blacklist_ips') ?>"
class="btn btn-secondary">

Annuler

</a>

</form>

</div>

</div>

</div>
</div>

</div>
</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>