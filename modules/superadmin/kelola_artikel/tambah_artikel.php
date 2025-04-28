<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $gambar_name = '';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $gambar_name = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/artikel/' . $gambar_name);
    }

    $stmt = $conn->prepare("INSERT INTO berita_artikel (judul, isi, gambar) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $judul, $isi, $gambar_name);
    $stmt->execute();

    header("Location: ?module=kelola_artikel");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Artikel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Tambah Artikel & Berita</h1>

            <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow space-y-4 max-w-xl">
                <input name="judul" placeholder="Judul Artikel" required class="w-full border p-2 rounded">
                <textarea name="isi" placeholder="Isi Artikel" rows="6" required class="w-full border p-2 rounded"></textarea>
                <input type="file" name="gambar" accept="image/*" class="w-full border p-2 rounded">
                <button class="bg-green-600 text-white px-4 py-2 rounded">Simpan Artikel</button>
            </form>
        </div>
    </div>
</body>

</html>