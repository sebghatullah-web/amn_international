<?php

namespace App\Core;

/**
 * Minimal logger writing to storage/logs/app.log.
 */
class Logger
{
    public static function error(string $message): void
    {
        $dir = BASE_PATH . '/storage/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $line = sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $message);
        @file_put_contents($dir . '/app.log', $line, FILE_APPEND | LOCK_EX);
    }
}
