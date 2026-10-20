<div class="card product-card h-100">
    <div class="position-relative product-media">
        <a class="d-block" href="<?= e(url('products/' . $p['slug'])) ?>">
            <?php if (!empty($p['primary_image'])): ?>
                <img src="<?= e(upload_url($p['primary_image'])) ?>" alt="<?= e($p['name']) ?>" class="card-img-top product-img" loading="lazy" onerror="this.src='<?= e(asset('images/fallback-product.svg')) ?>'">
            <?php else: ?>
                <div class="product-img bg-light d-flex align-items-center justify-content-center text-muted"><?= icon('box', 40) ?></div>
            <?php endif; ?>
            <?php if (!empty($p['is_featured'])): ?>
                <span class="badge bg-success position-absolute top-0 start-0 m-2"><?= e(__('admin.flag_featured')) ?></span>
            <?php endif; ?>
            <?php if (!empty($p['is_new'])): ?>
                <span class="badge bg-info text-dark position-absolute top-0 start-0 m-2"><?= e(__('admin.flag_new')) ?></span>
            <?php endif; ?>
            <?php if (!empty($p['is_popular'])): ?>
                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2"><?= e(__('admin.flag_popular')) ?></span>
            <?php endif; ?>
        </a>
        <button class="wishlist-btn position-absolute top-0 end-0 m-2 p-2 rounded-circle border-0 bg-white shadow-sm" aria-label="<?= e(__('common.wishlist', ['name' => $p['name']])) ?>">
            <?php if (!isset($wishlisted)): $wishlisted = false; endif; ?>
            <svg class="heart-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.05 1.56-4 3-0.95-1.44-2.24-3-4-3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.51 4.04 3 5.5"/></svg>
        </button>
    </div>
    <div class="card-body d-flex flex-column">
        <div class="mb-2">
            <a class="text-decoration-none d-block text-reset" href="<?= e(url('products/' . $p['slug'])) ?>">
                <h5 class="card-title product-title"><?= e($p['name']) ?></h5>
            </a>
            <span class="badge text-bg-<?= e($p['is_price_published'] ? 'primary' : 'secondary') ?>">
                <?php if ($p['is_price_published'] && $p['price_usd'] !== null): ?>
                    <?= e(\App\Models\Product::priceLabel($p)) ?>
                <?php else: ?>
                    <?= e(__('products.price_on_request')) ?>
                <?php endif; ?>
            </span>
        </div>
        <p class="card-text text-muted small mb-3"><?= e(mb_strimwidth($p['short_description'] ?? '', 0, 110, '…')) ?></p>
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="small text-muted"><?= e(__('products.origin_label')) ?>: <span class="text-reset"><?= e($p['country_of_origin'] ?? __('common.all')) ?></span></span>
            <span class="small text-muted"><?= e(__('products.availability_label')) ?>: <?= availability_badge($p['availability_status'] ?? 'in_stock') ?></span>
        </div>
        <div class="mt-auto d-grid gap-2">
            <a class="btn btn-sm btn-outline-secondary w-100" href="<?= e(url('products/' . $p['slug'])) ?>"><?= e(__('common.view_details')) ?></a>
            <?php if ($p['is_price_published'] && $p['price_usd'] !== null): ?>
                <a class="btn btn-sm btn-primary w-100" href="<?= e(url('quote')) ?>?product=<?= e($p['slug']) ?>"><?= e(__('products.quote_instead')) ?></a>
            <?php else: ?>
                <a class="btn btn-sm btn-primary w-100" href="<?= e(url('quote')) ?>?product=<?= e($p['slug']) ?>"><?= e(__('products.quote_instead')) ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>
