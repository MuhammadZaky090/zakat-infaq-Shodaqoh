<?php
session_start();
require_once './database/koneksi.php'; // Pastikan file koneksi sesuai

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ?module=login");
    exit;
}

$idUser = $_SESSION['user_id'];

// Ambil data metode pembayaran dari database
$query = "SELECT * FROM metode_pembayaran";
$result = $conn->query($query);
$metodePembayaranData = $result->fetch_all(MYSQLI_ASSOC);

// Tangani proses pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlahShodaqoh = $_POST['jumlah_shodaqoh'];
    $metodePembayaran = $_POST['metode_pembayaran'];

    $stmt = $conn->prepare("INSERT INTO pembayaran_shodaqoh (user_id, jumlah_shodaqoh, metode_pembayaran, tanggal) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("ids", $idUser, $jumlahShodaqoh, $metodePembayaran);
    $stmt->execute();

    header("Location: ?module=pembayaran_berhasil");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pembayaran Shodaqoh</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-gray-800 font-sans">
    <header class="bg-green-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">Pembayaran Shodaqoh</h1>
        </div>
    </header>

    <section class="max-w-lg mx-auto my-16">
        <h2 class="text-3xl text-center text-green-700 mb-4">Pembayaran Shodaqoh</h2>
        <form method="POST">
            <div class="mb-4">
                <label for="jumlah_shodaqoh" class="block text-sm font-medium text-gray-700">Jumlah Shodaqoh</label>
                <input type="text" name="jumlah_shodaqoh" id="jumlah_shodaqoh" class="mt-2 p-2 border border-gray-300 rounded w-full" required />

            </div>

            <div class="mb-6">
                <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="metode_pembayaran" id="metode_pembayaran" class="mt-2 p-2 border border-gray-300 rounded w-full" required>
                    <option value="">-- Pilih Metode --</option>
                    <?php foreach ($metodePembayaranData as $metode): ?>
                        <option value="<?php echo $metode['metode']; ?>"><?php echo $metode['metode']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="info_qris" class="hidden mb-6 text-center">
                <p class="mb-2 font-medium text-xl">Scan QRIS berikut:</p>
                <img id="gambar_qris" src="" alt="QRIS" class="mx-auto w-64 h-64 object-contain border rounded shadow-md transform transition duration-500 hover:scale-105" />
                <p class="mt-2 font-medium text-lg">Atas Nama: <span id="nama_qris"></span></p>
            </div>


            <div id="info_rekening" class="hidden mb-6 text-center">
                <p class="font-medium text-xl">Transfer ke:</p>
                <p class="text-gray-700 mt-4 flex justify-center items-center">
                    <img id="logo_bank" src="" alt="Logo Bank" class="w-24 h-12 object-contain shadow-md mr-4 transform transition duration-500 hover:scale-110" />
                    <span id="rekening_bank" class="text-lg font-semibold"></span>
                </p>
                <p class="mt-2 font-medium text-lg">Atas Nama: <span id="nama_rekening"></span></p>
            </div>



            <div class="flex justify-center">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700">Bayar Shodaqoh</button>
            </div>
        </form>

    </section>

    <footer class="bg-green-600 text-white py-6 text-center">
        <p>&copy; 2025 Yayasan Al Fajar. Semua hak dilindungi.</p>
    </footer>

    <script>
        const dataPembayaran = <?php echo json_encode($metodePembayaranData); ?>;

        document.getElementById('metode_pembayaran').addEventListener('change', function() {
            const metode = this.value;
            const qrisInfo = document.getElementById('info_qris');
            const rekeningInfo = document.getElementById('info_rekening');

            qrisInfo.classList.add('hidden');
            rekeningInfo.classList.add('hidden');

            const data = dataPembayaran.find(item => item.metode === metode);
            if (!data) return;

            if (metode === 'QRIS') {
                document.getElementById('gambar_qris').src = 'uploads/metode_pembayaran/' + data.gambar_qris;
                document.getElementById('nama_qris').textContent = data.atas_nama;
                qrisInfo.classList.remove('hidden');
            } else if (metode === 'Rekening Bank') {
                document.getElementById('logo_bank').src = 'uploads/metode_pembayaran/' + data.logo_bank;
                document.getElementById('rekening_bank').textContent = data.rekening_bank;
                document.getElementById('nama_rekening').textContent = data.atas_nama;
                rekeningInfo.classList.remove('hidden');
            }

        });

        const inputJumlah = document.getElementById("jumlah_shodaqoh");

        inputJumlah.addEventListener("input", function(e) {
            let value = this.value.replace(/\D/g, ""); // Hapus semua non-digit
            value = new Intl.NumberFormat("id-ID").format(value); // Format 3 digit
            this.value = value;
        });

        // Optional: Saat submit, hapus titik agar bisa disimpan sebagai angka
        document.querySelector("form").addEventListener("submit", function(e) {
            const rawValue = inputJumlah.value.replace(/\./g, "");
            inputJumlah.value = rawValue;
        });
    </script>

</body>

</html>