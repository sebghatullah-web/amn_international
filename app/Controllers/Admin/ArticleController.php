<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Article;

class ArticleController extends AdminController
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Article::search($q, $page);
        $this->view('admin/articles/index', [
            'title'   => __('admin.articles') . ' | ' . setting('company_name'),
            'result'  => $result,
            'q'       => $q,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/articles/form', [
            'title'   => __('admin.new') . ' ' . __('admin.articles'),
            'mode'    => 'create',
            'article' => null,
        ]);
    }

    public function store(): void
    {
        $this->save(null);
    }

    public function edit(int $id): void
    {
        $article = Article::find($id);
        if (!$article) {
            $this->flash('error', __('admin.no_results'));
            $this->redirect('admin/articles');
        }
        $this->view('admin/articles/form', [
            'title'   => __('admin.edit') . ' ' . __('admin.articles'),
            'mode'    => 'edit',
            'article' => $article,
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
        $article = Article::find($id);
        if (!$article) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Upload::delete($article['image']);
        Article::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/articles');
    }

    public function toggle(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $article = Article::find($id);
        if (!$article) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        $now = date('Y-m-d H:i:s');
        $row = ['is_published' => (int)!$article['is_published']];
        if (!empty($article['is_published'])) {
            // keep existing published date when unpublishing? set null only if never published
        } else {
            $row['published_at'] = $now;
        }
        Article::updateById($id, $row);
        $this->json(['success' => true, 'message' => __('admin.saved'), 'is_published' => (int)!$article['is_published']]);
    }

    private function save(?int $id): void
    {
        $validator = new Validator($_POST, [
            'title'          => 'required|min:2|max:200',
            'slug'           => 'nullable|max:200',
            'excerpt'        => 'max:500',
            'body'           => 'max:10000',
            'author'         => 'max:100',
            'category'       => 'max:100',
            'is_published'   => 'nullable|in:1',
        ]);
        if ($validator->fails()) {
            $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $slug = Article::uniqueSlug($data['slug'] ?: $data['title'], $id);

        $row = [
            'title'       => mb_substr($data['title'], 0, 200),
            'slug'        => $slug,
            'excerpt'     => mb_substr($data['excerpt'] ?? '', 0, 500),
            'body'        => mb_substr($data['body'] ?? '', 0, 10000),
            'author'      => mb_substr($data['author'] ?? setting('company_name'), 0, 100),
            'category'    => mb_substr($data['category'] ?? '', 0, 100),
            'is_published'=> isset($data['is_published']) ? 1 : 0,
            'seo_title'   => mb_substr($data['seo_title'] ?? '', 0, 200),
            'seo_desc'    => mb_substr($data['seo_description'] ?? '', 0, 300),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $uploaded = Upload::image($_FILES['image'] ?? [], 'articles');
        if ($uploaded['ok']) {
            $row['image'] = $uploaded['path'];
        }

        if ($id) {
            $old = Article::find($id);
            if (!empty($row['image'])) { Upload::delete($old['image']); }
            if (!empty($data['is_published']) && empty($old['published_at'])) {
                $row['published_at'] = date('Y-m-d H:i:s');
            }
            Article::updateById($id, $row);
        } else {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['published_at'] = !empty($data['is_published']) ? date('Y-m-d H:i:s') : null;
            Article::insert($row);
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.saved'), 'redirect' => url('admin/articles')]);
        }
        $this->flash('success', __('admin.saved'));
        $this->redirect('admin/articles');
    }
}
