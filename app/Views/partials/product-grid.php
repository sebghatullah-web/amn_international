<div class="row g-4 product-grid">
    <?php if (empty($products)): ?>
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-3"><?= icon('box', 64) ?></div>
                <h5><?= __('common.no_products') ?></h5>
                <p class="text-muted"><?= __('home.explore_categories') ?></p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($products as $p): ?>
            <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                <?php include __DIR__ . '/product-card.php'; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
