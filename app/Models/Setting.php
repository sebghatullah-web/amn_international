<?php

namespace App\Models;

/**
 * Key/value website settings (company info, contact, hero, currency, SEO...).
 */
class Setting extends Model
{
    protected static string $table = 'settings';

    private static array $cache = [];

    /** Load all settings once per request. */
    public static function load(): void
    {
        if (self::$cache) {
            return;
        }
        $rows = self::db()->query('SELECT skey, svalue FROM `' . static::$table . '`')->fetchAll(\PDO::FETCH_KEY_PAIR);
        self::$cache = $rows;
    }

    public static function get(string $key, $default = '')
    {
        self::load();
        return array_key_exists($key, self::$cache) ? self::$cache[$key] : $default;
    }

    public static function allSettings(): array
    {
        self::load();
        return self::$cache;
    }

    public static function set(string $key, $value): void
    {
        self::run(
            'INSERT INTO `' . static::$table . '` (skey, svalue) VALUES (?, ?) ON DUPLICATE KEY UPDATE svalue = VALUES(svalue)',
            [$key, $value]
        );
        self::$cache[$key] = $value;
    }

    public static function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            if ($value !== null) {
                self::set($key, (string)$value);
            }
        }
    }
}
