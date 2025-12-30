<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

$pageTitle = "Data Jadwal";
$pageDesc = "Data Jadwal";
$_SESSION['active_menu'] = 'linen';

// CSS untuk calendar view
$additionalCSS = [
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css'
];

// JS untuk calendar
$additionalJS = [
    '../assets/plugins/moment/moment.min.js',
    '../assets/plugins/fullcalendar/fullcalendar.min.js',
    '../assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
];

$q = mysqli_query($koneksi, "SELECT r.*, u.nama as admin_ruangan 
                            FROM ruangan r 
                            LEFT JOIN users u ON r.id_user = u.id");

ob_start();
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <?php
                    // Array untuk warna dan ikon yang berbeda
                    $colors = ['bg-aqua', 'bg-green', 'bg-yellow', 'bg-red', 'bg-purple', 'bg-maroon', 'bg-teal', 'bg-blue', 'bg-olive', 'bg-lime'];
                    $icons = ['fas fa-spa', 'fas fa-seedling', 'fas fa-heartbeat', 'fas fa-star', 'fas fa-procedures', 'fas fa-gem', 'fas fa-leaf', 'fas fa-water', 'fas fa-bed'];

                    $counter = 0;
                    while ($r = mysqli_fetch_assoc($q)) {
                        // Menggunakan modulo untuk mengulang warna dan ikon jika jumlah lab lebih banyak dari array
                        $colorClass = $colors[$counter % count($colors)];
                        $iconClass = $icons[$counter % count($icons)];
                    ?>
                        <div class="col-lg-6 col-xs-6">
                            <div class="small-box <?= $colorClass ?>">
                                <div class="inner">
                                    <h3><?= $r['nama_ruangan']; ?></h3>
                                    <p><?php 
                                        if ($r['admin_ruangan'] != 0) {
                                            echo $r['admin_ruangan']; 
                                        }else{
                                            echo "-";
                                        }
                                        ?></p>
                                </div>
                                <div class="icon">
                                    <i class="<?= $iconClass ?>"></i>
                                </div>
                                <?php
                                    if($r['admin_ruangan'] != 0){
                                ?>
                                <a href="data_linen.php?id_ruangan=<?= $r['id']; ?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
                                <?php
                                    }else{

                                    }
                                ?>
                            </div>
                        </div>
                    <?php
                        $counter++;
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