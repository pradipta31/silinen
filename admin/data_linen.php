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
$_SESSION['active_menu'] = 'linen';

// Query untuk mengambil data jadwal laboratorium
$dataQuery = mysqli_query($koneksi, "SELECT l.*, u.nama 
     FROM j_lab j 
     JOIN users u ON j.id_user = u.id 
     WHERE j.id_laboratorium = $id_laboratorium");

// Ambil info laboratorium
$labQuery = mysqli_query($koneksi, "SELECT * FROM laboratorium WHERE id = $id_laboratorium");
$labData = mysqli_fetch_assoc($labQuery);

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
            
            <!-- Info Laboratorium -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Informasi Laboratorium</h3>
                </div>
                <div class="box-body">
                    <?php if($labData): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Kode Lab</th>
                                    <td><?= htmlspecialchars($labData['kode_lab']) ?></td>
                                </tr>
                                <tr>
                                    <th>Nama Lab</th>
                                    <td><?= htmlspecialchars($labData['nama_lab']) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="label <?= ($labData['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                            <?= ($labData['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fa fa-warning"></i> Data laboratorium tidak ditemukan!
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Jadwal Laboratorium -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Jadwal Laboratorium</h3>
                    <div class="box-tools">
                        <a href="tambah_jadwal.php?id_lab=<?= $id_laboratorium ?>" class="btn btn-primary btn-md"> 
                            <i class="fa fa-plus"></i> Tambah Jadwal
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <?php if(mysqli_num_rows($dataQuery) > 0): ?>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Admin</th>
                                <th>Tanggal</th>
                                <th>Sesi</th>
                                <th>Status</th>
                                <th>Jadwal</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($dataQuery)):
                                // Format tanggal
                                $tanggal = date('d-m-Y', strtotime($data['tanggal']));
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['nama']; ?></td>
                                    <td><?= $tanggal ?></td>
                                    <td>
                                        <span class="label <?= ($data['sesi'] == 'Pagi') ? 'label-primary' : 'label-success' ?>">
                                            <?= $data['sesi']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label <?= ($data['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                            <?= ($data['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="lihat_jadwal.php?id_jlab=<?= $data['id']; ?>" class="btn btn-primary">
                                            <i class="fa fa-arrow-right"></i> Lihat Jadwal
                                        </a>
                                    </td>
                                    <td>
                                        <a href="edit_jadwal.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        <!-- <a href="hapus_jadwal.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-danger"
                                          onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                            <i class="fa fa-trash"></i> Hapus
                                        </a> -->
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Belum ada jadwal untuk laboratorium ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="box-footer">
                <a href="data_jadwal.php" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali ke Daftar Laboratorium
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