<?php

namespace App\Core;

use App\Controllers\Admin\AuthController;

/**
 * Application kernel: registers routes and handles the request.
 */
class App
{
    public function run(): void
    {
        $this->guardSecurePost();

        $router = new Router();
        $this->routes($router);

        [$method, $path] = $this->currentRequest();
        $router->dispatch($method, $path);
    }

    /** Reject state-changing requests without a valid CSRF token. */
    private function guardSecurePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !Csrf::verify()) {
            if ($this->wantsJson()) {
                http_response_code(419);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => __('errors.csrf')]);
                exit;
            }
            http_response_code(419);
            $view = BASE_PATH . '/app/Views/errors/error.php';
            $message = __('errors.csrf');
            if (is_file($view)) {
                include $view;
                exit;
            }
            echo $message;
            exit;
        }
    }

    private function wantsJson(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest'
            || strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
    }

    private function currentRequest(): array
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        // Fallback for hosts without URL rewriting: /?p=/products
        $configured = (bool)config('url_rewrite');

        if (!$configured || isset($_GET['p'])) {
            $path = (string)($_GET['p'] ?? '/');
        } else {
            $path = parse_url($uri, PHP_URL_PATH) ?: '/';
            $base = BASE_URL;
            if ($base !== '' && strpos($path, $base) === 0) {
                $path = substr($path, strlen($base));
            }
            $path = preg_replace('#^/index\.php#', '', $path);
        }

        $path = '/' . trim($path, '/');

        // Defensively support legacy delete forms that post _method=DELETE
        // while routing them to the dedicated POST /delete action.
        if ($method === 'POST' && strtoupper((string)($_POST['_method'] ?? '')) === 'DELETE'
            && !preg_match('#/delete$#', $path)) {
            $path .= '/delete';
        }

        return [$method, $path];
    }

    private function routes(Router $router): void
    {
        $router->get('/', [\App\Controllers\HomeController::class, 'index']);
        $router->get('about', [\App\Controllers\AboutController::class, 'index']);
        $router->get('products', [\App\Controllers\ProductController::class, 'index']);
        $router->get('products/{slug}', [\App\Controllers\ProductController::class, 'show']);
        $router->get('ajax/products', [\App\Controllers\ProductController::class, 'search']);
        $router->get('services', [\App\Controllers\ServiceController::class, 'index']);
        $router->get('import-process', [\App\Controllers\ProcessController::class, 'index']);
        $router->get('gallery', [\App\Controllers\GalleryController::class, 'index']);
        $router->get('news', [\App\Controllers\NewsController::class, 'index']);
        $router->get('news/{slug}', [\App\Controllers\NewsController::class, 'show']);
        $router->get('contact', [\App\Controllers\ContactController::class, 'index']);
        $router->post('contact', [\App\Controllers\ContactController::class, 'store']);
        $router->post('ajax/contact', [\App\Controllers\ContactController::class, 'store']);
        $router->get('quote', [\App\Controllers\QuoteController::class, 'index']);
        $router->post('quote', [\App\Controllers\QuoteController::class, 'store']);
        $router->post('ajax/quote', [\App\Controllers\QuoteController::class, 'store']);
        $router->post('ajax/newsletter', [\App\Controllers\NewsletterController::class, 'subscribe']);

        $router->get('admin/login', [AuthController::class, 'showLogin']);
        $router->post('admin/login', [AuthController::class, 'login']);
        $router->post('admin/logout', [AuthController::class, 'logout']);
        $router->get('admin', [\App\Controllers\Admin\DashboardController::class, 'index']);

        $router->get('admin/categories', [\App\Controllers\Admin\CategoryController::class, 'index']);
        $router->get('admin/categories/create', [\App\Controllers\Admin\CategoryController::class, 'create']);
        $router->post('admin/categories', [\App\Controllers\Admin\CategoryController::class, 'store']);
        $router->get('admin/categories/{id}/edit', [\App\Controllers\Admin\CategoryController::class, 'edit']);
        $router->post('admin/categories/{id}', [\App\Controllers\Admin\CategoryController::class, 'update']);
        $router->post('admin/categories/{id}/delete', [\App\Controllers\Admin\CategoryController::class, 'destroy']);
        $router->post('admin/categories/{id}/toggle', [\App\Controllers\Admin\CategoryController::class, 'toggle']);

        $router->get('admin/products', [\App\Controllers\Admin\ProductController::class, 'index']);
        $router->get('admin/products/create', [\App\Controllers\Admin\ProductController::class, 'create']);
        $router->post('admin/products', [\App\Controllers\Admin\ProductController::class, 'store']);
        $router->get('admin/products/{id}/edit', [\App\Controllers\Admin\ProductController::class, 'edit']);
        $router->post('admin/products/{id}', [\App\Controllers\Admin\ProductController::class, 'update']);
        $router->post('admin/products/{id}/delete', [\App\Controllers\Admin\ProductController::class, 'destroy']);
        $router->post('admin/products/{id}/toggle', [\App\Controllers\Admin\ProductController::class, 'toggle']);
        $router->post('admin/products/images', [\App\Controllers\Admin\ProductController::class, 'uploadImage']);
        $router->post('admin/products/images/{id}/delete', [\App\Controllers\Admin\ProductController::class, 'destroyImage']);
        $router->post('admin/products/images/{id}/primary', [\App\Controllers\Admin\ProductController::class, 'primaryImage']);
        $router->get('admin/services', [\App\Controllers\Admin\ServiceController::class, 'index']);
        $router->get('admin/services/create', [\App\Controllers\Admin\ServiceController::class, 'create']);
        $router->post('admin/services', [\App\Controllers\Admin\ServiceController::class, 'store']);
        $router->get('admin/services/{id}/edit', [\App\Controllers\Admin\ServiceController::class, 'edit']);
        $router->post('admin/services/{id}', [\App\Controllers\Admin\ServiceController::class, 'update']);
        $router->post('admin/services/{id}/delete', [\App\Controllers\Admin\ServiceController::class, 'destroy']);

        $router->get('admin/steps', [\App\Controllers\Admin\ProcessStepController::class, 'index']);
        $router->get('admin/steps/create', [\App\Controllers\Admin\ProcessStepController::class, 'create']);
        $router->post('admin/steps', [\App\Controllers\Admin\ProcessStepController::class, 'store']);
        $router->get('admin/steps/{id}/edit', [\App\Controllers\Admin\ProcessStepController::class, 'edit']);
        $router->post('admin/steps/{id}', [\App\Controllers\Admin\ProcessStepController::class, 'update']);
        $router->post('admin/steps/{id}/delete', [\App\Controllers\Admin\ProcessStepController::class, 'destroy']);

        $router->get('admin/gallery', [\App\Controllers\Admin\GalleryController::class, 'index']);
        $router->get('admin/gallery/create', [\App\Controllers\Admin\GalleryController::class, 'create']);
        $router->post('admin/gallery', [\App\Controllers\Admin\GalleryController::class, 'store']);
        $router->get('admin/gallery/{id}/edit', [\App\Controllers\Admin\GalleryController::class, 'edit']);
        $router->post('admin/gallery/{id}', [\App\Controllers\Admin\GalleryController::class, 'update']);
        $router->post('admin/gallery/{id}/delete', [\App\Controllers\Admin\GalleryController::class, 'destroy']);
        $router->get('admin/articles', [\App\Controllers\Admin\ArticleController::class, 'index']);
        $router->get('admin/articles/create', [\App\Controllers\Admin\ArticleController::class, 'create']);
        $router->post('admin/articles', [\App\Controllers\Admin\ArticleController::class, 'store']);
        $router->get('admin/articles/{id}/edit', [\App\Controllers\Admin\ArticleController::class, 'edit']);
        $router->post('admin/articles/{id}', [\App\Controllers\Admin\ArticleController::class, 'update']);
        $router->post('admin/articles/{id}/delete', [\App\Controllers\Admin\ArticleController::class, 'destroy']);
        $router->post('admin/articles/{id}/toggle', [\App\Controllers\Admin\ArticleController::class, 'toggle']);

        $router->get('admin/inquiries', [\App\Controllers\Admin\InquiryController::class, 'index']);
        $router->get('admin/inquiries/{id}', [\App\Controllers\Admin\InquiryController::class, 'show']);
        $router->post('admin/inquiries/{id}/status', [\App\Controllers\Admin\InquiryController::class, 'status']);
        $router->post('admin/inquiries/{id}/delete', [\App\Controllers\Admin\InquiryController::class, 'destroy']);

        $router->get('admin/subscribers', [\App\Controllers\Admin\SubscriberController::class, 'index']);
        $router->post('admin/subscribers/{id}/delete', [\App\Controllers\Admin\SubscriberController::class, 'destroy']);

        $router->get('admin/settings', [\App\Controllers\Admin\SettingController::class, 'index']);
        $router->get('admin/settings/{tab}', [\App\Controllers\Admin\SettingController::class, 'tab']);
        $router->post('admin/settings/{tab}', [\App\Controllers\Admin\SettingController::class, 'update']);

        $router->get('admin/users', [\App\Controllers\Admin\UserController::class, 'index']);
        $router->get('admin/users/create', [\App\Controllers\Admin\UserController::class, 'create']);
        $router->post('admin/users', [\App\Controllers\Admin\UserController::class, 'store']);
        $router->get('admin/users/{id}/edit', [\App\Controllers\Admin\UserController::class, 'edit']);
        $router->post('admin/users/{id}', [\App\Controllers\Admin\UserController::class, 'update']);
        $router->post('admin/users/{id}/delete', [\App\Controllers\Admin\UserController::class, 'destroy']);
        $router->post('admin/profile/password', [\App\Controllers\Admin\UserController::class, 'changePassword']);
    }
}
