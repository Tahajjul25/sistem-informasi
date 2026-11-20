<?php

namespace App\Models;

use App\Helpers\DatabaseHelper;
use PDO;

class Crop
{
    private static function db(): PDO
    {
        return DatabaseHelper::getConnection();
    }

    public static function all(string $search = ''): array
    {
        $sql = 'SELECT * FROM crops';
        $params = [];

        if ($search !== '') {
            $sql .= ' WHERE name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }

        $stmt = self::db()->prepare($sql . ' ORDER BY name ASC');
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM crops WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function count(): int
    {
        return (int) self::db()->query('SELECT COUNT(*) FROM crops')->fetchColumn();
    }

    public static function create(array $data): bool
    {
        $stmt = self::db()->prepare(
            'INSERT INTO crops (name, age_days, water_requirement) VALUES (:name, :age_days, :water)'
        );

        return $stmt->execute([
            ':name'     => $data['name'],
            ':age_days' => (int) $data['age_days'],
            ':water'    => $data['water_requirement'],
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = self::db()->prepare(
            'UPDATE crops SET name = :name, age_days = :age_days, water_requirement = :water WHERE id = :id'
        );

        return $stmt->execute([
            ':name'     => $data['name'],
            ':age_days' => (int) $data['age_days'],
            ':water'    => $data['water_requirement'],
            ':id'       => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare('DELETE FROM crops WHERE id = :id');

        return $stmt->execute([':id' => $id]);
    }
}
