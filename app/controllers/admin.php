<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'koneksi/koneksi.php';
require_once 'app/models/DestinasiModel.php';
require_once 'app/models/TripModel.php';
require_once 'app/models/PendaftaranModel.php';
require_once 'app/models/PembayaranModel.php';


function requireAdmin()
{
    if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }
}


function index()
{
    global $conn;
    requireAdmin();

    $stat_destinasi  = DestinasiModel::count($conn);
    $stat_trip_aktif = TripModel::countAktif($conn);
    $stat_pendaftar  = PendaftaranModel::countBulanIni($conn);
    $stat_pembayaran = PembayaranModel::totalPendapatan($conn);
    $stat_pending    = PendaftaranModel::countByStatus($conn, 'menunggu');
    $pendaftaran_terbaru = PendaftaranModel::getTerbaru($conn, 5);
    $pembayaran_terbaru  = PembayaranModel::getMenunggu($conn, 4);
    $upcoming_trips      = TripModel::getAktifTerbaru($conn, 4);
    
    $count_pendaftaran = PendaftaranModel::countByStatus($conn, 'menunggu');
    $count_pembayaran  = PembayaranModel::countMenunggu($conn);

    require 'app/views/admin/dashboard.php';
}

function destinasi()
{
    global $conn;
    requireAdmin();

    $destinasi = DestinasiModel::getAll($conn);

    $count_pendaftaran = PendaftaranModel::countByStatus($conn, 'menunggu');
    $count_pembayaran  = PembayaranModel::countMenunggu($conn);

    require 'app/views/admin/admin_destinasi.php';
}

function tambahdestinasi()
{
    global $conn;
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'admin/destinasi');
        exit;
    }

    $data = [
        'nama'      => trim($_POST['nama']      ?? ''),
        'lokasi'    => trim($_POST['lokasi']    ?? ''),
        'kategori'  => trim($_POST['kategori']  ?? ''),
        'deskripsi' => trim($_POST['deskripsi'] ?? ''),
    ];

    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $filename = 'dest_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], 'assets/img/' . $filename);
        $foto = $filename;
    }

    DestinasiModel::insert($conn, $data, $foto);

    $_SESSION['success'] = 'Destinasi berhasil ditambahkan.';
    header('Location: ' . BASE_URL . 'admin/destinasi');
    exit;
}

function editdestinasi()
{
    global $conn;

    $id = (int) $_POST['id'];

    $data = [
        'nama'       => $_POST['nama'],
        'lokasi'     => $_POST['lokasi'],
        'kategori'   => $_POST['kategori'],
        'deskripsi'  => $_POST['deskripsi']
    ];

    $foto = null;

    if (!empty($_FILES['foto']['name'])) {

        $foto = time() . '_' . $_FILES['foto']['name'];

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            'assets/img/' . $foto
        );
    }

    DestinasiModel::update($conn, $id, $data, $foto);

    $_SESSION['success'] = 'Destinasi berhasil diupdate';

    header('Location: ' . BASE_URL . 'admin/destinasi');
    exit;
}

function hapusdestinasi()
{
    global $conn;
    requireAdmin();

    $id = intval($_POST['id'] ?? 0);
    DestinasiModel::delete($conn, $id);

    $_SESSION['success'] = 'Destinasi berhasil dihapus.';
    header('Location: ' . BASE_URL . 'admin/destinasi');
    exit;
}

function opentrip()
{
    global $conn;
    requireAdmin();

    $open_trips = TripModel::getAllForAdmin($conn);
    $destinasi  = TripModel::getDestinasiList($conn);

    $count_pendaftaran = PendaftaranModel::countByStatus($conn, 'menunggu');
    $count_pembayaran  = PembayaranModel::countMenunggu($conn);

    require 'app/views/admin/admin_opentrip.php';
}

function tambahtrip()
{
    global $conn;
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'admin/opentrip');
        exit;
    }

    $data = [
        'nama'         => trim($_POST['nama']         ?? ''),
        'destinasi_id' => intval($_POST['destinasi_id'] ?? 0),
        'tanggal'      => $_POST['tanggal']            ?? '',
        'durasi'       => trim($_POST['durasi']        ?? '1 hari'),
        'harga'        => floatval($_POST['harga']     ?? 0),
        'kuota'        => intval($_POST['kuota']       ?? 10),
        'deskripsi'    => trim($_POST['deskripsi']     ?? ''),
        'fasilitas'    => trim($_POST['fasilitas']     ?? ''),
    ];

    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $filename = 'trip_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], 'assets/img/' . $filename);
        $foto = $filename;
    }

    TripModel::insert($conn, $data, $foto);

    $_SESSION['success'] = 'Open Trip berhasil ditambahkan.';
    header('Location: ' . BASE_URL . 'admin/opentrip');
    exit;
}

function edittrip()
{
    global $conn;
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'admin/opentrip');
        exit;
    }

    $id = intval($_POST['id'] ?? 0);

    $tripLama = TripModel::getByIdOpenTrip($conn, $id);

    if (!$tripLama) {
        $_SESSION['error'] = 'Trip tidak ditemukan.';
        header('Location: ' . BASE_URL . 'admin/opentrip');
        exit;
    }

    $data = [
        'nama'         => trim($_POST['nama'] ?? ''),
        'destinasi_id' => intval($_POST['destinasi_id'] ?? 0),
        'tanggal'      => $_POST['tanggal'] ?? '',
        'durasi'       => trim($_POST['durasi'] ?? ''),
        'harga'        => floatval($_POST['harga'] ?? 0),
        'kuota'        => intval($_POST['kuota'] ?? 0),
        'deskripsi'    => trim($_POST['deskripsi'] ?? ''),
        'fasilitas'    => trim($_POST['fasilitas'] ?? ''),
    ];

    $foto = $tripLama['foto'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $ext      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        $filename = 'trip_' . time() . '.' . $ext;

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            'assets/img/' . $filename
        );

        $foto = $filename;
    }

    TripModel::update($conn, $id, $data, $foto);

    $_SESSION['success'] = 'Open Trip berhasil diperbarui.';

    header('Location: ' . BASE_URL . 'admin/opentrip');
    exit;
}

function hapustrip()
{
    global $conn;
    requireAdmin();

    $id = intval($_POST['id'] ?? 0);
    TripModel::delete($conn, $id);

    $_SESSION['success'] = 'Open Trip berhasil dihapus.';
    header('Location: ' . BASE_URL . 'admin/opentrip');
    exit;
}

function pendaftaran()
{
    global $conn;
    requireAdmin();

    $status_filter   = $_GET['status'] ?? 'semua';
    $pendaftaran     = PendaftaranModel::getAll($conn, $status_filter);
    $count_disetujui = PendaftaranModel::countByStatus($conn, 'disetujui');
    $count_ditolak   = PendaftaranModel::countByStatus($conn, 'ditolak');

    $count_pendaftaran = PendaftaranModel::countByStatus($conn, 'menunggu');
    $count_pembayaran  = PembayaranModel::countMenunggu($conn);

    require 'app/views/admin/admin_pendaftaran.php';
}

function aksipendaftaran()
{
    global $conn;
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'admin/pendaftaran');
        exit;
    }

    $id     = intval($_POST['pendaftaran_id'] ?? $_POST['id'] ?? 0);
    $aksi   = $_POST['aksi'] ?? '';
    $alasan = trim($_POST['alasan_tolak'] ?? '');

    if ($aksi === 'disetujui') {
        PendaftaranModel::updateStatus($conn, $id, 'disetujui');
        $_SESSION['success'] = 'Pendaftaran berhasil disetujui.';
    } elseif ($aksi === 'ditolak') {
        PendaftaranModel::updateStatus($conn, $id, 'ditolak', $alasan);
        $_SESSION['success'] = 'Pendaftaran berhasil ditolak.';
    }

    header('Location: ' . BASE_URL . 'index.php?url=admin/pendaftaran');
    exit;
}


function pembayaran()
{
    global $conn;
    requireAdmin();

    $pembayaran     = PembayaranModel::getAll($conn);

    $count_pendaftaran = PendaftaranModel::countByStatus($conn, 'menunggu');
    $count_pembayaran  = PembayaranModel::countMenunggu($conn);

    require 'app/views/admin/admin_pembayaran.php';
}

function aksipembayaran()
{
    global $conn;
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'admin/pembayaran');
        exit;
    }

    $id   = intval($_POST['id']   ?? 0);
    $aksi = $_POST['aksi']        ?? '';

    if ($aksi === 'lunas') {
        PembayaranModel::updateStatus($conn, $id, 'lunas');
        $_SESSION['success'] = 'Pembayaran dikonfirmasi lunas.';
    } elseif ($aksi === 'tolak') {
        PembayaranModel::updateStatus($conn, $id, 'ditolak');
        $_SESSION['success'] = 'Pembayaran ditolak.';
    }

    header('Location: ' . BASE_URL . 'admin/pembayaran');
    exit;
}
?>
