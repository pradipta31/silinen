<?php 
    session_start();
    include '../koneksi.php';
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);
    $additionalCSS = [
        '../assets/dist/css/dashboard-silinen.css'
    ];

    $idUser = (int) $row['id'];

    $safeCount = function ($sql, $field = 'total') use ($koneksi) {
        $result = mysqli_query($koneksi, $sql);
        if (!$result) {
            return 0;
        }

        $data = mysqli_fetch_assoc($result);
        return isset($data[$field]) ? (int) $data[$field] : 0;
    };

    $ruanganAktif = $safeCount("SELECT COUNT(*) AS total FROM ruangan WHERE id_user = $idUser AND status = 1");

    // Query untuk mendapatkan data ruangan
    $queryRuangan = mysqli_query($koneksi, "SELECT * FROM ruangan WHERE id_user = $idUser AND status = 1 LIMIT 1");
    $rRuangan = mysqli_fetch_assoc($queryRuangan);

    $linenRuangan = $safeCount(
        "SELECT COALESCE(SUM(lr.jumlah_linen), 0) AS total
         FROM linen_ruangan lr
         INNER JOIN ruangan r ON lr.id_ruangan = r.id
         WHERE r.id_user = $idUser AND lr.status = 1"
    );

    $pengajuanBaru = $safeCount(
        "SELECT COUNT(*) AS total
         FROM pengajuan p
         INNER JOIN ruangan r ON p.id_ruangan = r.id
         WHERE r.id_user = $idUser AND p.status = 1"
    );
    $pengajuanDikirim = $safeCount(
        "SELECT COUNT(*) AS total
         FROM pengajuan p
         INNER JOIN ruangan r ON p.id_ruangan = r.id
         WHERE r.id_user = $idUser AND p.status = 2"
    );
    $pengajuanDiterima = $safeCount(
        "SELECT COUNT(*) AS total
         FROM pengajuan p
         INNER JOIN ruangan r ON p.id_ruangan = r.id
         WHERE r.id_user = $idUser AND p.status = 3"
    );

    $linenKotor = $safeCount(
        "SELECT COUNT(*) AS total
         FROM pencucian p
         INNER JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
         INNER JOIN ruangan r ON lr.id_ruangan = r.id
         WHERE r.id_user = $idUser AND p.status = 1"
    );
    $linenProses = $safeCount(
        "SELECT COUNT(*) AS total
         FROM pencucian p
         INNER JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
         INNER JOIN ruangan r ON lr.id_ruangan = r.id
         WHERE r.id_user = $idUser AND p.status = 2"
    );
    $linenSelesai = $safeCount(
        "SELECT COUNT(*) AS total
         FROM pencucian p
         INNER JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
         INNER JOIN ruangan r ON lr.id_ruangan = r.id
         WHERE r.id_user = $idUser AND p.status = 3"
    );

    $trendQuery = mysqli_query(
        $koneksi,
        "SELECT DATE(p.tanggal) AS tanggal_pengajuan, COUNT(*) AS total
         FROM pengajuan p
         INNER JOIN ruangan r ON p.id_ruangan = r.id
         WHERE r.id_user = $idUser
           AND p.tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
         GROUP BY DATE(p.tanggal)"
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
         WHERE r.id_user = $idUser
         ORDER BY p.tanggal DESC
         LIMIT 5"
    );

    $totalPengajuan = $pengajuanBaru + $pengajuanDikirim + $pengajuanDiterima;
    $totalLaundry = $linenKotor + $linenProses + $linenSelesai;

    $pengajuanPctBaru = $totalPengajuan > 0 ? round(($pengajuanBaru / $totalPengajuan) * 100) : 0;
    $pengajuanPctDikirim = $totalPengajuan > 0 ? round(($pengajuanDikirim / $totalPengajuan) * 100) : 0;
    $pengajuanPctDiterima = $totalPengajuan > 0 ? round(($pengajuanDiterima / $totalPengajuan) * 100) : 0;

    $linenPctKotor = $totalLaundry > 0 ? round(($linenKotor / $totalLaundry) * 100) : 0;
    $linenPctProses = $totalLaundry > 0 ? round(($linenProses / $totalLaundry) * 100) : 0;
    $linenPctSelesai = $totalLaundry > 0 ? round(($linenSelesai / $totalLaundry) * 100) : 0;

    // Cek apakah user memiliki ruangan
    $belumPunyaRuangan = ($ruanganAktif == 0);

    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Dashboard";
    $pageDesc = "Ringkasan Ruangan";
    $_SESSION['active_menu'] = 'dashboard';
    ob_start(); 
    
?>

<section class="content">
    <div class="silinen-dashboard">
        <div class="silinen-hero room-hero">
            <?php if ($belumPunyaRuangan): ?>
                <div class="alert alert-warning text-center" style="margin-bottom: 20px;">
                    <h4>Belum Memiliki Ruangan</h4>
                    <p>Silahkan hubungi admin terkait untuk mengatur ruangan Anda.</p>
                </div>
            <?php endif; ?>
            <div>
                <span class="silinen-eyebrow">Dashboard Ruangan</span>
                <h2>Halo, <?= htmlspecialchars($row['nama']) ?><?php if ($rRuangan): ?> - Ruang (<?= htmlspecialchars($rRuangan['nama_ruangan']) ?>)<?php endif; ?></h2>
                <p>Ringkasan pengajuan linen untuk ruangan Anda, termasuk progres pengiriman dan status laundry.</p>
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
                    <div class="stat-icon"><i class="fa fa-hospital-o"></i></div>
                    <div>
                        <h3><?= number_format($ruanganAktif) ?></h3>
                        <p>Ruangan Aktif</p>
                        <a href="linen_ruangan.php">Lihat Ruangan</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="silinen-stat-card stat-orange">
                    <div class="stat-icon"><i class="fa fa-bed"></i></div>
                    <div>
                        <h3><?= number_format($linenRuangan) ?></h3>
                        <p>Total Linen Ruangan</p>
                        <a href="linen_ruangan.php">Pantau Linen</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="silinen-stat-card stat-blue">
                    <div class="stat-icon"><i class="fa fa-paper-plane"></i></div>
                    <div>
                        <h3><?= number_format($totalPengajuan) ?></h3>
                        <p>Total Pengajuan</p>
                        <a href="data_pengajuan.php">Kelola Pengajuan</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="silinen-stat-card stat-green">
                    <div class="stat-icon"><i class="fa fa-refresh"></i></div>
                    <div>
                        <h3><?= number_format($totalLaundry) ?></h3>
                        <p>Proses Laundry</p>
                        <a href="data_pencucian.php">Lihat Pencucian</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="silinen-panel">
                    <div class="panel-head">
                        <h4>Status Pengajuan Ruangan</h4>
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
                        <h4>Status Laundry Ruangan</h4>
                        <span><?= number_format($totalLaundry) ?> item</span>
                    </div>
                    <div class="status-grid">
                        <div class="status-chip chip-red">
                            <strong><?= number_format($linenKotor) ?></strong>
                            <span>Kotor</span>
                        </div>
                        <div class="status-chip chip-yellow">
                            <strong><?= number_format($linenProses) ?></strong>
                            <span>Proses</span>
                        </div>
                        <div class="status-chip chip-ok">
                            <strong><?= number_format($linenSelesai) ?></strong>
                            <span>Selesai</span>
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
                            <label>Selesai (<?= $linenPctSelesai ?>%)</label>
                            <div class="silinen-progress"><span class="bar-ok" style="width: <?= $linenPctSelesai ?>%"></span></div>
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
                        <h4>Aktivitas Terbaru</h4>
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

</section><!-- /.content -->

<?php
    $content = ob_get_clean();
    include __DIR__ . '/../layouts/header.php';
    echo $content;
    include __DIR__ . '/../layouts/footer.php';
?>