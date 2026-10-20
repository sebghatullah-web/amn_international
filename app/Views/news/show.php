<div class="section pt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <article class="mb-5">
                    <?php if ($article['image']): ?>
                        <img src="<?= e(upload_url($article['image'])) ?>" alt="<?= e($article['title']) ?>" class="img-fluid rounded-3 mb-4" onerror="this.src='<?= e(asset('images/fallback-article.svg')) ?>'">
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-start small text-muted mb-3">
                        <span><?= __('news.published_on') ?> <?= e(format_date($article['published_at'])) ?></span>
                        <?php if ($article['author']): ?><span><?= __('news.by_author') ?> <?= e($article['author']) ?></span><?php endif; ?>
                        <?php if ($article['category']): ?><span class="badge text-bg-secondary"><?= e($article['category']) ?></span><?php endif; ?>
                    </div>
                    <h1 class="h2 fw-bold mb-4"><?= e($article['title']) ?></h1>
                    <div class="article-body">
                        <?= $bodyHtml ?>
                    </div>
                </article>

                <div class="card border-0 shadow-sm p-4 rounded-3 mb-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1"><?= __('common.related') ?></h5>
                        </div>
                        <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('news')) ?>"><?= __('news.backToList') ?></a>
                    </div>
                    <div class="row g-3 mt-3">
                        <?php foreach ($latest as $l): ?>
                            <?php if ($l['id'] == $article['id']) continue; ?>
                            <div class="col-12 col-md-4">
                                <div class="card border-0 h-100">
                                    <div class="card-body">
                                        <h6 class="fw-bold card-title mb-2"><a class="text-decoration-none text-reset" href="<?= e(url('news/' . $l['slug'])) ?>"><?= e($l['title']) ?></a></h6>
                                        <p class="card-text text-muted small mb-2"><?= e(mb_strimwidth($l['excerpt'] ?? '', 0, 100, '…')) ?></p>
                                        <a class="small text-primary text-decoration-none" href="<?= e(url('news/' . $l['slug'])) ?>"><?= __('common.read_more') ?> ›</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
