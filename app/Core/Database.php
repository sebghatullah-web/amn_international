<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * PDO connection singleton.
 */
class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $config = self::config();
        $db = $config['db'];
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $db['host'], $db['port'], $db['name'], $db['charset']);

        try {
            self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ]);
        } catch (PDOException $e) {
            Logger::error('Database connection failed: ' . $e->getMessage());
            http_response_code(503);
            $view = BASE_PATH . '/app/Views/errors/error.php';
            if (is_file($view)) {
                $message = 'Database connection failed. Please check your configuration in config/.env and make sure the database is imported (see README.md).';
                include $view;
            } else {
                echo 'Database connection failed.';
            }
            exit;
        }

        return self::$pdo;
    }

    public static function config(): array
    {
        static $config = null;
        if ($config === null) {
            $config = require BASE_PATH . '/config/config.php';
        }
        return $config;
    }

    /** Run a prepared statement and return it. */
    public static function run(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
