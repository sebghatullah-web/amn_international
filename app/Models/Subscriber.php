<?php

namespace App\Models;

/**
 * Newsletter subscribers.
 */
class Subscriber extends Model
{
    protected static string $table = 'subscribers';

    public static function subscribe(string $email): string
    {
        $existing = self::findBy('email', $email);
        if ($existing) {
            if (empty($existing['is_active'])) {
                self::updateById($existing['id'], ['is_active' => 1]);
                return 'ok';
            }
            return 'already';
        }
        self::insert([
            'email'      => $email,
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return 'ok';
    }

    public static function search(string $q, int $page, int $perPage = 20): array
    {
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND email LIKE ?';
            $params[] = '%' . $q . '%';
        }
        $total = self::count($where, $params);
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));
        $rows = self::run(
            'SELECT * FROM `' . static::$table . '` WHERE ' . $where . ' ORDER BY created_at DESC LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
            $params
        )->fetchAll();
        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages];
    }
}
