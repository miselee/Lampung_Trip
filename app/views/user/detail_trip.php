<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($trip['nama'] ?? '') ?> | Detail Open Trip</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/navbar.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/detail_trip.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/popup.css">
</head>

<body>

    <div class="navbar">
        <h2>Lampung Trip</h2>
        <div>
            <a href="<?= BASE_URL ?>index.php?url=user/index">Beranda</a>
            <a href="<?= BASE_URL ?>index.php?url=user/destinasi">Destinasi</a>
            <a class="active" href="<?= BASE_URL ?>index.php?url=user/opentrip">Open Trip</a>
            <a href="<?= BASE_URL ?>index.php?url=auth/logout">Logout</a>
        </div>
    </div>

    <div class="container">

        <div class="left-area">
            <div class="left-top">

                <div class="left">
                    <img id="mainImage" class="main-img"
                        src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($trip['foto'] ?? '') ?>"
                        onerror="this.src='<?= BASE_URL ?>assets/img/default.png'">

                    <div class="thumbs">
                        <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($trip['foto'] ?? '') ?>"
                            onclick="changeImg(this)" onerror="this.src='<?= BASE_URL ?>assets/img/default.png'">
                        <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($trip['foto'] ?? '') ?>"
                            onclick="changeImg(this)" onerror="this.src='<?= BASE_URL ?>assets/img/default.png'">
                        <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($trip['foto'] ?? '') ?>"
                            onclick="changeImg(this)" onerror="this.src='<?= BASE_URL ?>assets/img/default.png'">
                        <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($trip['foto'] ?? '') ?>"
                            onclick="changeImg(this)" onerror="this.src='<?= BASE_URL ?>assets/img/default.png'">
                    </div>
                </div>

                <div class="middle">
                    <h2><?= htmlspecialchars($trip['nama'] ?? '') ?></h2>
                    <p class="sub"><?= htmlspecialchars($trip['durasi'] ?? '1 hari') ?></p>
                    <p class="loc"><?= htmlspecialchars($trip['lokasi_destinasi'] ?? '') ?></p>

                    <p class="desc">
                        <?= nl2br(htmlspecialchars($trip['deskripsi'] ?? '')) ?>
                    </p>

                    <div class="fasilitas-box">
                        <div>Transport</div>
                        <div>Meeting Point</div>
                        <div>Makan</div>
                        <div>Group</div>
                    </div>
                </div>
            </div>

            <div class="left-bottom">
                <div class="left-info">
                    <div class="desc-box">
                        <h3>Deskripsi</h3>
                        <p><?= nl2br(htmlspecialchars($trip['deskripsi'] ?? '')) ?></p>
                    </div>

                    <div class="highlight-box">
                        <h3>Highlight</h3>
                        <p><?= htmlspecialchars($trip['highlight'] ?? 'Lihat destinasi terbaik') ?></p>
                    </div>
                </div>

                <div class="itinerary-box">
                    <h3>Itinerary</h3>
                    <p><?= nl2br(htmlspecialchars($trip['itinerary'] ?? 'Ikuti jadwal yang telah ditentukan')) ?></p>
                </div>
            </div>
        </div>

        <div class="right">
            <div class="card">
                <h3>
                    Rp <?= number_format($trip['harga'] ?? 0, 0, ',', '.') ?>
                    <span>/orang</span>
                </h3>

                <p class="kuota">
                    Kuota
                    <?= htmlspecialchars($trip['peserta_terdaftar'] ?? 0) ?>/<?= htmlspecialchars($trip['kuota'] ?? 0) ?>
                </p>

                <label>Tanggal</label>
                <input type="text" readonly value="<?= date('d M Y', strtotime($trip['tanggal'] ?? '')) ?>">

                <?php
                $terdaftar = (int) ($trip['peserta_terdaftar'] ?? 0);
                $kuota = (int) ($trip['kuota'] ?? 0);
                $penuh = ($kuota > 0 && $terdaftar >= $kuota);
                ?>

                <?php if ($penuh): ?>
                    <button class="btn" disabled style="opacity:.5;cursor:not-allowed;">Trip Penuh</button>
                <?php else: ?>
                    <button class="btn" id="btnDaftar" type="button">Daftar Sekarang</button>
                <?php endif; ?>

                <a class="btn-wa" target="_blank" href="https://wa.me/6281234567890">
                    WhatsApp
                </a>
            </div>

            <div class="card pembayaran">
                <h4>Pembayaran</h4>

                <div class="bank">
                    <img src="<?= BASE_URL ?>assets/img/bri.png" onerror="this.style.display='none'">
                    <div>
                        <p>1234 5678 9123</p>
                        <small>a.n Lampung Trip</small>
                    </div>
                </div>

                <div class="bank">
                    <img src="<?= BASE_URL ?>assets/img/bni.png" onerror="this.style.display='none'">
                    <div>
                        <p>9876 5432 1234</p>
                        <small>a.n Lampung Trip</small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div id="daftar-overlay">
        <div class="modal-wide">

            <div class="m-header">
                <h2>Daftar &amp; Pembayaran</h2>
                <p><?= htmlspecialchars($trip['nama'] ?? '') ?></p>
                <button class="m-close" type="button" id="btnTutupOverlay">&#10005;</button>
            </div>

            <form method="POST" action="<?= BASE_URL ?>index.php?url=user/daftartrip" enctype="multipart/form-data">
                <input type="hidden" name="open_trip_id" value="<?= (int) ($trip['id'] ?? 0) ?>">
                <input type="hidden" name="harga_satuan" value="<?= (int) ($trip['harga'] ?? 0) ?>">

                <div class="m-body">

                    <div class="m-left">
                        <p class="sec-title">Data Pendaftaran</p>

                        <div class="fg">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="fg">
                            <label>Nomor WhatsApp</label>
                            <input type="tel" name="no_whatsapp" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="fg">
                            <label>Jumlah Orang</label>
                            <input type="number" name="jumlah_orang" id="jumlahOrang" value="1" min="1"
                                max="<?= (int) ($trip['kuota'] ?? 10) ?>" required id="inputJumlah">
                        </div>
                        <div class="fg">
                            <label>Catatan (Opsional)</label>
                            <textarea name="catatan" placeholder="Catatan atau pertanyaan..."></textarea>
                        </div>
                        <div class="rincian">
                            <p class="sec-title" style="margin-bottom:10px;">Rincian Biaya</p>
                            <div class="rincian-row">
                                <span>Harga per Orang</span>
                                <span>Rp <?= number_format($trip['harga'] ?? 0, 0, ',', '.') ?></span>
                            </div>
                            <div class="rincian-row">
                                <span>Jumlah Peserta</span>
                                <span id="lblJumlah">1 Orang</span>
                            </div>
                            <div class="rincian-row">
                                <span>Total Pembayaran</span>
                                <span id="lblTotal">Rp <?= number_format($trip['harga'] ?? 0, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="m-right">
                        <p class="sec-title">Transfer ke Rekening</p>

                        <div class="rek-card">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" alt="BRI"
                                class="rek-logo">
                            <div>
                                <div class="rek-nomor">1234 5678 9012 3456</div>
                                <div class="rek-name">a.n Lampung Trip</div>
                            </div>
                        </div>

                        <div class="rek-note">
                            &#9432; Setelah transfer, segera upload bukti pembayaran di bawah ini.
                        </div>

                        <div class="fg">
                            <label>Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti_transfer" id="buktiTransfer" accept=".jpg,.jpeg,.png,.pdf"
                                style="display:none;"
                                onchange="document.getElementById('namaFile').textContent = this.files[0]?.name || 'Belum ada file'">
                            <div class="upload-area" onclick="document.getElementById('buktiTransfer').click()">
                                <span class="upload-icon">&#128206;</span>
                                <span id="namaFile">Klik untuk pilih file (JPG, PNG, PDF)</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-pay">
                            &#10003; Daftar &amp; Kirim Pembayaran
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <script>var TRIP_HARGA = <?= (int) ($trip['harga'] ?? 0) ?>;</script>
    <script src="<?= BASE_URL ?>assets/js/user.js"></script>

</body>

</html>