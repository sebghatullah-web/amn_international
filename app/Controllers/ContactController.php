<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Inquiry;

class ContactController extends Controller
{
    public function index(): void
    {
        $this->view('contact/index', [
            'title'       => setting('seo_contact_title', __('contact.title') . ' | ' . setting('company_name')),
            'description' => setting('seo_contact_description', __('contact.subtitle')),
        ]);
    }

    public function store(): void
    {
        $validator = new Validator($_POST, [
            'name'    => 'required|min:2|max:120',
            'email'   => 'required|email|max:190',
            'phone'   => 'nullable|phone|max:40',
            'subject' => 'max:255',
            'message' => 'required|min:10|max:4000',
        ]);

        if ($validator->fails()) {
            $this->respondFailure($validator->errors(), __('errors.generic'));
        }

        $data = $validator->validated();
        Inquiry::record($data, 'contact');

        $this->respondSuccess(__('contact.success'));
    }

    protected function respondSuccess(string $message): void
    {
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => $message]);
        }
        $this->flash('success', $message);
        $this->redirect('contact');
    }

    protected function respondFailure(array $errors, string $message): void
    {
        if ($this->isAjax()) {
            $this->json(['success' => false, 'message' => $message, 'errors' => $errors], 422);
        }
        $this->flash('error', $message);
        $this->redirect('contact');
    }
}
