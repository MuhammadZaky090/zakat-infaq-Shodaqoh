<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

$infaqQuery = "SELECT 'Infaq' AS jenis, u.nama, p.jumlah_infaq AS jumlah, p.metode_pembayaran, p.tanggal 
               FROM pembayaran_infaq p 
               JOIN users u ON p.user_id = u.id 
               WHERE p.status = 'terbayar'";

$shodaqohQuery = "SELECT 'Shodaqoh' AS jenis, u.nama, p.jumlah_shodaqoh AS jumlah, p.metode_pembayaran, p.tanggal 
                  FROM pembayaran_shodaqoh p 
                  JOIN users u ON p.user_id = u.id 
                  WHERE p.status = 'terbayar'";

$zakatQuery = "SELECT 'Zakat' AS jenis, u.nama, p.jumlah_zakat AS jumlah, p.metode_pembayaran, p.tanggal 
               FROM pembayaran_zakat p 
               JOIN users u ON p.user_id = u.id 
               WHERE p.status = 'terbayar'";

$finalQuery = "$infaqQuery UNION ALL $shodaqohQuery UNION ALL $zakatQuery ORDER BY tanggal DESC";
$result = $conn->query($finalQuery);

// Total untuk chart
$totalInfaq = $conn->query("SELECT SUM(jumlah_infaq) AS total FROM pembayaran_infaq WHERE status = 'terbayar'")->fetch_assoc()['total'] ?? 0;
$totalShodaqoh = $conn->query("SELECT SUM(jumlah_shodaqoh) AS total FROM pembayaran_shodaqoh WHERE status = 'terbayar'")->fetch_assoc()['total'] ?? 0;
$totalZakat = $conn->query("SELECT SUM(jumlah_zakat) AS total FROM pembayaran_zakat WHERE status = 'terbayar'")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Statistik Donasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6 space-y-8">
            <h1 class="text-3xl font-bold">Statistik Donasi</h1>

            <!-- Tabel Donasi -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded shadow">
                    <thead>
                        <tr class="bg-indigo-600 text-white text-sm text-center">
                            <th class="px-4 py-2">Jenis Donasi</th>
                            <th class="px-4 py-2">Nama Donatur</th>
                            <th class="px-4 py-2">Jumlah</th>
                            <th class="px-4 py-2">Metode Pembayaran</th>
                            <th class="px-4 py-2">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr class="border-t border-gray-200 text-sm text-center">
                                <td class="px-4 py-2"><?= $row['jenis'] ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="px-4 py-2">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                                <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Chart -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-xl font-semibold mb-4">Grafik Total Donasi Terbayar</h2>
                <canvas id="donationChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('donationChart').getContext('2d');
        const donationChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Infaq', 'Shodaqoh', 'Zakat'],
                datasets: [{
                    label: 'Total Donasi (Rp)',
                    data: [<?= $totalInfaq ?>, <?= $totalShodaqoh ?>, <?= $totalZakat ?>],
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    fill: true
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: '#e5e7eb' // Tailwind gray-200
                        }
                    },
                    x: {
                        grid: {
                            color: '#e5e7eb'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#111827'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>