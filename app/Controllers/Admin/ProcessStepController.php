<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\ProcessStep;

class ProcessStepController extends AdminController
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = ProcessStep::search($q, $page);
        $this->view('admin/steps/index', [
            'title'   => __('admin.steps') . ' | ' . setting('company_name'),
            'result'  => $result,
            'q'       => $q,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/steps/form', [
            'title'   => __('admin.new') . ' ' . __('admin.steps'),
            'mode'    => 'create',
            'step'    => null,
        ]);
    }

    public function store(): void
    {
        $this->save(null);
    }

    public function edit(int $id): void
    {
        $step = ProcessStep::find($id);
        if (!$step) {
            $this->flash('error', __('admin.no_results'));
            $this->redirect('admin/steps');
        }
        $this->view('admin/steps/form', [
            'title' => __('admin.edit') . ' ' . __('admin.steps'),
            'mode'  => 'edit',
            'step'  => $step,
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
        $step = ProcessStep::find($id);
        if (!$step) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Upload::delete($step['image']);
        ProcessStep::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/steps');
    }

    private function save(?int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }

        $validator = new Validator($_POST, [
            'title'          => 'required|min:2|max:150',
            'group_name'     => 'max:80',
            'description'    => 'max:1000',
            'sort_order'     => 'nullable|integer',
            'is_active'      => 'nullable|in:1',
        ]);
        if ($validator->fails()) {
            $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $uploaded = Upload::image($_FILES['image'] ?? [], 'steps');
        $row = [
            'title'         => mb_substr($data['title'], 0, 150),
            'group_name'    => mb_substr($data['group_name'] ?? '', 0, 80),
            'description'   => mb_substr($data['description'] ?? '', 0, 1000),
            'sort_order'    => (int)($data['sort_order'] ?? 0),
            'is_active'     => isset($data['is_active']) ? 1 : 0,
            'updated_at'    => date('Y-m-d H:i:s'),
        ];
        if ($uploaded['ok']) {
            $row['image'] = $uploaded['path'];
        }

        try {
            if ($id) {
                $old = ProcessStep::find($id);
                if (!empty($row['image'])) { Upload::delete($old['image']); }
                ProcessStep::updateById($id, $row);
            } else {
                $row['created_at'] = date('Y-m-d H:i:s');
                ProcessStep::insert($row);
            }
        } catch (\Throwable $e) {
            error_log('ProcessStepController::save failed: ' . $e->getMessage() . ' | id=' . var_export($id, true));
            $this->json(['success' => false, 'message' => __('errors.generic')], 500);
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.saved'), 'redirect' => url('admin/steps')]);
        }
        $this->flash('success', __('admin.saved'));
        $this->redirect('admin/steps');
    }
}
