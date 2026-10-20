<?php

namespace App\Core;

/**
 * Template renderer with layout support.
 */
class View
{
    public static function render(string $template, array $data = [], ?string $layout = null): void
    {
        $content = self::partial($template, $data);
        if ($layout === null) {
            echo $content;
            return;
        }
        $vars = $data;
        $vars['content'] = $content;
        echo self::partial($layout, $vars);
    }

    public static function partial(string $template, array $data = []): string
    {
        $file = BASE_PATH . '/app/Views/' . $template . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("View not found: $template");
        }
        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return (string)ob_get_clean();
    }
}
