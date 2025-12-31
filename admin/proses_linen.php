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
    
    // Update status menjadi 2 (Proses)
    $query = mysqli_query($koneksi, "UPDATE distribusi_linen SET status = 2 WHERE id = '$id'");
    
    if($query) {
        // Ambil data untuk log atau notifikasi
        $dataQuery = mysqli_query($koneksi, "SELECT 
            dl.*,
            l.nama_linen,
            r.nama_ruangan
            FROM distribusi_linen dl
            INNER JOIN linen_ruangan lr ON dl.id_linen_ruangan = lr.id
            INNER JOIN ruangan r ON lr.id_ruangan = r.id
            INNER JOIN linen l ON lr.id_linen = l.id
            WHERE dl.id = '$id'");
        $data = mysqli_fetch_assoc($dataQuery);
        
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