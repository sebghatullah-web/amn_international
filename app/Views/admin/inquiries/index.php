<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= __('admin.inquiries') ?></h5>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('admin/inquiries')) ?>?type=quote&status=new"><?= __('admin.type_quote') ?> / new</a>
            <a class="btn btn-outline-secondary btn-sm" href="<?= e(url('admin/inquiries')) ?>?type=contact"><?= __('admin.type_contact') ?></a>
        </div>
    </div>
    <div class="card-body">
        <form class="row g-2 mb-4" method="get" action="<?= e(url('admin/inquiries')) ?>">
            <div class="col-auto"><input class="form-control form-control-sm" type="search" name="q" placeholder="Search..." value="<?= e($filters['q']) ?>"></div>
            <div class="col-auto">
                <select class="form-select form-select-sm" name="type">
                    <option value=""><?= __('common.all') ?></option>
                    <option value="quote" <?= selected_if($filters['type'], 'quote') ?>><?= __('admin.type_quote') ?></option>
                    <option value="contact" <?= selected_if($filters['type'], 'contact') ?>><?= __('admin.type_contact') ?></option>
                </select>
            </div>
            <div class="col-auto">
                <select class="form-select form-select-sm" name="status">
                    <option value=""><?= __('common.all') ?></option>
                    <option value="new" <?= selected_if($filters['status'], 'new') ?>><?= __('admin.status') ?>: new</option>
                    <option value="read" <?= selected_if($filters['status'], 'read') ?>><?= __('admin.status') ?>: read</option>
                    <option value="replied" <?= selected_if($filters['status'], 'replied') ?>><?= __('admin.status') ?>: replied</option>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-sm btn-outline-secondary"><?= __('admin.search') ?></button></div>
            <div class="col-auto"><a class="btn btn-sm btn-link text-reset" href="<?= e(url('admin/inquiries')) ?>"><?= __('common.clear_filters') ?></a></div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead><tr><th>#</th><th><?= __('admin.name') ?></th><th><?= __('admin.email') ?></th><th>Type</th><th><?= __('admin.date') ?></th><th class="text-center"><?= __('admin.status') ?></th><th class="text-end"><?= __('admin.actions') ?></th></tr></thead>
                <tbody>
                <?php foreach ($result['rows'] as $r): ?>
                    <tr>
                        <td><?= (int)$r['id'] ?></td>
                        <td><?= e($r['name']) ?></td>
                        <td><?= e($r['email']) ?></td>
                        <td><span class="badge text-bg-<?= $r['type'] === 'quote' ? 'primary' : 'secondary' ?> small"><?= $r['type'] === 'quote' ? __('admin.type_quote') : __('admin.type_contact') ?></span></td>
                        <td class="small text-muted"><?= e(format_date($r['created_at'])) ?></td>
                        <td class="text-center"><span class="badge text-bg-<?= $r['status'] === 'new' ? 'warning text-dark' : ($r['status'] === 'replied' ? 'success' : 'secondary') ?> small"><?= e($r['status']) ?></span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/inquiries/' . $r['id'])) ?>"><?= __('common.view_details') ?></a>
                            <form class="d-inline" action="<?= e(url('admin/inquiries/' . $r['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('<?= e(__('admin.confirm_delete')) ?>')" style="margin:0">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger" type="submit"><?= __('admin.delete') ?></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($result['rows'])): ?><tr><td colspan="7" class="text-center text-muted py-3"><?= __('admin.no_results') ?></td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php echo pagination($result['page'], $result['pages'], 'admin/inquiries', array_filter($filters), 'inquiries-pagination'); ?>
    </div>
</div>
