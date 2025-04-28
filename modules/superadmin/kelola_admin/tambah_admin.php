<?php
session_start();
require_once './database/koneksi.php';

// Cek hak akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'admin'; // default admin

    $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $nama, $email, $password, $role);
    if ($stmt->execute()) {
        header("Location: ?module=kelola_admin");
        exit;
    } else {
        $error = "Gagal menambahkan admin: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6 max-w-xl mx-auto bg-white rounded shadow">
            <h1 class="text-2xl font-bold mb-6">Tambah Admin Baru</h1>

            <?php if (isset($error)) : ?>
                <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block mb-1 font-semibold">Nama</label>
                    <input type="text" name="nama" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Email</label>
                    <input type="email" name="email" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Password</label>
                    <input type="password" name="password" required class="w-full p-2 border rounded">
                </div>

                <div class="flex justify-between mt-6">
                    <a href="?module=kelola_admin" class="text-blue-600 hover:underline">← Kembali</a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                        Simpan Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>