<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\User;

class AuthController extends AdminController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('admin');
        }
        $this->view('admin/auth/login', [
            'title'       => __('auth.login_title') . ' | ' . setting('company_name'),
            'description' => __('admin.login_heading'),
        ], 'layouts/admin');
    }

    public function login(): void
    {
        $validator = new Validator($_POST, [
            'email'    => 'required|email|max:190',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $this->json(['success' => false, 'message' => __('auth.failed'), 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        if (!Auth::attempt($data['email'], $data['password'])) {
            $this->json(['success' => false, 'message' => __('auth.failed')], 401);
        }

        $this->json(['success' => true, 'message' => __('auth.logout'), 'redirect' => url('admin')]);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('admin/login');
    }
}
