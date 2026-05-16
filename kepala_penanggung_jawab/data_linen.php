<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// CSS Tambahan untuk halaman ini
$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css'
];

// JS Tambahan untuk halaman ini
$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js',
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js'
];

// Inline Javascript
$inlineJS = '<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $("#example1").DataTable({
            "order": [[ 0, "asc" ]],
            "pageLength": 25
        });
    });
    </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Linen";
$pageDesc = "Data Linen";
$_SESSION['active_menu'] = 'linen';

$dataQuery = mysqli_query($koneksi, "SELECT * FROM linen ORDER BY tanggal DESC");
ob_start();
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list"></i> Daftar Linen</h3>
                </div>
                <div class="box-body">
                    <?php if (mysqli_num_rows($dataQuery) > 0): ?>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Linen</th>
                                    <th>Nama Linen</th>
                                    <th>Jumlah</th>
                                    <th>Sisa Linen</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($data = mysqli_fetch_assoc($dataQuery)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($data['kode_linen']) ?></td>
                                        <td><?= htmlspecialchars($data['nama_linen']) ?></td>
                                        <td><?= htmlspecialchars($data['jumlah_linen']) ?></td>
                                        <td><?= htmlspecialchars($data['sisa_linen']) ?></td>
                                        <td><?= date('d M Y', strtotime($data['tanggal'])) ?></td>
                                        <td>
                                            <span class="label <?= ($data['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                                <?= ($data['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fa fa-info-circle"></i> Tidak ada data linen.
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