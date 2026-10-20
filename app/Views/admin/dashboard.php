<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title text-muted small text-uppercase"><?= __('admin.new_inquiries') ?></h5>
                        <h2 class="fw-bold mb-0"><?= $stats['inquiries'] ?></h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2"><?= icon('quote', 20) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title text-muted small text-uppercase"><?= __('admin.total_products') ?></h5>
                        <h2 class="fw-bold mb-0"><?= $stats['products'] ?></h2>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded p-2"><?= icon('box', 20) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title text-muted small text-uppercase"><?= __('admin.total_articles') ?></h5>
                        <h2 class="fw-bold mb-0"><?= $stats['articles'] ?></h2>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded p-2"><?= icon('doc', 20) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="card-title text-muted small text-uppercase"><?= __('admin.subscriber_count') ?></h5>
                        <h2 class="fw-bold mb-0"><?= $stats['subscribers'] ?></h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-2"><?= icon('mail', 20) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0">
                <h5 class="fw-bold mb-0"><?= __('admin.recent_inquiries') ?></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th><?= __('admin.name') ?></th>
                            <th><?= __('admin.email') ?></th>
                            <th><?= __('admin.type_quote') ?></th>
                            <th class="text-end"><?= __('admin.date') ?></th>
                            <th class="text-end"><?= __('admin.actions') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recent as $r): ?>
                            <tr>
                                <td><?= e($r['name']) ?></td>
                                <td><a class="text-decoration-none text-reset" href="mailto:<?= e($r['email']) ?>"><?= e($r['email']) ?></a></td>
                                <td><span class="badge text-bg-<?= $r['type'] === 'quote' ? 'primary' : 'secondary' ?> small"><?= $r['type'] === 'quote' ? __('admin.type_quote') : __('admin.type_contact') ?></span></td>
                                <td class="text-end small text-muted"><?= e(format_date($r['created_at'])) ?></td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="<?= e(url('admin/inquiries/' . $r['id'])) ?>"><?= __('common.view_details') ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3"><?= __('admin.no_results') ?></td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Quick actions</h5>
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= e(url('admin/products/create')) ?>">
                    <span><?= __('admin.new') ?> <?= __('admin.products') ?></span>
                    <?php icon('box', 16) ?>
                </a>
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= e(url('admin/articles/create')) ?>">
                    <span><?= __('admin.new') ?> <?= __('admin.articles') ?></span>
                    <?php icon('doc', 16) ?>
                </a>
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= e(url('admin/settings/currency')) ?>">
                    <span><?= __('admin.exchange_rate') ?></span>
                    <?php icon('globe', 16) ?>
                </a>
                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="<?= e(url('admin/users/create')) ?>">
                    <span><?= __('admin.new') ?> <?= __('admin.users') ?></span>
                    <?php icon('headset', 16) ?>
                </a>
            </div>
        </div>
    </div>
</div>
