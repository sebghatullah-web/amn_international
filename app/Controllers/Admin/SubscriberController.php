<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Models\Subscriber;

class SubscriberController extends AdminController
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Subscriber::search($q, $page);
        $this->view('admin/subscribers/index', [
            'title'   => __('admin.subscribers') . ' | ' . setting('company_name'),
            'result'  => $result,
            'q'       => $q,
        ]);
    }

    public function destroy(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $subscriber = Subscriber::find($id);
        if (!$subscriber) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Subscriber::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/subscribers');
    }
}
