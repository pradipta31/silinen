<?php
    session_start();
    include '../koneksi.php';

    $id = $_GET['id'];
    $query = mysqli_query($koneksi, "SELECT * FROM pengajuan WHERE id='$id'");
    $row = mysqli_fetch_assoc($query);
    $jumlah = 0;
    $jumlah = $row['jumlah'];
    $id_linen = $row['id_linen'];
    $id_ruangan = $row['id_ruangan'];
    $tanggal = date('Y-m-d H:i:s');

    $queryLinen = mysqli_query($koneksi, "SELECT * FROM linen WHERE id = '$id_linen'");
    $rowLinen = mysqli_fetch_assoc($queryLinen);
    $sisa = 0;
    $total = 0;
    $sisa = $rowLinen['sisa_linen'];
    $total = $sisa - $jumlah;
    
    $queryUpdateLinen = mysqli_query($koneksi, "UPDATE linen SET sisa_linen = '$total' WHERE id='$id_linen'");
    $queryLinenRuangan = mysqli_query($koneksi, "INSERT INTO linen_ruangan (id_ruangan, id_linen, jumlah_linen, tanggal, status) VALUES 
    ('$id_ruangan', '$id_linen', '$jumlah', '$tanggal', 1)");

    $query = mysqli_query($koneksi, "UPDATE pengajuan SET status = 3 WHERE id = '$id'");

    if ($query) {
        header("Location: data_pengajuan.php?pesan=berhasil");
    }else{
        header("Location: data_pengajuan.php?pesan=gagal");
    }
