<?php
$host = 'localhost';
$user = 'u187436749_dennis';
$pass = 'Kiftirul13';
$dbname = 'u187436749_zis_alfajar';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die('Koneksi gagal: ' . $conn->connect_error);
}
?>
