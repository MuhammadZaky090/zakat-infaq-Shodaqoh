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

// Proses upload foto profil
if (isset($_FILES['foto_profile']) && $_FILES['foto_profile']['error'] === 0) {
    $foto = $_FILES['foto_profile'];
    $target_dir = "uploads/profile/";
    $target_file = $target_dir . basename($foto["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $valid_extensions = ["jpg", "jpeg", "png", "gif"];

    if (in_array($imageFileType, $valid_extensions)) {
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($foto["tmp_name"], $target_file)) {
            $query = "UPDATE users SET foto_profile = '" . basename($foto["name"]) . "' WHERE nama = '$nama'";
            if (mysqli_query($conn, $query)) {
                header("Location: ?module=profile");
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Gagal mengupload gambar.";
        }
    } else {
        echo "File yang diupload bukan gambar yang valid.";
    }
}

$user_id = $user['id'];
$riwayat_donasi = [];

// Infaq
$query_infaq = mysqli_query($conn, "SELECT jumlah_infaq AS jumlah, tanggal, 'Infaq' AS jenis FROM pembayaran_infaq WHERE user_id = $user_id AND status = 'terbayar'");
while ($row = mysqli_fetch_assoc($query_infaq)) {
    $riwayat_donasi[] = $row;
}

// Shodaqoh
$query_shodaqoh = mysqli_query($conn, "SELECT jumlah_shodaqoh AS jumlah, tanggal, 'Shodaqoh' AS jenis FROM pembayaran_shodaqoh WHERE user_id = $user_id AND status = 'terbayar'");
while ($row = mysqli_fetch_assoc($query_shodaqoh)) {
    $riwayat_donasi[] = $row;
}

// Zakat
$query_zakat = mysqli_query($conn, "SELECT jumlah_zakat AS jumlah, tanggal, 'Zakat' AS jenis FROM pembayaran_zakat WHERE user_id = $user_id AND status = 'terbayar'");
while ($row = mysqli_fetch_assoc($query_zakat)) {
    $riwayat_donasi[] = $row;
}

// Urutkan berdasarkan tanggal terbaru
usort($riwayat_donasi, function ($a, $b) {
    return strtotime($b['tanggal']) - strtotime($a['tanggal']);
});

$pesan_pengguna = [];
$query_pesan = mysqli_query($conn, "SELECT * FROM pesan_pengguna WHERE user_id = $user_id ORDER BY created_at DESC");
while ($row = mysqli_fetch_assoc($query_pesan)) {
    $pesan_pengguna[] = $row;
}

?>

<!-- HTML START -->
<div class="relative min-h-screen bg-gradient-to-b from-emerald-50 to-white py-12">
    <!-- Ornamental Elements -->
    <div class="absolute top-0 left-0 right-0 h-16 bg-emerald-600 opacity-90"></div>
    <div class="absolute top-16 left-0 right-0 h-1 bg-emerald-400"></div>
    
    <div class="absolute bottom-0 left-0 w-24 h-24 rounded-tr-full bg-emerald-100 opacity-70"></div>
    <div class="absolute top-32 right-0 w-32 h-32 rounded-bl-full bg-emerald-100 opacity-70"></div>
    
    <!-- Decorative Pattern -->
    <div class="absolute top-24 left-4 w-6 h-6 rounded-full border-2 border-emerald-300 opacity-50"></div>
    <div class="absolute top-36 left-8 w-4 h-4 rounded-full border-2 border-emerald-300 opacity-50"></div>
    <div class="absolute top-20 right-12 w-8 h-8 rounded-full border-2 border-emerald-300 opacity-50"></div>
    
    <div class="max-w-4xl mx-auto relative">
        <!-- Main Profile Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-10 relative border border-emerald-100">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 h-36 relative">
                <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
                    <?php if ($user['foto_profile']): ?>
                        <img src="uploads/profile/<?= htmlspecialchars($user['foto_profile']) ?>"
                            alt="Foto Profil"
                            class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg cursor-pointer"
                            id="profileImage">
                    <?php else: ?>
                        <div class="w-32 h-32 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-700 font-bold text-xl border-4 border-white shadow-lg cursor-pointer"
                            id="profileImage">
                            <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    
                    <button id="editPhotoButton" 
                        class="absolute bottom-2 right-2 bg-white text-emerald-700 rounded-full p-2 shadow-md hover:bg-emerald-50 transition duration-200 border border-emerald-200">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                
                <div class="absolute top-4 left-6">
                    <div class="text-white text-opacity-90 text-xl font-light">Profil Pengguna</div>
                </div>
            </div>
            
            <div class="pt-20 pb-6 px-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-2"><?= htmlspecialchars($user['nama']) ?></h2>
                <p class="text-center text-emerald-600 mb-6">
                    <span class="inline-block px-4 py-1 rounded-full text-sm font-medium bg-emerald-100">
                        <?= htmlspecialchars($user['role']) ?>
                    </span>
                </p>
                
                <div class="grid md:grid-cols-2 gap-6 mt-8">
                    <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100 shadow-sm">
                        <h3 class="text-lg font-semibold text-emerald-800 border-b border-emerald-200 pb-3 mb-4">
                            <i class="fas fa-user-circle mr-2 text-emerald-600"></i>Informasi Akun
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-emerald-600 font-medium">Email</p>
                                <p class="text-gray-700"><?= htmlspecialchars($user['email']) ?></p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-emerald-600 font-medium">Terdaftar Sejak</p>
                                <p class="text-gray-700"><?= date('d F Y, H:i', strtotime($user['created_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-emerald-50 p-6 rounded-xl border border-emerald-100 shadow-sm">
                        <h3 class="text-lg font-semibold text-emerald-800 border-b border-emerald-200 pb-3 mb-4">
                            <i class="fas fa-hand-holding-heart mr-2 text-emerald-600"></i>Ringkasan Donasi
                        </h3>
                        
                        <?php 
                        $total_donasi = 0;
                        $count_donasi = count($riwayat_donasi);
                        foreach($riwayat_donasi as $donasi) {
                            $total_donasi += $donasi['jumlah'];
                        }
                        ?>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-emerald-600 font-medium">Total Donasi</p>
                                <p class="text-2xl font-bold text-gray-700">Rp<?= number_format($total_donasi, 0, ',', '.') ?></p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-emerald-600 font-medium">Jumlah Transaksi</p>
                                <p class="text-xl font-semibold text-gray-700"><?= $count_donasi ?> transaksi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Donasi Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-10 border border-emerald-100">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 py-4 px-6">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-history mr-3"></i>Riwayat Donasi Anda
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (count($riwayat_donasi) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-xl overflow-hidden text-left">
                            <thead class="bg-emerald-100 text-emerald-800">
                                <tr>
                                    <th class="py-3 px-4 font-medium">Jenis Donasi</th>
                                    <th class="py-3 px-4 font-medium">Jumlah</th>
                                    <th class="py-3 px-4 font-medium">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-emerald-100">
                                <?php foreach ($riwayat_donasi as $donasi): ?>
                                    <tr class="hover:bg-emerald-50 transition duration-150">
                                        <td class="py-3 px-4">
                                            <?php if($donasi['jenis'] == 'Infaq'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-donate mr-1"></i> Infaq
                                                </span>
                                            <?php elseif($donasi['jenis'] == 'Shodaqoh'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <i class="fas fa-gift mr-1"></i> Shodaqoh
                                                </span>
                                            <?php elseif($donasi['jenis'] == 'Zakat'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    <i class="fas fa-hand-holding-usd mr-1"></i> Zakat
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4 font-medium">Rp<?= number_format($donasi['jumlah'], 0, ',', '.') ?></td>
                                        <td class="py-3 px-4"><?= date('d M Y', strtotime($donasi['tanggal'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 bg-emerald-50 rounded-lg">
                        <div class="text-emerald-500 text-5xl mb-3">
                            <i class="fas fa-donate"></i>
                        </div>
                        <p class="text-gray-600">Belum ada riwayat donasi yang tercatat.</p>
                        <a href="?module=donasi" class="mt-4 inline-block px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-200">
                            Mulai Berdonasi
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Riwayat Zakat Card -->
        <?php
        $query_zakat = mysqli_query($conn, "SELECT * FROM pembayaran_zakat WHERE user_id = $user_id AND status = 'terbayar'");
        ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-10 border border-emerald-100">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 py-4 px-6">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-certificate mr-3"></i>Sertifikat Zakat
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (mysqli_num_rows($query_zakat) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white rounded-xl overflow-hidden text-left">
                            <thead class="bg-emerald-100 text-emerald-800">
                                <tr>
                                    <th class="py-3 px-4 font-medium">Jumlah Zakat</th>
                                    <th class="py-3 px-4 font-medium">Tanggal</th>
                                    <th class="py-3 px-4 font-medium">Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 divide-y divide-emerald-100">
                                <?php while ($zakat = mysqli_fetch_assoc($query_zakat)): ?>
                                    <tr class="hover:bg-emerald-50 transition duration-150">
                                        <td class="py-3 px-4 font-medium">Rp<?= number_format($zakat['jumlah_zakat'], 0, ',', '.') ?></td>
                                        <td class="py-3 px-4"><?= date('d M Y', strtotime($zakat['tanggal'])) ?></td>
                                        <td class="py-3 px-4">
                                            <a href="?module=unduh_sertifikat_zakat&id=<?= $zakat['id'] ?>"
                                                class="inline-flex items-center px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">
                                                <i class="fas fa-download mr-2"></i> Unduh
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 bg-emerald-50 rounded-lg">
                        <div class="text-emerald-500 text-5xl mb-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <p class="text-gray-600">Belum ada sertifikat zakat yang tersedia.</p>
                        <a href="?module=zakat" class="mt-4 inline-block px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-200">
                            Bayar Zakat
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Pesan dari Pengguna Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-10 border border-emerald-100">
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 py-4 px-6">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <i class="fas fa-comments mr-3"></i>Riwayat Pesan Anda
                </h3>
            </div>
            
            <div class="p-6">
                <?php if (count($pesan_pengguna) > 0): ?>
                    <div class="space-y-6">
                        <?php foreach ($pesan_pengguna as $pesan): ?>
                            <div class="border border-emerald-100 rounded-xl overflow-hidden shadow-sm">
                                <div class="bg-emerald-50 p-4 border-b border-emerald-100">
                                    <div class="flex justify-between items-center">
                                        <h4 class="font-medium text-emerald-800"><?= htmlspecialchars($pesan['subjek']) ?></h4>
                                        <span class="text-xs text-gray-500"><?= date('d M Y, H:i', strtotime($pesan['created_at'])) ?></span>
                                    </div>
                                </div>
                                
                                <div class="p-4 bg-white">
                                    <div class="text-gray-700">
                                        <?= nl2br(htmlspecialchars($pesan['isi'])) ?>
                                    </div>
                                    
                                    <?php if ($pesan['balasan']): ?>
                                        <div class="mt-4 pt-4 border-t border-dashed border-emerald-200">
                                            <div class="flex items-center text-emerald-700 mb-2">
                                                <i class="fas fa-reply mr-2"></i>
                                                <span class="font-medium">Balasan Admin:</span>
                                            </div>
                                            <div class="text-gray-700 bg-emerald-50 p-3 rounded-lg">
                                                <?= nl2br(htmlspecialchars($pesan['balasan'])) ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="mt-4 pt-4 border-t border-dashed border-gray-200 text-center">
                                            <span class="inline-block px-4 py-1 bg-gray-100 text-gray-500 rounded-full text-sm italic">
                                                <i class="fas fa-clock mr-1"></i> Menunggu balasan
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8 bg-emerald-50 rounded-lg">
                        <div class="text-emerald-500 text-5xl mb-3">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <p class="text-gray-600">Belum ada pesan yang Anda kirimkan.</p>
                        <a href="?module=kontak" class="mt-4 inline-block px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition duration-200">
                            Kirim Pesan
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Tombol Kembali -->
        <div class="text-center pb-10">
            <a href="?module=landing_page"
                class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-lg shadow-md hover:bg-emerald-700 transition duration-200">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<!-- Form Upload Modal -->
<div id="uploadForm" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 shadow-2xl w-full max-w-md border border-emerald-100 relative">
        <button type="button" onclick="document.getElementById('uploadForm').classList.add('hidden')"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="text-center mb-6">
            <div class="inline-block p-3 bg-emerald-100 text-emerald-600 rounded-full mb-3">
                <i class="fas fa-camera text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Upload Foto Profil</h3>
            <p class="text-gray-500 text-sm mt-1">Pilih foto terbaik Anda untuk ditampilkan</p>
        </div>
        
        <form action="?module=profile" method="POST" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="foto_profile" class="block text-sm font-medium text-gray-700 mb-2">Pilih Gambar</label>
                <div class="border-2 border-dashed border-emerald-200 rounded-lg p-4 text-center hover:border-emerald-400 transition">
                    <input type="file" name="foto_profile" id="foto_profile" required
                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="mt-2 text-xs text-gray-500">JPG, JPEG, PNG, atau GIF (Maks. 2MB)</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="document.getElementById('uploadForm').classList.add('hidden')"
                    class="py-2 px-4 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                    Batal
                </button>
                
                <button type="submit"
                    class="py-2 px-4 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                    <i class="fas fa-upload mr-1"></i> Upload
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById("editPhotoButton").addEventListener("click", function() {
        document.getElementById("uploadForm").classList.remove("hidden");
    });
    
    document.getElementById("profileImage").addEventListener("click", function() {
        document.getElementById("uploadForm").classList.remove("hidden");
    });
</script>