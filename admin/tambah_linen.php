<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Data Linen";
$pageDesc = "Tambah Linen";
$_SESSION['active_menu'] = 'linen';

// CSS Tambahan untuk halaman ini
$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css',
    '../assets/plugins/select2/select2.min.css',
    '../assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    '../assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css'
];

// JS Tambahan untuk halaman ini
$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js',
    '../assets/plugins/select2/select2.full.min.js',
    '../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    '../assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js'
];

// Inline Javascript
$inlineJS = '<script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Initialize Select2
            $(".select2").select2();
        });
        </script>';

ob_start();
?>

<!-- Tambahkan CSS Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "berhasil") {
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Berhasil!</strong> Data baru berhasil diinputkan!
                    </div>';
                } elseif ($_GET['pesan'] == "error") {
                    $detail_error = isset($_GET['detail']) ? $_GET['detail'] : 'Terjadi kesalahan';
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Error!</strong><br>' . htmlspecialchars($detail_error) . '
                        </div>';
                }
            }
            ?>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Linen Baru</b></h3>
                </div>
                <form role="form" action="proses_tambah_linen.php" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Kode Linen *</label>
                            <input type="text" class="form-control" name="kode_linen" placeholder="Masukkan Kode Linen" required>
                            <small>Contoh: L001</small>
                        </div>
                        <div class="form-group">
                            <label>Nama Linen *</label>
                            <input type="text" class="form-control" name="nama_linen" placeholder="Masukkan Nama Linen" required>
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" class="form-control" name="gambar" accept=".jpg,.jpeg,.png">
                            <small>Note: Gambar format .jpg/.png/.jpeg (Maksimal 2MB)</small>
                        </div>
                        <div class="form-group">
                            <label>Jumlah *</label>
                            <input type="number" class="form-control" name="jumlah_linen" placeholder="Masukkan jumlah stok linen" min="1" required>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a href="data_linen.php" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" name="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Linen Saat Ini</h3>
                </div>
                <div class="box-body">
                    <p>Berikut adalah daftar Linen</p>
                    <?php
                    // Query untuk mendapatkan data linen di ruangan ini
                    $queryLinen = mysqli_query($koneksi, "SELECT * FROM linen ORDER BY tanggal DESC");
                    
                    if (mysqli_num_rows($queryLinen) > 0) {
                        echo '<ul class="list-group">';
                        while ($linen = mysqli_fetch_assoc($queryLinen)) {
                            $gambar = !empty($linen['gambar']) ? '../uploads/linen/' . $linen['gambar'] : '../assets/img/no-image.png';
                            echo '<li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <img src="' . $gambar . '" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-9">
                                            <strong>' . htmlspecialchars($linen['kode_linen']) . ' - ' . htmlspecialchars($linen['nama_linen']) . '</strong><br>
                                            <small>Jumlah: ' . $linen['jumlah_linen'] . ' | Ditambahkan: ' . date('d-m-Y', strtotime($linen['tanggal'])) . '</small>
                                        </div>
                                    </div>
                                </li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Belum ada data linen saat ini.
                            </div>';
                    }
                    ?>
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