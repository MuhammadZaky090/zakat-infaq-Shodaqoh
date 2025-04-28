<?php
require_once "database/koneksi.php";

if ($_GET['module'] == 'superadmin_page') {
    include "modules/superadmin/master_superadmin.php";
}
elseif ($_GET['module'] == 'login') {
    include "modules/auth/login.php";
}
elseif ($_GET['module'] == 'register') {
    include "modules/auth/register.php";
}
elseif ($_GET['module'] == 'logout') {
    session_start();
    session_destroy();
    header("Location: index.php");
    exit;
}
elseif ($_GET['module'] == 'kelola_admin') {
    include "modules/superadmin/kelola_admin/kelola_admin.php";
} 
elseif ($_GET['module'] == 'edit_admin') {
    include "modules/superadmin/kelola_admin/edit_admin.php";
} 
elseif ($_GET['module'] == 'tambah_admin') {
    include "modules/superadmin/kelola_admin/tambah_admin.php";
}
elseif ($_GET['module'] == 'laporan_transaksi') {
    include "modules/superadmin/laporan/laporan_zis.php";
} 
elseif ($_GET['module'] == 'cetak_laporan') {
    include "modules/superadmin/laporan/cetak_laporan.php";
}
elseif ($_GET['module'] == 'pencairan_dana') {
    include "modules/superadmin/distribusi_dana/distribusi_dana.php";
}
elseif ($_GET['module'] == 'kategori_program') {
    include "modules/superadmin/kelola_program/kelola_program_zakat.php";
}
elseif ($_GET['module'] == 'tambah_program') {
    include "modules/superadmin/kelola_program/tambah_program.php";
}
elseif ($_GET['module'] == 'statistik_donasi') {
    include "modules/superadmin/statistik/statistik_donasi.php";
}
elseif ($_GET['module'] == 'informasi_lembaga') {
    include "modules/superadmin/kelola_artikel/kelola_artikel.php";
}
elseif ($_GET['module'] == 'tambah_lembaga') {
    include "modules/superadmin/kelola_artikel/tambah_artikel.php";
}
elseif ($_GET['module'] == 'parameter_sistem') {
    include "modules/superadmin/parameter_sistem.php";
}
elseif ($_GET['module'] == 'log_aktivitas') {
    include "modules/superadmin/log_aktivitas/log_aktivitas.php";
}
elseif ($_GET['module'] == 'blokir_akun') {
    include "modules/superadmin/blokir_akun/blokir_akun.php";
}
elseif ($_GET['module'] == 'aktifkan_akun') {
    include "modules/superadmin/blokir_akun/blokir_akun.php";
}
elseif ($_GET['module'] == 'metode_pembayaran') {
    include "modules/superadmin/kelola_pembayaran/kelola_pembayaran.php";
}
elseif ($_GET['module'] == 'tambah_pembayaran') {
    include "modules/superadmin/kelola_pembayaran/tambah_pembayaran.php";
}
else {
    echo "<div style='padding: 20px; text-align: center; font-weight: bold; color: red;'>Module tidak ditemukan!</div>";
}
