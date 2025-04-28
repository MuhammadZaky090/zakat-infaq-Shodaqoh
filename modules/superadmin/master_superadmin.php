<?php
require_once './database/koneksi.php';

// Query hitung total user yang role-nya 'user'
$result = $conn->query("SELECT COUNT(*) AS total_user FROM users WHERE role = 'user'");
$data = $result->fetch_assoc();
$total_user = $data['total_user'];

// Hitung total dari masing-masing tabel dengan status 'terbayar'
$shodaqoh = $conn->query("SELECT SUM(jumlah_shodaqoh) AS total_shodaqoh FROM pembayaran_shodaqoh WHERE status = 'terbayar'");
$data_shodaqoh = $shodaqoh->fetch_assoc();
$total_shodaqoh = $data_shodaqoh['total_shodaqoh'] ?? 0;

$infaq = $conn->query("SELECT SUM(jumlah_infaq) AS total_infaq FROM pembayaran_infaq WHERE status = 'terbayar'");
$data_infaq = $infaq->fetch_assoc();
$total_infaq = $data_infaq['total_infaq'] ?? 0;

$zakat = $conn->query("SELECT SUM(jumlah_zakat) AS total_zakat FROM pembayaran_zakat WHERE status = 'terbayar'");
$data_zakat = $zakat->fetch_assoc();
$total_zakat = $data_zakat['total_zakat'] ?? 0;

// Total semua donasi
$total_donasi_zis = $total_shodaqoh + $total_infaq + $total_zakat;


// Query untuk menghitung jumlah permintaan dengan status 'belum terkirim'
$query = "SELECT COUNT(*) AS total_menunggu FROM distribusi_dana WHERE status = 'belum terkirim'";
$result = $conn->query($query);
$data = $result->fetch_assoc();
$total_menunggu = $data['total_menunggu'] ?? 0;

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php include 'sidebar_superadmin.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 px-8 py-6 overflow-y-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                Selamat Datang, Super Admin!
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h2 class="text-lg font-semibold mb-2 text-green-600">Total User</h2>
                    <p class="text-3xl font-bold text-gray-700"><?= $total_user ?></p>
                </div>


                <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h2 class="text-lg font-semibold mb-2 text-green-600">Total Donasi ZIS</h2>
                    <p class="text-3xl font-bold text-gray-700"><?= "Rp. " . number_format($total_donasi_zis, 0, ',', '.'); ?></p>
                </div>


                <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                    <h2 class="text-lg font-semibold mb-2 text-green-600">Pencairan Menunggu</h2>
                    <p class="text-3xl font-bold text-gray-700"><?= $total_menunggu ?> Permintaan</p>
                </div>

            </div>


        </main>
    </div>

</body>

</html>