<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">
<div class="row">

<div class="col-12">
<div class="card">

<div class="card-header">
<h4>Nouvelle demande retour</h4>
</div>

<div class="card-body">

<form method="post" action="<?= base_url('Retours_remboursement/save') ?>">

<!-- commande -->
<select name="id_commande" class="form-control mb-2">
<option>Commande</option>
<?php foreach($commandes as $c): ?>
<option value="<?= $c->id_commande ?>">
Commande #<?= $c->id_commande ?>
</option>
<?php endforeach; ?>
</select>

<!-- utilisateur -->
<select name="id_utilisateur" class="form-control mb-2">
<option>Utilisateur</option>
<?php foreach($utilisateurs as $u): ?>
<option value="<?= $u->id_utilisateur ?>">
<?= $u->nom ?>
</option>
<?php endforeach; ?>
</select>

<input name="id_article" class="form-control mb-2" placeholder="ID article">

<select name="type" class="form-control mb-2">
<option value="retour_produit">Retour produit</option>
<option value="remboursement_partiel">Remboursement partiel</option>
</select>

<select name="motif" class="form-control mb-2">
<option value="produit_defectueux">Défectueux</option>
<option value="non_conforme">Non conforme</option>
<option value="erreur_livraison">Erreur livraison</option>
</select>

<textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

<input name="photos_urls" class="form-control mb-2" placeholder="URLs photos">

<input name="montant_demande" class="form-control mb-2" placeholder="Montant">

<select name="methode_remboursement" class="form-control mb-2">
<option value="mobile_money">Mobile Money</option>
<option value="virement">Virement</option>
</select>

<button class="btn btn-success">Envoyer demande</button>

</form>

</div>
</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>