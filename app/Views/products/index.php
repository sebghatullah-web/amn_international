<div class="section pt-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 fw-bold"><?= e($title) ?></h1>
                <p class="text-muted"><?= e($description) ?></p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><?= __('products.filters') ?></h6>
                        <form id="product-filters" method="get" action="<?= e(url('products')) ?>" novalidate>
                            <label class="form-label small text-uppercase fw-bold mb-2"><?= __('common.filter_category') ?></label>
                            <select class="form-select form-select-sm mb-3" name="category" id="category-filter">
                                <option value=""><?= __('common.all_categories') ?></option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= (int)$cat['id'] ?>" <?= selected_if($filters['category_id'], $cat['id']) ?>><?= e($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label class="form-label small text-uppercase fw-bold mb-2 d-block mt-3"><?= __('common.sort_by') ?></label>
                            <select class="form-select form-select-sm mb-3" name="sort" id="sort-filter">
                                <option value="latest" <?= selected_if($filters['sort'], 'latest') ?>><?= __('common.sort_latest') ?></option>
                                <option value="price_asc" <?= selected_if($filters['sort'], 'price_asc') ?>><?= __('common.sort_price_asc') ?></option>
                                <option value="price_desc" <?= selected_if($filters['sort'], 'price_desc') ?>><?= __('common.sort_price_desc') ?></option>
                                <option value="name" <?= selected_if($filters['sort'], 'name') ?>><?= __('common.sort_name') ?></option>
                            </select>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" type="submit"><?= __('common.filter_category') ?></button>
                                <a class="btn btn-link text-decoration-none text-reset small" href="<?= e(url('products')) ?>"><?= __('common.clear_filters') ?></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div>
                        <span class="badge text-bg-secondary me-2" id="products-total"><?= $result['total'] ?> <?= __('common.results_found') ?></span>
                    </div>
                    <div class="d-none d-md-flex gap-2">
                        <span class="small text-muted"><?= __('common.showing') ?></span>
                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('products')) ?>?flag=featured&category=<?= $filters['category_id'] ?>"><?= __('admin.flag_featured') ?></a>
                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('products')) ?>?flag=new&category=<?= $filters['category_id'] ?>"><?= __('admin.flag_new') ?></a>
                        <a class="btn btn-sm btn-outline-primary" href="<?= e(url('products')) ?>?flag=popular&category=<?= $filters['category_id'] ?>"><?= __('admin.flag_popular') ?></a>
                    </div>
                </div>

                <div id="products-grid-container">
                    <?php $products = $result['rows']; include __DIR__ . '/../partials/product-grid.php'; ?>
                </div>

                <?php if ($result['pages'] > 1): ?>
                    <nav aria-label="Pagination" class="mt-4">
                        <?php
                        $q = ['category' => $filters['category_id'], 'sort' => $filters['sort'], 'flag' => $filters['flag']];
                        if ($filters['q'] !== '') $q['q'] = $filters['q'];
                        unset($q['']);
                        echo pagination($result['page'], $result['pages'], 'products', $q, 'products-pagination');
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('product-filters');
    var grid = document.getElementById('products-grid-container');
    var totalBadge = document.getElementById('products-total');
    var sortSelect = document.getElementById('sort-filter');
    var categorySelect = document.getElementById('category-filter');

    if (!form || !grid) return;

    function debounce(fn, ms) {
        var t;
        return function () {
            clearTimeout(t);
            t = setTimeout(fn, ms);
        };
    }

    function reload() {
        var fd = new FormData(form);
        var params = new URLSearchParams();
        fd.forEach(function (v, k) { if (v !== '') params.set(k, v); });
        params.set('ajax', '1');

        fetch('<?= e(url('ajax/products')) ?>?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    grid.innerHTML = data.html;
                    totalBadge.textContent = data.total + ' <?= e(__('common.results_found')) ?>;
                    window.history.replaceState(null, '', '<?= e(url('products')) ?>?' + params.toString());
                }
            })
            .catch(function () {
                grid.innerHTML = '<div class="text-center py-4 text-danger"><?= e(__('errors.generic')) ?></div>';
            });
    }

    sortSelect.addEventListener('change', debounce(reload, 300));
    categorySelect.addEventListener('change', debounce(reload, 300));
});
</script>
