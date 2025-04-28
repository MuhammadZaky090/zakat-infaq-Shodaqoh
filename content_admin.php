<?php
require_once "database/koneksi.php";

// Menentukan module yang ingin dipanggil berdasarkan URL
if ($_GET['module'] == 'admin_page') {
    include "modules/admin/master_admin.php"; // Halaman utama admin
} elseif ($_GET['module'] == 'login') {
    include "modules/auth/login.php"; // Halaman login
} elseif ($_GET['module'] == 'register') {
    include "modules/auth/register.php"; // Halaman register
} elseif ($_GET['module'] == 'logout') {
    include "modules/auth/logout_admin.php"; // Halaman login
} elseif ($_GET['module'] == 'daftar_transaksi_donasi') {
    include "modules/admin/daftar_transaksi_donasi/daftar_transaksi_donasi.php"; // Daftar transaksi donasi
} elseif ($_GET['module'] == 'verifikasi_pembayaran') {
    include "modules/admin/verifikasi/verifikasi_pembayaran.php"; // Verifikasi bukti pembayaran
} elseif ($_GET['module'] == 'kelola_donatur') {
    include "modules/admin/kelola_donatur/kelola_donatur.php"; // Kelola data donatur
} elseif ($_GET['module'] == 'laporan_keuangan') {
    include "modules/admin/laporan/laporan_keuangan.php"; // Laporan keuangan
} elseif ($_GET['module'] == 'status_distribusi') {
    include "modules/admin/status_distribusi/status_distribusi.php"; // Status distribusi dana zakat
} elseif ($_GET['module'] == 'kelola_program') {
    include "modules/admin/kelola_program/kelola_program_zakat.php"; // Kelola program zakat
} elseif ($_GET['module'] == 'tambah_program') {
    include "modules/admin/kelola_program/tambah_program.php";
} elseif ($_GET['module'] == 'notifikasi') {
    include "modules/notifikasi/notifikasi_donatur.php"; // Kirim notifikasi
} elseif ($_GET['module'] == 'kelola_artikel') {
    include "modules/admin/kelola_artikel/kelola_artikel.php"; // Kelola berita & 
} elseif ($_GET['module'] == 'tambah_artikel') {
    include "modules/admin/kelola_artikel/tambah_artikel.php"; // Kelola berita & artikel
} elseif ($_GET['module'] == 'admin_pesan') {
    include "modules/admin/kelola_pesan/admin_pesan.php"; // Respon pesan dari pengguna
} elseif ($_GET['module'] == 'balas_pesan') {
    include "modules/admin/kelola_pesan/balas_pesan.php"; // Respon pesan dari pengguna
} else {
    echo "Halaman tidak ditemukan."; // Menampilkan pesan jika modul tidak ditemukan
}
