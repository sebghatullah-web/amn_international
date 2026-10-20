<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0"><?= $inquiry['type'] === 'quote' ? __('admin.type_quote') : __('admin.type_contact') ?> #<?= (int)$inquiry['id'] ?></h5>
        <div class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('admin/inquiries')) ?>"><?= __('admin.back') ?></a>
            <form class="d-inline" action="<?= e(url('admin/inquiries/' . $inquiry['id'])) ?>" method="post" onsubmit="return confirm('<?= e(__('admin.confirm_delete')) ?>')">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button class="btn btn-sm btn-outline-danger" type="submit"><?= __('admin.delete') ?></button>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-6">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted"><?= __('fields.name') ?></dt>
                    <dd class="col-sm-8"><?= e($inquiry['name']) ?></dd>
                    <dt class="col-sm-4 text-muted"><?= __('fields.email') ?></dt>
                    <dd class="col-sm-8"><a href="mailto:<?= e($inquiry['email']) ?>"><?= e($inquiry['email']) ?></a></dd>
                    <dt class="col-sm-4 text-muted"><?= __('fields.phone') ?></dt>
                    <dd class="col-sm-8"><?= e($inquiry['phone'] ?: '-') ?></dd>
                    <dt class="col-sm-4 text-muted"><?= __('fields.country') ?></dt>
                    <dd class="col-sm-8"><?= e($inquiry['country'] ?: '-') ?></dd>
                    <dt class="col-sm-4 text-muted"><?= __('fields.subject') ?></dt>
                    <dd class="col-sm-8"><?= e($inquiry['subject'] ?: '-') ?></dd>
                    <dt class="col-sm-4 text-muted"><?= __('quote.product_label') ?></dt>
                    <dd class="col-sm-8"><?= e($inquiry['product_name'] ?: '-') ?></dd>
                    <dt class="col-sm-4 text-muted"><?= __('fields.quantity') ?></dt>
                    <dd class="col-sm-8"><?= e($inquiry['quantity'] ?: '-') ?></dd>
                    <dt class="col-sm-4 text-muted"><?= __('admin.date') ?></dt>
                    <dd class="col-sm-8"><?= e(format_date($inquiry['created_at'], 'Y-m-d H:i')) ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold"><?= __('fields.message') ?></h6>
                <div class="border rounded p-3 bg-light" style="white-space: pre-wrap;"><?= e($inquiry['message']) ?></div>
                <div class="mt-3">
                    <h6 class="fw-bold"><?= __('admin.status') ?></h6>
                    <div class="btn-group" role="group" aria-label="Status">
                        <?php foreach (['new', 'read', 'replied'] as $st): ?>
                            <button type="button" class="btn btn-sm btn-outline-<?= $st === $inquiry['status'] ? 'primary active' : 'secondary' ?> status-btn" data-status="<?= $st ?>"><?= __('admin.mark_' . $st) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.status-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var status = this.getAttribute('data-status');
        var fd = new FormData();
        fd.append('status', status);
        fetch('<?= e(url('admin/inquiries/' . $inquiry['id'] . '/status')) ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-Token': '<?= e(csrf_token()) ?>' },
            body: fd
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    document.querySelectorAll('.status-btn').forEach(function (b) {
                        b.classList.remove('active', 'btn-primary', 'btn-outline-primary');
                        b.classList.add('btn-outline-secondary');
                    });
                    btn.classList.add('active', 'btn-primary', 'btn-outline-primary');
                    btn.classList.remove('btn-outline-secondary');
                }
            });
    });
});
</script>
