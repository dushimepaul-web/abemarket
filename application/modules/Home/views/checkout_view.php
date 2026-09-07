<section class="checkout-section-new section-t-space">
 <div class="custom-container">
  <h2>Finaliser la commande</h2>
  <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div><?php endif; ?>
  <form method="post" action="<?= base_url('checkout/process') ?>" class="row g-4">
   <div class="col-lg-7"><div class="checkout-left-box"><div class="billing-box checkbox-bg-color">
    <h4>Adresse de livraison</h4><div class="row g-3">
     <div class="col-md-6"><label>Nom complet *</label><input class="form-control" name="nom_complet" required value="<?= htmlspecialchars($this->session->userdata('nom_complet') ?? '') ?>"></div>
     <div class="col-md-6"><label>Téléphone Mobile Money *</label><input class="form-control" name="telephone" required></div>
     <div class="col-md-6"><label>Province *</label><select class="form-select" name="province" id="province" required><option value="">Choisir une province</option><?php foreach ($provinces as $province): ?><option value="<?= $province['id_province'] ?>"><?= htmlspecialchars($province['nom']) ?></option><?php endforeach; ?></select></div>
     <div class="col-md-6"><label>Commune *</label><select class="form-select" name="commune" id="commune" required disabled><option value="">Choisir d'abord une province</option></select></div>
     <div class="col-md-4"><label>Quartier *</label><select class="form-select" name="quartier" id="quartier" required disabled><option value="">Choisir d'abord une commune</option></select></div>
     <div class="col-md-4"><label>Zone *</label><select class="form-select" name="zone" id="zone" required disabled><option value="">Choisir d'abord une commune</option></select></div>
     <div class="col-md-4"><label>Colline *</label><select class="form-select" name="colline" id="colline" required disabled><option value="">Choisir d'abord une zone</option></select></div>
     <div class="col-12"><label>Adresse *</label><input class="form-control" name="adresse" required placeholder="Avenue, numéro de maison"></div>
     <div class="col-12"><label>Point de repère</label><input class="form-control" name="point_repere" placeholder="Près de..."></div>
     <div class="col-12"><label>Note de livraison</label><textarea class="form-control" name="note" rows="3"></textarea></div>
    </div>
   </div></div></div>
   <div class="col-lg-5"><div class="checkout-right-box checkbox-bg-color">
    <h4>Votre commande</h4>
    <?php foreach ($cartItems as $item): ?><div class="d-flex justify-content-between border-bottom py-2"><span><?= htmlspecialchars($item['nom_produit']) ?> × <?= (int)$item['quantite'] ?></span><strong><?= number_format($item['sous_total'], 0, ',', ' ') ?> BIF</strong></div><?php endforeach; ?>
    <div class="d-flex justify-content-between pt-3"><span>Sous-total</span><strong><?= number_format($subtotal, 0, ',', ' ') ?> BIF</strong></div>
    <div class="d-flex justify-content-between py-2"><span>Livraison</span><strong><?= number_format($frais_livraison, 0, ',', ' ') ?> BIF</strong></div>
    <div class="d-flex justify-content-between border-top pt-3 fs-5"><span>Total</span><strong><?= number_format($total, 0, ',', ' ') ?> BIF</strong></div>
    <div class="mt-4"><label>Paiement Mobile Money *</label>
     <?php foreach ($paymentMethods as $method): ?><?php if ($method['type'] === 'mobile_money'): ?><label class="d-block border rounded p-3 mb-2"><input type="radio" name="payment_method" value="<?= $method['id_mode_payement'] ?>" required> <strong><?= htmlspecialchars($method['description']) ?></strong><?php if (!empty($method['instructions'])): ?><small class="d-block text-muted mt-1"><?= htmlspecialchars($method['instructions']) ?></small><?php endif; ?></label><?php endif; ?><?php endforeach; ?>
    </div>
    <p class="small text-muted mt-3">La préparation débute seulement après confirmation du paiement.</p>
    <button type="submit" class="btn theme-bg-color text-white rounded-pill w-100">Passer la commande et payer</button>
   </div></div>
  </form>
 </div>
</section>
<script>
document.getElementById('province').addEventListener('change', async function () {
 const commune = document.getElementById('commune');
 const quartier = document.getElementById('quartier');
 const zone = document.getElementById('zone');
 const colline = document.getElementById('colline');
 
 commune.disabled = true; commune.innerHTML = '<option value="">Chargement...</option>';
 quartier.disabled = true; quartier.innerHTML = '<option value="">Choisir d\'abord une commune</option>';
 zone.disabled = true; zone.innerHTML = '<option value="">Choisir d\'abord une commune</option>';
 colline.disabled = true; colline.innerHTML = '<option value="">Choisir d\'abord une zone</option>';

 if (!this.value) {
     commune.innerHTML = '<option value="">Choisir d\'abord une province</option>';
     return;
 }

 const response = await fetch('<?= base_url('home/get_communes') ?>', {method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams({province_id: this.value})});
 const communes = await response.json(); 
 commune.innerHTML = '<option value="">Choisir une commune</option>';
 communes.forEach(item => commune.insertAdjacentHTML('beforeend', '<option value="' + item.id_commune + '">' + item.nom + '</option>')); 
 commune.disabled = false;
});

document.getElementById('commune').addEventListener('change', async function () {
 const quartier = document.getElementById('quartier');
 const zone = document.getElementById('zone');
 const colline = document.getElementById('colline');

 quartier.disabled = true; quartier.innerHTML = '<option value="">Chargement...</option>';
 zone.disabled = true; zone.innerHTML = '<option value="">Chargement...</option>';
 colline.disabled = true; colline.innerHTML = '<option value="">Choisir d\'abord une zone</option>';

 if (!this.value) {
     quartier.innerHTML = '<option value="">Choisir d\'abord une commune</option>';
     zone.innerHTML = '<option value="">Choisir d\'abord une commune</option>';
     return;
 }

 // Fetch Quartiers
 const respQ = await fetch('<?= base_url('home/get_quartiers') ?>', {method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams({commune_id: this.value})});
 const quartiers = await respQ.json();
 quartier.innerHTML = '<option value="">Choisir un quartier</option>';
 quartiers.forEach(item => quartier.insertAdjacentHTML('beforeend', '<option value="' + item.id_quartier + '">' + item.nom + '</option>'));
 quartier.disabled = false;

 // Fetch Zones
 const respZ = await fetch('<?= base_url('home/get_zones') ?>', {method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams({commune_id: this.value})});
 const zones = await respZ.json();
 zone.innerHTML = '<option value="">Choisir une zone</option>';
 zones.forEach(item => zone.insertAdjacentHTML('beforeend', '<option value="' + item.id_zone + '">' + item.nom + '</option>'));
 zone.disabled = false;
});

document.getElementById('zone').addEventListener('change', async function () {
 const colline = document.getElementById('colline');
 colline.disabled = true; colline.innerHTML = '<option value="">Chargement...</option>';

 if (!this.value) {
     colline.innerHTML = '<option value="">Choisir d\'abord une zone</option>';
     return;
 }

 const response = await fetch('<?= base_url('home/get_collines') ?>', {method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams({zone_id: this.value})});
 const collines = await response.json();
 colline.innerHTML = '<option value="">Choisir une colline</option>';
 collines.forEach(item => colline.insertAdjacentHTML('beforeend', '<option value="' + item.id_colline + '">' + item.nom + '</option>'));
 colline.disabled = false;
});
</script>
