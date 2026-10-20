<?php

namespace App\Models;

/**
 * Admin users.
 */
class User extends Model
{
    protected static string $table = 'users';

    public static function findByEmail(string $email): ?array
    {
        return self::findBy('email', $email);
    }

    public static function touchLastLogin(int $id): void
    {
        self::run('UPDATE `' . static::$table . '` SET last_login = NOW() WHERE id = ?', [$id]);
    }

    public static function create(string $name, string $email, string $password, string $role = 'admin'): int
    {
        return self::insert([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => $role,
            'is_active'=> 1,
        ]);
    }
}
