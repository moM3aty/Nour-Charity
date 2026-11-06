<?php
// app/Models/AssistanceRequest.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class AssistanceRequest extends Model
{
    protected string $table = 'assistance_requests';

    public function createRequest(array $data): int
    {
        $number = generate_ref('HLP');
        Database::execute(
            "INSERT INTO assistance_requests
             (request_number, full_name, email, phone, category, location, description, status, created_at)
             VALUES (?,?,?,?,?,?,?,'new',NOW())",
            [
                $number,
                $data['full_name'],
                $data['email'] ?? null,
                $data['phone'] ?? null,
                $data['category'] ?? null,
                $data['location'] ?? null,
                $data['description'] ?? null,
            ]
        );
        return (int) Database::conn()->lastInsertId();
    }

    public function byNumber(string $num): ?array
    {
        return Database::fetch("SELECT * FROM assistance_requests WHERE request_number = ? LIMIT 1", [$num]);
    }

    public function paginate(array $filters = [], int $page = 1, int $per = 20): array
    {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['status'])) { $where[] = "status = ?"; $params[] = $filters['status']; }
        if (!empty($filters['category'])) { $where[] = "category = ?"; $params[] = $filters['category']; }
        if (!empty($filters['q'])) {
            $where[] = "(full_name LIKE ? OR phone LIKE ? OR request_number LIKE ?)";
            $q = '%' . $filters['q'] . '%';
            $params[] = $q; $params[] = $q; $params[] = $q;
        }
        $w = implode(' AND ', $where);
        $offset = max(0, ($page - 1) * $per);

        $items = Database::fetchAll(
            "SELECT * FROM assistance_requests WHERE $w ORDER BY created_at DESC LIMIT $per OFFSET $offset",
            $params
        );
        $total = (int) (Database::fetch("SELECT COUNT(*) AS c FROM assistance_requests WHERE $w", $params)['c'] ?? 0);
        return ['items' => $items, 'total' => $total, 'pages' => (int)ceil($total / $per), 'page' => $page];
    }

    public function documents(int $id): array
    {
        return Database::fetchAll("SELECT * FROM assistance_documents WHERE request_id = ? ORDER BY id", [$id]);
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): bool
    {
        $allowed = ['new','reviewing','approved','rejected','completed'];
        if (!in_array($status, $allowed, true)) return false;
        $sql = "UPDATE assistance_requests SET status = ?";
        $params = [$status];
        if ($notes !== null) {
            $sql .= ", internal_notes = ?";
            $params[] = $notes;
        }
        $sql .= " WHERE id = ?";
        $params[] = $id;
        Database::execute($sql, $params);
        \App\Core\AuditLog::record('assistance.status', 'assistance_request', $id, "Status: $status");
        return true;
    }
}