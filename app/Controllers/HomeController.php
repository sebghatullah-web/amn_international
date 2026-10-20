<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'title'       => setting('seo_home_title', setting('company_name')),
            'description' => setting('seo_home_description', setting('company_slogan')),
            'categories'  => Category::withCounts(),
            'featured'    => Product::featured(8),
            'newArrivals' => Product::newArrivals(8),
            'popular'     => Product::popular(8),
            'services'    => Service::active(),
            'articles'    => Article::latest(3),
        ]);
    }
}
