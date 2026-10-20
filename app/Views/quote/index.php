<div class="section pt-4">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100 p-4 rounded-4">
                    <h2 class="h4 fw-bold mb-3"><?= icon('quote', 20) ?> <?= __('quote.form_title') ?></h2>
                    <p class="text-muted small mb-4"><?= e($product ? __('quote.for_product') . ': <strong>' . $product['name'] . '</strong>' : '') ?></p>
                    <form id="quote-form" action="<?= e(url('quote')) ?>" method="post" novalidate>
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
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. +93 70 123 4567" required maxlength="40">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= __('fields.country') ?></label>
                            <input type="text" name="country" class="form-control" value="Afghanistan" maxlength="80">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= __('quote.product_label') ?></label>
                            <select class="form-select" name="product_id">
                                <option value=""><?= __('quote.general_inquiry') ?></option>
                                <?php foreach ($products as $p): ?>
                                    <option value="<?= (int)$p['id'] ?>" <?= ($product && $p['id'] == $product['id'] ? 'selected' : '') ?>><?= e($p['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= __('fields.quantity') ?></label>
                            <input type="text" name="quantity" class="form-control" placeholder="e.g. 100 units" maxlength="60">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label"><?= __('fields.message') ?></label>
                            <textarea name="message" class="form-control" rows="5" required minlength="10" maxlength="4000"><?= $product ? 'I am interested in ' . $product['name'] . ' (' . $product['category_name'] . '). Please send pricing, MOQ, lead time and shipping options.' : '' ?></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button class="btn btn-primary" type="submit"><?= icon('paper-plane', 16) ?> <?= __('common.send_message') ?></button>
                    </form>
                    <div id="quote-response" class="mt-3" aria-live="polite"></div>
                </div>
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 rounded-4">
                    <h2 class="h4 fw-bold mb-3"><?= icon('headset', 20) ?> <?= __('common.need_help') ?></h2>
                    <p class="text-muted mb-4"><?= e(__('products.inquiry_text')) ?></p>
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="mb-2"><?= icon('phone', 20) ?></div>
                                <div class="fw-bold"><?= __('contact.phone') ?></div>
                                <a class="text-decoration-none text-reset" href="tel:<?= e(preg_replace('/[^0-9+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="mb-2"><?= icon('whatsapp', 20) ?></div>
                                <div class="fw-bold"><?= __('contact.whatsapp') ?></div>
                                <a class="text-decoration-none text-reset" href="<?= e(setting('whatsapp_link')) ?>" target="_blank" rel="noopener"><?= e(setting('whatsapp')) ?></a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="mb-2"><?= icon('mail', 20) ?></div>
                                <div class="fw-bold"><?= __('contact.email') ?></div>
                                <a class="text-decoration-none text-reset" href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded-3 p-3 bg-light">
                                <div class="mb-2"><?= icon('clock', 20) ?></div>
                                <div class="fw-bold"><?= __('contact.hours') ?></div>
                                <div class="small text-muted"><?= e(setting('working_hours')) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('quote-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var form = e.target;
    var btn = form.querySelector('button[type="submit"]');
    var resp = document.getElementById('quote-response');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<?= e(__('common.loading')) ?>...';
    resp.innerHTML = '';

    var fd = new FormData(form);
    fetch('<?= e(url('ajax/quote')) ?>', {
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
