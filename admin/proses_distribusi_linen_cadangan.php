<?php
include '../koneksi.php';
if (isset($_POST['submit'])) {
    $id_linen_ruangan = $_POST['id_linen_ruangan'];
    $id_linen = $_POST['id_linen'];
    $id_ruangan = $_POST['id_ruangan'];
    $jumlah_linen = $_POST['jumlah_cadangan'];

    $qLinenRuangan = mysqli_query($koneksi, "SELECT * FROM linen_ruangan WHERE id = '$id_linen_ruangan'");
    $rLinenRuangan = mysqli_fetch_assoc($qLinenRuangan);
    $linen_terpakai = 0;
    $linen_terpakai = $rLinenRuangan['linen_terpakai'];
    $linen_cadangan = 0;
    $linen_cadangan = $rLinenRuangan['linen_cadangan'];

    $total_linen_cadangan = 0;
    $total_linen_cadangan = $linen_cadangan - $jumlah_linen;

    $total_linen_terpakai = 0;
    $total_linen_terpakai = $linen_terpakai + $jumlah_linen;

    $qUpdate = "UPDATE linen_ruangan SET linen_terpakai = '$total_linen_terpakai', linen_cadangan = '$total_linen_cadangan' WHERE id = '$id_linen_ruangan'";

    if (mysqli_query($koneksi, $qUpdate)) {
        header("Location: detail_distribusi_laundry.php?id=$id_ruangan&pesan=berhasil");
        exit();
    } else {
        header("Location: detail_distribusi_laundry.php?id=$id_ruangan&pesan=gagalcadangan");
        exit();
    }
}
