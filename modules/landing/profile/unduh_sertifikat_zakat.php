<?php
include "koneksi.php";

$id_zakat = $_GET['id'];
$query_zakat = mysqli_query($conn, "SELECT * FROM pembayaran_zakat WHERE id = $id_zakat");
$zakat = mysqli_fetch_assoc($query_zakat);

if ($zakat) {
    $user_id = $zakat['user_id'];
    $query_user = mysqli_query($conn, "SELECT nama FROM users WHERE id = $user_id");
    $user = mysqli_fetch_assoc($query_user);
    
    // Get current date for the certificate
    $today = date("d F Y");

    echo "
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap');
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        
        .certificate-container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background: linear-gradient(to bottom right, #ffffff, #f8f8f8);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            padding: 3rem;
        }
        
        .certificate-border {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 2px solid #1e8449;
            border-radius: 10px;
            pointer-events: none;
            z-index: 1;
        }
        
        .certificate-border:before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid #27ae60;
            border-radius: 5px;
        }
        
        .corner-ornament {
            position: absolute;
            width: 80px;
            height: 80px;
            z-index: 2;
        }
        
        .top-left {
            top: 0;
            left: 0;
            border-top: 4px solid #27ae60;
            border-left: 4px solid #27ae60;
            border-top-left-radius: 10px;
        }
        
        .top-right {
            top: 0;
            right: 0;
            border-top: 4px solid #27ae60;
            border-right: 4px solid #27ae60;
            border-top-right-radius: 10px;
        }
        
        .bottom-left {
            bottom: 0;
            left: 0;
            border-bottom: 4px solid #27ae60;
            border-left: 4px solid #27ae60;
            border-bottom-left-radius: 10px;
        }
        
        .bottom-right {
            bottom: 0;
            right: 0;
            border-bottom: 4px solid #27ae60;
            border-right: 4px solid #27ae60;
            border-bottom-right-radius: 10px;
        }
        
        .certificate-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.03;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><path fill=\"%2327ae60\" d=\"M30,10 L70,10 L90,30 L90,70 L70,90 L30,90 L10,70 L10,30 Z\"></path></svg>');
            background-size: 100px 100px;
            pointer-events: none;
            z-index: 0;
        }
        
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }
        
        .logo-container {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .certificate-title {
            flex-grow: 1;
            text-align: center;
        }
        
        .certificate-title h1 {
            font-family: 'Playfair Display', serif;
            color: #1e8449;
            font-size: 2.8rem;
            margin: 0;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.1);
        }
        
        .certificate-title p {
            color: #666;
            font-size: 1.2rem;
            margin: 0.5rem 0 0;
        }
        
        .decorative-line {
            height: 3px;
            background: linear-gradient(to right, transparent, #27ae60, transparent);
            margin: 1.5rem 0;
            position: relative;
            z-index: 2;
        }
        
        .content {
            padding: 1.5rem;
            position: relative;
            z-index: 2;
            text-align: center;
        }
        
        .bismillah {
            font-size: 1.5rem;
            color: #27ae60;
            font-weight: 500;
            margin-bottom: 1.5rem;
            font-style: italic;
        }
        
        .certificate-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
        }
        
        .info-table tr {
            border-bottom: 1px solid rgba(39, 174, 96, 0.2);
        }
        
        .info-table tr:last-child {
            border-bottom: none;
        }
        
        .info-table td {
            padding: 0.8rem 1rem;
            text-align: left;
            font-size: 1.1rem;
        }
        
        .info-table td:first-child {
            font-weight: 600;
            color: #2c3e50;
            width: 40%;
        }
        
        .info-table td:last-child {
            color: #27ae60;
            font-weight: 500;
        }
        
        .footer {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            position: relative;
            z-index: 2;
        }
        
        .signature {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .signature-line {
            width: 180px;
            height: 2px;
            background-color: #333;
            margin: 0 auto 0.5rem;
        }
        
        .date {
            text-align: right;
            font-style: italic;
            color: #666;
            margin-top: 1rem;
        }
        
        .certificate-number {
            font-size: 0.8rem;
            color: #777;
            text-align: right;
            margin-top: 1rem;
        }
        
        .islamic-pattern {
            position: absolute;
            width: 150px;
            height: 150px;
            opacity: 0.05;
            z-index: 1;
        }
        
        .pattern-top-right {
            top: 0;
            right: 0;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><circle fill=\"%2327ae60\" cx=\"50\" cy=\"50\" r=\"50\"/></svg>');
            background-size: contain;
        }
        
        .pattern-bottom-left {
            bottom: 0;
            left: 0;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><circle fill=\"%2327ae60\" cx=\"50\" cy=\"50\" r=\"50\"/></svg>');
            background-size: contain;
        }

        .print-button {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            display: block;
        }
        
        .print-button:hover {
            background-color: #1e8449;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        @media print {
            body {
                background-color: #ffffff;
            }
            
            .certificate-container {
                box-shadow: none;
                margin: 0;
                padding: 2cm;
                width: 100%;
                max-width: 100%;
                height: 100%;
            }
            
            .print-button {
                display: none;
            }
        }
    </style>

    <div class='certificate-container'>
        <div class='certificate-border'></div>
        <div class='certificate-background'></div>
        
        <!-- Corner Ornaments -->
        <div class='corner-ornament top-left'></div>
        <div class='corner-ornament top-right'></div>
        <div class='corner-ornament bottom-left'></div>
        <div class='corner-ornament bottom-right'></div>
        
        <!-- Islamic Patterns -->
        <div class='islamic-pattern pattern-top-right'></div>
        <div class='islamic-pattern pattern-bottom-left'></div>
        
        <!-- Header -->
        <div class='header'>
            <div class='logo-container'>
                <img src='assets/image/logo.png' alt='Logo'>
            </div>
            <div class='certificate-title'>
                <h1>Sertifikat Zakat</h1>
                <p>Bukti Pembayaran Zakat</p>
            </div>
        </div>
        
        <div class='decorative-line'></div>
        
        <!-- Content -->
        <div class='content'>
            <div class='bismillah'>بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</div>
            
            <div class='certificate-text'>
                Dengan mengucap puji syukur ke hadirat Allah SWT, kami menyatakan bahwa:
            </div>
            
            <table class='info-table'>
                <tr>
                    <td>Nama</td>
                    <td>" . htmlspecialchars($user['nama']) . "</td>
                </tr>
                <tr>
                    <td>Jumlah Zakat</td>
                    <td>Rp" . number_format($zakat['jumlah_zakat'], 0, ',', '.') . "</td>
                </tr>
                <tr>
                    <td>Metode Pembayaran</td>
                    <td>" . htmlspecialchars($zakat['metode_pembayaran']) . "</td>
                </tr>
                <tr>
                    <td>Tanggal Pembayaran</td>
                    <td>" . date('d F Y, H:i', strtotime($zakat['tanggal'])) . "</td>
                </tr>
            </table>
            
            <p class='certificate-text'>
                Telah menunaikan kewajiban zakat. Semoga Allah SWT menerima dan melipat gandakan pahala atas zakat yang telah ditunaikan.
            </p>
            
            <div class='decorative-line'></div>
        </div>
        
        <!-- Footer -->
        <div class='footer'>
            <div class='signature'>
                <div class='signature-line'></div>
                <p>Penerima Zakat</p>
            </div>
            
            <div class='date'>
                Diterbitkan pada: " . $today . "
            </div>
        </div>
        
        <div class='certificate-number'>
            No. Sertifikat: ZKT-" . sprintf('%04d', $id_zakat) . "-" . date('Ymd', strtotime($zakat['tanggal'])) . "
        </div>
    </div>
    
    <button class='print-button' onclick='window.print()'>Cetak Sertifikat</button>
    ";
} else {
    echo "<div style='text-align: center; color: #e74c3c; font-weight: 600; padding: 2rem; font-family: Montserrat, sans-serif; margin: 2rem;'>
        <div style='font-size: 3rem; margin-bottom: 1rem;'>⚠️</div>
        <p style='font-size: 1.5rem;'>Data sertifikat tidak ditemukan.</p>
        <p>Silakan kembali dan coba lagi.</p>
    </div>";
}
?>