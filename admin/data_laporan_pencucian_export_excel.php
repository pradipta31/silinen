<?php
// export_laporan_pencucian.php
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

// Query untuk data laporan pencucian
$dataQuery = mysqli_query($koneksi, "SELECT p.*, 
    lr.id as id_linen_ruangan,
    l.nama_linen, l.kode_linen,
    r.nama_ruangan,
    u.nama as admin_ruangan
    FROM pencucian p
    LEFT JOIN linen_ruangan lr ON p.id_linen_ruangan = lr.id
    LEFT JOIN linen l ON lr.id_linen = l.id
    LEFT JOIN ruangan r ON lr.id_ruangan = r.id
    LEFT JOIN users u ON r.id_user = u.id
    WHERE DATE(p.tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY p.tanggal DESC");

// Statistik
$statistikQuery = mysqli_query($koneksi, "SELECT
    COUNT(*) as total_pencucian,
    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as pengambilan,
    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as pencucian,
    SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as selesai,
    SUM(jumlah) as total_linen
    FROM pencucian
    WHERE DATE(tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'");
$statistik = mysqli_fetch_assoc($statistikQuery);

// Set header untuk Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Pencucian_Linen_{$tanggal_awal}_{$tanggal_akhir}.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Output HTML sebagai Excel
echo '<html><head><meta charset="UTF-8"><title>Laporan Pencucian Linen</title><style>table{border-collapse:collapse;width:100%;}th,td{border:1px solid #ddd;padding:8px;text-align:left;}th{background:#4CAF50;color:#fff;}</style></head><body>';

echo '<h2>LAPORAN PENCUCIAN LINEN</h2>';
echo '<p>Periode: ' . date('d M Y', strtotime($tanggal_awal)) . ' - ' . date('d M Y', strtotime($tanggal_akhir)) . '</p>';
echo '<p>Tanggal Export: ' . date('d M Y H:i:s') . '</p>';

echo '<h4>Statistik</h4>';
echo '<table><tr><th>Total Pencucian</th><th>Pengambilan</th><th>Pencucian</th><th>Selesai</th><th>Total Linen</th></tr>';
echo '<tr><td>' . number_format($statistik['total_pencucian']) . '</td><td>' . number_format($statistik['pengambilan']) . '</td><td>' . number_format($statistik['pencucian']) . '</td><td>' . number_format($statistik['selesai']) . '</td><td>' . number_format($statistik['total_linen']) . '</td></tr></table>';

echo '<h4>Detail</h4>';
echo '<table><thead><tr><th>No</th><th>Tanggal</th><th>Ruangan</th><th>Admin Ruangan</th><th>Kode Linen</th><th>Nama Linen</th><th>Jumlah</th><th>Status</th><th>Keterangan</th></tr></thead><tbody>';

if (mysqli_num_rows($dataQuery) > 0) {
    $no = 1;
    while ($data = mysqli_fetch_assoc($dataQuery)) {
        $status_text = [1 => 'Pengambilan', 2 => 'Pencucian', 3 => 'Selesai'];
        $status = $status_text[$data['status']] ?? 'Unknown';

        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td>' . date('d M Y H:i', strtotime($data['tanggal'])) . '</td>';
        echo '<td>' . htmlspecialchars($data['nama_ruangan'] ?: '-') . '</td>';
        echo '<td>' . htmlspecialchars($data['admin_ruangan'] ?: '-') . '</td>';
        echo '<td>' . htmlspecialchars($data['kode_linen'] ?: '-') . '</td>';
        echo '<td>' . htmlspecialchars($data['nama_linen'] ?: '-') . '</td>';
        echo '<td>' . htmlspecialchars($data['jumlah']) . '</td>';
        echo '<td>' . htmlspecialchars($status) . '</td>';
        echo '<td>' . htmlspecialchars($data['keterangan'] ?: '-') . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="9" style="text-align:center;">Tidak ada data</td></tr>';
}

echo '</tbody></table>';

echo '</body></html>';
exit;
?>