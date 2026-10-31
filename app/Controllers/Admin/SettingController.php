<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Setting;

class SettingController extends AdminController
{
    public function index(): void
    {
        $this->redirect('admin/settings/general');
    }

    public function tab(string $tab): void
    {
        $allowed = ['general', 'contact', 'home', 'currency', 'seo', 'footer'];
        if (!in_array($tab, $allowed, true)) {
            $this->redirect('admin/settings/general');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::verify()) {
                $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
            }
            $this->saveTab($tab);
        }

        $settings = Setting::allSettings();
        $this->view('admin/settings/index', [
            'title'     => __('admin.settings') . ' | ' . setting('company_name'),
            'tab'       => $tab,
            'settings'  => $settings,
        ]);
    }

    public function update(string $tab): void
    {
        $this->tab($tab);
    }
    private function saveTab(string $tab): void
    {
        $pairs = [];

        switch ($tab) {
            case 'general':
                $validator = new Validator($_POST, [
                    'company_name'  => 'required|min:2|max:120',
                    'company_slogan'=> 'max:200',
                    'site_language' => 'max:10',
                ]);
                if ($validator->fails()) {
                    $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
                }
                $d = $validator->validated();
                $pairs = [
                    'company_name'  => $d['company_name'],
                    'company_slogan'=> $d['company_slogan'],
                    'site_language' => $d['site_language'],
                    'admin_email'   => trim($_POST['admin_email'] ?? ''),
                ];
                if (!empty($_FILES['company_logo']['name'])) {
                    $up = Upload::image($_FILES['company_logo'], 'site');
                    if (!$up['ok']) {
                        $this->json(['success' => false, 'message' => $up['error']], 422);
                    }
                    Upload::delete(Setting::get('company_logo', ''));
                    $pairs['company_logo'] = $up['path'];
                }
                break;

            case 'contact':
                $validator = new Validator($_POST, [
                    'phone'         => 'max:40',
                    'whatsapp'      => 'max:40',
                    'email'         => 'nullable|email|max:190',
                    'office_address'=> 'max:255',
                    'working_hours' => 'max:100',
                ]);
                if ($validator->fails()) {
                    $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
                }
                $d = $validator->validated();
                $pairs = [
                    'phone'         => $d['phone'],
                    'whatsapp'      => $d['whatsapp'],
                    'whatsapp_link' => trim($_POST['whatsapp_link'] ?? ''),
                    'email'         => $d['email'],
                    'office_address'=> $d['office_address'],
                    'working_hours' => $d['working_hours'],
                    'social_facebook'  => trim($_POST['social_facebook'] ?? ''),
                    'social_instagram' => trim($_POST['social_instagram'] ?? ''),
                    'social_linkedin'  => trim($_POST['social_linkedin'] ?? ''),
                    'social_youtube'   => trim($_POST['social_youtube'] ?? ''),
                    'social_x'         => trim($_POST['social_x'] ?? ''),
                ];
                break;

            case 'home':
                $fields = [
                    'hero_eyebrow', 'hero_title', 'hero_description', 'hero_alt',
                    'cta_title', 'cta_text', 'cta_alt',
                    'service_1_title', 'service_1_text', 'service_2_title', 'service_2_text',
                    'service_3_title', 'service_3_text',
                    'categories_title', 'featured_title', 'new_title', 'popular_title',
                    'stats_years', 'stats_clients', 'stats_shipments', 'stats_countries',
                    'footer_about', 'newsletter_text', 'show_demo_disclaimer',
                ];
                foreach ($fields as $f) {
                    $pairs[$f] = trim($_POST[$f] ?? '');
                }
                foreach (['hero_image', 'cta_image'] as $f) {
                    if (!empty($_FILES[$f]['name'])) {
                        $up = Upload::image($_FILES[$f], 'site');
                        if (!$up['ok']) {
                            $this->json(['success' => false, 'message' => $up['error']], 422);
                        }
                        Upload::delete(Setting::get($f, ''));
                        $pairs[$f] = $up['path'];
                    }
                }
                $pairs['announcement_1'] = trim($_POST['announcement_1'] ?? '');
                $pairs['announcement_2'] = trim($_POST['announcement_2'] ?? '');
                $pairs['announcement_3'] = trim($_POST['announcement_3'] ?? '');
                break;

            case 'currency':
                $validator = new Validator($_POST, [
                    'usd_afn_rate'       => 'nullable|decimal',
                    'currency_usd_code'  => 'max:10',
                    'currency_afn_code'  => 'max:10',
                ]);
                if ($validator->fails()) {
                    $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
                }
                $d = $validator->validated();
                $pairs = [
                    'usd_afn_rate'       => empty($d['usd_afn_rate']) ? '0' : $d['usd_afn_rate'],
                    'usd_afn_updated_at' => date('Y-m-d H:i:s'),
                    'currency_usd_code'  => $d['currency_usd_code'] ?: 'USD',
                    'currency_afn_code'  => $d['currency_afn_code'] ?: 'AFN',
                ];
                break;
            case 'seo':
                $fields = ['seo_home_title', 'seo_home_description', 'seo_about_title', 'seo_about_description',
                    'seo_products_title', 'seo_products_description', 'seo_services_title', 'seo_services_description',
                    'seo_process_title', 'seo_process_description', 'seo_gallery_title', 'seo_gallery_description',
                    'seo_news_title', 'seo_news_description', 'seo_contact_title', 'seo_contact_description',
                    'seo_quote_title', 'seo_quote_description'];
                foreach ($fields as $f) {
                    $pairs[$f] = trim($_POST[$f] ?? '');
                }
                break;

            case 'footer':
                $navRaw = trim($_POST['nav_links'] ?? '');
                $lines = [];
                foreach (preg_split('/\r?\n/', $navRaw) as $line) {
                    $parts = array_map('trim', explode('|', $line, 2));
                    if (count($parts) === 2 && $parts[0] !== '' && $parts[1] !== '') {
                        $lines[] = $parts[0] . '|' . $parts[1];
                    }
                }
                if (count($lines) === 0) {
                    $lines = ['Home|/', 'Products|/products', 'Services|/services', 'Import Process|/import-process', 'Gallery|/gallery', 'News & Articles|/news', 'About Us|/about', 'Contact Us|/contact'];
                }
                $pairs['nav_links'] = implode("\n", $lines);
                break;
        }

        Setting::setMany($pairs);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.settings_saved')]);
        }
        $this->flash('success', __('admin.settings_saved'));
        $this->redirect('admin/settings/' . $tab);
    }
}
