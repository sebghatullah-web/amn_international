<?php

namespace App\Controllers\Admin;

use App\Core\AdminController;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Validator;
use App\Models\User;

class UserController extends AdminController
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['page'] ?? 1));
        $where = '1=1';
        $params = [];
        if ($q !== '') {
            $where .= ' AND (name LIKE ? OR email LIKE ?)';
            $like = '%' . $q . '%';
            $params = [$like, $like];
        }
        $total = User::count($where, $params);
        $pages = User::pages($total, 15);
        $page = max(1, min($page, $pages));
        $users = User::run(
            'SELECT id, name, email, role, is_active, created_at, last_login
             FROM users WHERE ' . $where . ' ORDER BY id DESC LIMIT ' . ($page - 1) * 15 . ', 15',
            $params
        )->fetchAll();
        $this->view('admin/users/index', [
            'title'   => __('admin.users') . ' | ' . setting('company_name'),
            'users'   => $users,
            'total'   => $total,
            'page'    => $page,
            'pages'   => $pages,
            'q'       => $q,
            'pager'   => pagination($page, $pages, 'admin/users', $q !== '' ? ['q' => $q] : [], 'users-pagination'),
        ]);
    }

    public function create(): void
    {
        $this->view('admin/users/form', [
            'title'   => __('admin.new') . ' ' . __('admin.users'),
            'mode'    => 'create',
            'user'    => null,
        ]);
    }

    public function store(): void
    {
        $this->save(null);
    }

    public function edit(int $id): void
    {
        $user = User::find($id);
        if (!$user) {
            $this->flash('error', __('admin.no_results'));
            $this->redirect('admin/users');
        }
        $this->view('admin/users/form', [
            'title' => __('admin.edit') . ' ' . __('admin.users'),
            'mode'  => 'edit',
            'user'  => $user,
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
        if ((int)Auth::id() === $id) {
            $this->json(['success' => false, 'message' => __('errors.generic')], 422);
        }
        $user = User::find($id);
        if (!$user) {
            $this->json(['success' => false, 'message' => __('admin.no_results')], 404);
        }
        User::deleteById($id);
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.deleted')]);
        }
        $this->flash('success', __('admin.deleted'));
        $this->redirect('admin/users');
    }

    public function changePassword(): void
    {
        if (!Csrf::verify()) {
            $this->json(['success' => false, 'message' => __('errors.csrf')], 419);
        }
        $validator = new Validator($_POST, [
            'current_password' => 'required',
            'new_password'     => 'required|min:8|max:128',
        ]);
        if ($validator->fails()) {
            $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $user = User::find(Auth::id());
        if (!$user || !password_verify($data['current_password'], $user['password'])) {
            $this->json(['success' => false, 'message' => __('auth.failed')], 401);
        }
        User::updateById(Auth::id(), ['password' => password_hash($data['new_password'], PASSWORD_DEFAULT)]);
        $this->json(['success' => true, 'message' => __('admin.saved')]);
    }

    private function save(?int $id): void
    {
        $validator = new Validator($_POST, [
            'name'     => 'required|min:2|max:120',
            'email'    => 'required|email|max:190',
            'role'     => 'in:admin,editor',
            'password' => $id ? 'nullable|min:8|max:128' : 'required|min:8|max:128',
        ]);
        if ($validator->fails()) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => __('errors.generic'), 'errors' => $validator->errors()], 422);
            }
            $this->flash('error', __('errors.generic'));
            $this->redirect($id ? 'admin/users/' . $id . '/edit' : 'admin/users/create');
        }

        $data = $validator->validated();
        $exists = User::findByEmail($data['email']);
        if ($exists && $exists['id'] != $id) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Email already in use'], 422);
            }
            $this->flash('error', 'Email already in use');
            $this->redirect($id ? 'admin/users/' . $id . '/edit' : 'admin/users/create');
        }

        $row = [
            'name'     => mb_substr($data['name'], 0, 120),
            'email'    => mb_substr($data['email'], 0, 190),
            'role'     => $data['role'] ?? 'editor',
            'is_active'=> 1,
        ];
        if ($data['password'] !== '') {
            $row['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($id) {
            User::updateById($id, $row);
        } else {
            User::insert($row);
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => __('admin.saved'), 'redirect' => url('admin/users')]);
        }
        $this->flash('success', __('admin.saved'));
        $this->redirect('admin/users');
    }
}
