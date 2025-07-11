<?php
    if (isset($_POST['submit'])) {
        session_start();
        include 'koneksi.php';
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");
        $sql = mysqli_num_rows($query);
        $row = mysqli_fetch_assoc($query);
        if($sql > 0){
            $_SESSION['username'] = $username;
            $hak_akses = $row['hak_akses'];
            if($hak_akses == 'admin'){
                $_SESSION['status'] = 'login';
                header('location: admin/');
            }else if($hak_akses == 'admin_ruangan'){
                $_SESSION['status'] = 'login';
                header('location: admin_ruangan/');
            }
        }else{
            header('location: index.php?pesan=gagal');
        }

    }

?>