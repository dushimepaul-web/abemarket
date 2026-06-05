<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<form method="post"
action="<?= base_url('Transactions_paiement/update/'.$transaction->id_transaction) ?>">

<div class="card">

<div class="card-header">

<h4>Update transaction</h4>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<label>Commande</label>

<select name="id_commande"
class="form-control">

<option value="">Sélectionner</option>

<?php foreach($commandes as $c): ?>

<option value="<?= $c->id_commande ?>">

<?= $c->reference_commande ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6">

<label>Utilisateur</label>

<select name="id_utilisateur"
class="form-control" required>

<?php foreach($utilisateurs as $u): ?>

<option value="<?= $u->id_utilisateur ?>">

<?= $u->nom ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mt-3">

<label>Mode paiement</label>

<select name="id_mode_payement"
class="form-control" required>

<?php foreach($modes as $m): ?>

<option value="<?= $m->id_mode_payement ?>">

<?= $m->description ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="col-md-6 mt-3">

<label>Type transaction</label>

<select name="type_transaction"
class="form-control">

<option value="paiement">Paiement</option>
<option value="remboursement">Remboursement</option>
<option value="virement_vendeur">Virement vendeur</option>

</select>

</div>

<div class="col-md-4 mt-3">

<label>Montant</label>

<input type="number"
step="0.01"
name="montant"
class="form-control" value="<?= $transaction->montant ?>"
required>

</div>

<div class="col-md-4 mt-3">

<label>Frais</label>

<input type="number"
step="0.01"
name="frais"
class="form-control"  value="<?= $transaction->frais ?>">

</div>

<div class="col-md-4 mt-3">

<label>Devise</label>

<input type="text"
name="devise"
value="BIF"
class="form-control" value="<?= $transaction->devise ?>">

</div>

<div class="col-md-6 mt-3">

<label>Téléphone payeur</label>

<input type="text"
name="telephone_payeur"
class="form-control"  value="<?= $transaction->telephone_payeur ?>">

</div>

<div class="col-md-6 mt-3">

<label>Nom payeur</label>

<input type="text"
name="nom_payeur"
class="form-control" value="<?= $transaction->nom_payeur ?>">

</div>

<div class="col-md-12 mt-3">

<label>Message statut</label>

<input type="text"
name="message_statut"
class="form-control" value="<?= $transaction->message_statut ?>">

</div>

</div>

</div>

<div class="card-footer">

<button class="btn btn-success">

Enregistrer

</button>

</div>

</div>

</form>

</div>
</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>