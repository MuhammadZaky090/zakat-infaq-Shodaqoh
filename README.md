# Zakat-Infaq-Shodaqoh

**Kelas:** PTI-C 2023  
**Kelompok:** 6  

---

## Anggota Kelompok

1. Dennis Kiftirul Azis (23050974106)  
2. Muh. Yanuar Ismail Akbar (23050974107)  
3. Muhammad Zaky Ardiansyah (23050974090)  

---

## Deskripsi

Website **Zakat Masjid Al-Fajar Surabaya** adalah portal transparan dan interaktif untuk menghimpun dan menyalurkan zakat, infaq, dan sedekah. Melalui platform ini, pengunjung dapat melihat program-program sosial, memeriksa laporan keuangan, melakukan donasi online, dan memantau statistik real-time. Digitalisasi zakat diidentifikasi sebagai strategi krusial untuk meningkatkan efisiensi, transparansi, dan kemudahan akses bagi *muzakkî* (pemberi zakat) serta memperluas inklusi keuangan bagi *mustahik* (penerima). Sistem online mendukung pelaporan real-time dan audit digital, sehingga meningkatkan akuntabilitas dan kepercayaan publik.

---

## Tujuan Program

- **Meningkatkan Efisiensi Penghimpunan**  
  Mempermudah muzakkî dalam membayarkan zakat, infaq, dan sedekah kapan saja dan di mana saja melalui antarmuka yang responsif.
  
- **Menjamin Transparansi dan Akuntabilitas**  
  Menyajikan laporan keuangan dan statistik secara real-time, agar semua pihak (muzakkî, mustahik, dan pengurus masjid) dapat memantau arus dana secara jelas.

- **Memperluas Cakupan Donasi**  
  Mengakomodasi berbagai metode pembayaran (transfer bank, QRIS, e-wallet) dan mengurangi hambatan geografis bagi muzakkî yang ingin berdonasi.

- **Memberdayakan Mustahik**  
  Menyediakan data dan informasi program pemberdayaan bagi mustahik, serta memudahkan penyaluran dana secara tepat sasaran.

---

## Daftar Fungsi (Endpoint & Fitur)

### 1. Landing Page  
- **`/main.php?module=landing_page`**  
  Menampilkan ringkasan program, statistik real-time, banner promosi, dan tombol navigasi menuju halaman donasi serta laporan.

### 2. Halaman Donasi  
- **`/main.php?module=donasi_zakat`**  
  Formulir input data muzakkî, perhitungan otomatis zakat (fitrah, maal, penghasilan), dan pilihan metode pembayaran (transfer bank, QRIS, e-wallet).  
- **`/main.php?module=donasi_infaq`**  
  Form infaq sukarela tanpa ketentuan nishab, dengan opsi nominal tetap dan custom.  
- **`/main.php?module=donasi_sedekah`**  
  Form sedekah bebas, termasuk opsi donasi non-tunai (barang atau jasa).

### 3. Halaman Program  
- **`/main.php?module=program`**  
  Daftar program rutin Masjid Al-Fajar (pemberdayaan ekonomi, beasiswa, posko kesehatan, dan lain-lain), deskripsi singkat, serta jadwal kegiatan.

### 4. Halaman Laporan Keuangan  
- **`/main.php?module=laporan_keuangan`**  
  Menampilkan ringkasan penerimaan dan penyaluran dana (zakat, infaq, sedekah) per bulan/tahun dalam format tabel dan grafik (chart).  
- **`/main.php?module=export_laporan`**  
  Opsi mengunduh laporan keuangan dalam format PDF atau Excel.

### 5. Halaman Statistik  
- **`/main.php?module=statistik`**  
  Grafik real-time yang memperlihatkan total donasi (zakat, infaq, sedekah), jumlah muzakkî, serta daftar mustahik yang telah dibantu.   

### 6. Halaman Manajemen Admin  
- **`/main.php?module=login`**  
  Form login untuk admin dengan autentikasi username & password.  
- **`/main.php?module=dashboard_admin`**  
  Ringkasan statistik internal, notifikasi transaksi baru, dan menu manajemen data muzakkî serta mustahik.  
- **`/main.php?module=kelola_muzakki`**  
  CRUD (Create, Read, Update, Delete) data muzakkî, termasuk histori donasi.  
- **`/main.php?module=kelola_mustahik`**  
  CRUD data mustahik, alokasi dana, dan status penyaluran bantuan.  
- **`/main.php?module=kelola_program`**  
  CRUD program sosial (penambahan, pengeditan, penonaktifan program).  
- **`/main.php?module=laporan_admin`**  
  Laman untuk mengekspor laporan keuangan internal dan audit trail transaksi.

### 7. Lain-lain  
- **`/main.php?module=profil_masjid`**  
  Informasi singkat Sejarah, Visi, Misi, dan Kontak Masjid Al-Fajar Surabaya.  
- **`/main.php?module=faq`**  
  Daftar pertanyaan umum seputar zakat, infaq, dan sedekah (syarat, tata cara, dsb.).  
- **`/main.php?module=kontak`**  
  Formulir pertanyaan/saran untuk pengunjung, dengan notifikasi email ke admin.


## Alamat Website

[https://zakat1.masjidalfajar-sby.site/main.php?module=landing_page](https://zakat1.masjidalfajar-sby.site/main.php?module=landing_page)

