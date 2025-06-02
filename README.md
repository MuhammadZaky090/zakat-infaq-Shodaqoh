# Sistem Zakat Online Masjid Al Fajar Surabaya

 Tentang Program
Sistem Zakat Online Masjid Al Fajar Surabaya adalah platform digital yang dikembangkan untuk memudahkan jamaah dan masyarakat umum dalam menyalurkan zakat, infaq, dan sedekah secara online dengan cara yang mudah, aman, dan transparan. Program ini bertujuan untuk modernisasi pengelolaan dana zakat dengan memanfaatkan teknologi digital untuk meningkatkan efisiensi dan transparansi dalam distribusi dana kepada yang berhak.

 Tujuan Program
1.	Kemudahan Akses: Memberikan kemudahan kepada muzakki (pembayar zakat) untuk menunaikan kewajiban zakat kapan saja dan dimana saja
2.	Transparansi: Menyediakan laporan distribusi dana secara real-time untuk memastikan akuntabilitas pengelolaan zakat
3.	Efisiensi: Mempermudah proses administrasi dan pengelolaan dana zakat melalui sistem digital
4.	Edukasi: Membantu masyarakat dalam memahami dan menghitung zakat sesuai dengan syariat Islam
5.	Optimalisasi Distribusi: Memastikan dana zakat tersalurkan tepat sasaran kepada mustahiq (penerima zakat)

---

 Fitur Utama
 
1. Sistem Pembayaran Online
   
•	Pembayaran zakat, infaq, dan sedekah secara digital

•	Multiple payment gateway yang aman dan terpercaya

•	Konfirmasi pembayaran otomatis

•	Riwayat transaksi yang dapat diakses kapan saja

2. Kalkulator Zakat Otomatis
   
•	Perhitungan zakat sesuai jenis penghasilan dan kepemilikan

•	Kalkulator zakat mal (harta)

•	Kalkulator zakat penghasilan/profesi

•	Kalkulator zakat perdagangan

•	Panduan nisab terkini sesuai harga emas dan perak

3. Program Penyaluran Dana
   
•	Zakat: Program penyaluran zakat kepada 8 asnaf (golongan penerima zakat)

•	Infaq: Program pembangunan dan pemeliharaan fasilitas masjid

•	Sedekah: Program bantuan sosial dan kemanusiaan

4. Sistem Transparansi dan Pelaporan
   
•	Dashboard laporan keuangan real-time

•	Tracking distribusi dana per program

•	Laporan bulanan dan tahunan

•	Dokumentasi penyaluran bantuan

5. Sistem Komunikasi
    
•	Form kontak untuk pertanyaan dan saran

•	Notifikasi status pembayaran

•	Update program dan kegiatan masjid

•	Customer service untuk bantuan teknis

---

 Endpoints dan Fungsi Teknis
 
Authentication & User Management

•	POST /api/auth/login - Login pengguna

•	POST /api/auth/register - Registrasi pengguna baru

•	GET /api/user/profile - Profil pengguna

•	PUT /api/user/profile - Update profil pengguna

Zakat Calculator

•	GET /api/calculator/zakat-mal - Kalkulator zakat harta

•	GET /api/calculator/zakat-penghasilan - Kalkulator zakat penghasilan

•	GET /api/calculator/nisab - Data nisab terkini

•	POST /api/calculator/hitung - Perhitungan zakat custom

Payment System

•	POST /api/payment/create - Membuat transaksi pembayaran

•	GET /api/payment/status/{id} - Status pembayaran

•	POST /api/payment/confirm - Konfirmasi pembayaran

•	GET /api/payment/history - Riwayat pembayaran pengguna

Program Management

•	GET /api/programs - Daftar program zakat/infaq/sedekah

•	GET /api/programs/{id} - Detail program

•	POST /api/programs/donate - Donasi ke program tertentu

Reporting & Transparency

•	GET /api/reports/summary - Ringkasan laporan keuangan

•	GET /api/reports/distribution - Laporan distribusi dana

•	GET /api/reports/monthly/{month} - Laporan bulanan

•	GET /api/reports/annual/{year} - Laporan tahunan

Contact & Support

•	POST /api/contact/message - Kirim pesan/pertanyaan

•	GET /api/contact/faq - Frequently Asked Questions

•	POST /api/support/ticket - Buat tiket bantuan

 Keamanan dan Keandalan
 
•	Enkripsi SSL/TLS untuk semua transaksi

•	Payment Gateway Tersertifikasi dengan standar keamanan internasional

•	Backup Data Otomatis untuk memastikan keamanan data

•	Audit Trail untuk melacak semua aktivitas sistem

•	Compliance dengan regulasi keuangan dan syariah

 Platform Akses
 
•	Web Application: Akses melalui browser desktop dan mobile

•	Responsive Design: Optimal untuk semua ukuran layar

•	Progressive Web App (PWA): Dapat diinstall di perangkat mobile

•	Cross-browser Compatibility: Mendukung semua browser modern

---

 Manfaat untuk Stakeholder
 
Untuk Muzakki (Pembayar Zakat):

•	Kemudahan pembayaran 24/7

•	Transparansi penggunaan dana

•	Perhitungan zakat yang akurat

•	Laporan pajak untuk zakat penghasilan

Untuk Masjid:

•	Efisiensi pengelolaan administrasi

•	Peningkatan transparansi dan kepercayaan

•	Optimalisasi pengumpulan dan distribusi dana

•	Laporan keuangan yang akurat dan real-time

Untuk Mustahiq (Penerima Zakat):

•	Distribusi bantuan yang lebih tepat sasaran

•	Transparansi dalam penyaluran bantuan

•	Program bantuan yang berkelanjutan

---

 Kontak dan Dukungan
Untuk pertanyaan, saran, atau bantuan teknis, silakan hubungi tim kami melalui:

•	Website: https://zakat1.masjidalfajar-sby.site/

•	Form kontak yang tersedia di website

•	Media sosial Masjid Al Fajar Surabaya

---

Kelas PTI-C 2023 Kelompok 6

Anggota:

Dennis Kiftirul Azis (23050974106)

Muh. Yanuar Ismail Akbar (23050974107)

Muhammad Zaky Ardiansyah (23050974090)

Link Website:
https://zakat1.masjidalfajar-sby.site/
Link Prodi:
https://pendidikan-ti.ft.unesa.ac.id/
________________________________________
Masjid Al Fajar Surabaya
"Berzakat Lebih Mudah, Aman & Transparan"
Sistem ini dikembangkan dengan komitmen untuk memudahkan ibadah zakat sambil menjaga prinsip-prinsip syariah dan transparansi dalam pengelolaan dana umat.
