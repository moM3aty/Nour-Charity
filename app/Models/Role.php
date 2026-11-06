<?php
// app/Models/Role.php
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

final class Role extends Model
{
    protected string $table = 'roles';

    public function allWithCounts(): array
    {
        return Database::fetchAll(
            "SELECT r.*, (SELECT COUNT(*) FROM users u WHERE u.role_id = r.id AND u.deleted_at IS NULL) AS users_count
             FROM roles r ORDER BY r.id"
        );
    }

    public function permissions(int $roleId): array
    {
        $rows = Database::fetchAll(
            "SELECT p.id, p.slug FROM permissions p
             JOIN role_permissions rp ON rp.permission_id = p.id
             WHERE rp.role_id = ?",
            [$roleId]
        );
        return array_column($rows, 'slug');
    }

    public function allPermissions(): array
    {
        return Database::fetchAll("SELECT * FROM permissions ORDER BY id");
    }

    public function syncPermissions(int $roleId, array $slugs): void
    {
        Database::execute("DELETE FROM role_permissions WHERE role_id = ?", [$roleId]);
        if (empty($slugs)) return;
        $placeholders = implode(',', array_fill(0, count($slugs), '?'));
        $perms = Database::fetchAll("SELECT id FROM permissions WHERE slug IN ($placeholders)", $slugs);
        foreach ($perms as $p) {
            Database::execute("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?,?)", [$roleId, $p['id']]);
        }
    }
}