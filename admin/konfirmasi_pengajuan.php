<?php
    session_start();
    include '../koneksi.php';

    $id = $_GET['id'];

    $query = mysqli_query($koneksi, "UPDATE pengajuan SET status = 2 WHERE id = '$id'");

    if ($query) {
        header("Location: data_pengajuan.php?pesan=berhasil");
    }else{
        header("Location: data_pengajuan.php?pesan=gagal");
    }
