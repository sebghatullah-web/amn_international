<?php

namespace App\Models;

/** Newsletter subscribers model (handles top-level table + queries). */
class Newsletter extends Model
{
    protected static string $table = 'subscribers';

    public static function findByEmail(string $email): ?array
    {
        return self::findBy('email', $email);
    }
}
