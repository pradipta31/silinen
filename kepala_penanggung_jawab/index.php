<?php
    session_start();
    include '../koneksi.php';
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);
    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Dashboard";
    $pageDesc = "Control Panel";
    $_SESSION['active_menu'] = 'dashboard';
    ob_start();
?>

<section class="content">
    <?php
        echo '<div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Login Berhasil!</strong> Selamat datang '.$row['username'].' anda berhasil melakukan login pada aplikasi SILINEN!
        </div>';
    ?>

    <div class="row">

        <!-- Card 1: Jumlah Ruangan -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <?php
                        $query_ruangan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM ruangan");
                        $data_ruangan = mysqli_fetch_assoc($query_ruangan);
                    ?>
                    <h3><?php echo $data_ruangan['total']; ?></h3>
                    <p>Jumlah Ruangan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-building"></i>
                </div>
                <a href="data_ruangan.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Card 2: Jumlah Linen di Ruangan -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <?php
                        $query_linen_ruangan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM linen_ruangan");
                        $data_linen_ruangan = mysqli_fetch_assoc($query_linen_ruangan);
                    ?>
                    <h3><?php echo $data_linen_ruangan['total']; ?></h3>
                    <p>Jumlah Linen di Ruangan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bed"></i>
                </div>
                <a href="data_linen_ruangan.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Card 3: Jumlah Pengajuan -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <?php
                        $query_pengajuan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengajuan");
                        $data_pengajuan = mysqli_fetch_assoc($query_pengajuan);
                    ?>
                    <h3><?php echo $data_pengajuan['total']; ?></h3>
                    <p>Jumlah Pengajuan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-file-text"></i>
                </div>
                <a href="data_pengajuan.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Card 4: Jumlah Pengguna -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <?php
                        $query_pengguna = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE hak_akses = 'kepala_ruangan'");
                        $data_pengguna = mysqli_fetch_assoc($query_pengguna);
                    ?>
                    <h3><?php echo $data_pengguna['total']; ?></h3>
                    <p>Jumlah Kepala Ruangan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="data_pengguna.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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