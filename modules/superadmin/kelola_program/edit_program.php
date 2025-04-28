<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ?module=login");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data program berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM program_zakat WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $program = $result->fetch_assoc();

    // Proses edit program
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_program'])) {
        $nama = $_POST['nama_program'];
        $jenis = $_POST['jenis_zakat'];
        $deskripsi = $_POST['deskripsi_program'];

        // Proses upload gambar (jika ada gambar baru)
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
                    $stmt = $conn->prepare("UPDATE program_zakat SET nama_program = ?, jenis_zakat = ?, gambar = ?, deskripsi = ? WHERE id = ?");
                    $stmt->bind_param("ssssi", $nama, $jenis, $gambar_new_name, $deskripsi, $id);
                    $stmt->execute();
                    header("Location: kelola_program_zakat.php");
                    exit;
                } else {
                    echo "Gagal meng-upload gambar.";
                }
            } else {
                echo "Hanya file gambar dengan ekstensi JPG, JPEG, PNG, atau GIF yang diperbolehkan.";
            }
        } else {
            // Jika tidak ada gambar baru, update data program tanpa mengganti gambar
            $stmt = $conn->prepare("UPDATE program_zakat SET nama_program = ?, jenis_zakat = ?, deskripsi = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nama, $jenis, $deskripsi, $id);
            $stmt->execute();
            header("Location: kelola_program_zakat.php");
            exit;
        }
    }
} else {
    echo "ID program tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Program Zakat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/admin/sidebar_admin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Edit Program Zakat</h1>

            <!-- Form Edit -->
            <form method="POST" enctype="multipart/form-data" class="mb-6 bg-white p-4 rounded shadow grid grid-cols-1 md:grid-cols-6 gap-4">
                <input type="hidden" name="edit_program" value="1">
                <input name="nama_program" value="<?= htmlspecialchars($program['nama_program']) ?>" required placeholder="Nama Program" class="border p-2 rounded" />
                <select name="jenis_zakat" required class="border p-2 rounded">
                    <option value="Zakat" <?= ($program['jenis_zakat'] == 'Zakat') ? 'selected' : '' ?>>Zakat</option>
                    <option value="Infaq" <?= ($program['jenis_zakat'] == 'Infaq') ? 'selected' : '' ?>>Infaq</option>
                    <option value="Shodaqoh" <?= ($program['jenis_zakat'] == 'Shodaqoh') ? 'selected' : '' ?>>Shodaqoh</option>
                </select>
                <input type="file" name="gambar_program" class="border p-2 rounded" />
                <textarea name="deskripsi_program" placeholder="Deskripsi Program" class="border p-2 rounded" rows="4"><?= htmlspecialchars($program['deskripsi']) ?></textarea>
                <button class="col-span-full bg-green-500 text-white px-4 py-2 rounded">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</body>

</html>