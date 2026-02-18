<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);
// CSS Tambahan untuk halaman ini
$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css',
    'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css',
    '../assets/dist/css/linen.css'
];

// JS Tambahan untuk halaman ini
$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js', // Gunakan versi lebih baru
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js'
    // '../assets/plugins/select2/select2.full.min.js', // Untuk select2 jika dibutuhkan
];

// Inline Javascript
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
$pageTitle = "Linen Bersih";
$pageDesc = "Data Linen Bersih";
$_SESSION['active_menu'] = 'bersih';

// Query untuk mengambil data ke database
$dataQuery = mysqli_query($koneksi, "SELECT 
    dl.*,
    u.nama AS nama_admin,
    r.nama_ruangan,
    l.nama_linen,
    l.gambar,
    lr.id_user,
    lr.id_ruangan,
    lr.id_linen
FROM distribusi_linen dl
INNER JOIN linen_ruangan lr ON dl.id_linen_ruangan = lr.id
INNER JOIN users u ON lr.id_user = u.id
INNER JOIN ruangan r ON lr.id_ruangan = r.id
INNER JOIN linen l ON lr.id_linen = l.id
WHERE dl.status = 2
ORDER BY dl.tanggal_masuk DESC");

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
                <strong>Gagal!</strong> Data ruangan gagal di perbaharui!
            </div>';
                }
            }
            ?>
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Admin Ruangan</th>
                                <th>Nama Ruangan</th>
                                <th>Nama Linen</th>
                                <th>Gambar Linen</th>
                                <th>Tanggal Masuk</th>
                                <th>Jumlah</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($dataQuery)):
                                // Cek apakah ada gambar
                                $gambar_path = !empty($data['gambar']) ? '../uploads/linen/' . $data['gambar'] : '../assets/img/no-image.jpg';
                                $gambar_exists = !empty($data['gambar']) && file_exists($gambar_path);

                                $tanggal = date('d M Y', strtotime($data['tanggal_masuk']));
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <?= htmlspecialchars($data['nama_admin'] ?? 'Admin tidak ditemukan') ?>
                                    </td>
                                    <td><?= htmlspecialchars($data['nama_ruangan']) ?></td>
                                    <td><?= htmlspecialchars($data['nama_linen']) ?></td>
                                    <td class="text-center">
                                        <?php if ($gambar_exists): ?>
                                            <a href="<?= $gambar_path ?>" data-lightbox="linen-<?= $data['id_linen'] ?>" data-title="<?= htmlspecialchars($data['nama_linen']) ?>">
                                                <img src="<?= $gambar_path ?>" class="img-linen" alt="<?= htmlspecialchars($data['nama_linen']) ?>">
                                            </a>
                                        <?php else: ?>
                                            <div class="no-image">
                                                <i class="fa fa-image fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="fa fa-calendar"></i> <?= $tanggal ?>
                                            <br>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-custom" style="background: #f5e8e8ff; color: #2e7d32; font-size: 15px;">
                                            <i class="fa fa-hashtag"></i> <?= $data['jumlah'] ?> Pcs
                                        </span></td>
                                    </td>
                                    <td>
                                        <a href="edit_ruangan.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Edit</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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