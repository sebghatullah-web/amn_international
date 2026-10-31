<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\GalleryImage;

class GalleryController extends AdminController
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = GalleryImage::search($q, $page);
        $this->view('admin/gallery/index', [
            'title'   => __('admin.gallery') . ' | ' . setting('company_name'),
            'result'  => $result,
            'q'       => $q,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/gallery/form', [
            'title'    => __('admin.new') . ' ' . __('admin.gallery'),
            'mode'     => 'create',
            'gallery'  => null,
        ]);
    }

    public function store(): void
    {
        $this->save(null);
    }

    public function edit(int $id): void
    {
        $image = GalleryImage::find($id);
        if (!$image) {
            $this->flash('error', __('admin.no_results'));
            $this->redirect('admin/gallery');
        }
        $this->view('admin/gallery/form', [
            'title'   => __('admin.edit') . ' ' . __('admin.gallery'),
            'mode'    => 'edit',
            'gallery' => $image,
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
        $image = GalleryImage::find($id);
        if (!$image) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Upload::delete($image['path']);
        GalleryImage::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/gallery');
    }

    private function save(?int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }

        $validator = new Validator($_POST, [
            'title'        => 'required|min:2|max:150',
            'group_name'   => 'max:80',
            'sort_order'   => 'nullable|integer',
            'is_active'    => 'nullable|in:1',
        ]);
        if ($validator->fails()) {
            $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $slugBase = slugify($data['title']);
        $slug = $slugBase;
        $suffix = 1;
        while (true) {
            $existing = GalleryImage::findBy('slug', $slug);
            if (!$existing || ($id && (int)$existing['id'] === $id)) {
                break;
            }
            $slug = $slugBase . '-' . (++$suffix);
        }
        $uploaded = Upload::image($_FILES['image'] ?? [], 'gallery');
        if (!$id && !$uploaded['ok']) {
            $this->json(['success' => false, 'message' => $uploaded['error']], 422);
        }
        $row = [
            'title'       => mb_substr($data['title'], 0, 150),
            'slug'        => mb_substr($slug, 0, 150),
            'group_name'  => mb_substr($data['group_name'] ?? '', 0, 120),
            'alt'         => mb_substr($data['alt'] ?? $data['title'], 0, 200),
            'sort_order'  => (int)($data['sort_order'] ?? 0),
            'is_active'   => isset($data['is_active']) ? 1 : 0,
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        try {
            if ($id) {
                $old = GalleryImage::find($id);
                if ($uploaded['ok']) {
                    Upload::delete($old['path']);
                    $row['path'] = $uploaded['path'];
                }
                GalleryImage::updateById($id, $row);
            } else {
                $row['path'] = $uploaded['path'];
                $row['created_at'] = date('Y-m-d H:i:s');
                GalleryImage::insert($row);
            }
        } catch (\Throwable $e) {
            error_log('GalleryController::save failed: ' . $e->getMessage() . ' | id=' . var_export($id, true));
            $this->json(['success' => false, 'message' => __('errors.generic')], 500);
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.saved'), 'redirect' => url('admin/gallery')]);
        }
        $this->flash('success', __('admin.saved'));
        $this->redirect('admin/gallery');
    }
}
