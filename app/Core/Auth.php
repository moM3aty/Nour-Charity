<?php
// app/Core/Auth.php
namespace App\Core;

final class Auth
{
    private static ?array $user = null;
    private static ?array $perms = null;

    public static function attempt(string $email, string $password): bool
    {
        $user = Database::fetch(
            "SELECT * FROM users WHERE email = ? AND deleted_at IS NULL LIMIT 1",
            [$email]
        );
        if (!$user) return false;
        if ((int)$user['is_active'] !== 1) return false;

        if (!password_verify($password, $user['password_hash'])) return false;

        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            Database::execute(
                "UPDATE users SET password_hash = ? WHERE id = ?",
                [password_hash($password, PASSWORD_DEFAULT), $user['id']]
            );
        }

        Session::regenerate();
        $_SESSION['user_id'] = (int)$user['id'];
        Database::execute("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
        AuditLog::record('auth.login', 'user', (int)$user['id'], 'User logged in');
        return true;
    }

    public static function user(): ?array
    {
        if (self::$user !== null) return self::$user;
        $id = (int) Session::get('user_id', 0);
        if ($id <= 0) return null;
        self::$user = Database::fetch(
            "SELECT u.*, r.slug AS role_slug, r.name AS role_name
             FROM users u
             LEFT JOIN roles r ON r.id = u.role_id
             WHERE u.id = ? AND u.deleted_at IS NULL LIMIT 1",
            [$id]
        );
        return self::$user;
    }

    public static function check(): bool { return self::user() !== null; }

    /** ✅ إصلاح: تجنب Notice/TypeError عند عدم وجود مستخدم */
    public static function id(): int
    {
        $u = self::user();
        return $u ? (int)$u['id'] : 0;
    }

    public static function logout(): void
    {
        $id = Session::get('user_id');
        if ($id) AuditLog::record('auth.logout', 'user', (int)$id, 'User logged out');
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (!$user) return false;
        if (($user['role_slug'] ?? '') === 'super_admin') return true;

        if (self::$perms === null) {
            self::$perms = [];
            $roleId = (int)$user['role_id'];
            if ($roleId > 0) {
                $rows = Database::fetchAll(
                    "SELECT p.slug FROM permissions p
                     JOIN role_permissions rp ON rp.permission_id = p.id
                     WHERE rp.role_id = ?",
                    [$roleId]
                );
                self::$perms = array_column($rows, 'slug');
            }
        }
        return in_array($permission, self::$perms, true);
    }

    /** تحقق من أي من الصلاحيات - مفيد للمحرر */
    public static function canAny(array $permissions): bool
    {
        foreach ($permissions as $p) {
            if (self::can($p)) return true;
        }
        return false;
    }
}