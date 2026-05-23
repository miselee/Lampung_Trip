<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Open Trip | Lampung Trip</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-destinasi.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/admin-opentrip.css">
</head>

<body>

  <div class="admin-wrapper">

    <aside class="sidebar">
      <div class="sidebar-logo">Lampung Trip</div>
      <nav class="sidebar-menu">
        <a href="<?= BASE_URL ?>admin/index" class="menu-item">Overview</a>
        <a href="<?= BASE_URL ?>admin/destinasi" class="menu-item">Destinasi</a>
        <a href="<?= BASE_URL ?>admin/opentrip" class="menu-item active">Open Trip</a>
        <a href="<?= BASE_URL ?>admin/pendaftaran" class="menu-item">Pendaftaran <span class="menu-badge"><?= $stat_pending ?? 0 ?></span></a>
        <a href="<?= BASE_URL ?>admin/pembayaran" class="menu-item">Pembayaran</a>
      </nav>
      <a href="<?= BASE_URL ?>auth/logout" class="sidebar-logout">Logout</a>
    </aside>

    <main class="admin-main">
      <div class="topbar">
        <span class="topbar-brand">Lampung Trip <span>Admin</span></span>
        <span class="topbar-page">Kelola Open Trip</span>
      </div>

      <div class="admin-content">

        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert" style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:6px;margin-bottom:14px;">
            <?= htmlspecialchars($_SESSION['success']) ?>
          </div>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="page-header">
          <h2>Daftar Open Trip</h2>
          <a href="#modal-tambah-trip" class="btn-primary">+ Tambah Trip</a>
        </div>

        <div class="trip-grid">

          <?php if (!empty($open_trips)): ?>
            <?php foreach ($open_trips as $trip): ?>
            <div class="trip-card">
              <img class="trip-card-img" src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($trip['foto']) ?>" alt="<?= htmlspecialchars($trip['nama']) ?>">
              <div class="trip-card-body">
                <div class="trip-card-title"><?= htmlspecialchars($trip['nama']) ?></div>
                <div class="trip-card-meta">&bull; <?= date('d M Y', strtotime($trip['tanggal'])) ?></div>
                <div class="trip-card-meta">&bull; <?= $trip['peserta_terdaftar'] ?>/<?= $trip['kuota'] ?> peserta</div>
                <div class="trip-card-meta">&bull; Rp <?= number_format($trip['harga'], 0, ',', '.') ?> / orang</div>
                <div class="trip-card-footer">
                  <?php
                    $badgeMap = ['aktif'=>'badge-aktif','penuh'=>'badge-rejected','selesai'=>'badge-selesai'];
                    $labMap   = ['aktif'=>'Aktif','penuh'=>'Penuh','selesai'=>'Selesai'];
                  ?>
                  <span class="badge <?= $badgeMap[$trip['status']] ?? 'badge-aktif' ?>"><?= $labMap[$trip['status']] ?? ucfirst($trip['status']) ?></span>
                  <div class="action-btns">
                    <form method="POST" action="<?= BASE_URL ?>admin/hapustrip" onsubmit="return confirm('Hapus trip ini?')" style="display:inline;">
                      <input type="hidden" name="id" value="<?= $trip['id'] ?>">
                      <button type="submit" class="btn-danger">Hapus</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p style="text-align:center;color:#888;padding:40px 0;width:100%;">Belum ada open trip.</p>
          <?php endif; ?>

        </div>

      </div>
    </main>
  </div>

  <div id="modal-tambah-trip" class="modal-overlay">
    <div class="modal-box modal-lg">
      <div class="modal-header">Tambah Open Trip Baru</div>

      <form method="POST" action="<?= BASE_URL ?>admin/tambahtrip" enctype="multipart/form-data">

        <div class="form-row">

          <div class="form-col">
            <div class="form-group">
              <label>Nama Trip</label>
              <input type="text" name="nama" class="form-input" placeholder="Contoh: Open Trip Pahawang" required>
            </div>

            <div class="form-group">
              <label>Destinasi Utama</label>
              <select name="destinasi_id" class="form-input" required>
                <option value="">-- Pilih Destinasi --</option>
                <?php foreach ($destinasi as $d): ?>
                  <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nama']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Tanggal Keberangkatan</label>
              <input type="date" name="tanggal" class="form-input" required>
            </div>

            <div class="form-group">
              <label>Kuota Peserta Maksimal</label>
              <input type="number" name="kuota" class="form-input" placeholder="Contoh: 15" min="1" required>
            </div>
          </div>

          <div class="form-col">
            <div class="form-group">
              <label>Harga per Orang (Rp)</label>
              <input type="number" name="harga" class="form-input" placeholder="Contoh: 350000" min="0" required>
            </div>

            <div class="form-group">
              <label>Foto Banner Trip</label>
              <div class="file-upload-box">
                <input type="file" name="foto" accept="image/*" style="display:none;" id="fotoTrip" onchange="document.getElementById('fotoTripLabel').textContent=this.files[0].name">
                <button type="button" onclick="document.getElementById('fotoTrip').click()">Pilih File</button>
                <span id="fotoTripLabel">Belum ada file yang dipilih</span>
              </div>
            </div>

            <div class="form-group">
              <label>Durasi</label>
              <input type="text" name="durasi" class="form-input" placeholder="Contoh: 2 Hari 1 Malam" value="1 hari">
            </div>

            <div class="form-group">
              <label>Fasilitas &amp; Deskripsi Singkat</label>
              <textarea name="deskripsi" class="form-input" rows="4"
                placeholder="Sebutkan fasilitas seperti: Snorkeling, Makan siang, dll"></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <a href="<?= BASE_URL ?>admin/opentrip" class="btn-cancel">Batal</a>
          <button type="submit" class="btn-primary">Simpan Trip</button>
        </div>

      </form>
    </div>
  </div>

<script src="<?= BASE_URL ?>assets/js/admin.js"></script>

</body>

</html>