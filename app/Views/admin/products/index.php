<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0">
        <div>
            <h5 class="fw-bold mb-0"><?= __('admin.products') ?></h5>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary btn-sm" href="<?= e(url('admin/products/create')) ?>"><?= icon('box', 16) ?> <?= __('admin.new') ?></a>
        </div>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-4" method="get" action="<?= e(url('admin/products')) ?>">
            <div class="col-md-3"><input class="form-control form-control-sm" type="search" name="q" placeholder="Search products..." value="<?= e($filters['q']) ?>"></div>
            <div class="col-md-3"><select class="form-select form-select-sm" name="category"><option value=""><?= __('common.all_categories') ?></option><?php foreach ($categories as $cat): ?><option value="<?= (int)$cat['id'] ?>" <?= selected_if($filters['category_id'], $cat['id']) ?>><?= e($cat['name']) ?></option><?php endforeach; ?></select></div>
            <div class="col-md-2"><select class="form-select form-select-sm" name="active"><option value=""><?= __('common.all') ?></option><option value="1" <?= selected_if($filters['active'], '1') ?>><?= __('admin.active') ?></option><option value="0" <?= selected_if($filters['active'], '0') ?>><?= __('admin.inactive') ?></option></select></div>
            <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><?= __('admin.search') ?></button></div>
            <div class="col-auto"><a class="btn btn-sm btn-link text-reset" href="<?= e(url('admin/products')) ?>"><?= __('common.clear_filters') ?></a></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>#</th><th><?= __('admin.name') ?></th><th class="text-center"><?= __('admin.category') ?></th><th class="text-center"><?= __('admin.price_usd') ?></th><th class="text-center"><?= __('admin.status') ?></th><th class="text-center"><?= __('admin.flags') ?></th><th class="text-end"><?= __('admin.actions') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($result['rows'] as $p): ?>
                    <tr>
                        <td><?= (int)$p['id'] ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php if (!empty($p['primary_image'])): ?><img src="<?= e(upload_url($p['primary_image'])) ?>" class="rounded-2 me-2" width="36" height="36"><?php else: ?><div class="rounded-2 me-2 bg-light d-flex align-items-center justify-content-center" style="width:36px;height:36px"><small class="text-muted"><?= icon('box', 14) ?></small></div><?php endif; ?>
                                <div><a class="text-decoration-none text-reset d-block"><?= e($p['name']) ?></a><div class="small text-muted"><?= e($p['category_name'] ?? '-') ?></div></div>
                            </div>
                        </td>
                        <td class="text-center small text-muted"><?= e($p['category_name'] ?? '-') ?></td>
                        <td class="text-center">
                            <?php if ($p['is_price_published'] && $p['price_usd'] !== null): ?>
                                <span class="badge text-bg-primary small"><?= e(\App\Models\Product::priceLabel($p)) ?></span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary small"><?= __('admin.no_price') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-<?= $p['is_active'] ? 'success' : 'secondary' ?> toggle-btn" data-url="<?= e(url('admin/products/' . $p['id'] . '/toggle')) ?>" data-id="<?= (int)$p['id'] ?>">
                                <?= $p['is_active'] ? __('admin.active') : __('admin.inactive') ?>
                            </button>
                        </td>
                        <td class="text-center">
                            <?php if ($p['is_featured']): ?><span class="badge text-bg-success small"><?= __('admin.flag_featured') ?></span><?php endif; ?>
                            <?php if ($p['is_new']): ?><span class="badge text-bg-info text-dark small"><?= __('admin.flag_new') ?></span><?php endif; ?>
                            <?php if ($p['is_popular']): ?><span class="badge text-bg-warning text-dark small"><?= __('admin.flag_popular') ?></span><?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/products/' . $p['id'] . '/edit')) ?>"><?= __('admin.edit') ?></a>
                            <form class="d-inline" action="<?= e(url('admin/products/' . $p['id'])) ?>" method="post" onsubmit="return confirm('<?= e(__('admin.confirm_delete')) ?>')" style="margin:0">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-sm btn-outline-danger" type="submit"><?= __('admin.delete') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($result['rows'])): ?>
                    <tr><td colspan="7" class="text-center text-muted py-3"><?= __('admin.no_results') ?></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo pagination($result['page'], $result['pages'], 'admin/products', array_filter($filters), 'products-pagination'); ?>
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
