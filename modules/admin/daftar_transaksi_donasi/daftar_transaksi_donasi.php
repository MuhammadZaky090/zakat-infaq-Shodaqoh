<?php
session_start();
require_once './database/koneksi.php';

// Cek apakah user sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ?module=login");
    exit;
}

// Ambil data transaksi donasi dari ketiga tabel
$query = "
    SELECT 'zakat' AS jenis, z.id, z.user_id, u.nama, z.jumlah_zakat AS jumlah, z.metode_pembayaran AS metode, z.tanggal, z.status
    FROM pembayaran_zakat z
    JOIN users u ON z.user_id = u.id
    UNION
    SELECT 'infaq' AS jenis, i.id, i.user_id, u.nama, i.jumlah_infaq AS jumlah, 'QRIS' AS metode, i.tanggal, i.status
    FROM pembayaran_infaq i
    JOIN users u ON i.user_id = u.id
    UNION
    SELECT 'shodaqoh' AS jenis, s.id, s.user_id, u.nama, s.jumlah_shodaqoh AS jumlah, 'QRIS' AS metode, s.tanggal, s.status
    FROM pembayaran_shodaqoh s
    JOIN users u ON s.user_id = u.id
    ORDER BY tanggal DESC
";

$result = $conn->query($query);

// Hitung total nominal transaksi
$totalNominalQuery = "
    SELECT 
        IFNULL((SELECT SUM(jumlah_zakat) FROM pembayaran_zakat), 0) +
        IFNULL((SELECT SUM(jumlah_infaq) FROM pembayaran_infaq), 0) +
        IFNULL((SELECT SUM(jumlah_shodaqoh) FROM pembayaran_shodaqoh), 0) AS total_nominal
";
$totalNominalResult = $conn->query($totalNominalQuery);
$totalNominal = $totalNominalResult->fetch_assoc()['total_nominal'] ?? 0;

// Hitung jumlah data pending dan terbayar
$countQuery = "
    SELECT 
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN status = 'terbayar' THEN 1 ELSE 0 END) AS terbayar
    FROM (
        SELECT status FROM pembayaran_zakat
        UNION ALL
        SELECT status FROM pembayaran_infaq
        UNION ALL
        SELECT status FROM pembayaran_shodaqoh
    ) AS semua_status
";

$countResult = $conn->query($countQuery);
$summary = $countResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Transaksi Donasi - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">

        <?php include 'modules/admin/sidebar_admin.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-4">Daftar Transaksi Donasi</h1>
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-green-600 text-white text-left">
                        <th class="px-4 py-2">Jenis Donasi</th>
                        <th class="px-4 py-2">User ID</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Metode Pembayaran</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr class="border-t">
                            <td class="px-4 py-2"><?= htmlspecialchars(ucfirst($row['jenis'])) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['user_id']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="px-4 py-2">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['metode']) ?></td>
                            <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                            <td class="px-4 py-2 capitalize"><?= htmlspecialchars($row['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Ringkasan total data -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-500">
                    <h2 class="text-lg font-semibold text-gray-700">Total Nominal Transaksi</h2>
                    <p class="text-2xl font-bold text-gray-900">Rp <?= number_format($totalNominal, 0, ',', '.') ?></p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-yellow-500">
                    <h2 class="text-lg font-semibold text-gray-700">Status Pending</h2>
                    <p class="text-2xl font-bold text-gray-900"><?= $summary['pending'] ?> Data</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-green-500">
                    <h2 class="text-lg font-semibold text-gray-700">Status Terbayar</h2>
                    <p class="text-2xl font-bold text-gray-900"><?= $summary['terbayar'] ?> Data</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>