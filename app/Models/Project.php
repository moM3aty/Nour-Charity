<?php
// app/Models/Project.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class Project extends Model
{
    protected string $table = 'projects';

    public function paginate(string $search = '', int $categoryId = 0, string $status = 'active', int $page = 1, int $per = 9): array
    {
        $where = ["p.deleted_at IS NULL"];
        $params = [];
        
        if ($status !== '') {
            $where[] = "p.status = ?";
            $params[] = $status;
        }
        if ($categoryId > 0) {
            $where[] = "p.category_id = ?";
            $params[] = $categoryId;
        }
        if ($search !== '') {
            $where[] = "(p.title LIKE ? OR p.short_description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $w = implode(' AND ', $where);
        $offset = max(0, ($page - 1) * $per);
        
        $sql = "SELECT p.*, c.name AS category_name
                FROM projects p 
                LEFT JOIN categories c ON c.id = p.category_id
                WHERE $w 
                ORDER BY p.is_featured DESC, p.created_at DESC
                LIMIT $per OFFSET $offset";
                
        $rows = Database::fetchAll($sql, $params);
        
        $countSql = "SELECT COUNT(p.id) AS c FROM projects p WHERE $w";
        $totalRow = Database::fetch($countSql, $params);
        $total = (int) ($totalRow['c'] ?? 0);
        
        return [
            'items' => is_array($rows) ? $rows : [], 
            'total' => $total, 
            'page'  => $page, 
            'per'   => $per, 
            'pages' => (int)ceil($total / $per)
        ];
    }

    public function bySlug(string $slug): ?array
    {
        return Database::fetch(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM projects p LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.slug = ? AND p.deleted_at IS NULL LIMIT 1",
            [$slug]
        );
    }

    public function active(int $limit = 6, bool $featured = false): array
    {
        $sql = "SELECT p.*, c.name AS category_name
                FROM projects p LEFT JOIN categories c ON c.id = p.category_id
                WHERE p.status = 'active' AND p.deleted_at IS NULL";
        if ($featured) $sql .= " AND p.is_featured = 1";
        $sql .= " ORDER BY p.is_featured DESC, p.created_at DESC LIMIT " . (int)$limit;
        return Database::fetchAll($sql);
    }

    public function images(int $id): array
    {
        return Database::fetchAll("SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order", [$id]);
    }

    public function packages(int $id): array
    {
        return Database::fetchAll("SELECT * FROM donation_packages WHERE project_id = ? AND is_active = 1 ORDER BY sort_order", [$id]);
    }

    public function related(int $categoryId, int $excludeId, int $limit = 3): array
    {
        return Database::fetchAll(
            "SELECT * FROM projects WHERE category_id = ? AND id <> ? AND status='active' AND deleted_at IS NULL LIMIT " . (int)$limit,
            [$categoryId, $excludeId]
        );
    }
}