<?php
require_once './database/koneksi.php';

if (isset($_POST['register'])) {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm'];

  if ($password !== $confirm) {
    $error = "Password dan konfirmasi tidak sama.";
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nama, $email, $hash);

    if ($stmt->execute()) {
      $user_id = $conn->insert_id; // Dapatkan ID user yang baru saja dibuat
      $aksi = "Register";
      $deskripsi = "User " . $nama . " berhasil melakukan registrasi pada " . date('d-m-Y H:i:s');

      $log_query = "INSERT INTO log_aktivitas (user_id, aksi, deskripsi) VALUES (?, ?, ?)";
      $log_stmt = $conn->prepare($log_query);
      $log_stmt->bind_param("iss", $user_id, $aksi, $deskripsi);
      $log_stmt->execute();
      header("Location: ?module=login");
      exit();
    } else {
      $error = "Gagal mendaftar. Email mungkin sudah digunakan.";
    }
  }
}
?>


<div class="flex items-center justify-center min-h-screen bg-gray-50">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-bold text-green-700 text-center mb-6">Register</h2>
    <?php if (isset($error)) : ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?= $error ?>
      </div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-4">
        <label for="nama" class="block text-gray-700 mb-2">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" required class="w-full p-2 border rounded" />
      </div>
      <div class="mb-4">
        <label for="email" class="block text-gray-700 mb-2">Email</label>
        <input type="email" id="email" name="email" required class="w-full p-2 border rounded" />
      </div>
      <div class="mb-4">
        <label for="password" class="block text-gray-700 mb-2">Password</label>
        <input type="password" id="password" name="password" required class="w-full p-2 border rounded" />
      </div>
      <div class="mb-6">
        <label for="confirm" class="block text-gray-700 mb-2">Konfirmasi Password</label>
        <input type="password" id="confirm" name="confirm" required class="w-full p-2 border rounded" />
      </div>
      <button type="submit" name="register" class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700">Daftar</button>
      <p class="mt-4 text-center text-sm">Sudah punya akun? <a href="?module=login" class="text-green-600 hover:underline">Masuk di sini</a></p>
    </form>
  </div>
</div>

