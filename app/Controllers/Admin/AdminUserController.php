<?php
//  app/Controllers/Admin/AdminUserController.php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Role;
use App\Models\User;

final class AdminUserController extends Controller
{
    public function index(): void
    {
        $this->requireAuth('users.manage');
        $this->view('admin/users/index', [
            'title' => 'المستخدمون',
            'items' => (new User())->allWithRole(),
        ], 'admin');
    }

    public function create(): void
    {
        $this->requireAuth('users.manage');
        $this->view('admin/users/form', [
            'title' => 'مستخدم جديد',
            'user'  => null,
            'roles' => (new Role())->all('id'),
        ], 'admin');
    }

    public function store(): void
    {
        $this->requireAuth('users.manage');
        $this->requirePost();
        $v = new Validator($_POST);
        $v->required('name', 'الاسم')->required('email', 'البريد')->email('email')
          ->required('password', 'كلمة المرور')->minLen('password', 10, 'كلمة المرور');
        if ($v->fails()) { Session::flash('error', $v->first()); $this->redirect('admin/users/create'); }

        $email = trim((string)$this->input('email'));
        if (Database::fetch("SELECT id FROM users WHERE email = ?", [$email])) {
            Session::flash('error', 'البريد مستخدم مسبقاً.');
            $this->redirect('admin/users/create');
        }
        $roleId = (int)$this->input('role_id', 0);
        if (!Database::fetch("SELECT id FROM roles WHERE id = ?", [$roleId])) {
            Session::flash('error', 'دور غير صالح.');
            $this->redirect('admin/users/create');
        }
        $id = (new User())->createUser([
            'name'     => trim((string)$this->input('name')),
            'email'    => $email,
            'password' => (string)$this->input('password'),
            'role_id'  => $roleId,
            'is_active'=> (int)($this->input('is_active', 0)) ? 1 : 0,
        ]);
        \App\Core\AuditLog::record('user.create', 'user', $id, $email);
        Session::flash('success', 'تم إنشاء المستخدم.');
        $this->redirect('admin/users');
    }

    public function edit(string $id): void
    {
        $this->requireAuth('users.manage');
        $u = (new User())->find((int)$id);
        if (!$u) { http_response_code(404); exit; }
        $this->view('admin/users/form', [
            'title' => 'تعديل: ' . $u['name'],
            'user'  => $u,
            'roles' => (new Role())->all('id'),
        ], 'admin');
    }

    public function update(string $id): void
    {
        $this->requireAuth('users.manage');
        $this->requirePost();
        $u = (new User())->find((int)$id);
        if (!$u) { http_response_code(404); exit; }

        $data = [
            'name'      => trim((string)$this->input('name')),
            'email'     => trim((string)$this->input('email')),
            'role_id'   => (int)$this->input('role_id', 0),
            'is_active' => (int)($this->input('is_active', 0)) ? 1 : 0,
        ];
        if ((int)$id === Auth::id() && ($data['is_active'] === 0 || (int)$data['role_id'] !== (int)$u['role_id'])) {
            Session::flash('error', 'لا يمكنك تغيير دورك أو تعطيل حسابك.');
            $this->redirect('admin/users/' . $id . '/edit');
        }
        $pw = (string)$this->input('password', '');
        if ($pw !== '') {
            if (strlen($pw) < 10) { Session::flash('error', 'كلمة المرور قصيرة.'); $this->redirect('admin/users/' . $id . '/edit'); }
            $data['password'] = $pw;
        }
        (new User())->updateUser((int)$id, $data);
        \App\Core\AuditLog::record('user.update', 'user', (int)$id);
        Session::flash('success', 'تم التحديث.');
        $this->redirect('admin/users');
    }

    public function delete(string $id): void
    {
        $this->requireAuth('users.manage');
        $this->requirePost();
        if ((int)$id === Auth::id()) { Session::flash('error', 'لا يمكنك حذف حسابك.'); $this->redirect('admin/users'); }
        (new User())->softDelete((int)$id);
        \App\Core\AuditLog::record('user.delete', 'user', (int)$id);
        Session::flash('success', 'تم الحذف.');
        $this->redirect('admin/users');
    }

    /* Roles */
    public function roles(): void
    {
        $this->requireAuth('users.manage');
        $this->view('admin/users/roles', [
            'title' => 'الأدوار والصلاحيات',
            'roles' => (new Role())->allWithCounts(),
            'allPermissions' => (new Role())->allPermissions(),
        ], 'admin');
    }

    public function rolePermissions(string $id): void
    {
        $this->requireAuth('users.manage');
        $role = (new Role())->find((int)$id);
        if (!$role) { http_response_code(404); exit; }
        $this->view('admin/users/role-permissions', [
            'title'         => 'صلاحيات: ' . $role['name'],
            'role'          => $role,
            'assigned'      => (new Role())->permissions((int)$id),
            'allPermissions'=> (new Role())->allPermissions(),
        ], 'admin');
    }

    public function rolePermissionsSave(string $id): void
    {
        $this->requireAuth('users.manage');
        $this->requirePost();
        $slugs = $_POST['permissions'] ?? [];
        if (!is_array($slugs)) $slugs = [];
        (new Role())->syncPermissions((int)$id, $slugs);
        \App\Core\AuditLog::record('role.permissions', 'role', (int)$id, 'Updated permissions');
        Session::flash('success', 'تم تحديث الصلاحيات.');
        $this->redirect('admin/users/roles');
    }
}