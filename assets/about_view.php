<!-- about_view.php -->
<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2><?= $about_content['hero_title']['title'] ?? 'À propos de nous'; ?></h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">À propos</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- About Section Start -->
<div class="about-us-section section-t-space">
    <div class="custom-container ratio_66">
        <div>
            <div class="top-about-content">
                <h2><?= $settings['site_name'] ?? 'ABEMARKET'; ?> : <?= $about_content['hero_title']['content'] ?? 'Votre partenaire e-commerce au Burundi'; ?></h2>
                <p><?= $about_content['hero_text']['content'] ?? ''; ?></p>
                <p><?= $about_content['hero_text_2']['content'] ?? ''; ?></p>
            </div>
            <div>
                <img src="<?= base_url($about_content['hero_text_2']['image_url'] ?? 'assets/frontend/images/inner-page/contact-us.jpg'); ?>" alt="À propos de <?= $settings['site_name'] ?? 'ABEMARKET'; ?>" class="img-fluid">
            </div>
        </div>
    </div>
    <div class="business-agenda">
        <div class="row g-sm-4 g-3">
            <div class="col-xl-4 col-md-6">
                <h3><?= $about_content['excellence_title']['title'] ?? 'Des années d\'excellence en e-commerce'; ?></h3>
                <p><?= $about_content['excellence_text']['content'] ?? ''; ?></p>
            </div>
            <div class="col-xl-4 col-md-6">
                <h3><?= $about_content['innovation_title']['title'] ?? 'Un héritage d\'innovation digitale'; ?></h3>
                <p><?= $about_content['innovation_text']['content'] ?? ''; ?></p>
            </div>
            <div class="col-xl-4 col-md-6">
                <h3><?= $about_content['success_title']['title'] ?? 'Construire des histoires de succès'; ?></h3>
                <p><?= $about_content['success_text']['content'] ?? ''; ?></p>
            </div>
        </div>
    </div>
</div>
<!-- About Section End -->

<!-- Team Section Start -->
<?php if (!empty($team_members)): ?>
<section class="team-section section-t-space section-b-space">
    <div class="custom-container">
        <div class="testimonial-title text-center">
            <h2><?= $about_content['team_title']['title'] ?? 'Notre Équipe'; ?></h2>
            <p><?= $about_content['team_title']['content'] ?? 'Nous sommes une équipe passionnée de développeurs, de designers et de résolveurs de problèmes qui adorent transformer les idées en produits digitaux puissants.'; ?></p>
        </div>
        <div class="swiper user-slider">
            <div class="swiper-wrapper">
                <?php foreach ($team_members as $member): ?>
                <div class="swiper-slide">
                    <div class="team-box">
                        <div class="team-image">
                            <img src="<?= base_url($member['photo_url']); ?>" class="img-fluid" alt="<?= htmlspecialchars($member['prenom'] . ' ' . $member['nom'], ENT_QUOTES, 'UTF-8'); ?>">
                            <ul class="team-media">
                                <?php if (!empty($member['facebook_url'])): ?>
                                <li><a href="<?= htmlspecialchars($member['facebook_url'], ENT_QUOTES, 'UTF-8'); ?>" class="fb-bg" target="_blank"><i class="ri-facebook-fill"></i></a></li>
                                <?php endif; ?>
                                <?php if (!empty($member['instagram_url'])): ?>
                                <li><a href="<?= htmlspecialchars($member['instagram_url'], ENT_QUOTES, 'UTF-8'); ?>" class="insta-bg" target="_blank"><i class="ri-instagram-fill"></i></a></li>
                                <?php endif; ?>
                                <?php if (!empty($member['linkedin_url'])): ?>
                                <li><a href="<?= htmlspecialchars($member['linkedin_url'], ENT_QUOTES, 'UTF-8'); ?>" class="linkedin-bg" target="_blank"><i class="ri-linkedin-box-fill"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="team-name">
                            <h3><?= htmlspecialchars($member['prenom'] . ' ' . $member['nom'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><?= htmlspecialchars($member['poste'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<?php endif; ?>
<!-- Team Section End -->

<!-- Testimonial Section Start -->
<?php if (!empty($testimonials)): ?>
<section class="testimonial-section overflow-hidden">
    <div class="custom-container">
        <div class="row g-sm-4 g-3">
            <div class="col-lg-4">
                <div class="testimonial-title">
                    <h2><?= $about_content['testimonial_title']['title'] ?? 'Témoignages'; ?></h2>
                    <p><?= $about_content['testimonial_title']['content'] ?? 'Découvrez ce que nos clients disent de nous.'; ?></p>
                    <div>
                        <div class="slider-btn slidePrev-btn"><i class="ri-arrow-left-s-line"></i></div>
                        <div class="slider-btn slideNext-btn"><i class="ri-arrow-right-s-line"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="swiper testimonial-swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($testimonials as $testimonial): ?>
                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <svg><use xlink:href="<?= base_url('assets/frontend/svg/quote.svg#quote'); ?>"></use></svg>
                                <p><?= htmlspecialchars($testimonial['message'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <div class="testimonial-user">
                                    <img src="<?= base_url($testimonial['photo_url'] ?? 'assets/frontend/images/user/default.jpg'); ?>" alt="<?= htmlspecialchars($testimonial['prenom'] . ' ' . $testimonial['nom'], ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid">
                                    <div>
                                        <h3><?= htmlspecialchars($testimonial['prenom'] . ' ' . $testimonial['nom'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <h4><?= htmlspecialchars($testimonial['poste'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<!-- Testimonial Section End -->