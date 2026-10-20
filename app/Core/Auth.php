<?php

namespace App\Core;

use App\Models\User;

/**
 * Admin authentication.
 */
class Auth
{
    public static function check(): bool
    {
        return !empty($_SESSION['admin_id']);
    }

    public static function id(): ?int
    {
        return isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : null;
    }

    public static function user(): ?array
    {
        $id = self::id();
        if ($id === null) {
            return null;
        }
        return User::find($id);
    }

    public static function attempt(string $email, string $password): bool
    {
        // Simple brute-force protection based on the session.
        $tries = $_SESSION['_login_tries'] ?? 0;
        $lockedUntil = $_SESSION['_login_locked_until'] ?? 0;
        if ($tries >= 5 && time() < $lockedUntil) {
            return false;
        }

        $user = User::findByEmail($email);
        if ($user && $user['is_active'] && password_verify($password, $user['password'])) {
            Session::regenerate();
            $_SESSION['admin_id'] = (int)$user['id'];
            unset($_SESSION['_login_tries'], $_SESSION['_login_locked_until']);
            User::touchLastLogin((int)$user['id']);
            return true;
        }

        $_SESSION['_login_tries'] = $tries + 1;
        if ($_SESSION['_login_tries'] >= 5) {
            $_SESSION['_login_locked_until'] = time() + 300; // 5 minutes
        }
        return false;
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}
