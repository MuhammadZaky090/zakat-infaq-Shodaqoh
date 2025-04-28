<?php
session_start();
require_once './database/koneksi.php';

// Cek hak akses superadmin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Hapus pembayaran
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM pembayaran WHERE id = '$id'";

    if ($conn->query($query)) {
        header("Location: ?module=kelola_pembayaran");
        exit;
    } else {
        $error = "Gagal menghapus pembayaran!";
    }
}
