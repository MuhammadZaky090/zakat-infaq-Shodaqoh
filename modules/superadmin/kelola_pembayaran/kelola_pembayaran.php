<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

$query = "SELECT * FROM metode_pembayaran ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Metode Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Kelola Metode Pembayaran</h1>
            <div class="mb-4">
                <a href="?module=tambah_pembayaran" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                    + Tambah Metode
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded shadow">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Metode</th>
                            <th class="px-4 py-2">QRIS</th>
                            <th class="px-4 py-2">Rekening Bank</th>
                            <th class="px-4 py-2">Logo Bank</th>
                            <th class="px-4 py-2">Atas Nama</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr class="border-t border-gray-200 text-center">
                                <td class="px-4 py-2"><?= $row['id'] ?></td>
                                <td class="px-4 py-2"><?= $row['metode'] ?></td>
                                <td class="px-4 py-2">
                                <td class="px-4 py-2">
                                    <?php if ($row['gambar_qris']) : ?>
                                        <img src="uploads/metode_pembayaran/<?= $row['gambar_qris'] ?>" alt="QRIS" class="h-12 mx-auto">
                                    <?php else : ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2">
                                    <?php if ($row['logo_bank']) : ?>
                                        <img src="uploads/metode_pembayaran/<?= $row['logo_bank'] ?>" alt="Logo Bank" class="h-10 mx-auto">
                                    <?php else : ?>
                                        <span>-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-2"><?= $row['atas_nama'] ?></td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="?module=edit_pembayaran&id=<?= $row['id'] ?>" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Edit</a>
                                    <a href="?module=hapus_pembayaran&id=<?= $row['id'] ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>