<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Permohonan Pencucian";
$pageDesc = "Buat Permohonan Pencucian Linen";
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

$id_user = $row['id'];
$qRuangan = mysqli_query($koneksi, "SELECT * FROM ruangan WHERE id_user = '$id_user'");
$rRuangan = mysqli_fetch_assoc($qRuangan);
$id_ruangan = $rRuangan['id'];

ob_start();
?>

<!-- Tambahkan CSS Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "gagal") {
                    echo '<div class="alert alert-warning alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Gagal!</strong> Periksa kembali inputanmu!
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
                    <h3 class="box-title">Permohonan Pencucian Linen : <b>Ruang <?= $rRuangan['nama_ruangan']; ?></b></h3>
                </div>
                <form role="form" action="proses_pencucian.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_ruangan" value="<?= $id_ruangan ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Linen *</label>
                            <select class="form-control select2" name="id_linen_ruangan" style="width: 100%;">
                                <option value="">-- Pilih Linen --</option>
                                <?php
                                $query_linen = mysqli_query($koneksi, "SELECT lr.*, 
                                    l.nama_linen as nama_linen, 
                                    l.kode_linen as kode_linen,
                                    r.nama_ruangan as nama_ruangan
                                    FROM linen_ruangan lr 
                                    LEFT JOIN linen l ON lr.id_linen = l.id
                                    LEFT JOIN ruangan r ON lr.id_ruangan = r.id
                                    WHERE r.id_user = '$id_user'");
                                while ($linen = mysqli_fetch_assoc($query_linen)):
                                ?>
                                    <option value="<?= $linen['id'] ?>">
                                        <?= $linen['kode_linen'] ?> - <?= $linen['nama_linen'] ?> (Stok Tersedia: <?= $linen['jumlah_linen'] ?> pcs)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <small>Pilih linen yang akan diajukan</small>
                        </div>

                        <div class="form-group">
                            <label>Jumlah *</label>
                            <input type="number" class="form-control" name="jumlah"
                                min="1"
                                placeholder="Masukkan Jumlah Pencucian (Maksimal sesuai stok linen yang dipilih)">
                            <small class="text-muted">Perhatikan stok tersedia pada pilihan linen di atas. Jumlah tidak boleh melebihi stok yang ada.</small>
                        </div>
                        <div class="form-group">
                            <label>Keterangan </label>
                            <textarea name="keterangan" class="form-control" rows="5" id=""></textarea>
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
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Cari linen...',
            allowClear: true
        });
    });
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/header.php';
echo $content;
include __DIR__ . '/../layouts/footer.php';
?>