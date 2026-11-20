<?php

namespace App\Models;

use App\Helpers\DatabaseHelper;
use PDO;

class HarvestSchedule
{
    private const SELECT = 'SELECT hs.id, hs.planting_schedule_id, hs.estimated_harvest_date,
                                   hs.actual_harvest_date, hs.notes, hs.status, hs.created_at,
                                   ps.crop_id, ps.land_area, ps.planting_date,
                                   c.name AS crop_name
                            FROM harvest_schedules hs
                            INNER JOIN planting_schedules ps ON ps.id = hs.planting_schedule_id
                            INNER JOIN crops c ON c.id = ps.crop_id';

    private static function db(): PDO
    {
        return DatabaseHelper::getConnection();
    }

    public static function all(string $from = '', string $to = ''): array
    {
        $sql = self::SELECT . ' WHERE 1 = 1';
        $params = [];

        if ($from !== '') {
            $sql .= ' AND hs.estimated_harvest_date >= :date_from';
            $params[':date_from'] = $from;
        }
        if ($to !== '') {
            $sql .= ' AND hs.estimated_harvest_date <= :date_to';
            $params[':date_to'] = $to;
        }

        $stmt = self::db()->prepare($sql . ' ORDER BY hs.estimated_harvest_date ASC, hs.id ASC');
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $stmt = self::db()->prepare(self::SELECT . ' WHERE hs.id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function create(array $data): bool
    {
        $stmt = self::db()->prepare(
            'INSERT INTO harvest_schedules
                (planting_schedule_id, estimated_harvest_date, actual_harvest_date, notes, status)
             VALUES
                (:planting_schedule_id, :estimated, :actual, :notes, :status)'
        );

        return $stmt->execute([
            ':planting_schedule_id' => (int) $data['planting_schedule_id'],
            ':estimated'            => $data['estimated_harvest_date'],
            ':actual'               => $data['actual_harvest_date'],
            ':notes'                => $data['notes'],
            ':status'               => $data['status'],
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = self::db()->prepare(
            'UPDATE harvest_schedules
             SET planting_schedule_id = :planting_schedule_id,
                 estimated_harvest_date = :estimated,
                 actual_harvest_date = :actual,
                 notes = :notes,
                 status = :status
             WHERE id = :id'
        );

        return $stmt->execute([
            ':planting_schedule_id' => (int) $data['planting_schedule_id'],
            ':estimated'            => $data['estimated_harvest_date'],
            ':actual'               => $data['actual_harvest_date'],
            ':notes'                => $data['notes'],
            ':status'               => $data['status'],
            ':id'                   => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare('DELETE FROM harvest_schedules WHERE id = :id');

        return $stmt->execute([':id' => $id]);
    }
}
