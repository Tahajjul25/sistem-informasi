<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Helpers\Security;
use App\Helpers\Url;

abstract class BaseController
{
    protected function view(string $view, array $data = [], string $title = 'Sistem Informasi Jadwal Panen'): void
    {
        $data['title'] = $title;
        $data['basePath'] = Url::base();
        $data['currentUser'] = Auth::username();
        $data['csrf_token'] = Auth::csrfToken();

        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException('View tidak ditemukan: ' . $viewFile);
        }

        require $viewFile;
    }

    protected function redirectTo(string $path): void
    {
        Url::redirect($path);
    }

    protected function redirect(string $path): void
    {
        Url::redirect($path);
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method not allowed');
        }
    }

    protected function requireCsrf(): void
    {
        $this->requirePost();
        Auth::verifyCsrf();
    }

    protected function guardAuth(): void
    {
        if (!Auth::check()) {
            $this->redirectTo('/login');
        }
    }

    /** Mengambil parameter GET berupa tanggal; mengembalikan '' bila kosong/tidak valid. */
    protected function dateFromQuery(string $key): string
    {
        $value = trim((string) ($_GET[$key] ?? ''));

        return Security::isValidDate($value) ? $value : '';
    }
}
