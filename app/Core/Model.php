<?php
// app/Core/Model.php
namespace App\Core;

abstract class Model
{
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function find(int $id): ?array
    {
        return Database::fetch("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", [$id]);
    }

    public function findBy(string $column, mixed $value): ?array
    {
        return Database::fetch("SELECT * FROM `{$this->table}` WHERE `$column` = ? LIMIT 1", [$value]);
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        return Database::fetchAll("SELECT * FROM `{$this->table}` ORDER BY $orderBy");
    }

    public function create(array $data): int
    {
        $cols = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($cols), '?'));
        $colsList = '`' . implode('`,`', $cols) . '`';
        return Database::insert("INSERT INTO `{$this->table}` ($colsList) VALUES ($placeholders)", array_values($data));
    }

    public function update(int $id, array $data): int
    {
        if (empty($data)) return 0;
        $set = implode(',', array_map(fn($c) => "`$c` = ?", array_keys($data)));
        $params = array_values($data);
        $params[] = $id;
        return Database::execute("UPDATE `{$this->table}` SET $set WHERE `{$this->primaryKey}` = ?", $params);
    }

    public function delete(int $id): int
    {
        return Database::execute("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?", [$id]);
    }
}