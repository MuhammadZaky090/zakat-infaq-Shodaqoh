<?php
session_start();
session_unset(); // Hapus semua data dalam session
session_destroy(); // Hancurkan session
header("Location: main.php?module=landing_page"); // Arahkan ke halaman login
exit;
