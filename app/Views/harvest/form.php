<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-section">
    <div class="card-box form-card">
        <div class="card-head">
            <h5><?= !empty($item['id']) ? 'Edit Jadwal Panen' : 'Tambah Jadwal Panen' ?></h5>
        </div>

        <form method="POST" action="<?= htmlspecialchars($basePath) ?><?= !empty($item['id']) ? '/harvest-schedules/edit' : '/harvest-schedules/create' ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
            <?php if (!empty($item['id'])): ?>
                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Jadwal Tanam</label>
                <select name="planting_schedule_id" id="planting_schedule_id" class="form-control" required>
                    <option value="">-- Pilih jadwal tanam --</option>
                    <?php foreach (($plantings ?? []) as $p): ?>
                        <option value="<?= (int) ($p['id'] ?? 0) ?>" data-estimate="<?= htmlspecialchars(date('Y-m-d', strtotime(($p['planting_date'] ?? date('Y-m-d')) . ' +' . (int) ($p['crop_age_days'] ?? 0) . ' days'))) ?>" <?= (!empty($item['planting_schedule_id']) && (int) $item['planting_schedule_id'] === (int) ($p['id'] ?? 0)) ? 'selected' : '' ?>><?= htmlspecialchars($p['crop_name'] ?? '-') ?> - <?= htmlspecialchars(date('d-m-Y', strtotime($p['planting_date'] ?? date('Y-m-d')))) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['planting_schedule_id'])): ?><div class="text-error"><?= htmlspecialchars($errors['planting_schedule_id'][0]) ?></div><?php endif; ?>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Perkiraan Panen</label>
                    <input type="date" name="estimated_harvest_date" id="estimated_harvest_date" class="form-control" value="<?= htmlspecialchars($item['estimated_harvest_date'] ?? '') ?>" required>
                    <?php if (!empty($errors['estimated_harvest_date'])): ?><div class="text-error"><?= htmlspecialchars($errors['estimated_harvest_date'][0]) ?></div><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Aktual Panen (opsional)</label>
                    <input type="date" name="actual_harvest_date" class="form-control" value="<?= htmlspecialchars($item['actual_harvest_date'] ?? '') ?>">
                    <?php if (!empty($errors['actual_harvest_date'])): ?><div class="text-error"><?= htmlspecialchars($errors['actual_harvest_date'][0]) ?></div><?php endif; ?>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">Catatan</label>
                <textarea name="notes" rows="3" class="form-control"><?= htmlspecialchars($item['notes'] ?? '') ?></textarea>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= htmlspecialchars($basePath) ?>/harvest-schedules" class="btn btn-outline-light">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
// Saat memilih jadwal tanam, isi otomatis perkiraan panen (tanggal tanam + umur tanaman) bila masih kosong.
document.getElementById('planting_schedule_id').addEventListener('change', function () {
    var estimate = this.selectedOptions[0] ? this.selectedOptions[0].getAttribute('data-estimate') : '';
    var field = document.getElementById('estimated_harvest_date');
    if (estimate && !field.value) {
        field.value = estimate;
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
