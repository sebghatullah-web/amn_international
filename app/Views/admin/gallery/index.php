<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= __('admin.gallery') ?></h5>
        <a class="btn btn-primary btn-sm" href="<?= e(url('admin/gallery/create')) ?>"><?= icon('ship', 16) ?> <?= __('admin.new') ?></a>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-4" method="get" action="<?= e(url('admin/gallery')) ?>">
            <div class="col-auto"><input class="form-control form-control-sm" type="search" name="q" placeholder="Search..." value="<?= e($q) ?>"></div>
            <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><?= __('admin.search') ?></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>#</th><th><?= __('admin.image') ?></th><th><?= __('admin.title') ?></th><th><?= __('admin.display_order') ?></th><th class="text-center"><?= __('admin.status') ?></th><th class="text-end"><?= __('admin.actions') ?></th></tr></thead>
                <tbody>
                <?php foreach ($result['rows'] as $g): ?>
                    <tr>
                        <td><?= (int)$g['id'] ?></td>
                        <td><img src="<?= e(upload_url($g['path'])) ?>" class="rounded-2" width="60" height="45" style="object-fit: cover;"></td>
                        <td><?= e($g['title']) ?></td>
                        <td><?= (int)$g['sort_order'] ?></td>
                        <td class="text-center"><span class="badge text-bg-<?= $g['is_active'] ? 'success' : 'secondary' ?> small"><?= $g['is_active'] ? __('admin.active') : __('admin.inactive') ?></span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/gallery/' . $g['id'] . '/edit')) ?>"><?= __('admin.edit') ?></a>
                            <form class="d-inline" action="<?= e(url('admin/gallery/' . $g['id'])) ?>" method="post" onsubmit="return confirm('<?= e(__('admin.confirm_delete')) ?>')" style="margin:0">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-sm btn-outline-danger" type="submit"><?= __('admin.delete') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($result['rows'])): ?><tr><td colspan="6" class="text-center text-muted py-3"><?= __('admin.no_results') ?></td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo pagination($result['page'], $result['pages'], 'admin/gallery', $q !== '' ? ['q' => $q] : [], 'gallery-pagination'); ?>
    </div>
</div>
