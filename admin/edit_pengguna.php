<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Ambil data pengguna yang akan diedit
$id = isset($_GET['id']) ? $_GET['id'] : '';
$userData = [];
if($id) {
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id'");
    $userData = mysqli_fetch_assoc($query);
    if(!$userData) {
        header('location: data_pengguna.php?pesan=notfound');
        exit();
    }
}

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Pengguna";
$pageDesc = $id ? "Edit Pengguna" : "Tambah Pengguna";
$_SESSION['active_menu'] = 'pengguna';

// Inline Javascript
$inlineJS = '<script>
    $("#password, #confirmation_password").on("keyup", function () {
        if ($("#password").val() == $("#confirmation_password").val()) {
            $("#message").html("Password match!").css("color", "green");
        } else {
            $("#message").html("Password not match!").css("color", "red");
        }
    });
</script>';

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
                            <strong>Peringatan!</strong></br>'. $detail_error .'
                        </div>';
                }
            }
            ?>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $id ? 'Edit' : 'Tambah' ?> Pengguna</h3>
                </div>
                <form role="form" action="proses_edit_pengguna.php" method="POST">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" placeholder="Masukkan Nama" name="nama" value="<?= isset($userData['nama']) ? htmlspecialchars($userData['nama']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" placeholder="Masukkan Username" name="username" value="<?= isset($userData['username']) ? htmlspecialchars($userData['username']) : '' ?>">
                            <small>Username akan digunakan untuk melakukan proses login.</small>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="Masukkan Email" name="email" value="<?= isset($userData['email']) ? htmlspecialchars($userData['email']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Masukkan Password" name="password">
                            <small><?= $id ? 'Kosongkan jika tidak ingin mengubah password' : '' ?></small>
                        </div>
                        <div class="form-group">
                            <label>Re-Type Password</label>
                            <input type="password" class="form-control" id="confirmation_password" name="confirmation_password" placeholder="Masukkan Password Ulang">
                            <span id="message"></span>
                        </div>
                        <div class="form-group">
                            <label>Hak Akses</label>
                            <select name="hak_akses" class="form-control">
                                <option value="">Pilih Hak Akses</option>
                                <option value="admin_ruangan" <?= (isset($userData['hak_akses']) && $userData['hak_akses'] == 'admin_ruangan' ? 'selected' : '' )?>>Admin Ruangan</option>
                                <option value="petugas_laundry" <?= (isset($userData['hak_akses']) && $userData['hak_akses'] == 'petugas_laundry' ? 'selected' : '' )?>>Petugas Laundry</option>
                                <option value="kepala_penanggung_jawab" <?= (isset($userData['hak_akses']) && $userData['hak_akses'] == 'kepala_penanggung_jawab' ? 'selected' : '' )?>>Kepala Penanggung Jawab</option>
                            </select>
                        </div>
                        <?php if($id): ?>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="1" <?= (isset($userData['status']) && $userData['status'] == 1 ? 'selected' : '' )?>>Aktif</option>
                                <option value="0" <?= (isset($userData['status']) && $userData['status'] == 0 ? 'selected' : '' )?>>Nonaktif</option>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="box-footer">
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                        <a href="data_pengguna.php" class="btn btn-default">Kembali</a>
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