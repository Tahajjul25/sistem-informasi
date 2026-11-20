<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card auth-card-sm">
        <div class="auth-brand">
            <div class="brand-icon large"><i class="fa-solid fa-seedling"></i></div>
            <h2>Masuk</h2>
            <p>Sistem Informasi Jadwal Tanam dan Panen Aceh Utara</p>
        </div>

        <?php if (!empty($errors['login'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['login'][0]) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= htmlspecialchars($basePath ?? '') ?>/login">
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
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="<?= htmlspecialchars($basePath ?? '') ?>/register">Daftar</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
