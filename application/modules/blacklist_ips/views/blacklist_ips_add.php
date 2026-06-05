<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<div class="row">
<div class="col-xl-12">

<div class="card">

<div class="card-header">

<h4>Ajouter IP blacklist</h4>

</div>

<div class="card-body">

<form method="post"
action="<?= base_url('Blacklist_ips/save') ?>">

<div class="mb-3">

<label>Adresse IP</label>

<input type="text"
name="adresse_ip"
class="form-control"
placeholder="ex: 192.168.1.1"
required>

</div>

<div class="mb-3">

<label>Raison</label>

<input type="text"
name="raison"
class="form-control">

</div>

<div class="mb-3">

<label>Date fin blocage</label>

<input type="datetime-local"
name="date_fin"
class="form-control">

<small class="text-muted">
Laisser vide pour blocage permanent
</small>

</div>

<button class="btn btn-success">

Enregistrer

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