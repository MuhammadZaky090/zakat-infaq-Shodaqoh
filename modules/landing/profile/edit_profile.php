<!-- edit_profile.php -->
<?php
session_start();
include "koneksi.php";

// Cek apakah pengguna sudah login
if (!isset($_SESSION['nama'])) {
    header("Location: ?module=login");
    exit;
}

$nama = $_SESSION['nama'];

// Ambil data lengkap user dari database
$query = mysqli_query($conn, "SELECT * FROM users WHERE nama = '$nama'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "<div class='text-center mt-10 text-red-600'>Data pengguna tidak ditemukan.</div>";
    exit;
}

// Proses pembaruan nama, email, atau password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['nama']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = mysqli_real_escape_string($conn, $_POST['password']);

    $password_hash = password_hash($new_password, PASSWORD_DEFAULT); // Menggunakan hashing untuk password

    // Update query untuk nama, email, dan password
    $query_update = "UPDATE users SET nama = '$new_name', email = '$new_email', password = '$password_hash' WHERE nama = '$nama'";

    if (mysqli_query($conn, $query_update)) {
        $_SESSION['nama'] = $new_name; // Update session nama
        header("Location: ?module=profile");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!-- HTML form to edit profile -->
<div class="max-w-2xl mx-auto mt-20 bg-white p-8 rounded-2xl shadow-xl relative">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">👤 Edit Profil</h2>

    <form action="edit_profile.php" method="POST">
        <div class="mb-4">
            <label for="nama" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($user['nama']) ?>"
                class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>"
                class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-semibold text-gray-700">Password Baru</label>
            <input type="password" name="password" id="password" placeholder="Masukkan password baru"
                class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <button type="submit" name="update_profile"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Perbarui Profil
        </button>
    </form>

    <div class="mt-10 text-center">
        <a href="?module=profile"
            class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 shadow transition">
            ⬅️ Kembali ke Profil
        </a>
    </div>
</div>