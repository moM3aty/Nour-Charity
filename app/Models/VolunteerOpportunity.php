<?php
// app/Models/VolunteerOpportunity.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class VolunteerOpportunity extends Model
{
    protected string $table = 'volunteer_opportunities';

    public function openAll(): array
    {
        return Database::fetchAll("SELECT * FROM volunteer_opportunities WHERE status = 'open' ORDER BY created_at DESC");
    }

    public function allOrdered(): array
    {
        return Database::fetchAll("SELECT * FROM volunteer_opportunities ORDER BY created_at DESC");
    }
}