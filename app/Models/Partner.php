<?php
// app/Models/Category.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class Partner extends Model
{
    protected string $table = 'partners';

    public function active(): array
    {
        return Database::fetchAll("SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order, name");
    }
}