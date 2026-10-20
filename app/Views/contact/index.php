<div class="section pt-4">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100 p-4 rounded-4">
                    <h2 class="h4 fw-bold mb-3"><?= icon('mail', 20) ?> <?= __('contact.form_title') ?></h2>
                    <form id="contact-form" action="<?= e(url('contact')) ?>" method="post" novalidate>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label"><?= __('fields.name') ?></label>
                            <input type="text" name="name" class="form-control" required maxlength="120">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= __('fields.email') ?></label>
                            <input type="email" name="email" class="form-control" required maxlength="190">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= __('fields.phone') ?></label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. +93 70 123 4567" maxlength="40">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= __('fields.subject') ?></label>
                            <input type="text" name="subject" class="form-control" placeholder="General inquiry" maxlength="255">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label"><?= __('fields.message') ?></label>
                            <textarea name="message" class="form-control" rows="5" required minlength="10" maxlength="4000"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button class="btn btn-primary" type="submit"><?= icon('paper-plane', 16) ?> <?= __('common.send_message') ?></button>
                    </form>
                    <div id="contact-response" class="mt-3" aria-live="polite"></div>
                </div>
            <div class="col-lg-7">
                <h2 class="h4 fw-bold mb-3"><?= __('contact.info_title') ?></h2>
                <div class="row g-4 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="info-card p-3 rounded-3 border bg-light text-center">
                            <div class="mb-2"><?= icon('clock', 22) ?></div>
                            <div class="fw-bold"><?= __('contact.hours') ?></div>
                            <div class="small text-muted"><?= e(setting('working_hours')) ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="info-card p-3 rounded-3 border bg-light text-center">
                            <div class="mb-2"><?= icon('pin', 22) ?></div>
                            <div class="fw-bold"><?= __('contact.address') ?></div>
                            <div class="small text-muted"><?= e(setting('office_address')) ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="info-card p-3 rounded-3 border bg-light text-center">
                            <div class="mb-2"><?= icon('phone', 22) ?></div>
                            <div class="fw-bold"><?= __('contact.phone') ?></div>
                            <div class="small text-muted"><a class="text-reset text-decoration-none" href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="info-card p-3 rounded-3 border bg-light text-center">
                            <div class="mb-2"><?= icon('whatsapp', 22) ?></div>
                            <div class="fw-bold"><?= __('contact.whatsapp') ?></div>
                            <div class="small text-muted"><a class="text-reset text-decoration-none" href="<?= e(setting('whatsapp_link')) ?>" target="_blank" rel="noopener"><?= e(setting('whatsapp')) ?></a></div>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <div class="mb-3"><?= icon('mail', 22) ?></div>
                    <div class="fw-bold mb-1"><?= __('contact.email') ?></div>
                    <a class="text-decoration-none text-reset" href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a>
                    <p class="small text-muted mt-2"><?= e(setting('footer_about', __('footer.about_text'))) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contact-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var form = e.target;
    var btn = form.querySelector('button[type="submit"]');
    var resp = document.getElementById('contact-response');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<?= e(__('common.loading')) ?>...';
    resp.innerHTML = '';

    var fd = new FormData(form);
    fetch('<?= e(url('ajax/contact')) ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: fd
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = orig;
            if (data.success) {
                resp.innerHTML = '<div class="alert alert-success" role="alert">' + data.message + '</div>';
                form.reset();
            } else {
                resp.innerHTML = '<div class="alert alert-danger" role="alert">' + (data.message || '<?= e(__('errors.generic')) ?>') + '</div>';
                if (data.errors) {
                    Object.keys(data.errors).forEach(function (field) {
                        var input = form.elements[field];
                        if (input) {
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = data.errors[field];
                            input.addEventListener('input', function () { input.classList.remove('is-invalid'); }, { once: true });
                        }
                    });
                }
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = orig;
            resp.innerHTML = '<div class="alert alert-danger" role="alert"><?= e(__('errors.generic')) ?></div>';
        });
});
</script>
