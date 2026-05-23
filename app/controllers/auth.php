<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'koneksi/koneksi.php';

function login()
{
    if (isset($_SESSION['id'])) {
        $role = $_SESSION['role'] ?? 'user';
        header('Location: ' . BASE_URL . ($role === 'admin' ? 'admin/index' : 'user/index'));
        exit;
    }
    require 'app/views/auth/login.php';
}

function register()
{
    if (isset($_SESSION['id'])) {
        header('Location: ' . BASE_URL . 'user/index');
        exit;
    }
    require 'app/views/auth/register.php';
}

function proseslogin()
{
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email dan password wajib diisi.';
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'Email atau password salah.';
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }

    $_SESSION['id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'admin') {
        header('Location: ' . BASE_URL . 'admin/index');
    } else {
        header('Location: ' . BASE_URL . 'user/index');
    }
    exit;
}
function prosesregister()
{
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . 'auth/register');
        exit;
    }

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($nama) || empty($email) || empty($password)) {
        $_SESSION['error'] = 'Semua field wajib diisi.';
        header('Location: ' . BASE_URL . 'auth/register');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Password minimal 6 karakter.';
        header('Location: ' . BASE_URL . 'auth/register');
        exit;
    }

    if ($password !== $password_confirm) {
        $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
        header('Location: ' . BASE_URL . 'auth/register');
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $_SESSION['error'] = 'Email sudah terdaftar. Silakan gunakan email lain.';
        header('Location: ' . BASE_URL . 'auth/register');
        exit;
    }
    $stmt->close();

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param('sss', $nama, $email, $hashed);

    if ($stmt->execute()) {
        $stmt->close();
        $_SESSION['success'] = 'Akun berhasil dibuat! Silakan login.';
        header('Location: ' . BASE_URL . 'auth/login');
    } else {
        $stmt->close();
        $_SESSION['error'] = 'Terjadi kesalahan. Coba lagi.';
        header('Location: ' . BASE_URL . 'auth/register');
    }
    exit;
}

function logout()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    header('Location: ' . BASE_URL . 'auth/login');
    exit;
}
?>