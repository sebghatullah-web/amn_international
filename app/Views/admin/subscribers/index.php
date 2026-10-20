<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= __('admin.subscribers') ?> (<?= $result['total'] ?>)</h5>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-4" method="get" action="<?= e(url('admin/subscribers')) ?>">
            <div class="col-auto"><input class="form-control form-control-sm" type="search" name="q" placeholder="Search email..." value="<?= e($q) ?>"></div>
            <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><?= __('admin.search') ?></button></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>#</th><th><?= __('admin.email') ?></th><th><?= __('admin.date') ?></th><th class="text-end"><?= __('admin.actions') ?></th></tr></thead>
                <tbody>
                <?php foreach ($result['rows'] as $s): ?>
                    <tr>
                        <td><?= (int)$s['id'] ?></td>
                        <td><?= e($s['email']) ?></td>
                        <td class="small text-muted"><?= e(format_date($s['created_at'])) ?></td>
                        <td class="text-end">
                            <form class="d-inline" action="<?= e(url('admin/subscribers/' . $s['id'])) ?>" method="post" onsubmit="return confirm('<?= e(__('admin.confirm_delete')) ?>')" style="margin:0">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-sm btn-outline-danger" type="submit"><?= __('admin.delete') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($result['rows'])): ?><tr><td colspan="4" class="text-center text-muted py-3"><?= __('admin.no_results') ?></td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo pagination($result['page'], $result['pages'], 'admin/subscribers', $q !== '' ? ['q' => $q] : [], 'subscribers-pagination'); ?>
    </div>
</div>
