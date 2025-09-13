<?php
    session_start();
    include '../koneksi.php';
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);
    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Ruangan";
    $pageDesc = "Edit Ruangan";
    $_SESSION['active_menu'] = 'ruangan';

    // Inline Javascript
    $inlineJS = '<script>
            function simpanBtn(d){
                d.disabled = true;
                d.innerHTML = "<i class="fa fa-spinner fa-spin"></i>";
            }
            </script>';

    // Ambil data ruangan yang akan diedit
    $id_ruangan = isset($_GET['id']) ? $_GET['id'] : '';
    $row = [];
    $admin_options = '';
    if($id_ruangan) {
        $query = mysqli_query($koneksi, "SELECT * FROM ruangan WHERE id = '$id_ruangan'");
        $row = mysqli_fetch_assoc($query);
        $query_admin = mysqli_query($koneksi, "SELECT * FROM users WHERE hak_akses = 'admin_ruangan'");
        if ($row['id_user'] != null) {
            while($admin = mysqli_fetch_assoc($query_admin)) {
                $selected = ($admin['id'] == $row['id_user']) ? 'selected' : '';
                $admin_options .= '<option value="'.$admin['id'].'" '.$selected.'>'.$admin['nama'].' ('.$admin['username'].')</option>';
            }
        }else {
            $q_admin = mysqli_query($koneksi, "SELECT * FROM users WHERE hak_akses = 'admin_ruangan' AND status_ruangan = 0");
            while($admin = mysqli_fetch_assoc($q_admin)) {
                $admin_options .= '<option value="'.$admin['id'].'">'.$admin['nama'].' ('.$admin['username'].')</option>';
            }
        }
        if(!$row) {
            header('location: data_ruangan.php?pesan=notfound');
            exit();
        }
    }else{
        $admin_options = '<option value="">Tidak ada admin tersedia</option>';
    }

    ob_start();
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "gagal") {
                        echo '<div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Gagal!</strong> Data admin ruangan gagal dihapus!
                </div>';
                }
            }
            ?>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Ruangan <b><?= $row['nama_ruangan']; ?></b></h3>
                </div>
                <form role="form" action="proses_ruangan.php" method="POST">
                    <input type="hidden" name="id_ruangan" value="<?= $id_ruangan ?>">
                    <input type="hidden" name="token_ruangan" value="edit">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label>Pilih Admin Ruangan</label>
                                    <select name="id_user" class="form-control" disabled>
                                        <option value="">-- Pilih Admin --</option>
                                        <?php echo $admin_options; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label style="visibility: hidden;">Tombol Hapus</label><br> <!-- Label tersembunyi untuk kesejajaran -->
                                    <a href="proses_ruangan.php?hapus_admin_ruangan=<?= $id_ruangan; ?>" class="btn btn-warning" 
                                    onclick="return confirm('Apakah anda yakin ingin menghapus Admin ruangan ini?');"><i class="fa fa-trash"></i> Hapus Admin</a>
                                </div>
                            </div>
                            <small>NB: Harap hapus terlebih dahulu admin ruangan untuk mengganti admin ruangan</small>
                        </div>
                        <div class="form-group">
                            <label>Nama Ruangan</label>
                            <input type="text" class="form-control" name="nama_ruangan" value="<?= $row['nama_ruangan']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Telepon Ruangan</label>
                            <input type="text" class="form-control" name="telp_ruangan" value="<?= $row['telp_ruangan']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" value="<?= $row['status']; ?>">
                                <option value="">- Pilih Status Ruangan -</option>
                                <option value="1" <?= $row['status'] == '1' ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $row['status'] == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
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
                    <p>Berikut adalah daftar admin ruangan</p>
                    <ul class="list-group">
                        <?php
                        // Query ulang untuk menampilkan daftar admin
                        $query_admin_list = mysqli_query($koneksi, "SELECT 
                            r.*,
                            u.*
                        FROM ruangan r
                        LEFT JOIN users u ON r.id_user = u.id
                        WHERE u.hak_akses = 'admin_ruangan'");
                        if(mysqli_num_rows($query_admin_list) > 0) {
                            while($admin = mysqli_fetch_assoc($query_admin_list)) {
                                echo '<li class="list-group-item">'.$admin['nama'].' ('.$admin['username'].') - <b>Ruang '.$admin['nama_ruangan'].'</b></li>';
                            }
                        } else {
                            echo '<li class="list-group-item">Tidak ada admin ruangan yang tersedia</li>';
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