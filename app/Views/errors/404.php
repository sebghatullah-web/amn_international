<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? __('errors.404_title')) ?> | <?= e(setting('company_name', 'AMN International')) ?></title>
    <link rel="icon" href="<?= e(asset('images/favicon.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= e(asset('vendor/css/bootstrap.min.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">
</head>
<body class="d-flex flex-column min-vh-100">
<main class="flex-grow-1 d-flex align-items-center">
    <div class="container text-center py-5">
        <div class="mb-4" style="font-size: 4rem; opacity: .35;">404</div>
        <h1 class="h3 fw-bold mb-3"><?= e($title ?? __('errors.404_title')) ?></h1>
        <p class="text-muted mb-4"><?= __('errors.404_text') ?></p>
        <a class="btn btn-primary" href="<?= e(url('/')) ?>"><?= __('errors.back_home') ?></a>
        <a class="btn btn-outline-secondary" href="<?= e(url('products')) ?>"><?= __('common.products') ?></a>
    </div>
</main>
<footer class="py-3 border-top">
    <div class="container text-center small text-muted">&copy; <?= date('Y') ?> <?= e(setting('company_name', 'AMN International')) ?>. <?= __('footer.rights') ?></div>
</footer>
</body>
</html>
