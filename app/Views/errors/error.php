<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error</title>
    <link rel="icon" href="<?= e(defined('ASSET_URL') ? ASSET_URL : '') ?>/images/favicon.svg" type="image/svg+xml">
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f6f7f9; color: #1f2937; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .box { max-width: 640px; padding: 2.5rem; background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.06); text-align: center; }
        h1 { font-size: 1.4rem; margin-bottom: .75rem; }
        p { color: #6b7280; line-height: 1.6; }
        a.btn { display: inline-block; margin-top: 1rem; padding: .6rem 1.2rem; background: #1f6f4a; color: #fff; text-decoration: none; border-radius: 8px; }
    </style>
</head>
<body>
<div class="box">
    <h1>Something went wrong</h1>
    <p><?= e($message ?? 'We are sorry, something went wrong. Please try again later.') ?></p>
    <a class="btn" href="javascript:location.reload()">Try again</a>
</div>
</body>
</html>
