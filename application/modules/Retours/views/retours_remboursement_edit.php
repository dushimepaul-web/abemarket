<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">

<div class="container-fluid">

<div class="row">

<div class="col-12">
<h4>Traitement retour</h4>
</div>

<div class="card-body">

<form method="post" action="<?= base_url('Retours_remboursement/update/'.$retour->id_retour) ?>">

<select name="statut" class="form-control mb-2">
<option value="en_cours">En cours</option>
<option value="approuve">Approuver</option>
<option value="refuse">Refuser</option>
<option value="rembourse">Remboursé</option>
</select>

<input name="montant_approuve" class="form-control mb-2" placeholder="Montant approuvé">

<textarea name="motif_refus" class="form-control mb-2" placeholder="Motif refus"></textarea>

<button class="btn btn-primary">Mettre à jour</button>

</form>

</div>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>