<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Ambil id_ruangan dari parameter GET
$id_ruangan = isset($_GET['id_ruangan']) ? intval($_GET['id_ruangan']) : 0;

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
            $("#example1").DataTable();
        });
        </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Linen";
$pageDesc = "Data Linen";
$_SESSION['active_menu'] = 'linen_ruangan';

// Query untuk mengambil seluruh data Linen Ruangan (relasi: linen_ruangan -> ruangan -> users)
$whereRuangan = $id_ruangan > 0 ? "WHERE lr.id_ruangan = $id_ruangan" : "";
$dataQuery = mysqli_query($koneksi, "SELECT lr.*, l.kode_linen, l.nama_linen, l.gambar as gambar_linen, l.status as status_linen, r.nama_ruangan, r.id_user AS ruangan_id_user, u.nama AS admin_ruangan 
          FROM linen_ruangan lr
          JOIN ruangan r ON lr.id_ruangan = r.id
          LEFT JOIN users u ON r.id_user = u.id
          JOIN linen l ON lr.id_linen = l.id
          $whereRuangan");

// (opsional) Ambil info Ruangan jika id_ruangan disediakan
$ruanganData = false;
if($id_ruangan > 0){
    $ruanganQuery = mysqli_query($koneksi, "SELECT * FROM ruangan WHERE id = $id_ruangan");
    $ruanganData = mysqli_fetch_assoc($ruanganQuery);
}

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
            
            <!-- Info Ruangan -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Informasi Ruangan</h3>
                </div>
                <div class="box-body">
                    <?php if($id_ruangan > 0): ?>
                        <?php if($ruanganData): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered" style="font-size: medium;">
                                        <tr>
                                            <th>Nama Ruangan</th>
                                            <td><?= htmlspecialchars($ruanganData['nama_ruangan']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="label <?= ($ruanganData['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                                    <?= ($ruanganData['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fa fa-warning"></i> Data ruangan tidak ditemukan!
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Menampilkan seluruh data linen ruangan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Daftar Linen -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Daftar Linen</h3>
                </div>
                <div class="box-body">
                    <?php if(mysqli_num_rows($dataQuery) > 0): ?>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Admin Ruangan</th>
                                <th>Ruangan</th>
                                <th>Kode Linen</th>
                                <th>Nama Linen</th>
                                <th>Jumlah Linen</th>
                                <th>Status Linen</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($dataQuery)):
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($data['admin_ruangan'] ?: '-') ?></td>
                                    <td><?= htmlspecialchars($data['nama_ruangan'] ?: $data['nama_ruangan']) ?></td>
                                    <td><?= htmlspecialchars($data['kode_linen']) ?></td>
                                    <td><?= htmlspecialchars($data['nama_linen']) ?></td>
                                    <td>
                                        <?= htmlspecialchars($data['jumlah_linen']) ?>
                                    </td>
                                    <td>
                                        <span class="label <?= ($data['status_linen'] == 1) ? 'label-success' : 'label-danger' ?>">
                                            <?= ($data['status_linen'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit_linen.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Belum ada Linen untuk Ruangan ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="box-footer">
                <a href="linen_ruangan.php" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali ke Daftar Ruangan
                </a>
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