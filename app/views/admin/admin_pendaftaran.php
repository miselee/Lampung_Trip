<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Pendaftaran | Lampung Trip</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-pendaftaran.css">
</head>

<body>

  <div class="admin-wrapper">

    <aside class="sidebar">
      <div class="sidebar-logo">Lampung Trip</div>
      <nav class="sidebar-menu">
        <a href="<?= BASE_URL ?>index.php?url=admin/index" class="menu-item">Overview</a>
        <a href="<?= BASE_URL ?>index.php?url=admin/destinasi" class="menu-item">Destinasi</a>
        <a href="<?= BASE_URL ?>index.php?url=admin/opentrip" class="menu-item">Open Trip</a>
        <a href="<?= BASE_URL ?>index.php?url=admin/pendaftaran" class="menu-item active">Pendaftaran <span
            class="menu-badge"><?= $count_menunggu ?></span></a>
        <a href="<?= BASE_URL ?>index.php?url=admin/pembayaran" class="menu-item">Pembayaran <span
            class="menu-badge">0</span></a>
      </nav>
      <a href="<?= BASE_URL ?>auth/logout" class="sidebar-logout">Logout</a>
    </aside>

    <main class="admin-main">
      <div class="topbar">
        <span class="topbar-brand">Lampung Trip <span>Admin</span></span>
        <span class="topbar-page">Validasi Pendaftaran</span>
      </div>

      <div class="admin-content">

        <div class="page-header">
          <h2>Validasi Pendaftaran</h2>
        </div>

        <div class="kanban">
          <?php
          $pending = array_filter($pendaftaran, function ($p) {
            return $p['status'] === 'menunggu';
          });
          $approved = array_filter($pendaftaran, function ($p) {
            return $p['status'] === 'disetujui';
          });
          $rejected = array_filter($pendaftaran, function ($p) {
            return $p['status'] === 'ditolak';
          });
          ?>

          <div class="kanban-col admin-box">
            <div class="kanban-header pending">
              Menunggu
              <span class="kanban-count"><?= count($pending) ?></span>
            </div>

            <?php foreach ($pending as $p): ?>
              <div class="reg-card top-pending">
                <div class="reg-name"><?= htmlspecialchars($p['nama_lengkap'] ?? '') ?></div>
                <div class="reg-meta">&bull; <?= htmlspecialchars($p['nama_trip'] ?? '') ?></div>
                <div class="reg-meta">&bull; <?= htmlspecialchars($p['jumlah_orang'] ?? 1) ?> orang</div>
                <div class="reg-meta">&bull; <?= date('d M Y', strtotime($p['created_at'] ?? '')) ?></div>
                <div class="reg-actions">
                  <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/aksipendaftaran" style="display:inline;">
                    <input type="hidden" name="pendaftaran_id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="aksi" value="disetujui">
                    <button type="submit" class="btn-sm-approve">Setujui</button>
                  </form>
                  <form method="POST" action="<?= BASE_URL ?>index.php?url=admin/aksipendaftaran" style="display:inline;">
                    <input type="hidden" name="pendaftaran_id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="aksi" value="ditolak">
                    <input type="text" name="alasan_tolak" placeholder="Alasan (optional)" style="display:none;"
                      id="alasan_<?= $p['id'] ?>">
                    <button type="button" class="btn-sm-reject" onclick="toggleRejectForm(<?= $p['id'] ?>)">Tolak</button>
                    <button type="submit" style="display:none;" id="submit_<?= $p['id'] ?>">Kirim</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>

            <?php if (count($pending) === 0): ?>
              <div style="padding: 20px; text-align: center; color: #999;">Tidak ada pendaftaran menunggu</div>
            <?php endif; ?>
          </div>

          <div class="kanban-col admin-box">
            <div class="kanban-header approved">
              Disetujui
              <span class="kanban-count"><?= count($approved) ?></span>
            </div>

            <?php foreach ($approved as $p): ?>
              <div class="reg-card top-approved">
                <div class="reg-name"><?= htmlspecialchars($p['nama_lengkap'] ?? '') ?></div>
                <div class="reg-meta">&bull; <?= htmlspecialchars($p['nama_trip'] ?? '') ?></div>
                <div class="reg-meta">&bull; <?= htmlspecialchars($p['jumlah_orang'] ?? 1) ?> orang</div>
                <div class="reg-meta">&bull; <?= date('d M Y', strtotime($p['created_at'] ?? '')) ?></div>
                <div style="margin-top:10px;">
                  <span class="badge badge-approved">Sudah Disetujui</span>
                </div>
              </div>
            <?php endforeach; ?>

            <?php if (count($approved) === 0): ?>
              <div style="padding: 20px; text-align: center; color: #999;">Tidak ada pendaftaran disetujui</div>
            <?php endif; ?>
          </div>

          <div class="kanban-col admin-box">
            <div class="kanban-header rejected">
              Ditolak
              <span class="kanban-count"><?= count($rejected) ?></span>
            </div>

            <?php foreach ($rejected as $p): ?>
              <div class="reg-card top-rejected">
                <div class="reg-name"><?= htmlspecialchars($p['nama_lengkap'] ?? '') ?></div>
                <div class="reg-meta">&bull; <?= htmlspecialchars($p['nama_trip'] ?? '') ?></div>
                <div class="reg-meta">&bull; <?= htmlspecialchars($p['jumlah_orang'] ?? 1) ?> orang</div>
                <div class="reg-meta">&bull; <?= date('d M Y', strtotime($p['created_at'] ?? '')) ?></div>
                <div style="margin-top:10px;">
                  <span class="badge badge-rejected">Ditolak</span>
                </div>
              </div>
            <?php endforeach; ?>

            <?php if (count($rejected) === 0): ?>
              <div style="padding: 20px; text-align: center; color: #999;">Tidak ada pendaftaran ditolak</div>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </main>
  </div>

</body>

<script>
  function toggleRejectForm(id) {
    const alasanInput = document.getElementById('alasan_' + id);
    const submitBtn = document.getElementById('submit_' + id);
    if (alasanInput.style.display === 'none') {
      alasanInput.style.display = 'inline-block';
      submitBtn.style.display = 'inline-block';
    } else {
      alasanInput.style.display = 'none';
      submitBtn.style.display = 'none';
    }
  }
</script>

</html>