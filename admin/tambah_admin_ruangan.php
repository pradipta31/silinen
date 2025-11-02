<?php
    session_start();
    include '../koneksi.php';
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);
    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Admin Ruangan";
    $pageDesc = "Tambah Admin Ruangan";
    $_SESSION['active_menu'] = 'Ruangan';

    // Inline Javascript
    $inlineJS = '<script>
        function simpanBtn(d){
            d.disabled = true;
            d.innerHTML = "<i class="fa fa-spinner fa-spin"></i>";
        }
        
        $(document).ready(function() {
            $(".select2").select2();
        });
        </script>';

    // Ambil data ruangan yang akan diedit
    $id_ruangan = isset($_GET['id']) ? $_GET['id'] : '';
    $row = [];
    if($id_ruangan) {
        $query = mysqli_query($koneksi, "SELECT * FROM ruangan WHERE id = '$id_ruangan'");
        $row = mysqli_fetch_assoc($query);
        if(!$row) {
            header('location: data_ruangan.php?pesan=notfound');
            exit();
        }
    }

    $query_admin = mysqli_query($koneksi, "SELECT * FROM users WHERE hak_akses = 'admin_ruangan' AND status_ruangan = 0");
    $admin_options = '';
    while($admin = mysqli_fetch_assoc($query_admin)) {
        $admin_options .= '<option value="'.$admin['id'].'">'.$admin['nama'].' ('.$admin['username'].')</option>';
    }

    ob_start();
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "error") {
                    $detail_error = $_GET['detail'];
                    echo '<div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Peringatan!</strong></br>' . $detail_error . '
                        </div>';
                }
            }
            ?>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Admin <b>Ruangan <?= $row['nama_ruangan']; ?></b></h3>
                </div>
                <form role="form" action="proses_ruangan.php" method="POST">
                    <input type="hidden" name="id_ruangan" value="<?= $id_ruangan ?>">
                    <input type="hidden" name="token_ruangan" value="baru">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nama Ruangan</label>
                            <input type="text" class="form-control" value="<?= $row['nama_ruangan']; ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label>Pilih Admin Ruangan</label>
                            <select name="id_user" class="form-control select2" required>
                                <option value="">-- Pilih Admin --</option>
                                <?= $admin_options ?>
                            </select>
                            <p class="help-block">Pilih admin ruangan dari daftar yang tersedia</p>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="data_ruangan.php" class="btn btn-default">Kembali</a>
                        <button type="submit" name="submit" class="btn btn-primary" onclick="simpanBtn(this);">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Admin Ruangan Tersedia</h3>
                </div>
                <div class="box-body">
                    <p>Berikut adalah daftar <b>Admin Ruangan</b> yang tersedia (status belum terdaftar di ruangan manapun):</p>
                    <ul class="list-group">
                        <?php
                            // Query ulang untuk menampilkan daftar admin
                            $query_admin_list = mysqli_query($koneksi, "SELECT * FROM users WHERE hak_akses = 'admin_ruangan' AND status_ruangan = 0");
                            if(mysqli_num_rows($query_admin_list) > 0) {
                                while($admin = mysqli_fetch_assoc($query_admin_list)) {
                                    echo '<li class="list-group-item">'.$admin['nama'].' ('.$admin['username'].')</li>';
                                }
                            } else {
                                echo '<li class="list-group-item">Tidak ada admin ruangan yang tersedia. Silahkan tambahkan Pengguna dengan Hak Akses Admin Ruangan terlebih dahulu. Klik disini untuk menambahkan <b>Pengguna Baru</b>.
                                    <a href="tambah_pengguna.php">Tambah Pengguna</a>
                                </li>';
                            }
                        ?>
                    </ul>
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