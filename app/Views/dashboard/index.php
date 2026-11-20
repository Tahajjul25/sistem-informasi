<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="page-section">
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card info">
                <div class="stat-icon"><i class="fa-solid fa-seedling"></i></div>
                <div>
                    <small>Total Tanaman</small>
                    <h3><?= (int) ($totalCrops ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card primary">
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <div>
                    <small>Jadwal Tanam</small>
                    <h3><?= (int) ($totalPlantings ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card success">
                <div class="stat-icon"><i class="fa-solid fa-clipboard-check"></i></div>
                <div>
                    <small>Jadwal Panen</small>
                    <h3><?= (int) ($totalHarvests ?? 0) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warning">
                <div class="stat-icon"><i class="fa-solid fa-cloud-rain"></i></div>
                <div>
                    <small>Data Curah Hujan</small>
                    <h3><?= (int) ($totalRainfall ?? 0) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card-box">
                <div class="card-head">
                    <h5>Grafik Curah Hujan</h5>
                </div>
                <canvas id="rainfallChart"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-box">
                <div class="card-head">
                    <h5>Ringkasan Pertanian</h5>
                </div>
                <ul class="summary-list">
                    <li><span>Tanaman aktif</span><strong><?= (int) ($totalCrops ?? 0) ?></strong></li>
                    <li><span>Jadwal aktif</span><strong><?= (int) ($totalPlantings ?? 0) ?></strong></li>
                    <li><span>Panen dekat</span><strong><?= count($upcomingHarvests ?? []) ?></strong></li>
                    <li><span>Curah hujan data</span><strong><?= count($rainfallValues ?? []) ?></strong></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card-box">
                <div class="card-head">
                    <h5>Jadwal Tanam Terbaru</h5>
                </div>
                <div class="table-wrap">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr><th>Tanaman</th><th>Luas</th><th>Tanggal</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach (($latestPlantings ?? []) as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['crop_name'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars((string) ($item['land_area'] ?? 0)) ?> ha</td>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($item['planting_date'] ?? date('Y-m-d')))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-box">
                <div class="card-head">
                    <h5>Jadwal Panen Terdekat</h5>
                </div>
                <div class="table-wrap">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr><th>Tanaman</th><th>Status</th><th>Perkiraan</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach (($upcomingHarvests ?? []) as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['crop_name'] ?? '-') ?></td>
                                    <td><span class="status-badge"><?= htmlspecialchars($item['status'] ?? 'Belum waktunya panen') ?></span></td>
                                    <td><?= htmlspecialchars(date('d-m-Y', strtotime($item['estimated_harvest_date'] ?? date('Y-m-d')))) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const rainfallChart = new Chart(document.getElementById('rainfallChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($rainfallLabels ?? []) ?>,
        datasets: [{
            label: 'Curah Hujan (mm)',
            data: <?= json_encode($rainfallValues ?? []) ?>,
            borderColor: '#5ec2ff',
            backgroundColor: 'rgba(94,194,255,0.2)',
            fill: true,
            tension: 0.35,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#dfeefe' } } },
        scales: {
            x: { ticks: { color: '#dfeefe' }, grid: { color: 'rgba(255,255,255,0.08)' } },
            y: { ticks: { color: '#dfeefe' }, grid: { color: 'rgba(255,255,255,0.08)' } }
        }
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
