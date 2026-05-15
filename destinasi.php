<?php
require 'config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM destinasi";

if (!empty($search)) {
    $sql .= " WHERE nama_destinasi LIKE ? OR lokasi LIKE ?";
    $stmt = $conn->prepare($sql);
    $keyword = "%$search%";
    $stmt->bind_param("ss", $keyword, $keyword);
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
    <title>Destinasi | Lampung Trip</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/destinasi.css">
</head>
<body>

<div class="navbar">
    <h2>Lampung Trip</h2>
    <div>
        <a href="user.php">Beranda</a>
        <a class="active" href="destinasi.php">Destinasi</a>
        <a href="open_trip.php">Open Trip</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="search">
    <form method="GET" action="destinasi.php">
        <input 
            type="text" 
            name="search" 
            id="search" 
            placeholder="Cari destinasi wisata..."
            value="<?= htmlspecialchars($search) ?>"
        >
    </form>
</div>

<div class="container" id="list">

<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <img src="img/<?= htmlspecialchars($row['gambar']) ?>" alt="gambar">

            <div class="card-body">
                <h3><?= htmlspecialchars($row['nama_destinasi']) ?></h3>
                <p><?= htmlspecialchars($row['lokasi']) ?></p>

                <div class="rating">
                    ⭐ <?= htmlspecialchars($row['rating']) ?>
                </div>

                <a class="btn" href="detail_destinasi.php?id=<?= $row['id'] ?>">
                    Lihat Detail
                </a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center; width:100%;">
        Destinasi tidak ditemukan.
    </p>
<?php endif; ?>

</div>

</body>
</html>