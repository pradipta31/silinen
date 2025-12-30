<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Ambil id_ruangan dari parameter GET
// $id_ruangan = isset($_GET['id_ruangan']) ? intval($_GET['id_ruangan']) : 0;

// CSS Tambahan untuk halaman ini
$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css',
    'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css',
    '../assets/dist/css/linen.css'
];

$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js',
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js'
];

// Inline Javascript
$inlineJS = '<script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $("#example1").DataTable();
            
            // Inisialisasi lightbox
            lightbox.option({
                "resizeDuration": 200,
                "wrapAround": true,
                "albumLabel": "Gambar %1 dari %2"
            });
        });
        </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Linen";
$pageDesc = "Data Linen";
$_SESSION['active_menu'] = 'linen';

// Query untuk mengambil data Linen
$dataQuery = mysqli_query($koneksi, "SELECT * FROM linen ORDER BY tanggal DESC");
ob_start();
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "berhasil") {
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Berhasil!</strong> Data baru berhasil diinputkan!
            </div>';
                } elseif ($_GET['pesan'] == "gagal") {
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Gagal!</strong> Gagal input data baru!
            </div>';
                }
            }
            ?>

            <!-- Daftar Linen -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-list"></i> Daftar Linen
                        <small class="text-muted">(Total: <?= mysqli_num_rows($dataQuery) ?> item)</small>
                    </h3>
                    <div class="box-tools pull-right" style="position: absolute; right: 15px; top: 10px;">
                        <a href="tambah_linen.php" class="btn btn-primary">+ Tambah Linen Baru</a>
                        <button id="toggleView" class="btn btn-default">
                            <i class="fa fa-th-large"></i> Tampilan Grid
                        </button>
                    </div>
                </div>
                <div class="box-body">

                    <!-- TABEL VIEW (default) -->
                    <div id="tableView" class="table-view">
                        <?php if (mysqli_num_rows($dataQuery) > 0): ?>
                            <table id="example1" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">No</th>
                                        <th width="100">Gambar</th>
                                        <th>Kode Linen</th>
                                        <th>Nama Linen</th>
                                        <th width="100">Jumlah</th>
                                        <th>Sisa Linen</th>
                                        <th width="120">Tanggal Masuk</th>
                                        <th width="80">Status</th>
                                        <th width="100">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($data = mysqli_fetch_assoc($dataQuery)):
                                        // Cek apakah ada gambar
                                        $gambar_path = !empty($data['gambar']) ? '../uploads/linen/' . $data['gambar'] : '../assets/img/no-image.jpg';
                                        $gambar_exists = !empty($data['gambar']) && file_exists($gambar_path);

                                        // Format tanggal
                                        $tanggal = date('d M Y', strtotime($data['tanggal']));
                                        $waktu_masuk = date('H:i', strtotime($data['tanggal']));
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-center">
                                                <?php if ($gambar_exists): ?>
                                                    <a href="<?= $gambar_path ?>" data-lightbox="linen-<?= $data['id'] ?>" data-title="<?= htmlspecialchars($data['nama_linen']) ?>">
                                                        <img src="<?= $gambar_path ?>" class="img-linen" alt="<?= htmlspecialchars($data['nama_linen']) ?>">
                                                    </a>
                                                <?php else: ?>
                                                    <div class="no-image">
                                                        <i class="fa fa-image fa-2x"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($data['kode_linen']) ?></strong>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($data['nama_linen']) ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-custom" style="background: #e8f5e8; color: #2e7d32; font-size: 14px;">
                                                    <i class="fa fa-hashtag"></i> <?= $data['jumlah_linen'] ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-custom" style="background: #f5e8e8ff; color: #b83434ff; font-size: 14px;">
                                                    <i class="fa fa-hashtag"></i> <?= $data['sisa_linen'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted">
                                                    <i class="fa fa-calendar"></i> <?= $tanggal ?>
                                                    <br>
                                                    <small><i class="fa fa-clock-o"></i> <?= $waktu_masuk ?></small>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="label <?= ($data['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                                    <?= ($data['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="edit_linen.php?id=<?= $data['id'] ?>" class="btn btn-xs btn-warning" title="Edit">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                    <a href="detail_linen.php?id_linen=<?= $data['id']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Detail</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    endwhile;
                                    // Reset pointer untuk digunakan di grid view
                                    mysqli_data_seek($dataQuery, 0);
                                    ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center" style="padding: 50px 0;">
                                <i class="fa fa-inbox fa-4x text-muted"></i>
                                <h4 class="text-muted">Belum ada Linen untuk Ruangan ini</h4>
                                <p class="text-muted">Klik tombol "Tambah Linen Baru" untuk menambahkan data pertama</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- CARD/GRID VIEW -->
                    <div id="cardView" class="card-view">
                        <div class="linen-grid">
                            <?php
                            if (mysqli_num_rows($dataQuery) > 0):
                                while ($data = mysqli_fetch_assoc($dataQuery)):
                                    // Cek apakah ada gambar
                                    $gambar_path = !empty($data['gambar']) ? '../uploads/linen/' . $data['gambar'] : '../assets/img/no-image.jpg';
                                    $gambar_exists = !empty($data['gambar']) && file_exists($gambar_path);

                                    // Format tanggal
                                    $tanggal = date('d M Y', strtotime($data['tanggal']));
                                    $waktu_masuk = date('H:i', strtotime($data['tanggal']));
                            ?>
                                    <div class="linen-grid-item">
                                        <div class="linen-card">
                                            <div class="text-center mb-3">
                                                <?php if ($gambar_exists): ?>
                                                    <a href="<?= $gambar_path ?>" data-lightbox="linen-grid-<?= $data['id'] ?>" data-title="<?= htmlspecialchars($data['nama_linen']) ?>">
                                                        <img src="<?= $gambar_path ?>"
                                                            style="width: 100%; height: 180px; object-fit: cover; border-radius: 5px;"
                                                            alt="<?= htmlspecialchars($data['nama_linen']) ?>">
                                                    </a>
                                                <?php else: ?>
                                                    <div style="width: 100%; height: 180px; background: #f5f5f5; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fa fa-image fa-3x text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="linen-info">
                                                <h4 style="margin-top: 0; margin-bottom: 5px;">
                                                    <?= htmlspecialchars($data['kode_linen']) ?>
                                                </h4>
                                                <h5 style="margin: 0 0 10px 0; color: #333;">
                                                    <strong><?= htmlspecialchars($data['nama_linen']) ?></strong>
                                                </h5>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <div style="font-size: 24px; font-weight: bold; color: #4CAF50;">
                                                            <?= $data['jumlah_linen'] ?>
                                                        </div>
                                                        <small class="text-muted">Jumlah</small>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div style="font-size: 24px; font-weight: bold; color: #af534cff;">
                                                            <?= $data['sisa_linen'] ?>
                                                        </div>
                                                        <small class="text-muted">Sisa Linen</small>
                                                    </div>
                                                    <div class="col-xs-6 text-right">
                                                        <div style="font-size: 11px; color: #666;">
                                                            <i class="fa fa-calendar"></i> <?= $tanggal ?>
                                                            <br>
                                                            <i class="fa fa-clock-o"></i> <?= $waktu_masuk ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr style="margin: 10px 0;">

                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <span class="label <?= ($data['status'] == 1) ? 'label-success' : 'label-danger' ?>" style="display: block;">
                                                            <?= ($data['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                                        </span>
                                                    </div>
                                                    <div class="col-xs-6 text-right">
                                                        <div class="btn-group">
                                                            <a href="detail_linen.php?id_linen=<?= $data['id']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Detail</a>
                                                            <a href="edit_linen.php?id=<?= $data['id'] ?>"
                                                                class="btn btn-xs btn-warning" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                                Edit
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                endwhile;
                            else: ?>
                                <div class="text-center" style="width: 100%; padding: 50px 0;">
                                    <i class="fa fa-inbox fa-4x text-muted"></i>
                                    <h4 class="text-muted">Belum ada Linen untuk Ruangan ini</h4>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Toggle antara table view dan card view
    document.getElementById('toggleView').addEventListener('click', function() {
        const tableView = document.getElementById('tableView');
        const cardView = document.getElementById('cardView');
        const btn = this;

        if (tableView.style.display === 'none') {
            // Switch to table view
            tableView.style.display = 'block';
            cardView.style.display = 'none';
            btn.innerHTML = '<i class="fa fa-th-large"></i> Tampilan Grid';
            btn.classList.remove('active');
        } else {
            // Switch to card view
            tableView.style.display = 'none';
            cardView.style.display = 'block';
            btn.innerHTML = '<i class="fa fa-table"></i> Tampilan Tabel';
            btn.classList.add('active');
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/header.php';
echo $content;
include __DIR__ . '/../layouts/footer.php';
?>