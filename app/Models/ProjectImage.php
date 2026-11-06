<?php
// app/Models/ProjectImage.php
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

final class ProjectImage extends Model
{
    protected string $table = 'project_images';
    public function forProject(int $projectId): array
    {
        return Database::fetchAll("SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order, id", [$projectId]);
    }
    public function deleteForProject(int $projectId): void
    {
        Database::execute("DELETE FROM project_images WHERE project_id = ?", [$projectId]);
    }
}