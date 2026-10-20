<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= __('admin.users') ?></h5>
        <a class="btn btn-primary btn-sm" href="<?= e(url('admin/users/create')) ?>"><?= icon('headset', 16) ?> <?= __('admin.new') ?></a>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-4" method="get" action="<?= e(url('admin/users')) ?>">
            <div class="col-auto"><input class="form-control form-control-sm" type="search" name="q" placeholder="Search..." value="<?= e($q) ?>"></div>
            <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><?= __('admin.search') ?></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>#</th><th><?= __('admin.name') ?></th><th><?= __('admin.email') ?></th><th><?= __('admin.role') ?></th><th><?= __('admin.status') ?></th><th><?= __('admin.date') ?></th><th class="text-end"><?= __('admin.actions') ?></th></tr></thead>
                <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= (int)$u['id'] ?></td>
                        <td><?= e($u['name']) ?></td>
                        <td><?= e($u['email']) ?></td>
                        <td><span class="badge text-bg-<?= $u['role'] === 'admin' ? 'primary' : 'secondary' ?> small"><?= $u['role'] === 'admin' ? __('admin.role_admin') : __('admin.role_editor') ?></span></td>
                        <td><span class="badge text-bg-<?= $u['is_active'] ? 'success' : 'secondary' ?> small"><?= $u['is_active'] ? __('admin.active') : __('admin.inactive') ?></span></td>
                        <td class="small text-muted"><?= e(format_date($u['created_at'])) ?></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/users/' . $u['id'] . '/edit')) ?>"><?= __('admin.edit') ?></a>
                            <?php if ($u['id'] != \App\Core\Auth::id()): ?>
                                <form class="d-inline" action="<?= e(url('admin/users/' . $u['id'])) ?>" method="post" onsubmit="return confirm('<?= e(__('admin.confirm_delete')) ?>')" style="margin:0">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-sm btn-outline-danger" type="submit"><?= __('admin.delete') ?></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?><tr><td colspan="7" class="text-center text-muted py-3"><?= __('admin.no_results') ?></td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo $pager; ?>
    </div>
</div>

<hr class="my-4">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0"><h5 class="fw-bold mb-0"><?= __('admin.change_password') ?></h5></div>
    <div class="card-body">
        <form id="password-form" method="post" action="<?= e(url('admin/profile/password')) ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label"><?= __('fields.password') ?> (current)</label><input type="password" name="current_password" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label"><?= __('fields.password') ?> (new)</label><input type="password" name="new_password" class="form-control" required minlength="8"></div>
                <div class="col-md-4 d-flex align-items-end"><button class="btn btn-primary" type="submit"><?= __('admin.change_password') ?></button></div>
            </div>
            <div id="password-response" class="mt-3" aria-live="polite"></div>
        </form>
    </div>
</div>

<script>
document.getElementById('password-form')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var form = e.target;
    var resp = document.getElementById('password-response');
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
            resp.innerHTML = '<div class="alert ' + (data.success ? 'alert-success' : 'alert-danger') + '" role="alert">' + data.message + '</div>';
            if (data.success) form.reset();
        })
        .catch(function () { btn.disabled = false; btn.innerHTML = orig; resp.innerHTML = '<div class="alert alert-danger"><?= e(__('errors.generic')) ?></div>'; });
});
</script>
