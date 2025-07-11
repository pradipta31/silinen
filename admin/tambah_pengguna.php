<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);
// Judul halaman dan Deskripsi Halaman
$pageTitle = "Pengguna";
$pageDesc = "Tambah Pengguna";
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
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Pengguna Baru</h3>
                </div>
                <form role="form">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" placeholder="Masukkan Nama" name="nama">
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" placeholder="Masukkan Username" name="username">
                            <small>Username akan digunakan untuk melakukan proses login.</small>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="Masukkan Email" name="email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Masukkan Password" name="password">
                        </div>
                        <div class="form-group">
                            <label>Re-Type Password</label>
                            <input type="password" class="form-control" id="confirmation_password" name="confirmation_password" placeholder="Masukkan Password Ulang">
                            <span id="message"></span>
                        </div>
                        <div class="form-group">
                            <label>Hak Akses</label>
                            <select name="hak_akses" class="form-control">
                                <option>Pilih Hak Akses</option>
                                <option value="admin_ruangan">Admin Ruangan</option>
                                <option value="petugas_laundry">Petugas Laundry</option>
                                <option value="kepala_penanggung_jawab">Kepala Penanggung Jawab</option>
                            </select>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
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