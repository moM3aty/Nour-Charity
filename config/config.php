<?php
// config/config.php
declare(strict_types=1);

return [
    'app' => [
        'name'      => $_ENV['APP_NAME']    ?? 'Nour Makha',
        'name_ar'   => $_ENV['APP_NAME_AR'] ?? 'نور مخة العالمية',
        'url'       => rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/'),
        'env'       => $_ENV['APP_ENV'] ?? 'production',
        'debug'     => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
        'lang'      => $_ENV['APP_LANG'] ?? 'ar',
        'timezone'  => $_ENV['TIMEZONE'] ?? 'Asia/Riyadh',
    ],
    'db' => [
        'host'    => $_ENV['DB_HOST'] ?? 'localhost',
        'name'    => $_ENV['DB_NAME'] ?? 'u582652079_charity',
        'user'    => $_ENV['DB_USER'] ?? 'u582652079_charity',
        'pass'    => $_ENV['DB_PASS'] ?? 'TSs!Po2^',
        'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    ],
    'session' => [
        'name'     => $_ENV['SESSION_NAME'] ?? 'nour_session',
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200),
        'secure'   => ($_ENV['SESSION_SECURE'] ?? 'false') === 'true',
    ],
    'currency' => [
        'code'   => $_ENV['CURRENCY'] ?? 'SAR',
        'symbol' => $_ENV['CURRENCY_SYMBOL'] ?? 'ر.س',
    ],
    'mail' => [
        'host'       => $_ENV['MAIL_HOST'] ?? '',
        'port'       => (int)($_ENV['MAIL_PORT'] ?? 587),
        'username'   => $_ENV['MAIL_USERNAME'] ?? '',
        'password'   => $_ENV['MAIL_PASSWORD'] ?? '',
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
        'from_addr'  => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@localhost',
        'from_name'  => $_ENV['MAIL_FROM_NAME'] ?? 'Nour Makha',
    ],
    'uploads' => [
        'public_path'  => BASE_PATH . '/uploads',
        'public_url'   => '/uploads',
        'private_path' => BASE_PATH . '/storage/private',
        'max_size'     => 8 * 1024 * 1024,
        'allowed_images' => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'],
        'allowed_docs'   => ['application/pdf', 'application/msword',
                             'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
    ],
];