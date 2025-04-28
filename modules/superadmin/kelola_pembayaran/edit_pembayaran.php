<?php
session_start();
require_once './database/koneksi.php';

// Cek hak akses superadmin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Ambil data pembayaran untuk diubah
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM pembayaran WHERE id = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
} else {
    header("Location: ?module=kelola_pembayaran");
    exit;
}

// Proses update pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pembayaran = $_POST['nama_pembayaran'];
    $jumlah = $_POST['jumlah'];
    $metode = $_POST['metode'];
    $tanggal = $_POST['tanggal'];

    $query = "UPDATE pembayaran SET nama_pembayaran = '$nama_pembayaran', jumlah = '$jumlah', metode = '$metode', tanggal = '$tanggal' WHERE id = '$id'";

    if ($conn->query($query)) {
        header("Location: ?module=kelola_pembayaran");
        exit;
    } else {
        $error = "Gagal mengupdate data pembayaran!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Edit Pembayaran</h1>

            <?php if (isset($error)) : ?>
                <div class="bg-red-500 text-white p-4 mb-4 rounded">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="bg-white p-6 rounded shadow">
                <div class="mb-4">
                    <label for="nama_pembayaran" class="block text-lg font-medium text-gray-700">Nama Pembayaran</label>
                    <input type="text" name="nama_pembayaran" id="nama_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded" value="<?= htmlspecialchars($row['nama_pembayaran']) ?>" required>
                </div>
                <div class="mb-4">
                    <label for="jumlah" class="block text-lg font-medium text-gray-700">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="w-full px-4 py-2 border border-gray-300 rounded" value="<?= $row['jumlah'] ?>" required>
                </div>
                <div class="mb-4">
                    <label for="metode" class="block text-lg font-medium text-gray-700">Metode Pembayaran</label>
                    <select name="metode" id="metode" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                        <option value="Transfer" <?= $row['metode'] === 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                        <option value="Cash" <?= $row['metode'] === 'Cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="E-wallet" <?= $row['metode'] === 'E-wallet' ? 'selected' : '' ?>>E-wallet</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="tanggal" class="block text-lg font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="w-full px-4 py-2 border border-gray-300 rounded" value="<?= $row['tanggal'] ?>" required>
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>