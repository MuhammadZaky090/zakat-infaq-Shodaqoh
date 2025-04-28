<?php
session_start();
require_once './database/koneksi.php';

// Cek apakah user sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ?module=login");
    exit;
}

// Ambil data user yang rolenya 'user' (donatur)
$query = "SELECT id, nama, email, foto_profile, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Donatur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/admin/sidebar_admin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-4">Kelola Data Donatur</h1>

            <table class="min-w-full bg-white border border-gray-300 rounded shadow">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Foto</th>
                        <th class="px-4 py-2">Dibuat Pada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr class="border-t border-gray-200">
                            <td class="px-4 py-2"><?= $row['id'] ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="px-4 py-2">
                                <?php if ($row['foto_profile']) : ?>
                                    <img src="uploads/<?= $row['foto_profile'] ?>" alt="Foto Profil" class="w-12 h-12 object-cover rounded-full">
                                <?php else : ?>
                                    <span class="text-gray-500 italic">Tidak ada</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>