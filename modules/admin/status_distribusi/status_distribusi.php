<?php
session_start();
require_once './database/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ?module=login");
    exit;
}

// Filter
$statusFilter = $_GET['status'] ?? '';
$bulanFilter = $_GET['bulan'] ?? '';

$where = [];
if ($statusFilter != '') $where[] = "status = '$statusFilter'";
if ($bulanFilter != '') $where[] = "MONTH(tanggal) = '$bulanFilter'";

$whereSQL = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Ambil data distribusi
$sql = "SELECT * FROM distribusi_dana $whereSQL ORDER BY tanggal DESC";
$result = $conn->query($sql);

// Tambah distribusi baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_distribusi'])) {
    $nama = $_POST['nama_penerima'];
    $jenis = $_POST['jenis_bantuan'];
    $jumlah = $_POST['jumlah_dana'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO distribusi_dana (nama_penerima, jenis_bantuan, jumlah_dana, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $nama, $jenis, $jumlah, $status);
    $stmt->execute();
    header("Location: ?module=status_distribusi");
    exit;
}

// Ubah status distribusi
if (isset($_GET['ubah_status']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $statusBaru = $_GET['ubah_status'];
    $conn->query("UPDATE distribusi_dana SET status = '$statusBaru' WHERE id = $id");
    header("Location: status_distribusi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Status Distribusi Dana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/admin/sidebar_admin.php'; ?>

        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">Status Distribusi Dana</h1>

            <!-- Filter -->
            <form class="mb-4 flex gap-4" method="GET" action="status_distribusi.php">
                <select name="status" class="px-3 py-2 border rounded">
                    <option value="">Semua Status</option>
                    <option value="Terkirim" <?= $statusFilter === 'Terkirim' ? 'selected' : '' ?>>Terkirim</option>
                    <option value="Belum Terkirim" <?= $statusFilter === 'Belum Terkirim' ? 'selected' : '' ?>>Belum Terkirim</option>
                </select>
                <select name="bulan" class="px-3 py-2 border rounded">
                    <option value="">Semua Bulan</option>
                    <?php for ($b = 1; $b <= 12; $b++): ?>
                        <option value="<?= $b ?>" <?= $bulanFilter == $b ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $b, 10)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
            </form>

            <!-- Form Tambah -->
            <form method="POST" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4 bg-white p-4 rounded shadow">
                <input type="hidden" name="tambah_distribusi" value="1">
                <input required name="nama_penerima" placeholder="Nama Penerima" class="border p-2 rounded" />
                <select name="jenis_bantuan" required class="border p-2 rounded">
                    <option value="Zakat">Zakat</option>
                    <option value="Infaq">Infaq</option>
                    <option value="Shodaqoh">Shodaqoh</option>
                </select>
                <input required name="jumlah_dana" type="number" placeholder="Jumlah Dana" class="border p-2 rounded" />
                <select name="status" required class="border p-2 rounded">
                    <option value="Belum Terkirim">Belum Terkirim</option>
                   
                </select>
                <button class="bg-green-500 text-white px-4 py-2 rounded">Tambah</button>
            </form>

            <!-- Tabel Distribusi -->
            <table class="min-w-full bg-white border border-gray-200 rounded shadow">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-2">Nama Penerima</th>
                        <th class="px-4 py-2">Jenis Bantuan</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Tanggal</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['nama_penerima']) ?></td>
                            <td class="px-4 py-2"><?= $row['jenis_bantuan'] ?></td>
                            <td class="px-4 py-2"><?= number_format($row['jumlah_dana'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2"><?= $row['status'] ?></td>
                            <td class="px-4 py-2"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                            
                        </tr>
                    <?php endwhile ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>