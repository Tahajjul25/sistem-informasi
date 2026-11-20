<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-section">
    <div class="card-box form-card">
        <div class="card-head">
            <h5><?= !empty($schedule['id']) ? 'Edit Jadwal Tanam' : 'Tambah Jadwal Tanam' ?></h5>
        </div>

        <form method="POST" action="<?= htmlspecialchars($basePath) ?><?= !empty($schedule['id']) ? '/planting-schedules/edit' : '/planting-schedules/create' ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <?php if (!empty($schedule['id'])): ?>
                <input type="hidden" name="id" value="<?= (int) $schedule['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Jenis Tanaman</label>
                <select name="crop_id" class="form-control" required>
                    <option value="">-- Pilih tanaman --</option>
                    <?php foreach (($crops ?? []) as $crop): ?>
                        <option value="<?= (int) ($crop['id'] ?? 0) ?>" <?= (!empty($schedule['crop_id']) && (int) $schedule['crop_id'] === (int) ($crop['id'] ?? 0)) ? 'selected' : '' ?>><?= htmlspecialchars($crop['name'] ?? '-') ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['crop_id'])): ?><div class="text-error"><?= htmlspecialchars($errors['crop_id'][0]) ?></div><?php endif; ?>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Luas Lahan (ha)</label>
                    <input type="number" step="0.01" min="0.01" name="land_area" class="form-control" value="<?= htmlspecialchars((string) ($schedule['land_area'] ?? '')) ?>" required>
                    <?php if (!empty($errors['land_area'])): ?><div class="text-error"><?= htmlspecialchars($errors['land_area'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Tanam</label>
                    <input type="date" name="planting_date" class="form-control" value="<?= htmlspecialchars($schedule['planting_date'] ?? '') ?>" required>
                    <?php if (!empty($errors['planting_date'])): ?><div class="text-error"><?= htmlspecialchars($errors['planting_date'][0]) ?></div><?php endif; ?>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= htmlspecialchars($basePath) ?>/planting-schedules" class="btn btn-outline-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
