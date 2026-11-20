<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-section">
    <div class="card-box form-card">
        <div class="card-head">
            <h5><?= !empty($crop['id']) ? 'Edit Tanaman' : 'Tambah Tanaman' ?></h5>
        </div>

        <form method="POST" action="<?= htmlspecialchars($basePath) ?><?= !empty($crop['id']) ? '/crops/edit' : '/crops/create' ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <?php if (!empty($crop['id'])): ?>
                <input type="hidden" name="id" value="<?= (int) $crop['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Nama Tanaman</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($crop['name'] ?? '') ?>" required>
                <?php if (!empty($errors['name'])): ?><div class="text-error"><?= htmlspecialchars($errors['name'][0]) ?></div><?php endif; ?>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Umur Tanaman (hari)</label>
                    <input type="number" min="1" name="age_days" class="form-control" value="<?= htmlspecialchars((string) ($crop['age_days'] ?? '')) ?>" required>
                    <?php if (!empty($errors['age_days'])): ?><div class="text-error"><?= htmlspecialchars($errors['age_days'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kebutuhan Air (mm)</label>
                    <input type="number" step="0.01" min="0" name="water_requirement" class="form-control" value="<?= htmlspecialchars((string) ($crop['water_requirement'] ?? '')) ?>" required>
                    <?php if (!empty($errors['water_requirement'])): ?><div class="text-error"><?= htmlspecialchars($errors['water_requirement'][0]) ?></div><?php endif; ?>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= htmlspecialchars($basePath) ?>/crops" class="btn btn-outline-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
