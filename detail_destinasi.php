<?php
require 'config.php';

if (!isset($_GET['id'])) {
    die("ID destinasi tidak ditemukan.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM destinasi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Destinasi tidak ditemukan.");
}

$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($data['nama_destinasi']) ?> | Lampung Trip</title>
  <link rel="stylesheet" href="css/detail_destinasi.css">
</head>
<body>

<div class="navbar">
  <div class="logo">Lampung Trip</div>
  <div class="menu">
    <a href="user.php">Beranda</a>
    <a href="destinasi.php" class="active">Destinasi</a>
    <a href="open_trip.php">Open Trip</a>
    <a href="logout.php">Logout</a>
  </div>
</div>


<div class="hero" style="background-image: url('img/<?= htmlspecialchars($data['gambar']) ?>'); 
     background-size: cover; 
     background-position: center;">
</div>

<div class="thumbs">
  <img src="img/<?= htmlspecialchars($data['gambar']) ?>" alt="thumbnail">
  <img src="img/<?= htmlspecialchars($data['gambar']) ?>" alt="thumbnail">
  <img src="img/<?= htmlspecialchars($data['gambar']) ?>" alt="thumbnail">
  <img src="img/<?= htmlspecialchars($data['gambar']) ?>" alt="thumbnail">
</div>

<div class="container">
  <div class="title">
    <?= htmlspecialchars($data['nama_destinasi']) ?>
    <span class="rating">
      ★ <?= htmlspecialchars($data['rating']) ?>
    </span>
  </div>

  <div class="subtitle">
    <?= htmlspecialchars($data['lokasi']) ?>
  </div>

  <hr>

  <div class="content">
    <div class="left">
      <div class="section-title">Deskripsi</div>
      <div class="text">
        <?= nl2br(htmlspecialchars($data['deskripsi'])) ?>
      </div>

      <div class="section-title">Fasilitas</div>
      <div class="text">
        <?= nl2br(htmlspecialchars($data['fasilitas'])) ?>
      </div>
    </div>

    <div class="right">
      <div class="map">
        <iframe
          src="https://www.google.com/maps?q=<?= urlencode($data['nama_destinasi']) ?>&output=embed"
          loading="lazy">
        </iframe>
      </div>
    </div>
  </div>

  <a class="btn">
    Pesan Sekarang
</a>
</div>

</body>
</html>