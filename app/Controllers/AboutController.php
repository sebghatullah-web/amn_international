<?php

namespace App\Controllers;

use App\Core\Controller;

class AboutController extends Controller
{
    public function index(): void
    {
        $this->view('about/index', [
            'title'       => setting('seo_about_title', __('about.title') . ' | ' . setting('company_name')),
            'description' => setting('seo_about_description', __('about.subtitle')),
        ]);
    }
}
