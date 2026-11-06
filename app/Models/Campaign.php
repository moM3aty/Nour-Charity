<?php
// app/Models/Campaign.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class Campaign extends Model
{
    protected string $table = 'campaigns';

    public function active(int $limit = 4): array
    {
        return Database::fetchAll(
            "SELECT * FROM campaigns WHERE status = 'active' AND is_active = 1 ORDER BY created_at DESC LIMIT " . (int)$limit
        );
    }

    public function bySlug(string $slug): ?array
    {
        return Database::fetch("SELECT * FROM campaigns WHERE slug = ? LIMIT 1", [$slug]);
    }
}