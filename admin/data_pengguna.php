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
$pageTitle = "Pengguna";
$pageDesc = "Data Pengguna";
$_SESSION['active_menu'] = 'pengguna';

// Query untuk mengambil data ke database
$dataQuery = mysqli_query($koneksi, "SELECT * FROM users");

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
          }elseif ($_GET['pesan'] == "gagal") {
            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Gagal!</strong> Gagal input data baru!
            </div>';
          }
        }
        ?>
      <div class="box">
        <div class="box-header">
          <a href="tambah_pengguna.php" class="btn btn-primary btn-md"> Tambah Pengguna</a>
        </div>
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Hak Akses</th>
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
                  <td><?= htmlspecialchars($data['username']) ?></td>
                  <td><?= htmlspecialchars($data['nama']) ?></td>
                  <td><?= htmlspecialchars($data['email']) ?></td>
                  <td>
                    <span class="label <?=
                                        ($data['hak_akses'] == 'admin') ? 'label-primary' : (($data['hak_akses'] == 'admin_ruangan') ? 'label-info' : (($data['hak_akses'] == 'petugas_laundry') ? 'label-warning' : 'label-default'))
                                        ?>">
                      <?= htmlspecialchars($data['hak_akses']) ?>
                    </span>
                  </td>
                  <td>
                    <span class="label <?= ($data['status'] == 1) ? 'label-success' : 'label-danger' ?>">
                      <?= ($data['status'] == 1) ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                  </td>
                  <td>
                    <a href="edit_pengguna.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i> Edit</a>
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