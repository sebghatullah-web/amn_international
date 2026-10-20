<?php

namespace App\Models;

/**
 * Product image gallery.
 */
class ProductImage extends Model
{
    protected static string $table = 'product_images';

    public static function forProduct(int $productId): array
    {
        return self::run(
            'SELECT * FROM `' . static::$table . '` WHERE product_id = ? ORDER BY is_primary DESC, id ASC',
            [$productId]
        )->fetchAll();
    }

    public static function add(int $productId, string $path, string $alt, bool $primary = false): int
    {
        if ($primary) {
            self::run('UPDATE `' . static::$table . '` SET is_primary = 0 WHERE product_id = ?', [$productId]);
        } elseif (self::count('product_id = ?', [$productId]) === 0) {
            $primary = true; // first image is always primary
        }
        return self::insert([
            'product_id' => $productId,
            'path'       => $path,
            'alt'        => $alt,
            'is_primary' => $primary ? 1 : 0,
        ]);
    }

    public static function makePrimary(int $id): void
    {
        $image = self::find($id);
        if (!$image) {
            return;
        }
        self::run('UPDATE `' . static::$table . '` SET is_primary = 0 WHERE product_id = ?', [$image['product_id']]);
        self::updateById($id, ['is_primary' => 1]);
    }

    public static function deleteForProduct(int $productId): void
    {
        foreach (self::forProduct($productId) as $image) {
            \App\Core\Upload::delete($image['path']);
        }
        self::run('DELETE FROM `' . static::$table . '` WHERE product_id = ?', [$productId]);
    }
}
