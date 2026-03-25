<?php
    session_start();
    include '../koneksi.php';
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);
    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Ruangan";
    $pageDesc = "Tambah Ruangan";
    $_SESSION['active_menu'] = 'ruangan';

    ob_start();
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "berhasil") {
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Berhasil!</strong> Ruangan baru berhasil ditambahkan!
                        </div>';
                } elseif ($_GET['pesan'] == "gagal") {
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Gagal!</strong> Gagal menambahkan ruangan!
                        </div>';
                }
            }
            ?>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Ruangan Baru</h3>
                </div>
                <form role="form" action="proses_tambah_ruangan.php" method="POST">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nama Ruangan</label>
                            <input type="text" class="form-control" placeholder="Masukkan Nama Ruangan" name="nama_ruangan" required>
                        </div>
                        <div class="form-group">
                            <label>Telp Ruangan</label>
                            <input type="text" class="form-control" placeholder="Masukkan Telp Ruangan" name="telp_ruangan" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Pilih Status</option>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                        <a href="data_ruangan.php" class="btn btn-default">Batal</a>
                    </div>
                </form>
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