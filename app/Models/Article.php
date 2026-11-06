<?php
// app/Models/Article.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class Article extends Model
{
    protected string $table = 'articles';

    public function published(int $limit = 3): array
    {
        return Database::fetchAll(
            "SELECT a.*, c.name AS category_name FROM articles a
             LEFT JOIN article_categories c ON c.id = a.category_id
             WHERE a.status = 'published' AND a.deleted_at IS NULL
             ORDER BY a.is_featured DESC, a.published_at DESC, a.created_at DESC
             LIMIT " . (int)$limit
        );
    }

    public function paginate(int $page = 1, int $per = 9): array
    {
        $offset = max(0, ($page - 1) * $per);
        $items = Database::fetchAll(
            "SELECT a.*, c.name AS category_name FROM articles a
             LEFT JOIN article_categories c ON c.id = a.category_id
             WHERE a.status = 'published' AND a.deleted_at IS NULL
             ORDER BY a.published_at DESC LIMIT $per OFFSET $offset"
        );
        $total = (int) (Database::fetch("SELECT COUNT(*) AS c FROM articles WHERE status='published' AND deleted_at IS NULL")['c'] ?? 0);
        return ['items' => $items, 'total' => $total, 'pages' => (int)ceil($total / $per)];
    }

    public function bySlug(string $slug): ?array
    {
        return Database::fetch(
            "SELECT a.*, c.name AS category_name FROM articles a
             LEFT JOIN article_categories c ON c.id = a.category_id
             WHERE a.slug = ? AND a.status='published' AND a.deleted_at IS NULL LIMIT 1",
            [$slug]
        );
    }
}