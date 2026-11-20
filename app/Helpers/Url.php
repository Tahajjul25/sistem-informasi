<?php

namespace App\Helpers;

/**
 * Menentukan alamat dasar (folder /public) secara otomatis, sehingga aplikasi
 * jalan di XAMPP (subfolder htdocs) maupun `php -S localhost:8000 -t public`.
 */
class Url
{
    private static ?string $base = null;

    /** Path URL ke folder public (sudah di-encode), tanpa garis miring di akhir. */
    public static function base(): string
    {
        if (self::$base !== null) {
            return self::$base;
        }

        $projectRoot = dirname(__DIR__, 2);
        $publicDir = realpath($projectRoot . '/public') ?: $projectRoot . '/public';
        $publicDir = rtrim(str_replace('\\', '/', $publicDir), '/');

        $docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
        $docRoot = rtrim(str_replace('\\', '/', $docRoot), '/');

        $path = '';
        if ($docRoot !== '' && stripos($publicDir, $docRoot) === 0) {
            $path = substr($publicDir, strlen($docRoot));
        }

        $path = trim($path, '/');
        $path = $path === '' ? '' : '/' . $path;

        self::$base = implode('/', array_map('rawurlencode', explode('/', $path)));

        return self::$base;
    }

    /** Membuat URL lengkap dari path aplikasi, mis. Url::to('/crops'). */
    public static function to(string $path = ''): string
    {
        return self::base() . '/' . ltrim($path, '/');
    }

    /** Path route yang diminta browser, relatif terhadap aplikasi (mis. "/crops"). */
    public static function routePath(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = rawurldecode(is_string($uri) && $uri !== '' ? $uri : '/');

        $base = rawurldecode(self::base());
        $root = $base !== '' ? rtrim(str_replace('\\', '/', dirname($base)), '/') : '';

        foreach ([$base, $root] as $prefix) {
            if ($prefix !== '' && ($uri === $prefix || str_starts_with($uri, $prefix . '/'))) {
                $uri = substr($uri, strlen($prefix));
                break;
            }
        }

        return '/' . trim($uri, '/');
    }

    public static function redirect(string $path): void
    {
        header('Location: ' . self::to($path));
        exit;
    }
}
