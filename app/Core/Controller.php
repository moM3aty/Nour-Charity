<?php
// app/Core/Controller.php
namespace App\Core;

abstract class Controller
{
    protected function view(string $tpl, array $data = [], string $layout = 'frontend'): void
    {
        View::render($tpl, $data, $layout);
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $to, int $code = 302): void
    {
        header('Location: ' . url($to), true, $code);
        exit;
    }

    protected function back(): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($ref);
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function requirePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
        CSRF::check();
    }

    protected function requireAuth(string $permission = ''): void
    {
        if (!Auth::check()) {
            $this->redirect('/admin/login');
        }
        if ($permission !== '' && !Auth::can($permission)) {
            http_response_code(403);
            exit('Forbidden');
        }
    }

    /** ✅ دالة جديدة: تجبر المستخدم يسجل دخول قبل التبرع */
    protected function requireUserLogin(string $redirectTo = 'donate'): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'يجب تسجيل الدخول أولاً لإتمام عملية التبرع.');
            // نحفظ الصفحة اللي كان عايز يروحها عشان نرجعه لها بعد الدخول
            Session::set('redirect_after_login', $redirectTo);
            $this->redirect('login');
        }
    }
}