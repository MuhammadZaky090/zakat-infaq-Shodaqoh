<?php
require_once "database/koneksi.php";

if ($_GET['module'] == 'landing_page') {
    include "modules/landing/master_landing.php";
}
elseif ($_GET['module'] == 'login') {
    include "modules/auth/login.php";
}
elseif ($_GET['module'] == 'register') {
    include "modules/auth/register.php";
} 

elseif ($_GET['module'] == 'profile') {
    include "modules/landing/profile/profile.php";
} 

elseif ($_GET['module'] == 'kalkulator') {
    include "modules/landing/kalkulator.php";
}

elseif ($_GET['module'] == 'zakat') {
    include "modules/landing/bayar_zakat.php";
}

elseif ($_GET['module'] == 'infaq') {
    include "modules/landing/bayar_infaq.php";
}

elseif ($_GET['module'] == 'shodaqoh') {
    include "modules/landing/bayar_shodaqoh.php";
}

elseif ($_GET['module'] == 'pembayaran_berhasil') {
    include "modules/landing/pembayaran_berhasil.php";
}

elseif ($_GET['module'] == 'logout') {
    include "modules/auth/logout.php";
}

elseif ($_GET['module'] == 'unduh_sertifikat_zakat') {
    include "modules/landing/profile/unduh_sertifikat_zakat.php";
}