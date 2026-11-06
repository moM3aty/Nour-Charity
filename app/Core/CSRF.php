<?php
// app/Core/CSRF.php
namespace App\Core;

final class CSRF
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . self::token() . '">';
    }

    public static function check(): bool
    {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!is_string($token) || $token === '' || empty($_SESSION['_csrf'])) {
            http_response_code(419);
            exit('CSRF token mismatch.');
        }
        if (!hash_equals($_SESSION['_csrf'], $token)) {
            http_response_code(419);
            exit('CSRF token mismatch.');
        }
        return true;
    }
}