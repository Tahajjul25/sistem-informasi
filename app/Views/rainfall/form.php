<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-section">
    <div class="card-box form-card">
        <div class="card-head">
            <h5><?= !empty($item['id']) ? 'Edit Curah Hujan' : 'Tambah Curah Hujan' ?></h5>
        </div>

        <form method="POST" action="<?= htmlspecialchars($basePath) ?><?= !empty($item['id']) ? '/rainfall/edit' : '/rainfall/create' ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <?php if (!empty($item['id'])): ?>
                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tanggal Pengukuran</label>
                    <input type="date" name="measurement_date" class="form-control" value="<?= htmlspecialchars($item['measurement_date'] ?? '') ?>" required>
                    <?php if (!empty($errors['measurement_date'])): ?><div class="text-error"><?= htmlspecialchars($errors['measurement_date'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah Curah Hujan (mm)</label>
                    <input type="number" step="0.01" min="0" name="amount_mm" class="form-control" value="<?= htmlspecialchars((string) ($item['amount_mm'] ?? '')) ?>" required>
                    <?php if (!empty($errors['amount_mm'])): ?><div class="text-error"><?= htmlspecialchars($errors['amount_mm'][0]) ?></div><?php endif; ?>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= htmlspecialchars($basePath) ?>/rainfall" class="btn btn-outline-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
