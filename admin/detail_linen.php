<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Ambil id_linen dari parameter GET
$id_linen = isset($_GET['id_linen']) ? intval($_GET['id_linen']) : 0;

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
$pageTitle = "Linen Ruangan";
$pageDesc = "Data Linen Ruangan";
$_SESSION['active_menu'] = 'linen';

$linenQuery = mysqli_query($koneksi, "SELECT * FROM linen WHERE id='$id_linen'");
$linenData = mysqli_fetch_assoc($linenQuery);
// Query untuk mengambil data Linen
$dataQuery = mysqli_query($koneksi, "SELECT * FROM linen_ruangan WHERE id_linen = '$id_linen'");

$ruanganData = mysqli_query($koneksi, "SELECT r.*, u.nama as admin_ruangan 
                            FROM ruangan r 
                            LEFT JOIN users u ON r.id_user = u.id");

$linenRuangan = mysqli_query($koneksi, "SELECT 
            lr.*,
            u.nama as admin_ruangan,
            r.nama_ruangan,
            l.nama_linen
          FROM linen_ruangan lr
          LEFT JOIN users u ON lr.id_user = u.id
          LEFT JOIN ruangan r ON lr.id_ruangan = r.id
          LEFT JOIN linen l ON lr.id_linen = l.id");
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
            <div class="box box-primary">
                <div class="box-body">
                    <?php if ($linenData): ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <i class="fa fa-info-circle"></i> Informasi Linen
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="30%">Kode Linen</th>
                                                <td><?= htmlspecialchars($linenData['kode_linen']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Nama Linen</th>
                                                <td><?= htmlspecialchars($linenData['nama_linen']) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Linen</th>
                                                <td><?= htmlspecialchars($linenData['jumlah_linen']) ?> Pcs</td>
                                            </tr>
                                            <tr>
                                                <th>Sisa Linen</th>
                                                <td><?= htmlspecialchars($linenData['sisa_linen']) ?> Pcs</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="label <?= ($linenData['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                                                        <?= ($linenData['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <i class="fa fa-info-circle"></i> Informasi Pendistribusian Linen
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>No</th>
                                                <th>Ruangan</th>
                                                <th>Linen Terpakai</th>
                                                <th>Linen Cadangan</th>
                                            </tr>

                                            <?php
                                            $no = 1;
                                            $total_terpakai = 0;
                                            $total_cadangan = 0;

                                            while ($rLR = mysqli_fetch_assoc($linenRuangan)):
                                                $total_terpakai += $rLR['linen_terpakai'];
                                                $total_cadangan += $rLR['linen_cadangan'];
                                            ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= $rLR['nama_ruangan']; ?></td>
                                                    <td><?= $rLR['linen_terpakai']; ?> pcs</td>
                                                    <td><?= $rLR['linen_cadangan']; ?> pcs</td>
                                                </tr>
                                            <?php endwhile; ?>

                                            <!-- Baris Total -->
                                            <tr style="background-color: #f5f5f5; font-weight: bold;">
                                                <td colspan="2" align="right">TOTAL:</td>
                                                <td><?= $total_terpakai; ?> pcs</td>
                                                <td><?= $total_cadangan; ?> pcs</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fa fa-warning"></i> Data Linen tidak ditemukan!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-list"></i> Daftar Ruangan
                    </h3>

                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Admin Ruangan</th>
                                <th>Nama Ruangan</th>
                                <th>Telp Ruangan</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($ruanganData)):
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <?= htmlspecialchars($data['admin_ruangan'] ?? 'Admin masih kosong') ?>
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
                                <!-- Modal Bootstrap untuk Form Distribusi (Versi Non-JS) -->
                                <!-- Modal Bootstrap untuk Form Distribusi (Versi Non-JS) -->
                                <!-- Modal Bootstrap untuk Form Distribusi (Versi Non-JS) -->
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

            <!-- Tombol Kembali -->
            <div class="box-footer">
                <a href="data_linen.php" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Kembali ke Daftar Linen
                </a>
            </div>
        </div>
    </div>
    <script>
        // Fungsi untuk menghitung sisa linen
        function hitungSisaLinen(id) {
            const maxSisa = <?= htmlspecialchars($linenData['sisa_linen'] ?? 0) ?>;
            const linenTerpakai = document.getElementById(`linen_terpakai${id}`).value || 0;
            const linenCadangan = document.getElementById(`linen_cadangan${id}`).value || 0;
            const submitBtn = document.getElementById(`submitBtn-${id}`);

            // Hitung sisa setelah linen terpakai
            const sisaSetelahTerpakai = maxSisa - linenTerpakai;

            // Hitung total distribusi
            const totalDistribusi = parseInt(linenTerpakai) + parseInt(linenCadangan);

            // Hitung sisa akhir
            const sisaAkhir = maxSisa - totalDistribusi;

            // Update tampilan kalkulasi untuk linen terpakai
            document.getElementById(`kalkulasi-terpakai-${id}`).innerHTML =
                `Sisa setelah terpakai: ${sisaSetelahTerpakai >= 0 ? sisaSetelahTerpakai : 0} Pcs`;

            // Update info total distribusi
            const totalDistribusiElement = document.getElementById(`total-distribusi-${id}`);
            totalDistribusiElement.innerHTML =
                `<strong>Total Distribusi:</strong> ${totalDistribusi} Pcs<br>
         <strong>Sisa Akhir:</strong> ${sisaAkhir >= 0 ? sisaAkhir : 0} Pcs`;

            // Validasi dan styling berdasarkan sisa akhir
            if (sisaAkhir < 0) {
                // Jika sisa akhir minus
                totalDistribusiElement.classList.remove('alert-info');
                totalDistribusiElement.classList.add('alert-danger');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-exclamation-triangle"></i> Melebihi stok';
                submitBtn.classList.remove('btn-primary');
                submitBtn.classList.add('btn-danger');
            } else if (sisaAkhir === 0) {
                // Jika sisa akhir = 0
                totalDistribusiElement.classList.remove('alert-danger', 'alert-info');
                totalDistribusiElement.classList.add('alert-warning');
                // submitBtn.disabled = true;
                // submitBtn.innerHTML = '<i class="fa fa-ban"></i> Stok habis';
                // submitBtn.classList.remove('btn-primary');
                // submitBtn.classList.add('btn-warning');
            } else {
                // Jika sisa akhir > 0
                totalDistribusiElement.classList.remove('alert-danger', 'alert-warning');
                totalDistribusiElement.classList.add('alert-info');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa fa-save"></i> Simpan Distribusi';
                submitBtn.classList.remove('btn-danger', 'btn-warning');
                submitBtn.classList.add('btn-primary');
            }

            // Validasi input tidak boleh melebihi sisa tersedia
            if (parseInt(linenTerpakai) > maxSisa) {
                document.getElementById(`linen_terpakai${id}`).setCustomValidity('Linen terpakai melebihi sisa tersedia');
            } else {
                document.getElementById(`linen_terpakai${id}`).setCustomValidity('');
            }

            if (parseInt(linenCadangan) > maxSisa) {
                document.getElementById(`linen_cadangan${id}`).setCustomValidity('Linen cadangan melebihi sisa tersedia');
            } else {
                document.getElementById(`linen_cadangan${id}`).setCustomValidity('');
            }
        }

        // Inisialisasi hitung untuk semua modal yang sudah ada
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            // Reset pointer untuk loop ulang
            mysqli_data_seek($ruanganData, 0);
            while ($data = mysqli_fetch_assoc($ruanganData)):
            ?>
                hitungSisaLinen(<?= $data['id'] ?>);
            <?php endwhile; ?>
        });
    </script>
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