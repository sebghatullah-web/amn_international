<?php

namespace App\Models;

/**
 * Gallery images.
 */
class GalleryImage extends Model
{
    protected static string $table = 'gallery_images';

    public static function active(): array
    {
        return self::run('SELECT * FROM `' . static::$table . '` WHERE is_active = 1 ORDER BY sort_order ASC, id DESC')->fetchAll();
    }

    public static function search(string $q, int $page, int $perPage = 15): array
    {
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND (title LIKE ? OR group_name LIKE ?)';
            $params = ['%' . $q . '%', '%' . $q . '%'];
        }
        $total = self::count($where, $params);
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));
        $rows = self::run(
            'SELECT * FROM `' . static::$table . '` WHERE ' . $where . ' ORDER BY sort_order ASC, id DESC LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
            $params
        )->fetchAll();
        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages];
    }
}
