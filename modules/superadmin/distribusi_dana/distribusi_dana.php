<?php
include '../../koneksi.php';
session_start();

// Proses persetujuan
if (isset($_GET['setujui_id'])) {
    $id = $_GET['setujui_id'];
    $query = "UPDATE distribusi_dana SET status='Terkirim' WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Log aktivitas superadmin
    $user_id = $_SESSION['user_id'];
    $log = "INSERT INTO log_aktivitas (user_id, aksi, deskripsi) VALUES (?, 'Persetujuan Dana', ?)";
    $desc = "Superadmin menyetujui pencairan dana ID $id pada " . date('d-m-Y H:i:s');
    $log_stmt = $conn->prepare($log);
    $log_stmt->bind_param("is", $user_id, $desc);
    $log_stmt->execute();

    header("Location: ?module=pencairan_dana");
    exit();
}

// Ambil data distribusi dana dengan status Belum Terkirim
$result = $conn->query("SELECT * FROM distribusi_dana WHERE status = 'Belum Terkirim' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Persetujuan Pencairan Dana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Persetujuan Pencairan Dana</h1>

            <div class="overflow-x-auto bg-white rounded shadow p-4">
                <table class="min-w-full text-sm text-left border">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Nama Penerima</th>
                            <th class="px-4 py-2 border">Jenis Bantuan</th>
                            <th class="px-4 py-2 border">Jumlah Dana</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = $result->fetch_assoc()) :
                        ?>
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2 border"><?= $no++ ?></td>
                                <td class="px-4 py-2 border"><?= htmlspecialchars($row['nama_penerima']) ?></td>
                                <td class="px-4 py-2 border"><?= $row['jenis_bantuan'] ?></td>
                                <td class="px-4 py-2 border">Rp <?= number_format($row['jumlah_dana'], 0, ',', '.') ?></td>
                                <td class="px-4 py-2 border"><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                                <td class="px-4 py-2 border">
                                    <span class="px-2 py-1 rounded-full bg-yellow-300 text-gray-900"><?= $row['status'] ?></span>
                                </td>
                                <td class="px-4 py-2 border">
                                    <a href="?module=pencairan_dana&setujui_id=<?= $row['id'] ?>" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm" onclick="return confirm('Setujui pencairan dana ini?')">
                                        Setujui
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($result->num_rows === 0): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada pencairan dana yang menunggu persetujuan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>