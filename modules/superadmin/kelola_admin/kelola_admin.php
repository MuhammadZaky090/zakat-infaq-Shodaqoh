<?php
session_start();
require_once './database/koneksi.php';

// Cek hak akses superadmin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Ambil semua user yang role-nya 'admin' atau 'superadmin'
$query = "SELECT id, nama, email, foto_profile, role, created_at FROM users WHERE role IN ('admin') ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Kelola Data Admin</h1>
            <!-- Tombol Tambah Admin -->
            <div class="mb-4">
                <a href="?module=tambah_admin" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
                    + Tambah Admin
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded shadow">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Foto</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Dibuat Pada</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr class="border-t border-gray-200 text-center">
                                <td class="px-4 py-2"><?= $row['id'] ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="px-4 py-2">
                                    <?php if ($row['foto_profile']) : ?>
                                        <img src="uploads/<?= $row['foto_profile'] ?>" alt="Foto Profil" class="w-12 h-12 object-cover rounded-full mx-auto">
                                    <?php else : ?>
                                        <span class="text-gray-500 italic">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2"><?= $row['role'] ?></td>
                                <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="?module=edit_admin&id=<?= $row['id'] ?>" class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">Edit</a>
                                    <?php if ($_SESSION['user_id'] != $row['id']) : ?>
                                        <a href="?module=hapus_admin&id=<?= $row['id'] ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Yakin ingin menghapus admin ini?')">Hapus</a>
                                    <?php else : ?>
                                        <span class="text-gray-400 italic">Tidak bisa hapus sendiri</span>
                                    <?php endif; ?>
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