<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Pembayaran | Lampung Trip</title>

  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>

<div class="admin-wrapper">

    <aside class="sidebar">

      <div class="sidebar-logo">
        Lampung Trip
      </div>

      <nav class="sidebar-menu">

        <a href="<?= BASE_URL ?>admin/index" class="menu-item">Overview</a>
        <a href="<?= BASE_URL ?>admin/destinasi" class="menu-item">Destinasi</a>
        <a href="<?= BASE_URL ?>admin/opentrip" class="menu-item">Open Trip</a>
        <a href="<?= BASE_URL ?>admin/pendaftaran" class="menu-item">Pendaftaran</a>

        <a href="<?= BASE_URL ?>index.php?url=admin/pembayaran" class="menu-item active">

          Pembayaran

          <?php if ($count_menunggu > 0): ?>
            <span class="menu-badge">
              <?= $count_menunggu ?>
            </span>
          <?php endif; ?>

        </a>

      </nav>

      <a href="<?= BASE_URL ?>auth/logout" class="sidebar-logout">
        Logout
      </a>

    </aside>

    <main class="admin-main">

      <div class="topbar">
        <span class="topbar-brand">
          Lampung Trip <span>Admin</span>
        </span>

        <span class="topbar-page">
          Validasi Pembayaran
        </span>
      </div>

      <div class="admin-content">

        <?php if (isset($_SESSION['success'])): ?>

          <div class="flash flash-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
          </div>

          <?php unset($_SESSION['success']); ?>

        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>

          <div class="flash flash-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
          </div>

          <?php unset($_SESSION['error']); ?>

        <?php endif; ?>

        <div class="page-header">
          <h2>Validasi Pembayaran</h2>
        </div>

        <?php
        $menunggu = array_filter(
          $pembayaran,
          fn($p) => $p['status'] === 'menunggu'
        );

        $lunas = array_filter(
          $pembayaran,
          fn($p) => $p['status'] === 'lunas'
        );

        $ditolak = array_filter(
          $pembayaran,
          fn($p) => $p['status'] === 'ditolak'
        );
        ?>

        <div class="kanban">

          <div class="kanban-col admin-box">

            <div class="kanban-header pending">
              Menunggu

              <span class="kanban-count">
                <?= count($menunggu) ?>
              </span>
            </div>

            <?php foreach ($menunggu as $py): ?>

              <div class="reg-card top-pending">

                <div class="reg-name">
                  <?= htmlspecialchars($py['nama_user'] ?? $py['nama_lengkap'] ?? '-') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  <?= htmlspecialchars($py['nama_trip'] ?? '-') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  Rp <?= number_format($py['jumlah'], 0, ',', '.') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  <?= date('d M Y', strtotime($py['created_at'])) ?>
                </div>

                <?php if (!empty($py['bukti_transfer'])): ?>

                  <a href="<?= BASE_URL . htmlspecialchars($py['bukti_transfer']) ?>" target="_blank" class="btn-sm-bukti">

                    &#128247; Lihat Bukti

                  </a>

                <?php else: ?>

                  <span class="no-bukti">
                    Belum ada bukti
                  </span>

                <?php endif; ?>

                <div class="reg-actions">

                  <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/aksipembayaran" style="display:inline;"
                    onsubmit="return confirm('Konfirmasi pembayaran ini lunas?')">

                    <input type="hidden" name="id" value="<?= $py['id'] ?>">

                    <input type="hidden" name="aksi" value="lunas">

                    <button type="submit" class="btn-sm-approve">

                      &#10003; Konfirmasi Lunas

                    </button>

                  </form>

                  <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/aksipembayaran" style="display:inline;"
                    onsubmit="return confirm('Tolak pembayaran ini?')">

                    <input type="hidden" name="id" value="<?= $py['id'] ?>">

                    <input type="hidden" name="aksi" value="tolak">

                    <button type="submit" class="btn-sm-reject">

                      &#10005; Tolak

                    </button>

                  </form>

                </div>

              </div>

            <?php endforeach; ?>

            <?php if (empty($menunggu)): ?>

              <div class="empty-col">
                Tidak ada pembayaran menunggu
              </div>

            <?php endif; ?>

          </div>

          <div class="kanban-col admin-box">

            <div class="kanban-header approved">

              Lunas

              <span class="kanban-count">
                <?= count($lunas) ?>
              </span>

            </div>

            <?php foreach ($lunas as $py): ?>

              <div class="reg-card top-approved">

                <div class="reg-name">
                  <?= htmlspecialchars($py['nama_user'] ?? $py['nama_lengkap'] ?? '-') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  <?= htmlspecialchars($py['nama_trip'] ?? '-') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  Rp <?= number_format($py['jumlah'], 0, ',', '.') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  <?= date('d M Y', strtotime($py['created_at'])) ?>
                </div>

                <?php if (!empty($py['bukti_transfer'])): ?>

                  <a href="<?= BASE_URL . htmlspecialchars($py['bukti_transfer']) ?>" target="_blank" class="btn-sm-bukti">

                    &#128247; Lihat Bukti

                  </a>

                <?php endif; ?>

                <div style="margin-top:10px;">
                  <span class="badge badge-approved">
                    &#10003; Sudah Lunas
                  </span>
                </div>

              </div>

            <?php endforeach; ?>

            <?php if (empty($lunas)): ?>

              <div class="empty-col">
                Belum ada yang lunas
              </div>

            <?php endif; ?>

          </div>

          <div class="kanban-col admin-box">

            <div class="kanban-header rejected">

              Ditolak

              <span class="kanban-count">
                <?= count($ditolak) ?>
              </span>

            </div>

            <?php foreach ($ditolak as $py): ?>

              <div class="reg-card top-rejected">

                <div class="reg-name">
                  <?= htmlspecialchars($py['nama_user'] ?? $py['nama_lengkap'] ?? '-') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  <?= htmlspecialchars($py['nama_trip'] ?? '-') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  Rp <?= number_format($py['jumlah'], 0, ',', '.') ?>
                </div>

                <div class="reg-meta">
                  &#9632;
                  <?= date('d M Y', strtotime($py['created_at'])) ?>
                </div>

                <?php if (!empty($py['bukti_transfer'])): ?>

                  <a href="<?= BASE_URL . htmlspecialchars($py['bukti_transfer']) ?>" target="_blank" class="btn-sm-bukti">

                    &#128247; Lihat Bukti

                  </a>

                <?php endif; ?>

                <div style="margin-top:10px;">
                  <span class="badge badge-rejected">
                    &#10005; Ditolak
                  </span>
                </div>

              </div>

            <?php endforeach; ?>

            <?php if (empty($ditolak)): ?>

              <div class="empty-col">
                Tidak ada yang ditolak
              </div>

            <?php endif; ?>

          </div>

        </div>

      </div>
    </main>
  </div>

  <script src="<?= BASE_URL ?>assets/js/admin.js"></script>

</body>

</html>