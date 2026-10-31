<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= __('admin.categories') ?></h5>
        <a class="btn btn-primary btn-sm" href="<?= e(url('admin/categories/create')) ?>"><?= icon('box', 16) ?> <?= __('admin.new') ?></a>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-4" method="get" action="<?= e(url('admin/categories')) ?>">
            <div class="col-auto"><input class="form-control form-control-sm" type="search" name="q" placeholder="Search categories..." value="<?= e($q) ?>"></div>
            <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><?= __('admin.search') ?></button></div>
            <div class="col-auto"><a class="btn btn-sm btn-link text-reset" href="<?= e(url('admin/categories')) ?>"><?= __('common.clear_filters') ?></a></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th><th><?= __('admin.name') ?></th><th><?= __('admin.slug') ?></th><th class="text-center"><?= __('admin.products_count') ?></th><th class="text-center"><?= __('admin.status') ?></th><th class="text-end"><?= __('admin.actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($result['rows'] as $c): ?>
                    <tr>
                        <td><?= (int)$c['id'] ?></td>
                        <td><?= e($c['name']) ?></td>
                        <td class="text-muted small"><?= e($c['slug']) ?></td>
                        <td class="text-center"><span class="badge text-bg-secondary small"><?= (int)$c['products_count'] ?></span></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-<?= $c['is_active'] ? 'success' : 'secondary' ?> toggle-btn" data-url="<?= e(url('admin/categories/' . $c['id'] . '/toggle')) ?>" data-id="<?= (int)$c['id'] ?>">
                                <?= $c['is_active'] ? __('admin.active') : __('admin.inactive') ?>
                            </button>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/categories/' . $c['id'] . '/edit')) ?>"><?= __('admin.edit') ?></a>
                            <form class="d-inline" action="<?= e(url('admin/categories/' . $c['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('<?= e(__('admin.confirm_delete')) ?>')" style="margin:0">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger" type="submit"><?= __('admin.delete') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($result['rows'])): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3"><?= __('admin.no_results') ?></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo pagination($result['page'], $result['pages'], 'admin/categories', $q !== '' ? ['q' => $q] : [], 'categories-pagination'); ?>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var url = this.getAttribute('data-url');
        fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-Token': '<?= e(csrf_token()) ?>' },
            credentials: 'same-origin'
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    btn.classList.toggle('btn-success');
                    btn.classList.toggle('btn-secondary');
                    btn.textContent = data.is_active ? '<?= e(__('admin.active')) ?>' : '<?= e(__('admin.inactive')) ?>';
                }
            });
    });
});
</script>
