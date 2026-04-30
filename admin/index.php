<?php 
    session_start();
    include '../koneksi.php';
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);
    $additionalCSS = [
        '../assets/dist/css/dashboard-silinen.css'
    ];

    $safeCount = function ($sql, $field = 'total') use ($koneksi) {
        try {
            $result = mysqli_query($koneksi, $sql);
            if (!$result) {
                return 0;
            }
            $data = mysqli_fetch_assoc($result);
            return isset($data[$field]) ? (int) $data[$field] : 0;
        } catch (Exception $e) {
            return 0;
        }
    };

    $totalJenisLinen = $safeCount("SELECT COUNT(*) AS total FROM linen WHERE status = 1");
    $totalStokLinen = $safeCount("SELECT COALESCE(SUM(jumlah_linen), 0) AS total FROM linen WHERE status = 1");
    $totalLinenDiRuangan = $safeCount("SELECT COALESCE(SUM(jumlah_linen), 0) AS total FROM linen_ruangan WHERE status = 1");

    $pengajuanBaru = $safeCount("SELECT COUNT(*) AS total FROM pengajuan WHERE status = 1");
    $pengajuanDikirim = $safeCount("SELECT COUNT(*) AS total FROM pengajuan WHERE status = 2");
    $pengajuanDiterima = $safeCount("SELECT COUNT(*) AS total FROM pengajuan WHERE status = 3");

    $linenKotor = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 1");
    $linenProses = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 2");
    $linenBersih = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 3");
    $linenTerdistribusi = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 4");

    $trendQuery = mysqli_query(
        $koneksi,
        "SELECT DATE(tanggal) AS tanggal_pengajuan, COUNT(*) AS total
         FROM pengajuan
         WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
         GROUP BY DATE(tanggal)"
    );

    $trendMap = [];
    if ($trendQuery) {
        while ($trendRow = mysqli_fetch_assoc($trendQuery)) {
            $trendMap[$trendRow['tanggal_pengajuan']] = (int) $trendRow['total'];
        }
    }

    $trendLabels = [];
    $trendData = [];
    for ($i = 6; $i >= 0; $i--) {
        $dateKey = date('Y-m-d', strtotime("-$i day"));
        $trendLabels[] = date('d M', strtotime($dateKey));
        $trendData[] = isset($trendMap[$dateKey]) ? $trendMap[$dateKey] : 0;
    }

    $maxTrend = max($trendData);
    if ($maxTrend < 1) {
        $maxTrend = 1;
    }

    $aktivitasQuery = mysqli_query(
        $koneksi,
        "SELECT p.id, p.tanggal, p.jumlah, p.status, p.keterangan, l.nama_linen, r.nama_ruangan
         FROM pengajuan p
         LEFT JOIN linen l ON p.id_linen = l.id
         LEFT JOIN ruangan r ON p.id_ruangan = r.id
         ORDER BY p.tanggal DESC
         LIMIT 5"
    );

    $totalPengajuan = $pengajuanBaru + $pengajuanDikirim + $pengajuanDiterima;
    $totalProsesLaundry = $linenKotor + $linenProses + $linenBersih + $linenTerdistribusi;

    $pengajuanPctBaru = $totalPengajuan > 0 ? round(($pengajuanBaru / $totalPengajuan) * 100) : 0;
    $pengajuanPctDikirim = $totalPengajuan > 0 ? round(($pengajuanDikirim / $totalPengajuan) * 100) : 0;
    $pengajuanPctDiterima = $totalPengajuan > 0 ? round(($pengajuanDiterima / $totalPengajuan) * 100) : 0;

    $linenPctKotor = $totalProsesLaundry > 0 ? round(($linenKotor / $totalProsesLaundry) * 100) : 0;
    $linenPctProses = $totalProsesLaundry > 0 ? round(($linenProses / $totalProsesLaundry) * 100) : 0;
    $linenPctBersih = $totalProsesLaundry > 0 ? round(($linenBersih / $totalProsesLaundry) * 100) : 0;
    $linenPctTerdistribusi = $totalProsesLaundry > 0 ? round(($linenTerdistribusi / $totalProsesLaundry) * 100) : 0;

    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Dashboard";
    $pageDesc = "Ringkasan Operasional Linen";
    $_SESSION['active_menu'] = 'dashboard';
    ob_start(); 
?>

<section class="content">
    <div class="silinen-dashboard">
        <div class="silinen-hero">
            <div>
                <span class="silinen-eyebrow">Dashboard SiLinen</span>
                <h2>Selamat datang, <?= htmlspecialchars($row['nama']) ?></h2>
                <p>Kelola siklus linen rumah sakit dari satu layar: stok, pengajuan, proses laundry, dan distribusi.</p>
            </div>
            <div class="silinen-date-card">
                <small>Update Hari Ini</small>
                <strong><?= date('d M Y') ?></strong>
                <span><?= date('H:i') ?> WITA</span>
            </div>
        </div>

        <div class="row silinen-stats-row">
            <div class="col-lg-3 col-sm-6">
                <div class="silinen-stat-card stat-teal">
                    <div class="stat-icon"><i class="fa fa-list-ul"></i></div>
                    <div>
                        <h3><?= number_format($totalJenisLinen) ?></h3>
                        <p>Jenis Linen Aktif</p>
                        <a href="data_linen.php">Lihat Data Linen</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="silinen-stat-card stat-orange">
                    <div class="stat-icon"><i class="fa fa-cubes"></i></div>
                    <div>
                        <h3><?= number_format($totalStokLinen) ?></h3>
                        <p>Total Stok Linen</p>
                        <a href="data_linen.php">Kelola Stok</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="silinen-stat-card stat-blue">
                    <div class="stat-icon"><i class="fa fa-hospital-o"></i></div>
                    <div>
                        <h3><?= number_format($totalLinenDiRuangan) ?></h3>
                        <p>Linen Di Ruangan</p>
                        <a href="linen_ruangan.php">Pantau Linen Ruangan</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="silinen-stat-card stat-green">
                    <div class="stat-icon"><i class="fa fa-paper-plane"></i></div>
                    <div>
                        <h3><?= number_format($totalPengajuan) ?></h3>
                        <p>Total Pengajuan</p>
                        <a href="data_pengajuan.php">Lihat Pengajuan</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="silinen-panel">
                    <div class="panel-head">
                        <h4>Status Pengajuan</h4>
                        <span><?= number_format($totalPengajuan) ?> item</span>
                    </div>
                    <div class="status-grid">
                        <div class="status-chip chip-warn">
                            <strong><?= number_format($pengajuanBaru) ?></strong>
                            <span>Menunggu</span>
                        </div>
                        <div class="status-chip chip-info">
                            <strong><?= number_format($pengajuanDikirim) ?></strong>
                            <span>Dikirim</span>
                        </div>
                        <div class="status-chip chip-ok">
                            <strong><?= number_format($pengajuanDiterima) ?></strong>
                            <span>Diterima</span>
                        </div>
                    </div>
                    <div class="silinen-progress-list">
                        <div>
                            <label>Menunggu (<?= $pengajuanPctBaru ?>%)</label>
                            <div class="silinen-progress"><span class="bar-warn" style="width: <?= $pengajuanPctBaru ?>%"></span></div>
                        </div>
                        <div>
                            <label>Dikirim (<?= $pengajuanPctDikirim ?>%)</label>
                            <div class="silinen-progress"><span class="bar-info" style="width: <?= $pengajuanPctDikirim ?>%"></span></div>
                        </div>
                        <div>
                            <label>Diterima (<?= $pengajuanPctDiterima ?>%)</label>
                            <div class="silinen-progress"><span class="bar-ok" style="width: <?= $pengajuanPctDiterima ?>%"></span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="silinen-panel">
                    <div class="panel-head">
                        <h4>Status Proses Laundry</h4>
                        <span><?= number_format($totalProsesLaundry) ?> item</span>
                    </div>
                    <div class="status-grid status-grid-4">
                        <div class="status-chip chip-red">
                            <strong><?= number_format($linenKotor) ?></strong>
                            <span>Kotor</span>
                        </div>
                        <div class="status-chip chip-yellow">
                            <strong><?= number_format($linenProses) ?></strong>
                            <span>Proses</span>
                        </div>
                        <div class="status-chip chip-ok">
                            <strong><?= number_format($linenBersih) ?></strong>
                            <span>Bersih</span>
                        </div>
                        <div class="status-chip chip-info">
                            <strong><?= number_format($linenTerdistribusi) ?></strong>
                            <span>Distribusi</span>
                        </div>
                    </div>
                    <div class="silinen-progress-list">
                        <div>
                            <label>Kotor (<?= $linenPctKotor ?>%)</label>
                            <div class="silinen-progress"><span class="bar-red" style="width: <?= $linenPctKotor ?>%"></span></div>
                        </div>
                        <div>
                            <label>Proses (<?= $linenPctProses ?>%)</label>
                            <div class="silinen-progress"><span class="bar-warn" style="width: <?= $linenPctProses ?>%"></span></div>
                        </div>
                        <div>
                            <label>Bersih (<?= $linenPctBersih ?>%)</label>
                            <div class="silinen-progress"><span class="bar-ok" style="width: <?= $linenPctBersih ?>%"></span></div>
                        </div>
                        <div>
                            <label>Distribusi (<?= $linenPctTerdistribusi ?>%)</label>
                            <div class="silinen-progress"><span class="bar-info" style="width: <?= $linenPctTerdistribusi ?>%"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="silinen-panel">
                    <div class="panel-head">
                        <h4>Trend Pengajuan 7 Hari</h4>
                        <span>Harian</span>
                    </div>
                    <div class="silinen-chart-bars">
                        <?php foreach ($trendData as $index => $value): ?>
                            <?php $height = round(($value / $maxTrend) * 120); ?>
                            <div class="chart-bar-item">
                                <div class="bar" style="height: <?= $height ?>px" title="<?= $trendLabels[$index] ?>: <?= $value ?>"></div>
                                <small><?= $trendLabels[$index] ?></small>
                                <span><?= $value ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="silinen-panel">
                    <div class="panel-head">
                        <h4>Aktivitas Terbaru Pengajuan</h4>
                        <a href="data_pengajuan.php">Lihat Semua</a>
                    </div>
                    <div class="silinen-activity-list">
                        <?php if ($aktivitasQuery && mysqli_num_rows($aktivitasQuery) > 0): ?>
                            <?php while ($aktivitas = mysqli_fetch_assoc($aktivitasQuery)): ?>
                                <?php
                                    $statusMap = [
                                        1 => ['label' => 'Menunggu', 'class' => 'tag-warn'],
                                        2 => ['label' => 'Dikirim', 'class' => 'tag-info'],
                                        3 => ['label' => 'Diterima', 'class' => 'tag-ok']
                                    ];
                                    $statusAktif = isset($statusMap[$aktivitas['status']])
                                        ? $statusMap[$aktivitas['status']]
                                        : ['label' => 'Unknown', 'class' => 'tag-default'];
                                ?>
                                <div class="activity-item">
                                    <div>
                                        <strong><?= htmlspecialchars($aktivitas['nama_linen']) ?></strong>
                                        <p><?= htmlspecialchars($aktivitas['nama_ruangan']) ?> • <?= (int) $aktivitas['jumlah'] ?> pcs</p>
                                        <small><?= date('d M Y H:i', strtotime($aktivitas['tanggal'])) ?></small>
                                    </div>
                                    <span class="status-tag <?= $statusAktif['class'] ?>"><?= $statusAktif['label'] ?></span>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="empty-state">Belum ada aktivitas pengajuan terbaru.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<?php
    $content = ob_get_clean();
    include __DIR__ . '/../layouts/header.php';
    echo $content;
    include __DIR__ . '/../layouts/footer.php';
?>