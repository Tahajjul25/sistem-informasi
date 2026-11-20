<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Sistem Informasi Jadwal Panen') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars($basePath ?? '') ?>/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php if (!empty($currentUser)): ?>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand-box">
                <div class="brand-icon"><i class="fa-solid fa-seedling"></i></div>
                <div>
                    <h5>Panen Aceh</h5>
                    <small>Jadwal Tanam</small>
                </div>
            </div>
            <nav class="nav-menu">
                <?php
                $__path = \App\Helpers\Url::routePath();
                $__nav = [
                    ['/dashboard', 'fa-gauge-high', 'Dashboard'],
                    ['/crops', 'fa-plant-wilt', 'Tanaman'],
                    ['/planting-schedules', 'fa-calendar-days', 'Jadwal Tanam'],
                    ['/harvest-schedules', 'fa-clipboard-check', 'Jadwal Panen'],
                    ['/rainfall', 'fa-cloud-rain', 'Curah Hujan'],
                    ['/reports', 'fa-chart-column', 'Laporan'],
                ];
                foreach ($__nav as [$__href, $__icon, $__label]):
                    $__active = $__path === $__href || str_starts_with($__path, $__href . '/') || ($__href === '/dashboard' && $__path === '/');
                ?>
                    <a href="<?= htmlspecialchars($basePath) ?><?= $__href ?>" class="nav-link<?= $__active ? ' active' : '' ?>"><i class="fa-solid <?= $__icon ?>"></i> <?= $__label ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="<?= htmlspecialchars($basePath) ?>/logout">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                    <button class="btn btn-logout" type="submit"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                </form>
            </div>
        </aside>
        <main class="main-panel">
            <header class="topbar">
                <div>
                    <h3><?= htmlspecialchars($title ?? 'Dashboard') ?></h3>
                </div>
                <div class="user-box">
                    <span class="user-badge"><i class="fa-solid fa-user"></i></span>
                    <span><?= htmlspecialchars($currentUser ?? 'User') ?></span>
                </div>
            </header>
<?php else: ?>
    <div class="auth-body">
<?php endif; ?>
