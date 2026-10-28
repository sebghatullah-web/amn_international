<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Csrf;
use App\Core\Logger;
use App\Core\Validator;
use App\Core\Upload;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

class ProductController extends AdminController
{
    public function index(): void
    {
        $filters = [
            'q'            => trim((string)($_GET['q'] ?? '')),
            'category_id'  => (int)($_GET['category'] ?? 0),
            'active'       => (string)($_GET['active'] ?? ''),
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = Product::adminSearch($filters, $page);
        $this->view('admin/products/index', [
            'title'     => __('admin.products') . ' | ' . setting('company_name'),
            'result'    => $result,
            'filters'   => $filters,
            'categories' => Category::active(),
        ]);
    }

    public function create(): void
    {
        $this->view('admin/products/form', [
            'title'     => __('admin.new') . ' ' . __('admin.products'),
            'mode'      => 'create',
            'product'   => null,
            'categories'=> Category::active(),
            'images'    => [],
            'specs'     => [],
        ]);
    }

    public function edit(int $id): void
    {
        $product = Product::find($id);
        if (!$product) {
            $this->flash('error', __('admin.no_results'));
            $this->redirect('admin/products');
        }
        $this->view('admin/products/form', [
            'title'     => __('admin.edit') . ' ' . __('admin.products'),
            'mode'      => 'edit',
            'product'   => $product,
            'categories'=> Category::active(),
            'images'    => ProductImage::forProduct((int)$id),
            'specs'     => Product::specs((int)$id),
        ]);
    }

    public function update(int $id): void
    {
        $this->save($id);
    }

    public function store(): void
    {
        $this->save(null);
    }
    public function destroy(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $product = Product::find($id);
        if (!$product) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        ProductImage::deleteForProduct((int)$id);
        Product::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/products');
    }

    public function toggle(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $product = Product::find($id);
        if (!$product) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Product::updateById($id, ['is_active' => (int)!$product['is_active']]);
        $this->json(['success' => true, 'message' => __('admin.saved'), 'is_active' => (int)!$product['is_active']]);
    }

    public function uploadImage(): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $productId = (int)($_POST['product_id'] ?? 0);
        $product = Product::find($productId);
        if (!$product) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        $uploaded = Upload::image($_FILES['image'] ?? [], 'products');
        if (!$uploaded['ok']) {
            $this->json(['success' => false, 'message' => $uploaded['error']], 422);
        }
        $imageId = ProductImage::add($productId, $uploaded['path'], $_POST['alt'] ?? $product['name'], !empty($_POST['is_primary']));
        if (!empty($_POST['is_primary'])) {
            ProductImage::makePrimary($imageId);
        }
        $this->json(['success' => true, 'message' => __('admin.saved'), 'imageId' => $imageId]);
    }

    public function destroyImage(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $image = ProductImage::find($id);
        if (!$image) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        Upload::delete($image['path']);
        ProductImage::deleteById($id);
        $this->json(['success' => true, 'message' => __('admin.deleted')]);
    }

    public function primaryImage(int $id): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        ProductImage::makePrimary($id);
        $this->json(['success' => true, 'message' => __('admin.saved')]);
    }
    private function save(?int $id): void
    {
        $product = $id ? Product::find($id) : null;
        if ($id && !$product) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }

        $validator = new Validator($_POST, [
            'name'              => 'required|min:2|max:150',
            'slug'              => 'nullable|max:150',
            'category_id'       => 'required|integer',
            'short_description' => 'max:500',
            'description'       => 'max:5000',
            'country_of_origin' => 'max:100',
            'price_usd'         => 'nullable|decimal',
            'availability_status'=> 'in:in_stock,made_to_order,unavailable',
        ]);

        if ($validator->fails()) {
            $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $slug = Product::uniqueSlug($data['slug'] ?: $data['name'], $id);

        $row = [
            'name'                => mb_substr($data['name'], 0, 150),
            'slug'                => $slug,
            'category_id'         => (int)$data['category_id'],
            'short_description'   => mb_substr($data['short_description'] ?? '', 0, 500),
            'description'         => mb_substr($data['description'] ?? '', 0, 5000),
            'country_of_origin'   => mb_substr($data['country_of_origin'] ?? '', 0, 100),
            'price_usd'           => $data['price_usd'] === '' ? null : (float)$data['price_usd'],
            'availability_status' => $data['availability_status'] ?? 'in_stock',
            'is_active'           => 1,
            'is_featured'         => isset($_POST['is_featured']) ? 1 : 0,
            'is_new'              => isset($_POST['is_new']) ? 1 : 0,
            'is_popular'          => isset($_POST['is_popular']) ? 1 : 0,
            'is_price_published'  => isset($_POST['is_price_published']) ? 1 : 0,
            'seo_title'           => mb_substr($data['seo_title'] ?? '', 0, 200),
            'seo_description'     => mb_substr($data['seo_description'] ?? '', 0, 300),
            'updated_at'          => date('Y-m-d H:i:s'),
        ];

        try {
            if ($id) {
                Product::updateById($id, $row);
            } else {
                $row['created_at'] = date('Y-m-d H:i:s');
                $id = Product::insert($row);
            }

            Product::syncSpecs((int)$id, $_POST['specs'] ?? []);

            // Handle new image uploads (upload_image_1, upload_image_2, ...)
            foreach ($_FILES as $key => $file) {
                if (strpos($key, 'upload_image_') !== 0) {
                    continue;
                }
                if (empty($file['name'])) {
                    continue;
                }
                $uploaded = Upload::image($file, 'products');
                if ($uploaded['ok']) {
                    $n = substr($key, strlen('upload_image_'));
                    $alt = trim($_POST['upload_alt_' . $n] ?? '');
                    ProductImage::add((int)$id, $uploaded['path'], $alt !== '' ? $alt : $row['name']);
                }
            }
        } catch (\Throwable $e) {
            Logger::error('ProductController::save failed: ' . $e->getMessage() . ' | id=' . var_export($id, true));
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => __('errors.generic')], 500);
            }
            $this->flash('error', __('errors.generic'));
            $this->redirect('admin/products');
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.saved'), 'redirect' => url('admin/products')]);
        }
        $this->flash('success', __('admin.saved'));
        $this->redirect('admin/products');
    }
}
