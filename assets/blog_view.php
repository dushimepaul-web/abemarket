<!-- blog_view.php -->
<!-- Breadcrumb Section Start -->
<section class="breadcrumb-section">
    <div class="custom-container">
        <div class="breadcrumb-contain">
            <h2>Blog & Actualités</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('/'); ?>">
                            <i class="ri-home-3-fill"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Blog</li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Blog Section Start -->
<section class="blog-section section-t-space">
    <div class="custom-container">
        <div class="row g-sm-4 g-3">
            <!-- Sidebar Gauche - Catégories -->
            <div class="col-lg-3">
                <div class="blog-sidebar">
                    <!-- Catégories -->
                    <div class="sidebar-widget">
                        <h4>Catégories</h4>
                        <ul class="category-list">
                            <?php if (!empty($blog_categories)): ?>
                                <?php foreach ($blog_categories as $cat): ?>
                                <li>
                                    <a href="<?= base_url('blog?categorie=' . $cat['slug']); ?>">
                                        <?= htmlspecialchars($cat['nom'], ENT_QUOTES, 'UTF-8'); ?>
                                        <span>(<?= $cat['total_articles']; ?>)</span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li>Aucune catégorie</li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Articles récents -->
                    <div class="sidebar-widget">
                        <h4>Articles récents</h4>
                        <ul class="recent-posts">
                            <?php if (!empty($recent_posts)): ?>
                                <?php foreach ($recent_posts as $post): ?>
                                <li>
                                    <?php if (!empty($post['featured_image'])): ?>
                                    <img src="<?= base_url($post['featured_image']); ?>" alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php endif; ?>
                                    <div class="recent-post-info">
                                        <a href="<?= base_url('blog/' . $post['slug']); ?>">
                                            <?= htmlspecialchars(substr($post['title'], 0, 40), ENT_QUOTES, 'UTF-8'); ?>...
                                        </a>
                                        <span><?= date('d/m/Y', strtotime($post['date_publication'])); ?></span>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Tags populaires -->
                    <div class="sidebar-widget">
                        <h4>Tags populaires</h4>
                        <div class="tag-cloud">
                            <?php if (!empty($popular_tags)): ?>
                                <?php foreach ($popular_tags as $tag => $count): ?>
                                <a href="<?= base_url('blog?tag=' . urlencode($tag)); ?>" class="tag">
                                    <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Bannières sidebar -->
                    <?php if (!empty($blog_banners)): ?>
                        <?php foreach ($blog_banners as $banner): ?>
                        <div class="sidebar-widget banner-widget">
                            <a href="<?= base_url($banner['link'] ?? '#'); ?>">
                                <img src="<?= base_url($banner['image']); ?>" alt="<?= htmlspecialchars($banner['title'] ?? 'Bannière', ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid">
                            </a>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Liste des articles -->
            <div class="col-lg-9">
                <?php if (empty($posts)): ?>
                    <div class="text-center py-5">
                        <i class="ri-article-line" style="font-size: 64px; color: #ccc;"></i>
                        <h4 class="mt-3">Aucun article trouvé</h4>
                        <p>Revenez bientôt pour découvrir nos nouveaux articles.</p>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-xl-3 row-cols-md-2 row-cols-1 g-4">
                        <?php foreach ($posts as $post): ?>
                        <div class="col">
                            <div class="blog-card">
                                <div class="blog-image">
                                    <a href="<?= base_url('blog/' . $post['slug']); ?>">
                                        <img src="<?= base_url(!empty($post['featured_image']) ? $post['featured_image'] : 'assets/frontend/images/blog/default.jpg'); ?>" class="img-fluid" alt="<?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>">
                                    </a>
                                    <div class="blog-date">
                                        <span><?= date('d', strtotime($post['date_publication'])); ?></span>
                                        <span><?= date('M', strtotime($post['date_publication'])); ?></span>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-meta">
                                        <span><i class="ri-user-line"></i> <?= htmlspecialchars($post['auteur_nom'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span><i class="ri-folder-line"></i> <a href="<?= base_url('blog?categorie=' . $post['categorie_slug']); ?>"><?= htmlspecialchars($post['categorie_nom'] ?? 'Non classé', ENT_QUOTES, 'UTF-8'); ?></a></span>
                                        <span><i class="ri-eye-line"></i> <?= $post['views']; ?> vues</span>
                                    </div>
                                    <h3>
                                        <a href="<?= base_url('blog/' . $post['slug']); ?>">
                                            <?= htmlspecialchars(substr($post['title'], 0, 60), ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </h3>
                                    <p><?= htmlspecialchars(substr(strip_tags($post['excerpt'] ?? $post['content']), 0, 100), ENT_QUOTES, 'UTF-8'); ?>...</p>
                                    <a href="<?= base_url('blog/' . $post['slug']); ?>" class="read-more">Lire la suite <i class="ri-arrow-right-line"></i></a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <nav class="custom-pagination mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($current_page <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= base_url('blog?page=' . ($current_page - 1) . ($this->input->get('categorie') ? '&categorie=' . $this->input->get('categorie') : '')); ?>">
                                    <i class="ri-arrow-left-s-line"></i>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?= base_url('blog?page=' . $i . ($this->input->get('categorie') ? '&categorie=' . $this->input->get('categorie') : '')); ?>">
                                    <?= $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= base_url('blog?page=' . ($current_page + 1) . ($this->input->get('categorie') ? '&categorie=' . $this->input->get('categorie') : '')); ?>">
                                    <i class="ri-arrow-right-s-line"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- Blog Section End -->