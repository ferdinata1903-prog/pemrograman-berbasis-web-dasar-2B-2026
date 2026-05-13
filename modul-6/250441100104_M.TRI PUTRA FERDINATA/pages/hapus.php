<?php
require_once '../auth/cek_login.php';
require_once '../config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: data_produk.php");
    exit;
}

$id = $_GET['id'];

// Hapus gambar juga
$stmt = mysqli_prepare($conn, "SELECT gambar FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);

if ($produk && $produk['gambar'] && file_exists('../uploads/' . $produk['gambar'])) {
    unlink('../uploads/' . $produk['gambar']);
}

$stmt2 = mysqli_prepare($conn, "DELETE FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt2, "i", $id);
mysqli_stmt_execute($stmt2);

header("Location: data_produk.php?toast=hapus");
exit;
?>