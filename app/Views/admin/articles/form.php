<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= e($title) ?></h5>
    </div>
    <div class="card-body">
        <form id="article-form" method="post" action="<?= e($mode === 'edit' ? url('admin/articles/' . $article['id']) : url('admin/articles')) ?>" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?php if ($mode === 'edit'): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.title') ?></label>
                        <input type="text" name="title" class="form-control" required maxlength="200" value="<?= e($article['title'] ?? '') ?>">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.slug') ?></label>
                        <input type="text" name="slug" class="form-control" maxlength="200" value="<?= e($article['slug'] ?? '') ?>">
                        <div class="form-text"><?= __('admin.slug_auto') ?></div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('fields.details') ?> (excerpt)</label>
                        <textarea name="excerpt" class="form-control" rows="3" maxlength="500"><?= e($article['excerpt'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('fields.message') ?> (body, paragraphs separated by blank lines)</label>
                        <textarea name="body" class="form-control" rows="12" maxlength="10000"><?= e($article['body'] ?? '') ?></textarea>
                        <div class="form-text"><?= __('admin.content_hint') ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.image') ?></label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <?php if (!empty($article['image'])): ?><img src="<?= e(upload_url($article['image'])) ?>" class="img-thumbnail mt-2" style="max-width: 180px;"><?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control" maxlength="100" value="<?= e($article['author'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" maxlength="100" value="<?= e($article['category'] ?? '') ?>">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_published" value="1" <?= checked_if($article['is_published'] ?? 1) ?> id="is_published">
                        <label class="form-check-label" for="is_published"><?= __('admin.published') ?></label>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.seo') ?> title</label>
                        <input type="text" name="seo_title" class="form-control" maxlength="200" value="<?= e($article['seo_title'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.seo') ?> description</label>
                        <input type="text" name="seo_description" class="form-control" maxlength="300" value="<?= e($article['seo_description'] ?? '') ?>">
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="<?= e(url('admin/articles')) ?>"><?= __('admin.cancel') ?></a>
                <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('article-form')?.addEventListener('submit', function (e) {
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
            if (data.success) { window.location.href = data.redirect || '<?= e(url('admin/articles')) ?>'; }
            else { alert(data.message || '<?= e(__('errors.generic')) ?>'); }
        })
        .catch(function () { btn.disabled = false; btn.innerHTML = orig; alert('<?= e(__('errors.generic')) ?>'); });
});
</script>
