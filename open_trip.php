<?php
require 'config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "
    SELECT 
        ot.*,
        d.nama_destinasi,
        d.gambar
    FROM open_trip ot
    JOIN destinasi d ON ot.destinasi_id = d.id
";

if (!empty($search)) {
    $sql .= " WHERE d.nama_destinasi LIKE ?";
    $stmt = $conn->prepare($sql);
    $keyword = "%$search%";
    $stmt->bind_param("s", $keyword);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Trip | Lampung Trip</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/open_trip.css">
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

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="gambar">

            <div class="card-body">
                <h3>
                    Open Trip <?= htmlspecialchars($row['nama_destinasi']) ?>
                </h3>

                <p>
                    <?= date('d F Y', strtotime($row['tanggal_mulai'])) ?>
                    -
                    <?= date('d F Y', strtotime($row['tanggal_selesai'])) ?>
                </p>

                <b>
                    Rp <?= number_format($row['harga'], 0, ',', '.') ?>
                </b> /orang

                <p>
                    Kuota:
                    <?= htmlspecialchars($row['kuota_terisi']) ?>/<?= htmlspecialchars($row['kuota']) ?>
                </p>

                <a class="btn" href="detail_trip.php?id=<?= $row['id'] ?>">
                    Lihat Detail
                </a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center; width:100%;">
        Open trip belum tersedia.
    </p>
<?php endif; ?>

</div>

</body>
</html>