<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Models\Inquiry;

class InquiryController extends AdminController
{
    public function index(): void
    {
        $filters = [
            'type'   => (string)($_GET['type'] ?? ''),
            'status' => (string)($_GET['status'] ?? ''),
            'q'      => trim((string)($_GET['q'] ?? '')),
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Inquiry::search($filters, $page);
        $this->view('admin/inquiries/index', [
            'title'   => __('admin.inquiries') . ' | ' . setting('company_name'),
            'result'  => $result,
            'filters' => $filters,
        ]);
    }

    public function show(int $id): void
    {
        $inquiry = Inquiry::find($id);
        if (!$inquiry) {
            $this->flash('error', __('admin.no_results'));
            $this->redirect('admin/inquiries');
        }
        if ($inquiry['status'] === 'new') {
            Inquiry::updateById($id, ['status' => 'read']);
        }
        $this->view('admin/inquiries/show', [
            'title'     => __('admin.inquiries') . ' | ' . setting('company_name'),
            'inquiry'   => $inquiry,
            'product'   => !empty($inquiry['product_id']) ? \App\Models\Product::find($inquiry['product_id']) : null,
        ]);
    }

    public function status(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['new', 'read', 'replied'], true)) {
            $this->json(['success' => false, 'message' => __('errors.generic')], 422);
        }
        $inquiry = Inquiry::find($id);
        if (!$inquiry) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Inquiry::updateById($id, ['status' => $status]);
        $this->json(['success' => true, 'message' => __('admin.saved')]);
    }

    public function destroy(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $inquiry = Inquiry::find($id);
        if (!$inquiry) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Inquiry::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/inquiries');
    }
}
