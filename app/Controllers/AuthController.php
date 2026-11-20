<?php

namespace App\Controllers;

use App\Helpers\Auth;
use App\Models\User;

class AuthController extends BaseController
{
    public function login(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login', ['username' => '', 'errors' => []], 'Masuk');
    }

    public function authenticate(): void
    {
        $this->requireCsrf();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $errors = [];

        if ($username === '') $errors['username'] = ['Username wajib diisi.'];
        if ($password === '') $errors['password'] = ['Password wajib diisi.'];

        if (!$errors) {
            $user = User::validateCredentials($username, $password);
            if ($user) {
                Auth::login(['id' => $user->getId(), 'username' => $user->getUsername()]);
                session_regenerate_id(true);
                $this->redirect('/dashboard');
            }
            $errors['login'] = ['Username atau password salah.'];
        }

        $this->view('auth/login', ['username' => $username, 'errors' => $errors], 'Masuk');
    }

    public function register(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/register', ['username' => '', 'errors' => []], 'Daftar Akun');
    }

    public function store(): void
    {
        $this->requireCsrf();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
        $errors = [];

        if ($username === '' || strlen($username) > 50) $errors['username'] = ['Username wajib diisi dan maksimal 50 karakter.'];
        if (strlen($password) < 6) $errors['password'] = ['Password minimal 6 karakter.'];
        if ($confirmPassword !== $password) $errors['confirm_password'] = ['Konfirmasi password tidak cocok.'];
        if (!$errors && User::findByUsername($username)) $errors['username'] = ['Username sudah digunakan.'];

        if (!$errors) {
            $user = new User(null, $username, password_hash($password, PASSWORD_DEFAULT));
            if ($user->save()) $this->redirect('/login');
            $errors['register'] = ['Pendaftaran gagal. Silakan coba lagi.'];
        }

        $this->view('auth/register', ['username' => $username, 'errors' => $errors], 'Daftar Akun');
    }

    public function logout(): void
    {
        $this->requireCsrf();
        Auth::logout();
        $this->redirect('/login');
    }
}
