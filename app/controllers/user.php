<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'koneksi/koneksi.php';
require_once 'app/models/DestinasiModel.php';
require_once 'app/models/TripModel.php';
require_once 'app/models/PendaftaranModel.php';
require_once 'app/models/PembayaranModel.php';
function requireUserLogin()
{
    if (!isset($_SESSION['id'])) {
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }
}


function index()
{
    global $conn;
    requireUserLogin();

    $destinasi_rekomendasi = DestinasiModel::getRekomendasi($conn, 5);
    $open_trips_aktif = TripModel::getAktifTerbaru($conn, 4);

    require 'app/views/user/home.php';
}

function destinasi()
{
    global $conn;
    requireUserLogin();

    $search = trim($_GET['q'] ?? '');

    if ($search !== '') {
        $destinasi = DestinasiModel::search($conn, $search);
    } else {
        $destinasi = DestinasiModel::getAll($conn);
    }

    require 'app/views/user/destinasi.php';
}

function detaildestinasi()
{
    global $conn;
    requireUserLogin();

    $id = intval($_GET['id'] ?? 0);
    $destinasi = DestinasiModel::getById($conn, $id);

    if (!$destinasi) {
        http_response_code(404);
        die('<p style="text-align:center;margin-top:50px;">Destinasi tidak ditemukan.</p>');
    }

    $trip_tersedia = TripModel::getByDestinasiId($conn, $id);

    require 'app/views/user/detail_destinasi.php';
}

function opentrip()
{
    global $conn;
    requireUserLogin();

    $open_trips = TripModel::getAllAktif($conn);

    require 'app/views/user/open_trip.php';
}
function detailtrip()
{
    global $conn;
    requireUserLogin();

    $id = intval($_GET['id'] ?? 0);
    $trip = TripModel::getById($conn, $id);

    if (!$trip) {
        http_response_code(404);
        die('<p style="text-align:center;margin-top:50px;">Open Trip tidak ditemukan.</p>');
    }

    $show_payment = isset($_GET['show_payment']);

    $pendaftaran = null;

    if($show_payment && isset($_GET['pendaftaran_id']))
    {
        $pendaftaran=
        PendaftaranModel::getByIdAndUser(
            $conn,
            $_GET['pendaftaran_id'],
            $_SESSION['id']
        );
    }

    require 'app/views/user/detail_trip.php';
}

function daftartrip()
{
    global $conn;
    requireUserLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'user/opentrip');
        exit;
    }

    $open_trip_id = intval($_POST['open_trip_id'] ?? 0);
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $no_whatsapp = trim($_POST['no_whatsapp'] ?? '');
    $jumlah_orang = intval($_POST['jumlah_orang'] ?? 1);
    $catatan = trim($_POST['catatan'] ?? '');

    if (!$open_trip_id || empty($nama_lengkap) || empty($no_whatsapp)) {
        $_SESSION['error'] = 'Data pendaftaran tidak lengkap.';
        header('Location: ' . BASE_URL . 'user/detailtrip?id=' . $open_trip_id);
        exit;
    }

    $kuota_info = TripModel::getKuota($conn, $open_trip_id);

    if (!$kuota_info || ($kuota_info['peserta_terdaftar'] + $jumlah_orang) > $kuota_info['kuota']) {
        $_SESSION['error'] = 'Kuota trip sudah penuh atau tidak mencukupi.';
        header('Location: ' . BASE_URL . 'user/detailtrip?id=' . $open_trip_id);
        exit;
    }

    $pendaftaran_id = PendaftaranModel::insert($conn, [
        'user_id'      => $_SESSION['id'],
        'open_trip_id' => $open_trip_id,
        'nama_lengkap' => $nama_lengkap,
        'no_whatsapp'  => $no_whatsapp,
        'jumlah_orang' => $jumlah_orang,
        'catatan'      => $catatan,
    ]);

    TripModel::incrementPeserta($conn, $open_trip_id, $jumlah_orang);

    $bukti_transfer = '';
    if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/img/bukti/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $ext          = strtolower(pathinfo($_FILES['bukti_transfer']['name'], PATHINFO_EXTENSION));
        $allowed_ext  = ['jpg', 'jpeg', 'png', 'pdf'];
        if (in_array($ext, $allowed_ext)) {
            $filename       = 'bukti_' . $_SESSION['id'] . '_' . time() . '.' . $ext;
            $bukti_transfer = $upload_dir . $filename;
            move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $bukti_transfer);
        }
    }

    $jumlah = intval($_POST['harga_satuan'] ?? 0) * $jumlah_orang;
    PembayaranModel::insert($conn, [
        'pendaftaran_id' => $pendaftaran_id,
        'user_id'        => $_SESSION['id'],
        'open_trip_id'   => $open_trip_id,
        'jumlah'         => $jumlah,
        'bukti_transfer' => $bukti_transfer,
    ]);

    $_SESSION['success'] = 'Pendaftaran & bukti pembayaran berhasil dikirim! Menunggu konfirmasi admin.';
    header('Location: ' . BASE_URL . 'user/detailtrip?id=' . $open_trip_id);
    exit;
}

function pembayaran()
{
    global $conn;
    requireUserLogin();

    $pendaftaran_id = intval($_GET['pendaftaran_id'] ?? 0);
    $pendaftaran = PendaftaranModel::getByIdAndUser($conn, $pendaftaran_id, $_SESSION['id']);

    if (!$pendaftaran) {
        http_response_code(404);
        die('<p style="text-align:center;margin-top:50px;">Data pendaftaran tidak ditemukan.</p>');
    }

    $total = $pendaftaran['harga'] * $pendaftaran['jumlah_orang'];

    require 'app/views/user/pembayaran.php';
}

function uploadbukti()
{
    global $conn;
    requireUserLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'user/index');
        exit;
    }

    $pendaftaran_id = intval($_POST['pendaftaran_id'] ?? 0);
    $pendaftaran = PendaftaranModel::getByIdAndUser($conn, $pendaftaran_id, $_SESSION['id']);

    if (!$pendaftaran) {
        $_SESSION['error'] = 'Data tidak valid.';
        header('Location: ' . BASE_URL . 'user/index');
        exit;
    }

    $bukti_transfer = '';

    if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'assets/img/bukti/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $ext = pathinfo($_FILES['bukti_transfer']['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array(strtolower($ext), $allowed_ext)) {
            $_SESSION['error'] = 'Format file tidak valid. Gunakan JPG, PNG, atau PDF.';
            header('Location: ' . BASE_URL . 'user/detailtrip?id=' . $pendaftaran['open_trip_id'] . '&show_payment=1&pendaftaran_id=' . $pendaftaran_id);
            exit;
        }

        $filename = 'bukti_' . $_SESSION['id'] . '_' . time() . '.' . $ext;
        $bukti_transfer = $upload_dir . $filename;
        move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $bukti_transfer);
    }

    $jumlah = $pendaftaran['harga'] * $pendaftaran['jumlah_orang'];

    if (PembayaranModel::existsByPendaftaran($conn, $pendaftaran_id)) {
        PembayaranModel::updateBukti($conn, $pendaftaran_id, $bukti_transfer);
    } else {
        PembayaranModel::insert($conn, [
            'pendaftaran_id' => $pendaftaran_id,
            'user_id' => $_SESSION['id'],
            'open_trip_id' => $pendaftaran['open_trip_id'],
            'jumlah' => $jumlah,
            'bukti_transfer' => $bukti_transfer,
        ]);
    }

    $_SESSION['success'] = 'Bukti pembayaran berhasil dikirim! Menunggu konfirmasi admin.';
    header('Location: ' . BASE_URL . 'user/index');
    exit;
}

function riwayat()
{
    global $conn;
    requireUserLogin();

    $riwayat = PendaftaranModel::getByUser(
        $conn,
        $_SESSION['id']
    );

    require 'app/views/user/riwayat.php';
}

function updatependaftaran()
{
    global $conn;

    requireUserLogin();

    PendaftaranModel::update(
        $conn,
        $_POST['id'],
        $_POST['no_whatsapp'],
        $_POST['catatan']
    );

    header(
        'Location:' .
        BASE_URL .
        'user/riwayat'
    );

    exit;
}
?>