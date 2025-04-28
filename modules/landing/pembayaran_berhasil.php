<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ?module=login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pembayaran Berhasil - ZIS Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        .islamic-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23166534' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .success-card {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .success-card::before {
            content: "";
            position: absolute;
            top: -50px;
            left: -50px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: rgba(22, 163, 74, 0.1);
            z-index: 0;
        }
        
        .success-card::after {
            content: "";
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: rgba(22, 163, 74, 0.1);
            z-index: 0;
        }
        
        .islamic-border {
            border-style: solid;
            border-width: 3px;
            border-image: linear-gradient(to right, #16a34a 25%, #86efac 50%, #16a34a 75%) 1;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-white text-gray-800 font-sans islamic-pattern">
    <header class="bg-green-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-5 flex justify-between items-center">
            <h1 class="text-2xl font-bold flex items-center">
                <i class="fas fa-mosque mr-3"></i>
                ZIS Online
            </h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="index.php" class="hover:text-green-200 transition"><i class="fas fa-home mr-1"></i> Beranda</a></li>
                    <li><a href="?module=profile" class="hover:text-green-200 transition"><i class="fas fa-user mr-1"></i> Profil</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="max-w-4xl mx-auto my-16 px-4">
        <div class="success-card bg-white rounded-xl p-8 mb-10 relative islamic-border">
            <div class="text-center relative z-10">
                <div class="float-animation">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4 -4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold text-green-700 mb-4">Alhamdulillah, Pembayaran Berhasil!</h2>
                
                <div class="flex justify-center mb-6">
                    <div class="h-1 w-40 bg-gradient-to-r from-green-600 to-green-400 rounded-full"></div>
                </div>
                
                <p class="text-gray-600 mb-6 text-lg">Terima kasih atas kesediaan Anda dalam menunaikan kewajiban zakat. Semoga menjadi amal jariyah yang terus mengalir dan mendatangkan keberkahan.</p>
                
                <div class="py-5 px-6 bg-green-50 rounded-lg mb-6 max-w-lg mx-auto">
                    <p class="text-green-800 italic">
                        "Barangsiapa yang memberi dengan ikhlas (di jalan Allah), dan bertakwa, dan membenarkan adanya pahala yang terbaik (surga), maka Kami kelak akan menyiapkan baginya jalan yang mudah."
                    </p>
                    <p class="text-green-700 font-semibold mt-2">- QS. Al-Lail: 5-7</p>
                </div>
                
                <div class="flex flex-col md:flex-row justify-center gap-4 mt-8">
                    <a href="index.php" class="inline-block bg-green-600 text-white px-6 py-3 rounded-full hover:bg-green-700 transition flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                    </a>
                    <a href="?module=profile" class="inline-block bg-white border-2 border-green-600 text-green-600 px-6 py-3 rounded-full hover:bg-green-50 transition flex items-center justify-center">
                        <i class="fas fa-file-alt mr-2"></i> Lihat Riwayat Pembayaran
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Bagian Konfirmasi Pembayaran -->
        <div class="bg-green-50 rounded-xl p-8 border-l-4 border-green-600 shadow-md">
            <h3 class="text-2xl font-bold text-green-700 mb-4 flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600"></i>
                Konfirmasi Pembayaran
            </h3>
            
            <p class="text-gray-700 mb-5">
                Untuk memastikan pembayaran Anda telah diproses dengan benar, silakan konfirmasi dengan menghubungi kami melalui WhatsApp:
            </p>
            
            <div class="flex items-center justify-between bg-white rounded-lg p-4 shadow-sm border border-green-200">
                <div>
                    <p class="font-semibold text-gray-800">Dennis K. Azis</p>
                    <p class="text-gray-600">+6281-3333-48074</p>
                </div>
                <a href="https://wa.me/6281333348074?text=Assalamualaikum%2C%20saya%20ingin%20mengkonfirmasi%20pembayaran%20zakat%20saya%20pada%20ZIS%20Online." 
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg inline-flex items-center transition-all">
                    <i class="fab fa-whatsapp mr-2 text-xl"></i>
                    Konfirmasi via WhatsApp
                </a>
            </div>
            
            <div class="mt-6 bg-green-100 p-4 rounded-lg text-green-800 flex items-start">
                <i class="fas fa-info-circle text-xl mr-3 mt-0.5"></i>
                <p>Sertakan bukti pembayaran/transfer dan nama Anda saat menghubungi kami untuk mempercepat proses verifikasi.</p>
            </div>
        </div>
    </main>

    <footer class="bg-green-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-mosque mr-2"></i> ZIS Online
                    </h3>
                    <p class="text-green-100 mb-4">Platform digital untuk memudahkan Anda menunaikan Zakat, Infaq, dan Sedekah secara online dengan aman dan tepercaya.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-green-200"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/masjidalfajar.sby/" class="text-white hover:text-green-200"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@masjidalfajar-sby" class="text-white hover:text-green-200"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3 text-green-100">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                            <span>MASJID AL FAJAR | Jl. Cipta Menanggal Dalam, Kota Surabaya</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3"></i>
                            <span>+6281-3333-48074</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            <span>alfajarmenanggalsurabaya@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-green-600 mt-8 pt-6 text-center text-green-100">
                <p>&copy; 2025 ZIS Online. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>
</body>

</html>