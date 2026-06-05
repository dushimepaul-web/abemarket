<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>
<div class="page-content">

<div class="container-fluid">

<div class="card-header">
<h4>Détails configuration paiement</h4>
</div>

<div class="card-body">

<ul class="list-group">

<li class="list-group-item">
Méthode : <?= $config->methode_principale ?>
</li>

<li class="list-group-item">
Numéro Mobile :
<?= $config->numero_mobile_money ?>
</li>

<li class="list-group-item">
Compte bancaire :
<?= $config->numero_compte ?>
</li>

<li class="list-group-item">
Banque :
<?= $config->nom_banque ?>
</li>

<li class="list-group-item">
Vérifié :
<?= $config->est_verifie ?>
</li>

<li class="list-group-item">
Actif :
<?= $config->est_actif ?>
</li>

</ul>

</div>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>