<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Open Trip | Lampung Trip</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body class="open-trip">

    <div class="navbar">

        <h2>Lampung Trip</h2>

        <div>

            <a href="<?= BASE_URL ?>user/index">Beranda</a>
            <a href="<?= BASE_URL ?>user/destinasi">Destinasi</a>
            <a class="active" href="<?= BASE_URL ?>user/opentrip">Open Trip</a>
            <a href="<?= BASE_URL ?>user/riwayat">Riwayat</a>
            <a href="<?= BASE_URL ?>auth/logout">Logout</a>

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
                            <?= date('d F Y', strtotime($t['tanggal'])) ?>
                        </p>

                        <p class="price">
                            Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                            /orang
                        </p>

                        <p class="kuota">
                            Kuota:
                            <?= htmlspecialchars($t['peserta_terdaftar']) ?> /
                            <?= htmlspecialchars($t['kuota']) ?>
                        </p>

                        <?php if ($t['status'] == 'penuh'): ?>

                            <button class="btn" disabled>
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
                Open trip tidak ditemukan.
            </p>

        <?php endif; ?>

    </div>

</body>

</html>