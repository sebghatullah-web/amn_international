<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= e($title) ?></h5>
    </div>
    <div class="card-body">
        <form id="category-form" method="post" action="<?= e($mode === 'edit' ? url('admin/categories/' . $category['id']) : url('admin/categories')) ?>" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?php if ($mode === 'edit'): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.name') ?></label>
                        <input type="text" name="name" class="form-control" required maxlength="100" value="<?= e($category['name'] ?? '') ?>">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.slug') ?></label>
                        <input type="text" name="slug" class="form-control" maxlength="100" value="<?= e($category['slug'] ?? '') ?>">
                        <div class="form-text"><?= __('admin.slug_auto') ?></div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('fields.details') ?></label>
                        <textarea name="description" class="form-control" rows="3" maxlength="1000"><?= e($category['description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.image') ?></label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <?php if (!empty($category['image'])): ?>
                            <div class="mt-2">
                                <img src="<?= e(upload_url($category['image'])) ?>" alt="" class="img-thumbnail" style="max-width: 200px;">
                                <div class="form-text small text-muted"><?= e($category['image']) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.display_order') ?></label>
                        <input type="number" name="sort_order" class="form-control" value="<?= e($category['sort_order'] ?? 0) ?>">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= checked_if($category['is_active'] ?? 1) ?> id="is_active">
                        <label class="form-check-label" for="is_active"><?= __('admin.active') ?></label>
                    </div>
                    <hr>
                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-secondary" href="<?= e(url('admin/categories')) ?>"><?= __('admin.cancel') ?></a>
                        <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('category-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var form = e.target;
    var btn = form.querySelector('button[type="submit"]');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<?= e(__('common.loading')) ?>...';

    var fd = new FormData(form);
    fetch(form.action, {
        method: form.elements['_method'] ? 'POST' : 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: fd
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = orig;
            if (data.success) {
                window.location.href = data.redirect || '<?= e(url('admin/categories')) ?>';
            } else {
                var msg = data.message || '<?= e(__('errors.generic')) ?>';
                alert(msg);
                if (data.errors) {
                    Object.keys(data.errors).forEach(function (field) {
                        var input = form.elements[field];
                        if (input) {
                            input.classList.add('is-invalid');
                            input.nextElementSibling.textContent = data.errors[field];
                        }
                    });
                }
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = orig;
            alert('<?= e(__('errors.generic')) ?>');
        });
});
</script>
