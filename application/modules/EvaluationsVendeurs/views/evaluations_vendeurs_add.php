<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<div class="card">

<div class="card-header">

<h4>Ajouter une évaluation vendeur</h4>

</div>

<form method="post"
action="<?= base_url('Evaluations_vendeurs/save') ?>">

<div class="card-body">

<label>Vendeur</label>

<select name="id_vendeur"
class="form-control"
required>

<option value="">Choisir vendeur</option>

<?php foreach($vendeurs as $v): ?>

<option value="<?= $v->id_vendeur ?>">
<?= $v->nom_boutique ?>
</option>

<?php endforeach; ?>

</select>

<br>

<label>Utilisateur</label>

<select name="id_utilisateur"
class="form-control"
required>

<option value="">Choisir utilisateur</option>

<?php foreach($utilisateurs as $u): ?>

<option value="<?= $u->id_utilisateur ?>">
<?= $u->nom ?>
</option>

<?php endforeach; ?>

</select>

<br>

<label>Commande</label>

<select name="id_commande"
class="form-control"
required>

<?php foreach($commandes as $c): ?>

<option value="<?= $c->id_commande ?>">
Commande #<?= $c->id_commande ?>
</option>

<?php endforeach; ?>

</select>

<br>

<label>Note globale</label>

<select name="note_globale"
class="form-control">

<?php for($i=1;$i<=5;$i++): ?>

<option value="<?= $i ?>">
<?= $i ?> étoile(s)
</option>

<?php endfor; ?>

</select>

<br>

<label>Commentaire</label>

<textarea name="commentaire"
class="form-control"></textarea>

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