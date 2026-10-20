<?php

use App\Core\Csrf;
use App\Models\Setting;

/**
 * Global helper functions.
 */

function config(string $key, $default = null)
{
    static $config = null;
    if ($config === null) {
        $config = require BASE_PATH . '/config/config.php';
    }
    $segments = explode('.', $key);
    $value = $config;
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

/** HTML escape. */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Build a URL for the current installation.
 * Uses pretty URLs when rewriting is available, otherwise /?p=/path fallback.
 */
function url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');
    if ($path === '/') {
        $path = '';
    }
    if ((bool)config('url_rewrite')) {
        return BASE_URL . $path;
    }
    $base = BASE_URL . '/';
    if ($path === '') {
        return $base;
    }
    return $base . '?p=' . rawurlencode($path);
}

function asset(string $path): string
{
    return ASSET_URL . '/' . ltrim($path, '/');
}

function upload_url(?string $path): string
{
    if (!$path) {
        return asset('images/fallback-product.svg');
    }
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }
    if (strpos($path, 'uploads/') === 0) {
        return UPLOAD_URL . '/' . substr($path, strlen('uploads/'));
    }
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Translation helper. Strings live in app/Helpers/lang/{code}.php.
 * Adding a new language later only requires a new file + a code switch.
 */
function __(string $key, array $replace = []): string
{
    static $strings = null;
    if ($strings === null) {
        $strings = $GLOBALS['lang_strings'] ?? [];
    }
    $text = $strings[$key] ?? $key;
    foreach ($replace as $search => $value) {
        $text = str_replace(':' . $search, (string)$value, $text);
    }
    return $text;
}

/** Read a website setting from the database (cached per request). */
function setting(string $key, $default = '')
{
    return Setting::get($key, $default);
}

/**
 * Format a USD price with its AFN equivalent.
 * Example: $100.00 USD · 7,000 AFN
 */
function format_price($usd): string
{
    $usd = (float)$usd;
    $rate = (float)setting('usd_afn_rate', '0');
    $afn = $rate > 0 ? round($usd * $rate) : 0;
    $usdLabel = setting('currency_usd_code', 'USD');
    $afnLabel = setting('currency_afn_code', 'AFN');

    $usdText = '$' . number_format($usd, 2) . ' ' . $usdLabel;
    $afnText = number_format($afn, 0) . ' ' . $afnLabel;
    return $usdText . ' · ' . $afnText;
}

function convert_usd_to_afn(float $usd): float
{
    $rate = (float)setting('usd_afn_rate', '0');
    return $rate > 0 ? round($usd * $rate) : 0;
}

/** Sanitize rich text stored by admins (small whitelist). */
function clean_html(string $html): string
{
    $allowed = '<p><br><strong><b><em><i><u><ul><ol><li><h2><h3><h4><a><blockquote><span>';
    $clean = strip_tags($html, $allowed);
    $clean = preg_replace('/\son\w+\s*=\s*("|\').*?\1/i', '', $clean);
    $clean = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*\1/i', 'href="#"', $clean);
    return $clean;
}

/** Turn any title into a URL slug. */
function slugify(string $text): string
{
    $text = mb_strtolower(trim($text), 'UTF-8');
    $text = preg_replace('/[^\pL\pN]+/u', '-', $text);
    $text = trim((string)$text, '-');
    if ($text === '') {
        $text = 'item-' . substr(bin2hex(random_bytes(4)), 0, 6);
    }
    return $text;
}

function selected_if($current, $value): string
{
    return (string)$current === (string)$value ? 'selected' : '';
}

function checked_if($value): string
{
    return $value ? 'checked' : '';
}

function active_if(bool $condition): string
{
    return $condition ? 'active' : '';
}

/** Format a datetime for display. */
function format_date(?string $datetime, string $format = 'M d, Y'): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    return $ts ? date($format, $ts) : '';
}

/** CSRF helpers for templates. */
function csrf_token(): string
{
    return Csrf::token();
}

function csrf_field(): string
{
    return Csrf::field();
}
/** Availability helpers. */
function availability_options(): array
{
    return [
        'in_stock'      => __('availability.in_stock'),
        'made_to_order' => __('availability.made_to_order'),
        'unavailable'   => __('availability.unavailable'),
    ];
}

function availability_badge(string $status): string
{
    $map = [
        'in_stock'      => 'success',
        'made_to_order' => 'warning text-dark',
        'unavailable'   => 'secondary',
    ];
    $class = $map[$status] ?? 'secondary';
    $label = availability_options()[$status] ?? $status;
    return '<span class="badge rounded-pill amn-badge-' . explode(' ', $class)[0] . '">' . e($label) . '</span>';
}

/**
 * Pagination link builder shared by public and admin lists.
 */
function pagination(int $page, int $pages, string $baseUrl, array $query = [], string $id = 'pagination'): string
{
    if ($pages <= 1) {
        return '';
    }
    $build = function (int $p) use ($baseUrl, $query) {
        $query['page'] = $p;
        return url($baseUrl) . '?' . http_build_query($query);
    };
    $html = '<nav aria-label="Pagination"><ul class="pagination justify-content-center mb-0" id="' . e($id) . '">';
    $prevDisabled = $page <= 1 ? ' disabled' : '';
    $nextDisabled = $page >= $pages ? ' disabled' : '';
    $html .= '<li class="page-item' . $prevDisabled . '"><a class="page-link" href="' . e($build(max(1, $page - 1))) . '">&laquo;</a></li>';

    $start = max(1, $page - 2);
    $end = min($pages, $page + 2);
    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . e($build(1)) . '">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
        }
    }
    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $page ? ' active' : '';
        $html .= '<li class="page-item' . $active . '"><a class="page-link" href="' . e($build($i)) . '">' . $i . '</a></li>';
    }
    if ($end < $pages) {
        if ($end < $pages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . e($build($pages)) . '">' . $pages . '</a></li>';
    }

    $html .= '<li class="page-item' . $nextDisabled . '"><a class="page-link" href="' . e($build(min($pages, $page + 1))) . '">&raquo;</a></li>';
    $html .= '</ul></nav>';
    return $html;
}

/** Render the 404 page and stop execution. */
function not_found_page(): void
{
    http_response_code(404);
    $view = BASE_PATH . '/app/Views/errors/404.php';
    if (is_file($view)) {
        try {
            echo App\Core\View::partial('errors/404', ['title' => __('errors.404_title')]);
        } catch (Throwable $e) {
            echo 'Page not found.';
        }
    } else {
        echo 'Page not found.';
    }
    exit;
}
/** Inline SVG icon set (no external icon font required). */
function icon(string $name, int $size = 20): string
{
    $icons = [
        'bolt'  => '<path d="M13 2 4.5 12.5H11L9.5 22 19 10.5h-6.5L13 2z"/>',
        'heart-pulse' => '<path d="M19 14c1.5-1.5 2-3.2 2-5A5 5 0 0 0 12 5.1 5 5 0 0 0 3 9c0 1.8.5 3.5 2 5l7 7 7-7zM5 9a3.8 3.8 0 0 1 6-2.4L12 7.7l1-1.1A3.8 3.8 0 1 1 18.7 12L12 18.4 5.3 12A3.8 3.8 0 0 1 5 9z"/>',
        'shirt' => '<path d="M15.6 3 10 5.2 4.4 3 2 7.3l3 2.1V21h14V9.4l3-2.1L15.6 3zm0 3.7L12 8.2V20H5V10.3l3.5-1.3L12 9.7l3.5-.9L19 10.3V20h-7"/>',
        'box'   => '<path d="M12 2 3 6.5v11L12 22l9-4.5v-11L12 2zm0 2.3 6.3 3.1L12 10.6 5.7 7.4 12 4.3zM5 9.1l6 3v7.4l-6-3V9.1zm8 10.4v-7.4l6-3v7.4l-6 3z"/>',
        'search' => '<path d="M10 2a8 8 0 1 0 4.9 14.3l5.4 5.4 1.4-1.4-5.4-5.4A8 8 0 0 0 10 2zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12z"/>',
        'globe' => '<path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm7.9 9h-3.4a15.5 15.5 0 0 0-1.3-5.6A8 8 0 0 1 19.9 11zM12 4c.9 1.2 1.9 3.5 2.2 7H9.8C10.1 7.5 11.1 5.2 12 4zM8.8 5.4A15.5 15.5 0 0 0 7.5 11H4.1a8 8 0 0 1 4.7-5.6zM4.1 13h3.4c.2 2.1.7 4 1.3 5.6A8 8 0 0 1 4.1 13zM12 20c-.9-1.2-1.9-3.5-2.2-7h4.4c-.3 3.5-1.3 5.8-2.2 7zm3.2-1.4c.6-1.6 1.1-3.5 1.3-5.6h3.4a8 8 0 0 1-4.7 5.6z"/>',
        'ship'  => '<path d="M3 16.5 12 12l9 4.5-1.7 3.4-2-.9V22H6.7v-3l-2 .9L3 16.5zM5.6 12.3 12 9.1l6.4 3.2 1.6-3.2L12 4.5 4 9.1l1.6 3.2z"/>',
        'shield' => '<path d="M12 2 4 5v6.1c0 5 3.4 9.7 8 10.9 4.6-1.2 8-5.9 8-10.9V5l-8-3zm0 2.2 6 2.2v4.7c0 3.9-2.5 7.6-6 8.7-3.5-1.1-6-4.8-6-8.7V6.4l6-2.2z"/>',
        'truck' => '<path d="M3 5h11v10H3V5zm12 3h3.6l2.4 3v4h-6V8zM6.5 18a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>',
        'doc'   => '<path d="M6 2h8l5 5v15H6V2zm7 1.5V8h4.5L13 3.5zM8 11h8v1.6H8V11zm0 3.4h8V16H8v-1.6zm0 3.4h5v1.6H8v-1.6z"/>',
        'headset' => '<path d="M12 3a9 9 0 0 0-9 9v5a3 3 0 0 0 3 3h1v-8H5v-.2A7 7 0 0 1 19 12v.2h-2V12a5 5 0 0 0-10 0v.8h4V20h4a3 3 0 0 0 3-3v-5a9 9 0 0 0-9-9z"/>',
        'check' => '<path d="M9 16.2 4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>',
        'phone' => '<path d="M6.6 3h3.1l1.5 4.5-2 1.4a12 12 0 0 0 5 5l1.4-2 4.4 1.5v3.1c0 .9-.7 1.6-1.6 1.6A15.6 15.6 0 0 1 5 4.6C5 3.7 5.7 3 6.6 3z"/>',
        'mail'  => '<path d="M3 5h18v14H3V5zm2 2.4V17h14V7.4l-7 4.9-7-4.9zM5.7 7l6.3 4.4L18.3 7H5.7z"/>',
        'pin'   => '<path d="M12 2a7 7 0 0 0-7 7c0 5.3 7 13 7 13s7-7.7 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/>',
        'clock' => '<path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm1 5h-2v6l5 3 1-1.7-4-2.3V7z"/>',
        'whatsapp' => '<path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5.3 14c-.2.6-1.2 1.2-1.7 1.2-.5.1-1 .1-1.7-.1a12 12 0 0 1-5.6-4.8c-.4-.7-.9-1.6-.9-2.4 0-.9.5-1.4.7-1.6.2-.2.4-.3.6-.3h.5c.2 0 .4 0 .5.4l.8 2c.1.2 0 .4-.1.5l-.4.5c-.1.2-.3.3-.1.6.2.4.9 1.4 1.9 2.3 1.2 1 2.2 1.4 2.5 1.5.3.1.5.1.6-.1l.8-1c.2-.2.3-.2.6-.1l2 1c.3.1.4.2.5.3 0 .1 0 .7-.2 1.2z"/>',
        'star'  => '<path d="m12 2 3 6.6 7 .8-5.2 4.8L18.3 21 12 17.4 5.7 21l1.5-6.8L2 9.4l7-.8L12 2z"/>',
        'arrow' => '<path d="M13 5l7 7-7 7-1.4-1.4 4.6-4.6H4v-2h12.2L11.6 6.4 13 5z"/>',
        'leaf'  => '<path d="M5 21c0-9 5-14 14-15-1 9-5 14-14 15zm3-3c4-4 6.5-7.5 8-12-5 1.5-8.5 5-10 10l2 2z"/>',
        'quote' => '<path d="M9 7H5a3 3 0 0 0-3 3v3a3 3 0 0 0 3 3h2v-3H5v-3h4V7zm10 0h-4a3 3 0 0 0-3 3v3a3 3 0 0 0 3 3h2v-3h-2v-3h4V7z"/>',
        'cert'  => '<path d="M12 2 9.8 4.2 6.7 3.6 6 6.7 3.2 8.2l1.4 3-1.4 3L6 15.7l.7 3.1 3.1-.6L12 20.4l2.2-2.2 3.1.6.7-3.1 2.8-1.5-1.4-3 1.4-3-2.8-1.5-.7-3.1-3.1.6L12 2zm-1 13.5V7l5 4.2-5 4.3z"/>',
        'menu'  => '<path d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/>',
        'message' => '<path d="M4 4h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H8l-4 4V6a2 2 0 0 1 2-2zm0 2v13.2L6.2 13H20V6H4z"/>',
    ];
    $path = $icons[$name] ?? $icons['check'];
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">' . $path . '</svg>';
}
