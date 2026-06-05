<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">

<div class="container-fluid">

<div class="card-header">
<h4>Modifier configuration</h4>
</div>

<form method="post"
action="<?= base_url('Config_paiement_vendeur/update/'.$config->id_config) ?>">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label>Vendeur</label>

<select name="id_vendeur"
class="form-control">

<?php foreach($vendeurs as $v): ?>

<option value="<?= $v->id_vendeur ?>"
<?= ($v->id_vendeur==$config->id_vendeur)?'selected':'' ?>>

<?= $v->nom_boutique ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6">

<label>Méthode</label>

<select name="methode_principale"
class="form-control">

<option value="mobile_money"
<?= ($config->methode_principale=='mobile_money')?'selected':'' ?>>

Mobile Money

</option>

<option value="virement_bancaire"
<?= ($config->methode_principale=='virement_bancaire')?'selected':'' ?>>

Virement bancaire

</option>

</select>

</div>

<div class="col-md-6 mt-3">

<label>Numéro Mobile</label>

<input type="text"
name="numero_mobile_money"
value="<?= $config->numero_mobile_money ?>"
class="form-control">

</div>

<div class="col-md-6 mt-3">

<label>Numéro Compte</label>

<input type="text"
name="numero_compte"
value="<?= $config->numero_compte ?>"
class="form-control">

</div>

</div>

</div>

<div class="card-footer">

<button class="btn btn-success">

Mettre à jour

</button>

</div>

</form>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>