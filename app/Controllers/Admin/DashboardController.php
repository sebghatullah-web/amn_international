<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Article;
use App\Models\Subscriber;

class DashboardController extends AdminController
{
    public function index(): void
    {
        $this->view('admin/dashboard', [
            'title'       => __('admin.dashboard') . ' | ' . setting('company_name'),
            'stats'       => [
                'inquiries' => Inquiry::newCount(),
                'products'  => Product::count(),
                'articles'  => Article::count(),
                'subscribers' => Subscriber::count(),
            ],
            'recent'      => Inquiry::recent(8),
        ]);
    }
}
