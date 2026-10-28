<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Base model with PDO helpers. All queries use prepared statements.
 */
abstract class Model
{
    protected static string $table = '';

    public static function db(): PDO
    {
        return Database::connection();
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        return self::db()->query('SELECT * FROM `' . static::$table . '` ORDER BY ' . $orderBy)->fetchAll();
    }

    public static function find($id): ?array
    {
        $stmt = self::run('SELECT * FROM `' . static::$table . '` WHERE id = ? LIMIT 1', [$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function findBy(string $column, $value): ?array
    {
        $stmt = self::run('SELECT * FROM `' . static::$table . '` WHERE `' . $column . '` = ? LIMIT 1', [$value]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function where(string $column, $value, string $orderBy = 'id DESC'): array
    {
        return self::run('SELECT * FROM `' . static::$table . '` WHERE `' . $column . '` = ? ORDER BY ' . $orderBy, [$value])->fetchAll();
    }

    public static function count(string $where = '1=1', array $params = []): int
    {
        return (int)self::run('SELECT COUNT(*) FROM `' . static::$table . '` WHERE ' . $where, $params)->fetchColumn();
    }

    public static function insert(array $data): int
    {
        $columns = array_keys($data);
        $sql = 'INSERT INTO `' . static::$table . '` (`' . implode('`,`', $columns) . '`) VALUES (' .
            implode(',', array_fill(0, count($columns), '?')) . ')';
        self::run($sql, array_values($data));
        return (int)self::db()->lastInsertId();
    }

    public static function updateById($id, array $data): bool
    {
        if (!$data) {
            return false;
        }
        $sets = implode(',', array_map(fn($c) => "`$c` = ?", array_keys($data)));
        $stmt = self::run('UPDATE `' . static::$table . '` SET ' . $sets . ' WHERE id = ?', array_merge(array_values($data), [$id]));
        return $stmt->rowCount() >= 0;
    }

    public static function deleteById($id): bool
    {
        self::run('DELETE FROM `' . static::$table . '` WHERE id = ?', [$id]);
        return true;
    }

    protected static function run(string $sql, array $params = []): \PDOStatement
    {
        return Database::run($sql, $params);
    }

    /** Count total pages for a given limit. */
    public static function pages(int $total, int $perPage): int
    {
        return max(1, (int)ceil($total / max(1, $perPage)));
    }
}
