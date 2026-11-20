<?php

namespace App\Helpers;

use PDO;

class DatabaseHelper
{
    public static function getConnection(): PDO
    {
        return Database::connect();
    }

    public static function testConnection(): bool
    {
        try {
            self::getConnection()->query('SELECT 1');
            return true;
        } catch (\Throwable $e) {
            error_log('Database Test Error: ' . $e->getMessage());
            return false;
        }
    }
}
