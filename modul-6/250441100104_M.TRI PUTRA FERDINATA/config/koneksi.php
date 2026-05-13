<!-- file koneksi.php -->
<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "db_modul6";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>

<!-- file -->