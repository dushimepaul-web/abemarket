<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<form method="post"
action="<?= base_url('Notifications/update/'.$notification->id_notification) ?>">

<div class="card">

<div class="card-header">

<h4>Modifier notification</h4>

</div>

<div class="card-body">

<label>Utilisateur</label>

<select name="id_utilisateur"
class="form-control">

<?php foreach($utilisateurs as $u): ?>

<option value="<?= $u->id_utilisateur ?>"
<?= $u->id_utilisateur == $notification->id_utilisateur ? 'selected':'' ?>>

<?= $u->nom ?>

</option>

<?php endforeach; ?>

</select>

<br>

<label>Type canal</label>

<select name="type_canal"
class="form-control">

<option value="sms"
<?= $notification->type_canal=='sms'?'selected':'' ?>>

SMS

</option>

<option value="push"
<?= $notification->type_canal=='push'?'selected':'' ?>>

Push

</option>

<option value="in_app"
<?= $notification->type_canal=='in_app'?'selected':'' ?>>

In App

</option>

</select>

<br>

<label>Catégorie</label>

<select name="categorie"
class="form-control">

<option value="commande"
<?= $notification->categorie=='commande'?'selected':'' ?>>

Commande

</option>

<option value="paiement"
<?= $notification->categorie=='paiement'?'selected':'' ?>>

Paiement

</option>

<option value="livraison"
<?= $notification->categorie=='livraison'?'selected':'' ?>>

Livraison

</option>

<option value="securite"
<?= $notification->categorie=='securite'?'selected':'' ?>>

Sécurité

</option>

</select>

<br>

<label>Titre</label>

<input type="text"
name="titre"
value="<?= $notification->titre ?>"
class="form-control">

<br>

<label>Message</label>

<textarea name="message"
class="form-control">

<?= $notification->message ?>

</textarea>

</div>

<div class="card-footer">

<button class="btn btn-success">

Mettre à jour

</button>

<a href="<?= base_url('Notifications') ?>"
class="btn btn-secondary">

Retour

</a>

</div>

</div>

</form>

</div>
</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>