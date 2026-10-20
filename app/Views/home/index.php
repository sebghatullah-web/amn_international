<?php
/**
 * Homepage — modern e-commerce layout:
 * hero banner, category strip, promo cards, featured / new / popular
 * product sections, trust strip, services, newsletter band, articles.
 */
$announcement = array_values(array_filter([
    setting('announcement_1', __('announcement.1')),
    setting('announcement_2', __('announcement.2')),
    setting('announcement_3', __('announcement.3')),
]));
$whyItems = [
    ['icon' => 'cert', 'title' => __('why.quality_title'), 'text' => __('why.quality_text')],
    ['icon' => 'globe', 'title' => __('why.network_title'), 'text' => __('why.network_text')],
    ['icon' => 'bolt', 'title' => __('why.price_title'), 'text' => __('why.price_text')],
    ['icon' => 'ship', 'title' => __('why.shipping_title'), 'text' => __('why.shipping_text')],
    ['icon' => 'headset', 'title' => __('why.support_title'), 'text' => __('why.support_text')],
    ['icon' => 'shield', 'title' => __('why.compliance_title'), 'text' => __('why.compliance_text')],
];
?>

<!-- ================= HERO ================= -->
<section class="hero-wrap">
    <div class="container">
        <div class="hero position-relative">
            <img src="<?= e(upload_url(setting('hero_image', ''))) ?>" alt="<?= e(setting('hero_alt', 'AMN International ESD products')) ?>" class="hero-image" onerror="this.src='<?= e(asset('images/hero-fallback.svg')) ?>'" loading="eager">
            <div class="hero-overlay"></div>
            <div class="hero-content text-start text-md-center">
                <span class="hero-eyebrow"><?= icon('leaf', 16) ?> <?= e(setting('hero_eyebrow', __('home.eyebrow'))) ?></span>
                <h1 class="hero-title"><?= e(setting('hero_title', __('home.title'))) ?></h1>
                <p class="hero-description"><?= e(setting('hero_description', __('home.description'))) ?></p>
                <div class="hero-actions d-flex flex-wrap gap-2 mt-4">
                    <a class="btn btn-hero" href="<?= e(url('products')) ?>"><?= e(setting('hero_cta', __('home.shop_now'))) ?> <?= icon('arrow', 16) ?></a>
                    <a class="btn btn-outline-light" href="<?= e(url('quote')) ?>"><?= icon('quote', 16) ?> <?= e(__('common.free_quote')) ?></a>
                </div>
            </div>
            <div class="hero-badges">
                <?php $badgeIcons = ['ship', 'shield', 'headset']; ?>
                <?php foreach (array_slice($announcement, 0, 3) as $i => $item): ?>
                    <span class="hero-badge"><?= icon($badgeIcons[$i] ?? 'check', 18) ?> <?= e($item) ?></span>
                <?php endforeach; ?>
            </div>
            <div class="hero-dots" aria-hidden="true"><span class="active"></span><span></span><span></span></div>
        </div>
    </div>
</section>

<!-- ================= CATEGORY STRIP ================= -->
<section class="section category-strip-section">
    <div class="container">
        <div class="section-header text-center mb-4">
            <span class="section-eyebrow"><?= e(__('home.categories_title')) ?></span>
            <h2 class="section-title"><?= e(setting('categories_title', __('home.categories_subtitle'))) ?></h2>
        </div>
        <div class="row g-3 justify-content-center">
            <?php foreach ($categories as $cat): ?>
                <div class="col-6 col-md-4 col-lg">
                    <a class="cat-tile text-decoration-none" href="<?= e(url('products')) ?>?category=<?= (int)$cat['id'] ?>">
                        <span class="cat-circle">
                            <?php if ($cat['image']): ?>
                                <img src="<?= e(upload_url($cat['image'])) ?>" alt="<?= e($cat['name']) ?>" loading="lazy">
                            <?php else: ?>
                                <?= icon('box', 30) ?>
                            <?php endif; ?>
                        </span>
                        <span class="cat-tile-name"><?= e($cat['name']) ?></span>
                        <?php if (!empty($cat['products_count'])): ?>
                            <span class="cat-tile-count"><?= (int)$cat['products_count'] ?> <?= __('common.products') ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
            <div class="col-6 col-md-4 col-lg">
                <a class="cat-tile cat-tile-all text-decoration-none" href="<?= e(url('products')) ?>">
                    <span class="cat-circle cat-circle-dark"><?= icon('menu', 30) ?></span>
                    <span class="cat-tile-name"><?= __('common.all_categories') ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ================= PROMO CARDS ================= -->
<section class="section promo-section pt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="promo-card promo-dark h-100">
                    <div class="promo-body">
                        <span class="promo-eyebrow"><?= e(__('common.about')) ?></span>
                        <h3 class="promo-title"><?= e(__('home.about_title')) ?></h3>
                        <p class="promo-text"><?= e(mb_strimwidth(setting('about_story', __('home.about_text')), 0, 160, '…')) ?></p>
                        <a class="btn btn-promo" href="<?= e(url('about')) ?>"><?= e(__('home.about_link')) ?> <?= icon('arrow', 15) ?></a>
                    </div>
                    <div class="promo-icon-bg"><?= icon('globe', 150) ?></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="promo-card promo-green h-100">
                    <div class="promo-body">
                        <span class="promo-eyebrow"><?= e(__('common.need_help')) ?></span>
                        <h3 class="promo-title"><?= e(__('quote.title')) ?></h3>
                        <p class="promo-text"><?= e(__('quote.subtitle')) ?></p>
                        <a class="btn btn-promo" href="<?= e(url('quote')) ?>"><?= e(__('common.request_quote')) ?> <?= icon('arrow', 15) ?></a>
                    </div>
                    <div class="promo-icon-bg"><?= icon('quote', 130) ?></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <a class="promo-card promo-cream h-100 text-decoration-none d-block" href="<?= e(url('import-process')) ?>">
                    <div class="promo-body">
                        <span class="promo-eyebrow"><?= e(__('common.import_process')) ?></span>
                        <h3 class="promo-title"><?= e(__('process.title')) ?></h3>
                        <p class="promo-text"><?= e(__('process.subtitle')) ?></p>
                        <span class="btn btn-promo"><?= e(__('process.cta_title')) ?> <?= icon('arrow', 15) ?></span>
                    </div>
                    <div class="promo-icon-bg"><?= icon('ship', 130) ?></div>
                </a>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($featured)): ?>
<!-- ================= FEATURED PRODUCTS ================= -->
<section class="section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div class="section-header mb-0 text-start">
                <span class="section-eyebrow"><?= e(__('admin.flag_featured')) ?></span>
                <h2 class="section-title mb-0"><?= e(setting('featured_title', __('home.featured_title'))) ?></h2>
                <p class="section-subtitle mb-0"><?= e(__('home.featured_subtitle')) ?></p>
            </div>
            <a class="btn btn-outline-primary view-all-link" href="<?= e(url('products')) ?>?flag=featured"><?= e(__('common.view_all')) ?> <?= icon('arrow', 15) ?></a>
        </div>
        <div class="row g-4 product-grid">
            <?php foreach (array_slice($featured, 0, 4) as $p): ?>
                <div class="col-6 col-md-4 col-xl-3"><?php include __DIR__ . '/../partials/product-card.php'; ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($newArrivals)): ?>
<!-- ================= NEW ARRIVALS ================= -->
<section class="section section-tint">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div class="section-header mb-0 text-start">
                <span class="section-eyebrow"><?= e(__('admin.flag_new')) ?></span>
                <h2 class="section-title mb-0"><?= e(setting('new_title', __('home.new_title'))) ?></h2>
                <p class="section-subtitle mb-0"><?= e(__('home.new_subtitle')) ?></p>
            </div>
            <a class="btn btn-outline-primary view-all-link" href="<?= e(url('products')) ?>?flag=new"><?= e(__('common.view_all')) ?> <?= icon('arrow', 15) ?></a>
        </div>
        <div class="row g-4 product-grid">
            <?php foreach (array_slice($newArrivals, 0, 4) as $p): ?>
                <div class="col-6 col-md-4 col-xl-3"><?php include __DIR__ . '/../partials/product-card.php'; ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if (!empty($popular)): ?>
<!-- ================= BEST SELLERS (dark strip) ================= -->
<section class="section bestsellers-section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div class="section-header mb-0 text-start">
                <span class="section-eyebrow light"><?= e(__('admin.flag_popular')) ?></span>
                <h2 class="section-title light mb-0"><?= e(setting('popular_title', __('home.popular_title'))) ?></h2>
                <p class="section-subtitle light mb-0"><?= e(__('home.popular_subtitle')) ?></p>
            </div>
            <a class="btn btn-outline-light view-all-link" href="<?= e(url('products')) ?>?flag=popular"><?= e(__('common.view_all')) ?> <?= icon('arrow', 15) ?></a>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($popular, 0, 5) as $i => $p): ?>
                <div class="col-6 col-md-4 col-lg">
                    <a class="bs-card text-decoration-none" href="<?= e(url('products/' . $p['slug'])) ?>">
                        <span class="bs-rank"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="bs-thumb">
                            <?php if (!empty($p['primary_image'])): ?>
                                <img src="<?= e(upload_url($p['primary_image'])) ?>" alt="<?= e($p['name']) ?>" loading="lazy" onerror="this.src='<?= e(asset('images/fallback-product.svg')) ?>'">
                            <?php else: ?>
                                <?= icon('box', 34) ?>
                            <?php endif; ?>
                        </span>
                        <span class="bs-name"><?= e($p['name']) ?></span>
                        <span class="bs-price">
                            <?php if ($p['is_price_published'] && $p['price_usd'] !== null): ?>
                                <span class="bs-price-now"><?= e(format_price($p['price_usd'])) ?></span>
                            <?php else: ?>
                                <span class="bs-price-now"><?= e(__('products.price_on_request')) ?></span>
                            <?php endif; ?>
                        </span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ================= TRUST STRIP ================= -->
<section class="section trust-section">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($whyItems as $item): ?>
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="trust-item">
                        <span class="trust-icon"><?= icon($item['icon'], 24) ?></span>
                        <h5 class="trust-title"><?= e($item['title']) ?></h5>
                        <p class="trust-text"><?= e($item['text']) ?></p>
                        <a class="trust-link" href="<?= e(url('services')) ?>"><?= e(__('common.read_more')) ?> <?= icon('arrow', 13) ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($services)): ?>
<!-- ================= SERVICES ================= -->
<section class="section section-tint">
    <div class="container">
        <div class="section-header text-center mb-4">
            <span class="section-eyebrow"><?= e(__('common.services')) ?></span>
            <h2 class="section-title"><?= e(__('services.title')) ?></h2>
            <p class="section-subtitle"><?= e(__('services.subtitle')) ?></p>
        </div>
        <div class="row g-4">
            <?php foreach ($services as $s): ?>
                <div class="col-md-4">
                    <div class="service-card h-100">
                        <span class="service-icon"><?= icon($s['icon'] ?: 'check', 26) ?></span>
                        <h5 class="service-title"><?= e($s['name']) ?></h5>
                        <p class="service-text"><?= e(mb_strimwidth(strip_tags($s['description']), 0, 150, '…')) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ================= NEWSLETTER BAND ================= -->
<section class="section newsletter-band-section">
    <div class="container">
        <div class="newsletter-band">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <h3 class="newsletter-title"><?= e(__('footer.newsletter_title')) ?></h3>
                    <p class="newsletter-text"><?= e(setting('newsletter_text', __('footer.newsletter_text'))) ?></p>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form newsletter-form-lg d-flex gap-2" action="<?= e(url('ajax/newsletter')) ?>" method="post" data-ajax="newsletter" novalidate>
                        <?= csrf_field() ?>
                        <label class="visually-hidden" for="home-newsletter-email"><?= __('common.email_address') ?></label>
                        <input id="home-newsletter-email" type="email" name="email" class="form-control" placeholder="<?= e(__('common.email_address')) ?>" required>
                        <button class="btn btn-newsletter" type="submit"><?= e(__('common.subscribe')) ?></button>
                    </form>
                    <p class="form-feedback small mt-2 mb-0" aria-live="polite"></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($articles)): ?>
<!-- ================= NEWS & ARTICLES ================= -->
<section class="section pt-0">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div class="section-header mb-0 text-start">
                <span class="section-eyebrow"><?= e(__('common.news')) ?></span>
                <h2 class="section-title mb-0"><?= e(setting('seo_news_title', __('news.title'))) ?></h2>
            </div>
            <a class="btn btn-outline-primary view-all-link" href="<?= e(url('news')) ?>"><?= e(__('news.view_all')) ?> <?= icon('arrow', 15) ?></a>
        </div>
        <div class="row g-4">
            <?php foreach ($articles as $a): ?>
                <div class="col-md-4">
                    <a class="article-teaser text-decoration-none" href="<?= e(url('news/' . $a['slug'])) ?>">
                        <span class="article-teaser-thumb">
                            <?php if (!empty($a['image'])): ?>
                                <img src="<?= e(upload_url($a['image'])) ?>" alt="<?= e($a['title']) ?>" loading="lazy" onerror="this.src='<?= e(asset('images/fallback-article.svg')) ?>'">
                            <?php else: ?>
                                <img src="<?= e(asset('images/fallback-article.svg')) ?>" alt="" loading="lazy">
                            <?php endif; ?>
                        </span>
                        <span class="article-teaser-body">
                            <?php if (!empty($a['category'])): ?><span class="article-teaser-cat"><?= e($a['category']) ?></span><?php endif; ?>
                            <span class="article-teaser-title"><?= e($a['title']) ?></span>
                            <span class="article-teaser-link"><?= e(__('news.read_more')) ?> <?= icon('arrow', 14) ?></span>
                        </span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ================= CTA ================= -->
<section class="cta-section">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h2 class="cta-title mb-2"><?= e(__('home.cta_title')) ?></h2>
                <p class="cta-text mb-0"><?= e(__('home.cta_text')) ?></p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a class="btn btn-light btn-lg cta-btn" href="<?= e(url('quote')) ?>"><?= icon('quote', 18) ?> <?= e(__('common.request_quote')) ?></a>
            </div>
        </div>
    </div>
</section>

