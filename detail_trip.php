<?php
require 'config.php';

if (!isset($_GET['id'])) {
    die("ID trip tidak ditemukan.");
}

$id = intval($_GET['id']);

$sql = "
    SELECT 
        ot.*,
        d.nama_destinasi,
        d.lokasi,
        d.deskripsi,
        d.gambar
    FROM open_trip ot
    JOIN destinasi d ON ot.destinasi_id = d.id
    WHERE ot.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data trip tidak ditemukan.");
}

$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($data['nama_destinasi']) ?> | Detail Open Trip</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/detail_trip.css">
</head>
<body>

<div class="navbar">
    <h2>Lampung Trip</h2>
    <div>
        <a href="user.php">Beranda</a>
        <a href="destinasi.php">Destinasi</a>
        <a class="active" href="open_trip.php">Open Trip</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="left-area">
        <div class="left-top">

            <div class="left">
                <img id="mainImage"
                     class="main-img"
                     src="img/<?= htmlspecialchars($data['gambar']) ?>">

                <div class="thumbs">
                    <img src="img/<?= htmlspecialchars($data['gambar']) ?>" onclick="changeImg(this)">
                    <img src="img/<?= htmlspecialchars($data['gambar']) ?>" onclick="changeImg(this)">
                    <img src="img/<?= htmlspecialchars($data['gambar']) ?>" onclick="changeImg(this)">
                    <img src="img/<?= htmlspecialchars($data['gambar']) ?>" onclick="changeImg(this)">
                </div>
            </div>

            <div class="middle">
                <h2><?= htmlspecialchars($data['nama_destinasi']) ?></h2>
                <p class="sub"><?= htmlspecialchars($data['durasi']) ?></p>
                <p class="loc">📍 <?= htmlspecialchars($data['lokasi']) ?></p>

                <p class="desc">
                    <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
                </p>

                <div class="fasilitas-box">
                    <div>🚌 Transport</div>
                    <div>📍 Meeting Point</div>
                    <div>🍽 Makan</div>
                    <div>👥 Group</div>
                </div>
            </div>
        </div>

        <div class="left-bottom">
            <div class="left-info">
                <div class="desc-box">
                    <h3>Deskripsi</h3>
                    <p><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></p>
                </div>

                <div class="highlight-box">
                    <h3>Highlight</h3>
                    <p><?= nl2br(htmlspecialchars($data['highlight'])) ?></p>
                </div>
            </div>

            <div class="itinerary-box">
                <h3>Itinerary</h3>
                <p><?= nl2br(htmlspecialchars($data['itinerary'])) ?></p>
            </div>
        </div>
    </div>

    <div class="right">
        <div class="card">
            <h3>
                Rp <?= number_format($data['harga'], 0, ',', '.') ?>
                <span>/orang</span>
            </h3>

            <p class="kuota">
                Kuota <?= $data['kuota_terisi'] ?>/<?= $data['kuota'] ?>
            </p>

            <label>Tanggal</label>
            <input type="text"
                   readonly
                   value="<?= date('d M Y', strtotime($data['tanggal_mulai'])) ?>
 - <?= date('d M Y', strtotime($data['tanggal_selesai'])) ?>">

            <button class="btn" onclick="openOverlay()">
                Daftar Sekarang
            </button>

            <a class="btn-wa"
               target="_blank"
               href="https://wa.me/6281234567890">
                WhatsApp
            </a>
        </div>

        <div class="card pembayaran">
            <h4>Pembayaran</h4>

            <div class="bank">
                <img src="img/bri.png">
                <div>
                    <p>1234 5678 9123</p>
                    <small>a.n Lampung Trip</small>
                </div>
            </div>

            <div class="bank">
                <img src="img/bni.png">
                <div>
                    <p>9876 5432 1234</p>
                    <small>a.n Lampung Trip</small>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function changeImg(el) {
    document.getElementById("mainImage").src = el.src;
}

function openOverlay() {
    document.getElementById("overlayform").style.display = "block";
}

function closeOverlay() {
    document.getElementById("overlayform").style.display = "none";
}

function openPayment() {
    document.getElementById("overlayform").style.display = "none";
    document.getElementById("overlaypayment").style.display = "block";
}

function closePayment() {
    document.getElementById("overlaypayment").style.display = "none";
}
</script>

</body>
</html>