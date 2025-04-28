<?php
session_start();
require_once './database/koneksi.php';

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ?module=login");
    exit;
}

// Ambil pesan yang ingin dibalas berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $pesan = $conn->query("SELECT * FROM pesan_pengguna WHERE id = $id")->fetch_assoc();
}

// Proses balas pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $balasan = $_POST['balasan'];
    $conn->query("UPDATE pesan_pengguna SET balasan = '$balasan' WHERE id = $id");
    header("Location: ?module=admin_pesan");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Balas Pesan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/admin/sidebar_admin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Balas Pesan</h1>

            <div class="bg-white rounded shadow p-6">
                <p class="mb-4"><strong>Dari:</strong> <?= htmlspecialchars($pesan['nama_pengirim']) ?> (<?= htmlspecialchars($pesan['email_pengirim']) ?>)</p>
                <p class="mb-4"><strong>Subjek:</strong> <?= htmlspecialchars($pesan['subjek']) ?></p>
                <p class="mb-4"><strong>Isi Pesan:</strong></p>
                <p class="mb-4"><?= nl2br(htmlspecialchars($pesan['isi'])) ?></p>

                <form method="POST" class="space-y-4">
                    <textarea name="balasan" rows="5" required class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($pesan['balasan'] ?? '') ?></textarea>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 focus:outline-none">Kirim Balasan</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>