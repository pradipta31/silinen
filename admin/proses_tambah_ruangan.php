<?php
    include '../koneksi.php';
    if (isset($_POST['submit'])) {
        $nama_ruangan = $_POST['nama_ruangan'];
        $telp_ruangan = $_POST['telp_ruangan'];
        $status = $_POST['status'];

        $query = mysqli_query($koneksi, "INSERT INTO ruangan (nama_ruangan, telp_ruangan, status) VALUES ('$nama_ruangan', '$telp_ruangan', '$status')");
        if ($query) {
            header('location: tambah_ruangan.php?pesan=berhasil');
        } else {
            header('location: tambah_ruangan.php?pesan=gagal');
        }
    }
?>