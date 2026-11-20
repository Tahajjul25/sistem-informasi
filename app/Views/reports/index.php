<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-section">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">Laporan</h4>
        <a href="<?= htmlspecialchars($basePath) ?>/reports/export?from=<?= urlencode($from) ?>&amp;to=<?= urlencode($to) ?>" class="btn btn-primary"><i class="fa-solid fa-file-csv"></i> Unduh CSV Jadwal Tanam</a>
    </div>

    <div class="card-box mb-4">
        <form method="GET" action="<?= htmlspecialchars($basePath) ?>/reports" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>
    </div>

    <div class="card-box mb-4">
        <div class="card-head"><h5>Ringkasan per Tanaman</h5></div>
        <div class="table-wrap">
            <table class="table table-dark table-hover align-middle">
                <thead><tr><th>Tanaman</th><th>Jumlah Jadwal Tanam</th><th>Total Luas</th></tr></thead>
                <tbody>
                    <?php foreach (($crops ?? []) as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name'] ?? '-') ?></td>
                            <td><?= (int) ($row['total_plantings'] ?? 0) ?></td>
                            <td><?= htmlspecialchars((string) ($row['total_area'] ?? 0)) ?> ha</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($crops)): ?><tr><td colspan="3" class="text-center">Belum ada data.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card-box">
                <div class="card-head"><h5>Jadwal Tanam</h5></div>
                <div class="table-wrap">
                    <table class="table table-dark table-hover align-middle">
                        <thead><tr><th>Tanggal</th><th>Tanaman</th><th>Luas</th></tr></thead>
                        <tbody>
                            <?php foreach (($plantings ?? []) as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['planting_date']))) ?></td>
                                    <td><?= htmlspecialchars($row['crop_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars((string) ($row['land_area'] ?? 0)) ?> ha</td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($plantings)): ?><tr><td colspan="3" class="text-center">Tidak ada data pada rentang ini.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-box">
                <div class="card-head"><h5>Jadwal Panen</h5></div>
                <div class="table-wrap">
                    <table class="table table-dark table-hover align-middle">
                        <thead><tr><th>Perkiraan</th><th>Tanaman</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach (($harvests ?? []) as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['estimated_harvest_date']))) ?></td>
                                    <td><?= htmlspecialchars($row['crop_name'] ?? '-') ?></td>
                                    <td><span class="status-badge"><?= htmlspecialchars($row['status'] ?? '-') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($harvests)): ?><tr><td colspan="3" class="text-center">Tidak ada data pada rentang ini.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-box">
                <div class="card-head"><h5>Curah Hujan</h5></div>
                <div class="table-wrap">
                    <table class="table table-dark table-hover align-middle">
                        <thead><tr><th>Tanggal</th><th>Jumlah</th></tr></thead>
                        <tbody>
                            <?php foreach (($rainfall ?? []) as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($row['measurement_date']))) ?></td>
                                    <td><?= htmlspecialchars((string) ($row['amount_mm'] ?? 0)) ?> mm</td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($rainfall)): ?><tr><td colspan="2" class="text-center">Tidak ada data pada rentang ini.</td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
