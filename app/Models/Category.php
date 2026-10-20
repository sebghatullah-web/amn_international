<?php

namespace App\Models;

/**
 * Product categories.
 */
class Category extends Model
{
    protected static string $table = 'categories';

    /** Active categories for the public site. */
    public static function active(): array
    {
        return self::run(
            'SELECT * FROM `' . static::$table . '` WHERE is_active = 1 ORDER BY sort_order ASC, name ASC'
        )->fetchAll();
    }

    /** Active categories with product counts. */
    public static function withCounts(): array
    {
        return self::run(
            'SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id AND p.is_active = 1) AS products_count
             FROM `' . static::$table . '` c
             WHERE c.is_active = 1
             ORDER BY c.sort_order ASC, c.name ASC'
        )->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    /** Search + paginate for admin. */
    public static function search(string $q, int $page, int $perPage = 15): array
    {
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND (name LIKE ? OR slug LIKE ?)';
            $params = ['%' . $q . '%', '%' . $q . '%'];
        }
        $total = self::count($where, $params);
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));
        $rows = self::run(
            'SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS products_count
             FROM `' . static::$table . '` c WHERE ' . $where . '
             ORDER BY c.sort_order ASC, c.name ASC LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
            $params
        )->fetchAll();
        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages];
    }

    /** Unique slug helper (optionally ignoring one id during edits). */
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
}
