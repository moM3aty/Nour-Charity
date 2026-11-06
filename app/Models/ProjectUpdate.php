<?php
// app/Models/ProjectImage.php
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

final class ProjectUpdate extends Model
{
    protected string $table = 'project_updates';
    public function forProject(int $projectId): array
    {
        return Database::fetchAll("SELECT * FROM project_updates WHERE project_id = ? ORDER BY created_at DESC", [$projectId]);
    }
}