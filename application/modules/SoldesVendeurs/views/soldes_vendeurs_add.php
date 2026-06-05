<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">

<div class="container-fluid">

<div class="card">

<div class="card-header">

<h4>Ajouter solde vendeur</h4>

</div>

<form method="post"
action="<?= base_url('Soldes_vendeurs/save') ?>">

<div class="card-body">

<label>Vendeur</label>

<select name="id_vendeur"
class="form-control"
required>

<option value="">Choisir vendeur</option>

<?php foreach($vendeurs as $v): ?>

<option value="<?= $v->id_vendeur ?>">

<?= $v->nom_vendeur ?>

</option>

<?php endforeach; ?>

</select>

<br>

<label>Solde disponible</label>

<input type="number"
step="0.01"
name="solde_disponible"
class="form-control">

<br>

<label>Solde en attente</label>

<input type="number"
step="0.01"
name="solde_en_attente"
class="form-control">

<br>

<label>Total gagné</label>

<input type="number"
step="0.01"
name="total_gagne"
class="form-control">

<br>

<label>Total retiré</label>

<input type="number"
step="0.01"
name="total_retire"
class="form-control">

</div>

<div class="card-footer">

<button class="btn btn-success">

Enregistrer

</button>

</div>

</form>

</div>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>