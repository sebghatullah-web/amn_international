<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    /** Public catalog page (also handles no-JS form submits). */
    public function index(): void
    {
        $filters = $this->filtersFromRequest();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Product::search($filters, $page);

        $this->view('products/index', [
            'title'       => $filters['q'] !== ''
                ? sprintf('%s: %s', __('products.title'), $filters['q'])
                : setting('seo_products_title', __('products.title') . ' | ' . setting('company_name')),
            'description' => setting('seo_products_description', __('products.subtitle')),
            'result'      => $result,
            'filters'     => $filters,
            'categories'  => Category::withCounts(),
        ]);
    }

    public function show(string $slug): void
    {
        $product = Product::findBySlug($slug);
        if (!$product || empty($product['is_active'])) {
            not_found_page();
        }
        $images = \App\Models\ProductImage::forProduct((int)$product['id']);
        $specs = Product::specs((int)$product['id']);

        $this->view('products/show', [
            'title'       => $product['seo_title'] ?: ($product['name'] . ' | ' . setting('company_name')),
            'description' => $product['seo_description'] ?: $product['short_description'],
            'product'     => $product,
            'images'      => $images,
            'specs'       => $specs,
            'related'     => Product::related((int)$product['category_id'], (int)$product['id'], 4),
            'breadcrumb'  => true,
        ]);
    }

    /** AJAX product search/filter (JSON). */
    public function search(): void
    {
        $filters = $this->filtersFromRequest();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Product::search($filters, $page);

        $this->json([
            'success' => true,
            'html'    => \App\Core\View::partial('partials/product-grid', ['products' => $result['rows']]),
            'total'   => $result['total'],
            'page'    => $result['page'],
            'pages'   => $result['pages'],
            'summary' => sprintf('%d %s', $result['total'], __('common.results_found')),
            'pager'   => pagination($result['page'], $result['pages'], 'ajax/products', $this->queryForPagination($filters), 'products-pagination'),
        ]);
    }

    private function filtersFromRequest(): array
    {
        $categoryId = (int)($_GET['category'] ?? 0);
        $category = null;
        if ($categoryId > 0) {
            $category = Category::find($categoryId);
            if (!$category) {
                $categoryId = 0;
            }
        }
        $sort = (string)($_GET['sort'] ?? 'latest');
        if (!in_array($sort, ['latest', 'price_asc', 'price_desc', 'name'], true)) {
            $sort = 'latest';
        }
        $flag = (string)($_GET['flag'] ?? '');
        if (!in_array($flag, ['featured', 'new', 'popular'], true)) {
            $flag = '';
        }
        return [
            'q'           => trim((string)($_GET['q'] ?? '')),
            'category_id' => $categoryId,
            'category'    => $category,
            'sort'        => $sort,
            'flag'        => $flag,
        ];
    }

    private function queryForPagination(array $filters): array
    {
        $query = [];
        if ($filters['q'] !== '') {
            $query['q'] = $filters['q'];
        }
        if ($filters['category_id']) {
            $query['category'] = $filters['category_id'];
        }
        if ($filters['sort'] !== 'latest') {
            $query['sort'] = $filters['sort'];
        }
        if ($filters['flag'] !== '') {
            $query['flag'] = $filters['flag'];
        }
        return $query;
    }
}
