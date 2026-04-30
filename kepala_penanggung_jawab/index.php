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

    $totalRuangan = $safeCount("SELECT COUNT(*) AS total FROM ruangan WHERE status = 1");
    $totalJenisLinen = $safeCount("SELECT COUNT(*) AS total FROM linen WHERE status = 1");
    $totalStokLinen = $safeCount("SELECT COALESCE(SUM(jumlah_linen), 0) AS total FROM linen WHERE status = 1");
    $totalLinenRuangan = $safeCount("SELECT COALESCE(SUM(jumlah_linen), 0) AS total FROM linen_ruangan WHERE status = 1");

    $pengajuanBaru = $safeCount("SELECT COUNT(*) AS total FROM pengajuan WHERE status = 1");
    $pengajuanProses = $safeCount("SELECT COUNT(*) AS total FROM pengajuan WHERE status = 2");
    $pengajuanSelesai = $safeCount("SELECT COUNT(*) AS total FROM pengajuan WHERE status = 3");

    $cuciPengambilan = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 1");
    $cuciProses = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 2");
    $cuciSelesai = $safeCount("SELECT COUNT(*) AS total FROM pencucian WHERE status = 3");

    $totalPengajuan = $pengajuanBaru + $pengajuanProses + $pengajuanSelesai;
    $totalCuci = $cuciPengambilan + $cuciProses + $cuciSelesai;

    $pctPengajuanBaru = $totalPengajuan > 0 ? round(($pengajuanBaru / $totalPengajuan) * 100) : 0;
    $pctPengajuanProses = $totalPengajuan > 0 ? round(($pengajuanProses / $totalPengajuan) * 100) : 0;
    $pctPengajuanSelesai = $totalPengajuan > 0 ? round(($pengajuanSelesai / $totalPengajuan) * 100) : 0;

    $pctCuciPengambilan = $totalCuci > 0 ? round(($cuciPengambilan / $totalCuci) * 100) : 0;
    $pctCuciProses = $totalCuci > 0 ? round(($cuciProses / $totalCuci) * 100) : 0;
    $pctCuciSelesai = $totalCuci > 0 ? round(($cuciSelesai / $totalCuci) * 100) : 0;

    $aktivitasQuery = mysqli_query(
        $koneksi,
        "SELECT p.tanggal, p.jumlah, p.status, l.nama_linen, r.nama_ruangan
         FROM pengajuan p
         LEFT JOIN linen l ON p.id_linen = l.id
         LEFT JOIN ruangan r ON p.id_ruangan = r.id
         ORDER BY p.tanggal DESC
         LIMIT 6"
    );

    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Dashboard";
    $pageDesc = "Ringkasan Kepala Penanggung Jawab";
    $_SESSION['active_menu'] = 'dashboard';
    ob_start();
?>

<section class="content">
    <div class="alert alert-info" role="alert" style="border-radius: 10px;">
        <strong>Selamat datang, <?= htmlspecialchars($row['nama']) ?>.</strong>
        Dashboard ini menampilkan ringkasan operasional linen secara keseluruhan.
    </div>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?= number_format($totalRuangan) ?></h3>
                    <p>Ruangan Aktif</p>
                </div>
                <div class="icon">
                    <i class="fa fa-building"></i>
                </div>
                <a href="../admin/data_ruangan.php" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= number_format($totalJenisLinen) ?></h3>
                    <p>Jenis Linen Aktif</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bed"></i>
                </div>
                <a href="../admin/data_linen.php" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= number_format($totalStokLinen) ?></h3>
                    <p>Total Stok Linen</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <a href="../admin/data_linen.php" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= number_format($totalLinenRuangan) ?></h3>
                    <p>Linen di Ruangan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-hospital-o"></i>
                </div>
                <a href="../admin/linen_ruangan.php" class="small-box-footer">Lihat detail <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-paper-plane"></i> Status Pengajuan</h3>
                    <span class="pull-right text-muted">Total: <?= $totalPengajuan ?></span>
                </div>
                <div class="box-body">
                    <div class="progress-group">
                        <span class="progress-text">Baru</span>
                        <span class="progress-number"><b><?= $pengajuanBaru ?></b>/<?= $totalPengajuan ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-warning" style="width: <?= $pctPengajuanBaru ?>%"></div>
                        </div>
                    </div>
                    <div class="progress-group">
                        <span class="progress-text">Proses Pengiriman</span>
                        <span class="progress-number"><b><?= $pengajuanProses ?></b>/<?= $totalPengajuan ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-primary" style="width: <?= $pctPengajuanProses ?>%"></div>
                        </div>
                    </div>
                    <div class="progress-group" style="margin-bottom: 0;">
                        <span class="progress-text">Selesai / Diterima</span>
                        <span class="progress-number"><b><?= $pengajuanSelesai ?></b>/<?= $totalPengajuan ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-success" style="width: <?= $pctPengajuanSelesai ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right">
                    <a href="../admin/data_pengajuan.php" class="btn btn-sm btn-default">Lihat Data Pengajuan</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-tint"></i> Status Pencucian</h3>
                    <span class="pull-right text-muted">Total: <?= $totalCuci ?></span>
                </div>
                <div class="box-body">
                    <div class="progress-group">
                        <span class="progress-text">Pengambilan</span>
                        <span class="progress-number"><b><?= $cuciPengambilan ?></b>/<?= $totalCuci ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-warning" style="width: <?= $pctCuciPengambilan ?>%"></div>
                        </div>
                    </div>
                    <div class="progress-group">
                        <span class="progress-text">Pencucian</span>
                        <span class="progress-number"><b><?= $cuciProses ?></b>/<?= $totalCuci ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-primary" style="width: <?= $pctCuciProses ?>%"></div>
                        </div>
                    </div>
                    <div class="progress-group" style="margin-bottom: 0;">
                        <span class="progress-text">Selesai</span>
                        <span class="progress-number"><b><?= $cuciSelesai ?></b>/<?= $totalCuci ?></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-success" style="width: <?= $pctCuciSelesai ?>%"></div>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-right">
                    <a href="../admin/data_pencucian.php" class="btn btn-sm btn-default">Lihat Data Pencucian</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-clock-o"></i> Aktivitas Pengajuan Terbaru</h3>
                </div>
                <div class="box-body" style="max-height: 300px; overflow: auto;">
                    <?php if ($aktivitasQuery && mysqli_num_rows($aktivitasQuery) > 0): ?>
                        <ul class="products-list product-list-in-box">
                            <?php while ($aktivitas = mysqli_fetch_assoc($aktivitasQuery)): ?>
                                <?php
                                    $statusLabel = 'Unknown';
                                    $statusClass = 'label-default';

                                    if ((int) $aktivitas['status'] === 1) {
                                        $statusLabel = 'Baru';
                                        $statusClass = 'label-warning';
                                    } elseif ((int) $aktivitas['status'] === 2) {
                                        $statusLabel = 'Proses';
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
                        <p class="text-muted" style="margin: 0;">Belum ada aktivitas pengajuan.</p>
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