<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-section">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Manajemen Tanaman</h4>
        <a href="<?= htmlspecialchars($basePath) ?>/crops/form" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Tanaman</a>
    </div>

    <div class="card-box mb-4">
        <form method="GET" action="<?= htmlspecialchars($basePath) ?>/crops" class="row g-2 align-items-end">
            <div class="col-md-10">
                <label class="form-label">Pencarian</label>
                <input type="text" name="search" class="form-control" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Cari tanaman...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>
        </form>
    </div>

    <div class="card-box">
        <div class="table-wrap">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Umur</th>
                        <th>Kebutuhan Air</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($crops ?? []) as $crop): ?>
                        <tr>
                            <td><?= htmlspecialchars($crop['name'] ?? '-') ?></td>
                            <td><?= (int) ($crop['age_days'] ?? 0) ?> hari</td>
                            <td><?= htmlspecialchars((string) ($crop['water_requirement'] ?? 0)) ?> mm</td>
                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($crop['created_at'] ?? date('Y-m-d')))) ?></td>
                            <td>
                                <div class="action-group">
                                    <a href="<?= htmlspecialchars($basePath) ?>/crops/form?id=<?= (int) ($crop['id'] ?? 0) ?>" class="btn btn-sm btn-outline-light">Edit</a>
                                    <form method="POST" action="<?= htmlspecialchars($basePath) ?>/crops/delete" onsubmit="return confirm('Hapus tanaman ini?')" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                                        <input type="hidden" name="id" value="<?= (int) ($crop['id'] ?? 0) ?>">
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
