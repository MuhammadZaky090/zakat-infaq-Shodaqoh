<?php
session_start();
require_once './database/koneksi.php';
// Cek akses superadmin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
    header("Location: ?module=login");
    exit;
}

// Handle delete action
if (isset($_POST['delete']) && isset($_POST['selected_items'])) {
    $selected_items = $_POST['selected_items'];
    $deleted = 0;
    
    foreach ($selected_items as $item) {
        list($table, $id) = explode('_', $item);
        $id = (int)$id; // Sanitize ID
        
        // Determine table name based on prefix
        switch ($table) {
            case 'infaq':
                $table_name = 'pembayaran_infaq';
                break;
            case 'shodaqoh':
                $table_name = 'pembayaran_shodaqoh';
                break;
            case 'zakat':
                $table_name = 'pembayaran_zakat';
                break;
            default:
                continue; // Skip invalid table names
        }
        
        // Delete the record
        $delete_query = "DELETE FROM $table_name WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $deleted++;
        }
        $stmt->close();
    }
    
    if ($deleted > 0) {
        $_SESSION['message'] = "Berhasil menghapus $deleted data transaksi.";
    } else {
        $_SESSION['message'] = "Tidak ada data yang dihapus.";
    }
    
    // Fix: Use the current script name instead of relying on ?module parameter
    // This ensures we return to the same page after form submission
    header("Location: " . $_SERVER['PHP_SELF'] . "?module=laporan_transaksi_zis");
    exit;
}

// Gabungkan semua data dari ketiga tabel
$query = "
    SELECT i.id, u.nama, 'Infaq' AS jenis, i.jumlah_infaq AS jumlah, i.metode_pembayaran, i.status, i.tanggal, 'infaq' AS table_name
    FROM pembayaran_infaq i
    JOIN users u ON i.user_id = u.id
    UNION
    SELECT s.id, u.nama, 'Shodaqoh' AS jenis, s.jumlah_shodaqoh AS jumlah, s.metode_pembayaran, s.status, s.tanggal, 'shodaqoh' AS table_name
    FROM pembayaran_shodaqoh s
    JOIN users u ON s.user_id = u.id
    UNION
    SELECT z.id, u.nama, 'Zakat' AS jenis, z.jumlah_zakat AS jumlah, z.metode_pembayaran, z.status, z.tanggal, 'zakat' AS table_name
    FROM pembayaran_zakat z
    JOIN users u ON z.user_id = u.id
    ORDER BY tanggal DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi ZIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'modules/superadmin/sidebar_superadmin.php'; ?>
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Laporan Transaksi ZIS</h1>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= $_SESSION['message'] ?></span>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            
            <!-- Fix: Ensure the form action points back to the current page with the correct module -->
            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?module=laporan_transaksi_zis" id="deleteForm">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded shadow">
                        <thead>
                            <tr class="bg-green-600 text-white text-sm text-center">
                                <th class="px-4 py-2">
                                    <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4">
                                </th>
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Nama Donatur</th>
                                <th class="px-4 py-2">Jenis</th>
                                <th class="px-4 py-2">Jumlah</th>
                                <th class="px-4 py-2">Metode</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr class="border-t border-gray-200 text-center text-sm">
                                        <td class="px-4 py-2">
                                            <input type="checkbox" name="selected_items[]" value="<?= $row['table_name'] . '_' . $row['id'] ?>" class="form-checkbox h-4 w-4 item-checkbox">
                                        </td>
                                        <td class="px-4 py-2"><?= $row['id'] ?></td>
                                        <td class="px-4 py-2"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td class="px-4 py-2"><?= $row['jenis'] ?></td>
                                        <td class="px-4 py-2">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                                        <td class="px-4 py-2"><?= $row['metode_pembayaran'] ?></td>
                                        <td class="px-4 py-2">
                                            <?php if ($row['status'] === 'terbayar'): ?>
                                                <span class="text-green-600 font-semibold"><?= ucfirst($row['status']) ?></span>
                                            <?php else: ?>
                                                <span class="text-yellow-600 italic"><?= ucfirst($row['status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-2"><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="px-4 py-2 text-center">Tidak ada data transaksi</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="flex space-x-4 mt-6">
                    <a href="?module=cetak_laporan" target="_blank" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow">
                        Unduh / Cetak Laporan
                    </a>
                    
                    <button type="button" id="deleteBtn" 
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 shadow hidden">
                        Hapus Data Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-bold mb-4">Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus data yang dipilih?</p>
            <div class="mt-4 flex justify-end space-x-3">
                <button id="cancelDelete" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Batal
                </button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Hapus
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateDeleteButton();
        });
        
        // Individual checkbox change
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateDeleteButton);
        });
        
        // Update delete button visibility
        function updateDeleteButton() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            const deleteBtn = document.getElementById('deleteBtn');
            
            if (checkboxes.length > 0) {
                deleteBtn.classList.remove('hidden');
            } else {
                deleteBtn.classList.add('hidden');
            }
        }
        
        // Delete button click opens confirmation modal
        document.getElementById('deleteBtn').addEventListener('click', function() {
            document.getElementById('confirmModal').classList.remove('hidden');
        });
        
        // Cancel delete
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('confirmModal').classList.add('hidden');
        });
        
        // Confirm delete
        document.getElementById('confirmDelete').addEventListener('click', function() {
            document.getElementById('deleteForm').appendChild(
                Object.assign(document.createElement('input'), {
                    type: 'hidden',
                    name: 'delete',
                    value: '1'
                })
            );
            document.getElementById('deleteForm').submit();
        });
        
        // Initial button state update
        updateDeleteButton();
    </script>
</body>
</html>