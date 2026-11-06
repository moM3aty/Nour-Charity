<?php
// app/Core/Settings.php
namespace App\Core;

final class Settings
{
    private static ?array $cache = null;

    public static function load(): array
    {
        if (self::$cache !== null) return self::$cache;
        try {
            $rows = Database::fetchAll("SELECT `key`, `value` FROM settings");
            self::$cache = [];
            foreach ($rows as $r) self::$cache[$r['key']] = $r['value'];
        } catch (\Throwable) {
            self::$cache = [];
        }
        return self::$cache;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::load();
        return $all[$key] ?? $default;
    }

    public static function set(string $key, string $value): void
    {
        Database::execute(
            "INSERT INTO settings (`key`, `value`) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)",
            [$key, $value]
        );
        self::$cache = null;
    }
}