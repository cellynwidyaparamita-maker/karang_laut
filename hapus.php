<?php
session_start();
include 'config/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid.");
}

$id = intval($_GET['id']);


$stmt = $conn->prepare("DELETE FROM laporan_kegiatan WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();


header("Location: kegiatan.php?status=deleted");
exit();
?>
