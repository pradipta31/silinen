<?php
session_start();
include '../koneksi.php';

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    
    // Ambil data dari form
    $kode_linen = mysqli_real_escape_string($koneksi, $_POST['kode_linen']);
    $nama_linen = mysqli_real_escape_string($koneksi, $_POST['nama_linen']);
    $jumlah_linen = mysqli_real_escape_string($koneksi, $_POST['jumlah_linen']);
    
    // Validasi input
    $errors = [];
    
    if (empty($kode_linen)) {
        $errors[] = "Kode Linen harus diisi";
    }
    
    if (empty($nama_linen)) {
        $errors[] = "Nama Linen harus diisi";
    }
    
    if (empty($jumlah_linen) || $jumlah_linen <= 0) {
        $errors[] = "Jumlah Linen harus diisi dan lebih dari 0";
    }
    
    // Cek apakah kode linen sudah ada di ruangan ini
    $checkQuery = "SELECT * FROM linen WHERE kode_linen = '$kode_linen'";
    $checkResult = mysqli_query($koneksi, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $errors[] = "Kode Linen sudah terdaftar!";
    }
    
    // Proses upload gambar jika ada
    $nama_file = '';
    $upload_success = true;
    $upload_message = '';
    
    if (!empty($_FILES['gambar']['name'])) {
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_type = $_FILES['gambar']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validasi ekstensi file
        $allowed_extensions = array("jpg", "jpeg", "png");
        
        if (!in_array($file_ext, $allowed_extensions)) {
            $errors[] = "Format file tidak didukung. Hanya file JPG, JPEG, PNG yang diizinkan";
            $upload_success = false;
        }
        
        // Validasi ukuran file (max 2MB)
        if ($file_size > 2097152) {
            $errors[] = "Ukuran file terlalu besar. Maksimal 2MB";
            $upload_success = false;
        }
        
        // Jika validasi berhasil, proses upload
        if ($upload_success) {
            $nama_file = time() . '_' . uniqid() . '.' . $file_ext;
            $upload_path = '../uploads/linen/';
            
            // Buat folder jika belum ada
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            
            // Pindahkan file ke folder upload
            if (move_uploaded_file($file_tmp, $upload_path . $nama_file)) {
                $upload_message = "Gambar berhasil diupload";
            } else {
                $errors[] = "Gagal mengupload gambar";
                $upload_success = false;
            }
        }
    }
    
    // Jika ada error, redirect kembali dengan pesan error
    if (!empty($errors)) {
        $error_message = implode("<br>", $errors);
        header("Location: tambah_linen.php?pesan=error&detail=" . urlencode($error_message));
        exit();
    }
    
    // Siapkan data untuk insert
    $tanggal = date('Y-m-d H:i:s');
    
    // Query untuk insert data
    $query = "INSERT INTO linen (kode_linen, nama_linen, gambar, jumlah_linen, sisa_linen, tanggal, status) 
              VALUES ('$kode_linen', '$nama_linen', '$nama_file', '$jumlah_linen', '$jumlah_linen', '$tanggal', 1)";
    
    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, redirect ke halaman data linen
        header("Location: data_linen.php?pesan=berhasil");
        exit();
    } else {
        // Jika gagal, tampilkan error
        $error_detail = mysqli_error($koneksi);
        header("Location: tambah_linen.php?pesan=error&detail=" . urlencode("Gagal menyimpan data: " . $error_detail));
        exit();
    }
    
} else {
    // Jika tidak ada POST, redirect ke halaman tambah
    header("Location: tambah_linen.php?pesan=error&detail=" . urlencode("Akses tidak valid"));
    exit();
}
?>