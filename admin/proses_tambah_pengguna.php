<?php
include '../koneksi.php';

if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hak_akses = $_POST['hak_akses'];
    $status = 1;

    // Validasi field kosong
    $error = array();

    if (empty($nama)) {
        $error[] = "Nama harus diisi";
    }
    if (empty($username)) {
        $error[] = "Username harus diisi";
    }
    if (empty($email)) {
        $error[] = "Email harus diisi";
    }
    if (empty($password)) {
        $error[] = "Password harus diisi";
    }
    if (empty($hak_akses)) {
        $error[] = "Hak akses harus dipilih";
    }

    // Jika tidak ada error, proses insert
    if (empty($error)) {
        $password = md5($password);

        $query = mysqli_query($koneksi, "INSERT INTO users(nama, username, email, password, hak_akses, status) 
            VALUES ('$nama', '$username', '$email', '$password', '$hak_akses', '$status')");

        if ($query) {
            header('location: data_pengguna.php?pesan=berhasil');
        } else {
            header('location: tambah_pengguna.php?pesan=gagal');
        }
    } else {
        // Jika ada error, kembali ke form dengan pesan error
        $pesan_error = implode("<br>", $error);
        header("location: tambah_pengguna.php?pesan=error&detail=$pesan_error");
    }
}
