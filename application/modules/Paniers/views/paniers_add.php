<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">

<div class="container-fluid">
<div class="card-header">
<h4>Ajouter au panier</h4>
</div>

<div class="card-body">

<form method="post" action="<?= base_url('Paniers/add') ?>">

<div class="mb-3">
<label>Utilisateur ID</label>
<input type="text" name="id_utilisateur" class="form-control">
</div>

<div class="mb-3">
<label>Produit</label>
<input type="text" name="id_produit" class="form-control">
</div>

<div class="mb-3">
<label>Variante</label>
<input type="text" name="id_variante" class="form-control">
</div>

<div class="mb-3">
<label>Quantité</label>
<input type="number" name="quantite" class="form-control" value="1">
</div>

<button class="btn btn-success">Ajouter</button>

</form>

</div>
</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>