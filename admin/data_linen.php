<?php
    session_start();
    include '../koneksi.php';
    // SESSION LOGIN
    $username = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    $row = mysqli_fetch_assoc($query);
    // CSS Tambahan untuk halaman ini
    $additionalCSS = [
        '../assets/plugins/datatables/dataTables.bootstrap.css',
    ];

    // JS Tambahan untuk halaman ini
    $additionalJS = [
        '../assets/plugins/jQuery/jQuery-2.1.4.min.js', // Gunakan versi lebih baru
        '../assets/plugins/datatables/jquery.dataTables.min.js',
        '../assets/plugins/datatables/dataTables.bootstrap.min.js',
        // '../assets/plugins/select2/select2.full.min.js', // Untuk select2 jika dibutuhkan
    ];

    // Inline Javascript
    $inlineJS = '<script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            $("#example1").DataTable();
        });
        </script>';

    // Judul halaman dan Deskripsi Halaman
    $pageTitle = "Linen";
    $pageDesc = "Data Linen";
    $_SESSION['active_menu'] = 'linen';

    // Query untuk mengambil data ke database
    $dataQuery = mysqli_query($koneksi, "SELECT * FROM linen");

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
                                <th>Admin</th>
                                <th>Nama Ruangan</th>
                                <th>Nama Linen</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($dataQuery)):
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <?php if (empty($data['id_user'])): ?>
                                            <!-- Tombol trigger modal -->
                                            <a href="tambah_admin_ruangan.php?id=<?= $data['id'] ?>" class="btn btn-primary">
                                                Tambah Admin
                                                </button>
                                            <?php else: ?>
                                                <?= htmlspecialchars($data['nama_admin'] ?? 'Admin tidak ditemukan') ?>
                                            <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($data['nama_ruangan']) ?></td>
                                    <td><?= htmlspecialchars($data['telp_ruangan']) ?></td>
                                    <td>
                                        <span class="label <?= ($data['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                            <?= ($data['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($data['id_user'])): ?>
                                            <a href="edit_ruangan.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Edit</a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-warning" disabled><i class="fa fa-pencil"></i> Edit</button>
                                        <?php endif; ?>
                                        <!-- <a href="hapus_pengguna.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin menghapus?')"><i class="fa fa-trash"></i> Hapus</a> -->
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