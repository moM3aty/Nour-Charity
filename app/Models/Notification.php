<?php
// app/Models/Notification.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class Notification extends Model
{
    protected string $table = 'notifications';

    public function forUser(int $userId, int $limit = 20): array
    {
        return Database::fetchAll(
            "SELECT * FROM notifications
             WHERE user_id IS NULL OR user_id = ?
             ORDER BY is_read ASC, created_at DESC LIMIT " . (int)$limit,
            [$userId]
        );
    }

    public function unreadCount(int $userId): int
    {
        $row = Database::fetch(
            "SELECT COUNT(*) AS c FROM notifications
             WHERE (user_id IS NULL OR user_id = ?) AND is_read = 0",
            [$userId]
        );
        return (int)($row['c'] ?? 0);
    }

    public function markRead(int $id): void
    {
        Database::execute("UPDATE notifications SET is_read = 1 WHERE id = ?", [$id]);
    }

    public function markAllRead(int $userId): void
    {
        Database::execute(
            "UPDATE notifications SET is_read = 1 WHERE (user_id IS NULL OR user_id = ?) AND is_read = 0",
            [$userId]
        );
    }

    public static function push(string $type, string $title, ?string $message = null, ?int $userId = null): int
    {
        Database::execute(
            "INSERT INTO notifications (user_id, type, title, message, is_read, created_at)
             VALUES (?,?,?,?,0,NOW())",
            [$userId, $type, $title, $message]
        );
        return (int) Database::conn()->lastInsertId();
    }
}