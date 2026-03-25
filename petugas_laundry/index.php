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

        <!-- Card 1: Jumlah Linen Kotor -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>123</h3>
                    <p>Jumlah Linen Kotor</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tint"></i>
                </div>
                <a href="data_linen_kotor.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Card 2: Jumlah Pencucian -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>456</h3>
                    <p>Jumlah Pencucian</p>
                </div>
                <div class="icon">
                    <i class="fa fa-refresh"></i>
                </div>
                <a href="data_pencucian.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Card 3: Jumlah Linen Bersih -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>789</h3>
                    <p>Jumlah Linen Bersih</p>
                </div>
                <div class="icon">
                    <i class="fa fa-check"></i>
                </div>
                <a href="data_linen_bersih.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <!-- Card 4: Jumlah Distribusi Laundry -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>101112</h3>
                    <p>Jumlah Distribusi Laundry</p>
                </div>
                <div class="icon">
                    <i class="fa fa-truck"></i>
                </div>
                <a href="data_distribusi_laundry.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
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