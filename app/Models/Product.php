<?php

namespace App\Models;

/**
 * Products (catalog items).
 */
class Product extends Model
{
    protected static string $table = 'products';

    public const PER_PAGE = 12;

    public static function findBySlug(string $slug): ?array
    {
        $row = self::run(
            'SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM `' . static::$table . '` p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.slug = ? LIMIT 1',
            [$slug]
        )->fetch();
        return $row ?: null;
    }

    /**
     * Public catalog query with search, filters, sorting and pagination.
     */
    public static function search(array $filters, int $page, int $perPage = self::PER_PAGE): array
    {
        $where = ['p.is_active = 1'];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(p.name LIKE ? OR p.short_description LIKE ? OR p.country_of_origin LIKE ?)';
            $like = '%' . $filters['q'] . '%';
            array_push($params, $like, $like, $like);
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'p.category_id = ?';
            $params[] = (int)$filters['category_id'];
        }
        if (!empty($filters['flag']) && in_array($filters['flag'], ['featured', 'new', 'popular'], true)) {
            $where[] = 'p.is_' . $filters['flag'] . ' = 1';
        }

        $orderBy = 'p.created_at DESC, p.id DESC';
        switch ($filters['sort'] ?? 'latest') {
            case 'price_asc':
                $orderBy = 'CASE WHEN p.price_usd IS NULL THEN 1 ELSE 0 END, p.price_usd ASC, p.id DESC';
                break;
            case 'price_desc':
                $orderBy = 'CASE WHEN p.price_usd IS NULL THEN 1 ELSE 0 END, p.price_usd DESC, p.id DESC';
                break;
            case 'name':
                $orderBy = 'p.name ASC';
                break;
        }

        $whereSql = implode(' AND ', $where);
        $total = (int)self::run('SELECT COUNT(*) FROM `' . static::$table . '` p WHERE ' . $whereSql, $params)->fetchColumn();
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));

        $rows = self::run(
            'SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    (SELECT path FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS primary_image
             FROM `' . static::$table . '` p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE ' . $whereSql . '
             ORDER BY ' . $orderBy . '
             LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
            $params
        )->fetchAll();

        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages];
    }

    public static function featured(int $limit = 8): array
    {
        return self::section('is_featured', $limit);
    }

    public static function newArrivals(int $limit = 8): array
    {
        return self::section('is_new', $limit);
    }

    public static function popular(int $limit = 8): array
    {
        return self::section('is_popular', $limit);
    }

    private static function section(string $flag, int $limit): array
    {
        return self::run(
            'SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    (SELECT path FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS primary_image
             FROM `' . static::$table . '` p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.is_active = 1 AND p.' . $flag . ' = 1
             ORDER BY p.created_at DESC, p.id DESC
             LIMIT ' . (int)$limit
        )->fetchAll();
    }
    public static function related(int $categoryId, int $productId, int $limit = 4): array
    {
        return self::run(
            'SELECT p.*, c.name AS category_name, c.slug AS category_slug,
                    (SELECT path FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS primary_image
             FROM `' . static::$table . '` p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.is_active = 1 AND p.category_id = ? AND p.id != ?
             ORDER BY p.created_at DESC LIMIT ' . (int)$limit,
            [$categoryId, $productId]
        )->fetchAll();
    }

    /** Admin list with filters. */
    public static function adminSearch(array $filters, int $page, int $perPage = 15): array
    {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['q'])) {
            $where[] = '(p.name LIKE ? OR p.slug LIKE ?)';
            $like = '%' . $filters['q'] . '%';
            $params[] = $like;
            $params[] = $like;
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'p.category_id = ?';
            $params[] = (int)$filters['category_id'];
        }
        if (isset($filters['active']) && $filters['active'] !== '') {
            $where[] = 'p.is_active = ?';
            $params[] = (int)$filters['active'];
        }
        $whereSql = implode(' AND ', $where);
        $total = (int)self::run('SELECT COUNT(*) FROM `' . static::$table . '` p WHERE ' . $whereSql, $params)->fetchColumn();
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));
        $rows = self::run(
            'SELECT p.*, c.name AS category_name,
                    (SELECT path FROM product_images pi WHERE pi.product_id = p.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS primary_image
             FROM `' . static::$table . '` p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE ' . $whereSql . '
             ORDER BY p.created_at DESC, p.id DESC
             LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
            $params
        )->fetchAll();
        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages];
    }

    public static function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = $slug;
        $i = 1;
        while (true) {
            $sql = 'SELECT COUNT(*) FROM `' . static::$table . '` WHERE slug = ?';
            $params = [$slug];
            if ($ignoreId) {
                $sql .= ' AND id != ?';
                $params[] = $ignoreId;
            }
            if ((int)self::run($sql, $params)->fetchColumn() === 0) {
                return $slug;
            }
            $i++;
            $slug = $base . '-' . $i;
        }
    }

    /** Technical specifications. */
    public static function specs(int $productId): array
    {
        return self::run(
            'SELECT * FROM product_specs WHERE product_id = ? ORDER BY sort_order ASC, id ASC',
            [$productId]
        )->fetchAll();
    }

    public static function syncSpecs(int $productId, array $specs): void
    {
        self::run('DELETE FROM product_specs WHERE product_id = ?', [$productId]);
        $sort = 1;
        foreach ($specs as $spec) {
            $label = trim($spec['label'] ?? '');
            $value = trim($spec['value'] ?? '');
            if ($label === '' || $value === '') {
                continue;
            }
            self::run(
                'INSERT INTO product_specs (product_id, label, value, sort_order) VALUES (?, ?, ?, ?)',
                [$productId, mb_substr($label, 0, 120), mb_substr($value, 0, 500), $sort++]
            );
        }
    }

    /** Price label: USD + AFN or "Request a Quote". */
    public static function priceLabel($row): string
    {
        if (empty($row['is_price_published']) || $row['price_usd'] === null || $row['price_usd'] === '') {
            return __('products.quote_instead');
        }
        return format_price($row['price_usd']);
    }
}
