<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Overview | Lampung Trip</title>
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/admin-overview.css">
</head>

<body>

  <div class="admin-wrapper">

    <aside class="sidebar">
      <div class="sidebar-logo">Lampung Trip</div>
      <nav class="sidebar-menu">
        <a href="admin.php" class="menu-item active">Overview</a>
        <a href="admin_destinasi.html" class="menu-item">Destinasi</a>
        <a href="admin_opentrip.html" class="menu-item">Open Trip</a>
        <a href="admin_pendaftaran.html" class="menu-item">
          Pendaftaran
          <span class="menu-badge">2</span>
        </a>
        <a href="admin_pembayaran.html" class="menu-item">
          Pembayaran
          <span class="menu-badge">2</span>
        </a>
      </nav>
      <a href="logout.php" class="sidebar-logout">Logout</a>
    </aside>

    <main class="admin-main">

      <div class="topbar">
        <span class="topbar-brand">Lampung Trip <span>Admin</span></span>
        <span class="topbar-page">Overview</span>
      </div>

      <div class="admin-content">

        <div class="admin-hero">
          <div class="hero-stat">
            <div class="hero-stat-num">5</div>
            <div class="hero-stat-label">Destinasi</div>
          </div>
          <div class="hero-divider"></div>
          <div class="hero-stat">
            <div class="hero-stat-num">4</div>
            <div class="hero-stat-label">Open Trip</div>
          </div>
          <div class="hero-divider"></div>
          <div class="hero-stat">
            <div class="hero-stat-num">2</div>
            <div class="hero-stat-label">Menunggu Validasi</div>
          </div>
          <div class="hero-divider"></div>
          <div class="hero-stat">
            <div class="hero-stat-num">2</div>
            <div class="hero-stat-label">Pembayaran Pending</div>
          </div>
        </div>

        <div class="overview-grid">

          <div class="list-card admin-box">
            <div class="section-title">Pendaftaran Terbaru</div>
            <div class="list-row">
              <div>
                <div class="list-row-name">Budi Santoso</div>
                <div class="list-row-sub">&bull; Pahawang &bull; 2 orang</div>
              </div>
              <span class="badge badge-pending">Menunggu</span>
            </div>
            <div class="list-row">
              <div>
                <div class="list-row-name">Dewi Rahayu</div>
                <div class="list-row-sub">&bull; Krakatau &bull; 3 orang</div>
              </div>
              <span class="badge badge-pending">Menunggu</span>
            </div>
            <div class="list-row">
              <div>
                <div class="list-row-name">Rina Kartika</div>
                <div class="list-row-sub">&bull; Krakatau &bull; 2 orang</div>
              </div>
              <span class="badge badge-approved">Disetujui</span>
            </div>
            <div class="list-row">
              <div>
                <div class="list-row-name">Hadi Wijaya</div>
                <div class="list-row-sub">&bull; Pahawang &bull; 4 orang</div>
              </div>
              <span class="badge badge-approved">Disetujui</span>
            </div>
          </div>

          <div class="list-card admin-box">
            <div class="section-title">Pembayaran Menunggu</div>
            <div class="list-row">
              <div>
                <div class="list-row-name">Budi Santoso</div>
                <div class="list-row-sub">&bull; Pahawang &bull; 01 Mei 2026</div>
              </div>
              <span class="badge badge-pending">Rp 700.000</span>
            </div>
            <div class="list-row">
              <div>
                <div class="list-row-name">Dewi Rahayu</div>
                <div class="list-row-sub">&bull; Krakatau &bull; 02 Mei 2026</div>
              </div>
              <span class="badge badge-pending">Rp 1.500.000</span>
            </div>
            <div class="list-row">
              <div>
                <div class="list-row-name">Rina Kartika</div>
                <div class="list-row-sub">&bull; Krakatau &bull; 28 Apr 2026</div>
              </div>
              <span class="badge badge-paid">Lunas</span>
            </div>
            <div class="list-row">
              <div>
                <div class="list-row-name">Hadi Wijaya</div>
                <div class="list-row-sub">&bull; Pahawang &bull; 29 Apr 2026</div>
              </div>
              <span class="badge badge-paid">Lunas</span>
            </div>
          </div>

          <div class="list-card admin-box">
            <div class="section-title">Upcoming Trips</div>
            <div class="trip-agenda-item">
              <div class="trip-dot dot-full"></div>
              <div class="trip-date">10 Mei</div>
              <div class="trip-name-text">Pahawang</div>
              <div class="trip-quota-text">8/15</div>
            </div>
            <div class="trip-agenda-item">
              <div class="trip-dot dot-available"></div>
              <div class="trip-date">17 Mei</div>
              <div class="trip-name-text">Krakatau</div>
              <div class="trip-quota-text">3/10</div>
            </div>
            <div class="trip-agenda-item">
              <div class="trip-dot dot-open"></div>
              <div class="trip-date">24 Mei</div>
              <div class="trip-name-text">Seminung</div>
              <div class="trip-quota-text">0/12</div>
            </div>
            <div class="trip-agenda-item">
              <div class="trip-dot dot-open"></div>
              <div class="trip-date">31 Mei</div>
              <div class="trip-name-text">Way Kambas</div>
              <div class="trip-quota-text">0/20</div>
            </div>
            <div style="margin-top:14px; font-size:12px; color:#aaa;">
              &bull; Hijau: Hampir penuh &nbsp;&bull; Kuning: Tersedia &nbsp;&bull; Abu: Baru dibuka
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>

</body>

</html>