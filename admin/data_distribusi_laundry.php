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
$pageTitle = "Distribusi Laundry";
$pageDesc = "Data Distribusi Laundry";
$_SESSION['active_menu'] = 'distribusi';

$distribusiLinen = mysqli_query($koneksi, "SELECT 
            r.id,
            u.nama as admin_ruangan,
            r.nama_ruangan,
            COALESCE(SUM(lr.linen_terpakai), 0) as total_terpakai,
            COALESCE(SUM(lr.linen_cadangan), 0) as total_cadangan
        FROM ruangan r
        LEFT JOIN linen_ruangan lr ON r.id = lr.id_ruangan
        LEFT JOIN users u ON r.id_user = u.id
        GROUP BY r.id, r.nama_ruangan
        ORDER BY r.id_user DESC");
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
                        <i class="fa fa-list"></i> Daftar Distribusi Laundry
                    </h3>

                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Admin Ruangan</th>
                                <th>Nama Ruangan</th>
                                <th>Linen Terpakai</th>
                                <th>Linen Cadangan</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($distribusiLinen)):
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <?= htmlspecialchars($data['admin_ruangan'] ?? 'Admin masih kosong') ?>
                                    </td>
                                    <td><?= htmlspecialchars($data['nama_ruangan']) ?></td>
                                    <td><?= htmlspecialchars($data['total_terpakai']) ?> Pcs</td>
                                    <td><?= htmlspecialchars($data['total_cadangan']) ?> Pcs</td>
                                    <td>
                                        <?php if (!empty($data['admin_ruangan'])): ?>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modalDistribusi<?= $data['id'] ?>">
                                                Distribusi <i class="fa fa-arrow-right"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-primary" disabled>Distribusi <i class="fa fa-arrow-right"></i></button>
                                        <?php endif; ?>
                                        <!-- <a href="hapus_pengguna.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-danger"
                      onclick="return confirm('Yakin ingin menghapus?')"><i class="fa fa-trash"></i> Hapus</a> -->
                                    </td>
                                </tr>

                                <!-- MODAL -->
                                <div class="modal fade" id="modalDistribusi<?= $data['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalDistribusiLabel<?= $data['id'] ?>">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <h4 class="modal-title" id="modalDistribusiLabel<?= $data['id'] ?>">Distribusi Linen ke Ruang <b><?= htmlspecialchars($data['nama_ruangan']) ?></b></h4>
                                            </div>
                                            <form id="formDistribusi<?= $data['id'] ?>" action="proses_distribusi_linen.php" method="POST">
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="ruangan_nama<?= $data['id'] ?>">Ruangan Tujuan</label>
                                                        <input type="text" class="form-control" id="ruangan_nama<?= $data['id'] ?>"
                                                            value="<?= htmlspecialchars($data['nama_ruangan']) ?>" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="nama_linen<?= $data['id'] ?>">Nama Linen</label>
                                                        <input type="text" class="form-control" id="nama_linen<?= $data['id'] ?>"
                                                            value="<?= htmlspecialchars($linenData['nama_linen'] ?? '') ?>" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="kode_linen<?= $data['id'] ?>">Kode Linen</label>
                                                        <input type="text" class="form-control" id="kode_linen<?= $data['id'] ?>"
                                                            value="<?= htmlspecialchars($linenData['kode_linen'] ?? '') ?>" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="linen_terpakai<?= $data['id'] ?>">Linen Terpakai</label>
                                                        <input type="number" class="form-control" id="linen_terpakai<?= $data['id'] ?>"
                                                            name="linen_terpakai" min="1" max="<?= htmlspecialchars($linenData['sisa_linen'] ?? 0) ?>" required
                                                            oninput="hitungSisaLinen(<?= $data['id'] ?>)">
                                                        <small class="text-muted">Sisa linen tersedia: <?= htmlspecialchars($linenData['sisa_linen'] ?? 0) ?> Pcs</small>
                                                        <!-- Kalkulasi untuk linen terpakai -->
                                                        <div id="kalkulasi-terpakai-<?= $data['id'] ?>" class="text-info" style="margin-top: 5px; font-size: 12px;">
                                                            Sisa setelah terpakai: <?= htmlspecialchars($linenData['sisa_linen'] ?? 0) ?> Pcs
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="linen_cadangan<?= $data['id'] ?>">Linen Cadangan</label>
                                                        <input type="number" class="form-control" id="linen_cadangan<?= $data['id'] ?>"
                                                            name="linen_cadangan" min="1" max="<?= htmlspecialchars($linenData['sisa_linen'] ?? 0) ?>" required
                                                            oninput="hitungSisaLinen(<?= $data['id'] ?>)">
                                                        <small class="text-muted">Sisa linen tersedia: <?= htmlspecialchars($linenData['sisa_linen'] ?? 0) ?> Pcs</small>
                                                    </div>

                                                    <!-- Info total distribusi -->
                                                    <div class="alert alert-info" id="total-distribusi-<?= $data['id'] ?>" style="margin-top: 10px; padding: 10px;">
                                                        <strong>Total Distribusi:</strong> 0 Pcs<br>
                                                        <strong>Sisa Akhir:</strong> <?= htmlspecialchars($linenData['sisa_linen'] ?? 0) ?> Pcs
                                                    </div>

                                                    <!-- Hidden Inputs -->
                                                    <input type="hidden" id="id_user<?= $data['id'] ?>" name="id_user" value="<?= $data['id_user'] ?>">
                                                    <input type="hidden" id="id_ruangan<?= $data['id'] ?>" name="id_ruangan" value="<?= $data['id'] ?>">
                                                    <input type="hidden" id="id_linen<?= $data['id'] ?>" name="id_linen" value="<?= $id_linen ?>">
                                                    <input type="hidden" name="max_sisa" value="<?= htmlspecialchars($linenData['sisa_linen'] ?? 0) ?>">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                    <button type="submit" id="submitBtn-<?= $data['id'] ?>" name="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Distribusi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
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