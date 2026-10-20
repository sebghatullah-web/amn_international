<div class="section pt-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="h3 fw-bold"><?= e($title) ?></h1>
                <p class="text-muted"><?= e($description) ?></p>
            </div>
        </div>

        <form class="row mb-4" method="get" action="<?= e(url('news')) ?>" role="search">
            <div class="col-12 col-md-8">
                <div class="input-group input-group-sm">
                    <span class="input-group-text border-end-0"><?= icon('search', 18) ?></span>
                    <input class="form-control border-start-0" type="search" name="q" placeholder="Search articles..." value="<?= e($q) ?>">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <button class="btn btn-primary w-100" type="submit"><?= __('common.search') ?></button>
            </div>
        </form>

        <div class="row g-4">
            <?php foreach ($result['rows'] as $a): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card article-card h-100 border-0 shadow-sm">
                        <?php if ($a['image']): ?>
                            <img src="<?= e(upload_url($a['image'])) ?>" alt="<?= e($a['title']) ?>" class="card-img-top" loading="lazy" onerror="this.src='<?= e(asset('images/fallback-article.svg')) ?>'">
                        <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted" style="height: 180px;"><?= icon('doc', 40) ?></div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="badge text-bg-secondary small mb-2 d-inline-block"><?= e($a['category'] ?? __('common.news')) ?></span>
                            <h3 class="card-title fw-bold h5 mb-2"><a class="text-decoration-none text-reset" href="<?= e(url('news/' . $a['slug'])) ?>"><?= e($a['title']) ?></a></h3>
                            <p class="card-text text-muted small mb-3"><?= e(mb_strimwidth($a['excerpt'] ?? '', 0, 140, '…')) ?></p>
                            <div class="d-flex justify-content-between align-items-center small text-muted">
                                <span><?= __('news.published_on') ?> <?= e(format_date($a['published_at'])) ?></span>
                                <?php if ($a['author']): ?><span><?= __('news.by_author') ?> <?= e($a['author']) ?></span><?php endif; ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
            <?php if (empty($result['rows'])): ?>
                <div class="col-12">
                    <p class="text-center text-muted py-5"><?= __('news.empty') ?></p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($result['pages'] > 1): ?>
            <nav aria-label="Pagination" class="mt-4">
                <?php
                $q = $q !== '' ? ['q' => $q] : [];
                echo pagination($result['page'], $result['pages'], 'news', $q, 'news-pagination');
                ?>
            </nav>
        <?php endif; ?>
    </div>
</div>
