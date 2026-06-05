<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<div class="card-header">
    <h4>Ajouter un souhait</h4>
</div>

<div class="card-body">

<form method="post" action="<?= base_url('Liste_souhaits/save') ?>">

<!-- utilisateur -->
<div class="mb-3">
<label>Utilisateur</label>
<select name="id_utilisateur" class="form-control" required>
    <option value="">Choisir</option>
    <?php foreach($utilisateurs as $u): ?>
        <option value="<?= $u->id_utilisateur ?>">
            <?= $u->nom ?>
        </option>
    <?php endforeach; ?>
</select>
</div>

<!-- produit -->
<div class="mb-3">
<label>Produit</label>
<select name="id_produit" class="form-control" required>
    <option value="">Choisir</option>
    <?php foreach($produits as $p): ?>
        <option value="<?= $p->id_produit ?>">
            <?= $p->nom_produit ?>
        </option>
    <?php endforeach; ?>
</select>
</div>

<button class="btn btn-success">
    Enregistrer
</button>

</form>

</div>

</div>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>