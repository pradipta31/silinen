<?php
    $koneksi = mysqli_connect('localhost', 'root', '', 'silinen');
    if(!$koneksi){
        echo "Koneksi Gagal!";
    }
?>