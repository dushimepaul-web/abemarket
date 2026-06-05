<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>
<div class="page-content">

<div class="container-fluid">

<div class="card">

<div class="card-header d-flex justify-content-between">
<h4 class="card-title">
Configurations Paiement Vendeurs
</h4>

<a href="<?= base_url('Config_paiement_vendeur/ajouter') ?>"
class="btn btn-primary">

Ajouter configuration

</a>
</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead>
<tr>

<th>ID</th>
<th>Vendeur</th>
<th>Méthode</th>
<th>Mobile </th>


<th>Vérifié</th>
<th>Actif</th>
<th>Actions</th>

</tr>
</thead>

<tbody>

<?php foreach($configs as $c): ?>

<tr>

<td><?= $c->id_config ?></td>

<td><?= $c->nom_boutique ?></td>

<td><?= $c->methode_principale ?></td>

<td>

<?php if($c->methode_principale=='mobile_money'): ?>

<?= $c->numero_mobile_money ?>

<?php else: ?>

<?= $c->numero_compte ?>

<?php endif; ?>

</td>

<td>

<?php if($c->est_verifie): ?>

<span class="badge bg-success">
Vérifié
</span>

<?php else: ?>

<span class="badge bg-warning">
Non vérifié
</span>

<?php endif; ?>

</td>

<td>

<?php if($c->est_actif): ?>

<span class="badge bg-success">
Actif
</span>

<?php else: ?>

<span class="badge bg-danger">
Inactif
</span>

<?php endif; ?>

</td>

<td>

<a href="<?= base_url('Config_paiement_vendeur/detail/'.$c->id_config) ?>"
class="btn btn-info btn-sm">

Voir

</a>

<a href="<?= base_url('Config_paiement_vendeur/edit/'.$c->id_config) ?>"
class="btn btn-primary btn-sm">

Modifier

</a>

<a href="<?= base_url('Config_paiement_vendeur/verifier/'.$c->id_config) ?>"
class="btn btn-success btn-sm">

Vérifier

</a>

<a href="<?= base_url('Config_paiement_vendeur/toggle/'.$c->id_config) ?>"
class="btn btn-warning btn-sm">

Activer/Désactiver

</a>

<a href="<?= base_url('Config_paiement_vendeur/delete/'.$c->id_config) ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Supprimer ?')">

Supprimer

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>
</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>