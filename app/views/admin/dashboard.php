<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Overview | Lampung Trip</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

  <div class="admin-wrapper">

   <aside class="sidebar">
        <div class="sidebar-logo">
            Lampung Trip
        </div>

        <nav class="sidebar-menu">

            <a href="<?= BASE_URL ?>admin/index" class="menu-item active">
                <i class="fa-solid fa-chart-line"></i>
                Overview
            </a>

            <a href="<?= BASE_URL ?>admin/destinasi" class="menu-item">
                <i class="fa-solid fa-location-dot"></i>
                Destinasi
            </a>

            <a href="<?= BASE_URL ?>admin/opentrip" class="menu-item">
                <i class="fa-solid fa-route"></i>
                Open Trip
            </a>

            <a href="<?= BASE_URL ?>admin/pendaftaran" class="menu-item">
                <i class="fa-solid fa-user-check"></i>
                Pendaftaran
                <span class="menu-badge"><?= $stat_pending ?></span>
            </a>

            <a href="<?= BASE_URL ?>admin/pembayaran" class="menu-item">
                <i class="fa-solid fa-credit-card"></i>
                Pembayaran
                <span class="menu-badge"><?= $count_menunggu_bayar ?? 0 ?></span>
            </a>

        </nav>

        <a href="<?= BASE_URL ?>auth/logout" class="sidebar-logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>
    </aside>

    <main class="admin-main">

      <div class="topbar">
        <span class="topbar-brand">Lampung Trip <span>Admin</span></span>
        <span class="topbar-page">Overview</span>
      </div>

      <div class="admin-content">

        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert" style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:6px;margin-bottom:14px;">
            <?= htmlspecialchars($_SESSION['success']) ?>
          </div>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="admin-hero">
          <div class="hero-stat">
            <div class="hero-stat-num"><?= $stat_destinasi ?></div>
            <div class="hero-stat-label">Destinasi</div>
          </div>
          <div class="hero-divider"></div>
          <div class="hero-stat">
            <div class="hero-stat-num"><?= $stat_trip_aktif ?></div>
            <div class="hero-stat-label">Open Trip</div>
          </div>
          <div class="hero-divider"></div>
          <div class="hero-stat">
            <div class="hero-stat-num"><?= $stat_pending ?></div>
            <div class="hero-stat-label">Menunggu Validasi</div>
          </div>
          <div class="hero-divider"></div>
          <div class="hero-stat">
            <div class="hero-stat-num"><?= $stat_pendaftar ?></div>
            <div class="hero-stat-label">Pendaftar Bulan Ini</div>
          </div>
        </div>

        <div class="overview-grid">

          <div class="list-card admin-box">
            <div class="section-title">Pendaftaran Terbaru</div>
            <?php if (!empty($pendaftaran_terbaru)): ?>
              <?php foreach ($pendaftaran_terbaru as $p): ?>
              <div class="list-row">
                <div>
                  <div class="list-row-name"><?= htmlspecialchars($p['nama_lengkap']) ?></div>
                  <div class="list-row-sub">&bull; <?= htmlspecialchars($p['nama_trip'] ?? '-') ?> &bull; <?= $p['jumlah_orang'] ?> orang</div>
                </div>
                <?php
                  $badge = ['menunggu'=>'badge-pending','disetujui'=>'badge-approved','ditolak'=>'badge-rejected'];
                  $label = ['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak'];
                ?>
                <span class="badge <?= $badge[$p['status']] ?? 'badge-pending' ?>"><?= $label[$p['status']] ?? $p['status'] ?></span>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div style="color:#aaa;font-size:13px;text-align:center;padding:16px 0;">Belum ada pendaftaran.</div>
            <?php endif; ?>
          </div>

          <div class="list-card admin-box">
            <div class="section-title">Pembayaran Menunggu</div>
            <?php if (!empty($pembayaran_terbaru)): ?>
              <?php foreach ($pembayaran_terbaru as $py): ?>
              <div class="list-row">
                <div>
                  <div class="list-row-name"><?= htmlspecialchars($py['nama_user'] ?? $py['nama_lengkap'] ?? '-') ?></div>
                  <div class="list-row-sub">&bull; <?= htmlspecialchars($py['nama_trip'] ?? '-') ?> &bull; <?= date('d M Y', strtotime($py['created_at'])) ?></div>
                </div>
                <span class="badge badge-pending">Rp <?= number_format($py['jumlah'], 0, ',', '.') ?></span>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div style="color:#aaa;font-size:13px;text-align:center;padding:16px 0;">Tidak ada pembayaran menunggu.</div>
            <?php endif; ?>
          </div>

          <div class="list-card admin-box">
            <div class="section-title">Upcoming Trips</div>
            <?php if (!empty($upcoming_trips)): ?>
              <?php foreach ($upcoming_trips as $ut): ?>
                <?php
                  $rasio = $ut['kuota'] > 0 ? $ut['peserta_terdaftar'] / $ut['kuota'] : 0;
                  $dot = $rasio >= 0.8 ? 'dot-full' : ($rasio >= 0.3 ? 'dot-available' : 'dot-open');
                ?>
              <div class="trip-agenda-item">
                <div class="trip-dot <?= $dot ?>"></div>
                <div class="trip-date"><?= date('d M', strtotime($ut['tanggal'])) ?></div>
                <div class="trip-name-text"><?= htmlspecialchars($ut['nama']) ?></div>
                <div class="trip-quota-text"><?= $ut['peserta_terdaftar'] ?>/<?= $ut['kuota'] ?></div>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div style="color:#aaa;font-size:13px;text-align:center;padding:16px 0;">Tidak ada trip mendatang.</div>
            <?php endif; ?>
            <div style="margin-top:14px; font-size:12px; color:#aaa;">
              &bull; Hijau: Hampir penuh &nbsp;&bull; Kuning: Tersedia &nbsp;&bull; Abu: Baru dibuka
            </div>
          </div>

        </div>
      </div>
    </main>
  </div>

<script src="<?= BASE_URL ?>assets/js/admin.js"></script>

</body>

</html>