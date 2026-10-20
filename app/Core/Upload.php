<?php

namespace App\Core;

/**
 * Safe image upload handling.
 */
class Upload
{
    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        'image/svg+xml' => 'svg',
    ];

    /**
     * Validate and store an uploaded image.
     * @return array{ok:bool,error?:string,path?:string,url?:string}
     */
    public static function image(array $file, string $subdir = 'site'): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['ok' => false, 'error' => __('upload.invalid')];
        }
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['ok' => false, 'error' => __('upload.no_file')];
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'error' => __('upload.failed')];
        }

        $maxKb = (int)(Database::config()['upload_max_kb'] ?? 3072);
        if ($file['size'] > $maxKb * 1024) {
            return ['ok' => false, 'error' => __('upload.too_large', ['kb' => $maxKb])];
        }

        $subdir = preg_replace('/[^a-z0-9_\-]/i', '', $subdir) ?: 'site';

        // Determine real MIME type (never trust the client).
        $info = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $info->file($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) {
            return ['ok' => false, 'error' => __('upload.type')];
        }

        // For raster images additionally verify the file really is an image.
        if ($mime !== 'image/svg+xml' && !@getimagesize($file['tmp_name'])) {
            return ['ok' => false, 'error' => __('upload.type')];
        }
        if ($mime === 'image/svg+xml') {
            $svg = (string)file_get_contents($file['tmp_name']);
            if (stripos($svg, '<svg') === false || preg_match('/<script|on\w+\s*=|javascript:/i', $svg)) {
                return ['ok' => false, 'error' => __('upload.type')];
            }
        }

        $ext = self::ALLOWED[$mime];
        $name = date('Ymd') . '-' . bin2hex(random_bytes(8)) . '.' . $ext;
        $dir = BASE_PATH . '/uploads/' . $subdir;
        if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
            return ['ok' => false, 'error' => __('upload.failed')];
        }

        $dest = $dir . '/' . $name;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            return ['ok' => false, 'error' => __('upload.failed')];
        }
        @chmod($dest, 0644);

        $relative = 'uploads/' . $subdir . '/' . $name;
        return ['ok' => true, 'path' => $relative, 'url' => UPLOAD_URL . '/' . $subdir . '/' . $name];
    }

    /** Delete a previously stored upload (path relative to project root). */
    public static function delete(?string $relativePath): void
    {
        if (!$relativePath || strpos($relativePath, 'uploads/') !== 0) {
            return;
        }
        // Prevent traversal.
        $real = BASE_PATH . '/' . str_replace(['..', "\0"], '', $relativePath);
        if (is_file($real)) {
            @unlink($real);
        }
    }
}
