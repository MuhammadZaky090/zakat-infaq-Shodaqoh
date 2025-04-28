<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Ambil data log aktivitas
$logs = $conn->query("SELECT * FROM log_aktivitas ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4 text-green-800">Log Aktivitas</h1>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full table-auto text-sm text-gray-700 border">
                    <thead class="bg-green-700 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">User ID</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                            <th class="px-4 py-2 text-left">Deskripsi</th>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $no = 1; ?>
                        <?php while ($data = $logs->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2"><?= $no++ ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($data['user_id']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($data['aksi']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($data['deskripsi']) ?></td>
                                <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($data['tanggal'])) ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>