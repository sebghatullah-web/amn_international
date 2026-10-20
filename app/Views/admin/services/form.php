<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= e($title) ?></h5>
    </div>
    <div class="card-body">
        <form id="service-form" method="post" action="<?= e($mode === 'edit' ? url('admin/services/' . $service['id']) : url('admin/services')) ?>" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?php if ($mode === 'edit'): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.title') ?></label>
                        <input type="text" name="title" class="form-control" required maxlength="150" value="<?= e($service['title'] ?? '') ?>">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.slug') ?></label>
                        <input type="text" name="slug" class="form-control" maxlength="150" value="<?= e($service['slug'] ?? '') ?>">
                        <div class="form-text"><?= __('admin.slug_auto') ?></div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><?= __('fields.details') ?></label>
                        <textarea name="short_description" class="form-control" rows="3" maxlength="500"><?= e($service['short_description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.image') ?></label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <?php if (!empty($service['image'])): ?><img src="<?= e(upload_url($service['image'])) ?>" class="img-thumbnail mt-2" style="max-width: 180px;"><?php endif; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.display_order') ?></label>
                        <input type="number" name="sort_order" class="form-control" value="<?= e($service['sort_order'] ?? 0) ?>">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= checked_if($service['is_active'] ?? 1) ?> id="is_active">
                        <label class="form-check-label" for="is_active"><?= __('admin.active') ?></label>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label"><?= __('fields.message') ?></label>
                <textarea name="description" class="form-control" rows="6" maxlength="5000"><?= e($service['description'] ?? '') ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="<?= e(url('admin/services')) ?>"><?= __('admin.cancel') ?></a>
                <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('service-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var form = e.target;
    var btn = form.querySelector('button[type="submit"]');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<?= e(__('common.loading')) ?>...';
    fetch(form.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: new FormData(form)
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = orig;
            if (data.success) { window.location.href = data.redirect || '<?= e(url('admin/services')) ?>'; }
            else { alert(data.message || '<?= e(__('errors.generic')) ?>'); }
        })
        .catch(function () { btn.disabled = false; btn.innerHTML = orig; alert('<?= e(__('errors.generic')) ?>'); });
});
</script>
