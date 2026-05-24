<?php

require_once 'app/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

$route = $_GET['url'] ?? '';

if ($route === '') {

    if (isset($_SESSION['id'])) {

        if ($_SESSION['role'] === 'admin') {
            header('Location: ' . BASE_URL . 'admin/index');
        } else {
            header('Location: ' . BASE_URL . 'user/index');
        }

    } else {
        header('Location: ' . BASE_URL . 'auth/login');
    }

    exit;
}

switch ($route) {

    case 'auth/login':
        require_once 'app/controllers/auth.php';
        login();
        break;

    case 'auth/proseslogin':
        require_once 'app/controllers/auth.php';
        proseslogin();
        break;

    case 'auth/register':
        require_once 'app/controllers/auth.php';
        register();
        break;

    case 'auth/prosesregister':
        require_once 'app/controllers/auth.php';
        prosesregister();
        break;

    case 'auth/logout':
        require_once 'app/controllers/auth.php';
        logout();
        break;

    case 'user/index':
        require_once 'app/controllers/user.php';
        index();
        break;

    case 'user/destinasi':
        require_once 'app/controllers/user.php';
        destinasi();
        break;

    case 'user/detaildestinasi':
        require_once 'app/controllers/user.php';
        detaildestinasi();
        break;

    case 'user/opentrip':
        require_once 'app/controllers/user.php';
        opentrip();
        break;

    case 'user/detailtrip':
        require_once 'app/controllers/user.php';
        detailtrip();
        break;

    case 'user/daftartrip':
        require_once 'app/controllers/user.php';
        daftartrip();
        break;

    case 'user/pembayaran':
        require_once 'app/controllers/user.php';
        pembayaran();
        break;

    case 'user/uploadbukti':
        require_once 'app/controllers/user.php';
        uploadbukti();
        break;

    case 'user/riwayat':
        require_once 'app/controllers/user.php';
        riwayat();
        break;
    
    case 'admin/index':
        require_once 'app/controllers/admin.php';
        index();
        break;

    case 'admin/destinasi':
        require_once 'app/controllers/admin.php';
        destinasi();
        break;

    case 'admin/tambahdestinasi':
        require_once 'app/controllers/admin.php';
        tambahdestinasi();
        break;

    case 'admin/hapusdestinasi':
        require_once 'app/controllers/admin.php';
        hapusdestinasi();
        break;

    case 'admin/opentrip':
        require_once 'app/controllers/admin.php';
        opentrip();
        break;

    case 'admin/tambahtrip':
        require_once 'app/controllers/admin.php';
        tambahtrip();
        break;

    case 'admin/edittrip':
        require_once 'app/controllers/admin.php';
        edittrip();
        break;

    case 'admin/hapustrip':
        require_once 'app/controllers/admin.php';
        hapustrip();
        break;

    case 'admin/pendaftaran':
        require_once 'app/controllers/admin.php';
        pendaftaran();
        break;

    case 'admin/aksipendaftaran':
        require_once 'app/controllers/admin.php';
        aksipendaftaran();
        break;

    case 'admin/pembayaran':
        require_once 'app/controllers/admin.php';
        pembayaran();
        break;

    case 'admin/aksipembayaran':
        require_once 'app/controllers/admin.php';
        aksipembayaran();
        break;

    default:
        http_response_code(404);

        echo "
        <h2 style='text-align:center;margin-top:80px;font-family:sans-serif;'>
            404 - Halaman tidak ditemukan
        </h2>

        <p style='text-align:center;color:#666;'>
            Route: <b>$route</b>
        </p>
        ";
        break;
}
?>