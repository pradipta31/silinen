<?php
session_start();
include '../koneksi.php';

// Cek apakah user sudah login
if(!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

        $dataQuery = mysqli_query($koneksi, "SELECT 
            dl.*,
            lr.id_linen as id_linen,
            l.nama_linen,
            l.sisa_linen,
            r.nama_ruangan
            FROM distribusi_linen dl
            INNER JOIN linen_ruangan lr ON dl.id_linen_ruangan = lr.id
            INNER JOIN ruangan r ON lr.id_ruangan = r.id
            INNER JOIN linen l ON lr.id_linen = l.id
            WHERE dl.id = '$id'");
        $row = mysqli_fetch_assoc($dataQuery);
        $id_linen = $row['id_linen'];
        $jumlah_linen = 0;
        $jumlah_linen = $row['jumlah'];

        $sisa_linen = $row['sisa_linen'];

        $total = $jumlah_linen + $sisa_linen;
    
    // Update status menjadi 2 (Proses)
    $query = mysqli_query($koneksi, "UPDATE distribusi_linen SET status = 3 WHERE id = '$id'");
    
    
    if($query) {
        $queryUpdateLinen = mysqli_query($koneksi, "UPDATE linen SET sisa_linen = '$total' WHERE id = '$id_linen'");

        // Redirect dengan pesan sukses
        header("Location: data_linen_kotor.php?pesan=berhasil");
        exit();
    } else {
        // Redirect dengan pesan gagal
        header("Location: data_linen_kotor.php?pesan=gagal&error=" . urlencode(mysqli_error($koneksi)));
        exit();
    }
} else {
    // Jika tidak ada ID, redirect ke halaman utama
    header("Location: data_linen_kotor.php");
    exit();
}
?>