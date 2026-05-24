<?php


class DestinasiModel
{
    public static function getAll(mysqli $conn): array
    {
        $res = $conn->query("
            SELECT *
            FROM view_destinasi_all
            ORDER BY created_at DESC
        ");

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function getRekomendasi(mysqli $conn, int $limit = 5): array
    {
        $stmt = $conn->prepare("
            SELECT *
            FROM view_destinasi_rekomendasi
            LIMIT ?
        ");

        $stmt->bind_param('i', $limit);

        $stmt->execute();

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $data;
    }

    public static function getById(mysqli $conn, int $id): ?array
    {
        $stmt = $conn->prepare("
            SELECT *
            FROM destinasi
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->bind_param('i', $id);

        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $data ?: null;
    }

    public static function search(mysqli $conn, string $keyword): array
    {
        $stmt = $conn->prepare("
            SELECT *
            FROM destinasi
            WHERE nama LIKE ?
            OR lokasi LIKE ?
            ORDER BY rating DESC
        ");

        $like = '%' . $keyword . '%';

        $stmt->bind_param('ss', $like, $like);

        $stmt->execute();

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $data;
    }

    public static function insert(mysqli $conn, array $data, string $foto): bool
    {
        $stmt = $conn->prepare("
            INSERT INTO destinasi
            (nama, lokasi, kategori, foto, deskripsi)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            'sssss',
            $data['nama'],
            $data['lokasi'],
            $data['kategori'],
            $foto,
            $data['deskripsi']
        );

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    public static function update(mysqli $conn, int $id, array $data, ?string $foto = null): bool
    {
        if ($foto) {

            $stmt = $conn->prepare("
                UPDATE destinasi
                SET nama = ?, lokasi = ?, kategori = ?, foto = ?, deskripsi = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                'sssssi',
                $data['nama'],
                $data['lokasi'],
                $data['kategori'],
                $foto,
                $data['deskripsi'],
                $id
            );

        } else {

            $stmt = $conn->prepare("
                UPDATE destinasi
                SET nama = ?, lokasi = ?, kategori = ?, deskripsi = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                'ssssi',
                $data['nama'],
                $data['lokasi'],
                $data['kategori'],
                $data['deskripsi'],
                $id
            );
        }

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    public static function delete(mysqli $conn, int $id): bool
    {
        $stmt = $conn->prepare("
            DELETE FROM destinasi
            WHERE id = ?
        ");

        $stmt->bind_param('i', $id);

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    public static function count(mysqli $conn): int
    {
        $res = $conn->query("
            SELECT total
            FROM view_total_destinasi
        ");

        return (int)($res->fetch_assoc()['total'] ?? 0);
    }
}
?>