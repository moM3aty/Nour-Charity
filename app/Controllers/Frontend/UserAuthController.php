<?php
// app/Controllers/Frontend/UserAuthController.php
namespace App\Controllers\Frontend;
use App\Core\Controller; use App\Core\Database; use App\Core\Session; use App\Core\Validator;

final class UserAuthController extends Controller
{
    public function loginForm(): void
    {
        if (\App\Core\Auth::check()) $this->redirect('profile');
            $this->view('frontend/login', ['title' => 'تسجيل الدخول'], 'auth');
    }

    public function login(): void
    {
        $this->requirePost();
        $email = trim((string)$this->input('email'));
        $password = (string)$this->input('password');

        if (\App\Core\Auth::attempt($email, $password)) {
            // ✅ التحقق من وجود صفحة مطلوب الرجوع لها بعد الدخول
            $redirect = Session::get('redirect_after_login');
            if ($redirect) {
                Session::forget('redirect_after_login');
                $this->redirect($redirect);
            }
            $this->redirect('profile');
        }
        
        Session::flash('error', 'البريد الإلكتروني أو كلمة المرور غير صحيحة.');
        $this->redirect('login');
    }

    public function registerForm(): void
    {
        if (\App\Core\Auth::check()) $this->redirect('profile');
        $this->view('frontend/register', ['title' => 'إنشاء حساب'], 'auth');
    }

    public function register(): void
    {
        $this->requirePost();
        $v = new Validator($_POST);
        $v->required('name')->required('email')->email('email')->required('password')->minLen('password', 6);
        
        if ($v->fails()) { Session::flash('error', $v->first()); $this->redirect('register'); }

        $email = trim((string)$this->input('email'));
        if (Database::fetch("SELECT id FROM users WHERE email = ?", [$email])) {
            Session::flash('error', 'البريد مستخدم مسبقاً.'); $this->redirect('register');
        }

        $role = Database::fetch("SELECT id FROM roles WHERE slug = 'viewer' LIMIT 1");
        $roleId = $role ? $role['id'] : null;

        Database::execute("INSERT INTO users (name, email, password_hash, role_id, is_active, created_at) VALUES (?,?,?,?,1,NOW())", 
            [trim((string)$this->input('name')), $email, password_hash((string)$this->input('password'), PASSWORD_DEFAULT), $roleId]
        );

        Session::flash('success', 'تم إنشاء الحساب بنجاح. يمكنك تسجيل الدخول الآن.');
        
        // ✅ نفس فكرة الرجوع للصفحة الأصلية
        $redirect = Session::get('redirect_after_login');
        if ($redirect) {
            Session::forget('redirect_after_login');
            $this->redirect('login'); // يروح للوجين عشان يدخل ويكمل
        }
        $this->redirect('login');
    }

    public function profile(): void
    {
        if (!\App\Core\Auth::check()) $this->redirect('login');
        $user = \App\Core\Auth::user();
        $donations = Database::fetchAll("SELECT * FROM donations WHERE donor_id = (SELECT id FROM donors WHERE email = ?) ORDER BY created_at DESC", [$user['email']]);
        $this->view('frontend/profile', ['title' => 'حسابي', 'user' => $user, 'donations' => $donations]);
    }

    public function logout(): void
    {
        \App\Core\Auth::logout();
        $this->redirect('/');
    }
}