<?php
// app/Models/Donor.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class Donor extends Model
{
    protected string $table = 'donors';

    public function findOrCreate(string $name, string $email, string $phone = '', int $isAnonymous = 0): int
    {
        $email = trim($email);
        $existing = null;
        if ($email !== '') {
            $existing = Database::fetch("SELECT * FROM donors WHERE email = ? LIMIT 1", [$email]);
        }
        if ($existing) {
            Database::execute(
                "UPDATE donors SET name = COALESCE(NULLIF(?, ''), name), phone = COALESCE(NULLIF(?, ''), phone) WHERE id = ?",
                [$name, $phone, $existing['id']]
            );
            return (int) $existing['id'];
        }
        Database::execute(
            "INSERT INTO donors (name, email, phone, is_anonymous) VALUES (?,?,?,?)",
            [$name, $email, $phone, $isAnonymous]
        );
        return (int) Database::conn()->lastInsertId();
    }
}