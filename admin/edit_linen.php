<?php
session_start();
include '../koneksi.php';
$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_assoc($query);

// Ambil ID linen dari parameter GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Jika tidak ada ID, redirect
if ($id == 0) {
    header("Location: data_linen.php?pesan=error&detail=ID tidak valid");
    exit();
}

// Ambil data linen yang akan diedit
$queryLinen = mysqli_query($koneksi, "SELECT * FROM linen WHERE id = $id");

if (mysqli_num_rows($queryLinen) == 0) {
    header("Location: data_linen.php?pesan=error&detail=Data linen tidak ditemukan");
    exit();
}

$linenData = mysqli_fetch_assoc($queryLinen);


// Judul halaman dan Deskripsi Halaman
$pageTitle = "Edit Linen";
$pageDesc = "Ubah Data Linen";
$_SESSION['active_menu'] = 'linen';

// CSS Tambahan untuk halaman ini
$additionalCSS = [
    '../assets/plugins/datatables/dataTables.bootstrap.css',
    '../assets/plugins/select2/select2.min.css',
    '../assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    '../assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css'
];

// JS Tambahan untuk halaman ini
$additionalJS = [
    '../assets/plugins/jQuery/jQuery-2.1.4.min.js',
    '../assets/plugins/select2/select2.full.min.js',
    '../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    '../assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js'
];

// Inline Javascript
$inlineJS = '<script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Initialize Select2
            $(".select2").select2();
            
            // Preview gambar sebelum upload
            $("#gambar").change(function() {
                readURL(this);
                $("#currentImageSection").slideUp();
                $("#newImagePreview").slideDown();
            });
            
            // Fungsi untuk preview gambar
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        $("#newImagePreview img").attr("src", e.target.result);
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                }
            }
            
            // Toggle untuk hapus gambar
            $("#hapusGambar").change(function() {
                if($(this).is(":checked")) {
                    $("#currentImageSection").slideUp();
                    $("#newImagePreview").slideUp();
                    $("#gambar").prop("disabled", true);
                } else {
                    $("#currentImageSection").slideDown();
                    $("#gambar").prop("disabled", false);
                }
            });
        });
        </script>';

ob_start();
?>
<style>
/* Style untuk preview gambar */
.image-preview-container {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background: #f9f9f9;
    transition: all 0.3s;
}

.image-preview-container:hover {
    border-color: #3c8dbc;
    background: #f0f8ff;
}

.current-image {
    max-width: 100%;
    max-height: 200px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.new-image-preview {
    max-width: 100%;
    max-height: 200px;
    border-radius: 5px;
    border: 2px solid #5cb85c;
}

.image-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #5cb85c;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
}

.image-actions {
    margin-top: 10px;
}

.info-box {
    background: #f8f9fa;
    border-left: 4px solid #3c8dbc;
    padding: 15px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.info-box i {
    color: #3c8dbc;
    margin-right: 10px;
}

.form-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    margin-bottom: 20px;
    border: 1px solid #eaeaea;
}

.form-section-title {
    border-bottom: 2px solid #3c8dbc;
    padding-bottom: 10px;
    margin-bottom: 20px;
    color: #3c8dbc;
}

.required-field::after {
    content: " *";
    color: #d9534f;
}
</style>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($_GET['pesan'])) {
                if ($_GET['pesan'] == "berhasil") {
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Berhasil!</strong> Data berhasil diperbarui!
                    </div>';
                } elseif ($_GET['pesan'] == "error") {
                    $detail_error = isset($_GET['detail']) ? $_GET['detail'] : 'Terjadi kesalahan';
                    echo '<div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Error!</strong><br>' . htmlspecialchars($detail_error) . '
                        </div>';
                }
            }
            ?>
        </div>
        
        <div class="col-md-8">
            <!-- Info Box -->
            <div class="info-box">
                <i class="fa fa-info-circle fa-2x"></i>
                <div style="display: inline-block; vertical-align: top;">
                    <h4 style="margin: 0 0 5px 0;">Edit Data Linen</h4>
                    <p style="margin: 0; color: #666;">
                        Anda sedang mengedit data linen <strong><?= htmlspecialchars($linenData['nama_linen']) ?></strong> 
                    </p>
                </div>
            </div>
            
            <!-- Form Edit Linen -->
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="fa fa-edit"></i> Form Edit Data Linen
                </h4>
                
                <form role="form" action="proses_edit_linen.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $linenData['id'] ?>">
                    <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($linenData['gambar']) ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required-field">Kode Linen</label>
                                <input type="text" class="form-control" name="kode_linen" 
                                       value="<?= htmlspecialchars($linenData['kode_linen']) ?>" 
                                       placeholder="Masukkan Kode Linen" required
                                       oninput="checkKodeLinen(this.value)">
                                <small class="text-muted" id="kodeInfo">Contoh: L001</small>
                                <small class="text-success" id="kodeAvailable" style="display: none;">
                                    <i class="fa fa-check"></i> Kode tersedia
                                </small>
                                <small class="text-danger" id="kodeNotAvailable" style="display: none;">
                                    <i class="fa fa-warning"></i> Kode sudah digunakan
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required-field">Nama Linen</label>
                                <input type="text" class="form-control" name="nama_linen" 
                                       value="<?= htmlspecialchars($linenData['nama_linen']) ?>" 
                                       placeholder="Masukkan Nama Linen" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required-field">Jumlah Linen</label>
                                <input type="number" class="form-control" name="jumlah_linen" 
                                       value="<?= $linenData['jumlah_linen'] ?>" 
                                       placeholder="Masukkan jumlah stok linen" min="1" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" style="width: 100%;">
                                    <option value="1" <?= ($linenData['status'] == 1) ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= ($linenData['status'] == 0) ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Section Gambar -->
                    <h5><i class="fa fa-image"></i> Pengaturan Gambar</h5>
                    <p class="text-muted" style="margin-bottom: 20px;">
                        Anda dapat mengganti gambar, menghapus gambar, atau membiarkan gambar tetap seperti sebelumnya.
                    </p>
                    
                    <div class="row">
                        <!-- Gambar Saat Ini -->
                        <div class="col-md-6">
                            <div id="currentImageSection" class="image-preview-container">
                                <h6>Gambar Saat Ini</h6>
                                <?php
                                $gambar_path = !empty($linenData['gambar']) ? '../uploads/linen/' . $linenData['gambar'] : '../assets/img/no-image.jpg';
                                $gambar_exists = !empty($linenData['gambar']) && file_exists($gambar_path);
                                ?>
                                <?php if($gambar_exists): ?>
                                    <div style="position: relative;">
                                        <img src="<?= $gambar_path ?>" class="current-image" 
                                             alt="<?= htmlspecialchars($linenData['nama_linen']) ?>">
                                        <span class="image-badge">Current</span>
                                    </div>
                                    <div class="image-actions">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="hapusGambar" name="hapus_gambar" value="1">
                                                <span class="text-danger">
                                                    <i class="fa fa-trash"></i> Hapus gambar ini
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div style="padding: 30px 0;">
                                        <i class="fa fa-image fa-4x text-muted"></i>
                                        <p class="text-muted" style="margin-top: 10px;">Tidak ada gambar</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Preview Gambar Baru -->
                        <div class="col-md-6">
                            <div id="newImagePreview" class="image-preview-container" style="display: none;">
                                <h6>Preview Gambar Baru</h6>
                                <div style="position: relative;">
                                    <img src="" class="new-image-preview" alt="Preview Gambar Baru">
                                    <span class="image-badge" style="background: #d9534f;">New</span>
                                </div>
                                <p class="text-success" style="margin-top: 10px;">
                                    <i class="fa fa-info-circle"></i> Gambar baru akan menggantikan gambar lama
                                </p>
                            </div>
                            
                            <!-- Input File -->
                            <div class="form-group" style="margin-top: 20px;">
                                <label>Upload Gambar Baru (Opsional)</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" 
                                       accept=".jpg,.jpeg,.png,.gif">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle"></i> Format: JPG, JPEG, PNG (Maksimal 2MB). 
                                    Jika tidak memilih file, gambar lama akan tetap digunakan.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Informasi Tanggal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Masuk</label>
                                <input type="text" class="form-control" 
                                       value="<?= date('d F Y H:i', strtotime($linenData['tanggal'])) ?>" 
                                       readonly style="background: #f5f5f5;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Terakhir Diupdate</label>
                                <input type="text" class="form-control" 
                                       value="<?= !empty($linenData['updated_at']) ? date('d F Y H:i', strtotime($linenData['updated_at'])) : 'Belum pernah diupdate' ?>" 
                                       readonly style="background: #f5f5f5;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="box-footer" style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="data_linen.php?id_ruangan=<?= $id_ruangan ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Simpan Perubahan
                                </button>
                                <button type="reset" class="btn btn-warning">
                                    <i class="fa fa-refresh"></i> Reset Form
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Panel Info -->
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-info-circle"></i> Informasi Editing
                    </h3>
                </div>
                <div class="panel-body">
                    <h5><i class="fa fa-lightbulb-o"></i> Tips Editing:</h5>
                    <ul>
                        <li>Pastikan kode linen tidak duplikat</li>
                        <li>Gambar baru akan menggantikan gambar lama</li>
                        <li>Cek jumlah linen sebelum menyimpan</li>
                        <li>Nonaktifkan linen jika sudah tidak digunakan</li>
                    </ul>
                    
                    <hr>
                    
                    <h5><i class="fa fa-history"></i> Data Saat Ini:</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Kode Linen</th>
                            <td><?= htmlspecialchars($linenData['kode_linen']) ?></td>
                        </tr>
                        <tr>
                            <th>Nama Linen</th>
                            <td><?= htmlspecialchars($linenData['nama_linen']) ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td><?= $linenData['jumlah_linen'] ?></td>
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
                    
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> 
                        <strong>Perhatian:</strong> Perubahan data tidak dapat dibatalkan setelah disimpan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Fungsi untuk mengecek ketersediaan kode linen (AJAX)
function checkKodeLinen(kode) {
    if(kode.length < 2) return;
    
    var currentKode = '<?= $linenData['kode_linen'] ?>';
    
    // Jika kode sama dengan yang lama, tidak perlu dicek
    if(kode === currentKode) {
        document.getElementById('kodeInfo').style.display = 'block';
        document.getElementById('kodeAvailable').style.display = 'none';
        document.getElementById('kodeNotAvailable').style.display = 'none';
        return;
    }
    
    // Kirim AJAX request untuk cek kode
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'cek_kode_linen.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            
            if(response.available) {
                document.getElementById('kodeInfo').style.display = 'none';
                document.getElementById('kodeAvailable').style.display = 'block';
                document.getElementById('kodeNotAvailable').style.display = 'none';
            } else {
                document.getElementById('kodeInfo').style.display = 'none';
                document.getElementById('kodeAvailable').style.display = 'none';
                document.getElementById('kodeNotAvailable').style.display = 'block';
                document.getElementById('kodeNotAvailable').innerHTML = 
                    '<i class="fa fa-warning"></i> ' + response.message;
            }
        }
    };
    
    xhr.send('kode_linen=' + encodeURIComponent(kode) + '&id_ruangan=<?= $id_ruangan ?>');
}

// Validasi sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    var kodeLinen = document.querySelector('input[name="kode_linen"]').value;
    var jumlahLinen = document.querySelector('input[name="jumlah_linen"]').value;
    var currentKode = '<?= $linenData['kode_linen'] ?>';
    
    // Validasi jumlah
    if(jumlahLinen < 1) {
        e.preventDefault();
        alert('Jumlah linen harus lebih dari 0');
        return false;
    }
    
    // Jika kode berubah, beri konfirmasi
    if(kodeLinen !== currentKode) {
        if(!confirm('Anda mengubah kode linen. Lanjutkan?')) {
            e.preventDefault();
            return false;
        }
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/header.php';
echo $content;
include __DIR__ . '/../layouts/footer.php';
?>