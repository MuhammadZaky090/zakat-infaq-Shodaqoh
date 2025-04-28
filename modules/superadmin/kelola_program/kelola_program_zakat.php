<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Hapus program
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM program_zakat WHERE id = $id");
    header("Location: ?module=kelola_program");
    exit;
}

// Ambil semua data program
$programs = $conn->query("SELECT * FROM program_zakat ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Program Zakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Kelola Program Penerimaan Zakat</h1>

            <!-- Tombol Tambah Program -->
            <a href="?module=tambah_program" class="mb-6 bg-blue-500 text-white px-4 py-2 rounded inline-block">Tambah Program</a>

            <!-- Tabel Program -->
            <div class="bg-white rounded shadow mt-6">
                <table class="min-w-full table-auto border">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-4 py-2">Nama Program</th>
                            <th class="px-4 py-2">Jenis</th>
                            <th class="px-4 py-2">Deskripsi</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $programs->fetch_assoc()): ?>
                            <tr class="border-t">
                                <td class="px-4 py-2"><?= htmlspecialchars($row['nama_program']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['jenis_zakat']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['deskripsi']) ?></td>
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