<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProcessStep;

class ProcessController extends Controller
{
    public function index(): void
    {
        $this->view('process/index', [
            'title'       => setting('seo_process_title', __('process.title') . ' | ' . setting('company_name')),
            'description' => setting('seo_process_description', __('process.subtitle')),
            'steps'       => ProcessStep::active(),
        ]);
    }
}
