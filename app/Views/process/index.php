<div class="section pt-4">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="h3 fw-bold"><?= e($title) ?></h1>
                <p class="text-muted"><?= e($description) ?></p>
            </div>
        </div>

        <div class="row g-4">
            <?php $stepNo = 1; foreach ($steps as $step): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card step-card h-100 border-0 shadow-sm">
                        <div class="card-header border-0 bg-transparent py-3 d-flex align-items-start">
                            <span class="step-number me-3"><?= $stepNo++ ?></span>
                            <div>
                                <h5 class="card-title fw-bold mb-1"><?= e($step['title']) ?></h5>
                                <span class="badge text-bg-primary small"><?= e($step['group_name'] ?? __('common.step')) ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= e($step['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($steps)): ?>
                <div class="col-12">
                    <p class="text-center text-muted py-5"><?= __('common.no_products') ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm p-5 rounded-4 text-center bg-primary text-white">
                    <h2 class="fw-bold mb-3"><?= e(__('process.cta_title')) ?></h2>
                    <p class="mb-4 opacity-90"><?= e(__('process.cta_text')) ?></p>
                    <a class="btn btn-light btn-lg" href="<?= e(url('quote')) ?>"><?= icon('quote', 16) ?> <?= e(__('common.free_quote')) ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
