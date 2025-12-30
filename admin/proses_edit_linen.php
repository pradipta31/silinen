<?php
session_start();
include '../koneksi.php';

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    
    // Ambil data dari form
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $kode_linen = mysqli_real_escape_string($koneksi, $_POST['kode_linen']);
    $nama_linen = mysqli_real_escape_string($koneksi, $_POST['nama_linen']);
    $jumlah_linen = mysqli_real_escape_string($koneksi, $_POST['jumlah_linen']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);
    $gambar_lama = mysqli_real_escape_string($koneksi, $_POST['gambar_lama']);
    $hapus_gambar = isset($_POST['hapus_gambar']) ? 1 : 0;
    
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
    
    // Cek apakah kode linen sudah ada (kecuali untuk data ini sendiri)
    $checkQuery = "SELECT * FROM linen WHERE kode_linen = '$kode_linen' 
                   AND id != '$id'";
    $checkResult = mysqli_query($koneksi, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $errors[] = "Kode Linen '$kode_linen' sudah digunakan di ruangan ini";
    }
    
    // Proses gambar
    $nama_file = $gambar_lama;
    
    // Jika hapus gambar dipilih
    if ($hapus_gambar == 1) {
        // Hapus file gambar lama jika ada
        if (!empty($gambar_lama)) {
            $old_file_path = '../uploads/linen/' . $gambar_lama;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
            $nama_file = ''; // Kosongkan nama file
        }
    }
    
    // Jika ada upload gambar baru
    if (!empty($_FILES['gambar']['name']) && $_FILES['gambar']['error'] == 0) {
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_type = $_FILES['gambar']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validasi ekstensi file
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        
        if (!in_array($file_ext, $allowed_extensions)) {
            $errors[] = "Format file tidak didukung. Hanya file JPG, JPEG, PNG, GIF yang diizinkan";
        }
        
        // Validasi ukuran file (max 2MB)
        if ($file_size > 2097152) {
            $errors[] = "Ukuran file terlalu besar. Maksimal 2MB";
        }
        
        // Jika validasi berhasil, proses upload
        if (empty($errors)) {
            // Hapus file gambar lama jika ada dan ada gambar baru
            if (!empty($gambar_lama)) {
                $old_file_path = '../uploads/linen/' . $gambar_lama;
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
            
            // Generate nama file baru
            $nama_file = time() . '_' . uniqid() . '.' . $file_ext;
            $upload_path = '../uploads/linen/';
            
            // Buat folder jika belum ada
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }
            
            // Pindahkan file ke folder upload
            if (!move_uploaded_file($file_tmp, $upload_path . $nama_file)) {
                $errors[] = "Gagal mengupload gambar baru";
                $nama_file = $gambar_lama; // Kembalikan ke gambar lama jika gagal
            }
        }
    }
    
    // Jika ada error, redirect kembali dengan pesan error
    if (!empty($errors)) {
        $error_message = implode("<br>", $errors);
        header("Location: edit_linen.php?id=$id&pesan=error&detail=" . urlencode($error_message));
        exit();
    }
    
    // Update data di database
    $updated_at = date('Y-m-d H:i:s');
    
    $query = "UPDATE linen SET 
              kode_linen = '$kode_linen',
              nama_linen = '$nama_linen',
              gambar = '$nama_file',
              jumlah_linen = '$jumlah_linen',
              status = '$status',
              updated_at = '$updated_at'
              WHERE id = '$id'";
    
    // Eksekusi query update
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, redirect ke halaman data linen
        header("Location: data_linen.php?&pesan=berhasil");
        exit();
    } else {
        // Jika gagal, tampilkan error
        $error_detail = mysqli_error($koneksi);
        header("Location: edit_linen.php?id=$id&pesan=error&detail=" . urlencode("Gagal menyimpan perubahan: " . $error_detail));
        exit();
    }
    
} else {
    // Jika tidak ada POST, redirect ke halaman data linen
    header("Location: data_linen.php?pesan=error&detail=" . urlencode("Akses tidak valid"));
    exit();
}
?>