<?php
// app/Core/Database.php
namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    public static function conn(): PDO
    {
        if (self::$pdo !== null) return self::$pdo;

        $cfg = require BASE_PATH . '/config/config.php';
        $db  = $cfg['db'];

        $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";
        try {
            self::$pdo = new PDO($dsn, $db['user'], $db['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$db['charset']} COLLATE {$db['charset']}_unicode_ci",
            ]);
        } catch (PDOException $e) {
            error_log('DB connection failed: ' . $e->getMessage());
            http_response_code(500);
            exit('Database connection error. Please check configuration.');
        }
        return self::$pdo;
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::conn()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function insert(string $sql, array $params = []): int
    {
        self::query($sql, $params);
        return (int) self::conn()->lastInsertId();
    }

    public static function execute(string $sql, array $params = []): int
    {
        return self::query($sql, $params)->rowCount();
    }
}