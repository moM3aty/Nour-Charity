<?php
// app/Models/VolunteerApplication.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class VolunteerApplication extends Model
{
    protected string $table = 'volunteer_applications';

    public function paginate(array $filters = [], int $page = 1, int $per = 20): array
    {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['status'])) { $where[] = "status = ?"; $params[] = $filters['status']; }
        if (!empty($filters['q'])) {
            $where[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $q = '%' . $filters['q'] . '%';
            $params[] = $q; $params[] = $q; $params[] = $q;
        }
        $w = implode(' AND ', $where);
        $offset = max(0, ($page - 1) * $per);

        $items = Database::fetchAll(
            "SELECT * FROM volunteer_applications WHERE $w ORDER BY created_at DESC LIMIT $per OFFSET $offset",
            $params
        );
        $total = (int) (Database::fetch("SELECT COUNT(*) AS c FROM volunteer_applications WHERE $w", $params)['c'] ?? 0);
        return ['items' => $items, 'total' => $total, 'pages' => (int)ceil($total / $per), 'page' => $page];
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): bool
    {
        $allowed = ['new','reviewing','accepted','rejected'];
        if (!in_array($status, $allowed, true)) return false;
        $sql = "UPDATE volunteer_applications SET status = ?";
        $params = [$status];
        if ($notes !== null) {
            $sql .= ", internal_notes = ?";
            $params[] = $notes;
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;
        Database::execute($sql, $params);
        \App\Core\AuditLog::record('volunteer.status', 'volunteer_application', $id, "Status: $status");
        return true;
    }
}