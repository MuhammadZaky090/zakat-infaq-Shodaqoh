<?php
session_start();
require_once './database/koneksi.php';

// Cek hak akses superadmin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Proses blokir akun
if (isset($_GET['module']) && $_GET['module'] == 'blokir_akun' && isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Update status menjadi 'blokir'
    $query = "UPDATE users SET status = 'blokir' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        // Tetap berada di halaman blokir_akun
        header("Location: ?module=blokir_akun"); // Tetap di blokir_akun
        exit();
    } else {
        echo "Gagal memblokir akun.";
    }
}

// Proses aktifkan akun
if (isset($_GET['module']) && $_GET['module'] == 'aktifkan_akun' && isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Update status menjadi 'aktif'
    $query = "UPDATE users SET status = 'aktif' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        // Tetap berada di halaman aktifkan_akun
        header("Location: ?module=aktifkan_akun"); // Tetap di aktifkan_akun
        exit();
    } else {
        echo "Gagal mengaktifkan akun.";
    }
}

// Ambil semua user yang status-nya aktif atau blokir
$query = "SELECT id, nama, email, foto_profile, role, status, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Akun Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar Superadmin -->
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Kelola Akun Pengguna</h1>

            <!-- Table daftar pengguna -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 rounded shadow">
                    <thead>
                        <tr class="bg-green-600 text-white">
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Foto</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Status</th>
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
                                <td class="px-4 py-2"><?= ucfirst($row['status']) ?></td>
                                <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                                <td class="px-4 py-2 space-x-2">
                                    <!-- Tombol Aktifkan / Blokir -->
                                    <?php if ($_SESSION['user_id'] != $row['id']) : ?>
                                        <?php if ($row['status'] === 'aktif') : ?>
                                            <a href="?module=blokir_akun&id=<?= $row['id'] ?>" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600" onclick="return confirm('Yakin ingin memblokir akun ini?')">Blokir</a>
                                        <?php else : ?>
                                            <a href="?module=aktifkan_akun&id=<?= $row['id'] ?>" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600" onclick="return confirm('Yakin ingin mengaktifkan akun ini?')">Aktifkan</a>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="text-gray-400 italic">Tidak bisa blokir/aktifkan sendiri</span>
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