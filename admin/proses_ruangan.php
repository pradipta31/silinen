<?php
    include '../koneksi.php';
    if (isset($_POST['submit'])) {
        $admin_baru = $_POST['token_ruangan'];
        if ($admin_baru == 'baru') {
            $id_ruangan = $_POST['id_ruangan'];
            $id_user = $_POST['id_user'];
            $query = mysqli_query($koneksi, "UPDATE ruangan SET id_user = '$id_user' WHERE id = '$id_ruangan'");
            if($query){
                $query1 = mysqli_query($koneksi, "UPDATE users SET status_ruangan = 1 WHERE id = '$id_user'");
                header('location: data_ruangan.php?pesan=berhasil');
            }else{
                header('location: data_ruangan.php?pesan=gagal');
            }
        }else{
            $id_ruangan = $_POST['id_ruangan'];
            $nama_ruangan = $_POST['nama_ruangan'];
            $telp_ruangan = $_POST['telp_ruangan'];
            $status = $_POST['status'];
            $query = mysqli_query($koneksi, "UPDATE ruangan SET nama_ruangan = '$nama_ruangan', telp_ruangan = '$telp_ruangan', status = '$status' WHERE id = '$id_ruangan'");
            if ($query) {
                header('location: data_ruangan.php?pesan=berhasil');
            }else{
                header('location: data_ruangan.php?pesan=gagal');
            }
            
        }
    }

    if ($_GET['hapus_admin_ruangan']) {
        $id_ruangan = $_GET['hapus_admin_ruangan'];
        $q = mysqli_query($koneksi, "SELECT * FROM ruangan WHERE id=$id_ruangan");
        $row = mysqli_fetch_assoc($q);
        $id_user = $row['id_user'];
        $query = mysqli_query($koneksi, "UPDATE ruangan SET id_user = NULL WHERE id=$id_ruangan");
        if ($query) {
            $query1 = mysqli_query($koneksi, "UPDATE users SET status_ruangan = 0 WHERE id = $id_user");
            header('location: data_ruangan.php?pesan=berhasil');
        }else{
            header('location: data_ruangan.php?pesan=gagal');
        }
    }
?>