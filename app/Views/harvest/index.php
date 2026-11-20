<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-section">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Jadwal Panen</h4>
        <a href="<?= htmlspecialchars($basePath) ?>/harvest-schedules/form" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Panen</a>
    </div>

    <div class="card-box mb-4">
        <form method="GET" action="<?= htmlspecialchars($basePath) ?>/harvest-schedules" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="date_from" class="form-control" value="<?= htmlspecialchars($date_from ?? '') ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="date_to" class="form-control" value="<?= htmlspecialchars($date_to ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="card-box">
        <div class="table-wrap">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr><th>Tanaman</th><th>Status</th><th>Perkiraan</th><th>Aktual</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php foreach (($items ?? []) as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['crop_name'] ?? '-') ?></td>
                            <td><span class="status-badge"><?= htmlspecialchars($item['status'] ?? '-') ?></span></td>
                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($item['estimated_harvest_date'] ?? date('Y-m-d')))) ?></td>
                            <td><?= !empty($item['actual_harvest_date']) ? htmlspecialchars(date('d-m-Y', strtotime($item['actual_harvest_date']))) : '-' ?></td>
                            <td>
                                <div class="action-group">
                                    <a href="<?= htmlspecialchars($basePath) ?>/harvest-schedules/form?id=<?= (int) ($item['id'] ?? 0) ?>" class="btn btn-sm btn-outline-light">Edit</a>
                                    <form method="POST" action="<?= htmlspecialchars($basePath) ?>/harvest-schedules/delete" onsubmit="return confirm('Hapus jadwal panen ini?')" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                                        <input type="hidden" name="id" value="<?= (int) ($item['id'] ?? 0) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
