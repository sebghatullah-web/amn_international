<div class="section pt-4">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="h3 fw-bold"><?= e($title) ?></h1>
                <p class="text-muted"><?= e($description) ?></p>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($services as $s): ?>
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card service-card h-100 border-0 shadow-sm">
                        <div class="service-icon bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <?= icon('bolt', 30) ?>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="card-title fw-bold"><?= e($s['title']) ?></h3>
                            <p class="card-text text-muted small"><?= e($s['short_description']) ?></p>
                            <a class="btn btn-sm btn-outline-primary mt-3" href="<?= e(url('quote')) ?>?product=<?= e($s['slug']) ?>"><?= e(__('common.free_quote')) ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($services)): ?>
                <div class="col-12">
                    <p class="text-center text-muted py-5"><?= __('common.no_products') ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm p-4 rounded-3 bg-light">
                    <h3 class="fw-bold mb-3"><?= icon('truck', 20) ?> <?= __('home.about_title') ?></h3>
                    <p><?= e(setting('about_story', __('about.subtitle'))) ?></p>
                    <a class="btn btn-primary mt-3" href="<?= e(url('about')) ?>"><?= __('home.about_link') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
