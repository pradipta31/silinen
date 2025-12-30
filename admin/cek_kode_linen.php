<?php
session_start();
include '../koneksi.php';

// Hanya terima request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_linen = mysqli_real_escape_string($koneksi, $_POST['kode_linen']);
    $id_ruangan = mysqli_real_escape_string($koneksi, $_POST['id_ruangan']);
    $id = isset($_POST['id']) ? mysqli_real_escape_string($koneksi, $_POST['id']) : 0;
    
    // Query untuk cek kode linen
    $query = "SELECT COUNT(*) as count FROM linen 
              WHERE kode_linen = '$kode_linen' 
              AND id_ruangan = '$id_ruangan'";
    
    // Jika ada ID (edit mode), exclude data saat ini
    if ($id > 0) {
        $query .= " AND id != '$id'";
    }
    
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    
    // Response JSON
    header('Content-Type: application/json');
    
    if ($row['count'] > 0) {
        echo json_encode([
            'available' => false,
            'message' => "Kode '$kode_linen' sudah digunakan di ruangan ini"
        ]);
    } else {
        echo json_encode([
            'available' => true,
            'message' => "Kode tersedia"
        ]);
    }
    
    exit();
}
?>