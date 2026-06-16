<?php

$host     = "sql200.infinityfree.com";
$username = "if0_42194397";
$password = "l4mpungtrip";
$database = "if0_42194397_lampungtrip";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>