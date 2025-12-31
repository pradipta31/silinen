<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Ambil id_linen dari parameter GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
            
            // Validasi form hanya angka
            $(".angka-only").on("input", function() {
                this.value = this.value.replace(/[^0-9]/g, "");
            });
            
            // Validasi tidak boleh 0
            $(".modal form").on("submit", function(e) {
                var jumlah = $(this).find("input[type=\'number\']").val();
                if(jumlah <= 0) {
                    e.preventDefault();
                    alert("Jumlah harus lebih dari 0!");
                    return false;
                }
            });
        });
        </script>';

// Judul halaman dan Deskripsi Halaman
$pageTitle = "Detail Distribusi Laundry";
$pageDesc = "Data Detail Distribusi Laundry";
$_SESSION['active_menu'] = 'distribusi';

$ruanganQuery = mysqli_query($koneksi, "SELECT * FROM ruangan WHERE id='$id'");
$ruanganData = mysqli_fetch_assoc($ruanganQuery);

$linenRuangan = mysqli_query($koneksi, "SELECT 
            lr.*,
            u.nama as admin_ruangan,
            r.nama_ruangan,
            l.nama_linen,
            l.id as id_linen,
            l.gambar
          FROM linen_ruangan lr
          LEFT JOIN users u ON lr.id_user = u.id
          LEFT JOIN ruangan r ON lr.id_ruangan = r.id
          LEFT JOIN linen l ON lr.id_linen = l.id
          WHERE id_ruangan = '$id'");
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
                } elseif ($_GET['pesan'] == "gagalkotor") {
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Gagal!</strong> Gagal melakukan perubahan pada Linen Kotor!
            </div>';
                } elseif ($_GET['pesan'] == "gagalcadangan") {
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Gagal!</strong> Gagal melakukan perubahan pada Linen Cadangan!
            </div>';
                }
            }
            ?>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-list"></i> Daftar Linen di Ruangan <b><?= $ruanganData['nama_ruangan']; ?></b>
                    </h3>
                </div>
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Linen</th>
                                <th>Gambar Linen</th>
                                <th>Linen Terpakai</th>
                                <th>Linen Cadangan</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            while ($data = mysqli_fetch_assoc($linenRuangan)):
                                // Cek apakah ada gambar
                                $gambar_path = !empty($data['gambar']) ? '../uploads/linen/' . $data['gambar'] : '../assets/img/no-image.jpg';
                                $gambar_exists = !empty($data['gambar']) && file_exists($gambar_path);
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
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
                                    <td><?= htmlspecialchars($data['linen_terpakai']) ?> Pcs</td>
                                    <td><?= htmlspecialchars($data['linen_cadangan']) ?> Pcs</td>
                                    <td>
                                        <?php if (!empty($data['id_user'])): ?>
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#modalDistribusi<?= $data['id'] ?>">
                                                Linen Kotor <i class="fa fa-arrow-right"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                                data-target="#modalCadangan<?= $data['id'] ?>">
                                                Linen Cadangan <i class="fa fa-arrow-right"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-primary" disabled>Distribusi <i class="fa fa-arrow-right"></i></button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
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
</section>

<?php
// Reset pointer query untuk modal
mysqli_data_seek($linenRuangan, 0);
$no = 1;
while ($data = mysqli_fetch_assoc($linenRuangan)):
    if (!empty($data['id_user'])):
?>

<!-- MODAL DISTRIBUSI LINEN KOTOR -->
<div class="modal fade" id="modalDistribusi<?= $data['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalDistribusiLabel<?= $data['id'] ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDistribusiLabel<?= $data['id'] ?>">
                    <i class="fa fa-upload"></i> Distribusi Linen Kotor
                </h4>
            </div>
            <form action="proses_distribusi_linen_kotor.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_linen_ruangan" value="<?= $data['id'] ?>">
                    <input type="hidden" name="id_ruangan" value="<?= $id ?>">
                    <input type="hidden" name="id_linen" value="<?= $data['id_linen'] ?>">
                    
                    <div class="alert alert-info">
                        <strong>Info:</strong> Anda akan mendistribusikan <b><?= htmlspecialchars($data['nama_linen']); ?></b> dari ruangan <b><?= htmlspecialchars($data['nama_ruangan']) ?></b>
                    </div>
                    
                    <div class="form-group">
                        <label for="jumlah_kotor<?= $data['id'] ?>">Jumlah Linen Kotor</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                            <input type="number" 
                                   class="form-control angka-only" 
                                   id="jumlah_kotor<?= $data['id'] ?>" 
                                   name="jumlah_kotor" 
                                   placeholder="Masukkan jumlah linen kotor"
                                   min="1"
                                   max="<?= $data['linen_terpakai'] ?>"
                                   required>
                            <span class="input-group-addon">Pcs</span>
                        </div>
                        <small class="text-muted">
                            Maksimal: <?= $data['linen_terpakai'] ?> Pcs (Linen terpakai tersedia)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i> Kirim Linen Kotor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DISTRIBUSI LINEN CADANGAN -->
<div class="modal fade" id="modalCadangan<?= $data['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalCadanganLabel<?= $data['id'] ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalCadanganLabel<?= $data['id'] ?>">
                    <i class="fa fa-exchange"></i> Distribusi Linen Cadangan
                </h4>
            </div>
            <form action="proses_distribusi_linen_cadangan.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_linen_ruangan" value="<?= $data['id'] ?>">
                    <input type="hidden" name="id_ruangan" value="<?= $id ?>">
                    <input type="hidden" name="id_linen" value="<?= $data['id_linen'] ?>">
                    
                    <div class="alert alert-warning">
                        <strong>Perhatian:</strong> Anda akan menggunakan <b><?= htmlspecialchars($data['nama_linen']); ?></b> cadangan dari ruangan <b><?= htmlspecialchars($data['nama_ruangan']) ?></b>
                    </div>
                    
                    <div class="form-group">
                        <label for="jumlah_cadangan<?= $data['id'] ?>">Jumlah Linen Cadangan</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
                            <input type="number" 
                                   class="form-control angka-only" 
                                   id="jumlah_cadangan<?= $data['id'] ?>" 
                                   name="jumlah_cadangan" 
                                   placeholder="Masukkan jumlah linen cadangan"
                                   min="1"
                                   max="<?= $data['linen_cadangan'] ?>"
                                   required>
                            <span class="input-group-addon">Pcs</span>
                        </div>
                        <small class="text-muted">
                            Maksimal: <?= $data['linen_cadangan'] ?> Pcs (Linen cadangan tersedia)
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" name="submit" class="btn btn-success">
                        <i class="fa fa-paper-plane"></i> Kirim Linen Cadangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
    endif;
    $no++;
endwhile;
?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/header.php';
echo $content;
include __DIR__ . '/../layouts/footer.php';
?>