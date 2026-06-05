<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">

<div class="container-fluid">

<div class="row">

<div class="col-12">
<h4>Détails retour #<?= $retour->id_retour ?></h4>
</div>

<div class="card-body">

<p>Commande : <?= $retour->id_commande ?></p>
<p>Type : <?= $retour->type ?></p>
<p>Motif : <?= $retour->motif ?></p>
<p>Description : <?= $retour->description ?></p>
<p>Montant demandé : <?= $retour->montant_demande ?></p>
<p>Montant approuvé : <?= $retour->montant_approuve ?></p>
<p>Statut : <?= $retour->statut ?></p>

</div>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>