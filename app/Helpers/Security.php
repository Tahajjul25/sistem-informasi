<?php

namespace App\Helpers;

class Security
{
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    public static function sanitizeString(?string $value): string
    {
        return trim((string) $value);
    }

    public static function isPositiveNumber(mixed $value): bool
    {
        return is_numeric($value) && (float) $value >= 0;
    }

    /** Memastikan string adalah tanggal valid berformat YYYY-MM-DD. */
    public static function isValidDate(?string $value): bool
    {
        if ($value === null || $value === '') {
            return false;
        }

        $date = \DateTime::createFromFormat('Y-m-d', $value);

        return $date !== false && $date->format('Y-m-d') === $value;
    }

    public static function uniqueSlug(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9\s-]/', '', $value);
        $value = preg_replace('/\s+/', '-', $value);
        return trim($value, '-');
    }
}
