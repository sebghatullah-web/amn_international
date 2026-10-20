<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Inquiry;
use App\Models\Product;

class QuoteController extends Controller
{
    public function index(): void
    {
        $product = null;
        $slug = (string)($_GET['product'] ?? '');
        if ($slug !== '') {
            $candidate = Product::findBySlug($slug);
            if ($candidate && !empty($candidate['is_active'])) {
                $product = $candidate;
            }
        }

        $this->view('quote/index', [
            'title'       => setting('seo_quote_title', __('quote.title') . ' | ' . setting('company_name')),
            'description' => setting('seo_quote_description', __('quote.subtitle')),
            'product'     => $product,
            'products'    => Product::search(['sort' => 'name'], 1, 500)['rows'],
        ]);
    }

    public function store(): void
    {
        $validator = new Validator($_POST, [
            'name'      => 'required|min:2|max:120',
            'email'     => 'required|email|max:190',
            'phone'     => 'required|phone|max:40',
            'country'   => 'max:80',
            'product_id'=> 'nullable|integer',
            'quantity'  => 'max:60',
            'message'   => 'required|min:10|max:4000',
        ]);

        if ($validator->fails()) {
            $this->respondFailure($validator->errors(), __('errors.generic'));
        }

        $data = $validator->validated();

        // Ensure the referenced product exists (optional field).
        if (!empty($data['product_id'])) {
            $product = Product::find((int)$data['product_id']);
            if (!$product || empty($product['is_active'])) {
                $data['product_id'] = null;
            }
        } else {
            $data['product_id'] = null;
        }

        Inquiry::record($data, 'quote');

        $this->respondSuccess(__('quote.success'));
    }

    private function respondSuccess(string $message): void
    {
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => $message]);
        }
        $this->flash('success', $message);
        $this->redirect('quote');
    }

    private function respondFailure(array $errors, string $message): void
    {
        if ($this->isAjax()) {
            $this->json(['success' => false, 'message' => $message, 'errors' => $errors], 422);
        }
        $this->flash('error', $message);
        $this->redirect('quote');
    }
}
