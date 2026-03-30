<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);
// CSS Tambahan untuk halaman ini
$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css'
];

// JS Tambahan untuk halaman ini
$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js', // Gunakan versi lebih baru
    '../assets/plugins/datatables/jquery.dataTables.min.js',
    '../assets/plugins/datatables/dataTables.bootstrap.min.js'
];

// Inline Javascript
$inlineJS = '<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        $("#example1").DataTable();
    });
    </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Pengajuan";
$pageDesc = "Data Pengajuan";
$_SESSION['active_menu'] = 'distribusi';

// Query untuk mengambil data ke database
$dataQuery = mysqli_query($koneksi, "SELECT p.*, 
                                    l.nama_linen as nama_linen, 
                                    r.nama_ruangan as nama_ruangan
                                    FROM pengajuan p 
                                    LEFT JOIN linen l ON p.id_linen = l.id
                                    LEFT JOIN ruangan r ON p.id_ruangan = r.id
                                    ORDER BY p.tanggal DESC");

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
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-list"></i> Daftar Pengajuan
                    </h3>
                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Linen</th>
                                <th>Nama Ruangan</th>
                                <th>Jumlah</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($dataQuery)):
                                $tanggal = date('d M Y', strtotime($data['tanggal']));
                                $waktu_masuk = date('H:i', strtotime($data['tanggal']));
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><b><?= htmlspecialchars($data['nama_linen']) ?></b></td>
                                    <td><?= htmlspecialchars($data['nama_ruangan']) ?></td>
                                    <td>
                                        <span class="label label-success" style="font-size: 12px;">
                                            <i class="fa fa-check-circle"></i> <?= htmlspecialchars($data['jumlah']) ?> pcs
                                        </span>
                                    </td>
                                    <td>
                                        <div style="border-left: 3px solid #00a65a; padding-left: 8px;">
                                            <i class="fa fa-calendar"></i> <?= date('d M Y', strtotime($data['tanggal'])) ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa fa-clock-o"></i> <?= date('H:i', strtotime($data['tanggal'])) ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($data['keterangan']) ?>
                                    </td>
                                    <td class="text-center">
                                        <?= ($data['status'] == 1) ? '<span class="label label-warning"><i class="fa fa-clock-o"></i> Pengajuan</span>' : (($data['status'] == 2) ? '<span class="label label-primary"><i class="fa fa-truck"></i> Pengiriman</span>' : (($data['status'] == 3) ? '<span class="label label-success"><i class="fa fa-check-circle"></i> Diterima</span>' :
                                            '<span class="label label-default">Unknown</span>')) ?>
                                    </td>
                                    <td>
                                        <?php if ($data['status'] == 1): ?>
                                            <a href="#" class="btn btn-sm btn-success"
                                                onclick="konfirmasi(<?= $data['id'] ?>)">
                                                <i class="fa fa-check-circle"></i> Konfirmasi
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-success" disabled style="opacity: 0.5; cursor: not-allowed;">
                                                <i class="fa fa-check-circle"></i> Konfirmasi
                                            </button>
                                        <?php endif; ?>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasi(id) {
        Swal.fire({
            title: 'Konfirmasi?',
            text: "Yakin ingin konfirmasi pengajuan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, konfirmasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'konfirmasi_pengajuan.php?id=' + id;
            }
        });
        return false;
    }
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/header.php';
echo $content;
include __DIR__ . '/../layouts/footer.php';
?>