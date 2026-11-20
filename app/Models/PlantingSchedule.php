<?php

namespace App\Models;

use App\Helpers\DatabaseHelper;
use PDO;

class PlantingSchedule
{
    private static function db(): PDO
    {
        return DatabaseHelper::getConnection();
    }

    public static function getAll(string $search = '', string $from = '', string $to = ''): array
    {
        $sql = 'SELECT ps.id, ps.crop_id, ps.land_area, ps.planting_date, ps.created_at,
                       c.name AS crop_name, c.age_days AS crop_age_days
                FROM planting_schedules ps
                INNER JOIN crops c ON c.id = ps.crop_id
                WHERE 1 = 1';
        $params = [];

        if ($search !== '') {
            $sql .= ' AND c.name LIKE :search';
            $params[':search'] = '%' . $search . '%';
        }
        if ($from !== '') {
            $sql .= ' AND ps.planting_date >= :date_from';
            $params[':date_from'] = $from;
        }
        if ($to !== '') {
            $sql .= ' AND ps.planting_date <= :date_to';
            $params[':date_to'] = $to;
        }

        $stmt = self::db()->prepare($sql . ' ORDER BY ps.planting_date DESC, ps.id DESC');
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare(
            'SELECT ps.*, c.name AS crop_name, c.age_days AS crop_age_days
             FROM planting_schedules ps
             INNER JOIN crops c ON c.id = ps.crop_id
             WHERE ps.id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $data): bool
    {
        $stmt = self::db()->prepare(
            'INSERT INTO planting_schedules (crop_id, land_area, planting_date)
             VALUES (:crop_id, :land_area, :planting_date)'
        );

        return $stmt->execute([
            ':crop_id'       => (int) $data['crop_id'],
            ':land_area'     => $data['land_area'],
            ':planting_date' => $data['planting_date'],
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = self::db()->prepare(
            'UPDATE planting_schedules
             SET crop_id = :crop_id, land_area = :land_area, planting_date = :planting_date
             WHERE id = :id'
        );

        return $stmt->execute([
            ':crop_id'       => (int) $data['crop_id'],
            ':land_area'     => $data['land_area'],
            ':planting_date' => $data['planting_date'],
            ':id'            => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare('DELETE FROM planting_schedules WHERE id = :id');

        return $stmt->execute([':id' => $id]);
    }
}
