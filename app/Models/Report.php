<?php

namespace App\Models;

use App\Helpers\DatabaseHelper;
use PDO;

class Report
{
    private static function db(): PDO
    {
        return DatabaseHelper::getConnection();
    }

    private static function run(string $sql, array $params = []): array
    {
        $stmt = self::db()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function cropSummary(): array
    {
        return self::run(
            'SELECT c.id, c.name,
                    COUNT(ps.id) AS total_plantings,
                    COALESCE(SUM(ps.land_area), 0) AS total_area
             FROM crops c
             LEFT JOIN planting_schedules ps ON ps.crop_id = c.id
             GROUP BY c.id, c.name
             ORDER BY c.name ASC'
        );
    }

    public static function plantingRange(string $from, string $to): array
    {
        return self::run(
            'SELECT ps.id, ps.planting_date, ps.land_area, c.name AS crop_name
             FROM planting_schedules ps
             INNER JOIN crops c ON c.id = ps.crop_id
             WHERE ps.planting_date BETWEEN :date_from AND :date_to
             ORDER BY ps.planting_date ASC',
            [':date_from' => $from, ':date_to' => $to]
        );
    }

    public static function harvestRange(string $from, string $to): array
    {
        return self::run(
            'SELECT hs.id, hs.estimated_harvest_date, hs.actual_harvest_date, hs.status,
                    c.name AS crop_name, ps.land_area
             FROM harvest_schedules hs
             INNER JOIN planting_schedules ps ON ps.id = hs.planting_schedule_id
             INNER JOIN crops c ON c.id = ps.crop_id
             WHERE hs.estimated_harvest_date BETWEEN :date_from AND :date_to
             ORDER BY hs.estimated_harvest_date ASC',
            [':date_from' => $from, ':date_to' => $to]
        );
    }

    public static function rainfallRange(string $from, string $to): array
    {
        return self::run(
            'SELECT id, measurement_date, amount_mm
             FROM rainfall_data
             WHERE measurement_date BETWEEN :date_from AND :date_to
             ORDER BY measurement_date ASC',
            [':date_from' => $from, ':date_to' => $to]
        );
    }
}
