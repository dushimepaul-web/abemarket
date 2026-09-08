<section class="section-t-space">
 <div class="custom-container"><div class="row justify-content-center"><div class="col-lg-7">
  <div class="card p-4">
   <h2>Paiement Mobile Money</h2>
   <?php if ($this->session->flashdata('success')): ?><div class="alert alert-success"><?= htmlspecialchars($this->session->flashdata('success'), ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
   <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
   <p>Commande <strong><?= htmlspecialchars($payment['numero_commande']) ?></strong> — montant à payer : <strong><?= number_format($payment['montant'], 0, ',', ' ') ?> BIF</strong>.</p>
   <p>Mode : <strong><?= htmlspecialchars($payment['description']) ?></strong></p>
   <?php if (!empty($payment['instructions'])): ?><div class="alert alert-info"><?= nl2br(htmlspecialchars($payment['instructions'])) ?></div><?php endif; ?>
   <p>Référence AbeMarket : <code><?= htmlspecialchars($payment['reference_interne']) ?></code></p>
   <form method="post" action="<?= base_url('payment/submit-reference') ?>">
    <input type="hidden" name="reference_interne" value="<?= htmlspecialchars($payment['reference_interne']) ?>">
    <label class="form-label">Référence donnée par Mobile Money *</label>
    <input class="form-control" name="reference_operateur" required value="<?= htmlspecialchars($payment['reference_operateur'] ?? '') ?>">
    <button class="btn theme-bg-color text-white mt-3" type="submit">Envoyer la référence pour vérification</button>
   </form>
   <p class="text-muted mt-3 mb-0">Sans API, un administrateur confirme le paiement après vérification. La commande ne peut pas être préparée avant cette confirmation.</p>
  </div>
 </div></div></div>
</section>
