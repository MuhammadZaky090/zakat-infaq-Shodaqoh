<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM berita_artikel WHERE id = $id");
    header("Location: ?module=kelola_artikel");
    exit;
}

// Ambil data artikel
$artikel = $conn->query("SELECT * FROM berita_artikel ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Artikel & Berita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Kelola Artikel & Berita</h1>

            <a href="?module=tambah_lembaga" class="bg-blue-500 text-white px-4 py-2 rounded mb-6 inline-block">Tambah Artikel</a>

            <div class="bg-white rounded shadow">
                <table class="min-w-full table-auto border">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="px-4 py-2">Judul</th>
                            <th class="px-4 py-2">Gambar</th>
                            <th class="px-4 py-2">Isi</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $artikel->fetch_assoc()): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?= htmlspecialchars($row['judul']) ?></td>
                                <td class="px-4 py-2">
                                    <?php if ($row['gambar']): ?>
                                        <img src="uploads/artikel/<?= $row['gambar'] ?>" class="h-16 rounded">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2"><?= mb_strimwidth(strip_tags($row['isi']), 0, 50, '...') ?></td>
                                <td class="px-4 py-2">
                                    <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus?')" class="text-red-500 hover:underline">Hapus</a>
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