<?php

namespace App\Models;

/**
 * News & articles.
 */
class Article extends Model
{
    protected static string $table = 'articles';

    public static function published(int $page, int $perPage = 6, string $q = ''): array
    {
        $where = 'is_published = 1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND (title LIKE ? OR excerpt LIKE ? OR body LIKE ?)';
            $like = '%' . $q . '%';
            array_push($params, $like, $like, $like);
        }
        $total = self::count($where, $params);
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));
        $rows = self::run(
            'SELECT id, title, slug, excerpt, image, author, category, published_at, created_at
             FROM `' . static::$table . '` WHERE ' . $where . '
             ORDER BY published_at DESC, id DESC LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
            $params
        )->fetchAll();
        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages];
    }

    public static function latest(int $limit = 3): array
    {
        return self::run(
            'SELECT id, title, slug, excerpt, image, author, category, published_at
             FROM `' . static::$table . '` WHERE is_published = 1
             ORDER BY published_at DESC, id DESC LIMIT ' . (int)$limit
        )->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    public static function search(string $q, int $page, int $perPage = 15): array
    {
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND (title LIKE ? OR slug LIKE ? OR author LIKE ?)';
            $like = '%' . $q . '%';
            array_push($params, $like, $like, $like);
        }
        $total = self::count($where, $params);
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));
        $rows = self::run(
            'SELECT * FROM `' . static::$table . '` WHERE ' . $where . ' ORDER BY published_at DESC, id DESC LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
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

    /** Convert plain-text body into simple paragraphs. */
    public static function bodyToHtml(string $body): string
    {
        $paragraphs = preg_split('/\n\s*\n/', trim($body));
        $html = '';
        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if ($paragraph === '') {
                continue;
            }
            $html .= '<p>' . nl2br(e($paragraph)) . '</p>';
        }
        return $html;
    }
}
