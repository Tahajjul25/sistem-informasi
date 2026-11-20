<?php

define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT . '/vendor/autoload.php';

use App\Helpers\Database;
use App\Helpers\Url;

$config = require APP_ROOT . '/config/app.php';
date_default_timezone_set($config['timezone'] ?? 'Asia/Jakarta');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Menampilkan halaman error sederhana lalu menghentikan eksekusi. */
function render_error(int $code, string $title, string $message): void
{
    http_response_code($code);
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>'
        . htmlspecialchars($title)
        . '</title></head><body style="font-family:Arial;background:#081A2F;color:#fff;padding:32px">'
        . '<h2>' . htmlspecialchars($title) . '</h2>'
        . '<p style="line-height:1.6">' . htmlspecialchars($message) . '</p></body></html>';
    exit;
}

try {
    Database::setup();
} catch (Throwable $e) {
    render_error(500, 'Koneksi / Struktur Database Bermasalah', $e->getMessage());
}

require_once APP_ROOT . '/routes/web.php';

$requestUri = Url::routePath();
$requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

try {
    foreach ($routes as $route) {
        $routeUri = '/' . trim($route['uri'], '/');

        if ($routeUri === $requestUri && strtoupper($route['method']) === $requestMethod) {
            $controllerName = $route['controller'];
            $methodName = $route['methodName'];

            if (class_exists($controllerName) && method_exists($controllerName, $methodName)) {
                (new $controllerName())->$methodName();
                exit;
            }
        }
    }
} catch (Throwable $e) {
    if (str_contains($e->getMessage(), 'CSRF')) {
        render_error(419, 'Sesi Kedaluwarsa', 'Token keamanan tidak valid. Kembali ke halaman sebelumnya, refresh, lalu coba lagi.');
    }

    error_log((string) $e);
    $detail = !empty($config['debug']) ? $e->getMessage() . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')' : 'Terjadi kesalahan pada server.';
    render_error(500, 'Terjadi Kesalahan', $detail);
}

render_error(404, '404', 'Halaman tidak ditemukan.');
