<?php
// app/Controllers/Admin/AdminAuthController.php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Core\Validator;

final class AdminAuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) $this->redirect('admin/dashboard');
        $this->view('admin/login', ['title' => 'دخول لوحة التحكم'], 'admin_auth');
    }

    public function login(): void
    {
        $this->requirePost();
        $v = new Validator($_POST);
        $v->required('email')->email('email')->required('password');

        if ($v->fails()) {
            Session::flash('error', $v->first());
            $this->redirect('admin/login');
        }

        $email = trim((string)$this->input('email'));
        $password = (string)$this->input('password');

        // Throttle: 5 failed attempts in 15 minutes
        $recent = Database::fetch(
            "SELECT COUNT(*) AS c FROM login_attempts
             WHERE email = ? AND success = 0 AND created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)",
            [$email]
        );
        if (((int)($recent['c'] ?? 0)) >= 5) {
            Session::flash('error', 'تم تجاوز عدد المحاولات. حاول مجدداً بعد 15 دقيقة.');
            $this->redirect('admin/login');
        }

        $ok = Auth::attempt($email, $password);

        Database::execute(
            "INSERT INTO login_attempts (email, ip_address, success) VALUES (?, ?, ?)",
            [$email, $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', $ok ? 1 : 0]
        );

        if (!$ok) {
            Session::flash('error', 'البريد الإلكتروني أو كلمة المرور غير صحيحة.');
            $this->redirect('admin/login');
        }

        $this->redirect('admin/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('admin/login');
    }
}