<?php
    session_start();
    include '../koneksi.php';

    $id = $_GET['id'];

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // Ambil data pencucian termasuk jumlah dan id_linen
        $query_get = mysqli_query($koneksi, "SELECT p.jumlah, lr.id_linen 
                                             FROM pencucian p
                                             JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
                                             WHERE p.id = '$id'");
        $data = mysqli_fetch_assoc($query_get);

        if ($data) {
            $jumlah = $data['jumlah'];
            $id_linen = $data['id_linen'];

            // Update status pencucian ke 3
            $query_update_status = mysqli_query($koneksi, "UPDATE pencucian SET status = 3 WHERE id = '$id'");

            // Tambah sisa_linen di tabel linen
            $query_update_linen = mysqli_query($koneksi, "UPDATE linen SET sisa_linen = sisa_linen + $jumlah WHERE id = '$id_linen'");

            if ($query_update_status && $query_update_linen) {
                mysqli_commit($koneksi);
                header("Location: data_pencucian.php?pesan=berhasil");
            } else {
                throw new Exception("Gagal update data");
            }
        } else {
            throw new Exception("Data tidak ditemukan");
        }
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        header("Location: data_pencucian.php?pesan=gagal");
    }