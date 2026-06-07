<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Open Trip | Lampung Trip</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="open-trip">

    <div class="navbar">

        <h2>Lampung Trip</h2>

        <div>
            <a href="<?= BASE_URL ?>user/index">
                <i class="fa-solid fa-house"></i> Beranda
            </a>

            <a href="<?= BASE_URL ?>user/destinasi">
                <i class="fa-solid fa-location-dot"></i> Destinasi
            </a>

            <a class="active" href="<?= BASE_URL ?>user/opentrip">
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

    <div class="container">

    <?php if (!empty($open_trips)): ?>

        <?php foreach ($open_trips as $t): ?>

            <div class="card">

                <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($t['foto']) ?>" alt="gambar"
                    onerror="this.src='<?= BASE_URL ?>assets/img/pahawang.png'">

                <div class="card-body">

                    <h3>
                        <?= htmlspecialchars($t['nama']) ?>
                    </h3>

                    <p>
                        <i class="fa-solid fa-calendar-days icon"></i>
                        <?= date('d F Y', strtotime($t['tanggal'])) ?>
                    </p>

                    <p class="price">
                        <i class="fa-solid fa-money-bill-wave icon"></i>
                        Rp <?= number_format($t['harga'], 0, ',', '.') ?> /orang
                    </p>

                    <p class="kuota">
                        <i class="fa-solid fa-user-group icon"></i>
                        Kuota: <?= htmlspecialchars($t['peserta_terdaftar']) ?> / <?= htmlspecialchars($t['kuota']) ?>
                    </p>

                    <?php if ($t['status'] == 'penuh'): ?>

                        <button class="btn" disabled>
                            <i class="fa-solid fa-circle-xmark"></i>
                            Trip Penuh
                        </button>

                    <?php else: ?>

                        <a class="btn" href="<?= BASE_URL ?>user/detailtrip?id=<?= $t['id'] ?>">
                            Lihat Detail
                        </a>

                    <?php endif; ?>

                </div>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <p style="text-align:center; width:100%;">
            <i class="fa-solid fa-magnifying-glass"></i>
            Open trip tidak ditemukan.
        </p>

    <?php endif; ?>

</div>

</body>

</html>