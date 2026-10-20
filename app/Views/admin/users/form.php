<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= e($title) ?></h5>
    </div>
    <div class="card-body">
        <form id="user-form" method="post" action="<?= e($mode === 'edit' ? url('admin/users/' . $user['id']) : url('admin/users')) ?>" novalidate>
            <?= csrf_field() ?>
            <?php if ($mode === 'edit'): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label"><?= __('admin.name') ?></label>
                    <input type="text" name="name" class="form-control" required maxlength="120" value="<?= e($user['name'] ?? '') ?>">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label"><?= __('admin.email') ?></label>
                    <input type="email" name="email" class="form-control" required maxlength="190" value="<?= e($user['email'] ?? '') ?>">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?= __('admin.role') ?></label>
                    <select class="form-select" name="role">
                        <option value="admin" <?= selected_if($user['role'] ?? 'admin', 'admin') ?>><?= __('admin.role_admin') ?></option>
                        <option value="editor" <?= selected_if($user['role'] ?? '', 'editor') ?>><?= __('admin.role_editor') ?></option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label"><?= __('fields.password') ?></label>
                    <input type="password" name="password" class="form-control" <?= $mode === 'edit' ? '' : 'required' ?> minlength="8" value="">
                    <div class="form-text"><?= __('admin.password_note') ?></div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="<?= e(url('admin/users')) ?>"><?= __('admin.cancel') ?></a>
                <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('user-form')?.addEventListener('submit', function (e) {
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
            if (data.success) { window.location.href = data.redirect || '<?= e(url('admin/users')) ?>'; }
            else { alert(data.message || '<?= e(__('errors.generic')) ?>'); }
        })
        .catch(function () { btn.disabled = false; btn.innerHTML = orig; alert('<?= e(__('errors.generic')) ?>'); });
});
</script>
