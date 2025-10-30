<?php
include 'config/db.php';
$nama = $_GET['nama'];
$data = $conn->query("SELECT * FROM laporan_iuran WHERE nama='$nama' ORDER BY bulan DESC");

if ($data->num_rows > 0) {
    echo "<table style='width:100%; border-collapse:collapse;'>
            <tr style='background:#3f2b96; color:white;'>
                <th>Bulan</th><th>Jumlah</th><th>Status</th>
            </tr>";
    while ($d = $data->fetch_assoc()) {
        echo "<tr>
                <td>{$d['bulan']}</td>
                <td>Rp ".number_format($d['jumlah'],0,',','.')."</td>
                <td>{$d['status']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center; color:#666;'>Belum ada catatan pembayaran untuk anggota ini</p>";
}
?>
