<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card auth-card-sm">
        <div class="auth-brand">
            <div class="brand-icon large"><i class="fa-solid fa-user-plus"></i></div>
            <h2>Daftar</h2>
            <p>Buat akun untuk mengelola jadwal tanam dan panen.</p>
        </div>

        <form method="POST" action="<?= htmlspecialchars($basePath ?? '') ?>/register">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username ?? '') ?>" required>
                <?php if (!empty($errors['username'])): ?><div class="text-error"><?= htmlspecialchars($errors['username'][0]) ?></div><?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                <?php if (!empty($errors['password'])): ?><div class="text-error"><?= htmlspecialchars($errors['password'][0]) ?></div><?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
                <?php if (!empty($errors['confirm_password'])): ?><div class="text-error"><?= htmlspecialchars($errors['confirm_password'][0]) ?></div><?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="<?= htmlspecialchars($basePath ?? '') ?>/login">Masuk</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
