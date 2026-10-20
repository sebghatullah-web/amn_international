<div class="section pt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="product-main-image position-relative border rounded-3 overflow-hidden bg-light" style="aspect-ratio: 4/5;">
                            <?php if (!empty($images[0]['path'])): ?>
                                <img src="<?= e(upload_url($images[0]['path'])) ?>" alt="<?= e($product['name']) ?>" class="img-fluid w-100 h-100 object-fit-cover" loading="eager" onerror="this.src='<?= e(asset('images/fallback-product.svg')) ?>'">
                            <?php else: ?>
                                <div class="img-fluid w-100 h-100 d-flex align-items-center justify-content-center text-muted bg-light"><?= icon('box', 80) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (count($images) > 1): ?>
                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 overflow-hidden bg-light" style="aspect-ratio: 4/5;">
                                <img src="<?= e(upload_url($images[1]['path'] ?? '')) ?>" alt="<?= e($product['name']) ?>" class="img-fluid w-100 h-100 object-fit-cover" loading="lazy" onerror="this.src='<?= e(asset('images/fallback-product.svg')) ?>'">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="border rounded-3 overflow-hidden bg-light" style="aspect-ratio: 4/5;">
                                <img src="<?= e(upload_url($images[2]['path'] ?? '')) ?>" alt="<?= e($product['name']) ?>" class="img-fluid w-100 h-100 object-fit-cover" loading="lazy" onerror="this.src='<?= e(asset('images/fallback-product.svg')) ?>'">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-info h-100 d-flex flex-column">
                    <ol class="breadcrumb small mb-3 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?= e(url('/')) ?>"><?= __('common.home') ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= e(url('products')) ?>"><?= __('common.products') ?></a></li>
                        <li class="breadcrumb-item active"><?= e($product['name']) ?></li>
                    </ol>
                    <span class="badge text-bg-<?= e($product['is_price_published'] ? 'primary' : 'secondary') ?> mb-3 d-inline-block">
                        <?php if ($product['is_price_published'] && $product['price_usd'] !== null): ?>
                            <?= e(Product::priceLabel($product)) ?>
                        <?php else: ?>
                            <?= e(__('products.price_on_request')) ?>
                        <?php endif; ?>
                    </span>
                    <h1 class="h2 fw-bold mb-3"><?= e($product['name']) ?></h1>
                    <p class="text-muted mb-4"><?= e($product['short_description']) ?></p>
                    <div class="row mb-4">
                        <div class="col-6 mb-2">
                            <span class="text-muted small d-block"><?= __('products.origin_label') ?></span>
                            <span class="text-reset"><?= e($product['country_of_origin'] ?? __('common.all')) ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="text-muted small d-block"><?= __('products.availability_label') ?></span>
                            <span class="text-reset"><?= availability_badge($product['availability_status'] ?? 'in_stock') ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="text-muted small d-block"><?= __('products.sku_label') ?></span>
                            <span class="text-reset">AMN-<?= strtoupper(substr($product['slug'], 0, 8)) ?></span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="text-muted small d-block">Reference</span>
                            <span class="text-reset">#<?= (int)$product['id'] ?></span>
                        </div>
                    </div>

                    <?php if (!empty($specs)): ?>
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2 border-bottom pb-1"><?= __('common.specifications') ?></h6>
                            <table class="table table-sm table-borderless small">
                                <?php foreach ($specs as $spec): ?>
                                    <tr>
                                        <td class="text-muted" style="width: 40%"><?= e($spec['label']) ?></td>
                                        <td class="text-reset"><?= e($spec['value']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2 mt-auto">
                        <a class="btn btn-primary btn-lg" href="<?= e(url('quote')) ?>?product=<?= e($product['slug']) ?>">
                            <?= icon('quote', 16) ?> <?= e(__('products.quote_instead')) ?>
                        </a>
                        <a class="btn btn-outline-secondary" href="<?= e(url('products')) ?>"><?= __('common.back') ?></a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($related)): ?>
            <div class="row mt-5">
                <div class="col-12 mb-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3"><?= __('common.related_products') ?></h5>
                </div>
                <div class="row g-4">
                    <?php foreach ($related as $p): ?>
                        <div class="col-12 col-sm-6 col-md-3">
                            <?php include __DIR__ . '/../partials/product-card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
