<aside class="w-64 bg-gradient-to-b from-green-700 to-green-800 text-white shadow-lg flex flex-col h-screen">
    <!-- Header -->
    <div class="p-6 text-2xl font-bold border-b border-green-600 tracking-wide">
        Super Admin
    </div>

    <!-- Navigation + Logout wrapper -->
    <div class="flex flex-col justify-between flex-1">
        <!-- Menu -->
        <nav class="p-4 space-y-1 text-sm font-medium overflow-y-auto">
            <a href="?module=kelola_admin" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15.75 4.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0M19.5 15v2.25m0 0v2.25m0-2.25h2.25M19.5 17.25h-2.25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Kelola Data Admin
            </a>
            <a href="?module=laporan_transaksi" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M19.5 14.25v-4.5m0 0V5.25M19.5 9.75H3m0 0L6.75 5.25m-3.75 4.5L6.75 14.25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Laporan Transaksi ZIS
            </a>
            <a href="?module=pencairan_dana" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 6v6h4.5m-4.5 6a9 9 0 110-18 9 9 0 010 18z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Persetujuan Pencairan Dana
            </a>
            <a href="?module=kategori_program" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 7.5V5.25A2.25 2.25 0 015.25 3h13.5A2.25 2.25 0 0121 5.25v2.25m-18 0v9A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75v-9M3 7.5h18" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Kategori Program
            </a>
            <a href="?module=statistik_donasi" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 3v18h18M7.5 15v3m4.5-6v6m4.5-9v9" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Statistik Donasi
            </a>
            <a href="?module=informasi_lembaga" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 20.25V18a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 18v2.25M4.5 10.5h15m-13.5 0v6.75M18 10.5v6.75M12 3v4.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Informasi Lembaga
            </a>
            <a href="?module=log_aktivitas" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path d="M2.25 12C3.75 7.5 7.5 4.5 12 4.5s8.25 3 9.75 7.5c-1.5 4.5-5.25 7.5-9.75 7.5S3.75 16.5 2.25 12z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Log Aktivitas
            </a>
            <a href="?module=blokir_akun" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15.75 9V5.25m0 0a3.75 3.75 0 00-7.5 0v3.75m7.5-3.75H18m-12 0h2.25m0 0v3.75m0 0a3.75 3.75 0 107.5 0v-3.75M6 12v6.75A2.25 2.25 0 008.25 21h7.5A2.25 2.25 0 0018 18.75V12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Blokir/Aktifkan Akun
            </a>
            <a href="?module=metode_pembayaran" class="flex items-center px-4 py-2 rounded hover:bg-green-600 transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 7.5H3v9h18v-9zM3 10.5h18M9 14.25h1.5" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Metode Pembayaran
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-4">
            <a href="?module=logout" class="flex items-center w-full px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15.75 9V5.25m0 0a3.75 3.75 0 00-7.5 0v3.75m7.5-3.75H18m-12 0h2.25m0 0v3.75m0 0a3.75 3.75 0 107.5 0v-3.75M6 12v6.75A2.25 2.25 0 008.25 21h7.5A2.25 2.25 0 0018 18.75V12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Logout
            </a>
        </div>
    </div>
</aside>