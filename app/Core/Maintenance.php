<?php
// app/Core/Maintenance.php
namespace App\Core;

final class Maintenance
{
    public static function check(): void
    {
        if (setting('maintenance.mode', '0') !== '1') return;

        // Allow admin routes through
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        if (str_starts_with($path, '/admin')) return;

        // Allow authenticated admins
        if (Auth::check() && Auth::can('settings.manage')) return;

        http_response_code(503);
        header('Retry-After: 3600');
        if (is_file(BASE_PATH . '/app/Views/errors/maintenance.php')) {
            require BASE_PATH . '/app/Views/errors/maintenance.php';
        } else {
            echo '<h1>الموقع تحت الصيانة</h1>';
        }
        exit;
    }
}