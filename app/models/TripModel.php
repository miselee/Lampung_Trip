<?php

class TripModel
{

    public static function getAllForAdmin(mysqli $conn): array
    {
        $res = $conn->query("
            SELECT * FROM view_open_trip_admin
            ORDER BY created_at DESC
        ");

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function getAllAktif(mysqli $conn): array
    {
        $res = $conn->query("
            SELECT * FROM view_open_trip_aktif
            ORDER BY tanggal ASC
        ");

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function getAktifTerbaru(mysqli $conn, int $limit = 4): array
    {
        $stmt = $conn->prepare("
            SELECT *
            FROM view_open_trip_aktif
            WHERE status = 'aktif'
            ORDER BY tanggal ASC
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
            FROM view_trip_detail
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->bind_param('i', $id);

        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $data ?: null;
    }

    public static function getByDestinasiId(mysqli $conn, int $destinasi_id, int $limit = 3): array
    {
        $stmt = $conn->prepare("
            SELECT *
            FROM open_trip
            WHERE destinasi_id = ?
            AND status = 'aktif'
            ORDER BY tanggal ASC
            LIMIT ?
        ");

        $stmt->bind_param('ii', $destinasi_id, $limit);

        $stmt->execute();

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $data;
    }

    public static function getDestinasiList(mysqli $conn): array
    {
        $res = $conn->query("
            SELECT id, nama
            FROM destinasi
            ORDER BY nama ASC
        ");

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function getKuota(mysqli $conn, int $id): ?array
    {
        $stmt = $conn->prepare("
            SELECT kuota, peserta_terdaftar
            FROM open_trip
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->bind_param('i', $id);

        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $data ?: null;
    }

    public static function insert(mysqli $conn, array $data, string $foto): bool
    {
        $stmt = $conn->prepare("
            INSERT INTO open_trip
            (
                nama,
                destinasi_id,
                tanggal,
                durasi,
                harga,
                kuota,
                foto,
                deskripsi,
                fasilitas
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            'sisddiiss',
            $data['nama'],
            $data['destinasi_id'],
            $data['tanggal'],
            $data['durasi'],
            $data['harga'],
            $data['kuota'],
            $foto,
            $data['deskripsi'],
            $data['fasilitas']
        );

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    public static function delete(mysqli $conn, int $id): bool
    {
        $stmt = $conn->prepare("
            DELETE FROM open_trip
            WHERE id = ?
        ");

        $stmt->bind_param('i', $id);

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    public static function incrementPeserta(mysqli $conn, int $trip_id, int $jumlah): void
    {
        $stmt = $conn->prepare("
            UPDATE open_trip
            SET peserta_terdaftar = peserta_terdaftar + ?
            WHERE id = ?
        ");

        $stmt->bind_param('ii', $jumlah, $trip_id);

        $stmt->execute();

        $stmt->close();
    }

    public static function countAktif(mysqli $conn): int
    {
        $res = $conn->query("
            SELECT COUNT(*) AS total
            FROM open_trip
            WHERE status = 'aktif'
        ");

        return (int)($res->fetch_assoc()['total'] ?? 0);
    }
}
?>