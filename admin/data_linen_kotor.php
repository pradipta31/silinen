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
    'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css', // SweetAlert2
    '../assets/dist/css/linen.css'
];

// JS Tambahan untuk halaman ini
$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js', // Gunakan versi lebih baru
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js' // SweetAlert2
    // '../assets/plugins/select2/select2.full.min.js', // Untuk select2 jika dibutuhkan
];

// Di bagian $inlineJS, tambahkan fungsi JavaScript:
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
        
        // Fungsi konfirmasi untuk proses linen
        function confirmProses(id, nama_linen, nama_ruangan) {
            Swal.fire({
                title: "Proses Linen?",
                html: `Apakah Anda yakin ingin memproses:<br>
                      <strong>${nama_linen}</strong><br>
                      Dari ruangan: <strong>${nama_ruangan}</strong>?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#f39c12",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Proses!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "proses_linen.php?id=" + id;
                }
            });
        }
        
        // Fungsi konfirmasi untuk selesai
        function confirmSelesai(id, nama_linen, nama_ruangan) {
            Swal.fire({
                title: "Tandai Selesai?",
                html: `Apakah Anda yakin linen sudah bersih:<br>
                      <strong>${nama_linen}</strong><br>
                      Dari ruangan: <strong>${nama_ruangan}</strong>?`,
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#00a65a",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Sudah Bersih!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "selesai_linen.php?id=" + id;
                }
            });
        }
        </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Linen Kotor";
$pageDesc = "Data Linen Kotor";
$_SESSION['active_menu'] = 'kotor';

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
                <strong>Berhasil!</strong> Data baru berhasil diperbaharui!
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
                                <th>Status</th>
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
                                        <span class="badge badge-custom" style="background: #f5e8e8ff; color: #b83434ff; font-size: 15px;">
                                            <i class="fa fa-hashtag"></i> <?= $data['jumlah'] ?> Pcs
                                        </span></td>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $statusConfig = [
                                            1 => ['text' => 'Kotor', 'class' => 'bg-red'],
                                            2 => ['text' => 'Proses', 'class' => 'bg-yellow'],
                                            3 => ['text' => 'Bersih', 'class' => 'bg-green'],
                                            4 => ['text' => 'Sudah di Distribusikan', 'class' => 'bg-blue']
                                        ];
                                        
                                        $status = $data['status'];
                                        $config = $statusConfig[$status] ?? ['text' => 'Unknown', 'class' => 'bg-gray'];
                                        ?>
                                        <span class="badge <?= $config['class'] ?>"><?= $config['text'] ?></span>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                            $status = $data['status'];
                                            $id = $data['id'];
                                            $nama_linen = htmlspecialchars($data['nama_linen']);
                                            $nama_ruangan = htmlspecialchars($data['nama_ruangan']);
                                            
                                            if ($status == 1) {
                                                // Status Kotor - Tombol Proses dengan confirmation
                                                echo '<a href="#" onclick="confirmProses(' . $id . ', \'' . $nama_linen . '\', \'' . $nama_ruangan . '\')" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-refresh"></i> Proses
                                                    </a>';
                                            } elseif ($status == 2) {
                                                // Status Proses - Tombol Selesai hijau dengan confirmation
                                                echo '<a href="#" onclick="confirmSelesai(' . $id . ', \'' . $nama_linen . '\', \'' . $nama_ruangan . '\')" class="btn btn-sm btn-success">
                                                        <i class="fa fa-check"></i> Selesai
                                                    </a>';
                                            } elseif ($status == 3) {
                                                // Status Bersih - Tombol disabled
                                                echo '<button class="btn btn-sm btn-default" disabled>
                                                        <i class="fa fa-check-circle text-success"></i> Selesai
                                                    </button>';
                                            } elseif ($status == 4) {
                                                // Status Distribusi - Tombol disabled
                                                echo '<button class="btn btn-sm btn-info" disabled>
                                                        <i class="fa fa-truck"></i> Terdistribusi
                                                    </button>';
                                            }
                                        ?>
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