<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= e($title) ?></h5>
    </div>
    <div class="card-body">
        <form id="product-form" method="post" action="<?= e($mode === 'edit' ? url('admin/products/' . $product['id']) : url('admin/products')) ?>" enctype="multipart/form-data" novalidate>
            <?= csrf_field() ?>
            <?php if ($mode === 'edit'): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.name') ?></label>
                        <input type="text" name="name" class="form-control" required maxlength="150" value="<?= e($product['name'] ?? '') ?>">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.slug') ?></label>
                        <input type="text" name="slug" class="form-control" maxlength="150" value="<?= e($product['slug'] ?? '') ?>">
                        <div class="form-text"><?= __('admin.slug_auto') ?></div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.category') ?></label>
                        <select class="form-select" name="category_id" required>
                            <option value=""><?= __('common.all_categories') ?></option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>" <?= selected_if($product['category_id'] ?? '', $cat['id']) ?>><?= e($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label"><?= __('admin.price_usd') ?></label>
                        <div class="input-group"><span class="input-group-text">$</span><input type="text" name="price_usd" class="form-control" placeholder="0.00" value="<?= e($product['price_usd'] ?? '') ?>"><span class="input-group-text"><?= __('admin.price_hint') ?></span></div>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('products.availability_label') ?></label>
                        <select class="form-select" name="availability_status">
                            <?php foreach (availability_options() as $k => $v): ?><option value="<?= e($k) ?>" <?= selected_if($product['availability_status'] ?? 'in_stock', $k) ?>><?= e($v) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('products.origin_label') ?></label>
                        <input type="text" name="country_of_origin" class="form-control" placeholder="e.g. Germany" value="<?= e($product['country_of_origin'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3 d-flex flex-column gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" <?= checked_if($product['is_featured'] ?? 0) ?> id="is_featured">
                        <label class="form-check-label" for="is_featured"><?= __('admin.flag_featured') ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_new" value="1" <?= checked_if($product['is_new'] ?? 0) ?> id="is_new">
                        <label class="form-check-label" for="is_new"><?= __('admin.flag_new') ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_popular" value="1" <?= checked_if($product['is_popular'] ?? 0) ?> id="is_popular">
                        <label class="form-check-label" for="is_popular"><?= __('admin.flag_popular') ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_price_published" value="1" <?= checked_if($product['is_price_published'] ?? 0) ?> id="is_price_published">
                        <label class="form-check-label" for="is_price_published">Publish price</label>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><?= __('fields.details') ?> (short)</label>
                        <textarea name="short_description" class="form-control" rows="3" maxlength="500"><?= e($product['short_description'] ?? '') ?></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label"><?= __('fields.details') ?> (full)</label>
                        <textarea name="description" class="form-control" rows="6" maxlength="5000"><?= e($product['description'] ?? '') ?></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label d-flex justify-content-between align-items-center">
                    <span><?= __('common.specifications') ?></span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="add-spec"><?= icon('bolt', 14) ?> Add row</button>
                </label>
                <div id="specs-container" class="row g-2">
                    <?php $si = 0; foreach ($specs as $s): ?>
                        <div class="spec-row col-12">
                            <div class="row g-2">
                                <div class="col"><input type="text" name="specs[<?= $si ?>][label]" class="form-control" placeholder="e.g. Voltage" value="<?= e($s['label']) ?>" required></div>
                                <div class="col"><input type="text" name="specs[<?= $si ?>][value]" class="form-control" placeholder="e.g. 220V" value="<?= e($s['value']) ?>" required></div>
                                <div class="col-auto"><button type="button" class="btn btn-outline-danger btn-sm remove-spec"><?= icon('truck', 14) ?></button></div>
                            </div>
                        </div>
                    <?php $si++; endforeach; ?>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label d-flex justify-content-between align-items-center">
                    <span><?= __('admin.images') ?></span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="add-image-row"><?= icon('ship', 14) ?> Add upload</button>
                </label>
                <div id="images-container" class="row g-2">
                    <?php $ii = 0; foreach ($images as $img): ?>
                        <div class="image-row col-md-4 col-6">
                            <div class="card border-0 p-2 bg-light">
                                <img src="<?= e(upload_url($img['path'])) ?>" class="img-fluid rounded-2 mb-2" style="max-height: 120px; object-fit: cover;" onerror="this.src='<?= e(asset('images/fallback-product.svg')) ?>'">
                                <div class="d-flex gap-2">
                                    <input type="text" name="images[<?= $ii ?>][alt]" class="form-control form-control-sm" placeholder="Alt text" value="<?= e($img['alt']) ?>">
                                    <input type="hidden" name="images[<?= $ii ?>][id]" value="<?= (int)$img['id'] ?>">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-image" data-id="<?= (int)$img['id'] ?>"><?= icon('truck', 14) ?></button>
                                    <?php if ($img['is_primary']): ?><button type="button" class="btn btn-outline-primary btn-sm set-primary" data-id="<?= (int)$img['id'] ?>" title="Primary"><?= icon('cert', 14) ?></button><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php $ii++; endforeach; ?>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label"><?= __('admin.upload_image') ?></label>
                <div id="upload-rows-container" class="row g-2"></div>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4"><input type="file" class="form-control upload-image-input" name="upload_image_1" accept="image/*"></div>
                    <div class="col-md-3"><input type="text" class="form-control upload-alt-input" name="upload_alt_1" placeholder="Alt text"></div>
                    <div class="col-auto"><button type="button" class="btn btn-outline-secondary" id="add-upload-row"><?= icon('box', 14) ?> Add row</button></div>
                </div>
            </div>
            <hr>
            <div class="mb-4">
                <label class="form-label fw-bold"><?= __('admin.seo') ?></label>
                <div class="row g-3">
                    <div class="col-md-6"><input type="text" name="seo_title" class="form-control" placeholder="SEO title" maxlength="200" value="<?= e($product['seo_title'] ?? '') ?>"></div>
                    <div class="col-md-6"><input type="text" name="seo_description" class="form-control" placeholder="SEO description" maxlength="300" value="<?= e($product['seo_description'] ?? '') ?>"></div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" href="<?= e(url('admin/products')) ?>"><?= __('admin.cancel') ?></a>
                <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var specsContainer = document.getElementById('specs-container');
    var uploadRows = document.getElementById('upload-rows-container');
    var uploadCount = 1;
    var specIndex = 100;

    document.getElementById('add-spec')?.addEventListener('click', function () {
        var row = document.createElement('div');
        row.className = 'spec-row col-12';
        row.innerHTML = '<div class="row g-2">' +
            '<div class="col"><input type="text" name="specs[' + specIndex + '][label]" class="form-control" placeholder="Label" required></div>' +
            '<div class="col"><input type="text" name="specs[' + specIndex + '][value]" class="form-control" placeholder="Value" required></div>' +
            '<div class="col-auto"><button type="button" class="btn btn-outline-danger btn-sm remove-spec">✕</button></div>' +
            '</div>';
        specsContainer.appendChild(row);
        specIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-spec')) {
            e.target.closest('.spec-row').remove();
        }
    });

    document.getElementById('add-upload-row')?.addEventListener('click', function () {
        uploadCount++;
        var row = document.createElement('div');
        row.className = 'row g-2 align-items-end mb-2';
        row.innerHTML = '<div class="col-md-4"><input type="file" class="form-control upload-image-input" name="upload_image_' + uploadCount + '" accept="image/*"></div>' +
            '<div class="col-md-3"><input type="text" class="form-control upload-alt-input" name="upload_alt_' + uploadCount + '" placeholder="Alt text"></div>' +
            '<div class="col-auto"><button type="button" class="btn btn-outline-danger remove-upload-row">✕</button></div>';
        uploadRows.appendChild(row);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-upload-row')) {
            var row = e.target.closest('.row.g-2');
            if (row) row.remove();
        }
    });

    document.addEventListener('click', function (e) {
        var removeBtn = e.target.closest('.remove-image');
        if (removeBtn) {
            var imgId = removeBtn.getAttribute('data-id');
            if (!imgId) { removeBtn.closest('.image-row').remove(); return; }
            if (!confirm('<?= e(__('admin.delete_image_confirm')) ?>')) return;
            fetch('<?= e(url('admin/products/images/' . ((int)($product['id'] ?? 0)) . '/delete')) ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-Token': '<?= e(csrf_token()) ?>' },
                body: new FormData()
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) { removeBtn.closest('.image-row').remove(); }
                    else { alert(data.message || '<?= e(__('errors.generic')) ?>'); }
                });
            return;
        }

        var primaryBtn = e.target.closest('.set-primary');
        if (primaryBtn) {
            fetch('<?= e(url('admin/products/images/' . ((int)($product['id'] ?? 0)) . '/primary')) ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-Token': '<?= e(csrf_token()) ?>' },
                body: new FormData()
            })
                .then(function (r) { return r.json(); })
                .then(function (data) { if (data.success) { location.reload(); } else { alert(data.message || '<?= e(__('errors.generic')) ?>'); } });
        }
    });

    document.getElementById('product-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        var form = e.target;
        var btn = form.querySelector('button[type="submit"]');
        var orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<?= e(__('common.loading')) ?>...';

        var fd = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                btn.disabled = false;
                btn.innerHTML = orig;
                if (data.success) {
                    window.location.href = data.redirect || '<?= e(url('admin/products')) ?>';
                } else {
                    alert(data.message || '<?= e(__('errors.generic')) ?>');
                    if (data.errors) {
                        Object.keys(data.errors).forEach(function (field) {
                            var input = form.elements[field];
                            if (input && input.classList) {
                                input.classList.add('is-invalid');
                                if (input.nextElementSibling && input.nextElementSibling.classList.contains('invalid-feedback')) {
                                    input.nextElementSibling.textContent = data.errors[field];
                                }
                            }
                        });
                    }
                }
            })
            .catch(function () {
                btn.disabled = false;
                btn.innerHTML = orig;
                alert('<?= e(__('errors.generic')) ?>');
            });
    });
});
</script>
