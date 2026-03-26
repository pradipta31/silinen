<?php
// export_excel.php
session_start();
include '../koneksi.php';

// Cek session login
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

// Ambil parameter tanggal dari GET
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-d');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

// Query untuk data laporan pengajuan
$dataQuery = mysqli_query($koneksi, "SELECT p.*, 
    l.id, l.nama_linen, l.kode_linen,
    r.nama_ruangan,
    u.nama as admin_ruangan
    FROM pengajuan p
    LEFT JOIN linen l ON p.id_linen = l.id
    LEFT JOIN ruangan r ON p.id_ruangan = r.id
    LEFT JOIN users u ON r.id_user = u.id
    WHERE DATE(p.tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY p.tanggal DESC");

// Query untuk statistik
$statistikQuery = mysqli_query($koneksi, "SELECT 
    COUNT(*) as total_pengajuan,
    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as baru_mengajukan,
    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as proses_pengantaran,
    SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as konfirmasi_diterima,
    SUM(jumlah) as total_linen
    FROM pengajuan 
    WHERE DATE(tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
$statistik = mysqli_fetch_assoc($statistikQuery);

// Set header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Pengajuan_Linen_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

// Buat tabel HTML untuk Excel
echo '<html>';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Laporan Pengajuan Linen</title>';
echo '<style>';
echo 'body { font-family: Arial, sans-serif; }';
echo 'table { border-collapse: collapse; width: 100%; }';
echo 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
echo 'th { background-color: #4CAF50; color: white; }';
echo '.header { margin-bottom: 20px; }';
echo '.title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 10px; }';
echo '.subtitle { text-align: center; margin-bottom: 20px; }';
echo '.statistik-box { margin-bottom: 20px; }';
echo '</style>';
echo '</head>';
echo '<body>';

// Header Laporan
echo '<div class="header">';
echo '<div class="title">LAPORAN PENGAJUAN LINEN</div>';
echo '<div class="subtitle">Periode: ' . date('d M Y', strtotime($tanggal_awal)) . ' - ' . date('d M Y', strtotime($tanggal_akhir)) . '</div>';
echo '<div class="subtitle">Tanggal Export: ' . date('d M Y H:i:s') . '</div>';
echo '</div>';

// Statistik Laporan
echo '<div class="statistik-box">';
echo '<h3>Ringkasan Statistik</h3>';
echo '<table style="width: 100%; margin-bottom: 20px;">';
echo '<tr style="background-color: #f2f2f2;">';
echo '<th style="background-color: #2196F3;">Total Pengajuan</th>';
echo '<th style="background-color: #FFC107;">Baru Mengajukan</th>';
echo '<th style="background-color: #17a2b8;">Proses Pengantaran</th>';
echo '<th style="background-color: #28a745;">Konfirmasi Diterima</th>';
echo '<th style="background-color: #6c757d;">Total Linen</th>';
echo '</tr>';
echo '<tr style="text-align: center;">';
echo '<td>' . number_format($statistik['total_pengajuan']) . '</td>';
echo '<td>' . number_format($statistik['baru_mengajukan']) . '</td>';
echo '<td>' . number_format($statistik['proses_pengantaran']) . '</td>';
echo '<td>' . number_format($statistik['konfirmasi_diterima']) . '</td>';
echo '<td>' . number_format($statistik['total_linen']) . ' pcs</td>';
echo '</tr>';
echo '</table>';
echo '</div>';

// Tabel Data Laporan
echo '<h3>Detail Pengajuan Linen</h3>';
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th>No</th>';
echo '<th>Tanggal</th>';
echo '<th>Ruangan</th>';
echo '<th>Admin Ruangan</th>';
echo '<th>Kode Linen</th>';
echo '<th>Nama Linen</th>';
echo '<th>Jumlah</th>';
echo '<th>Status</th>';
echo '<th>Keterangan</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if (mysqli_num_rows($dataQuery) > 0) {
    $no = 1;
    while ($data = mysqli_fetch_assoc($dataQuery)) {
        $tanggal = date('d M Y H:i', strtotime($data['tanggal']));
        
        $status_text = [
            1 => 'Baru Mengajukan',
            2 => 'Proses Pengantaran',
            3 => 'Konfirmasi Diterima'
        ];
        $status = $status_text[$data['status']] ?? 'Unknown';
        
        echo '<tr>';
        echo '<td style="text-align: center;">' . $no++ . '</td>';
        echo '<td>' . $tanggal . '</td>';
        echo '<td>' . htmlspecialchars($data['nama_ruangan'] ?: '-') . '</td>';
        echo '<td>' . htmlspecialchars($data['admin_ruangan'] ?: '-') . '</td>';
        echo '<td>' . htmlspecialchars($data['kode_linen'] ?: '-') . '</td>';
        echo '<td>' . htmlspecialchars($data['nama_linen'] ?: '-') . '</td>';
        echo '<td style="text-align: center;">' . $data['jumlah'] . ' pcs</td>';
        echo '<td>' . $status . '</td>';
        echo '<td>' . htmlspecialchars($data['keterangan'] ?: '-') . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr>';
    echo '<td colspan="9" style="text-align: center;">Tidak ada data untuk periode yang dipilih</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';

// Footer
echo '<div style="margin-top: 30px;">';
echo '<table style="width: 100%; border: none;">';
echo '<tr>';
echo '<td style="border: none; text-align: left;">Dicetak oleh: ' . htmlspecialchars($_SESSION['username']) . '</td>';
echo '<td style="border: none; text-align: right;">' . date('d M Y H:i:s') . '</td>';
echo '</tr>';
echo '</table>';
echo '</div>';

echo '</body>';
echo '</html>';
?>