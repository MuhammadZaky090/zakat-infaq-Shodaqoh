
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <!-- Sidebar -->
    <div class="flex min-h-screen">
        <?php include 'sidebar_admin.php'; ?>

        <!-- Main Content -->
        <main class="flex-1 px-8 py-6 overflow-y-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                Dashboard Admin
            </h1>

            <div class="bg-white px-6 py-4 shadow-sm border border-gray-200">
                <h2 class="text-xl font-bold mb-4">Selamat Datang, Admin</h2>
                <p>Di sini Anda dapat mengelola semua hal terkait dengan donasi, verifikasi pembayaran, laporan keuangan, dan lainnya.</p>
                
            </div>
        </main>
    </div>

</body>

</html>