<?php
session_start();
require_once './database/koneksi.php';

// Logout langsung
if (isset($_GET['module']) && $_GET['module'] === 'logout') {
  session_destroy();
  header("Location: index.php");
  exit;
}

// Ambil info user jika login
$namaPengguna = $emailPengguna = null;
if (isset($_SESSION['user_id'])) {
  $idUser = $_SESSION['user_id'];
  $query = $conn->prepare("SELECT nama, email FROM users WHERE id = ?");
  $query->bind_param("i", $idUser);
  $query->execute();
  $result = $query->get_result();
  if ($row = $result->fetch_assoc()) {
    $namaPengguna = $row['nama'];
    $emailPengguna = $row['email'];
  }
}

$pesanSukses = $pesanError = '';

// Proses kirim pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $subjek = trim($_POST['subjek'] ?? '');
  $isi = trim($_POST['isi'] ?? '');
  $user_id = $_SESSION['user_id'] ?? null;

  if ($user_id && $subjek !== '' && $isi !== '') {
    // Ambil nama & email dari sesi pengguna yang login
    $stmtUser = $conn->prepare("SELECT nama, email FROM users WHERE id = ?");
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();

    if ($rowUser = $resultUser->fetch_assoc()) {
      $namaPengirim = $rowUser['nama'];
      $emailPengirim = $rowUser['email'];

      // Simpan ke tabel pesan
      $stmt = $conn->prepare("INSERT INTO pesan_pengguna (user_id, nama_pengirim, email_pengirim, subjek, isi, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("issss", $user_id, $namaPengirim, $emailPengirim, $subjek, $isi);

      if ($stmt->execute()) {
        $pesanSukses = "Pesan berhasil dikirim!";
      } else {
        $pesanError = "Terjadi kesalahan saat mengirim pesan.";
      }
    } else {
      $pesanError = "Pengguna tidak ditemukan.";
    }
  } else {
    $pesanError = "Subjek dan isi pesan tidak boleh kosong.";
  }
}

// Ambil data program & artikel
$programs = $conn->query("SELECT * FROM program_zakat ORDER BY created_at DESC");
$berita = $conn->query("SELECT * FROM berita_artikel ORDER BY created_at DESC LIMIT 3");
?>




<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ZIS | Zakat Infaq Shodaqoh</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <footer class="bg-gradient-to-b from-green-700 to-green-900 text-white py-12">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-white text-gray-800 font-sans">
  <!-- Navbar -->
  <header class="bg-green-300 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <!-- Gambar di navbar -->
      <img src="assets/image/logo.png" alt="ZIS Logo" class="w-40 h-auto">
      <nav class="space-x-4 relative">
        <a href="#" class="hover:underline">Beranda</a>
        <a href="#program" class="hover:underline">Program</a>
        <a href="#kalkulator" class="hover:underline">Kalkulator Zakat</a>
        <a href="#artikel" class="hover:underline">Artikel</a>

        <?php if ($namaPengguna): ?>
          <div class="inline-block relative group">
            <button class="bg-white text-green-600 px-4 py-2 rounded hover:bg-green-100">
              <?= htmlspecialchars($namaPengguna) ?> ▼
            </button>
            <div class="absolute right-0 mt-2 w-48 bg-white rounded shadow-md opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-200 z-10">
              <a href="?module=profile" class="block px-4 py-2 text-gray-700 hover:bg-green-100">Profil</a>
              <a href="?module=logout" class="block px-4 py-2 text-gray-700 hover:bg-green-100">Logout</a>
            </div>
          </div>
        <?php else: ?>
          <a href="?module=login" class="bg-white text-green-600 px-4 py-2 rounded hover:bg-green-100">Login</a>
          <a href="?module=register" class="bg-green-800 text-white px-4 py-2 rounded hover:bg-green-700">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>


  <!-- Hero Section -->
  <section class="bg-green-50 py-20 text-center">
    <h2 class="text-4xl font-bold text-green-700 mb-4">Berzakat Lebih Mudah, Aman & Transparan</h2>
    <p class="max-w-xl mx-auto text-gray-600 mb-6">Salurkan Zakat, Infaq, dan Shodaqoh Anda secara online dengan mudah dan aman. Pantau laporan distribusi dana secara real-time.</p>
    <a href="#program" class="bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700">Lihat Program</a>
  </section>

  <section id="program" class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
      <h3 class="text-3xl font-semibold text-center text-green-700 mb-10">Program ZIS Kami</h3>
      <div class="grid md:grid-cols-3 gap-8">
        <?php while ($p = $programs->fetch_assoc()): ?>
          <div class="p-6 bg-green-50 rounded shadow hover:shadow-lg transition flex flex-col items-center">
            <!-- Gambar Program -->
            <?php
            $gambar_path = 'uploads/program/' . htmlspecialchars($p['gambar']);
            if (file_exists($gambar_path)): ?>
              <img src="<?= $gambar_path ?>" alt="<?= htmlspecialchars($p['nama_program']) ?>" class="w-full h-40 object-cover rounded-md mb-4">
            <?php else: ?>
              <img src="path/to/default-image.jpg" alt="Gambar tidak tersedia" class="w-full h-40 object-cover rounded-md mb-4">
            <?php endif; ?>
            <!-- Nama Program -->
            <h4 class="text-xl font-bold text-green-700 mb-2"><?= htmlspecialchars($p['nama_program']) ?></h4>
            <!-- Jenis Zakat (sebagai deskripsi singkat) -->
            <p class="text-gray-600 text-center mb-4"><?= htmlspecialchars($p['jenis_zakat']) ?></p>
            <div class="flex justify-center w-full">
              <!-- Link untuk membayar zakat -->
              <a href="?module=<?= strtolower($p['jenis_zakat']) ?>" class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 w-full text-center">Bayar <?= $p['jenis_zakat'] ?></a>
            </div>
          </div>
        <?php endwhile ?>
      </div>
    </div>
  </section>

  <!-- Kalkulator Zakat Section -->
  <section id="kalkulator" class="bg-green-100 py-16 text-center">
    <h3 class="text-3xl font-semibold text-green-800 mb-4">Kalkulator Zakat</h3>
    <p class="text-gray-700 mb-6">Hitung zakat Anda secara otomatis sesuai jenis penghasilan atau kepemilikan Anda.</p>
    <a href="?module=kalkulator" class="bg-green-700 text-white px-5 py-3 rounded-full hover:bg-green-800">Gunakan Kalkulator</a>
  </section>

  <!-- Artikel / Berita -->
  <section id="artikel" class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
      <h3 class="text-3xl font-semibold text-center text-green-700 mb-10">Artikel & Berita</h3>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php while ($row = $berita->fetch_assoc()): ?>
          <div class="bg-green-50 p-6 rounded shadow hover:shadow-lg">
            <?php if ($row['gambar']): ?>
              <img src="uploads/artikel/<?= $row['gambar'] ?>" class="w-full h-48 object-cover rounded mb-4">
            <?php endif; ?>
            <h4 class="text-xl font-bold text-green-700 mb-2"><?= htmlspecialchars($row['judul']) ?></h4>
            <p class="text-gray-600"><?= mb_strimwidth(strip_tags($row['isi']), 0, 100, '...') ?></p>
          </div>
        <?php endwhile ?>
      </div>
    </div>
  </section>

  <section id="kontak" class="py-16 bg-green-100">
    <div class="max-w-4xl mx-auto px-4">
      <h3 class="text-3xl font-semibold text-center text-green-800 mb-6">Kirim Pesan ke Admin</h3>
      <form method="POST" class="bg-white p-6 rounded shadow space-y-4">
        <?php if ($pesanSukses): ?>
          <p class="text-green-700 bg-green-100 p-2 rounded"><?= $pesanSukses ?></p>
        <?php elseif ($pesanError): ?>
          <p class="text-red-700 bg-red-100 p-2 rounded"><?= $pesanError ?></p>
        <?php endif; ?>

        <?php if ($namaPengguna): ?>
          <input type="nama" name="nama" value="<?= htmlspecialchars($namaPengguna) ?>" readonly class="w-full px-4 py-2 border rounded bg-gray-100">
        <?php endif; ?>


        <input type="text" name="subjek" required placeholder="Subjek Pesan" class="w-full px-4 py-2 border rounded">
        <textarea name="isi" rows="5" required placeholder="Tulis pesan Anda..." class="w-full px-4 py-2 border rounded"></textarea>

        <?php if ($emailPengguna): ?>
          <input type="email" name="email" value="<?= htmlspecialchars($emailPengguna) ?>" readonly class="w-full px-4 py-2 border rounded bg-gray-100">
        <?php endif; ?>

        <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded hover:bg-green-800">Kirim Pesan</button>
      </form>

    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gradient-to-b from-green-700 to-green-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-mosque mr-2"></i> ZIS Online
                    </h3>
                    <p class="text-green-100 mb-4">Platform digital untuk memudahkan Anda menunaikan Zakat, Infaq, dan Sedekah secara online dengan aman dan tepercaya.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-green-200 bg-green-600 hover:bg-green-500 h-10 w-10 rounded-full flex items-center justify-center transition-all">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/masjidalfajar.sby/" class="text-white hover:text-green-200 bg-green-600 hover:bg-green-500 h-10 w-10 rounded-full flex items-center justify-center transition-all">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/@masjidalfajar-sby" class="text-white hover:text-green-200 bg-green-600 hover:bg-green-500 h-10 w-10 rounded-full flex items-center justify-center transition-all">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3 text-green-100">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-green-300"></i>
                            <span>MASJID AL FAJAR | Jl. Cipta Menanggal Dalam, Kota Surabaya</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-green-300"></i>
                            <span>+6281-3333-48074</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-green-300"></i>
                            <span>alfajarmenanggalsurabaya@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-green-600 mt-8 pt-6 text-center text-green-100">
                <div class="flex justify-center mb-4">
                    <div class="h-1 w-24 bg-green-400 rounded"></div>
                </div>
                <p>&copy; 2025 ZIS Online. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</body>

</html>