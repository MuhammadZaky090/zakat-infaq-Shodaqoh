<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: ?module=kelola_admin");
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    echo "Data admin tidak ditemukan.";
    exit;
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update = $conn->prepare("UPDATE users SET nama = ?, email = ?, role = ? WHERE id = ?");
    $update->bind_param("sssi", $nama, $email, $role, $id);
    $update->execute();

    header("Location: ?module=kelola_admin");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Edit Data Admin</h1>

            <div class="bg-white p-6 rounded shadow max-w-xl">
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700">Nama</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($admin['nama']) ?>" class="w-full px-4 py-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" class="w-full px-4 py-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Role</label>
                        <select name="role" class="w-full px-4 py-2 border rounded" required>
                            <option value="admin" <?= $admin['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= $admin['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 w-full">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>