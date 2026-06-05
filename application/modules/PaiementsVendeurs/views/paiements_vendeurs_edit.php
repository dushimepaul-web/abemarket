<form method="post" action="<?= base_url('Paiements_vendeurs/update/'.$paiement->id_paiement) ?>">

<div class="card">

<div class="card-header">
<h4>Modifier paiement</h4>
</div>

<div class="card-body">

<label>Statut</label>
<select name="statut" class="form-control">
<option value="en_attente" <?= $paiement->statut=='en_attente'?'selected':'' ?>>En attente</option>
<option value="en_cours" <?= $paiement->statut=='en_cours'?'selected':'' ?>>En cours</option>
<option value="paye" <?= $paiement->statut=='paye'?'selected':'' ?>>Payé</option>
<option value="echoue" <?= $paiement->statut=='echoue'?'selected':'' ?>>Échoué</option>
</select>

<label>Référence transaction</label>
<input type="text" name="reference_transaction" class="form-control"
value="<?= $paiement->reference_transaction ?>">

</div>

<div class="card-footer">
<button class="btn btn-primary">Modifier</button>
</div>

</div>

</form>