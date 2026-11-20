<?php

namespace App\Helpers;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $pdo = null;

    /** Tabel dan kolom yang wajib ada (sesuai database/schema.sql). */
    private const REQUIRED = [
        'users'              => ['id', 'username', 'password'],
        'crops'              => ['id', 'name', 'age_days', 'water_requirement', 'created_at'],
        'planting_schedules' => ['id', 'crop_id', 'land_area', 'planting_date', 'created_at'],
        'harvest_schedules'  => ['id', 'planting_schedule_id', 'estimated_harvest_date', 'actual_harvest_date', 'notes', 'status', 'created_at'],
        'rainfall_data'      => ['id', 'measurement_date', 'amount_mm', 'created_at'],
        'reports'            => ['id', 'type', 'content', 'created_at'],
    ];

    private static function config(): array
    {
        return require __DIR__ . '/../../config/database.php';
    }

    public static function connect(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $c = self::config();
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $c['host'],
            $c['port'],
            $c['database'],
            $c['charset']
        );

        try {
            self::$pdo = new PDO($dsn, $c['username'], $c['password'], $c['options']);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new RuntimeException(
                'Koneksi database gagal. Pastikan MySQL di XAMPP sudah di-Start. Detail: ' . $e->getMessage()
            );
        }

        return self::$pdo;
    }

    /** Membuat database kosong jika belum ada. */
    public static function ensureDatabaseExists(): void
    {
        $c = self::config();

        if (!preg_match('/^[A-Za-z0-9_]+$/', (string) $c['database'])) {
            throw new RuntimeException('Nama database tidak valid di config/database.php.');
        }

        $dsn = sprintf('mysql:host=%s;port=%s;charset=%s', $c['host'], $c['port'], $c['charset']);

        try {
            $pdo = new PDO($dsn, $c['username'], $c['password'], $c['options']);
            $pdo->exec(
                'CREATE DATABASE IF NOT EXISTS `' . $c['database'] . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
            );
        } catch (PDOException $e) {
            error_log('Database create error: ' . $e->getMessage());
            throw new RuntimeException(
                'Tidak bisa terhubung ke MySQL. Pastikan MySQL di XAMPP sudah di-Start. Detail: ' . $e->getMessage()
            );
        }
    }

    /**
     * Dipanggil pada setiap request: membuat database + tabel + data contoh
     * bila belum ada, dan memastikan struktur tabel sesuai kode.
     */
    public static function setup(): void
    {
        self::ensureDatabaseExists();
        $pdo = self::connect();

        $existing = self::existingColumns($pdo);
        $missingTable = false;
        foreach (array_keys(self::REQUIRED) as $table) {
            if (!isset($existing[$table])) {
                $missingTable = true;
                break;
            }
        }

        if ($missingTable) {
            $firstInstall = !isset($existing['users']);
            self::runSchema($pdo);
            if ($firstInstall) {
                self::seed($pdo);
            }
            $existing = self::existingColumns($pdo);
        }

        foreach (self::REQUIRED as $table => $columns) {
            foreach ($columns as $column) {
                if (!isset($existing[$table][$column])) {
                    throw new RuntimeException(
                        "Struktur tabel `$table` di database lama tidak cocok (kolom `$column` tidak ada). "
                        . 'Buka phpMyAdmin, hapus (Drop) database `' . self::config()['database']
                        . '`, lalu refresh halaman ini. Database akan dibuat ulang otomatis.'
                    );
                }
            }
        }
    }

    private static function existingColumns(PDO $pdo): array
    {
        $rows = $pdo->query(
            'SELECT TABLE_NAME AS t, COLUMN_NAME AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE()'
        )->fetchAll(PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $row) {
            $map[strtolower($row['t'])][strtolower($row['c'])] = true;
        }

        return $map;
    }

    private static function runSchema(PDO $pdo): void
    {
        $sql = file_get_contents(__DIR__ . '/../../database/schema.sql');
        if ($sql === false) {
            throw new RuntimeException('File database/schema.sql tidak ditemukan.');
        }

        $sql = preg_replace('/^\s*--.*$/m', '', $sql);

        foreach (explode(';', $sql) as $statement) {
            $statement = trim($statement);
            if ($statement === '' || preg_match('/^(CREATE\s+DATABASE|USE)\b/i', $statement)) {
                continue;
            }
            $pdo->exec($statement);
        }
    }

    /** Data contoh awal: akun admin, daftar tanaman, dan curah hujan. */
    private static function seed(PDO $pdo): void
    {
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:u, :p)');
        $stmt->execute([':u' => 'admin', ':p' => password_hash('admin123', PASSWORD_DEFAULT)]);

        $crops = [
            ['Padi', 120, 900], ['Jagung', 100, 500], ['Kedelai', 85, 450],
            ['Ubi Jalar', 120, 600], ['Kacang Hijau', 65, 350], ['Tomat', 90, 600],
            ['Cabai', 120, 600], ['Bawang Merah', 65, 350], ['Sayuran Hijau', 30, 300],
        ];
        $stmt = $pdo->prepare('INSERT INTO crops (name, age_days, water_requirement) VALUES (:n, :a, :w)');
        foreach ($crops as [$name, $age, $water]) {
            $stmt->execute([':n' => $name, ':a' => $age, ':w' => $water]);
        }

        $rain = [100, 120, 80, 150, 90, 200, 110, 130, 140, 160];
        $stmt = $pdo->prepare('INSERT INTO rainfall_data (measurement_date, amount_mm) VALUES (:d, :a)');
        foreach ($rain as $i => $amount) {
            $stmt->execute([':d' => sprintf('2023-01-%02d', $i + 1), ':a' => $amount]);
        }
    }
}
