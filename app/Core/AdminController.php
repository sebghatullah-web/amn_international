<?php

namespace App\Core;

use App\Core\Auth;

/**
 * Base controller for the admin area. Enforces authentication.
 */
abstract class AdminController extends Controller
{
    public function __construct()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $publicRoutes = ['/admin/login', '/admin/logout'];
        $isPublicRoute = false;
        foreach ($publicRoutes as $route) {
            if (strpos($uri, $route) !== false) {
                $isPublicRoute = true;
                break;
            }
        }
        if (!Auth::check() && !$isPublicRoute) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => __('auth.login_required'), 'redirect' => url('admin/login')], 401);
            }
            $this->redirect('admin/login');
        }
    }

    protected function view(string $template, array $data = [], string $layout = 'layouts/admin'): void
    {
        View::render($template, $data, $layout);
    }
}
