<?php

namespace App\Models;

/**
 * Quote requests and contact messages.
 */
class Inquiry extends Model
{
    protected static string $table = 'inquiries';

    public static function search(array $filters, int $page, int $perPage = 20): array
    {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['type']) && in_array($filters['type'], ['quote', 'contact'], true)) {
            $where[] = 'type = ?';
            $params[] = $filters['type'];
        }
        if (!empty($filters['status']) && in_array($filters['status'], ['new', 'read', 'replied'], true)) {
            $where[] = 'status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['q'])) {
            $where[] = '(name LIKE ? OR email LIKE ? OR message LIKE ? OR phone LIKE ?)';
            $like = '%' . $filters['q'] . '%';
            array_push($params, $like, $like, $like, $like);
        }
        $whereSql = implode(' AND ', $where);
        $total = self::count($whereSql, $params);
        $pages = self::pages($total, $perPage);
        $page = max(1, min($page, $pages));
        $rows = self::run(
            'SELECT i.*, p.name AS product_name
             FROM `' . static::$table . '` i
             LEFT JOIN products p ON p.id = i.product_id
             WHERE ' . $whereSql . '
             ORDER BY i.created_at DESC LIMIT ' . ($page - 1) * $perPage . ',' . $perPage,
            $params
        )->fetchAll();
        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages];
    }

    public static function newCount(): int
    {
        return self::count("status = 'new'");
    }

    public static function recent(int $limit = 8): array
    {
        return self::run(
            'SELECT i.*, p.name AS product_name FROM `' . static::$table . '` i
             LEFT JOIN products p ON p.id = i.product_id
             ORDER BY i.created_at DESC LIMIT ' . (int)$limit
        )->fetchAll();
    }

    /** Store a validated inquiry. */
    public static function record(array $data, string $type): int
    {
        return self::insert([
            'type'        => $type,
            'name'        => mb_substr($data['name'] ?? '', 0, 120),
            'email'       => mb_substr($data['email'] ?? '', 0, 190),
            'phone'       => mb_substr($data['phone'] ?? '', 0, 60),
            'country'     => mb_substr($data['country'] ?? '', 0, 80),
            'subject'     => mb_substr($data['subject'] ?? '', 0, 255),
            'product_id'  => !empty($data['product_id']) ? (int)$data['product_id'] : null,
            'quantity'    => mb_substr($data['quantity'] ?? '', 0, 60),
            'message'     => mb_substr($data['message'] ?? '', 0, 5000),
            'ip_address'  => substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
            'status'      => 'new',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}
