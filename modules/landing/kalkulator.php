<?php
session_start();
require_once './database/koneksi.php';

if (isset($_GET['module']) && $_GET['module'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

$namaPengguna = null;
if (isset($_SESSION['user_id'])) {
    $idUser = $_SESSION['user_id'];
    $query = $conn->prepare("SELECT nama FROM users WHERE id = ?");
    $query->bind_param("i", $idUser);
    $query->execute();
    $result = $query->get_result();
    if ($row = $result->fetch_assoc()) {
        $namaPengguna = $row['nama'];
    }
}

function hitungZakat($jumlah, $nisab, $persentaseZakat)
{
    return ($jumlah >= $nisab) ? ($jumlah * $persentaseZakat) / 100 : 0;
}

$zakat = 0;
$nisab = 6530000;
$persentaseZakat = 2.5;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['jumlah']) && is_numeric(str_replace('.', '', $_POST['jumlah']))) {
        $jenisZakat = $_POST['jenis_zakat'];
        $jumlah = floatval(str_replace('.', '', $_POST['jumlah']));

        switch ($jenisZakat) {
            case 'Emas dan Perak':
            case 'Uang dan Surat Berharga':
            case 'Perniagaan':
            case 'Pertambangan':
            case 'Perindustrian':
                $persentaseZakat = 2.5;
                $zakat = hitungZakat($jumlah, $nisab, $persentaseZakat);
                break;

            case 'Pertanian':
                $irigasi = $_POST['irigasi'] ?? 'irigasi';
                $persentaseZakat = ($irigasi === 'irigasi') ? 5 : 10;
                $zakat = hitungZakat($jumlah, $nisab, $persentaseZakat);
                break;

            case 'Peternakan dan Perikanan':
                $persentaseZakat = 5;
                $zakat = hitungZakat($jumlah, $nisab, $persentaseZakat);
                break;

            case 'Rikaz':
                $persentaseZakat = 20;
                $zakat = hitungZakat($jumlah, $nisab, $persentaseZakat);
                break;

            case 'Profesi':
                $persentaseZakat = 2.5;
                $zakat = hitungZakat($jumlah, $nisab, $persentaseZakat);
                break;

            default:
                $zakat = 0;
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kalkulator Zakat | ZIS Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        function formatAngka(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">
    <header class="bg-green-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">ZIS Online</h1>
            <nav class="space-x-4 relative">
                <a href="index.php" class="hover:underline">Beranda</a>
                <a href="#program" class="hover:underline">Program</a>
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

    <section id="kalkulator" class="py-16 bg-gradient-to-b from-green-50 to-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <div class="inline-block mb-4">
                    <i class="fas fa-calculator text-green-600 text-5xl mb-2"></i>
                </div>
                <h3 class="text-3xl font-bold text-green-800">Kalkulator Zakat</h3>
                <div class="flex justify-center mt-3">
                    <div class="h-1 w-24 bg-green-600 rounded"></div>
                </div>
                <p class="text-gray-700 mt-4 max-w-2xl mx-auto">Hitung zakat Anda berdasarkan jenis harta yang dimiliki dengan mudah dan tepat sesuai ketentuan syariah.</p>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden relative">
                    <!-- Islamic ornamental pattern at top -->
                    <div class="h-12 bg-green-600 flex items-center justify-center">
                        <div class="flex space-x-2">
                            <?php for ($i = 0; $i < 9; $i++): ?>
                                <div class="w-6 h-6 bg-white bg-opacity-20 transform rotate-45"></div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- Side ornamental patterns -->
                    <div class="absolute left-0 top-12 bottom-0 w-3 bg-green-100 flex flex-col justify-around items-center">
                        <?php for ($i = 0; $i < 10; $i++): ?>
                            <div class="w-2 h-2 bg-green-300 rounded-full"></div>
                        <?php endfor; ?>
                    </div>
                    <div class="absolute right-0 top-12 bottom-0 w-3 bg-green-100 flex flex-col justify-around items-center">
                        <?php for ($i = 0; $i < 10; $i++): ?>
                            <div class="w-2 h-2 bg-green-300 rounded-full"></div>
                        <?php endfor; ?>
                    </div>
                    
                    <div class="px-8 py-6 mx-3">
                        <form method="POST" class="space-y-6">
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="flex-1">
                                    <label for="jenis_zakat" class="block text-gray-700 font-medium mb-2">
                                        <i class="fas fa-tags text-green-600 mr-2"></i>Jenis Zakat
                                    </label>
                                    <div class="relative">
                                        <select name="jenis_zakat" id="jenis_zakat" class="w-full px-4 py-3 border border-green-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700" onchange="toggleIrigasi()">
                                            <option value="Emas dan Perak">Zakat Emas dan Perak</option>
                                            <option value="Uang dan Surat Berharga">Zakat Uang dan Surat Berharga</option>
                                            <option value="Perniagaan">Zakat Perniagaan</option>
                                            <option value="Pertanian">Zakat Pertanian</option>
                                            <option value="Profesi">Zakat Profesi</option>
                                            <option value="Rikaz">Zakat Rikaz</option>
                                        </select>
                                        <div class="absolute right-3 top-3 text-green-600 pointer-events-none">
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="irigasi_div" class="hidden">
                                <label for="irigasi" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-water text-green-600 mr-2"></i>Jenis Pengairan
                                </label>
                                <div class="relative">
                                    <select name="irigasi" id="irigasi" class="w-full px-4 py-3 border border-green-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700">
                                        <option value="irigasi">Dengan Irigasi (5%)</option>
                                        <option value="non_irigasi">Tanpa Irigasi/Air Hujan (10%)</option>
                                    </select>
                                    <div class="absolute right-3 top-3 text-green-600 pointer-events-none">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 italic">*Persentase zakat berbeda sesuai dengan jenis pengairan</p>
                            </div>

                            <div>
                                <label for="jumlah" class="block text-gray-700 font-medium mb-2">
                                    <i class="fas fa-coins text-green-600 mr-2"></i>Jumlah Harta (Rp)
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3 top-3 text-gray-500">Rp</div>
                                    <input type="text" name="jumlah" id="jumlah" class="w-full pl-10 pr-4 py-3 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-gray-700" required placeholder="Contoh: 10.000.000" oninput="formatAngka(this)">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">*Nisab: Rp <?= number_format($nisab, 0, ',', '.') ?></p>
                            </div>

                            <div class="flex flex-col md:flex-row gap-4 pt-4">
                                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all flex-1 flex items-center justify-center font-medium">
                                    <i class="fas fa-calculator mr-2"></i> Hitung Zakat
                                </button>
                                <a href="index.php" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-all text-center flex items-center justify-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if ($zakat > 0): ?>
                    <div class="mt-8 bg-green-50 border-l-4 border-green-600 p-6 rounded-lg shadow-md">
                        <div class="flex items-center mb-3">
                            <div class="bg-green-600 p-3 rounded-full mr-4">
                                <i class="fas fa-hand-holding-heart text-white text-xl"></i>
                            </div>
                            <h4 class="text-xl font-bold text-green-800">Zakat yang harus dibayarkan:</h4>
                        </div>
                        <div class="pl-16">
                            <p class="text-3xl font-bold text-green-600">Rp <?= number_format($zakat, 0, ',', '.') ?></p>
                            <p class="text-sm text-gray-600 mt-2">Silahkan salurkan zakat Anda melalui lembaga ZIS terpercaya atau langsung kepada yang berhak menerimanya.</p>
                            <div class="mt-4">
                                <a href="?module=zakat" class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-all text-sm">
                                    <i class="fas fa-donate mr-2"></i> Bayar Zakat Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="bg-yellow-500 p-3 rounded-full mr-4">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold text-yellow-800">Belum Mencapai Nisab</h4>
                                <p class="text-gray-600 mt-1">Jumlah harta Anda belum mencapai nisab untuk kewajiban zakat (Rp <?= number_format($nisab, 0, ',', '.') ?>), tetapi Anda tetap bisa berinfaq atau bersedekah.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Informasi Tambahan -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-600">
                        <div class="text-green-600 mb-3">
                            <i class="fas fa-book-open text-2xl"></i>
                        </div>
                        <h5 class="text-lg font-semibold mb-2">Tentang Nisab</h5>
                        <p class="text-sm text-gray-600">Nisab adalah batasan minimal harta yang wajib dikeluarkan zakatnya. Setara dengan 85 gram emas atau 595 gram perak.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-600">
                        <div class="text-green-600 mb-3">
                            <i class="fas fa-percentage text-2xl"></i>
                        </div>
                        <h5 class="text-lg font-semibold mb-2">Persentase Zakat</h5>
                        <p class="text-sm text-gray-600">Persentase zakat bervariasi dari 2,5% hingga 20% tergantung pada jenis harta yang dizakatkan.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-600">
                        <div class="text-green-600 mb-3">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <h5 class="text-lg font-semibold mb-2">Penerima Zakat</h5>
                        <p class="text-sm text-gray-600">Zakat disalurkan kepada 8 golongan (asnaf) yang berhak menerimanya sesuai ketentuan Al-Quran.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleIrigasi() {
            const jenis = document.getElementById("jenis_zakat").value;
            document.getElementById("irigasi_div").style.display = (jenis === "Pertanian") ? "block" : "none";
        }

        // Inisialisasi saat load ulang
        window.onload = toggleIrigasi;
    </script>

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