<?php
// app/models/PembayaranModel.php

class PembayaranModel
{
    public static function getAll(mysqli $conn): array
    {
        $res = $conn->query("
            SELECT *
            FROM view_pembayaran_admin
            ORDER BY created_at DESC
        ");

        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function getByPendaftaran(mysqli $conn, int $pendaftaran_id, int $user_id): ?array
    {
        $stmt = $conn->prepare("
            SELECT *
            FROM pembayaran
            WHERE pendaftaran_id = ? AND user_id = ?
            LIMIT 1
        ");

        $stmt->bind_param('ii', $pendaftaran_id, $user_id);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        return $data ?: null;
    }

    public static function existsByPendaftaran(mysqli $conn, int $pendaftaran_id): bool
    {
        $stmt = $conn->prepare("
            SELECT id
            FROM pembayaran
            WHERE pendaftaran_id = ?
            LIMIT 1
        ");

        $stmt->bind_param('i', $pendaftaran_id);
        $stmt->execute();

        $stmt->store_result();

        $exists = $stmt->num_rows > 0;

        $stmt->close();

        return $exists;
    }

    public static function insert(mysqli $conn, array $data): bool
    {
        $status = empty($data['bukti_transfer'])
        ? 'belum_upload'
        : 'menunggu';

        $stmt = $conn->prepare("
            INSERT INTO pembayaran
            (pendaftaran_id, user_id, open_trip_id, jumlah, bukti_transfer, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            'iiidss',
            $data['pendaftaran_id'],
            $data['user_id'],
            $data['open_trip_id'],
            $data['jumlah'],
            $data['bukti_transfer'],
            $status
        );

        $ok = $stmt->execute();

        $stmt->close();

        return $ok;
    }

    public static function updateBukti(mysqli $conn, int $pendaftaran_id, string $bukti): bool
    {
    $stmt = $conn->prepare("
        UPDATE pembayaran
        SET bukti_transfer=?,
            status='menunggu'
        WHERE pendaftaran_id=?
    ");

    $stmt->bind_param(
        "si",
        $bukti,
        $pendaftaran_id
    );

    $ok = $stmt->execute();

    $stmt->close();

    return $ok;
    }

    public static function updateStatus(mysqli $conn, int $pendaftaran_id, string $status): bool
    {
    $stmt = $conn->prepare("
        UPDATE pembayaran
        SET status=?
        WHERE pendaftaran_id=?
    ");

    $stmt->bind_param(
        "si",
        $status,
        $pendaftaran_id
    );

    $ok = $stmt->execute();

    $stmt->close();

    return $ok;
    }

    public static function countMenunggu(mysqli $conn): int
    {
        $res = $conn->query("
            SELECT total
            FROM view_pembayaran_menunggu
        ");

        return (int)($res->fetch_assoc()['total'] ?? 0);
    }

    public static function getMenunggu(mysqli $conn, int $limit = 5): array
    {
        $stmt = $conn->prepare("
            SELECT *
            FROM view_pembayaran_admin
            WHERE status = 'menunggu'
            ORDER BY created_at DESC
            LIMIT ?
        ");

        $stmt->bind_param('i', $limit);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $data;
    }

    public static function totalPendapatan(mysqli $conn): float
    {
        $res = $conn->query("
            SELECT total
            FROM view_total_pendapatan
        ");

        return (float)($res->fetch_assoc()['total'] ?? 0);
    }
}
?>