<?php

namespace App\Models;

use App\Helpers\DatabaseHelper;
use PDO;
use Throwable;
use InvalidArgumentException;

class User
{
    private $id;
    private $username;
    private $password;

    public function __construct(
        $id = null,
        $username = '',
        $password = ''
    ) {
        $this->id = $id;
        $this->username = trim($username);
        $this->password = $password;
    }

    private static function db(): PDO
    {
        return DatabaseHelper::getConnection();
    }

    // GETTER
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    // SETTER
    public function setUsername($username)
    {
        $this->username = trim($username);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    // VALIDASI
    private function validate(): void
    {
        if ($this->username === '') {
            throw new InvalidArgumentException(
                'Username wajib diisi.'
            );
        }

        if (strlen($this->username) > 50) {
            throw new InvalidArgumentException(
                'Username maksimal 50 karakter.'
            );
        }

        if ($this->password === '') {
            throw new InvalidArgumentException(
                'Password wajib diisi.'
            );
        }

        if (strlen($this->password) > 255) {
            throw new InvalidArgumentException(
                'Password terlalu panjang.'
            );
        }
    }

    // REGISTRASI DAN UPDATE PENGGUNA
    public function save(): bool
    {
        $this->validate();

        try {
            $db = self::db();

            if ($this->id === null) {

                // Pastikan username belum digunakan
                if (self::findByUsername($this->username)) {
                    return false;
                }

                $sql = "INSERT INTO users
                        (username, password)
                        VALUES
                        (:username, :password)";

                $stmt = $db->prepare($sql);

                $result = $stmt->execute([
                    ':username' => $this->username,
                    ':password' => $this->password
                ]);

                if ($result) {
                    $this->id = $db->lastInsertId();
                }

                return $result;
            }

            $sql = "UPDATE users
                    SET username = :username,
                        password = :password
                    WHERE id = :id";

            $stmt = $db->prepare($sql);

            return $stmt->execute([
                ':username' => $this->username,
                ':password' => $this->password,
                ':id' => $this->id
            ]);

        } catch (Throwable $e) {
            error_log('User Save Error: ' . $e->getMessage());
            return false;
        }
    }

    // MENGHAPUS PENGGUNA
    public function delete(): bool
    {
        if (!$this->id) {
            return false;
        }

        try {
            $stmt = self::db()->prepare(
                "DELETE FROM users WHERE id = :id"
            );

            return $stmt->execute([
                ':id' => $this->id
            ]);

        } catch (Throwable $e) {
            error_log('User Delete Error: ' . $e->getMessage());
            return false;
        }
    }

    // MENCARI PENGGUNA BERDASARKAN ID
    public static function findById($id): ?self
    {
        try {
            $stmt = self::db()->prepare(
                "SELECT id, username, password
                 FROM users
                 WHERE id = :id
                 LIMIT 1"
            );

            $stmt->execute([
                ':id' => $id
            ]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return null;
            }

            return new self(
                $data['id'],
                $data['username'],
                $data['password']
            );

        } catch (Throwable $e) {
            error_log('Find User Error: ' . $e->getMessage());
            return null;
        }
    }

    // MENCARI PENGGUNA BERDASARKAN USERNAME
    public static function findByUsername($username): ?self
    {
        try {
            $stmt = self::db()->prepare(
                "SELECT id, username, password
                 FROM users
                 WHERE username = :username
                 LIMIT 1"
            );

            $stmt->execute([
                ':username' => trim($username)
            ]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return null;
            }

            return new self(
                $data['id'],
                $data['username'],
                $data['password']
            );

        } catch (Throwable $e) {
            error_log('Find Username Error: ' . $e->getMessage());
            return null;
        }
    }

    // VERIFIKASI PASSWORD
    public function verifyPassword($password): bool
    {
        return password_verify(
            $password,
            $this->password
        );
    }

    // PROSES LOGIN
    public static function validateCredentials(
        $username,
        $password
    ): ?self {

        $user = self::findByUsername($username);

        if ($user && $user->verifyPassword($password)) {
            return $user;
        }

        return null;
    }

    // MENGAMBIL SEMUA PENGGUNA
    public static function getAll(): array
    {
        try {
            $stmt = self::db()->query(
                "SELECT id, username, created_at
                 FROM users
                 ORDER BY id DESC"
            );

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            error_log('Get Users Error: ' . $e->getMessage());
            return [];
        }
    }
}
