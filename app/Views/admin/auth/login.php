<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary mb-2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/></svg>
                        <h1 class="h5 fw-bold mb-1"><?= __('auth.login_title') ?></h1>
                        <p class="text-muted small mb-0"><?= e(setting('company_name')) ?></p>
                    </div>
                    <form id="login-form" method="post" action="<?= e(url('admin/login')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label"><?= __('admin.email') ?></label>
                            <input type="email" name="email" class="form-control" required autofocus autocomplete="username">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label"><?= __('fields.password') ?></label>
                            <input type="password" name="password" class="form-control" required autocomplete="current-password">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div id="login-response" class="mb-3" aria-live="polite"></div>
                        <button class="btn btn-primary w-100" type="submit"><?= __('auth.login_title') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('login-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var form = e.target;
    var btn = form.querySelector('button[type="submit"]');
    var resp = document.getElementById('login-response');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<?= e(__('common.loading')) ?>...';
    resp.innerHTML = '';
    fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: new FormData(form)
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = orig;
            if (data.success) {
                window.location.href = data.redirect || '<?= e(url('admin')) ?>';
            } else {
                resp.innerHTML = '<div class="alert alert-danger py-2" role="alert">' + data.message + '</div>';
            }
        })
        .catch(function () { btn.disabled = false; btn.innerHTML = orig; resp.innerHTML = '<div class="alert alert-danger py-2"><?= e(__('errors.generic')) ?></div>'; });
});
</script>
