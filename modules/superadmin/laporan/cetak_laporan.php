<?php
require_once './database/koneksi.php';
$query = "
    SELECT i.id, u.nama, 'Infaq' AS jenis, i.jumlah_infaq AS jumlah, i.metode_pembayaran, i.status, i.tanggal
    FROM pembayaran_infaq i
    JOIN users u ON i.user_id = u.id
    UNION
    SELECT s.id, u.nama, 'Shodaqoh' AS jenis, s.jumlah_shodaqoh AS jumlah, s.metode_pembayaran, s.status, s.tanggal
    FROM pembayaran_shodaqoh s
    JOIN users u ON s.user_id = u.id
    UNION
    SELECT z.id, u.nama, 'Zakat' AS jenis, z.jumlah_zakat AS jumlah, z.metode_pembayaran, z.status, z.tanggal
    FROM pembayaran_zakat z
    JOIN users u ON z.user_id = u.id
    ORDER BY tanggal DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan ZIS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background-color: #ddd;
        }
        .button-container {
            margin-bottom: 20px;
        }
        .btn {
            padding: 8px 16px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-print {
            background-color: #16a34a;
            color: white;
            border: none;
        }
        .btn-print:hover {
            background-color: #15803d;
        }
        .btn-back {
            background-color: #3b82f6;
            color: white;
            border: none;
        }
        .btn-back:hover {
            background-color: #2563eb;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="button-container no-print">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak Laporan
        </button>
        <a href="?module=laporan_transaksi" class="btn btn-back">
            ← Kembali
        </a>
    </div>
    <h2>LAPORAN TRANSAKSI ZIS</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Donatur</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                    <td><?= $row['metode_pembayaran'] ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>