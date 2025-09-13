<?php
include '../koneksi.php';

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hak_akses = $_POST['hak_akses'];
    $status = $_POST['status'];

    if ($id) { // Edit pengguna
        if (!empty($password)) {
            $password_hash = md5($password);
            $query = mysqli_query($koneksi,
                    "UPDATE users SET 
                    nama = '$nama', 
                    username = '$username', 
                    email = '$email', 
                    password = '$password_hash', 
                    hak_akses = '$hak_akses', 
                    status = '$status' 
                    WHERE id = '$id'");
        } else {
            $query = mysqli_query($koneksi,
                    "UPDATE users SET 
                    nama = '$nama', 
                    username = '$username', 
                    email = '$email', 
                    hak_akses = '$hak_akses', 
                    status = '$status' 
                    WHERE id = '$id'");
        }
    }
    if ($query) {
        header('location: data_pengguna.php?pesan=berhasil');
    } else {
        header('location: edit_pengguna.php?pesan=gagal');
    }
}
