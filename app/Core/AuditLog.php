<?php
// app/Core/AuditLog.php
namespace App\Core;

final class AuditLog
{
    public static function record(string $action, string $entity = '', int $entityId = 0, string $description = ''): void
    {
        try {
            Database::execute(
                "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, description, ip_address, user_agent, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
                [
                    (int)(Session::get('user_id', 0)) ?: null,
                    $action,
                    $entity ?: null,
                    $entityId ?: null,
                    $description ?: null,
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
                ]
            );
        } catch (\Throwable $e) {
            error_log('AuditLog failed: ' . $e->getMessage());
        }
    }
}