<?php
// app/Models/Category.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class Category extends Model
{
    protected string $table = 'categories';

    public function active(): array
    {
        return Database::fetchAll("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order, name");
    }
}