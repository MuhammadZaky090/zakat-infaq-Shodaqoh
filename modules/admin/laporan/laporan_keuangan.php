<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ?module=login");
    exit;
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'semua';

$whereTanggal = "";

// Range tanggal
switch ($filter) {
    case 'harian':
        $whereTanggal = "AND DATE(tanggal) = CURDATE()";
        break;
    case 'mingguan':
        $whereTanggal = "AND YEARWEEK(tanggal, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'bulanan':
        $whereTanggal = "AND MONTH(tanggal) = MONTH(CURDATE()) AND YEAR(tanggal) = YEAR(CURDATE())";
        break;
    default:
        $whereTanggal = "";
}

$query = "
    SELECT 'Zakat' AS jenis, u.nama, z.jumlah_zakat AS jumlah, z.metode_pembayaran AS metode, z.tanggal
    FROM pembayaran_zakat z
    JOIN users u ON z.user_id = u.id
    WHERE z.status = 'terbayar' $whereTanggal

    UNION

    SELECT 'Infaq' AS jenis, u.nama, i.jumlah_infaq AS jumlah, 'QRIS' AS metode, i.tanggal
    FROM pembayaran_infaq i
    JOIN users u ON i.user_id = u.id
    WHERE i.status = 'terbayar' $whereTanggal

    UNION

    SELECT 'Shodaqoh' AS jenis, u.nama, s.jumlah_shodaqoh AS jumlah, 'QRIS' AS metode, s.tanggal
    FROM pembayaran_shodaqoh s
    JOIN users u ON s.user_id = u.id
    WHERE s.status = 'terbayar' $whereTanggal

    ORDER BY tanggal DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .sidebar {
                display: none !important;
            }

            body {
                margin: 0;
            }

            .flex-1 {
                width: 100% !important;
            }
        }
    </style>

</head>

<body class="bg-gray-100">
    <div class="flex">
        <div class="sidebar">
            <?php include 'modules/admin/sidebar_admin.php'; ?>
        </div>


        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-4">Laporan Keuangan</h1>

            <form method="GET" class="mb-4 no-print">
                <input type="hidden" name="module" value="laporan_keuangan">
                <label for="filter" class="font-semibold">Filter Tanggal:</label>
                <select name="filter" id="filter" onchange="this.form.submit()" class="ml-2 p-2 border rounded">
                    <option value="semua" <?= $filter == 'semua' ? 'selected' : '' ?>>Semua</option>
                    <option value="harian" <?= $filter == 'harian' ? 'selected' : '' ?>>Harian</option>
                    <option value="mingguan" <?= $filter == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
                    <option value="bulanan" <?= $filter == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                </select>
            </form>

            <div class="mb-4 no-print">
                <button onclick="window.print()" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Cetak Laporan
                </button>
            </div>

            <table class="min-w-full bg-white border border-gray-300 rounded shadow">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-4 py-2">Jenis</th>
                        <th class="px-4 py-2">Nama Donatur</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Metode Pembayaran</th>
                        <th class="px-4 py-2">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) :
                            $total += $row['jumlah'];
                    ?>
                            <tr class="border-t border-gray-200">
                                <td class="px-4 py-2"><?= $row['jenis'] ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="px-4 py-2">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['metode']) ?></td>
                                <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                            </tr>
                    <?php
                        endwhile;
                    } else {
                        echo '<tr><td colspan="5" class="text-center py-4">Tidak ada data pada periode ini.</td></tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-200 font-semibold">
                        <td colspan="2" class="px-4 py-2 text-right">Total</td>
                        <td class="px-4 py-2">Rp <?= number_format($total, 0, ',', '.') ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>