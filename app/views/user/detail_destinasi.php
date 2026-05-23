<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($destinasi['nama'] ?? '') ?> | Lampung Trip</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/detail_destinasi.css">
</head>

<body>

  <div class="navbar">
    <div class="logo">Lampung Trip</div>
    <div class="menu">
      <a href="<?= BASE_URL ?>user/index">Beranda</a>
      <a href="<?= BASE_URL ?>user/destinasi" class="active">Destinasi</a>
      <a href="<?= BASE_URL ?>user/open_trip">Open Trip</a>
      <a href="<?= BASE_URL ?>auth/logout">Logout</a>
    </div>
  </div>


  <div class="hero" style="background-image: url('<?= BASE_URL ?>assets/img/<?= htmlspecialchars($destinasi['foto'] ?? '') ?>'); 
     background-size: cover; 
     background-position: center;">
  </div>

  <div class="thumbs">
    <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($destinasi['foto'] ?? '') ?>" alt="thumbnail"
      onclick="changeImg(this)">
    <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($destinasi['foto'] ?? '') ?>" alt="thumbnail"
      onclick="changeImg(this)">
    <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($destinasi['foto'] ?? '') ?>" alt="thumbnail"
      onclick="changeImg(this)">
    <img src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($destinasi['foto'] ?? '') ?>" alt="thumbnail"
      onclick="changeImg(this)">
  </div>

  <div class="container">
    <div class="title">
      <?= htmlspecialchars($destinasi['nama'] ?? '') ?>
      <span class="rating">
        ★ <?= htmlspecialchars($destinasi['rating'] ?? '5') ?>
      </span>
    </div>

    <div class="subtitle">
      <?= htmlspecialchars($destinasi['lokasi'] ?? '') ?>
    </div>

    <hr>

    <div class="content">
      <div class="left">
        <div class="section-title">Deskripsi</div>
        <div class="text">
          <?= nl2br(htmlspecialchars($destinasi['deskripsi'] ?? '')) ?>
        </div>

        <div class="section-title">Kategori</div>
        <div class="text">
          <?= htmlspecialchars($destinasi['kategori'] ?? '') ?>
        </div>
      </div>

      <div class="right">
        <div class="map">
          <iframe src="https://www.google.com/maps?q=<?= urlencode($destinasi['nama'] ?? '') ?>&output=embed"
            loading="lazy">
          </iframe>
        </div>
      </div>
    </div>

    <a href="<?= BASE_URL ?>user/opentrip" class="btn">
      Pesan Sekarang
    </a>
  </div>

  <script>
    function changeImg(element) {
      document.querySelector('.hero').style.backgroundImage = 'url(' + element.src + ')';
    }
  </script>

</body>

</html>