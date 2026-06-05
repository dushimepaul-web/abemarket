<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>
<div class="page-content">

<div class="container-fluid">
<h4>Générer OTP</h4>

<form method="post" action="<?= base_url('Codes_otp/save') ?>">

<div class="form-group">
<label>Utilisateur</label>
<select name="id_utilisateur" class="form-control">
<?php foreach($utilisateurs as $u): ?>
<option value="<?= $u->id_utilisateur ?>"><?= $u->nom ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="form-group">
<label>Code OTP</label>
<input type="text" name="code" class="form-control">
</div>

<div class="form-group">
<label>Type OTP</label>
<select name="type_otp" class="form-control">
<option value="connexion">Connexion</option>
<option value="verification_telephone">Téléphone</option>
<option value="verification_email">Email</option>
</select>
</div>

<div class="form-group">
<label>Téléphone</label>
<input type="text" name="telephone" class="form-control">
</div>

<div class="form-group">
<label>Email</label>
<input type="email" name="email" class="form-control">
</div>

<div class="form-group">
<label>Date expiration</label>
<input type="datetime-local" name="date_expiration" class="form-control">
</div>

<button class="btn btn-success mt-3">Enregistrer</button>

</form>

</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>