<?php
    session_start();
    include '../koneksi.php';

    $id = $_GET['id'];

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // Ambil data distribusi_linen termasuk jumlah dan id_linen_ruangan
        $query_get = mysqli_query($koneksi, "SELECT jumlah, id_linen_ruangan FROM distribusi_linen WHERE id = '$id'");
        $data = mysqli_fetch_assoc($query_get);

        if ($data) {
            $jumlah = $data['jumlah'];
            $id_linen_ruangan = $data['id_linen_ruangan'];

            // Update status distribusi_linen ke 2
            $query_update_status = mysqli_query($koneksi, "UPDATE distribusi_linen SET status = 2 WHERE id = '$id'");

            // Kurangi jumlah_linen di tabel linen_ruangan
            $query_update_linen_ruangan = mysqli_query($koneksi, "UPDATE linen_ruangan SET jumlah_linen = GREATEST(jumlah_linen - $jumlah, 0) WHERE id = '$id_linen_ruangan'");

            if ($query_update_status && $query_update_linen_ruangan) {
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
