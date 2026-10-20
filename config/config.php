<?php
/**
 * Application configuration.
 * Values are loaded from config/.env (see config/.env.example).
 */

if (!function_exists('load_env_file')) {
    function load_env_file(string $path): array
    {
        $vars = [];
        if (!is_readable($path)) {
            return $vars;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }
            $key = trim(substr($line, 0, $pos));
            $value = trim(substr($line, $pos + 1));
            if ($value !== '' && ($value[0] === '"' || $value[0] === "'") && substr($value, -1) === $value[0]) {
                $value = substr($value, 1, -1);
            }
            $vars[$key] = $value;
        }
        return $vars;
    }
}

$env = load_env_file(__DIR__ . '/.env');
$env_default = load_env_file(__DIR__ . '/.env.example');

static $config = null;
if ($config !== null) {
    return $config;
}

$get = function (string $key, $default = null) use ($env, $env_default) {
    if (array_key_exists($key, $env) && $env[$key] !== '') {
        return $env[$key];
    }
    if (array_key_exists($key, $env_default) && $env_default[$key] !== '') {
        return $env_default[$key];
    }
    return $default;
};

$bool = function ($value) {
    return in_array(strtolower((string)$value), ['1', 'true', 'yes', 'on'], true);
};

$config = [
    'app_name'        => $get('APP_NAME', 'AMN International'),
    'env'             => $get('APP_ENV', 'local'),
    'debug'           => $bool($get('APP_DEBUG', 'true')),
    'base_url'        => rtrim($get('APP_URL', ''), '/'),
    'timezone'        => $get('APP_TIMEZONE', 'Asia/Kabul'),
    'url_rewrite'     => $bool($get('URL_REWRITE', 'true')),
    'db' => [
        'host'    => $get('DB_HOST', '127.0.0.1'),
        'port'    => (int)$get('DB_PORT', 3306),
        'name'    => $get('DB_NAME', 'amn_international'),
        'user'    => $get('DB_USER', 'root'),
        'pass'    => $get('DB_PASS', ''),
        'charset' => $get('DB_CHARSET', 'utf8mb4'),
    ],
    'upload_max_kb'   => (int)$get('UPLOAD_MAX_KB', 3072),
    'session_name'    => $get('SESSION_NAME', 'AMN_SESS'),
    'log_file'        => BASE_PATH . '/storage/logs/app.log',
];

return $config;
