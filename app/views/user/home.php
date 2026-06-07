<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home User</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="user">

    <div class="navbar">

        <h2>Lampung Trip</h2>

        <div>
            <a class="active" href="<?= BASE_URL ?>user/index">
                <i class="fa-solid fa-house"></i> Beranda
            </a>

            <a href="<?= BASE_URL ?>user/destinasi">
                <i class="fa-solid fa-location-dot"></i> Destinasi
            </a>

            <a href="<?= BASE_URL ?>user/opentrip">
                <i class="fa-solid fa-users"></i> Open Trip
            </a>

            <a href="<?= BASE_URL ?>user/riwayat">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
            </a>

            <a href="<?= BASE_URL ?>auth/logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>

    </div>

    <section class="hero">

        <div class="hero-content">

            <h1>Jelajahi Keindahan Lampung</h1>

            <p>
                Temukan destinasi wisata terbaik,
                budaya, kuliner dan open trip menarik di Lampung
            </p>

            <div class="search-box">

                <input type="text" id="searchInput" placeholder="Cari destinasi wisata">

            </div>

        </div>

    </section>

    <section class="rekomendasi">

        <h2>Rekomendasi Destinasi</h2>

        <div class="wrapper">

            <button class="prev" id="prevBtn">
                < </button>

                    <div class="cards" id="cards">

                        <?php foreach ($destinasi_rekomendasi as $d): ?>

                            <div class="card">

                                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($d['foto']) ?>">

                                <h3>
                                    <?= htmlspecialchars($d['nama']) ?>
                                </h3>

                                <p>
                                    <?= htmlspecialchars($d['lokasi']) ?>
                                </p>

                                <span>
                                    ⭐ <?= htmlspecialchars($d['rating']) ?>
                                </span>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <button class="next" id="nextBtn">
                        >
                    </button>

        </div>

    </section>

    <section class="berita">

        <h2>Open Trip Terbaru</h2>

        <div class="berita-container">

            <?php foreach ($open_trips_aktif as $trip): ?>

                <div class="berita-card">

                    <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($trip['foto']) ?>">

                    <div class="berita-content">

                        <h3>
                            <?= htmlspecialchars($trip['nama']) ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($trip['deskripsi']) ?>
                        </p>

                        <span>
                            <?= htmlspecialchars($trip['tanggal']) ?>
                        </span>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </section>
    <section class="berita">

        <h2>Berita Wisata</h2>

        <div class="berita-container">

            <div class="berita-card">

                <img src="<?= BASE_URL ?>assets/img/festival-krakatau.avif">

                <div class="berita-content">

                    <h3>Festival Krakatau</h3>

                    <p>
                        Ribuan wisatawan memadati Festival Krakatau di Lampung
                        untuk menikmati pertunjukan seni, budaya, dan kuliner khas Lampung.
                    </p>

                    <span>15 Mei 2026</span>

                </div>

            </div>

            <div class="berita-card">

                <img src="<?= BASE_URL ?>assets/img/seruit.jpg">

                <div class="berita-content">

                    <h3>Wisata Kuliner Lampung Makin Digemari</h3>

                    <p>
                        Seruit dan tempoyak jadi incaran wisatawan
                        lokal dan mancanegara.
                    </p>

                    <span>20 April 2026</span>

                </div>

            </div>

        </div>

    </section>
    <script>
        window.BASE_URL = "<?= BASE_URL ?>";
    </script>

    <script src="<?= BASE_URL ?>assets/js/user.js"></script>

</body>

</html>