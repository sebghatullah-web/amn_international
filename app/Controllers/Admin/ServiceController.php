<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Service;

class ServiceController extends AdminController
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Service::search($q, $page);
        $this->view('admin/services/index', [
            'title'   => __('admin.services') . ' | ' . setting('company_name'),
            'result'  => $result,
            'q'       => $q,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/services/form', [
            'title'   => __('admin.new') . ' ' . __('admin.services'),
            'mode'    => 'create',
            'service' => null,
        ]);
    }

    public function store(): void
    {
        $this->save(null);
    }

    public function edit(int $id): void
    {
        $service = Service::find($id);
        if (!$service) {
            $this->flash('error', __('admin.no_results'));
            $this->redirect('admin/services');
        }
        $this->view('admin/services/form', [
            'title'   => __('admin.edit') . ' ' . __('admin.services'),
            'mode'    => 'edit',
            'service' => $service,
        ]);
    }

    public function update(int $id): void
    {
        $this->save($id);
    }

    public function destroy(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $service = Service::find($id);
        if (!$service) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Upload::delete($service['image']);
        Service::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/services');
    }

    private function save(?int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }

        $validator = new Validator($_POST, [
            'title'        => 'required|min:2|max:150',
            'slug'         => 'nullable|max:150',
            'short_description' => 'max:500',
            'description'  => 'max:5000',
            'sort_order'   => 'nullable|integer',
            'is_active'    => 'nullable|in:1',
        ]);
        if ($validator->fails()) {
            $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $slug = Service::uniqueSlug(slugify($data['slug'] ?: $data['title']), $id);

        $uploaded = Upload::image($_FILES['image'] ?? [], 'services');
        $row = [
            'title'            => mb_substr($data['title'], 0, 150),
            'slug'             => $slug,
            'short_description'=> mb_substr($data['short_description'] ?? '', 0, 500),
            'description'      => mb_substr($data['description'] ?? '', 0, 5000),
            'sort_order'       => (int)($data['sort_order'] ?? 0),
            'is_active'        => isset($data['is_active']) ? 1 : 0,
            'updated_at'       => date('Y-m-d H:i:s'),
        ];
        if ($uploaded['ok']) {
            $row['image'] = $uploaded['path'];
        }

        try {
            if ($id) {
                $old = Service::find($id);
                if (!empty($row['image'])) { Upload::delete($old['image']); }
                Service::updateById($id, $row);
            } else {
                $row['created_at'] = date('Y-m-d H:i:s');
                Service::insert($row);
            }
        } catch (\Throwable $e) {
            error_log('ServiceController::save failed: ' . $e->getMessage() . ' | id=' . var_export($id, true));
            $this->json(['success' => false, 'message' => __('errors.generic')], 500);
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.saved'), 'redirect' => url('admin/services')]);
        }
        $this->flash('success', __('admin.saved'));
        $this->redirect('admin/services');
    }
}
