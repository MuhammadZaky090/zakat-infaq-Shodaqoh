<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Tambah program baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_program'])) {
    $nama = $_POST['nama_program'];
    $jenis = $_POST['jenis_zakat'];
    $deskripsi = $_POST['deskripsi_program'];

    // Proses upload gambar
    if (isset($_FILES['gambar_program']) && $_FILES['gambar_program']['error'] === 0) {
        $gambar_name = $_FILES['gambar_program']['name'];
        $gambar_tmp = $_FILES['gambar_program']['tmp_name'];
        $gambar_size = $_FILES['gambar_program']['size'];
        $gambar_ext = pathinfo($gambar_name, PATHINFO_EXTENSION);

        // Tentukan folder upload dan nama file gambar yang akan disimpan
        $upload_dir = 'uploads/program/';
        $gambar_new_name = uniqid('', true) . '.' . $gambar_ext;
        $gambar_path = $upload_dir . $gambar_new_name;

        // Validasi ekstensi file gambar
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($gambar_ext), $allowed_exts)) {
            // Pindahkan file gambar ke folder upload
            if (move_uploaded_file($gambar_tmp, $gambar_path)) {
                // Simpan data program ke database
                $stmt = $conn->prepare("INSERT INTO program_zakat (nama_program, jenis_zakat, gambar, deskripsi) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $nama, $jenis, $gambar_new_name, $deskripsi);
                $stmt->execute();
                header("Location: ?module=kelola_program");
                exit;
            } else {
                echo "Gagal meng-upload gambar.";
            }
        } else {
            echo "Hanya file gambar dengan ekstensi JPG, JPEG, PNG, atau GIF yang diperbolehkan.";
        }
    } else {
        echo "Harap pilih file gambar untuk di-upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Program Zakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Tambah Program Zakat</h1>

            <!-- Form Tambah -->
            <form method="POST" enctype="multipart/form-data" class="mb-6 bg-white p-4 rounded shadow grid grid-cols-1 md:grid-cols-6 gap-4">
                <input type="hidden" name="tambah_program" value="1">
                <input name="nama_program" required placeholder="Nama Program" class="border p-2 rounded" />
                <select name="jenis_zakat" required class="border p-2 rounded">
                    <option value="Zakat">Zakat</option>
                    <option value="Infaq">Infaq</option>
                    <option value="Shodaqoh">Shodaqoh</option>
                </select>
                <input type="file" name="gambar_program" required class="border p-2 rounded" />
                <textarea name="deskripsi_program" placeholder="Deskripsi Program" class="border p-2 rounded" rows="4"></textarea>
                <button class="col-span-full bg-green-500 text-white px-4 py-2 rounded">Tambah Program</button>
            </form>
        </div>
    </div>
</body>

</html>