<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

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
        $("#example1").DataTable({
            "order": [[ 0, "asc" ]],
            "pageLength": 25
        });
    });
    </script>';

$pageTitle = "Pencucian Linen";
$pageDesc = "Data Pencucian Linen";
$_SESSION['active_menu'] = 'distribusi';

$dataQuery = mysqli_query($koneksi, "SELECT p.*, lr.id as id_linen_ruangan, l.nama_linen as nama_linen, r.nama_ruangan as nama_ruangan
          FROM pencucian p
          LEFT JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
          LEFT JOIN linen l ON lr.id_linen = l.id
          LEFT JOIN ruangan r ON lr.id_ruangan = r.id
          ORDER BY p.tanggal DESC");
ob_start();
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list"></i> Daftar Pencucian Linen</h3>
                </div>
                <div class="box-body">
                    <?php if (mysqli_num_rows($dataQuery) > 0): ?>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Linen</th>
                                    <th>Nama Ruangan</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; while ($data = mysqli_fetch_assoc($dataQuery)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($data['nama_linen']) ?></td>
                                        <td><?= htmlspecialchars($data['nama_ruangan']) ?></td>
                                        <td><?= htmlspecialchars($data['jumlah']) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($data['tanggal'])) ?></td>
                                        <td><?= htmlspecialchars($data['keterangan']) ?></td>
                                        <td class="text-center">
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
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <i class="fa fa-info-circle"></i> Tidak ada data pencucian.
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