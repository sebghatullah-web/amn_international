<?php

namespace App\Models;

/**
 * Company services.
 */
class Service extends Model
{
    protected static string $table = 'services';

    public static function active(): array
    {
        return self::run('SELECT * FROM `' . static::$table . '` WHERE is_active = 1 ORDER BY sort_order ASC, id ASC')->fetchAll();
    }

    public static function search(string $q, int $page, int $perPage = 15): array
    {
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND (title LIKE ? OR slug LIKE ?)';
            $params = ['%' . $q . '%', '%' . $q . '%'];
        }
        $total = self::count($where, $params);
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));
        $rows = self::run(
            'SELECT * FROM `' . static::$table . '` WHERE ' . $where . ' ORDER BY sort_order ASC, id ASC LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
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
}
