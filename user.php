<?php
session_start();

if(!isset($_SESSION['email']) || $_SESSION['role'] != "user"){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home User</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/user.css">
</head>
<body>
    
    <div class="navbar">
        <h2>Lampung Trip</h2>
        <div>
            <a class="active" href="user.php">Beranda</a>
            <a href="destinasi.html">Destinasi</a>
            <a href="open_trip.html">Open Trip</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <section class="hero">
        <div class="hero-content">
            <h1>Jelajahi Keindahan Lampung</h1>
            <p>Temukan destinasi wisata terbaik, budaya, kuliner dan open trip menarik di Lampung</p>

            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari destinasi wisata">
            </div>
        </div>
    </section>

    <section class="rekomendasi">
        <h2>Rekomendasi Destinasi</h2>
        <div class="wrapper">
            <button class="prev"><</button>

            <div class="cards" id="cards">
                <div class="card">
                    <img src="img/pahawang.png">
                    <h3>Pantai Pahawang</h3>
                    <p>Pesawaran</p>
                    <span>⭐ 4.9 (280 ulasan)</span>
                </div>

                <div class="card">
                    <img src="img/seminung.png">
                    <h3>Gunung Seminung</h3>
                    <p>Liwa</p>
                    <span>⭐ 4.8 (150 ulasan)</span>
                </div>

                <div class="card">
                    <img src="img/krakatau.jpg">
                    <h3>Gunung Krakatau</h3>
                    <p>Pesisir Utara</p>
                    <span>⭐ 4.7 (200 ulasan)</span>
                </div>

                <div class="card">
                    <img src="img/mahitam.jpg">
                    <h3>Pantai Mahitam</h3>
                    <p>Pesawaran</p>
                    <span>⭐ 4.9 (280 ulasan)</span>
                </div>
            </div>

            <button class="next">></button>
        </div>
    </section>

    <section class="berita">
        <h2>Berita Wisata</h2>

        <div class="berita-container">
            <div class="berita-card">
                <img src="img/festival-krakatau.avif">
                <div class="berita-content">
                    <h3>Festival Krakatau</h3>
                    <p>Ribuan wisatawan memadati Festival Krakatau di Lampung untuk menikmati pertunjukan seni, budaya, dan kuliner khas Lampung yang digelar setiap tahun.</p>
                    <span>15 Mei 2023</span>
                </div>
            </div>

            <div class="berita-card">
                <img src="img/seruit.jpg">
                <div class="berita-content">
                    <h3>Wisata Kuliner Lampung Makin Digemari</h3>
                    <p>Seruit dan tempoyak jadi incaran wisatawan lokal dan mancanegara</p>
                    <span>20 April 2026</span>
                </div>
            </div>
        </div>
    </section>
</body>
</html>