<?php

namespace App\Models;

use App\Helpers\DatabaseHelper;
use PDO;

class RainfallData
{
    private static function db(): PDO
    {
        return DatabaseHelper::getConnection();
    }

    public static function getAll(string $from = '', string $to = ''): array
    {
        $sql = 'SELECT * FROM rainfall_data WHERE 1 = 1';
        $params = [];

        if ($from !== '') {
            $sql .= ' AND measurement_date >= :date_from';
            $params[':date_from'] = $from;
        }
        if ($to !== '') {
            $sql .= ' AND measurement_date <= :date_to';
            $params[':date_to'] = $to;
        }

        $stmt = self::db()->prepare($sql . ' ORDER BY measurement_date DESC, id DESC');
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare('SELECT * FROM rainfall_data WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $data): bool
    {
        $stmt = self::db()->prepare(
            'INSERT INTO rainfall_data (measurement_date, amount_mm) VALUES (:d, :a)'
        );

        return $stmt->execute([':d' => $data['measurement_date'], ':a' => $data['amount_mm']]);
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = self::db()->prepare(
            'UPDATE rainfall_data SET measurement_date = :d, amount_mm = :a WHERE id = :id'
        );

        return $stmt->execute([':d' => $data['measurement_date'], ':a' => $data['amount_mm'], ':id' => $id]);
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare('DELETE FROM rainfall_data WHERE id = :id');

        return $stmt->execute([':id' => $id]);
    }
}
