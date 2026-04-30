<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Cek apakah user valid
if (!$row) {
    header("Location: ../index.php");
    exit;
}

// Ambil id_linen dari parameter GET
$id_linen = isset($_GET['id_linen']) ? intval($_GET['id_linen']) : 0;

// Cek apakah id_linen valid
if ($id_linen <= 0) {
    header("Location: data_linen.php");
    exit;
}

// Simpan id_linen dan id_ruangan ke session
$_SESSION['last_id_linen'] = $id_linen;
$ruanganQuery = mysqli_query($koneksi, "SELECT id FROM ruangan WHERE id_user = {$row['id']}");
$ruanganData = mysqli_fetch_assoc($ruanganQuery);
if ($ruanganData) {
    $_SESSION['last_id_ruangan'] = $ruanganData['id'];
}

// CSS Tambahan untuk halaman ini
$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css',
    'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css'
];

// JS Tambahan untuk halaman ini
$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js',
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js'
];

// Inline Javascript
$inlineJS = '<script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $("#example1").DataTable();
            
            // Inisialisasi lightbox
            lightbox.option({
                "resizeDuration": 200,
                "wrapAround": true,
                "albumLabel": "Gambar %1 dari %2"
            });
        });
        </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Detail Linen";
$pageDesc = "Detail Linen";
$_SESSION['active_menu'] = 'linen';

// Query untuk mengambil data Linen
$linenQuery = mysqli_query($koneksi, "SELECT * FROM linen WHERE id = $id_linen");
$linenData = mysqli_fetch_assoc($linenQuery);

// Debug: Uncomment untuk melihat ID dan data
// echo "<!-- Debug: ID Linen = $id_linen, Kode Linen = " . ($linenData ? $linenData['kode_linen'] : 'NULL') . " -->";

// Query untuk stok di ruangan user
$queryStokRuangan = mysqli_query($koneksi, "SELECT lr.jumlah_linen FROM linen_ruangan lr INNER JOIN ruangan r ON lr.id_ruangan = r.id WHERE lr.id_linen = $id_linen");
$stokRuanganData = mysqli_fetch_assoc($queryStokRuangan);
$stokRuangan = $stokRuanganData ? $stokRuanganData['jumlah_linen'] : 0;

// Query untuk riwayat distribusi
$riwayatQuery = mysqli_query($koneksi, "SELECT p.*, lr.jumlah_linen, r.nama_ruangan, u.nama as admin_ruangan
                                        FROM pencucian p
                                        LEFT JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
                                        LEFT JOIN ruangan r ON lr.id_ruangan = r.id
                                        LEFT JOIN users u ON r.id_user = u.id
                                        WHERE lr.id_linen = $id_linen
                                        ORDER BY p.tanggal DESC");

ob_start();
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if($linenData): ?>
                <!-- Info Linen -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-info-circle"></i> Detail Linen
                        </h3>
                        <div class="box-tools pull-right">
                            <a href="data_linen.php" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Gambar Linen -->
                                <div class="text-center">
                                    <?php
                                    $gambar_path = !empty($linenData['gambar']) ? '../uploads/linen/' . $linenData['gambar'] : '../assets/img/no-image.jpg';
                                    $gambar_exists = !empty($linenData['gambar']) && file_exists($gambar_path);
                                    ?>
                                    <?php if ($gambar_exists): ?>
                                        <a href="<?= $gambar_path ?>" data-lightbox="detail-linen" data-title="<?= htmlspecialchars($linenData['nama_linen']) ?>">
                                            <img src="<?= $gambar_path ?>" class="img-responsive img-thumbnail" style="max-height: 300px;" alt="<?= htmlspecialchars($linenData['nama_linen']) ?>">
                                        </a>
                                        <p class="text-muted">Klik gambar untuk memperbesar</p>
                                    <?php else: ?>
                                        <div class="no-image-detail">
                                            <i class="fa fa-image fa-5x text-muted"></i>
                                            <p class="text-muted">Tidak ada gambar</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="150">Kode Linen</th>
                                        <td><strong><?= htmlspecialchars($linenData['kode_linen']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Nama Linen</th>
                                        <td><strong><?= htmlspecialchars($linenData['nama_linen']) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Stok Tersedia</th>
                                        <td>
                                            <span class="badge badge-success" style="font-size: 16px;">
                                                <i class="fa fa-hashtag"></i> <?= $stokRuangan ?> pcs
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Masuk</th>
                                        <td>
                                            <i class="fa fa-calendar"></i> <?= date('d M Y', strtotime($linenData['tanggal'])) ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa fa-clock-o"></i> <?= date('H:i:s', strtotime($linenData['tanggal'])) ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="label <?= ($linenData['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                                <?= ($linenData['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php if(!empty($linenData['deskripsi'])): ?>
                                    <tr>
                                        <th>Deskripsi</th>
                                        <td><?= nl2br(htmlspecialchars($linenData['deskripsi'])) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Distribusi -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-history"></i> Riwayat Distribusi
                        </h3>
                    </div>
                    <div class="box-body">
                        <?php if(mysqli_num_rows($riwayatQuery) > 0): ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Ruangan</th>
                                        <th>Admin Ruangan</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($riwayat = mysqli_fetch_assoc($riwayatQuery)):
                                        $tanggal_riwayat = date('d M Y H:i', strtotime($riwayat['tanggal']));
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <i class="fa fa-calendar"></i> <?= $tanggal_riwayat ?>
                                            </td>
                                            <td><?= htmlspecialchars($riwayat['nama_ruangan'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($riwayat['admin_ruangan'] ?: '-') ?></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?= $riwayat['jumlah'] ?> pcs
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $status_labels = [
                                                    1 => '<span class="label label-warning">Pengajuan</span>',
                                                    2 => '<span class="label label-primary">Pencucian</span>',
                                                    3 => '<span class="label label-success">Selesai</span>'
                                                ];
                                                echo $status_labels[$riwayat['status']] ?? '<span class="label label-default">Unknown</span>';
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($riwayat['keterangan'] ?: '-') ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Belum ada riwayat distribusi untuk linen ini.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-triangle"></i> Data linen tidak ditemukan!
                </div>
                <a href="data_linen.php" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali ke Daftar Linen
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/header.php';
echo $content;
include __DIR__ . '/../layouts/footer.php';
?>