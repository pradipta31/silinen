<?php
include '../koneksi.php';

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $id_ruangan = $_POST['id_ruangan'];
    $id_linen = $_POST['id_linen'];
    $jumlah = $_POST['jumlah'];
    $tanggal = date('Y-m-d H:i:s');
    $keterangan = $_POST['keterangan'];
    $status = 1;

    // Validasi field kosong
    $error = array();

    if (empty($id_linen)) {
        $error[] = "Silahkan pilih linen terlebih dahulu!";
    }
    if (empty($jumlah)) {
        $error[] = "Jumlah harus diisi";
    }
    if (empty($keterangan)) {
        $error[] = "Keterangan harus diisi";
    }

    // Jika tidak ada error, proses insert
    if (empty($error)) {
        $query = mysqli_query($koneksi, "INSERT INTO pengajuan (id_linen, id_ruangan, jumlah, tanggal, keterangan, status) VALUES 
        ('$id_linen', '$id_ruangan', '$jumlah', '$tanggal', '$keterangan', '$status')");
        

        if ($query) {
            header('location: data_pengajuan.php?pesan=berhasil');
        } else {
            header('location: tambah_pengajuan.php?pesan=gagal');
        }
    } else {
        // Jika ada error, kembali ke form dengan pesan error
        $pesan_error = implode("<br>", $error);
        header("location: tambah_pengajuan.php?pesan=error&detail=$pesan_error");
    }
}

?>
