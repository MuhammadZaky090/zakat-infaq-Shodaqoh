<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode = $_POST['metode'];
    $rekening_bank = $_POST['rekening_bank'];
    $atas_nama = $_POST['atas_nama'];

    $uploadDir = "uploads/metode_pembayaran";

    // Buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Validasi & upload gambar QRIS
    $gambar_qris = $_FILES['gambar_qris'];
    $gambar_qris_name = '';
    if ($gambar_qris['error'] === UPLOAD_ERR_OK) {
        $ext_qris = pathinfo($gambar_qris['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext_qris), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $gambar_qris_name = 'qris_' . uniqid() . '.' . $ext_qris;
            $qrisPath = $uploadDir . '/' . $gambar_qris_name;
            move_uploaded_file($gambar_qris['tmp_name'], $qrisPath);
        }
    }

    // Validasi & upload logo bank
    $logo_bank = $_FILES['logo_bank'];
    $logo_bank_name = '';
    if ($logo_bank['error'] === UPLOAD_ERR_OK) {
        $ext_logo = pathinfo($logo_bank['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext_logo), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            $logo_bank_name = 'logo_' . uniqid() . '.' . $ext_logo;
            $logoPath = $uploadDir . '/' . $logo_bank_name;
            move_uploaded_file($logo_bank['tmp_name'], $logoPath);
        }
    }

    // Simpan ke database
    $query = "INSERT INTO metode_pembayaran (metode, gambar_qris, rekening_bank, logo_bank, atas_nama)
              VALUES ('$metode', '$gambar_qris_name', '$rekening_bank', '$logo_bank_name', '$atas_nama')";

    if ($conn->query($query)) {
        header("Location: ?module=metode_pembayaran");
        exit;
    } else {
        $error = "Gagal menambah data metode pembayaran!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Metode Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Tambah Metode Pembayaran</h1>

            <?php if (isset($error)) : ?>
                <div class="bg-red-500 text-white p-4 mb-4 rounded"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
                <div class="mb-4">
                    <label class="block font-medium">Metode</label>
                    <select name="metode" class="w-full px-4 py-2 border rounded" required>
                        <option value="QRIS">QRIS</option>
                        <option value="Rekening Bank">Rekening Bank</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Gambar QRIS</label>
                    <input type="file" name="gambar_qris" class="w-full border px-4 py-2 rounded" accept="image/*">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Rekening Bank</label>
                    <input type="text" name="rekening_bank" class="w-full border px-4 py-2 rounded">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Logo Bank</label>
                    <input type="file" name="logo_bank" class="w-full border px-4 py-2 rounded" accept="image/*">
                </div>
                <div class="mb-4">
                    <label class="block font-medium">Atas Nama</label>
                    <input type="text" name="atas_nama" class="w-full border px-4 py-2 rounded" required>
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>