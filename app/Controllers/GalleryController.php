<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\GalleryImage;

class GalleryController extends Controller
{
    public function index(): void
    {
        $this->view('gallery/index', [
            'title'       => setting('seo_gallery_title', __('gallery.title') . ' | ' . setting('company_name')),
            'description' => setting('seo_gallery_description', __('gallery.subtitle')),
            'images'      => GalleryImage::active(),
        ]);
    }
}
