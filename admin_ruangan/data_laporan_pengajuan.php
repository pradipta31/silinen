<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Ambil parameter tanggal dari GET
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-d');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

// CSS Tambahan untuk halaman ini
$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css',
    '../assets/plugins/daterangepicker/daterangepicker.css'
];

// JS Tambahan untuk halaman ini
$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js',
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js',
    '../assets/plugins/daterangepicker/moment.min.js',
    '../assets/plugins/daterangepicker/daterangepicker.js'
];

$inlineJS = '<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $("#tableLaporan").DataTable({
            "order": [[ 1, "desc" ]],
            "pageLength": 25
        });
        
        // Event listener untuk input tanggal
        $("#tanggal_awal, #tanggal_akhir").change(function() {
            var tanggal_awal = $("#tanggal_awal").val();
            var tanggal_akhir = $("#tanggal_akhir").val();
            if (tanggal_awal && tanggal_akhir) {
                window.location.href = "?tanggal_awal=" + tanggal_awal + "&tanggal_akhir=" + tanggal_akhir;
            }
        });
    });
    
    function printLaporan() {
        window.print();
    }
    
    function exportExcel() {
        var tanggal_awal = $("#tanggal_awal").val();
        var tanggal_akhir = $("#tanggal_akhir").val();
        window.location.href = "data_laporan_pengajuan_export_excel.php?tanggal_awal=" + tanggal_awal + "&tanggal_akhir=" + tanggal_akhir;
    }
    </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Laporan Pengajuan Linen";
$pageDesc = "Laporan Pengajuan Linen";
$_SESSION['active_menu'] = 'laporan';

// Query untuk statistik pengajuan
$statistikQuery = mysqli_query($koneksi, "SELECT 
    COUNT(*) as total_pengajuan,
    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as baru_mengajukan,
    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as proses_pengantaran,
    SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as konfirmasi_diterima,
    SUM(jumlah) as total_linen
    FROM pengajuan 
    WHERE DATE(tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
$statistik = mysqli_fetch_assoc($statistikQuery);

// Query untuk data laporan pengajuan
$dataQuery = mysqli_query($koneksi, "SELECT p.*, 
    l.id, l.nama_linen, l.kode_linen,
    r.nama_ruangan,
    u.nama as admin_ruangan
    FROM pengajuan p
    LEFT JOIN linen l ON p.id_linen = l.id
    LEFT JOIN ruangan r ON p.id_ruangan = r.id
    LEFT JOIN users u ON r.id_user = u.id
    WHERE DATE(p.tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY p.tanggal DESC");

ob_start();
?>
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        body {
            font-size: 12px;
        }

        .table {
            font-size: 11px;
        }
    }
</style>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <!-- Header Laporan -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-file-text-o"></i> Laporan Pengajuan Linen
                    </h3>
                    <div class="box-tools pull-right no-print">
                        <button type="button" class="btn btn-default btn-sm" onclick="printLaporan()">
                            <i class="fa fa-print"></i> Cetak Laporan
                        </button>
                        <!-- Update bagian tombol Export Excel -->
                        <button type="button" class="btn btn-success btn-sm" onclick="exportExcel()">
                            <i class="fa fa-file-excel-o"></i> Export Excel
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <!-- Periode Laporan -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Periode Laporan:</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="<?= $tanggal_awal ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?= $tanggal_akhir ?>">
                                        </div>
                                    </div>
                                </div>
                                <small class="help-block">Pilih tanggal awal dan akhir untuk filter laporan</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="pull-right">
                                <h4 class="text-muted">
                                    <i class="fa fa-calendar-o"></i>
                                    <?= date('d M Y', strtotime($tanggal_awal)) ?> - <?= date('d M Y', strtotime($tanggal_akhir)) ?>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Statistik -->
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?= number_format($statistik['total_pengajuan']) ?></h3>
                            <p>Total Pengajuan</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-paper-plane"></i>
                        </div>
                        <a href="#laporan" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?= number_format($statistik['baru_mengajukan']) ?></h3>
                            <p>Baru Mengajukan</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <a href="#laporan" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3><?= number_format($statistik['proses_pengantaran']) ?></h3>
                            <p>Proses Pengantaran</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-truck"></i>
                        </div>
                        <a href="#laporan" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3><?= number_format($statistik['konfirmasi_diterima']) ?></h3>
                            <p>Konfirmasi Diterima</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <a href="#laporan" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <!-- Statistik Total Linen -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body">
                            <div class="col-md-3">
                                <div class="description-block border-right">
                                    <h5 class="description-header text-blue">
                                        <i class="fa fa-hashtag"></i> <?= number_format($statistik['total_linen']) ?> pcs
                                    </h5>
                                    <span class="description-text">TOTAL LINEN DIAJUKAN</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Laporan Pengajuan -->
            <div class="box" id="laporan">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-table"></i> Detail Laporan Pengajuan Linen
                    </h3>
                </div>
                <div class="box-body">
                    <?php if (mysqli_num_rows($dataQuery) > 0): ?>
                        <div class="table-responsive">
                            <table id="tableLaporan" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th width="120">Tanggal</th>
                                        <th>Ruangan</th>
                                        <th>Admin Ruangan</th>
                                        <th>Kode Linen</th>
                                        <th>Nama Linen</th>
                                        <th width="80">Jumlah</th>
                                        <th width="100">Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($data = mysqli_fetch_assoc($dataQuery)):
                                        $tanggal = date('d M Y H:i', strtotime($data['tanggal']));

                                        $status_labels = [
                                            1 => '<span class="label label-warning"><i class="fa fa-clock-o"></i> Baru Mengajukan</span>',
                                            2 => '<span class="label label-primary"><i class="fa fa-truck"></i> Proses Pengantaran</span>',
                                            3 => '<span class="label label-success"><i class="fa fa-check-circle"></i> Konfirmasi Diterima</span>'
                                        ];
                                        $status_label = $status_labels[$data['status']] ?? '<span class="label label-default">Unknown</span>';
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td>
                                                <i class="fa fa-calendar"></i> <?= $tanggal ?>
                                            </td>
                                            <td><?= htmlspecialchars($data['nama_ruangan'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($data['admin_ruangan'] ?: '-') ?></td>
                                            <td><strong><?= htmlspecialchars($data['kode_linen'] ?: '-') ?></strong></td>
                                            <td><?= htmlspecialchars($data['nama_linen']) ?></td>
                                            <td class="text-center">
                                                <span class="badge badge-info">
                                                    <?= $data['jumlah'] ?> pcs
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?= $status_label ?>
                                            </td>
                                            <td><?= htmlspecialchars($data['keterangan'] ?: '-') ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Tidak ada data pengajuan untuk periode yang dipilih.
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