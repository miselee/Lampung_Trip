<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — Destinasi | Lampung Trip</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>

  <div class="admin-wrapper">

    <aside class="sidebar">
      <div class="sidebar-logo">Lampung Trip</div>
      <nav class="sidebar-menu">
        <a href="<?= BASE_URL ?>admin/index" class="menu-item">Overview</a>
        <a href="<?= BASE_URL ?>admin/destinasi" class="menu-item active">Destinasi</a>
        <a href="<?= BASE_URL ?>admin/opentrip" class="menu-item">Open Trip</a>
        <a href="<?= BASE_URL ?>admin/pendaftaran" class="menu-item">Pendaftaran <span class="menu-badge"><?= $stat_pending ?? 0 ?></span></a>
        <a href="<?= BASE_URL ?>admin/pembayaran" class="menu-item">Pembayaran <span class="menu-badge"><?= $count_menunggu ?? 0 ?></span></a>
      </nav>
      <a href="<?= BASE_URL ?>auth/logout" class="sidebar-logout">Logout</a>
    </aside>

    <main class="admin-main">
      <div class="topbar">
        <span class="topbar-brand">Lampung Trip <span>Admin</span></span>
        <span class="topbar-page">Kelola Destinasi</span>
      </div>

      <div class="admin-content">

        <?php if (isset($_SESSION['success'])): ?>
          <div class="alert" style="background:#d1fae5;color:#065f46;padding:10px 14px;border-radius:6px;margin-bottom:14px;">
            <?= htmlspecialchars($_SESSION['success']) ?>
          </div>
          <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="page-header">
          <h2>Daftar Destinasi</h2>
          <a href="#modal-tambah-destinasi" class="btn-primary">+ Tambah Destinasi</a>
        </div>

        <div class="admin-table-wrapper admin-box">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Foto</th>
                <th>Nama Destinasi</th>
                <th>Lokasi</th>
                <th>Kategori</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($destinasi)): ?>
                <?php foreach ($destinasi as $d): ?>
                <tr>
                  <td><img class="table-img" src="<?= BASE_URL ?>assets/img/<?= htmlspecialchars($d['foto']) ?>" alt="<?= htmlspecialchars($d['nama']) ?>"></td>
                  <td><?= htmlspecialchars($d['nama']) ?></td>
                  <td><?= htmlspecialchars($d['lokasi']) ?></td>
                  <td><span class="badge badge-aktif"><?= htmlspecialchars($d['kategori'] ?? '-') ?></span></td>
                  <td>
                    <div class="action-btns">
                      <form method="POST" action="<?= BASE_URL ?>admin/hapusdestinasi" onsubmit="return confirm('Hapus destinasi ini?')" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                        <button type="submit" class="btn-danger">Hapus</button>
                      </form>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" style="text-align:center;padding:30px;color:#888;">Belum ada destinasi.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

      </div>
    </main>
  </div>

  <div id="modal-tambah-destinasi" class="modal-overlay">
    <div class="modal-box modal-md">
      <div class="modal-header">Tambah Destinasi Baru</div>

      <form method="POST" action="<?= BASE_URL ?>admin/tambahdestinasi" enctype="multipart/form-data">

        <div class="form-group">
          <label>Foto Destinasi</label>
          <div class="file-upload-box">
            <input type="file" name="foto" accept="image/*" style="display:none;" id="fotoInput" onchange="document.getElementById('fotoLabel').textContent=this.files[0].name">
            <button type="button" onclick="document.getElementById('fotoInput').click()">Pilih File</button>
            <span id="fotoLabel">Belum ada file yang dipilih</span>
          </div>
        </div>

        <div class="form-group">
          <label>Nama Destinasi</label>
          <input type="text" name="nama" class="form-input" placeholder="Masukkan nama destinasi" required>
        </div>

        <div class="form-group">
          <label>Lokasi</label>
          <input type="text" name="lokasi" class="form-input" placeholder="Contoh: Pesawaran" required>
        </div>

        <div class="form-group">
          <label>Kategori</label>
          <select name="kategori" class="form-input">
            <option value="Pantai">Pantai</option>
            <option value="Gunung">Gunung</option>
            <option value="Taman Nasional">Taman Nasional</option>
            <option value="Pulau">Pulau</option>
          </select>
        </div>

        <div class="form-group">
          <label>Deskripsi</label>
          <textarea name="deskripsi" class="form-input" rows="3" placeholder="Deskripsi singkat destinasi"></textarea>
        </div>

        <div class="modal-footer">
          <a href="<?= BASE_URL ?>admin/destinasi" class="btn-cancel">Batal</a>
          <button type="submit" class="btn-primary">Simpan Destinasi</button>
        </div>

      </form>
    </div>
  </div>

<script src="<?= BASE_URL ?>assets/js/admin.js"></script>

</body>

</html>