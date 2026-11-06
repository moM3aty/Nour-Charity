<?php
// app/Core/Session.php
namespace App\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) return;

        $cfg = require BASE_PATH . '/config/config.php';
        $s = $cfg['session'];

        session_name($s['name']);
        session_set_cookie_params([
            'lifetime' => $s['lifetime'],
            'path'     => '/',
            'domain'   => '',
            'secure'   => $s['secure'],
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();

        // idle timeout
        if (isset($_SESSION['_last']) && (time() - $_SESSION['_last']) > $s['lifetime']) {
            $_SESSION = [];
            session_destroy();
            session_start();
        }
        $_SESSION['_last'] = time();
    }

    public static function set(string $k, mixed $v): void   { $_SESSION[$k] = $v; }
    public static function get(string $k, mixed $d = null): mixed { return $_SESSION[$k] ?? $d; }
    public static function forget(string $k): void          { unset($_SESSION[$k]); }
    public static function regenerate(): void               { session_regenerate_id(true); }
    public static function flash(string $k, mixed $v): void { $_SESSION['_flash'][$k] = $v; }
    public static function getFlash(string $k): mixed {
        $v = $_SESSION['_flash'][$k] ?? null;
        unset($_SESSION['_flash'][$k]);
        return $v;
    }
}