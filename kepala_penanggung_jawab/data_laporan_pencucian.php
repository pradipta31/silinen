<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-d');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css'
];

$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js',
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js'
];

$inlineJS = '<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $("#tableLaporan").DataTable({
            "order": [[ 1, "desc" ]],
            "pageLength": 25
        });

        $("#tanggal_awal, #tanggal_akhir").change(function() {
            var tanggal_awal = $("#tanggal_awal").val();
            var tanggal_akhir = $("#tanggal_akhir").val();
            if (tanggal_awal && tanggal_akhir) {
                window.location.href = "?tanggal_awal=" + tanggal_awal + "&tanggal_akhir=" + tanggal_akhir;
            }
        });
    });
    </script>';

$pageTitle = "Laporan Pencucian Linen";
$pageDesc = "Laporan Pencucian Linen";
$_SESSION['active_menu'] = 'laporan';

$statistikQuery = mysqli_query($koneksi, "SELECT
    COUNT(*) as total_pencucian,
    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as pengambilan,
    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as pencucian,
    SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as selesai,
    SUM(jumlah) as total_linen
    FROM pencucian
    WHERE DATE(tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
$statistik = mysqli_fetch_assoc($statistikQuery);

$dataQuery = mysqli_query($koneksi, "SELECT p.*, l.nama_linen, l.kode_linen, r.nama_ruangan, u.nama as admin_ruangan
    FROM pencucian p
    LEFT JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
    LEFT JOIN linen l ON lr.id_linen = l.id
    LEFT JOIN ruangan r ON lr.id_ruangan = r.id
    LEFT JOIN users u ON r.id_user = u.id
    WHERE DATE(p.tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY p.tanggal DESC");

ob_start();
?>
<style>
    @media print {
        .no-print { display: none !important; }
        body { font-size: 12px; }
        .table { font-size: 11px; }
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-file-text-o"></i> Laporan Pencucian Linen</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Periode Laporan:</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="tanggal_awal" value="<?= $tanggal_awal ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="tanggal_akhir" value="<?= $tanggal_akhir ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <h4 class="text-muted" style="margin-top: 30px;">
                                <?= date('d M Y', strtotime($tanggal_awal)) ?> - <?= date('d M Y', strtotime($tanggal_akhir)) ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?= number_format($statistik['total_pencucian']) ?></h3>
                            <p>Total Pencucian</p>
                        </div>
                        <div class="icon"><i class="fa fa-list"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?= number_format($statistik['pengambilan']) ?></h3>
                            <p>Pengambilan</p>
                        </div>
                        <div class="icon"><i class="fa fa-clock-o"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3><?= number_format($statistik['pencucian']) ?></h3>
                            <p>Pencucian</p>
                        </div>
                        <div class="icon"><i class="fa fa-tint"></i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?= number_format($statistik['selesai']) ?></h3>
                            <p>Selesai</p>
                        </div>
                        <div class="icon"><i class="fa fa-check-circle"></i></div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-table"></i> Detail Laporan Pencucian</h3>
                </div>
                <div class="box-body">
                    <?php if (mysqli_num_rows($dataQuery) > 0): ?>
                        <div class="table-responsive">
                            <table id="tableLaporan" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Ruangan</th>
                                        <th>Admin Ruangan</th>
                                        <th>Kode Linen</th>
                                        <th>Nama Linen</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($data = mysqli_fetch_assoc($dataQuery)): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= date('d M Y H:i', strtotime($data['tanggal'])) ?></td>
                                            <td><?= htmlspecialchars($data['nama_ruangan'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($data['admin_ruangan'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($data['kode_linen'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($data['nama_linen'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($data['jumlah']) ?> pcs</td>
                                            <td>
                                                <?php
                                                    if ($data['status'] == 1) {
                                                        echo '<span class="label label-warning"><i class="fa fa-clock-o"></i> Pengambilan</span>';
                                                    } elseif ($data['status'] == 2) {
                                                        echo '<span class="label label-primary"><i class="fa fa-tint"></i> Pencucian</span>';
                                                    } elseif ($data['status'] == 3) {
                                                        echo '<span class="label label-success"><i class="fa fa-check-circle"></i> Selesai</span>';
                                                    } else {
                                                        echo '<span class="label label-default">Unknown</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($data['keterangan'] ?: '-') ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fa fa-info-circle"></i> Tidak ada data untuk periode yang dipilih.
                        </div>
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