<?php
// app/Models/Media.php
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

final class Media extends Model
{
    protected string $table = 'media';

    public function paginate(array $filters = [], int $page = 1, int $per = 24): array
    {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['q'])) {
            $where[] = "(original_name LIKE ? OR filename LIKE ?)";
            $q = '%' . $filters['q'] . '%';
            $params[] = $q; $params[] = $q;
        }
        if (!empty($filters['type'])) {
            $where[] = "mime_type LIKE ?";
            $params[] = $filters['type'] . '%';
        }
        $w = implode(' AND ', $where);
        $offset = max(0, ($page - 1) * $per);
        $items = Database::fetchAll("SELECT * FROM media WHERE $w ORDER BY id DESC LIMIT $per OFFSET $offset", $params);
        $total = (int) (Database::fetch("SELECT COUNT(*) AS c FROM media WHERE $w", $params)['c'] ?? 0);
        return ['items' => $items, 'total' => $total, 'pages' => (int)ceil($total / $per), 'page' => $page];
    }
}