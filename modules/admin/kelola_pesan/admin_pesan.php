<?php
session_start();
require_once './database/koneksi.php';

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ?module=login");
    exit;
}

// Proses hapus pesan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM pesan_pengguna WHERE id = $id");
    header("Location: ?module=admin_pesan");
    exit;
}

// Ambil data pesan
$pesan = $conn->query("SELECT * FROM pesan_pengguna ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Pesan Masuk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/admin/sidebar_admin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Kelola Pesan Masuk</h1>

            <div class="bg-white rounded shadow">
                <table class="min-w-full table-auto border">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="px-4 py-2">Nama Pengirim</th>
                            <th class="px-4 py-2">Subjek</th>
                            <th class="px-4 py-2">Pesan</th>
                            <th class="px-4 py-2">Balasan</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = $pesan->fetch_assoc()): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?= htmlspecialchars($p['nama_pengirim']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($p['subjek']) ?></td>
                                <td class="px-4 py-2 max-w-xs truncate"><?= htmlspecialchars($p['isi']) ?></td>
                                <td class="px-4 py-2"><?= $p['balasan'] ? htmlspecialchars($p['balasan']) : '<span class="text-gray-500">Belum dibalas</span>' ?></td>
                                <td class="px-4 py-2">
                                    <a href="?module=balas_pesan&id=<?= $p['id'] ?>" class="text-green-500 hover:underline">Balas</a>
                                    |
                                    <a href="?module=admin_pesan&hapus=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin hapus pesan ini?')" class="text-red-500 hover:underline">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>