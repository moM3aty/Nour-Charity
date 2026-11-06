<?php
// index.php
declare(strict_types=1);

define('BASE_PATH', __DIR__);

/* Load .env */
$envFile = BASE_PATH . '/.env';
if (is_file($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (!str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k);
        $v = trim($v, " \t\n\r\0\x0B\"'");
        $_ENV[$k] = $v;
        putenv("$k=$v");
    }
}

/* Errors */
$debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
ini_set('display_errors', $debug ? '1' : '0');
error_reporting($debug ? E_ALL : 0);
ini_set('log_errors', '1');
ini_set('error_log', BASE_PATH . '/storage/logs/error.log');

/* Timezone & encoding */
date_default_timezone_set($_ENV['TIMEZONE'] ?? 'Asia/Riyadh');
mb_internal_encoding('UTF-8');

/* Autoloader */
spl_autoload_register(function (string $class): void {
    $map = [
        'App\\' => BASE_PATH . '/app/',
    ];
    foreach ($map as $prefix => $base) {
        if (str_starts_with($class, $prefix)) {
            $rel = substr($class, strlen($prefix));
            $file = $base . str_replace('\\', '/', $rel) . '.php';
            if (is_file($file)) require $file;
            return;
        }
    }
});

/* Helpers */
require BASE_PATH . '/app/Helpers/functions.php';

/* Session */
App\Core\Session::start();

/* Maintenance */
if (class_exists('App\Core\Maintenance')) {
    App\Core\Maintenance::check();
}

/* Router */
$router = new App\Core\Router();
require BASE_PATH . '/config/routes.php';
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);