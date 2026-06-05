<?php include VIEWPATH.'includes/backend/Header.php'; ?>
<?php include VIEWPATH.'includes/backend/Sidebar.php'; ?>
<?php include VIEWPATH.'includes/backend/Topheader.php'; ?>

<div class="page-content">
<div class="container-fluid">

<div class="card">

<div class="card-header">

<h4>Modifier évaluation</h4>

</div>

<form method="post"
action="<?= base_url('Evaluations_vendeurs/update/'.$evaluation->id_evaluation) ?>">

<div class="card-body">

<label>Note globale</label>

<select name="note_globale"
class="form-control">

<?php for($i=1;$i<=5;$i++): ?>

<option value="<?= $i ?>"
<?= $evaluation->note_globale==$i?'selected':'' ?>>

<?= $i ?> étoile(s)

</option>

<?php endfor; ?>

</select>

<br>

<label>Commentaire</label>

<textarea name="commentaire"
class="form-control">

<?= $evaluation->commentaire ?>

</textarea>

<br>

<label>Approuvé</label>

<select name="est_approuve"
class="form-control">

<option value="0"
<?= !$evaluation->est_approuve?'selected':'' ?>>

Non

</option>

<option value="1"
<?= $evaluation->est_approuve?'selected':'' ?>>

Oui

</option>

</select>

</div>

<div class="card-footer">

<button class="btn btn-primary">
Mettre à jour
</button>

</div>

</form>

</div>

</div>
</div>

<?php include VIEWPATH.'includes/backend/Footer.php'; ?>