<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">

<div class="container-fluid">

<h4 class="mb-3">Mon Panier</h4>

<div class="row">

<!-- LISTE PANIER -->
<div class="col-md-8">

<div class="card">

<div class="card-body table-responsive">

<table class="table table-bordered align-middle">

<thead>
<tr>
<th>Produit</th>
<th>Prix</th>
<th>Quantité</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php 
$total_general = 0;
foreach($panier as $p): 

$prix = $p->prix ?? 0;
$total = $prix * $p->quantite;
$total_general += $total;
?>

<tr>

<td>
<strong><?= $p->nom_produit ?></strong>
</td>

<td>
<?= number_format($prix, 2) ?> F
</td>

<td style="width:120px;">

<form method="post" action="<?= base_url('Paniers/update/'.$p->id_panier) ?>">

<input type="number"
       name="quantite"
       value="<?= $p->quantite ?>"
       min="1"
       class="form-control form-control-sm">

<button class="btn btn-sm btn-primary mt-1 w-100">
Update
</button>

</form>

</td>

<td>
<strong><?= number_format($total, 2) ?> F</strong>
</td>

<td>

<a href="<?= base_url('Paniers/delete/'.$p->id_panier) ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Supprimer ce produit ?')">

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

<!-- RESUME -->
<div class="col-md-4">

<div class="card">

<div class="card-header">
<h5>Résumé commande</h5>
</div>

<div class="card-body">

<p>
<strong>Total produits :</strong>
<?= count($panier) ?>
</p>

<p>
<strong>Total général :</strong><br>
<span style="font-size:22px;color:green;">
<?= number_format($total_general, 2) ?> F
</span>
</p>

<hr>

<form method="post" action="<?= base_url('Commandes/create_from_cart') ?>">

<input type="hidden" name="id_utilisateur" value="<?= $panier[0]->id_utilisateur ?? '' ?>">

<button class="btn btn-success w-100">
Valider la commande
</button>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>