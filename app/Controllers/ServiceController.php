<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(): void
    {
        $this->view('services/index', [
            'title'       => setting('seo_services_title', __('services.title') . ' | ' . setting('company_name')),
            'description' => setting('seo_services_description', __('services.subtitle')),
            'services'    => Service::active(),
        ]);
    }
}
