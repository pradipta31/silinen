<?php
include '../koneksi.php';
if (isset($_POST['submit'])) {
    $id_user = $_POST['id_user'];
    $id_ruangan = $_POST['id_ruangan'];
    $id_linen = $_POST['id_linen'];
    $linen_terpakai = $_POST['linen_terpakai'];
    $linen_cadangan = $_POST['linen_cadangan'];
    $total_linen = 0;

    $checkLinen = mysqli_query($koneksi, "SELECT * FROM linen WHERE id='$id_linen'");
    $sisa_linen = 0;
    $rLinen = mysqli_fetch_assoc($checkLinen);
    $sisa_linen = $rLinen['sisa_linen'];

    $query = "INSERT INTO linen_ruangan (id_user, id_ruangan, id_linen, linen_terpakai, linen_cadangan) 
          VALUES ('$id_user', '$id_ruangan', '$id_linen', '$linen_terpakai', '$linen_cadangan')";


    if (mysqli_query($koneksi, $query)) {
        $total_linen = $linen_terpakai + $linen_cadangan;
        $linen_baru = $sisa_linen - $total_linen;
        $qLinen = "UPDATE linen SET sisa_linen='$linen_baru' WHERE id='$id_linen'";
        if (mysqli_query($koneksi,$qLinen)) {
            header("Location: detail_linen.php?id_linen=$id_linen&pesan=berhasil");
            exit();
        }else{
            $error_detail = mysqli_error($koneksi);
            header("Location: detail_linen.php?id_linen=$id_linen&pesan=error&detail=" . urlencode("Gagal menyimpan data: " . $error_detail));
            exit();
        }
    } else {
        $error_detail = mysqli_error($koneksi);
        header("Location: detail_linen.php?id_linen=$id_linen&pesan=error&detail=" . urlencode("Gagal menyimpan data: " . $error_detail));
        exit();
    }
}
