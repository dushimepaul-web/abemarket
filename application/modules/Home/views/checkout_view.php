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
     <div class="col-12"><label>Adresse *</label><input class="form-control" name="adresse" required placeholder="Quartier, avenue, numéro de maison"></div>
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
 const commune = document.getElementById('commune'); commune.disabled = true; commune.innerHTML = '<option>Chargement...</option>';
 const response = await fetch('<?= base_url('home/get_communes') ?>', {method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: new URLSearchParams({province_id: this.value})});
 const communes = await response.json(); commune.innerHTML = '<option value="">Choisir une commune</option>';
 communes.forEach(item => commune.insertAdjacentHTML('beforeend', '<option value="' + item.id_commune + '">' + item.nom + '</option>')); commune.disabled = false;
});
</script>
