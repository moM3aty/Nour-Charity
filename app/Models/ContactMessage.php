<?php
// app/Models/ContactMessage.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class ContactMessage extends Model
{
    protected string $table = 'contact_messages';

    public function paginate(array $filters = [], int $page = 1, int $per = 20): array
    {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['status'])) { $where[] = "status = ?"; $params[] = $filters['status']; }
        if (!empty($filters['q'])) {
            $where[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ?)";
            $q = '%' . $filters['q'] . '%';
            $params[] = $q; $params[] = $q; $params[] = $q;
        }
        $w = implode(' AND ', $where);
        $offset = max(0, ($page - 1) * $per);

        $items = Database::fetchAll(
            "SELECT * FROM contact_messages WHERE $w ORDER BY created_at DESC LIMIT $per OFFSET $offset",
            $params
        );
        $total = (int) (Database::fetch("SELECT COUNT(*) AS c FROM contact_messages WHERE $w", $params)['c'] ?? 0);
        return ['items' => $items, 'total' => $total, 'pages' => (int)ceil($total / $per), 'page' => $page];
    }

    public function markRead(int $id): void
    {
        Database::execute("UPDATE contact_messages SET status = CASE WHEN status='new' THEN 'read' ELSE status END WHERE id = ?", [$id]);
    }
}