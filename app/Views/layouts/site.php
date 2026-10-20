<?php
/**
 * Public site layout.
 * Expects: $title, $description, $content
 */
$pageTitle = isset($title) && $title ? $title : setting('company_name');
$pageDescription = isset($description) && $description ? $description : setting('company_slogan');
$announcement = array_values(array_filter([
    setting('announcement_1', __('announcement.1')),
    setting('announcement_2', __('announcement.2')),
    setting('announcement_3', __('announcement.3')),
]));
$flashes = App\Core\Session::pullFlashes();
?>
<!DOCTYPE html>
<html lang="<?= e(setting('site_language', 'en')) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDescription) ?>">
    <link rel="icon" href="<?= e(asset('images/favicon.svg')) ?>" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">
</head>
<body class="d-flex flex-column min-vh-100">

<?php if ($announcement): ?>
<div class="announcement-bar">
    <div class="container d-flex flex-wrap justify-content-center justify-content-md-between align-items-center gap-2 py-1">
        <?php foreach ($announcement as $item): ?>
            <span class="announcement-item"><?= icon('check', 14) ?> <?= e($item) ?></span>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<header class="site-header sticky-top">
    <div class="container">
        <div class="header-main d-flex align-items-center gap-3 py-3">
            <a class="brand d-flex align-items-center gap-2 text-decoration-none" href="<?= e(url('/')) ?>">
                <img src="<?= e(upload_url(setting('company_logo', 'assets/images/logo.svg'))) ?>" alt="<?= e(setting('company_name')) ?> logo" class="brand-logo" width="46" height="46" onerror="this.src='<?= e(asset('images/logo.svg')) ?>'">
                <span class="brand-text">
                    <span class="brand-name"><?= e(setting('company_name')) ?></span>
                    <span class="brand-slogan"><?= e(setting('company_slogan')) ?></span>
                </span>
            </a>

            <form class="header-search d-none d-md-flex" action="<?= e(url('products')) ?>" method="get" role="search">
                <label class="visually-hidden" for="header-search-input"><?= __('common.search') ?></label>
                <input id="header-search-input" type="search" name="q" class="form-control" placeholder="<?= e(__('common.search_products')) ?>" value="<?= e($_GET['q'] ?? '') ?>">
                <button class="btn btn-search" type="submit" aria-label="<?= e(__('common.search')) ?>"><?= icon('search', 18) ?></button>
            </form>

            <div class="header-actions d-flex align-items-center gap-3 ms-md-auto">
                <a class="header-action" href="<?= e(url('admin/login')) ?>">
                    <?= icon('headset', 22) ?>
                    <span><?= __('nav.login') ?></span>
                </a>
                <a class="header-action position-relative" href="<?= e(url('quote')) ?>">
                    <?= icon('quote', 22) ?>
                    <span><?= __('common.request_quote') ?></span>
                </a>
                <button class="btn menu-toggle d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                    <?= icon('menu', 24) ?>
                </button>
            </div>
        </div>
        <nav id="mainNav" class="main-nav collapse navbar-collapse" aria-label="Main navigation">
            <?php
            $navRaw = setting('nav_links', "Home|/\nProducts|/products\nServices|/services\nImport Process|/import-process\nGallery|/gallery\nNews & Articles|/news\nAbout Us|/about\nContact Us|/contact");
            $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
            $navItems = [];
            foreach (preg_split('/\r?\n/', $navRaw) as $line) {
                $parts = array_map('trim', explode('|', $line, 2));
                if (count($parts) === 2 && $parts[0] !== '' && $parts[1] !== '') {
                    $navItems[] = $parts;
                }
            }
            ?>
            <ul class="navbar-nav mx-auto">
                <?php foreach ($navItems as [$label, $path]): ?>
                    <?php $isActive = $path === '/' ? (rtrim($currentPath, '/') === rtrim(BASE_URL, '/')) : strpos($currentPath, BASE_URL . rtrim($path, '/')) !== false; ?>
                    <li class="nav-item">
                        <a class="nav-link<?= $isActive ? ' active' : '' ?>" href="<?= e(url($path)) ?>"><?= e($label) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a class="btn btn-nav-cta d-none d-lg-inline-flex" href="<?= e(url('quote')) ?>"><?= icon('quote', 16) ?> <?= e(__('common.free_quote')) ?></a>
        </nav>

        <form class="header-search d-md-none mb-3" action="<?= e(url('products')) ?>" method="get" role="search">
            <label class="visually-hidden" for="header-search-input-m"><?= __('common.search') ?></label>
            <input id="header-search-input-m" type="search" name="q" class="form-control" placeholder="<?= e(__('common.search_products')) ?>" value="<?= e($_GET['q'] ?? '') ?>">
            <button class="btn btn-search" type="submit" aria-label="<?= e(__('common.search')) ?>"><?= icon('search', 18) ?></button>
        </form>
    </div>
</header>

<?php foreach ($flashes as $flash): ?>
    <div class="container mt-3">
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : e($flash['type']) ?> alert-dismissible fade show" role="alert">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endforeach; ?>

<main class="flex-grow-1">
    <?= $content ?>
</main>
<footer class="site-footer mt-5">
    <div class="container">
        <div class="row g-4 py-5">
            <div class="col-12 col-lg-4">
                <div class="footer-brand d-flex align-items-center gap-2 mb-3">
                    <img src="<?= e(upload_url(setting('company_logo', 'assets/images/logo.svg'))) ?>" alt="" width="40" height="40" onerror="this.src='<?= e(asset('images/logo.svg')) ?>'">
                    <span><?= e(setting('company_name')) ?></span>
                </div>
                <p class="footer-about"><?= e(setting('footer_about', __('footer.about_text'))) ?></p>
                <div class="footer-social d-flex gap-2 mt-3">
                    <?php
                    $socials = [
                        'facebook'  => setting('social_facebook'),
                        'instagram' => setting('social_instagram'),
                        'linkedin'  => setting('social_linkedin'),
                        'youtube'   => setting('social_youtube'),
                        'whatsapp'  => setting('social_whatsapp'),
                    ];
                    ?>
                    <?php foreach ($socials as $network => $socialUrl): ?>
                        <?php if ($socialUrl): ?>
                            <a class="social-link" href="<?= e($socialUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e(ucfirst($network)) ?>">
                                <?php if ($network === 'whatsapp'): ?><?= icon('whatsapp', 16) ?><?php else: ?><span aria-hidden="true"><?= e(strtoupper(substr($network, 0, 1))) ?></span><?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-6 col-lg-2">
                <h5 class="footer-title"><?= __('footer.quick_links') ?></h5>
                <ul class="footer-links">
                    <?php foreach (array_slice($navItems, 0, 8) as [$label, $path]): ?>
                        <li><a href="<?= e(url($path)) ?>"><?= e($label) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h5 class="footer-title"><?= __('footer.categories_title') ?></h5>
                <ul class="footer-links">
                    <?php foreach (\App\Models\Category::withCounts() as $cat): ?>
                        <li><a href="<?= e(url('products')) ?>?category=<?= (int)$cat['id'] ?>"><?= e($cat['name']) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= e(url('products')) ?>"><?= __('common.all') ?> <?= __('common.products') ?></a></li>
                </ul>
            </div>

            <div class="col-12 col-lg-4">
                <h5 class="footer-title"><?= __('footer.contact_title') ?></h5>
                <ul class="footer-contact">
                    <li><?= icon('pin', 16) ?> <span><?= e(setting('office_address')) ?></span></li>
                    <li><?= icon('phone', 16) ?> <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></li>
                    <li><?= icon('whatsapp', 16) ?> <a href="<?= e(setting('whatsapp_link')) ?>" target="_blank" rel="noopener noreferrer"><?= e(setting('whatsapp')) ?></a></li>
                    <li><?= icon('mail', 16) ?> <a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></li>
                    <li><?= icon('clock', 16) ?> <span><?= e(setting('working_hours')) ?></span></li>
                </ul>
                <div class="newsletter-box mt-4">
                    <h6 class="mb-1"><?= __('footer.newsletter_title') ?></h6>
                    <p class="small mb-2"><?= e(setting('newsletter_text', __('footer.newsletter_text'))) ?></p>
                    <form class="newsletter-form d-flex gap-2" action="<?= e(url('ajax/newsletter')) ?>" method="post" data-ajax="newsletter" novalidate>
                        <?= csrf_field() ?>
                        <label class="visually-hidden" for="newsletter-email"><?= __('common.email_address') ?></label>
                        <input id="newsletter-email" type="email" name="email" class="form-control form-control-sm" placeholder="<?= e(__('common.email_address')) ?>" required>
                        <button class="btn btn-sm btn-newsletter" type="submit"><?= __('common.subscribe') ?></button>
                    </form>
                    <p class="form-feedback small mt-2 mb-0" aria-live="polite"></p>
                </div>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
            <p class="mb-0 small">&copy; <?= date('Y') ?> <?= e(setting('company_name')) ?>. <?= __('footer.rights') ?></p>
            <div class="footer-legal small">
                <a href="<?= e(url('about')) ?>"><?= __('footer.privacy') ?></a>
                <span class="mx-2">·</span>
                <a href="<?= e(url('about')) ?>"><?= __('footer.terms') ?></a>
                <span class="mx-2">·</span>
                <a href="<?= e(url('contact')) ?>"><?= __('common.contact') ?></a>
            </div>
        </div>
        <?php if (setting('show_demo_disclaimer', '1') === '1'): ?>
            <p class="footer-disclaimer text-center pb-3 mb-0 small"><?= e(__('footer.disclaimer')) ?></p>
        <?php endif; ?>
    </div>
</footer>

<script src="<?= e(asset('vendor/js/bootstrap.bundle.min.js')) ?>"></script>
<script src="<?= e(asset('js/site.js')) ?>"></script>
</body>
</html>
