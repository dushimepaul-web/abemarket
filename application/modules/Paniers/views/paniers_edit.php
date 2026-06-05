<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">

<div class="container-fluid">

<div class="card-header">
<h4>Modifier panier</h4>
</div>

<div class="card-body">

<form method="post" action="<?= base_url('Paniers/update/'.$panier->id_panier) ?>">

<div class="mb-3">
<label>Quantité</label>
<input type="number" name="quantite" class="form-control" value="<?= $panier->quantite ?>">
</div>

<button class="btn btn-primary">Modifier</button>

</form>

</div>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>