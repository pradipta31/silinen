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
        
        <!-- Card 1: Jumlah Linen Total -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>50</h3>
                    <p>Jumlah Linen</p>
                </div>
                <div class="icon">
                    <i class="fa fa-sort-numeric-asc"></i>
                </div>
                <a href="data_linen.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <!-- Card 2: Jumlah Linen Terpakai -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>50<sup style="font-size: 20px"> </sup></h3>
                    <p>Jumlah Linen Terpakai</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bed"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <!-- Card 3: Jumlah Linen Bersih -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>50</h3>
                    <p>Jumlah Linen Bersih</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tint"></i>
                </div>
                <a href="linen.php?status=bersih" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        
        <!-- Card 4: Jumlah Linen Kotor -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>50</h3>
                    <p>Jumlah Linen Kotor</p>
                </div>
                <div class="icon">
                    <i class="fa fa-exclamation-circle"></i>
                </div>
                <a href="linen.php?status=kotor" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Tambahan: Ringkasan Statistik -->
    <!-- <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">📊 Ringkasan Statistik Linen</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="progress-group">
                                <span class="progress-text">Linen Terpakai</span>
                                <span class="progress-number"><b><?php echo $total_linen_terpakai; ?></b>/<?php echo $total_jumlah_linen; ?></span>
                                <div class="progress sm">
                                    <div class="progress-bar progress-bar-green" style="width: <?php echo $persentase_terpakai; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-group">
                                <span class="progress-text">Linen Bersih</span>
                                <span class="progress-number"><b><?php echo $total_linen_bersih; ?></b>/<?php echo $total_jumlah_linen; ?></span>
                                <div class="progress sm">
                                    <?php 
                                    $persentase_bersih = ($total_jumlah_linen > 0) ? round(($total_linen_bersih / $total_jumlah_linen) * 100, 1) : 0;
                                    ?>
                                    <div class="progress-bar progress-bar-yellow" style="width: <?php echo $persentase_bersih; ?>%"></div>
                                </div>
                            </div>
                            
                            <div class="progress-group">
                                <span class="progress-text">Linen Kotor</span>
                                <span class="progress-number"><b><?php echo $total_linen_kotor; ?></b>/<?php echo $total_jumlah_linen; ?></span>
                                <div class="progress sm">
                                    <?php 
                                    $persentase_kotor = ($total_jumlah_linen > 0) ? round(($total_linen_kotor / $total_jumlah_linen) * 100, 1) : 0;
                                    ?>
                                    <div class="progress-bar progress-bar-red" style="width: <?php echo $persentase_kotor; ?>%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-bed"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Terpakai</span>
                                    <span class="info-box-number"><?php echo $total_linen_terpakai; ?></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: <?php echo $persentase_terpakai; ?>%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <?php echo $persentase_terpakai; ?>% dari total linen
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

</section>

<?php
    $content = ob_get_clean();
    include __DIR__ . '/../layouts/header.php';
    echo $content;
    include __DIR__ . '/../layouts/footer.php';
?>