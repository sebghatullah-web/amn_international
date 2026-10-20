<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= e($title) ?></h5>
    </div>
    <div class="card-body">
        <form id="gallery-form" method="post" action="<?= e($mode === 'edit' ? url('admin/gallery/' . $gallery['id']) : url('admin/gallery')) ?>" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?php if ($mode === 'edit'): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.title') ?></label>
                        <input type="text" name="title" class="form-control" required maxlength="150" value="<?= e($gallery['title'] ?? '') ?>">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Group (optional)</label>
                        <input type="text" name="group_name" class="form-control" maxlength="80" value="<?= e($gallery['group_name'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.image') ?></label>
                        <input type="file" name="image" class="form-control" accept="image/*" <?= $mode === 'edit' ? '' : 'required' ?>>
                        <?php if (!empty($gallery['path'])): ?><img src="<?= e(upload_url($gallery['path'])) ?>" class="img-thumbnail mt-2" style="max-width: 180px;"><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.display_order') ?></label>
                        <input type="number" name="sort_order" class="form-control" value="<?= e($gallery['sort_order'] ?? 0) ?>">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= checked_if($gallery['is_active'] ?? 1) ?> id="is_active">
                        <label class="form-check-label" for="is_active"><?= __('admin.active') ?></label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="<?= e(url('admin/gallery')) ?>"><?= __('admin.cancel') ?></a>
                <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('gallery-form')?.addEventListener('submit', function (e) {
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
            if (data.success) { window.location.href = data.redirect || '<?= e(url('admin/gallery')) ?>'; }
            else { alert(data.message || '<?= e(__('errors.generic')) ?>'); }
        })
        .catch(function () { btn.disabled = false; btn.innerHTML = orig; alert('<?= e(__('errors.generic')) ?>'); });
});
</script>
