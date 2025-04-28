<?php
session_start();
require_once './database/koneksi.php';

$title = "Login Akun";
ob_start(); // Mulai output buffering, kontennya ditangkap nanti

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Cek apakah akun diblokir
    if ($user['status'] === 'blokir') {
      $error = "Akun Anda telah diblokir. Silakan hubungi administrator.";
    } else {
      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama'];

        $user_id = $user['id'];
        $aksi = "Login";
        $deskripsi = "User " . $user['nama'] . " melakukan login pada " . date('d-m-Y H:i:s');

        $log_query = "INSERT INTO log_aktivitas (user_id, aksi, deskripsi) VALUES (?, ?, ?)";
        $log_stmt = $conn->prepare($log_query);
        $log_stmt->bind_param("iss", $user_id, $aksi, $deskripsi);
        $log_stmt->execute();

        // Arahkan berdasarkan role
        if ($user['role'] === 'superadmin') {
          header("Location: main_superadmin.php?module=superadmin_page");
        } elseif ($user['role'] === 'admin') {
          header("Location: main_admin.php?module=admin_page");
        } else {
          header("Location: main.php?module=landing_page");
        }
        exit();
      } else {
        $error = "Password salah.";
      }
    }
  } else {
    $error = "Email tidak ditemukan.";
  }
}
?>
<div class="flex items-center justify-center min-h-screen bg-gray-50">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-green-700 text-center mb-6">Login</h2>
    <?php if (isset($error)) : ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?= $error ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-4">
        <label for="email" class="block text-gray-700 mb-2">Email</label>
        <input type="email" id="email" name="email" required class="w-full p-2 border rounded" />
      </div>
      <div class="mb-6">
        <label for="password" class="block text-gray-700 mb-2">Password</label>
        <input type="password" id="password" name="password" required class="w-full p-2 border rounded" />
      </div>
      <button type="submit" name="login" class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700">Masuk</button>
      <p class="mt-4 text-center text-sm">Belum punya akun? <a href="?module=register" class="text-green-600 hover:underline">Daftar di sini</a></p>
    </form>
  </div>
</div>