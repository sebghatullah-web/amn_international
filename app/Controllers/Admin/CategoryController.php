<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Category;

class CategoryController extends AdminController
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Category::search($q, $page);
        $this->view('admin/categories/index', [
            'title'   => __('admin.categories') . ' | ' . setting('company_name'),
            'result'  => $result,
            'q'       => $q,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/categories/form', [
            'title'   => __('admin.new') . ' ' . __('admin.categories'),
            'mode'    => 'create',
            'category'=> null,
        ]);
    }

    public function store(): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }

        $validator = new Validator($_POST, [
            'name'        => 'required|min:2|max:100',
            'slug'        => 'nullable|max:100',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|in:1',
        ]);

        if ($validator->fails()) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
            }
            $this->flash('error', __('errors.generic'));
            $this->redirect('admin/categories/create');
        }

        $data = $validator->validated();
        $slug = Category::uniqueSlug(slugify($data['slug'] ?: $data['name']));
        $uploaded = Upload::image($_FILES['image'] ?? [], 'categories');

        Category::insert([
            'name'         => mb_substr($data['name'], 0, 100),
            'slug'         => $slug,
            'description'  => mb_substr($data['description'] ?? '', 0, 1000),
            'image'        => $uploaded['ok'] ? $uploaded['path'] : null,
            'sort_order'   => (int)($data['sort_order'] ?? 0),
            'is_active'    => isset($data['is_active']) ? 1 : 0,
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.saved'), 'redirect' => url('admin/categories')]);
        }
        $this->flash('success', __('admin.saved'));
        $this->redirect('admin/categories');
    }

    public function edit(int $id): void
    {
        $category = Category::find($id);
        if (!$category) {
            $this->flash('error', __('admin.no_results'));
            $this->redirect('admin/categories');
        }
        $this->view('admin/categories/form', [
            'title'    => __('admin.edit') . ' ' . __('admin.categories'),
            'mode'     => 'edit',
            'category' => $category,
        ]);
    }

    public function update(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }

        $category = Category::find($id);
        if (!$category) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }

        $validator = new Validator($_POST, [
            'name'        => 'required|min:2|max:100',
            'slug'        => 'nullable|max:100',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|in:1',
        ]);

        if ($validator->fails()) {
            $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $slug = Category::uniqueSlug(slugify($data['slug'] ?: $data['name']), $id);

        $update = [
            'name'        => mb_substr($data['name'], 0, 100),
            'slug'        => $slug,
            'description' => mb_substr($data['description'] ?? '', 0, 1000),
            'sort_order'  => (int)($data['sort_order'] ?? 0),
            'is_active'   => isset($data['is_active']) ? 1 : 0,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if (!empty($_FILES['image']['name'])) {
            $uploaded = Upload::image($_FILES['image'], 'categories');
            if (!$uploaded['ok']) {
                $this->json(['success' => false, 'message' => $uploaded['error']], 422);
            }
            Upload::delete($category['image']);
            $update['image'] = $uploaded['path'];
        }

        Category::updateById($id, $update);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.saved'), 'redirect' => url('admin/categories')]);
        }
        $this->flash('success', __('admin.saved'));
        $this->redirect('admin/categories');
    }

    public function destroy(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $category = Category::find($id);
        if (!$category) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Upload::delete($category['image']);
        Category::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/categories');
    }

    public function toggle(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $category = Category::find($id);
        if (!$category) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Category::updateById($id, ['is_active' => (int)!$category['is_active']]);
        $this->json(['success' => true, 'message' => __('admin.saved'), 'is_active' => (int)!$category['is_active']]);
    }
}
