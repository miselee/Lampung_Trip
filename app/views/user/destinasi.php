<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinasi | Lampung Trip</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body class="destinasi">

    <div class="navbar">
        <h2>Lampung Trip</h2>
        <div>
            <a href="<?= BASE_URL ?>user/index">Beranda</a>
            <a class="active" href="<?= BASE_URL ?>user/destinasi">Destinasi</a>
            <a href="<?= BASE_URL ?>user/opentrip">Open Trip</a>
            <a href="<?= BASE_URL ?>user/riwayat">Riwayat</a>
            <a href="<?= BASE_URL ?>auth/logout">Logout</a>
        </div>
    </div>

    <div class="search">
        <form method="GET" action="<?= BASE_URL ?>index.php?url=user/destinasi">
            <input type="text" name="q" id="search" placeholder="Cari destinasi wisata..."
                value="<?= htmlspecialchars($search ?? '') ?>">
        </form>
    </div>

    <div class="container" id="list">

        <?php if (!empty($destinasi)): ?>
            <?php foreach ($destinasi as $row): ?>
                <div class="card">
                    <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($row['foto'] ?? '') ?>"
                        alt="<?= htmlspecialchars($row['nama'] ?? '') ?>"
                        onerror="this.src='<?= BASE_URL ?>assets/img/default.png'">

                    <div class="card-body">
                        <h3><?= htmlspecialchars($row['nama'] ?? '') ?></h3>
                        <p><?= htmlspecialchars($row['lokasi'] ?? '') ?></p>

                        <div class="rating">
                            ⭐ <?= htmlspecialchars($row['rating'] ?? '5') ?>
                        </div>

                        <a class="btn" href="<?= BASE_URL ?>index.php?url=user/detaildestinasi&id=<?= $row['id'] ?>">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; width:100%;">
                Destinasi tidak ditemukan.
            </p>
        <?php endif; ?>

    </div>

</body>

</html>