<?php
/**
 * Admin layout — sidebar + top header + main content.
 */
$flashes = App\Core\Session::pullFlashes();
$user = App\Core\Auth::user();
$navItems = [
    ['label' => __('admin.dashboard'),   'path' => '/admin',                            'icon' => 'grid'],
    ['label' => __('admin.products'),    'path' => '/admin/products',                   'icon' => 'box'],
    ['label' => __('admin.categories'),  'path' => '/admin/categories',                 'icon' => 'tag'],
    ['label' => __('admin.services'),    'path' => '/admin/services',                   'icon' => 'settings'],
    ['label' => __('admin.steps'),       'path' => '/admin/steps',                      'icon' => 'list'],
    ['label' => __('admin.gallery'),     'path' => '/admin/gallery',                    'icon' => 'image'],
    ['label' => __('admin.articles'),    'path' => '/admin/articles',                   'icon' => 'doc'],
    ['label' => __('admin.inquiries'),   'path' => '/admin/inquiries',                  'icon' => 'quote'],
    ['label' => __('admin.subscribers'), 'path' => '/admin/subscribers',                'icon' => 'mail'],
    ['label' => __('admin.settings'),    'path' => '/admin/settings',                   'icon' => 'gear'],
    ['label' => __('admin.users'),       'path' => '/admin/users',                      'icon' => 'headset'],
];
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
?>
<!DOCTYPE html>
<html lang="en" class="admin-html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'AMN International Admin') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="d-flex flex-column min-vh-100 admin-body">
<!-- ================= SIDEBAR ================= -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-brand d-flex align-items-center gap-2 px-3 py-3 border-bottom">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/></svg>
        <span class="fw-bold d-none d-sm-inline admin-sidebar-brand-text"><?= e(setting('company_name')) ?></span>
    </div>
    <nav class="admin-sidebar-nav flex-grow-1 py-3">
        <ul class="list-unstyled ps-0 mb-0">
            <?php foreach ($navItems as $item): ?>
                <?php
                $isActive = $item['path'] === '/admin'
                    ? ($currentPath === rtrim(BASE_URL, '/') . '/admin' || ($currentPath === rtrim(BASE_URL, '/')))
                    : strpos($currentPath, BASE_URL . rtrim($item['path'], '/')) === 0;
                ?>
                <li class="mb-1">
                    <a class="admin-nav-link d-flex align-items-center gap-2 px-3 py-2 rounded-3 text-decoration-none <?php echo $isActive ? 'active' : '' ?>"
                       href="<?= e(url($item['path'])) ?>"
                       aria-current="<?php echo $isActive ? 'page' : 'false' ?>">
                        <?php icon($item['icon'], 18) ?>
                        <span class="admin-nav-label d-none d-sm-inline"><?= e($item['label']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="admin-sidebar-footer border-top p-3">
        <a class="btn btn-outline-secondary btn-sm w-100 mb-2" href="<?= e(url('/')) ?>" target="_blank" rel="noopener"><?= __('admin.view_site') ?></a>
        <a class="btn btn-danger btn-sm w-100" href="<?= e(url('admin/logout')) ?>" onclick="return confirm('<?= e(__('admin.confirm_delete')) ?>')"><?= __('admin.logout') ?></a>
    </div>
</aside>
<div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

<!-- ================= TOP HEADER ================= -->
<header class="admin-header border-bottom">
    <div class="container d-flex align-items-center justify-content-between py-2">
        <button class="btn btn-link text-reset p-0 d-sm-none" id="sidebarToggle" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand d-none d-sm-flex align-items-center gap-2 text-decoration-none" href="<?= e(url('admin')) ?>">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/></svg>
            <span class="fw-bold admin-sidebar-brand-text"><?= e(setting('company_name')) ?></span>
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="d-none d-sm-inline text-muted small admin-header-user"><?= e($user['name'] ?? '') ?></span>
            <span class="badge bg-primary text-white d-none d-sm-inline admin-header-role"><?= __('admin.role_admin') ?></span>
        </div>
    </div>
</header>

<main class="admin-main flex-grow-1">
    <div class="container mt-4 flex-grow-1">
        <?php foreach ($flashes as $flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : e($flash['type']) ?> alert-dismissible fade show">
                <?= e($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
        <?= $content ?? '' ?>
    </div>
</main>

<footer class="mt-auto py-3 border-top admin-footer">
    <div class="container text-muted small d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2">
        <span><?= __('admin.role_admin') ?>: <?= e($user['name'] ?? '') ?> (<?= e($user['email'] ?? '') ?>)</span>
        <span>&copy; <?= date('Y') ?> <?= e(setting('company_name')) ?></span>
        <a class="text-decoration-none text-muted" href="<?= e(url('/')) ?>"><?= __('admin.view_site') ?></a>
    </div>
</footer>

<script src="<?= e(asset('vendor/js/bootstrap.bundle.min.js')) ?>"></script>
<script src="<?= e(asset('js/admin.js')) ?>"></script>
<script>
/* Sidebar toggle (mobile) */
var sidebar = document.getElementById('adminSidebar');
var toggle = document.getElementById('sidebarToggle');
var overlay = document.getElementById('sidebarOverlay');
function openSidebar() { sidebar.classList.add('show'); overlay.classList.add('show'); document.body.classList.add('sidebar-open'); }
function closeSidebar() { sidebar.classList.remove('show'); overlay.classList.remove('show'); document.body.classList.remove('sidebar-open'); }
if (sidebar && toggle && overlay) {
    toggle.addEventListener('click', openSidebar);
    overlay.addEventListener('click', closeSidebar);
    sidebar.querySelectorAll('.admin-nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) closeSidebar();
        });
    });
    document.addEventListener('click', function (e) {
        if (window.innerWidth < 992 && sidebar.classList.contains('show') &&
            !sidebar.contains(e.target) && !toggle.contains(e.target)) {
            closeSidebar();
        }
    });
}
</script>
</body>
</html>
