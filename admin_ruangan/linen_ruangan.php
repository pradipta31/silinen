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
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js', // Gunakan versi lebih baru
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js'
];

// Inline Javascript
$inlineJS = '<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $("#example1").DataTable();
    });
    </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Pengajuan";
$pageDesc = "Data Pengajuan";
$_SESSION['active_menu'] = 'linen';

// QUERY untuk mengambil siapa yang login
$id_user = $row['id'];
// Query untuk mengambil data ke database
$dataQuery = mysqli_query($koneksi, "SELECT lr.*, 
                                    l.nama_linen as nama_linen, 
                                    r.nama_ruangan as nama_ruangan
                                    FROM linen_ruangan lr 
                                    LEFT JOIN linen l ON lr.id_linen = l.id
                                    LEFT JOIN ruangan r ON lr.id_ruangan = r.id
                                    WHERE r.id_user = '$id_user'");

ob_start();
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "berhasil") {
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Berhasil!</strong> Data baru berhasil diinputkan!
            </div>';
                } elseif ($_GET['pesan'] == "gagal") {
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Gagal!</strong> Gagal input data baru!
            </div>';
                }
            }
            ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-list"></i> Daftar Linen
                    </h3>
                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Linen</th>
                                <th>Nama Ruangan</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($dataQuery)):
                                $tanggal = date('d M Y', strtotime($data['tanggal']));
                                $waktu_masuk = date('H:i', strtotime($data['tanggal']));
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['nama_linen']) ?></td>
                                    <td><?= htmlspecialchars($data['nama_ruangan']) ?></td>
                                    <td>
                                        <span class="label label-success" style="font-size: 12px;">
                                            <i class="fa fa-check-circle"></i> <?= htmlspecialchars($data['jumlah_linen']) ?> pcs
                                        </span>
                                    </td>
                                    <td>
                                        <div style="border-left: 3px solid #00a65a; padding-left: 8px;">
                                            <i class="fa fa-calendar"></i> <?= date('d M Y', strtotime($data['tanggal'])) ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa fa-clock-o"></i> <?= date('H:i', strtotime($data['tanggal'])) ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="label <?= ($data['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                            <?= ($data['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="detail_linen.php?id_linen=<?= $data['id']; ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Detail</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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