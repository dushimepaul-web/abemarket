<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">

<div class="container-fluid">

<div class="card-header">
<h4>Ajouter configuration paiement vendeur</h4>
</div>

<form method="post"
action="<?= base_url('Config_paiement_vendeur/save') ?>">

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label>Vendeur</label>

<select name="id_vendeur"
class="form-control" required>

<option value="">
Sélectionner vendeur
</option>

<?php foreach($vendeurs as $v): ?>

<option value="<?= $v->id_vendeur ?>">
<?= $v->nom_boutique ?>
</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6">

<label>Méthode principale</label>

<select name="methode_principale"
class="form-control">

<option value="mobile_money">
Mobile Money
</option>

<option value="virement_bancaire">
Virement bancaire
</option>

</select>

</div>

<div class="col-md-6 mt-3">

<label>Opérateur Mobile</label>

<input type="text"
name="operateur_mobile"
class="form-control">

</div>

<div class="col-md-6 mt-3">

<label>Numéro Mobile Money</label>

<input type="text"
name="numero_mobile_money"
class="form-control">

</div>

<div class="col-md-6 mt-3">

<label>Nom Abonné Mobile</label>

<input type="text"
name="nom_abonne_mobile"
class="form-control">

</div>

<div class="col-md-6 mt-3">

<label>Nom Titulaire</label>

<input type="text"
name="nom_titulaire"
class="form-control">

</div>

<div class="col-md-6 mt-3">

<label>Numéro Compte</label>

<input type="text"
name="numero_compte"
class="form-control">

</div>

<div class="col-md-6 mt-3">

<label>Nom Banque</label>

<input type="text"
name="nom_banque"
class="form-control">

</div>

</div>

</div>

<div class="card-footer">

<button class="btn btn-success">

Enregistrer

</button>

<a href="<?= base_url('Config_paiement_vendeur') ?>"
class="btn btn-secondary">

Retour

</a>

</div>

</form>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>