<?php
/**
 * Application bootstrap: configuration, autoloading, session, error handling.
 */

// Keep secrets and application files private when the project root is the web root.
$denyDirs = ['app', 'config', 'database', 'storage', 'tools'];
// (Access control for these directories is also enforced by .htaccess files.)

if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    exit('PHP 8.0 or newer is required to run this application.');
}

$config = require BASE_PATH . '/config/config.php';

define('APP_ENV', $config['env']);
define('APP_DEBUG', $config['debug']);

date_default_timezone_set($config['timezone']);
mb_internal_encoding('UTF-8');

// ---------------------------------------------------------------------------
// Autoloading (PSR-4 style for App\ namespace + helper files)
// ---------------------------------------------------------------------------
spl_autoload_register(function (string $class) {
    if (strpos($class, 'App\\') !== 0) {
        return;
    }
    $relative = substr($class, 4); // strip "App\"
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require_once BASE_PATH . '/app/Helpers/helpers.php';

// ---------------------------------------------------------------------------
// Error / exception handling
// ---------------------------------------------------------------------------
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');

set_error_handler(function (int $no, string $str, string $file = '', int $line = 0) {
    if (!(error_reporting() & $no)) {
        return false;
    }
    App\Core\Logger::error("$str in $file on line $line");
    if (APP_DEBUG) {
        return false; // let PHP render it in dev
    }
    return true;
});

set_exception_handler(function (Throwable $e) {
    App\Core\Logger::error('Uncaught: ' . get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
    if (!headers_sent()) {
        http_response_code(500);
    }
    if (App\Core\Auth::check()) {
        // Authenticated admins may see details in local environment.
        if (APP_DEBUG) {
            echo '<h1>Application error</h1><pre>' . htmlspecialchars($e->getMessage() . "\n\n" . $e->getTraceAsString(), ENT_QUOTES, 'UTF-8') . '</pre>';
            exit;
        }
    }
    $view = BASE_PATH . '/app/Views/errors/error.php';
    if (is_file($view)) {
        $vars = ['message' => APP_DEBUG ? $e->getMessage() : 'We are sorry, something went wrong. Please try again later.'];
        extract($vars, EXTR_SKIP);
        include $view;
    } else {
        echo 'Internal server error.';
    }
    exit;
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        App\Core\Logger::error('Fatal: ' . $error['message'] . ' @ ' . $error['file'] . ':' . $error['line']);
    }
});

// ---------------------------------------------------------------------------
// Session
// ---------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_name($config['session_name']);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
    session_start();
}

// ---------------------------------------------------------------------------
// Base URL detection (supports subfolder installs such as XAMPP htdocs)
// ---------------------------------------------------------------------------
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath = rtrim(dirname($scriptName), '/\\');
if ($config['base_url'] !== '') {
    $basePath = $config['base_url'];
}
define('BASE_URL', $basePath);
define('ASSET_URL', $basePath . '/assets');
define('UPLOAD_URL', $basePath . '/uploads');

// Load UI language strings.
$lang_code = 'en'; // Future languages can be switched here / via settings.
require_once BASE_PATH . '/app/Helpers/lang/' . $lang_code . '.php';
