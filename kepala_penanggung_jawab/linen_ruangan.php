<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

$pageTitle = "Data Linen Ruangan";
$pageDesc = "Data Linen Ruangan";
$_SESSION['active_menu'] = 'linen_ruangan';

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
                    $colors = ['bg-aqua', 'bg-green', 'bg-yellow', 'bg-red', 'bg-purple', 'bg-maroon', 'bg-teal', 'bg-blue', 'bg-olive', 'bg-lime'];
                    $icons = ['fa fa-spa', 'fa fa-seedling', 'fa fa-heartbeat', 'fa fa-star', 'fa fa-procedures', 'fa fa-gem', 'fa fa-leaf', 'fa fa-water', 'fa fa-bed'];
                    $counter = 0;
                    while ($r = mysqli_fetch_assoc($q)) {
                        $colorClass = $colors[$counter % count($colors)];
                        $iconClass = $icons[$counter % count($icons)];
                    ?>
                        <div class="col-lg-6 col-xs-6">
                            <div class="small-box <?= $colorClass ?>">
                                <div class="inner">
                                    <h3><?= htmlspecialchars($r['nama_ruangan']) ?></h3>
                                    <p><?= !empty($r['admin_ruangan']) ? htmlspecialchars($r['admin_ruangan']) : '-' ?></p>
                                </div>
                                <div class="icon">
                                    <i class="<?= $iconClass ?>"></i>
                                </div>
                                <?php if (!empty($r['admin_ruangan'])): ?>
                                    <a href="data_linen_ruangan.php?id_ruangan=<?= $r['id'] ?>" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
                                <?php endif; ?>
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