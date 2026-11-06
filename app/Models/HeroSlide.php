<?php
// app/Models/HeroSlide.php
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

final class HeroSlide extends Model
{
    protected string $table = 'hero_slides';
    public function active(): array
    {
        return Database::fetchAll("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order, id");
    }
    public function allOrdered(): array
    {
        return Database::fetchAll("SELECT * FROM hero_slides ORDER BY sort_order, id");
    }
}