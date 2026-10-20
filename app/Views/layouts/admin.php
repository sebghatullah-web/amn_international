<?php
/**
 * Admin layout.
 */
$flashes = App\Core\Session::pullFlashes();
$user = App\Core\Auth::user();
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
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg border-bottom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= e(url('admin')) ?>">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/></svg>
            <span><?= e(setting('company_name')) ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link<?= active_if($_SERVER['REQUEST_URI'] === BASE_URL . '/admin' || strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/') === false ? ' active' : '') ?>" href="<?= e(url('admin')) ?>"><?= __('admin.dashboard') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/products') === 0) ?>" href="<?= e(url('admin/products')) ?>"><?= __('admin.products') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/categories') === 0) ?>" href="<?= e(url('admin/categories')) ?>"><?= __('admin.categories') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/services') === 0) ?>" href="<?= e(url('admin/services')) ?>"><?= __('admin.services') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/steps') === 0) ?>" href="<?= e(url('admin/steps')) ?>"><?= __('admin.steps') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/gallery') === 0) ?>" href="<?= e(url('admin/gallery')) ?>"><?= __('admin.gallery') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/articles') === 0) ?>" href="<?= e(url('admin/articles')) ?>"><?= __('admin.articles') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/inquiries') === 0) ?>" href="<?= e(url('admin/inquiries')) ?>"><?= __('admin.inquiries') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/subscribers') === 0) ?>" href="<?= e(url('admin/subscribers')) ?>"><?= __('admin.subscribers') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/settings') === 0) ?>" href="<?= e(url('admin/settings')) ?>"><?= __('admin.settings') ?></a></li>
                <li class="nav-item"><a class="nav-link<?= active_if(strpos($_SERVER['REQUEST_URI'] ?? '', '/admin/users') === 0) ?>" href="<?= e(url('admin/users')) ?>"><?= __('admin.users') ?></a></li>
            </ul>
            <div class="d-flex align-items-center">
                <a class="btn btn-outline-secondary btn-sm me-2" href="<?= e(url('/')) ?>" target="_blank" rel="noopener"><?= __('admin.view_site') ?></a>
                <a class="btn btn-danger btn-sm" href="<?= e(url('admin/logout')) ?>" onclick="return confirm('<?= __('admin.confirm_delete') ?>')"><?= __('admin.logout') ?></a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4 flex-grow-1">
    <?php foreach ($flashes as $flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : e($flash['type']) ?> alert-dismissible fade show">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>
    <?= $content ?? '' ?>
</div>

<footer class="mt-auto py-3 border-top">
    <div class="container text-muted small">
        <span><?= __('admin.role_admin') ?>: <?= e($user['name'] ?? '') ?> (<?= e($user['email'] ?? '') ?>)</span>
        &nbsp;·&nbsp;
        <span>&copy; <?= date('Y') ?> <?= e(setting('company_name')) ?></span>
        &nbsp;·&nbsp;
        <a class="text-decoration-none text-muted" href="<?= e(url('/')) ?>"><?= __('admin.view_site') ?></a>
    </div>
</footer>

<script src="<?= e(asset('vendor/js/bootstrap.bundle.min.js')) ?>"></script>
<script src="<?= e(asset('js/admin.js')) ?>"></script>
</body>
</html>
