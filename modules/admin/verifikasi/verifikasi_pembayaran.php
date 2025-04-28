<?php
session_start();
require_once './database/koneksi.php';

// Cek apakah user sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ?module=login");
    exit;
}

// Cek jika ada ID transaksi yang akan diubah statusnya
if (isset($_GET['id']) && isset($_GET['jenis'])) {
    $id = $_GET['id'];
    $jenis = $_GET['jenis'];

    // Update status transaksi berdasarkan jenis
    $updateQuery = "";
    switch ($jenis) {
        case 'zakat':
            $updateQuery = "UPDATE pembayaran_zakat SET status = 'terbayar' WHERE id = ?";
            break;
        case 'infaq':
            $updateQuery = "UPDATE pembayaran_infaq SET status = 'terbayar' WHERE id = ?";
            break;
        case 'shodaqoh':
            $updateQuery = "UPDATE pembayaran_shodaqoh SET status = 'terbayar' WHERE id = ?";
            break;
        default:
            // Jika jenis transaksi tidak valid
            $_SESSION['error_message'] = "Jenis transaksi tidak valid!";
            header("Location: ?module=verifikasipembayaran");
            exit;
    }

    // Persiapkan statement dan eksekusi query update
    if ($stmt = $conn->prepare($updateQuery)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Status transaksi berhasil diubah menjadi terbayar.";
        } else {
            $_SESSION['error_message'] = "Terjadi kesalahan saat mengubah status transaksi.";
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Gagal menyiapkan query!";
    }

    // Redirect kembali ke halaman verifikasi pembayaran
    header("Location: ?module=verifikasi_pembayaran");
    exit;
}

// Ambil data transaksi yang statusnya pending untuk ditampilkan
$query = "
    SELECT 'zakat' AS jenis, z.id, z.user_id, u.nama, z.jumlah_zakat AS jumlah, z.metode_pembayaran AS metode, z.tanggal, z.status
    FROM pembayaran_zakat z
    JOIN users u ON z.user_id = u.id
    WHERE z.status = 'pending'
    UNION
    SELECT 'infaq' AS jenis, i.id, i.user_id, u.nama, i.jumlah_infaq AS jumlah, 'QRIS' AS metode, i.tanggal, i.status
    FROM pembayaran_infaq i
    JOIN users u ON i.user_id = u.id
    WHERE i.status = 'pending'
    UNION
    SELECT 'shodaqoh' AS jenis, s.id, s.user_id, u.nama, s.jumlah_shodaqoh AS jumlah, 'QRIS' AS metode, s.tanggal, s.status
    FROM pembayaran_shodaqoh s
    JOIN users u ON s.user_id = u.id
    WHERE s.status = 'pending'
    ORDER BY tanggal DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Verifikasi Pembayaran - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">

        <?php include 'modules/admin/sidebar_admin.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-4">Verifikasi Pembayaran</h1>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    <?= $_SESSION['success_message']; ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php elseif (isset($_SESSION['error_message'])): ?>
                <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                    <?= $_SESSION['error_message']; ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-blue-600 text-white text-left">
                        <th class="px-4 py-2">Jenis Donasi</th>
                        <th class="px-4 py-2">User ID</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Metode Pembayaran</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td class="px-4 py-2"><?= ucfirst($row['jenis']) ?></td>
                            <td class="px-4 py-2"><?= $row['user_id'] ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="px-4 py-2"><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['metode']) ?></td>
                            <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                            <td class="px-4 py-2">
                                <a href="?module=verifikasi_pembayaran&id=<?= $row['id'] ?>&jenis=<?= $row['jenis'] ?>"
                                    class="text-blue-500 hover:text-blue-700">
                                    Verifikasi
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>