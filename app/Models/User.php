<?php
// app/Models/User.php
namespace App\Models;
use App\Core\Model;
use App\Core\Database;

final class User extends Model
{
    protected string $table = 'users';

    public function allWithRole(): array
    {
        return Database::fetchAll(
            "SELECT u.*, r.name AS role_name, r.slug AS role_slug
             FROM users u LEFT JOIN roles r ON r.id = u.role_id
             WHERE u.deleted_at IS NULL
             ORDER BY u.created_at DESC"
        );
    }

    public function createUser(array $data): int
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        return $this->create($data);
    }

    public function updateUser(int $id, array $data): int
    {
        if (!empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        unset($data['password'], $data['password2']);
        return $this->update($id, $data);
    }

    public function softDelete(int $id): int
    {
        return Database::execute("UPDATE users SET deleted_at = NOW(), is_active = 0 WHERE id = ?", [$id]);
    }
}