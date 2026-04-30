<?php
    session_start();
    include '../koneksi.php';
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);

    $safeCount = function ($sql, $field = 'total') use ($koneksi) {
        $result = mysqli_query($koneksi, $sql);
        if (!$result) {
            return 0;
        }

        $data = mysqli_fetch_assoc($result);
        return isset($data[$field]) ? (int) $data[$field] : 0;
    };

    $totalPengajuan = $safeCount("SELECT COUNT(*) AS total FROM pengajuan");
    $pencucianMasuk = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 1");
    $sedangDicuci = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 2");
    $selesaiDicuci = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 3");

    $totalProses = $pencucianMasuk + $sedangDicuci + $selesaiDicuci;
    $pctMasuk = $totalProses > 0 ? round(($pencucianMasuk / $totalProses) * 100) : 0;
    $pctCuci = $totalProses > 0 ? round(($sedangDicuci / $totalProses) * 100) : 0;
    $pctSelesai = $totalProses > 0 ? round(($selesaiDicuci / $totalProses) * 100) : 0;

    $aktivitasQuery = mysqli_query(
        $koneksi,
        "SELECT p.tanggal, p.jumlah, p.status, l.nama_linen, r.nama_ruangan
         FROM pencucian p
         LEFT JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
         LEFT JOIN linen l ON lr.id_linen = l.id
         LEFT JOIN ruangan r ON lr.id_ruangan = r.id
         ORDER BY p.tanggal DESC
         LIMIT 5"
    );

    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Dashboard";
    $pageDesc = "Ringkasan Petugas Laundry";
    $_SESSION['active_menu'] = 'dashboard';
    ob_start();
?>

<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= number_format($pencucianMasuk) ?></h3>
                    <p>Pencucian Masuk</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tint"></i>
                </div>
                <a href="data_pencucian.php" class="small-box-footer">Lihat Data <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?= number_format($sedangDicuci) ?></h3>
                    <p>Sedang Dicuci</p>
                </div>
                <div class="icon">
                    <i class="fa fa-refresh"></i>
                </div>
                <a href="data_pencucian.php" class="small-box-footer">Lihat Data <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= number_format($selesaiDicuci) ?></h3>
                    <p>Selesai Dicuci</p>
                </div>
                <div class="icon">
                    <i class="fa fa-check"></i>
                </div>
                <a href="data_pencucian.php" class="small-box-footer">Lihat Data <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= number_format($totalPengajuan) ?></h3>
                    <p>Total Pengajuan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-paper-plane"></i>
                </div>
                <a href="data_pengajuan.php" class="small-box-footer">Lihat Pengajuan <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart"></i> Progress Pencucian</h3>
                </div>
                <div class="box-body">
                    <div class="progress-group">
                        <span class="progress-text">Pencucian Masuk</span>
                        <span class="progress-number"><b><?= $pencucianMasuk ?></b>/<?= $totalProses ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-red" style="width: <?= $pctMasuk ?>%"></div>
                        </div>
                    </div>
                    <div class="progress-group">
                        <span class="progress-text">Sedang Dicuci</span>
                        <span class="progress-number"><b><?= $sedangDicuci ?></b>/<?= $totalProses ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-primary" style="width: <?= $pctCuci ?>%"></div>
                        </div>
                    </div>
                    <div class="progress-group" style="margin-bottom: 0;">
                        <span class="progress-text">Selesai Dicuci</span>
                        <span class="progress-number"><b><?= $selesaiDicuci ?></b>/<?= $totalProses ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-green" style="width: <?= $pctSelesai ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-clock-o"></i> Aktivitas Terbaru</h3>
                </div>
                <div class="box-body" style="max-height: 260px; overflow: auto;">
                    <?php if ($aktivitasQuery && mysqli_num_rows($aktivitasQuery) > 0): ?>
                        <ul class="products-list product-list-in-box">
                            <?php while ($aktivitas = mysqli_fetch_assoc($aktivitasQuery)): ?>
                                <?php
                                    $statusLabel = 'Unknown';
                                    $statusClass = 'label-default';

                                    if ((int) $aktivitas['status'] === 1) {
                                        $statusLabel = 'Pengambilan';
                                        $statusClass = 'label-warning';
                                    } elseif ((int) $aktivitas['status'] === 2) {
                                        $statusLabel = 'Pencucian';
                                        $statusClass = 'label-primary';
                                    } elseif ((int) $aktivitas['status'] === 3) {
                                        $statusLabel = 'Selesai';
                                        $statusClass = 'label-success';
                                    }
                                ?>
                                <li class="item">
                                    <div class="product-info" style="margin-left: 0;">
                                        <span class="product-title">
                                            <?= htmlspecialchars($aktivitas['nama_linen']) ?>
                                            <span class="label <?= $statusClass ?> pull-right"><?= $statusLabel ?></span>
                                        </span>
                                        <span class="product-description">
                                            <?= htmlspecialchars($aktivitas['nama_ruangan']) ?> • <?= (int) $aktivitas['jumlah'] ?> pcs • <?= date('d M Y H:i', strtotime($aktivitas['tanggal'])) ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted" style="margin: 0;">Belum ada aktivitas pencucian terbaru.</p>
                    <?php endif; ?>
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