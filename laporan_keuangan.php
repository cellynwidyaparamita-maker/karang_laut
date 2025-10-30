<?php
session_start();
include 'config/db.php';


if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','ketua'])) {
    header("Location: index.php");
    exit();
}


$tipe_filter = isset($_GET['tipe']) ? $_GET['tipe'] : 'semua';

if($tipe_filter == 'masuk'){
    $sql = "SELECT * FROM transaksi WHERE tipe='masuk' ORDER BY tanggal ASC";
    $total_masuk = $conn->query("SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='masuk'")->fetch_assoc()['total'];
    $total_keluar = 0;
} elseif($tipe_filter == 'keluar'){
    $sql = "SELECT * FROM transaksi WHERE tipe='keluar' ORDER BY tanggal ASC";
    $total_keluar = $conn->query("SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='keluar'")->fetch_assoc()['total'];
    $total_masuk = 0;
} else {
    $sql = "SELECT * FROM transaksi ORDER BY tanggal ASC";
    $total_masuk = $conn->query("SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='masuk'")->fetch_assoc()['total'];
    $total_keluar = $conn->query("SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='keluar'")->fetch_assoc()['total'];
}

$saldo = $total_masuk - $total_keluar;
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Keuangan - Karang Taruna</title>
<style>

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg,#a8c0ff,#3f2b96);
    margin: 0;
    padding: 40px;
    color: #333;
}

h1 {
    text-align: center;
    color: #fff;
    margin-bottom: 30px;
    font-size: 32px;
    font-weight: 700;
}


.filter-form {
    text-align: center;
    margin-bottom: 25px;
}

.filter-form select {
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    outline: none;
}

.filter-form button {
    padding: 10px 18px;
    margin-left: 8px;
    border: none;
    border-radius: 8px;
    background: linear-gradient(90deg,#3f2b96,#a8c0ff);
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s;
}

.filter-form button:hover {
    opacity: 0.85;
}


table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

th, td {
    padding: 12px 15px;
    text-align: left;
}

th {
    background: #3f2b96;
    color: #fff;
    font-weight: 600;
}

tr:nth-child(even) {
    background: #f7f7f7;
}

tr:hover {
    background: #e1e4ff;
    transition: 0.3s;
}


.summary {
    margin-top: 25px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.summary p {
    font-size: 16px;
    margin: 8px 0;
    font-weight: 500;
}

.summary strong {
    color: #3f2b96;
}


.btn-back {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    background: linear-gradient(90deg,#3f2b96,#a8c0ff);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: 0.3s;
}

.btn-back:hover {
    opacity: 0.85;
}
</style>
</head>
<body>

<h1>Laporan Keuangan Karang Taruna</h1>


<form class="filter-form" method="get">
    <label for="tipe" style="color:white; font-weight:500; margin-right:8px;">Tampilkan:</label>
    <select name="tipe" id="tipe">
        <option value="semua" <?=($tipe_filter=='semua')?'selected':''?>>Semua</option>
        <option value="masuk" <?=($tipe_filter=='masuk')?'selected':''?>>Uang Masuk</option>
        <option value="keluar" <?=($tipe_filter=='keluar')?'selected':''?>>Uang Keluar</option>
    </select>
    <button type="submit">Filter</button>
</form>

<table>
<tr>
    <th>No</th>
    <th>Tanggal</th>
    <th>Tipe</th>
    <th>Jumlah</th>
    <th>Keterangan</th>
</tr>
<?php
$no=1;
while($row = $result->fetch_assoc()){
    echo "<tr>
            <td>{$no}</td>
            <td>{$row['tanggal']}</td>
            <td>{$row['tipe']}</td>
            <td>Rp ".number_format($row['jumlah'],0,',','.')."</td>
            <td>{$row['keterangan']}</td>
          </tr>";
    $no++;
}
?>
</table>

<div class="summary">
    <p><strong>Total Masuk:</strong> Rp <?=number_format($total_masuk,0,',','.')?></p>
    <p><strong>Total Keluar:</strong> Rp <?=number_format($total_keluar,0,',','.')?></p>
    <p><strong>Saldo:</strong> Rp <?=number_format($saldo,0,',','.')?></p>
</div>

<a href="dashboard_admin.php" class="btn-back">← Kembali ke Dashboard</a>


</body>
</html>
