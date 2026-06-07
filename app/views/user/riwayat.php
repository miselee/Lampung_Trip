<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat | Lampung Trip</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="riwayat-page">

    <div class="navbar">
        <h2>Lampung Trip</h2>

        <div>
            <a href="<?= BASE_URL ?>user/index">
                <i class="fa-solid fa-house"></i> Beranda
            </a>

            <a href="<?= BASE_URL ?>user/destinasi">
                <i class="fa-solid fa-location-dot"></i> Destinasi
            </a>

            <a href="<?= BASE_URL ?>user/opentrip">
                <i class="fa-solid fa-users"></i> Open Trip
            </a>

            <a class="active" href="<?= BASE_URL ?>user/riwayat">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
            </a>

            <a href="<?= BASE_URL ?>auth/logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>


    <div class="riwayat-container">

        <?php if(empty($riwayat)): ?>
            <div class="empty-box">
                Belum ada riwayat pendaftaran
            </div>
        <?php endif; ?>

        <div class="riwayat-list">
            <?php foreach($riwayat as $r): ?>
            <div class="riwayat-card">

                <div class="riwayat-top">
                    <div>
                        <h2>
                            <?= htmlspecialchars($r['nama_trip']) ?>
                        </h2>

                        <p class="tanggal">
                            <i class="fa-solid fa-calendar-days"></i>
                            <?= date('d M Y', strtotime($r['created_at'])) ?>
                        </p>
                    </div>
                </div>

                <div class="riwayat-info">
                    <div class="info-box">
                        <span>
                            <i class="fa-solid fa-user"></i>
                            Nama
                        </span>

                        <strong>
                            <?= htmlspecialchars($r['nama_lengkap']) ?>
                        </strong>
                    </div>

                    <div class="info-box">
                        <span>
                            <i class="fa-solid fa-users"></i>
                            Jumlah Orang
                        </span>

                        <strong>
                            <?= $r['jumlah_orang'] ?> Orang
                        </strong>
                    </div>
                </div>

                <div class="status-wrapper">
                    <div class="status-item">

                        <span>
                            <i class="fa-solid fa-file-signature"></i>
                            Pendaftaran
                        </span>

                        <?php if($r['status']=="menunggu"): ?>

                            <span class="badge pending">
                                Menunggu
                            </span>

                        <?php elseif($r['status']=="disetujui"): ?>

                            <span class="badge approved">
                                Disetujui
                            </span>

                        <?php else: ?>

                            <span class="badge rejected">
                                Ditolak
                            </span>

                        <?php endif; ?>
                    </div>


                    <div class="status-item">
                        <span>
                            <i class="fa-solid fa-credit-card"></i>
                            Pembayaran
                        </span>

                        <?php if($r['status_pembayaran']=="belum_upload"): ?>

                            <span class="badge pending">
                                Belum Upload
                            </span>

                        <?php elseif($r['status_pembayaran']=="menunggu"): ?>

                            <span class="badge pending">
                                Menunggu Verifikasi
                            </span>

                        <?php elseif($r['status_pembayaran']=="lunas"): ?>

                            <span class="badge approved">
                                Lunas
                            </span>

                        <?php else: ?>

                            <span class="badge rejected">
                                Ditolak
                            </span>

                        <?php endif; ?>
                    </div>
                </div>


                <?php if(!empty($r['alasan_tolak'])): ?>

                <div class="alasan-box">

                    <b>
                        <i class="fa-solid fa-circle-exclamation"></i>
                        Alasan Penolakan:
                    </b>

                    <p>
                        <?= htmlspecialchars($r['alasan_tolak']) ?>
                    </p>

                </div>

                <?php endif; ?>

                <?php if($r['status_pembayaran']=="belum_upload"): ?>

                <a
                class="btn-upload"
                href="<?= BASE_URL ?>user/detailtrip?id=<?= $r['open_trip_id'] ?>&show_payment=1&pendaftaran_id=<?= $r['id'] ?>">

                <i class="fa-solid fa-cloud-arrow-up"></i>
                Upload Bukti
                </a>

                <?php elseif(
                    $r['status_pembayaran']=="menunggu"
                    || $r['status_pembayaran']=="ditolak"
                ): ?>

                <a
                class="btn-upload"
                href="<?= BASE_URL ?>user/detailtrip?id=<?= $r['open_trip_id'] ?>&show_payment=1&pendaftaran_id=<?= $r['id'] ?>">

                <i class="fa-solid fa-pen-to-square"></i>
                Ubah Bukti

                </a>

                <?php endif; ?>
            </div>

            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>