<!-- contact_view.php -->
<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Contactez-nous</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Contact</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Contact Box Section Start -->
<div class="contact-section section-t-space">
    <div class="custom-container">
        <div class="contact-main-box">
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="contact-wrapper">
                        <h3>Contactez-nous</h3>
                        <p class="contact-desc">
                            Vous avez des questions ou besoin d'aide ? Notre équipe est là pour vous aider ! Contactez-nous à tout moment, nous vous répondrons dans les plus brefs délais.
                        </p>
                        
                        <!-- Informations de contact dynamiques -->
                        <?php if (!empty($contact_info)): ?>
                            <?php foreach ($contact_info as $info): ?>
                                <p>
                                    <?php if (!empty($info['icone'])): ?>
                                    <i class="<?= htmlspecialchars($info['icone'], ENT_QUOTES, 'UTF-8'); ?>"></i> 
                                    <?php endif; ?>
                                    <?= htmlspecialchars($info['info_value'], ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><i class="ri-mail-line"></i> <?= $settings['site_email'] ?? 'contact@abemarket.com'; ?></p>
                            <p><i class="ri-phone-line"></i> <?= $settings['site_phone'] ?? '+257 68 86 39 45'; ?></p>
                        <?php endif; ?>
                        
                        <h2 class="contact-title">CONTACT</h2>
                    </div>
                </div>
                <div class="col-xxl-9 col-lg-8 col-md-7">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($this->session->flashdata('success'), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (validation_errors()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= validation_errors(); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form class="contact-right-box" method="post" action="<?= base_url('contact'); ?>">
                        <h3>PARLEZ-NOUS</h3>
                        <p><span>"</span><span class="txt-danger-color">&nbsp;*&nbsp;</span><span>"</span><span class="ps-1">indique les champs requis</span></p>
                        <div class="row g-md-4 g-3">
                            <div class="col-12 theme-form">
                                <label for="contactUsName" class="form-label">Nom <span class="txt-danger-color">*</span></label>
                                <input type="text" class="form-control" id="contactUsName" name="name" placeholder="Votre nom" value="<?= set_value('name', $old_input['name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-6 theme-form">
                                <label for="contactUsEmail" class="form-label">Email <span class="txt-danger-color">*</span></label>
                                <input type="email" class="form-control" id="contactUsEmail" name="email" placeholder="exemple@domaine.com" value="<?= set_value('email', $old_input['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-6 theme-form">
                                <label for="contactUsPhone" class="form-label">Téléphone <span class="txt-danger-color">*</span></label>
                                <input type="tel" class="form-control" id="contactUsPhone" name="phone" placeholder="Numéro de téléphone" value="<?= set_value('phone', $old_input['phone'] ?? ''); ?>" required>
                            </div>
                            <div class="col-12 theme-form">
                                <label class="form-label">Sélectionnez un sujet <span class="txt-danger-color">*</span></label>
                                <select class="form-select" name="topic" required>
                                    <option value="">Sélectionnez...</option>
                                    <?php if (!empty($contact_sujets)): ?>
                                        <?php foreach ($contact_sujets as $sujet): ?>
                                            <option value="<?= htmlspecialchars($sujet['code'], ENT_QUOTES, 'UTF-8'); ?>" <?= set_select('topic', $sujet['code'], (($old_input['topic'] ?? '') == $sujet['code'])); ?>>
                                                <?= htmlspecialchars($sujet['libelle'], ENT_QUOTES, 'UTF-8'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="question">Question générale</option>
                                        <option value="commande">Problème de commande</option>
                                        <option value="livraison">Problème de livraison</option>
                                        <option value="produit">Information produit</option>
                                        <option value="partenariat">Partenariat / Devenir vendeur</option>
                                        <option value="autre">Autre</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-12 theme-form">
                                <label for="contactUsHelpMessage" class="form-label">Comment pouvons-nous vous aider ? <span class="txt-danger-color">*</span></label>
                                <textarea class="form-control" rows="6" id="contactUsHelpMessage" name="message" placeholder="Décrivez votre demande..." required><?= set_value('message', $old_input['message'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="submit" class="btn theme-bg-color text-white">Envoyer le message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Box Section End -->

<!-- Google Maps Section (optionnel) -->
<section class="map-section section-t-space">
    <div class="custom-container">
        <div class="map-box">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d255282.35887787633!2d29.267633685316403!3d-3.3730999999999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19d9e5c5b5b5b5b5%3A0x5b5b5b5b5b5b5b5b!2sBujumbura%2C%20Burundi!5e0!3m2!1sfr!2sbi!4v1234567890123!5m2!1sfr!2sbi" 
                width="100%" 
                height="400" 
                style="border:0; border-radius: 12px;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>
<!-- Google Maps Section End -->