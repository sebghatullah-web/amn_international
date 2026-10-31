<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= __('admin.articles') ?></h5>
        <a class="btn btn-primary btn-sm" href="<?= e(url('admin/articles/create')) ?>"><?= icon('doc', 16) ?> <?= __('admin.new') ?></a>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-4" method="get" action="<?= e(url('admin/articles')) ?>">
            <div class="col-auto"><input class="form-control form-control-sm" type="search" name="q" placeholder="Search..." value="<?= e($q) ?>"></div>
            <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><?= __('admin.search') ?></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>#</th><th><?= __('admin.title') ?></th><th><?= __('admin.date') ?></th><th class="text-center"><?= __('admin.status') ?></th><th class="text-end"><?= __('admin.actions') ?></th></tr></thead>
                <tbody>
                <?php foreach ($result['rows'] as $a): ?>
                    <tr>
                        <td><?= (int)$a['id'] ?></td>
                        <td><?= e($a['title']) ?><div class="small text-muted"><?= e($a['author'] ?? '') ?></div></td>
                        <td class="small text-muted"><?= e(format_date($a['published_at'] ?: $a['created_at'])) ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-<?= $a['is_published'] ? 'success' : 'secondary' ?> toggle-btn" data-url="<?= e(url('admin/articles/' . $a['id'] . '/toggle')) ?>">
                                <?= $a['is_published'] ? __('admin.published') : __('admin.unpublished') ?>
                            </button>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/articles/' . $a['id'] . '/edit')) ?>"><?= __('admin.edit') ?></a>
                            <form class="d-inline" action="<?= e(url('admin/articles/' . $a['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('<?= e(__('admin.confirm_delete')) ?>')" style="margin:0">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger" type="submit"><?= __('admin.delete') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($result['rows'])): ?><tr><td colspan="5" class="text-center text-muted py-3"><?= __('admin.no_results') ?></td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo pagination($result['page'], $result['pages'], 'admin/articles', $q !== '' ? ['q' => $q] : [], 'articles-pagination'); ?>
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
                    btn.textContent = data.is_published ? '<?= e(__('admin.published')) ?>' : '<?= e(__('admin.unpublished')) ?>';
                }
            });
    });
});
</script>
