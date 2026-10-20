<div class="section pt-4">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-5 fw-bold mb-3"><?= e(__('about.title')) ?></h1>
                <p class="lead text-muted mb-4"><?= e(__('about.subtitle')) ?></p>
            </div>
        </div>

        <div class="row g-5 mb-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 p-lg-5">
                        <h2 class="h4 fw-bold mb-3"><?= icon('bolt', 24) ?> <?= e(__('about.our_story')) ?></h2>
                        <p class="mb-4"><?= e(setting('about_story', 'AMN International began with a simple mission: connect verified manufacturers around the world with businesses that need reliable imported goods. Over the years we have built a network of trusted suppliers, freight partners, and customs brokers who help us deliver consistent quality — on time and on specification.')) ?></p>
                        <h2 class="h4 fw-bold mb-3"><?= icon('shield', 24) ?> <?= e(__('about.our_mission')) ?></h2>
                        <p class="mb-4"><?= e(setting('about_mission', 'To make international sourcing simple, transparent and affordable for businesses of every size — from small retailers to large distributors — by managing every step of the import process from factory selection to final delivery.')) ?></p>
                        <h2 class="h4 fw-bold mb-3"><?= icon('leaf', 24) ?> <?= e(__('about.our_vision')) ?></h2>
                        <p class="mb-0"><?= e(setting('about_vision', 'To be the most trusted import partner in the region, known for verified quality, honest pricing and dependable logistics.')) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="stat-card text-center p-4 border rounded-3 bg-light">
                            <div class="stat-number fs-4 fw-bold text-primary"><?= e(setting('stats_years', '25+')) ?></div>
                            <div class="stat-label small text-muted"><?= e(__('home.stats_years')) ?></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card text-center p-4 border rounded-3 bg-light">
                            <div class="stat-number fs-4 fw-bold text-primary"><?= e(setting('stats_clients', '500+')) ?></div>
                            <div class="stat-label small text-muted"><?= e(__('home.stats_clients')) ?></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card text-center p-4 border rounded-3 bg-light">
                            <div class="stat-number fs-4 fw-bold text-primary"><?= e(setting('stats_shipments', '12k+')) ?></div>
                            <div class="stat-label small text-muted"><?= e(__('home.stats_shipments')) ?></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card text-center p-4 border rounded-3 bg-light">
                            <div class="stat-number fs-4 fw-bold text-primary"><?= e(setting('stats_countries', '40+')) ?></div>
                            <div class="stat-label small text-muted"><?= e(__('home.stats_countries')) ?></div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 p-4 border rounded-3 bg-light">
                    <h2 class="h5 fw-bold mb-3"><?= icon('quote', 20) ?> <?= e(__('about.values_title')) ?></h2>
                    <ul class="list-unstyled small">
                        <li class="mb-2 d-flex align-items-start"><?= icon('check', 16) ?> <span class="ms-2">Verified suppliers only — every manufacturer audited before onboarding.</span></li>
                        <li class="mb-2 d-flex align-items-start"><?= icon('check', 16) ?> <span class="ms-2">Quality inspection before every shipment leaves the factory.</span></li>
                        <li class="mb-2 d-flex align-items-start"><?= icon('check', 16) ?> <span class="ms-2">Transparent pricing — USD prices with local afghani equivalents.</span></li>
                        <li class="mb-2 d-flex align-items-start"><?= icon('check', 16) ?> <span class="ms-2">Dedicated account manager for every business client.</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="h3 fw-bold mb-3"><?= e(__('about.cta_title')) ?></h2>
                <p class="text-muted mb-4"><?= e(__('about.cta_text')) ?></p>
                <a class="btn btn-primary" href="<?= e(url('quote')) ?>"><?= icon('quote', 16) ?> <?= e(__('common.free_quote')) ?></a>
            </div>
        </div>
    </div>
</div>
